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
     * @return string
     */
    public function login(): string
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
            return header("Location: " . HOME . '/expenses');
        } catch (Exception $exception) {
            Flash::returnMessageExceptionError(
                $exception,
                'Verifique as credênciais. Caso persista o erro, contate o administrador!'
            );
        }
    }

    /**
     * Efetua logout do usuário do sistema
     *
     * @return string
     */
    public function logout()
    {
        $authenticator = (new Authenticator())->logout();
        Flash::sendMessageSession("warning", "Usuário deslogado!");
        return header("Location: " . HOME . '/auth/login');
    }
}
