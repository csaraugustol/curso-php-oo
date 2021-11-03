<?php

namespace Vex;

use Exception;
use InvalidArgumentException;
use Vex\Exceptions\MyCustomException;

class Sum
{
    public function doSum($firstNumber, $secondNumber)
    {
        if ($secondNumber > 10) {
            //Tratativa geral
            //throw new Exception("Parâmetro dois deve ser menor ou igual a 10");

            //Tratativa que nortea o tipo do erro
            //throw new InvalidArgumentException("Parâmetro dois deve ser menor ou igual a 10");

            //Tratativa com mensagem personalizada
            throw new MyCustomException();

            //A mensagem poderia ser passada aqui caso não houvesse o construtor
            //throw new MyCustomException("Parâmetro dois deve ser menor ou igual a 10");
        }
        return $firstNumber + $secondNumber;
    }
}
