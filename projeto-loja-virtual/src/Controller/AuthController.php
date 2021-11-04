<?php

namespace LojaVirtual\Controller;

use Exception;
use LojaVirtual\View\View;
use LojaVirtual\Entity\User;
use LojaVirtual\Session\Flash;
use LojaVirtual\DataBase\Connection;
use LojaVirtual\Authenticator\Authenticator;

class AuthController
{
    /**
     * Efetua login e redirecionamento
     * para página interna do sistema
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
                Flash::sendMessageSession("warning", "Usuário ou senha incorretos!");
                return header("Location: " . HOME . '/auth/login');
            }

            Flash::sendMessageSession("success", "Usuário logado com suceso!");
        } catch (Exception $exception) {
            Flash::returnExceptionErrorMessage(
                $exception,
                'Erro ao realizar login. Tente novamente!'
            );

            return header("Location: " . HOME . '/auth/login');
        }

        return header("Location: " . HOME . '/admin/products');
    }

    /**
     * Efetua logout do sistema
     *
     * @return redirect
     */
    public function logout()
    {
        $auth = (new Authenticator())->logout();
        Flash::sendMessageSession('success', 'Usuário deslogado com sucesso!');

        return header("Location: " . HOME);
    }
}
