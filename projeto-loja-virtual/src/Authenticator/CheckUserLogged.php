<?php

namespace LojaVirtual\Authenticator;

use LojaVirtual\Session\Session;

class CheckUserLogged
{
    /**
     * Verifica se existe um usuário na sessão para verificar
     * suas permissões de acesso ao sistema
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
}
