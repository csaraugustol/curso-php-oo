<?php

namespace GGP\Controller;

use GGP\View\View;

class HomeController
{
    //Método de exibição da view 'home'
    public function index()
    {
        $view = new View('site/index.phtml');
        return $view->render();
    }
}
