<?php

namespace GGP\Controller;

use GGP\View\View;
use GGP\Session\Flash;
use GGP\Authenticator\CheckUserLogged;

class HomeController
{
    use CheckUserLogged;

    //Método para verificar se o usuário está  autenticado
    //e pode acessar o sistema
    public function __construct()
    {
        if (!$this->checkAuthenticator()) {
            Flash::sendMessageSession("danger", "Faça o login para acesar!");
            return header("Location: " . HOME . '/auth/login');
        }
    }

    //Método de exibição da view 'home'
    public function index()
    {
        $view = new View('site/index.phtml');
        return $view->render();
    }
}
