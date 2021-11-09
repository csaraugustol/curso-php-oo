<?php

namespace LojaVirtual\Payment\PagSeguro;

use PagSeguro\Helpers\Xhr;
use InvalidArgumentException;
use PagSeguro\Configuration\Configure;
use PagSeguro\Services\Transactions\Notification as TransactionsNotification;

class Notification
{
    /**
     * Retorna as notificações após compra
     *
     * @return array
     */
    public function returnNotificationTransaction(): array
    {
        if (!Xhr::hasPost()) {
            throw new InvalidArgumentException($_POST);
        }

        $response = TransactionsNotification::check(
            Configure::getAccountCredentials()
        );

        return $response;
    }
}
