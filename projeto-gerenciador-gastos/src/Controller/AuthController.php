<?php

namespace GGP\Controller;

use Exception;
use GGP\View\View;
use GGP\Entity\User;
use GGP\Session\Flash;
use GGP\DataBase\Connection;
use GGP\Authenticator\Authenticator;

class AuthController
{
    /**
     * Efetua login do usuário
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

            Flash::sendMessageSession("success", "Usuário logado com sucesso!");
        } catch (Exception $exception) {
            Flash::returnMessageExceptionError(
                $exception,
                'Verifique as credênciais. Caso persista o erro, contate o administrador!'
            );

            return header("Location: " . HOME . '/auth/login');
        }

        return header("Location: " . HOME . '/expenses');
    }

    /**
     * Efetua logout do usuário do sistema
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
