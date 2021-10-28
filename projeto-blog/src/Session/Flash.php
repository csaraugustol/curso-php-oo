<?php

namespace Blog\Session;

use Exception;

class Flash
{
    /**
     * Envia a mensagem do alerta da sessão
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


    /**
     * Retorna erros para o caso de exception, verificando
     * se irá retornar a mensagem da exception
     *
     * @param Exception $exception
     * @param string $message
     *
     * @return void
     */
    public static function returnErrorExceptionMessage(
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
