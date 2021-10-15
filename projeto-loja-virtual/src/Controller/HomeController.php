<?php

namespace LojaVirtual\Controller;

use LojaVirtual\View\View;

class HomeController
{
    public function index()
    {
        $view = new View('site/index.phtml');

        return $view->render();
    }
}
