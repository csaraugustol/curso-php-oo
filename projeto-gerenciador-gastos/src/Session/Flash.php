<?php

namespace GGP\Session;

use Exception;

class Flash
{
    /**
     * Envia mensagem para a sessão
     *
     * @param string $keySession
     * @param string $message
     * @return void
     */
    public static function sendMessageSession(string $keySession, string $message): void
    {
        Session::addUserSession($keySession, $message);
    }

    /**
     * Retorna a mensagem da sessão
     *
     * @param string $keySession
     * @return string
     */
    public static function returnMessageSession(string $keySession): string
    {
        $message = Session::verifyExistsKey($keySession);
        Session::removeUserSession($keySession);
        return $message;
    }

    /**
     * Retorna exceptions e outras mensagens
     * de erro encontradas
     *
     * @param Exception $exception
     * @param string $message
     * @param string $alertType
     * @return void
     */
    public static function returnMessageExceptionError(
        Exception $exception,
        string $message,
        string $alertType = 'danger'
    ): void {
        if (APP_DEBUG) {
            Flash::sendMessageSession($alertType, $exception->getMessage());
        }
        Flash::sendMessageSession($alertType, $message);
    }
}
