<?php

namespace GGP\Authenticator;

use Exception;
use GGP\Entity\User;
use GGP\Session\Flash;
use GGP\Session\Session;

class Authenticator
{
    /**
     * Usuário para autenticar
     *
     * @var User
     */
    private $user;

    /**
     * Usuário para validar autenticação
     *
     * @param User|null $user
     */
    public function __construct(User $user = null)
    {
        $this->user = $user;
    }

    /**
     * Verifica login e senha do usuário
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
            if ($user['password'] != $credentials['password']) {
                return false;
            }
            unset($user['password']);
            Session::addUserLoggedSession('user', $user);

            return true;
        } catch (Exception $exception) {
            Flash::returnMessageExceptionError(
                $exception,
                'Verifique suas credênciais. Caso persista, entre em contato com o administrador!',
            );

            return false;
        }
    }

    /**
     * Remove usuário da sessão e efetua logout do sistema
     *
     * @return void
     */
    public function logout(): void
    {
        if (Session::hasKeySession('user')) {
            Session::removeKeySession('user');
        }
        Session::clearkeySession();
    }
}
