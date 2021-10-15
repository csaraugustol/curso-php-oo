<?php

namespace LojaVirtual\Controller;

use LojaVirtual\View\View;
use LojaVirtual\Entity\User;
use LojaVirtual\Session\Flash;
use LojaVirtual\DataBase\Connection;
use LojaVirtual\Authenticator\Authenticator;

class AuthController
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = new User(Connection::getInstance());
            $authenticator = new Authenticator($user);

            if (!$authenticator->login($_POST)) {
                Flash::sendMessageSession("warning", "Usuário ou senha incorretos!");
                return header("Location: " . HOME . '/auth/login');
            }

            Flash::sendMessageSession("success", "Usuário logado com suceso!");
            return header("Location: " . HOME . '/admin/products');
        }
        $view = new View('auth/index.phtml');
        return $view->render();
    }

    public function logout()
    {
        $auth = (new Authenticator())->logout();
        Flash::sendMessageSession('success', 'Usuário deslogado com sucesso!');
        return header("Location: " . HOME);
    }
}
