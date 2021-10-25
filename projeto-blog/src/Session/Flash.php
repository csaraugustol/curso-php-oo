<?php

namespace Blog\Session;

class Flash
{
    /**
     * Envia a mensagem do alerta da sessão
     *
     * @param string $keySession
     * @param string $message
     * @return void
     */
    public static function sendMessageSession($keySession, $message): void
    {
        Session::addUserSession($keySession, $message);
    }

    /**
     * Retorna a mensagem do alerta da sessão
     *
     * @param string $keySession
     * @return string
     */
    public static function returnMessageSession($keySession): string
    {
        $message = Session::verifyExistsKey($keySession);
        Session::removeUserSession($keySession);
        return $message;
    }
}
