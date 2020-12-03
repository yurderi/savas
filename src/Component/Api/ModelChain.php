<?php

declare(strict_types=1);

namespace App\Component\Api;

use Doctrine\ORM\EntityManagerInterface;

class ModelChain
{
    /**
     * @var string[]
     */
    protected $models;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->models        = [];
        $this->entityManager = $entityManager;
    }

    public function registerModel(string $className): void
    {
        $this->models[] = $className;
    }

    public function getModelClass(string $entityName): string
    {
        foreach ($this->models as $className) {
            $metadata = $this->entityManager->getClassMetadata($className);

            if ($entityName === $metadata->getTableName()) {
                return $className;
            }
        }

        throw new \Exception('Entity not found.');
    }
}