<?php

namespace Instituicao\Controller;

use Instituicao\View\View;
use Instituicao\Entity\Product;
use Instituicao\DataBase\Connection;

class ProductController
{
    public function index(int $id)
    {
        $connection = Connection::getInstance();
        $view = new View('site/product.phtml');
        $view->product = (new Product($connection))->findProductById($id);
        return $view->render();
    }
}
