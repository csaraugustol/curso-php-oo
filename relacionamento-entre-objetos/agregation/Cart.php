<?php

namespace agregation;

use Product;

class Cart
{
    /**
     * @var array
     */
    private $products;

    public function addProduct(Product $product)
    {
        $this->products[] = $product;
    }

    /**
     * Obtem os produtos que estão no carrinho
     * de compras
     *
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }
}
