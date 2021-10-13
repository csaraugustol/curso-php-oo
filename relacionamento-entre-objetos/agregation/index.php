<?php

require __DIR__ . '/Product.php';
require __DIR__ . '/Cart.php';

$product1 = new Product();
$product1->setId(1);
$product1->setName("Camisa Cruzeiro 2021");
$product1->setPrice(279.99);

$product2 = new Product();
$product2->setId(2);
$product2->setName("Mala Cruzeiro");
$product2->setPrice(299.99);


$cart = new Cart();
$cart->addProduct($product1);
$cart->addProduct($product2);

foreach ($cart->getProducts() as $product) {
    print $product->getId() . ' - ' . $product->getName() . '(R$ ' . $product->getPrice() . ')';
    print '<br>';
}
