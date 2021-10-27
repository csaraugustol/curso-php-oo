<?php

namespace Blog\Authenticator;

use Exception;
use Blog\Entity\User;
use Blog\Session\Flash;
use Blog\Session\Session;
use Blog\Security\PasswordHash;

class Authenticator
{
    private $user;

    /**
     * Construtor da autenticação
     *
     * @param User|null $user
     */
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
    public function login(array $credentials): bool
    {
        try {
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
        } catch (Exception $exception) {
            Flash::returnErrorExceptionMessage(
                $exception,
                'Verifique suas credênciais. Caso persista, entre em contato com o administrador!',
            );
            return false;
        }
    }

    /**
     * Método para remover usuário da sessão e sair do sistema
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
