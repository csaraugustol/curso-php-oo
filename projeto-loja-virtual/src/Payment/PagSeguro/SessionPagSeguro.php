<?php

namespace LojaVirtual\Payment\PagSeguro;

use PagSeguro\Services\Session as PagSession;
use PagSeguro\Configuration\Configure;
use LojaVirtual\Session\Session;

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
        if (!Session::hasKeySession('pagseguro_session')) {
            $sessionCode = PagSession::create(
                Configure::getAccountCredentials()
            );
            Session::addMessageSession('pagseguro_session', $sessionCode->getResult());
        }
    }
}
