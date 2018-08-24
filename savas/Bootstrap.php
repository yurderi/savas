<?php

namespace savas;

use Favez\Mvc\App;

class Bootstrap extends \CMS\Components\Plugin\Bootstrap
{

    public function install ()
    {
        // Create symlink to register custom theme
        $currentPath = $this->getPath() . '/Themes/savas';
        $targetPath  = App::path() . '/themes/savas';

        `ln -s $currentPath $targetPath`;

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

        App::instance()->getContainer()['notFoundHandler'] = function() {
            return function (\Slim\Http\Request $request, $response) {
                return App::instance()->dispatcher()->dispatch('savas:Index:index', []);
            };
        };
    }

}