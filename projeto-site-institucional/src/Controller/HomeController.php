<?php

namespace Instituicao\Controller;

use Instituicao\View\View;

class HomeController
{
    /**
     * Retorna a página inicial do site
     *
     * @return redirect
     */
    public function index()
    {
        $view = new View('site/index.phtml');
        return $view->render();
    }
}
