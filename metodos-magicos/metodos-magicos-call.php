<?php

class Product
{
    //Métodos comuns que não existem
    public function __call($name, $params)
    {
        print_r([$name, $params]);
    }

    //Métodos estáticos que não existem
    public static function __callStatic($name, $params)
    {
        print_r([$name, $params]);
    }
}

print Product::getConnection();
$product = new Product();
$product->save('Coxinha', 4.99);
