<?php

namespace CMS\Controllers\Savas;

use CMS\Components\Controller;

class IndexController extends Controller
{

    public function indexAction()
    {
        $plugin = $this->plugins()->get('savas');
        $filename = $plugin->getPath() . '/Themes/savas/default/dist/index.html';

        $html = file_get_contents($filename);
        $html = str_replace('/static', '/ext/custom/savas/Themes/savas/default/dist/static', $html);

        $this->httpCache()->setCacheKey('savas/index.html');

        return $html;
    }

}