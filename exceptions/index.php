<?php

use Error;
use Vex\Sum;
use Vex\Exceptions\MyCustomException;

require __DIR__ . '/vendor/autoload.php';

try {
    $sum = new Sum();
    print $sum->doSum(7, 102); //Aqui passa dois arguementos
} catch (Error $e) {
    print_r($e->getTrace()); //Retorna um array
} catch (MyCustomException $e) { //ou-> Exception $e
    print $e->getMessage(); //Mensagem de erro criada
} finally {
    print '<br>';
    print "Finally...";
}
