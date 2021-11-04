<?php

namespace LojaVirtual\Session;

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
        Session::addMessageSession($keySession, $message);
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
     * Recebe mensagem de erro de exception e
     * outros tipos de erros
     *
     * @param Exception $exception
     * @param string $message
     * @param string $alertType
     * @return void
     */
    public static function returnExceptionErrorMessage(
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
