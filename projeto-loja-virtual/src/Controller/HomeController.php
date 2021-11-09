<?php

namespace LojaVirtual\Controller;

use Exception;
use LojaVirtual\View\View;
use LojaVirtual\Session\Flash;
use LojaVirtual\Entity\Product;
use LojaVirtual\DataBase\Connection;

class HomeController
{
    /**
     * Exibe a página inicial
     *
     * @return redirect
     */
    public function index()
    {
        try {
            $view = new View('site/index.phtml');
            $view->products = (new Product(Connection::getInstance()))
                ->returnAllProductsWithThumb();
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
