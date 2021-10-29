<?php

namespace LojaVirtual\Authenticator;

use LojaVirtual\Session\Session;

trait CheckUserLogged
{
    //Método para verificar se existe um usuário autenticado
    public function checkAuthenticator()
    {
        return Session::hasUserSession('user');
        /*-if (Session::hasUserSession('user')) {
            return true;
        }
        return false;*/
    }
}
