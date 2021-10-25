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
     * Adiciona usuário na sessão
     *
     * @param string $keySession
     * @param string $value
     * @return void
     */
    public static function addUserSession($keySession, $value)
    {
        self::sessionStart();
        $_SESSION[$keySession] = $value;
    }

    /**
     * Remove usuário da sessão
     *
     * @param string $keySession
     * @return void
     */
    public static function removeUserSession($keySession): void
    {
        self::sessionStart();
        if (isset($_SESSION[$keySession])) {
            unset($_SESSION[$keySession]);
        }
    }

    /**
     * Limpa usuário da sessão
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
     * Verifica usuário na sessão
     *
     * @param string $keySession
     * @return boolean
     */
    public static function hasUserSession($keySession)
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
    public static function verifyExistsKey($keySession): string
    {
        self::sessionStart();

        return isset($_SESSION[$keySession]) ? $_SESSION[$keySession] : null;
    }
}