<?php

namespace Blog\Controller;

use Blog\View\View;
use Blog\Entity\User;
use Blog\Session\Flash;
use Blog\DataBase\Connection;
use Blog\Authenticator\Authenticator;

class AuthController
{
    /**
     * Método para refetuar login
     *
     * @return string
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $view = new View('auth/index.phtml');
            return $view->render();
        }

        $user = new User(Connection::getInstance());
        $authenticator = new Authenticator($user);

        if (!$authenticator->login($_POST)) {
            Flash::sendMessageSession("danger", "Usuário ou senha incorretos!");
            return header("Location: " . HOME . '/auth/login');
        }
        return header("Location: " . HOME . '/home');
    }

    /**
     * Método para encerrar uma sessão
     *
     * @return void
     */
    public function logout()
    {
        $authenticator = (new Authenticator())->logout();
        Flash::sendMessageSession("warning", "Usuário deslogado!");
        return header("Location: " . HOME . '/auth/login');
    }
}
