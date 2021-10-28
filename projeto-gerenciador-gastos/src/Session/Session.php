<?php

namespace GGP\Session;

class Session
{
    /**
     * Inicia a sessão
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
     * Adiciona usuário na sessão
     *
     * @param string $keySession
     * @param string $value
     * @return void
     */
    public static function addUserSession(string $keySession, string $value): void
    {
        self::sessionStart();
        $_SESSION[$keySession] = $value;
    }

    /**
     * Remove um usuário da sessão
     *
     * @param string $keySession
     * @return void
     */
    public static function removeUserSession(string $keySession): void
    {
        self::sessionStart();
        if (isset($_SESSION[$keySession])) {
            unset($_SESSION[$keySession]);
        }
    }

    /**
     * Limpa um usuário na sessão
     *
     * @return void
     */
    public static function clearUserSession(): void
    {
        self::sessionStart();
        session_destroy();
        $_SESSION = [];
    }

    /**
     * Verifica se existe usuário na sessão
     *
     * @param string $keySession
     * @return bool
     */
    public static function hasUserSession(string $keySession): bool
    {
        self::sessionStart();
        return isset($_SESSION[$keySession]);
    }

    /**
     * Retorna string que está na sessão
     *
     * @param string $keySession
     * @return string
     */
    public static function verifyExistsKey(string $keySession): string
    {
        self::sessionStart();
        return isset($_SESSION[$keySession]) ? $_SESSION[$keySession] : null;
    }

    /**
     * Retorna usuário da sessão
     *
     * @param string $keySession
     * @return array
     */
    public static function returnUserSession(string $keySession): array
    {
        self::sessionStart();
        return isset($_SESSION[$keySession]) ? $_SESSION[$keySession] : null;
    }
}
