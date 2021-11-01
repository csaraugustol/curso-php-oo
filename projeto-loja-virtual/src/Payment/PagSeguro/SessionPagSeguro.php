<?php

namespace LojaVirtual\Payment\PagSeguro;

use PagSeguro\Services\Session;
use PagSeguro\Configuration\Configure;
use LojaVirtual\Session\Session as PagSession;

class SessionPagSeguro
{
    public static function createSession()
    {
        if (!PagSession::hasUserSession('pagseguro_session')) {
            $sessionCode = Session::create(
                Configure::getAccountCredentials()
            );
            PagSession::addUserSession('pagseguro_session', $sessionCode->getResult());
        }
    }
}
