<?php

namespace LojaVirtual\Authenticator;

use LojaVirtual\Entity\User;
use LojaVirtual\Session\Session;
use LojaVirtual\Security\PasswordHash;

class Authenticator
{
    /**
     * @var User
     */
    private $user;

    /**
     * Recebe um usuário por parâmetro
     *
     * @param User|null $user
     */
    public function __construct(User $user = null)
    {
        $this->user = $user;
    }

    /**
     * Verifica credênciais para efetuar login
     *
     * @param array $credentials
     * @return boolean
     */
    public function login(array $credentials): bool
    {
        $user = $this->user->filterWithConditions([
            'email' => $credentials['email'],
        ]);

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
     * Efetua logout do sistema
     *
     * @return void
     */
    public function logout(): void
    {
        if (Session::hasUserSession('user')) {
            Session::removeUserSession('user');
        }
        Session::clearUserSession();
    }
}
