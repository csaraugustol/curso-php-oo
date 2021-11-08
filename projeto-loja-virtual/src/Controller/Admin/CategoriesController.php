<?php

namespace LojaVirtual\Controller\Admin;

use Exception;
use LojaVirtual\View\View;
use LojaVirtual\Session\Flash;
use LojaVirtual\Entity\Category;
use LojaVirtual\DataBase\Connection;
use Ausi\SlugGenerator\SlugGenerator;
use LojaVirtual\Security\Validator\Sanitizer;
use LojaVirtual\Security\Validator\Validator;

class CategoriesController
{
    /**
     * Listagem de categorias
     *
     * @return redirect
     */
    public function index()
    {
        try {
            $view = new View('admin/categories/index.phtml');
            $view->categories = (new Category(Connection::getInstance()))->findAll();
        } catch (Exception $exception) {
            Flash::returnExceptionErrorMessage(
                $exception,
                'Erro interno ao carregar os dados!'
            );

            return header('Location: ' . HOME . '/admin/categories');
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
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $view = new View('/admin/categories/new.phtml');
                return $view->render();
            }

            $data = $_POST;
            $data = Sanitizer::sanitizeData($data, Category::$filters);

            if (!Validator::validateRequiredFields($data)) {
                Flash::sendMessageSession('warning', 'Preencha todos os campos!');
                return header('Location: ' . HOME . '/admin/categories/new');
            }

            $post = new Category(Connection::getInstance());
            $data['slug'] = (new SlugGenerator())->generate($data['name']);

            if (!$post->insert($data)) {
                Flash::sendMessageSession('error', 'Erro ao criar categoria!');
                return header('Location: ' . HOME . '/admin/categories/new');
            }

            Flash::sendMessageSession('success', 'Categoria criada com sucesso!');
        } catch (Exception $exception) {
            Flash::returnExceptionErrorMessage(
                $exception,
                'Ocorreu um erro interno ao salvar, por favor contate o administrador.'
            );

            return header('Location: ' . HOME . '/admin/categories');
        }

        return header('Location: ' . HOME . '/admin/categories');
    }

    /**
     * Edita uma categoria
     *
     * @param integer $id
     * @return redirect
     */
    public function edit(int $id)
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $view = new View('admin/categories/edit.phtml');
                $view->category = (new Category(Connection::getInstance()))->findById($id);

                return $view->render();
            }

            $data = $_POST;
            $data = Sanitizer::sanitizeData($data, Category::$filters);
            $data['id'] = (int) $id;

            if (!Validator::validateRequiredFields($data)) {
                Flash::sendMessageSession('warning', 'Preencha todos os campos!');

                return header('Location: ' . HOME . '/admin/categories/edit/' . $id);
            }

            $post = new Category(Connection::getInstance());

            if (!$post->update($data)) {
                Flash::sendMessageSession('error', 'Erro ao atualizar categoria!');

                return header('Location: ' . HOME . '/admin/categories/edit/' . $id);
            }

            Flash::sendMessageSession('success', 'Categoria atualizada com sucesso!');
        } catch (Exception $exception) {
            Flash::returnExceptionErrorMessage(
                $exception,
                'Ocorreu um erro interno ao editar, por favor contate o administrador.'
            );

            return header('Location: ' . HOME . '/admin/categories');
        }

        return header('Location: ' . HOME . '/admin/categories');
    }

    /**
     * Remove uma categoria
     *
     * @param integer $id
     * @return redirect
     */
    public function remove(int $id)
    {
        try {
            $post = new Category(Connection::getInstance());

            if (!$post->delete($id)) {
                Flash::sendMessageSession('error', 'Erro ao realizar remoção do categoria!');
                return header('Location: ' . HOME . '/admin/categories');
            }

            Flash::sendMessageSession('success', 'Categoria removida com sucesso!');
        } catch (Exception $exception) {
            Flash::returnExceptionErrorMessage(
                $exception,
                'Ocorreu um erro interno ao remover, por favor contate o administrador.'
            );

            return header('Location: ' . HOME . '/admin/categories');
        }

        return header('Location: ' . HOME . '/admin/categories');
    }
}
