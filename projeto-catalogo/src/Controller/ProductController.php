<?php

namespace Catalogo\Controller;

use Catalogo\View\View;
use Catalogo\Entity\Product;
use Catalogo\DataBase\Connection;

class ProductController
{
    /**
     * Exibe os detalhes do Produto
     *
     * @param int $id
     * @return redirect
     */
    public function index(int $id)
    {
        $connection = Connection::getInstance();
        $view = new View('site/product.phtml');
        $view->product = (new Product($connection))->findProductById($id);

        return $view->render();
    }
}
