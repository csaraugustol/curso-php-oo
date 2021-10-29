<?php

namespace LojaVirtual\Controller;

use LojaVirtual\View\View;
use LojaVirtual\Entity\User;
use LojaVirtual\Session\Flash;
use LojaVirtual\DataBase\Connection;
use LojaVirtual\Security\PasswordHash;
use LojaVirtual\Authenticator\Authenticator;
use LojaVirtual\Security\Validator\Sanitizer;
use LojaVirtual\Security\Validator\Validator;
use LojaVirtual\Session\Session;

class StoreController
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = new User(Connection::getInstance());
            $authenticator = new Authenticator($user);

            if (!$authenticator->login($_POST)) {
                Flash::sendMessageSession("warning", "Usuário ou senha incorretos!");
                return header("Location: " . HOME . '/store/login');
            }

            Flash::sendMessageSession("success", "Usuário logado com suceso!");
            return header("Location: " . HOME . '/checkout');
        }

        $view = new View('site/login.phtml');
        return $view->render();
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $_POST;
            $data = Sanitizer::sanitizeData($data, User::$filters);

            if (!Validator::validateRequiredFields($data)) {
                Flash::sendMessageSession('warning', 'Preencha todos os campos!');
                return header('Location: ' . HOME . '/store/login');
            }

            if (!Validator::validatePasswordMinStringLenght($data['password'])) {
                Flash::sendMessageSession('warning', 'Senha deve conter pelo menos 6 caracteres!');
                return header('Location: ' . HOME . '/store/login');
            }

            if (!Validator::validatePasswordConfirm($data['password'], $data['password_confirm'])) {
                Flash::sendMessageSession('warning', 'Senhas não conferem!');
                return header('Location: ' . HOME . '/store/login');
            }


            $newUser = new User(Connection::getInstance());

            $data['password'] = PasswordHash::hashPassword($data['password']);
            $data['is_active'] = 1;
            unset($data['password_confirm']);

            if (!$user = $newUser->insert($data)) {
                Flash::sendMessageSession('error', 'Erro ao criar usuário!');
                return header('Location: ' . HOME . '/store/login');
            }

            unset($user['password']);
            Session::addUserSession('user', $user);
            return header('Location: ' . HOME . '/cart/checkout');
        }
    }
}
