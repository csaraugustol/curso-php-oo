<?php

namespace Instituicao\Controller;

use Instituicao\View\View;

class ContactController
{
    /**
     * Retorna a view que exibe formas de contatos
     * com a instituição
     *
     * @return redirect
     */
    public function index()
    {
        $view = new View('site/contact.phtml');
        return $view->render();
    }
}
