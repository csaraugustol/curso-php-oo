<?php

$classAnounymous = new class
{
    public function log($message)
    {
        return $message;
    }
};

class BankAccount
{
    public function withdraw($value, $classAnounymous)
    {
        return $classAnounymous->log('Logging withdraw...');
    }
}

$bankAccount = new BankAccount();
print $bankAccount->withdraw(19.99, $classAnounymous);


/*****OUTRA FORAM DE USAR CLASSE ANÔNIMA*****
$classAnounymousTow = '';

$bankAccountTwo = new BankAccount();
print $bankAccountTwo->withdraw(7.99, new class {
    public function log($message)
    {
        return $message;
    }
});
 *****OUTRA FORAM DE USAR CLASSE ANÔNIMA*****/
