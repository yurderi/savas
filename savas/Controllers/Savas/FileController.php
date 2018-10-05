<?php

namespace CMS\Controllers\Savas;

use Favez\ORM\Entity\Entity;
use Favez\ORM\Entity\Repository;
use savas\Components\Controllers\API;
use savas\Models\Savas\Application\Application;
use savas\Models\Savas\Application\File;
use savas\Models\Savas\Application\Release;
use Slim\Http\UploadedFile;

class FileController extends API
{

    public function configure()
    {
        return [
            'model' => File::class
        ];
    }

    protected function setDefaultValues(Entity $entity)
    {
        $entity->created = date('Y-m-d H:i:s');
        $entity->size    = 0;
    }

    protected function setValues(Entity $entity, $input)
    {
        $entity->releaseID        = $input['releaseID'];
        $entity->platformID       = $input['platformID'];
        $entity->originalFilename = $input['originalFilename']; // . '_' . md5($input['filename'] . uniqid());
        $entity->changed          = date('Y-m-d H:i:s');

        $files = self::request()->getUploadedFiles();

        if (isset($files['file']))
        {
            /** @var UploadedFile $file */
            $file = $files['file'];
            $name = md5(uniqid() . time() . $file->getClientFilename());

            $filename = self::path() . 'media/savas/' . $name;

            $file->moveTo($filename);

            $entity->filename = $name;
            $entity->size     = $file->getSize();
        }
    }

    protected function checkPermission(Entity $entity)
    {
        $releaseID = $entity->releaseID;
        $release   = Release::repository()->find($releaseID);

        if ($release)
        {
            return Application::isMember($release->appID);
        }

        return true;
    }

    public function downloadAction()
    {
        $id = (int) self::request()->getParam('id');

        /** @var Repository $repository */
        $repository = $this->getClass()::repository();
        $className  = $this->getClass();

        if ($id > 0)
        {
            $model = $repository->find($id);

            if (!($model instanceof $className))
            {
                return self::json()->failure(['message' => 'Entity by id not found.']);
            }

            if (!$this->checkPermission($model))
            {
                return self::json()->failure(['message' => 'You are not permitted to edit this entity.']);
            }

            $filename = self::path() . 'media/savas/' . $model->filename;

            if (is_file($filename))
            {
                $extension = pathinfo($model->originalFilename, PATHINFO_EXTENSION);
                $type      = \Hoa\Mime\Mime::getMimeFromExtension($extension);

                header('Content-Type: ' . $type);
                readfile($filename);
                die;
            }
            else
            {
                return self::json()->failure(['message' => 'File not found.']);
            }
        }
        else
        {
            return self::json()->failure(['message' => 'Missing required param: id']);
        }
    }

}