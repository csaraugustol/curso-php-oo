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

class CategoriesController
{
    /**
     * Lista categorias
     *
     * @return redirect
     */
    public function index()
    {
        try {
            $view = new View('adm/categories/index.phtml');
            $view->categories = (new Category(Connection::getInstance()))->findAll();
        } catch (Exception $exception) {
            Flash::returnErrorExceptionMessage(
                $exception,
                'Erro ao carregar dados das categorias!',
            );

            return header('Location: ' . HOME . '/categories');
        }

        return $view->render();
    }

    /**
     * Cria nova categoria
     *
     * @return redirect
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
        } catch (Exception $exception) {
            Flash::returnErrorExceptionMessage(
                $exception,
                'Erro ao executar criação da categoria. Contate o administrador!'
            );

            return header('Location: ' . HOME . '/categories');
        }

        return header('Location: ' . HOME . '/categories');
    }

    /**
     * Edição de uma categoria
     *
     * @param int|null $id
     * @return redirect
     */
    public function edit(int $id = null)
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
        } catch (Exception $exception) {
            Flash::returnErrorExceptionMessage(
                $exception,
                'Erro ao executar edição da categoria. Contate o administrador!'
            );

            return header('Location: ' . HOME . '/categories');
        }

        return header('Location: ' . HOME . '/categories');
    }

    /**
     * Remove uma categoria
     *
     * @param int|null $id
     * @return redirect
     */
    public function remove(int $id = null)
    {
        try {
            $category = (new Category(Connection::getInstance()));
            if (!$category->delete($id)) {
                Flash::sendMessageSession('danger', 'Erro ao apagar categoria!');
                return header('Location: ' . HOME . '/categories/new');
            }

            Flash::sendMessageSession('warning', 'Categoria removida com sucesso!');
        } catch (Exception $exception) {
            Flash::returnErrorExceptionMessage(
                $exception,
                'Erro ao executar remoção da categoria. Contate o administrador!'
            );

            return header('Location: ' . HOME . '/categories');
        }

        return header('Location: ' . HOME . '/categories');
    }
}
