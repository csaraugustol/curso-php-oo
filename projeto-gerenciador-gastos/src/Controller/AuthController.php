<?php

namespace GGP\Controller;

use GGP\View\View;
use GGP\Entity\User;
use GGP\Session\Flash;
use GGP\DataBase\Connection;
use GGP\Authenticator\Authenticator;

class AuthController
{
    public function login()
    {
        //Método para refetuar login
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = new User(Connection::getInstance());
            $authenticator = new Authenticator($user);

            if (!$authenticator->login($_POST)) {
                Flash::sendMessageSession("danger", "Usuário ou senha incorretos!");
                return header("Location: " . HOME . '/auth/login');
            }

            Flash::sendMessageSession("success", "Usuário logado com sucesso!");
            return header("Location: " . HOME . '/expenses');
        }

        $view = new View('auth/index.phtml');
        return $view->render();
    }

    //Método para sair do sistema
    public function logout()
    {
        $authenticator = (new Authenticator())->logout();
        Flash::sendMessageSession("warning", "Usuário deslogado!");
        return header("Location: " . HOME . '/auth/login');
    }
}
