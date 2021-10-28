<?php

namespace Blog\Controller;

use Exception;
use Blog\View\View;
use Blog\Entity\Post;
use Blog\Session\Flash;
use Blog\Entity\Category;
use Blog\DataBase\Connection;

class CategoryController
{
    /**
     * Exibe um post relacionado a uma categoria
     * para a leitura de um usuário
     *
     * @param string $slug
     * @return redirect
     */
    public function index(string $slug)
    {
        try {
            $connection = Connection::getInstance();
            $category = current((new Category($connection))
                ->filterWithConditions(['slug' => $slug]));

            $view = new View('site/category.phtml');
            $view->posts = (new Post($connection))
                ->filterWithConditions(['category_id' => $category['id']]);

                $view->category = $category;
        } catch (Exception $exception) {
            Flash::returnErrorExceptionMessage(
                $exception,
                'Nenhum Post para a categoria ' . $category['name'] . ' foi encontrado!',
                'warning'
            );

            return header('Location: ' . HOME);
        }

        return $view->render();
    }
}
