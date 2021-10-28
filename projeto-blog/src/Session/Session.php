<?php

namespace Blog\Session;

class Session
{
    /**
     * Inícia a sessão
     *
     * @return void
     */
    public static function sessionStart(): void
    {
        if (session_status() != PHP_SESSION_NONE) {
            return;
        }
        session_start();
    }

    /**
     * Adiciona usuário logado na sessão
     *
     * @param string $keySession
     * @param array $value
     * @return void
     */
    public static function addUserLoggedSession(string $keySession, array $value): void
    {
        self::sessionStart();
        $_SESSION[$keySession] = $value;
    }

    /**
     * Adiciona chave na sessão
     *
     * @param string $keySession
     * @param string $value
     * @return void
     */
    public static function addKeySession(string $keySession, string $value): void
    {
        self::sessionStart();
        $_SESSION[$keySession] = $value;
    }

    /**
     * Remove chave da sessão
     *
     * @param string $keySession
     * @return void
     */
    public static function removeKeySession(string $keySession): void
    {
        self::sessionStart();
        if (isset($_SESSION[$keySession])) {
            unset($_SESSION[$keySession]);
        }
    }

    /**
     * Limpa chave da sessão
     *
     * @return void
     */
    public static function clearKeySession(): void
    {
        self::sessionStart();
        session_destroy();
        $_SESSION = [];
    }

    /**
     * Verifica chave na sessão
     *
     * @param string $keySession
     * @return bool
     */
    public static function hasKeySession(string $keySession): bool
    {
        self::sessionStart();
        return isset($_SESSION[$keySession]);
    }

    /**
     * Verifica se existe alguma chave na sessão
     *
     * @param string $keySession
     * @return string
     */
    public static function verifyExistsKey(string $keySession): string
    {
        self::sessionStart();
        return isset($_SESSION[$keySession]) ? $_SESSION[$keySession] : null;
    }
}
