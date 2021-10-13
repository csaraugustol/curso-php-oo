<?php

namespace Blog\Controller;

use Blog\View\View;
use Blog\Entity\Post;
use Blog\Entity\Category;
use Blog\DataBase\Connection;

class HomeController
{
    //Método de exibição da view 'home'
    public function index()
    {
        $connection = Connection::getInstance();
        $view = new View('site/index.phtml');
        $view->posts = (new Post($connection))->findAll();
        $view->categories = (new Category($connection))->findAll('name, slug');
        return $view->render();
    }
}
