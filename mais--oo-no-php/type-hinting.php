<?php

//Força a mantar o tipo que está sendo exigido
declare(strict_types=1);

class Product
{
    private $name;
    private $price;

    //Espera um tipo string
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    //Espera um tipo float
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getPrice()
    {
        return $this->price;
    }
}

class Cart
{
    private $itens = [];

    //Espera um tipo Product
    public function addProduct(Product $product)
    {
        $this->itens[] = $product;
    }

    //É possível também tipar o retorno do método
    public function getItens(): array
    {
        return $this->itens;
    }
}

$firstProduct = new Product();
$firstProduct->setName('Suco de Manga');
$firstProduct->setPrice(3.95);

$secondProduct = new Product();
$secondProduct->setName('Pastel de Carne');
$secondProduct->setPrice(4.95);

$cart = new Cart();
$cart->addProduct($firstProduct);
$cart->addProduct($secondProduct);
