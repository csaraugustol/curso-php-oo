<?php

namespace LojaVirtual\Controller;

use Exception;
use LojaVirtual\View\View;
use LojaVirtual\Session\Flash;
use LojaVirtual\Entity\Product;
use LojaVirtual\DataBase\Connection;

class ProductController
{
    /**
     * Mostra detalhes do produto
     *
     * @param string $slug
     * @return redirect
     */
    public function view(string $slug)
    {
        try {
            $product = (new Product(Connection::getInstance()))
                ->returnProductWithImages($slug, true);
            $lgPhoto = isset($product['images']) &&
                count($product['images']) ? array_shift($product['images']) : false;

            $view = new View('site/single.phtml');
            $view->product = $product;
            $view->lgPhoto = $lgPhoto;
        } catch (Exception $exception) {
            Flash::returnExceptionErrorMessage(
                $exception,
                'Erro interno ao carregar os dados!'
            );

            return header('Location: ' . HOME);
        }

        return $view->render();
    }
}
