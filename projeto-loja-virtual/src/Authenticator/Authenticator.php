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

    public function __construct(User $user = null)
    {
        $this->user = $user;
    }

    public function login(array $credentials)
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

    public function logout()
    {
        if (Session::hasUserSession('user')) {
            Session::removeUserSession('user');
        }
        Session::clearUserSession();
    }
}
