<?php

namespace Blog\Controller;

use Exception;
use Blog\View\View;
use Blog\Entity\Post;
use Blog\Session\Flash;
use Blog\DataBase\Connection;

class PostController
{
    /**
     * Exibe detalhes de um post da listagem geral
     *
     * @param string $slug
     * @return string
     */
    public function index($slug)
    {
        try {
            $post = new Post(Connection::getInstance());
            $view = new View('site/single.phtml');
            $view->post = current($post->filterWithConditions(['slug' => $slug]));
            return $view->render();
        } catch (Exception $exception) {
            Flash::returnErrorExceptionMessage(
                $exception,
                'Postagem não encontrada!'
            );
            return header('Location: ' . HOME);
        }
    }
}
