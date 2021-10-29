<?php

namespace LojaVirtual\Payment\PagSeguro;

use LojaVirtual\Session\Session as PagSession;
use PagSeguro\Configuration\Configure;
use PagSeguro\Services\Session;

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
