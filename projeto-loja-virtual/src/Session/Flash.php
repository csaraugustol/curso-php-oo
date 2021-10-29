<?php

namespace LojaVirtual\Session;

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
}
