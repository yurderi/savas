<?php

namespace savas;

use Favez\Mvc\App;
use savas\Components\ModelValidator;
use Slim\Http\Body;
use Slim\Http\Response;

class Bootstrap extends \CMS\Components\Plugin\Bootstrap
{

    public function install ()
    {
        // Create symlink to register custom theme
        $currentPath = $this->getPath() . '/Themes/savas';
        $targetPath  = App::path() . '/themes/savas';

        `ln -s $currentPath $targetPath`;

        $this->migrateDb();

        return true;
    }

    public function uninstall ()
    {
        $targetPath = App::path() . '/themes/savas';

        if (is_link($targetPath))
        {
            unlink($targetPath);
        }

        return true;
    }

    public function execute()
    {
        require_once $this->getPath() . '/vendor/autoload.php';

        $this->registerController('Savas', 'Index');
        $this->registerController('Savas', 'User');
        $this->registerController('Savas', 'Application');
        $this->registerController('Savas', 'Channel');
        $this->registerController('Savas', 'Platform');
        $this->registerController('Savas', 'Release');
        $this->registerController('Savas', 'File');

        App::instance()->any('/savas/api/download/{filename}', function () {
            require_once __DIR__ . '/Controllers/Savas/ApiController.php';

            return App::dispatcher()->dispatch('savas:api:download', []);
        });

        $this->registerController('Savas', 'Api');

        App::di()->registerShared('modelValidator', function() {
            return new ModelValidator();
        });

        App::instance()->getContainer()['notFoundHandler'] = function() {
            return function (\Slim\Http\Request $request,  Response $response) {
                $html = App::instance()->dispatcher()->dispatch('savas:Index:notFound', []);

                $response->getBody()->write($html);

                return $response->withStatus(404);
            };
        };
    }

}