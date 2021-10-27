<?php

namespace GGP\Controller;

use GGP\View\View;

class HomeController
{
    /**
     * Retorna página inicial do sistema
     *
     * @return string
     */
    public function index(): string
    {
        $view = new View('site/index.phtml');
        return $view->render();
    }
}
