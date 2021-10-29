<?php

namespace Catalogo\Controller;

use Catalogo\View\View;
use Catalogo\Entity\Product;
use Catalogo\DataBase\Connection;

class HomeController
{
    /**
     * Retorna página principal
     *
     * @return redirect
     */
    public function index()
    {
        $connection = Connection::getInstance();
        $view = new View('site/index.phtml');
        $view->products = (new Product($connection))->findAllProducts();

        return $view->render();
    }
}
