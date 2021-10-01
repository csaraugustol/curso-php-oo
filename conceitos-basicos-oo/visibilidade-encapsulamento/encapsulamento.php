<?php

class BankAccount
{
    private $balance = 0;

    public function __construct()
    {
        $this->balance = 30;
    }

    public function deposit($money)
    {
        $this->balance += $money;
    }

    public function withdraw($money)
    {
        if ($money > $this->balance)
            return false;

        $this->balance -= $money;
    }

    /*public function setBalance($value)
    {
        $this->balance = $value;
    }*/

    public function getBalance()
    {
        return $this->balance;
    }
}

$bankAccount = new BankAccount();
$bankAccount->deposit(100);
$bankAccount->withdraw(15);
// $bankAccount->balance = 1000;
print $bankAccount->getBalance();
