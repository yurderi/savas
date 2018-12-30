<?php

namespace ProVallo\Controllers\Frontend;

class IndexController extends \ProVallo\Components\Controller
{

    public function indexAction()
    {
        $plugin = $this->plugins()->get('Savas');
        $filename = $plugin->getPath() . '/Views/frontend/dist/index.html';

        $html = file_get_contents($filename);
        $html = str_replace('/static', '/ext/Savas/Views/frontend/dist/static', $html);

        // $this->httpCache()->setCacheKey('savas/index.html');

        return $html;
    }

    public function notFoundAction()
    {
        return '404 Not Found';
    }

}