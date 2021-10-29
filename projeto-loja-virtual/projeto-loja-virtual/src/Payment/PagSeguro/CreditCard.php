<?php

namespace LojaVirtual\Payment\PagSeguro;

use PagSeguro\Configuration\Configure;
use PagSeguro\Domains\Requests\DirectPayment\CreditCard as DirectPaymentCreditCard;

class CreditCard
{
    private $reference;
    private $items;
    private $data;
    private $user;

    public function __construct($reference, $items, $data, $user)
    {
        $this->reference = $reference;
        $this->items = $items;
        $this->data = $data;
        $this->user = $user;
    }

    //Método de ligação com a API PagSeguro
    public function doPayment()
    {
        // \PagSeguro\Domains\Requests\DirectPayment\
        $creditCard = new DirectPaymentCreditCard();
        $creditCard->setReceiverEmail(getenv('PAGSEGURO_EMAIL'));
        $creditCard->setReference($this->reference);
        $creditCard->setCurrency("BRL");

        foreach ($this->items as $item) {
            $creditCard->addItems()->withParameters(
                $this->reference,
                $item['name'],
                $item['qtd'],
                $item['price']
            );
        }

        //Necessário passar domínio de e-mail: @sandbox.pagseguro.com.br
        $name = $this->user['first_name'] . ' ' . $this->user['last_name'];
        $email = getenv('PAGSEGURO_ENV') == 'sandbox' ? 'email@sandbox.pagseguro.com.br' : $this->user['email'];
        $creditCard->setSender()->setName($name);
        $creditCard->setSender()->setEmail($email);

        $creditCard->setSender()->setPhone()->withParameters(
            11,
            56273440
        );

        $creditCard->setSender()->setDocument()->withParameters(
            'CPF',
            '24628446350'
        );

        $creditCard->setSender()->setHash($this->data['hash']);

        $creditCard->setSender()->setIp('127.0.0.0');

        $creditCard->setShipping()->setAddress()->withParameters(
            'Av. Brig. Faria Lima',
            '1384',
            'Jardim Paulistano',
            '01452002',
            'São Paulo',
            'SP',
            'BRA',
            'apto. 114'
        );

        $creditCard->setBilling()->setAddress()->withParameters(
            'Av. Brig. Faria Lima',
            '1384',
            'Jardim Paulistano',
            '01452002',
            'São Paulo',
            'SP',
            'BRA',
            'apto. 114'
        );

        list($installmentNumber, $installmentValue) = explode("|", $this->data['installments']);
        $installmentValue = number_format((float) $installmentValue, 2, '.', '');
        $creditCard->setToken($this->data['card_token']);
        $creditCard->setInstallment()->withParameters($installmentNumber, $installmentValue);
        $creditCard->setHolder()->setBirthdate('01/10/1979');
        $creditCard->setHolder()->setName($this->data['card_name']);

        $creditCard->setHolder()->setPhone()->withParameters(
            11,
            56273440
        );

        $creditCard->setHolder()->setDocument()->withParameters(
            'CPF',
            '24628446350'
        );

        $creditCard->setMode('DEFAULT');

        $result = $creditCard->register(
            Configure::getAccountCredentials()
        );

        return $result;
    }
}
