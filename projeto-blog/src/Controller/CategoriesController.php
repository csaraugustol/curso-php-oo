<?php

namespace Blog\Controller;

use Exception;
use Blog\View\View;
use Blog\Session\Flash;
use Blog\Entity\Category;
use Blog\DataBase\Connection;
use Ausi\SlugGenerator\SlugGenerator;
use Blog\Security\Validator\Sanitizer;
use Blog\Security\Validator\Validator;
use Blog\Authenticator\CheckUserLogged;

class CategoriesController
{
    use CheckUserLogged;

    /**
     * Verifica se o usuário está autenticado
     * e pode acessar o sistema
     */
    public function __construct()
    {
        if (!$this->checkAuthenticator()) {
            Flash::sendMessageSession("danger", "Faça o login para acesar!");
            return header("Location: " . HOME . '/auth/login');
        }
    }

    /**
     * Lista categorias
     *
     * @return string
     */
    public function index()
    {
        $view = new View('adm/categories/index.phtml');
        $view->categories = (new Category(Connection::getInstance()))->findAll();
        return $view->render();
    }

    /**
     * Cria nova categoria
     *
     * @return string
     */
    public function new()
    {
        try {
            $connection = Connection::getInstance();

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $view = new View('adm/categories/new.phtml');
                return $view->render();
            }

            $data = $_POST;
            Sanitizer::sanitizeData($data, Category::$filters);
            if (!Validator::validateRequiredFields($data)) {
                Flash::sendMessageSession('warning', 'Preencha todos os campos!');
                return header('Location: ' . HOME . '/categories/new');
            }

            $category = new Category($connection);
            $data['slug'] = (new SlugGenerator())->generate($data['name']);
            if (!$category->insert($data)) {
                Flash::sendMessageSession('danger', 'Erro ao criar categoria!');
                return header('Location: ' . HOME . '/categories/new');
            }

            Flash::sendMessageSession('success', 'Categoria criada com sucesso!');
            return header('Location: ' . HOME . '/categories');
        } catch (Exception $exception) {
            if (APP_DEBUG) {
                Flash::sendMessageSession('danger', $exception->getMessage());
                return header('Location: ' . HOME . '/categories');
            }

            Flash::sendMessageSession('danger', 'Ocorreu um erro interno. Entre em contato com o administrador!');
            return header('Location: ' . HOME . '/categories');
        }
    }

    /**
     * Edição de uma categoria
     *
     * @param int $id
     * @return string
     */
    public function edit($id = null)
    {
        try {
            $connection = Connection::getInstance();

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $view = new View('adm/categories/edit.phtml');
                $view->category = (new Category($connection))->findById($id);
                return $view->render();
            }
            $data = $_POST;

            Sanitizer::sanitizeData($data, Category::$filters);
            $data['id'] = (int) $id;

            if (!Validator::validateRequiredFields($data)) {
                Flash::sendMessageSession('warning', 'Preencha todos os campos!');
                return header('Location: ' . HOME . '/categories/edit/' . $id);
            }

            $category = new Category($connection);
            if (!$category->update($data)) {
                Flash::sendMessageSession('danger', 'Erro ao atualizar categoria!');
                return header('Location: ' . HOME . '/categories/new');
            }

            Flash::sendMessageSession('success', 'Categoria atualizada com sucesso!');
            return header('Location: ' . HOME . '/categories');
        } catch (Exception $exception) {
            if (APP_DEBUG) {
                Flash::sendMessageSession('danger', $exception->getMessage());
                return header('Location: ' . HOME . '/categories');
            }

            Flash::sendMessageSession('danger', 'Ocorreu um erro interno. Entre em contato com o administrador!');
            return header('Location: ' . HOME . '/categories');
        }
    }

    /**
     * Remove uma categoria
     *
     * @param int $id
     * @return string
     */
    public function remove($id = null)
    {
        try {
            $category = (new Category(Connection::getInstance()));
            if (!$category->delete($id)) {
                Flash::sendMessageSession('danger', 'Erro ao apagar categoria!');
                return header('Location: ' . HOME . '/categories/new');
            }

            Flash::sendMessageSession('warning', 'Categoria removida com sucesso!');
            return header('Location: ' . HOME . '/categories');
        } catch (Exception $exception) {
            if (APP_DEBUG) {
                Flash::sendMessageSession('danger', $exception->getMessage());
                return header('Location: ' . HOME . '/categories');
            }

            Flash::sendMessageSession('danger', 'Ocorreu um erro interno. Entre em contato com o administrador!');
            return header('Location: ' . HOME . '/categories');
        }
    }
}
