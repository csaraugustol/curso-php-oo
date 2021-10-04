<?php

use Product as GlobalProduct;

class Product
{
    public $props = [];

    public function __set($name, $value)
    {
        $this->props[$name] = $value;
    }

    public function __toString()
    {
        //return "Você tentou printar a classe: " . __CLASS__;
        return json_encode($this->props);
    }
}

$product = new Product();
$product->name = 'Lasanha';
$product->price = 19.99;

print $product;
