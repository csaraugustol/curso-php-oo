<?php

class Product
{
    public $props = [];

    public function __set($name, $value)
    {
        $this->props[$name] = $value;
    }

    public function __get($name)
    {
        return $this->props[$name];
    }
}

$product = new Product();
$product->name = "Nome do Produto";
print $product->name;
$product->price = 19.99;
print $product->price;

//var_dump($product->props);
