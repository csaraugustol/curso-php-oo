<?php

namespace LojaVirtual\Payment\PagSeguro;

use PagSeguro\Services\Session;
use PagSeguro\Configuration\Configure;
use LojaVirtual\Session\Session as PagSession;

class SessionPagSeguro
{
    /**
     * Cria na sessão uma instância tipo 'pagseguro' para manipulação
     * dos dados da API
     *
     * @return void
     */
    public static function createSession(): void
    {
        if (!PagSession::hasUserSession('pagseguro_session')) {
            $sessionCode = Session::create(
                Configure::getAccountCredentials()
            );
            PagSession::addMessageSession('pagseguro_session', $sessionCode->getResult());
        }
    }
}
