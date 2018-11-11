<?php

namespace savas;

use CMS\Components\Form\Form;
use Favez\Mvc\App;
use savas\Commands\ConfigCommand;
use savas\Components\ModelValidator;
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

        //
        $form = $this->createForm();
        $form->elements()->set('domain', 'text', [
            'label' => 'Domain'
        ]);
        $form->save();

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
        if (PHP_SAPI === 'cli') {
            App::events()->subscribe('core.console_commands.collect', function() {
                return [
                    new ConfigCommand()
                ];
            });

            return;
        }

        // Make sure the plugin is limited to a domain
        $form = Form::load('plugin_savas');
        $domainID = (int) $form->domain;
        if ($domainID !== (int) App::domain()->id) {
            return;
        }

        require_once $this->getPath() . '/vendor/autoload.php';

        $this->registerController('Frontend', 'Index');
        $this->registerController('Frontend', 'User');
        $this->registerController('Frontend', 'Application');
        $this->registerController('Frontend', 'Channel');
        $this->registerController('Frontend', 'Platform');
        $this->registerController('Frontend', 'Release');
        $this->registerController('Frontend', 'File');
        $this->registerController('Frontend', 'Token');

        App::instance()->any('/api/v1/download/{filename}', function () {
            require_once __DIR__ . '/Controllers/Frontend/ApiController.php';

            return App::dispatcher()->dispatch('frontend:Api:download', []);
        });

        $this->registerController('Frontend', 'Api', false);
        App::instance()->any('/api/v1/[{action}]', 'frontend:Api:{action}');


        App::di()->registerShared('modelValidator', function() {
            return new ModelValidator();
        });

        App::instance()->getContainer()['notFoundHandler'] = function() {
            return function (\Slim\Http\Request $request,  Response $response) {
                $html = App::instance()->dispatcher()->dispatch('frontend:Index:notFound', []);

                $response->getBody()->write($html);

                return $response->withStatus(404);
            };
        };
    }

}