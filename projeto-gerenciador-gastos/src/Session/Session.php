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
     * Adiciona usuário na sessão
     *
     * @param string $keySession
     * @return void
     */
    public static function addUserSession(string $keySession, $value): void
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
     * Undocumented function
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
