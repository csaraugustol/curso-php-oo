<?php

namespace LojaVirtual\Controller;

use LojaVirtual\DataBase\Connection;
use LojaVirtual\Entity\Product;
use LojaVirtual\View\View;

class ProductController
{
    public function view($slug)
    {

        $view = new View('site/single.phtml');
        $view->product = (new Product(Connection::getInstance()))->returnProductWithImages($slug, true);
        $lgPhoto = isset($view->product['images']) && count($view->product['images'])
        ? array_shift($view->product['images']) : false;
        $view->lgPhoto = $lgPhoto;
        return $view->render();
    }
}
