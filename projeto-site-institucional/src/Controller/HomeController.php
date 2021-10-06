<?php

namespace Instituicao\Controller;

use Instituicao\View\View;

class HomeController
{
    public function index()
    {
        $view = new View('site/index.phtml');
        return $view->render();
    }
}
