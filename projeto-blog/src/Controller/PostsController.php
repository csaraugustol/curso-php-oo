<?php

namespace Blog\Controller;

use Exception;
use Blog\View\View;
use Blog\Entity\Post;
use Blog\Entity\User;
use Blog\Session\Flash;
use Blog\Entity\Category;
use Blog\DataBase\Connection;
use Ausi\SlugGenerator\SlugGenerator;
use Blog\Security\Validator\Sanitizer;
use Blog\Security\Validator\Validator;

class PostsController
{
    /**
     * Exibe todos os posts para o usuário que está logado
     *
     * @return redirect
     */
    public function index()
    {
        try {
            $view = new View('adm/posts/index.phtml');
            $view->posts = (new Post(Connection::getInstance()))->findAll();
        } catch (Exception $exception) {
            Flash::returnErrorExceptionMessage(
                $exception,
                'Erro ao carregar dados dos posts!',
            );

            header('Location: ' . HOME . '/posts');
        }

        return $view->render();
    }

    /**
     * Cria um novo post
     *
     * @return redirect
     */
    public function new()
    {
        try {
            $connection = Connection::getInstance();

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $view = new View('adm/posts/new.phtml');
                $view->users = (new User($connection))
                    ->findAll('id, first_name, last_name');
                $view->categories = (new Category($connection))
                    ->findAll('id, name');

                return $view->render();
            }

            $data = $_POST;
            Sanitizer::sanitizeData($data, Post::$filters);
            if (!Validator::validateRequiredFields($data)) {
                Flash::sendMessageSession('warning', 'Preencha todos os campos!');
                return header('Location: ' . HOME . '/posts/new');
            }

            $post = new Post($connection);
            $data['slug'] = (new SlugGenerator())->generate($data['title']);
            if (!$post->insert($data)) {
                Flash::sendMessageSession('danger', 'Erro ao criar postagem!');
                return header('Location: ' . HOME . '/posts/new');
            }

            Flash::sendMessageSession('success', 'Postagem criada com sucesso!');
        } catch (Exception $exception) {
            Flash::returnErrorExceptionMessage(
                $exception,
                'Erro ao executar criação do post. Contate o administrador!'
            );
            return header('Location: ' . HOME . '/posts');
        }

        return header('Location: ' . HOME . '/posts');
    }

    /**
     * Edição de um post
     *
     * @param int|null $id
     * @return redirect
     */
    public function edit(int $id = null)
    {
        try {
            $connection = Connection::getInstance();

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $view = new View('adm/posts/edit.phtml');
                $view->categories = (new Category($connection))->findAll();
                $view->users = (new User($connection))->findAll();
                $view->post = (new Post($connection))->findById($id);
                return $view->render();
            }

            $data = $_POST;
            Sanitizer::sanitizeData($data, Post::$filters);
            $data['id'] = (int) $id;

            if (!Validator::validateRequiredFields($data)) {
                Flash::sendMessageSession('warning', 'Preencha todos os campos!');
                return header('Location: ' . HOME . '/posts/edit/' . $id);
            }

            $post = new Post($connection);
            if (!$post->update($data)) {
                Flash::sendMessageSession('danger', 'Erro ao atualizar postagem!');
                return header('Location: ' . HOME . '/posts/new');
            }

            Flash::sendMessageSession('success', 'Postagem atualizada com sucesso!');
        } catch (Exception $exception) {
            Flash::returnErrorExceptionMessage(
                $exception,
                'Erro ao executar edição do post. Contate o administrador!'
            );

            return header('Location: ' . HOME . '/posts');
        }

        return header('Location: ' . HOME . '/posts');
    }

    /**
     * Remove um post
     *
     * @param int|null $id
     * @return redirect
     */
    public function remove(int $id = null)
    {
        try {
            $post = (new Post(Connection::getInstance()));
            if (!$post->delete($id)) {
                Flash::sendMessageSession('danger', 'Erro ao apagar postagem!');
                return header('Location: ' . HOME . '/posts/new');
            }

            Flash::sendMessageSession('success', 'Postagem removida com sucesso!');
        } catch (Exception $exception) {
            Flash::returnErrorExceptionMessage(
                $exception,
                'Erro ao executar remoção do post. Contate o administrador!'
            );

            return header('Location: ' . HOME . '/posts');
        }

        return header('Location: ' . HOME . '/posts');
    }
}
