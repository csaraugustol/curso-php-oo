<?php

namespace Blog\Authenticator;

use Blog\Session\Session;

class CheckUserLogged
{
    /**
     * Verifica se existe um usuário autenticado
     *
     * @return bool
     */
    public static function checkAuthenticator(): bool
    {
        if (Session::hasKeySession('user')) {
            return true;
        }
        return false;
    }

    /**
     * Verifica se existe um usuário autenticado
     * para permitir acesso aos endpoints
     *
     * @return bool
     */
    public static function checkController(): bool
    {
        if (Session::hasKeySession('user')) {
            return true;
        }
        return false;
    }
}
