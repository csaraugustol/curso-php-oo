<?php

namespace Blog\Authenticator;

use Blog\Session\Session;

trait CheckUserLogged
{
    //Método para verificar se existe um usuário autenticado
    public function checkAuthenticator()
    {
        if (Session::hasUserSession('user')) {
            return true;
        }
        return false;
    }
}
