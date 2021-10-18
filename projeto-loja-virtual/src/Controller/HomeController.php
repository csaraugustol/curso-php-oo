<?php

namespace LojaVirtual\Controller;

use LojaVirtual\View\View;
use LojaVirtual\Entity\Product;
use LojaVirtual\DataBase\Connection;

class HomeController
{
    public function index()
    {
        $view = new View('site/index.phtml');
        $view->products = (new Product(Connection::getInstance()))->returnAllProductsWithThumb();
        return $view->render();
    }
}
