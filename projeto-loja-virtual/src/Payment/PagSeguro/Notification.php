<?php

namespace LojaVirtual\Payment\PagSeguro;

use InvalidArgumentException;
use PagSeguro\Configuration\Configure;
use PagSeguro\Helpers\Xhr;
use PagSeguro\Services\Transactions\Notification as TransactionsNotification;

class Notification
{
    public function returnNotificationTransaction()
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
