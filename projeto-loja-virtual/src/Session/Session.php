<?php

namespace LojaVirtual\Session;

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
     * Adiciona uma chave na sessão
     *
     * @param string $keySession
     * @param array $value
     * @return void
     */
    public static function addKeySession(string $keySession, array $value): void
    {
        self::sessionStart();
        $_SESSION[$keySession] = $value;
    }

    /**
     * Adiciona uma chave na sessão
     *
     * @param string $keySession
     * @param string $value
     * @return void
     */
    public static function addCartSession(string $keySession, string $value): void
    {
        self::sessionStart();
        $_SESSION[$keySession] = $value;
    }

    /**
     * Adiciona uma menssagem a sessão
     *
     * @param string $keySession
     * @param string $value
     * @return void
     */
    public static function addMessageSession(string $keySession, string $message): void
    {
        self::sessionStart();
        $_SESSION[$keySession] = $message;
    }

    /**
     * Remove chave da sessão
     *
     * @param string $keySession
     * @return void
     */
    public static function removekeySession(string $keySession): void
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
     * Verifica se há a chave na sessão retornado
     * um boleano
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
     * Verifica se existe a string recebida na sessão
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
     * Verifica se existe uma chave tipo array na sessão
     *
     * @param string $keySession
     * @return array
     */
    public static function verifyExistsKeyOfArray(string $keySession): array
    {
        self::sessionStart();
        return isset($_SESSION[$keySession]) ? $_SESSION[$keySession] : $cart = [];
    }
}
