<?php

namespace LojaVirtual\Authenticator;

use Exception;
use LojaVirtual\Entity\User;
use LojaVirtual\Session\Session;
use LojaVirtual\Security\PasswordHash;
use LojaVirtual\Session\Flash;

class Authenticator
{
    /**
     * @var User
     */
    private $user;

    /**
     * Recebe um usuário
     *
     * @param User|null $user
     */
    public function __construct(User $user = null)
    {
        $this->user = $user;
    }

    /**
     * Verifica usuário e senha
     *
     * @param array $credentials
     * @return bool
     */
    public function login(array $credentials): bool
    {
        try {
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
        } catch (Exception $exception) {
            Flash::returnExceptionErrorMessage(
                $exception,
                'Erro ao logar. Verifique as credênciais!'
            );

            return false;
        }

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
