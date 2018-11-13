<?php

namespace CMS\Controllers\Frontend;

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

    public function getListQuery()
    {
        $userID = self::auth()->userID();
        $releaseID  = self::request()->getParam('releaseID');
        $query  = self::db()->from('s_application_release_file f')
            ->leftJoin('s_application_release r ON r.id = f.releaseID')
            ->leftJoin('s_application a ON a.id = r.appID')
            ->leftJoin('s_application_member m ON m.appID = a.id')
            ->where('m.userID', $userID)
            ->where('f.releaseID', $releaseID)
            ->select(null)->select('f.*');

        return $query;
    }

    protected function setDefaultValues(Entity $entity)
    {
        $entity->created   = date('Y-m-d H:i:s');
        $entity->size      = 0;
        $entity->extension = '';
        $entity->mimeType  = '';
    }

    protected function setValues(Entity $entity, $input)
    {
        $entity->releaseID   = $input['releaseID'];
        $entity->platformID  = $input['platformID'];
        $entity->displayName = $input['displayName'];
        $entity->changed     = date('Y-m-d H:i:s');

        $files = self::request()->getUploadedFiles();

        if (isset($files['file']))
        {
            /** @var UploadedFile $file */
            $file = $files['file'];
            $name = md5(uniqid() . time() . $file->getClientFilename());

            $filename = self::path() . 'media/savas/' . $name;

            $file->moveTo($filename);

            $entity->filename  = $name;
            $entity->size      = $file->getSize();
            $entity->extension = pathinfo($entity->displayName, PATHINFO_EXTENSION);
            $entity->mimeType  = \Hoa\Mime\Mime::getMimeFromExtension($entity->extension) ?? '';
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
                header('Content-Type: ' . $model->mimeType);
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