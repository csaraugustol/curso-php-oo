<?php

namespace Blog\Controller;

use Exception;
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
     * @return redirect
     */
    public function login()
    {
        try {
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
        } catch (Exception $exception) {
            Flash::returnErrorExceptionMessage(
                $exception,
                'Verifique suas credênciais. Caso persista, entre em contato com o administrador!',
            );

            return header("Location: " . HOME . '/auth/login');
        }

        return header("Location: " . HOME . '/home');
    }

    /**
     * Método para encerrar uma sessão
     *
     * @return redirect
     */
    public function logout()
    {
        $authenticator = (new Authenticator())->logout();
        Flash::sendMessageSession("warning", "Usuário deslogado!");
        return header("Location: " . HOME . '/auth/login');
    }
}
