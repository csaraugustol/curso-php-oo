<?php

namespace LojaVirtual\Controller\Admin;

use LojaVirtual\View\View;
use LojaVirtual\Session\Flash;
use LojaVirtual\Entity\Category;
use LojaVirtual\DataBase\Connection;
use Ausi\SlugGenerator\SlugGenerator;
use LojaVirtual\Security\Validator\Sanitizer;
use LojaVirtual\Security\Validator\Validator;
use LojaVirtual\Authenticator\CheckUserLogged;

class CategoriesController
{
    use CheckUserLogged;

    public function __construct()
    {
        if (!$this->checkAuthenticator()) return header('Location: ' . HOME . '/auth/login');
    }

    public function index()
    {
        $view = new View('admin/categories/index.phtml');
        $view->categories = (new Category(Connection::getInstance()))->findAll();

        return $view->render();
    }

    public function new()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
                return header('Location: ' . HOME . '/admin/categories');
            }

            $view = new View('/admin/categories/new.phtml');
            return $view->render();
        } catch (\Exception $e) {
            if (APP_DEBUG) {
                Flash::sendMessageSession('error', $e->getMessage());
                return header('Location: ' . HOME . '/admin/categories');
            }
            Flash::sendMessageSession('error', 'Ocorreu um problema interno, por favor contacte o admin.');
            return header('Location: ' . HOME . '/admin/categories');
        }
    }

    public function edit($id = null)
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

                return header('Location: ' . HOME . '/admin/categories/edit/' . $id);
            }

            $view = new View('admin/admin/categories/edit.phtml');
            $view->category = (new Category(Connection::getInstance()))->findById($id);

            return $view->render();
        } catch (\Exception $e) {
            if (APP_DEBUG) {
                Flash::sendMessageSession('error', $e->getMessage());
                return header('Location: ' . HOME . '/admin/categories');
            }
            Flash::sendMessageSession('error', 'Ocorreu um problema interno, por favor contacte o admin.');
            return header('Location: ' . HOME . '/admin/categories');
        }
    }

    public function remove($id = null)
    {
        try {
            $post = new Category(Connection::getInstance());

            if (!$post->delete($id)) {
                Flash::sendMessageSession('error', 'Erro ao realizar remoção do categoria!');
                return header('Location: ' . HOME . '/admin/categories');
            }

            Flash::sendMessageSession('success', 'Categoria removida com sucesso!');
            return header('Location: ' . HOME . '/admin/categories');
        } catch (\Exception $e) {
            if (APP_DEBUG) {
                Flash::sendMessageSession('error', $e->getMessage());
                return header('Location: ' . HOME . '/admin/categories');
            }
            Flash::sendMessageSession('error', 'Ocorreu um problema interno, por favor contacte o admin.');
            return header('Location: ' . HOME . '/admin/categories');
        }
    }
}
