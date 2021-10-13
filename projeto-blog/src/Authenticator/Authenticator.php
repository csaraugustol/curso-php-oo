<?php

namespace Blog\Authenticator;

use Blog\Entity\User;
use Blog\Session\Session;
use Blog\Security\PasswordHash;

class Authenticator
{
    private $user;

    public function __construct(User $user = null)
    {
        $this->user = $user;
    }

    //Método para verificar usuário e realizar login
    public function login(array $credentials)
    {
        $user = current($this->user->filterWithConditions([
            'email' => $credentials['email']
        ]));

        if (!$user) {
            return false;
        }

        if (!PasswordHash::checkPassword($credentials['password'], $user['password'])) {
            return false;
        }

        unset($user['password']);
        Session::addUserSession('user', $user);
        return true;
    }

    //Método para remover usuário da sessão e sair do sistema
    public function logout()
    {
        if (Session::hasUserSession('user')) {
            Session::removeUserSession('user');
        }

        Session::clearUserSession();
    }
}
