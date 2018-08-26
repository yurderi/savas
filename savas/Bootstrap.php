<?php

namespace savas;

use Favez\Mvc\App;
use savas\Components\ModelValidator;

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
        $this->registerController('Savas', 'Index');
        $this->registerController('Savas', 'User');
        $this->registerController('Savas', 'Application');

        App::di()->registerShared('modelValidator', function() {
            return new ModelValidator();
        });

        App::instance()->getContainer()['notFoundHandler'] = function() {
            return function (\Slim\Http\Request $request, $response) {
                return App::instance()->dispatcher()->dispatch('savas:Index:index', []);
            };
        };
    }

}