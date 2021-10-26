<?php

namespace Blog\Authenticator;

use Blog\Session\Session;

class CheckUserLogged // Classe e coloca os metodos como privados
{
    /**
     * Método para verificar se existe um usuário autenticado
     *
     * @return bool
     */
    public static function checkAuthenticator(): bool
    {
        if (Session::hasUserSession('user')) {
            return true;
        }
        return false;
    }

    /**
     * Método para verificar se existe um usuário autenticado
     * para permitir acesso aos endpoints
     *
     * @return bool
     */
    public static function checkController(): bool
    {
        if (Session::hasUserSession('user')) {
            return true;
        }
        return false;
    }
}
