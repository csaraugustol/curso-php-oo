<?php

namespace Instituicao\Controller;

use Instituicao\View\View;

class ContactController
{
    /**
     * Retorna a view que exibe os contatos
     * da instituição
     *
     * @return string
     */
    public function index(): string
    {
        $view = new View('site/contact.phtml');
        return $view->render();
    }
}
