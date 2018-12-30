<?php

namespace ProVallo\Plugins\Savas;

use ProVallo\Core;

class Bootstrap extends \ProVallo\Components\Plugin\Bootstrap
{
    
    public function install ()
    {
        $this->installDB();
        
        Core::di()->get('backend.config')->create($this, [
            'domainID' => [
                'label'       => 'Domain',
                'type'        => 'select',
                'store'       => [
                    'type'         => 'remote',
                    'model'        => 'domain',
                    'displayField' => 'label',
                    'valueField'   => 'id'
                ],
                'description' => 'On which domain should the store be available?'
            ]
        ]);
        
        return true;
    }
    
    public function execute ()
    {
        $config = Core::di()->get('backend.config')->get($this);
        $domainID = Core::di()->get('frontend.domain')->getAlternativeID();
        
        if ($config['domainID'] === $domainID)
        {
            require_once $this->getPath() . '/vendor/autoload.php';
            
            Core::events()->subscribe('core.route.register', function() {
                $this->registerController('Frontend', 'Index');
                $this->registerController('Frontend', 'User');
                $this->registerController('Frontend', 'Application');
                $this->registerController('Frontend', 'Channel');
                $this->registerController('Frontend', 'Platform');
                $this->registerController('Frontend', 'Release');
                $this->registerController('Frontend', 'File');
                $this->registerController('Frontend', 'Token');
    
                Core::instance()->any('/api/v1/download/{filename}', function () {
                    require_once __DIR__ . '/Controllers/Frontend/ApiController.php';
        
                    return Core::dispatcher()->dispatch('frontend:Api:download', []);
                });
    
                $this->registerController('Frontend', 'Api', false);
                Core::instance()->any('/api/v1/[{action}]', 'frontend:Api:{action}');
    
                Core::instance()->any('/', 'frontend:Index:index');
            });
        }
    }
    
}