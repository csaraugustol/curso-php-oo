<?php

namespace Instituicao\Controller;

use Instituicao\View\View;

class AboutController
{
    public function index()
    {
        $view = new View('site/about.phtml');
        return $view->render();
    }
}
