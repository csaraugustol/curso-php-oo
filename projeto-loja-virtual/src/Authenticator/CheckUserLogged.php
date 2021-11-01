<?php

namespace LojaVirtual\Authenticator;

use LojaVirtual\Session\Session;

class CheckUserLogged
{
    /**
     * Verifica a autenticação do usuário
     * para ter acesso aos endpoints
     *
     * @return boolean
     */
    public static function checkAuthenticator(): bool
    {
        return Session::hasUserSession('user');
        /*-if (Session::hasUserSession('user')) {
            return true;
        }
        return false;*/
    }
}
