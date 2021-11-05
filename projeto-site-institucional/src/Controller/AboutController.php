<?php

namespace Instituicao\Controller;

use Instituicao\View\View;

class AboutController
{
    /**
     * Retorna view 'sobre'
     *
     * @return redirect
     */
    public function index()
    {
        $view = new View('site/about.phtml');
        return $view->render();
    }
}
