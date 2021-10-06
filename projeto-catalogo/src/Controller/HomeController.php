<?php

namespace Instituicao\Controller;

use Instituicao\View\View;
use Instituicao\Entity\Product;
use Instituicao\DataBase\Connection;

class HomeController
{
    public function index()
    {
        $connection = Connection::getInstance();
        $view = new View('site/index.phtml');
        $view->products = (new Product($connection))->findAllProducts();
        return $view->render();
    }
}
