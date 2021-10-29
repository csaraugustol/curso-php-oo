<?php

namespace LojaVirtual\Controller;

use LojaVirtual\DataBase\Connection;
use LojaVirtual\Entity\Product;
use LojaVirtual\View\View;

class ProductController
{
    public function view($slug)
    {
        $product = (new Product(Connection::getInstance()))->returnProductWithImages($slug, true);
        $lgPhoto = isset($product['images']) && count($product['images']) ? array_shift($product['images']) : false;
        $view = new View('site/single.phtml');
        $view->product = $product;
        $view->lgPhoto = $lgPhoto;
        return $view->render();
    }
}
