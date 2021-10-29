<?php

namespace LojaVirtual\Session;

class Session
{
    //Método para iniciar a sessão
    public static function sessionStart()
    {
        if (session_status() != PHP_SESSION_NONE) {
            return;
        }
        session_start();
    }

    //Método para adicionar usuário na sessão
    public static function addUserSession($keySession, $value)
    {
        self::sessionStart();
        $_SESSION[$keySession] = $value;
    }

    //Método para remover usuário da sessão
    public static function removeUserSession($keySession)
    {
        self::sessionStart();

        if (isset($_SESSION[$keySession])) {
            unset($_SESSION[$keySession]);
        }
    }

    //Método para limpar usuário sessão
    public static function clearUserSession()
    {
        self::sessionStart();
        session_destroy();
        $_SESSION = [];
    }

    //Método para verificar usuário na sessão
    public static function hasUserSession($keySession)
    {
        self::sessionStart();
        return isset($_SESSION[$keySession]);
    }

    //Método para verificar se existe alguma chave na sessão
    public static function verifyExistsKey($keySession)
    {
        self::sessionStart();

        return isset($_SESSION[$keySession]) ? $_SESSION[$keySession] : null;
    }
}
