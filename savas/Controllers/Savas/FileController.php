<?php

namespace CMS\Controllers\Savas;

use Favez\ORM\Entity\Entity;
use savas\Components\Controllers\API;
use savas\Models\Savas\Application\File;
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

}