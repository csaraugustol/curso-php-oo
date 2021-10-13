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
    //Método de exibição dos posts relacionados a uma categoria
    public function index($slug)
    {
        try {
            $connection = Connection::getInstance();
            $category = current((new Category($connection))
            ->filterWithConditions(['slug' => $slug]));

            $view = new View('site/category.phtml');
            $view->posts = (new Post($connection))
            ->filterWithConditions(['category_id' => $category['id']]);
            $view->category = $category['name'];
            return $view->render();
        } catch (Exception $e) {
            Flash::sendMessageSession('warning', 'Nenhum Post para a categoria '
             . $category['name'] . ' foi encontrado!');
            header('Location: ' . HOME);
        }
    }
}
