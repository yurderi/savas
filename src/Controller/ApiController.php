<?php

declare(strict_types=1);

namespace App\Controller;

use App\Component\Api\ModelChain;
use App\Component\Api\ModelInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ApiController
 *
 * @package App\Controller
 *
 * This api controller is used to work with models which implements \App\Component\Api\ModelInterface.
 */
class ApiController
{
    /**
     * @var ModelChain
     */
    private $models;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        ModelChain $models,
        EntityManagerInterface $entityManager
    ) {
        $this->models        = $models;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/api/v1/search/{entity}", methods={"GET"})
     *
     * @param Request $request
     * @param string  $entity The entity to search for.
     *
     * @return Response
     * @throws \Exception
     */
    public function search(Request $request, string $entity): Response
    {
        $className  = $this->models->getModelClass($entity);
        $repository = $this->entityManager->getRepository($className);

        $offset       = (int)$request->get('offset', 0);
        $limit        = (int)$request->get('limit', 100);
        $orderBy      = $request->get('order', [ 'created' => 'ASC' ]);
        $criteria     = $request->get('filter', []);
        $associations = $request->get('associations', []);

        $models = $repository->findBy($criteria, $orderBy, $limit, $offset);

        $associations = $this->prepareAssociations($associations);
        $rows         = $this->hydrateModels($models, $className, $associations);

        $schema          = $this->entityManager->getClassMetadata($className);
        $entityPersister = $this->entityManager->getUnitOfWork()->getEntityPersister($schema->name);
        $total           = $entityPersister->count($criteria);

        return new JsonResponse(
            [
                'total' => $total,
                'data'  => $rows,
            ]
        );
    }

    /**
     * @Route("/api/v1/create/{entity}", methods={"POST"})
     *
     * @param Request $request
     * @param string  $entity
     *
     * @return Response
     * @throws \Exception
     */
    public function create(Request $request, string $entity): Response
    {
        $className = $this->models->getModelClass($entity);
        /** @var ModelInterface $model */
        $model = new $className();

        $this->mapRequestToModel($request, $model, $className);

        $this->entityManager->persist($model);
        $this->entityManager->flush();

        return new JsonResponse(
            $this->hydrateModel($model, $className)
        );
    }

    /**
     * @Route("/api/v1/upsert/{entity}", methods={"PUT"})
     *
     * @param Request $request
     * @param string  $entity
     *
     * @return Response
     * @throws \Exception
     */
    public function upsert(Request $request, string $entity): Response
    {
        $className = $this->models->getModelClass($entity);
        $id        = $request->get('id');
        $model     = null;

        if (null !== $id) {
            $model = $this->entityManager->find($className, $id);
        }

        if (null === $model) {
            /** @var ModelInterface $model */
            $model = new $className();
        }

        $this->mapRequestToModel($request, $model, $className);

        $this->entityManager->persist($model);
        $this->entityManager->flush();

        return new JsonResponse(
            $this->hydrateModel($model, $className)
        );
    }

    /**
     * @Route("/api/v1/update/{entity}", methods={"PATCH"})
     *
     * @param Request $request
     * @param string  $entity
     *
     * @return Response
     * @throws \Exception
     */
    public function update(Request $request, string $entity): Response
    {
        $className = $this->models->getModelClass($entity);
        $id        = $request->get('id');
        /** @var ModelInterface $model */
        $model = $this->entityManager->find($className, $id);

        if (null === $model) {
            throw new \Exception('Model by id not found.');
        }

        $this->mapRequestToModel($request, $model, $className);

        $this->entityManager->persist($model);
        $this->entityManager->flush();

        return new JsonResponse(
            $this->hydrateModel($model, $className)
        );
    }

    /**
     * @Route("/api/v1/delete/{entity}", methods={"DELETE"})
     *
     * @param Request $request
     * @param string  $entity
     *
     * @return Response
     * @throws \Exception
     */
    public function delete(Request $request, string $entity): Response
    {
        $className = $this->models->getModelClass($entity);
        $id        = $request->get('id');
        /** @var ModelInterface $model */
        $model = $this->entityManager->find($className, $id);

        if (null === $model) {
            throw new \Exception('Model by id not found.');
        }

        $this->entityManager->remove($model);
        $this->entityManager->flush();

        return new Response();
    }

    private function mapRequestToModel(Request $request, ModelInterface $model, string $className): void
    {
        $schema = $this->entityManager->getClassMetadata($className);

        foreach ($schema->fieldMappings as $fieldName => $mapping) {
            if (isset($mapping['id']) && true === $mapping['id']) {
                $id = $request->get('id', null);

                if (null === $id) {
                    $id = Uuid::uuid4();
                } else {
                    $id = Uuid::fromString($id);
                }

                $model->setId($id);

                continue;
            }

            $value = $request->get($fieldName);

            if (null === $value) {
                if ('created' === $fieldName) {
                    $model->setCreated(new \DateTime());
                }

                if ('changed' === $fieldName) {
                    $model->setChanged(new \DateTime());
                }

                continue;
            }

            $model->{'set' . ucfirst($fieldName)}($value);
        }
    }

    private function prepareAssociations(array $associations): array
    {
        $structure = [];

        foreach ($associations as $association) {
            $tree      = array_reverse(explode('.', $association));
            $current   = [];
            $firstNode = array_pop($tree);

            foreach ($tree as $node) {
                $current = [ $node => $current ];
            }

            $structure[$firstNode] = array_merge_recursive($current, $structure[$firstNode] ?? []);
        }

        return $structure;
    }

    private function hydrateModel(ModelInterface $model, string $className, array $associations = null): array
    {
        $schema = $this->entityManager->getClassMetadata($className);
        $result = [];

        // Map fields
        foreach ($schema->fieldMappings as $fieldName => $mapping) {
            $type  = $mapping['type'] === 'boolean' ? 'is' : 'get';
            $value = $model->{$type . ucfirst($fieldName)}();

            if ($value instanceof \DateTime) {
                $value = $value->getTimestamp();
            }

            $result[$fieldName] = $value;
        }

        // Map associated entities
        if (is_array($associations)) {
            foreach ($associations as $association => $children) {
                $mapping = $schema->getAssociationMapping($association);
                $value   = $model->{'get' . ucfirst($association)}();

                if ($value instanceof Collection) {
                    $value = $this->hydrateModels(
                        $value->toArray(),
                        $mapping['targetEntity'],
                        $children
                    );
                } else {
                    $value = $this->hydrateModel(
                        $value,
                        $mapping['targetEntity'],
                        $children
                    );
                }

                $result[$association] = $value;
            }
        }

        return $result;
    }

    private function hydrateModels(array $models, string $className, array $associations = null): array
    {
        return array_map(
            function ($model) use ($className, $associations) {
                return $this->hydrateModel($model, $className, $associations);
            },
            $models
        );
    }
}