<?php

namespace GGP\Authenticator;

use GGP\Session\Session;

class CheckUserLogged
{
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
