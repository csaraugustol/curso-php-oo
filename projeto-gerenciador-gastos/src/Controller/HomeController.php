<?php

namespace GGP\Controller;

use GGP\View\View;

class HomeController
{
    /**
     * Retorna página inicial do sistema
     *
     * @return redirect
     */
    public function index()
    {
        $view = new View('site/index.phtml');
        return $view->render();
    }
}
