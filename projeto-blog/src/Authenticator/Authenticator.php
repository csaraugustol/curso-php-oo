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

    /**
     * Método para validar login e senha do usuário
     * e permitir acesso ao sistema
     *
     * @param array $credentials
     * @return bool
     */
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

    /**
     * Método para remover usuário da sessão e sair do sistema
     *
     * @return string
     */
    public function logout()
    {
        if (Session::hasUserSession('user')) {
            Session::removeUserSession('user');
        }

        Session::clearUserSession();
    }
}
