<?php

use agregation\Cart;
use agregation\Product;

require __DIR__ . '/Product.php';
require __DIR__ . '/Cart.php';

$productOne = new Product();
$productOne->setId(1);
$productOne->setName("Camisa Cruzeiro 2021");
$productOne->setPrice(279.99);

$productTwo = new Product();
$productTwo->setId(2);
$productTwo->setName("Mala Cruzeiro");
$productTwo->setPrice(299.99);

$cart = new Cart();
$cart->addProduct($productOne);
$cart->addProduct($productTwo);

foreach ($cart->getProducts() as $product) {
    print $product->getId() . ' - ' . $product->getName() . '(R$ ' . $product->getPrice() . ')';
    print '<br>';
}
