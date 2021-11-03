<?php

namespace Vex\Exceptions;

use Exception;
use Throwable;

//O nome da classe deve fazer referência ao tipo de erro que será tratado
class MyCustomException extends Exception
{
    //Pode passar a mensagem aqui ou na classe, no retorno da Exception
    //Isso tira a necessidade da criação do construtor
    public function __construct($message = "Parâmetro dois deve ser menor ou 
    igual a 10", $code = 0, Throwable $previus = null)
    {
        parent::__construct($message, $code, $previus);
    }
}
