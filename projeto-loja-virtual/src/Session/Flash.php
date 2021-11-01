<?php

namespace LojaVirtual\Session;

use Exception;

class Flash
{
    //Envia mensagem para a sessão
    public static function sendMessageSession($keySession, $message)
    {
        Session::addUserSession($keySession, $message);
    }

    //Retorna a mensagem da sessão
    public static function returnMessageSession($keySession)
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
