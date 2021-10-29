<?php

namespace Blog\Controller;

use Exception;
use Blog\View\View;
use Blog\Entity\Post;
use Blog\Session\Flash;
use Blog\Entity\Category;
use Blog\DataBase\Connection;

class HomeController
{
    /**
     * Lista todos os posts para
     * leitura de um usuário
     *
     * @return redirect
     */
    public function index()
    {
        try {
            $connection = Connection::getInstance();
            $view = new View('site/index.phtml');
            $view->posts = (new Post($connection))->findAll();
            $view->categories = (new Category($connection))->findAll('name, slug');
        } catch (Exception $exception) {
            Flash::returnErrorExceptionMessage(
                $exception,
                'Nenhum Post encontrado!',
                'warning'
            );

            return header('Location: ' . HOME);
        }

        return $view->render();
    }
}
