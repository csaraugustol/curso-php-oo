<?php

namespace LojaVirtual\Controller\Admin;

use Exception;
use LojaVirtual\View\View;
use LojaVirtual\Entity\User;
use LojaVirtual\Session\Flash;
use LojaVirtual\DataBase\Connection;
use LojaVirtual\Security\PasswordHash;
use LojaVirtual\Security\Validator\Sanitizer;
use LojaVirtual\Security\Validator\Validator;
use LojaVirtual\Authenticator\CheckUserLogged;

class UsersController
{
    use CheckUserLogged;

    public function __construct()
    {
        if (!$this->checkAuthenticator()) return header('Location: ' . HOME . '/auth/login');
    }

    /**
     * Lista todos os usuários
     *
     * @return redirect
     */
    public function index()
    {
        try {
            $view = new View('admin/users/index.phtml');
            $view->posts = (new User(Connection::getInstance()))->findAll();
        } catch (Exception $exception) {
            Flash::returnExceptionErrorMessage(
                $exception,
                'Erro interno ao carregar os dados!'
            );

            return header('Location: ' . HOME . '/admin/users');
        }

        return $view->render();
    }

    /**
     * Cria um usuário
     *
     * @return redirect
     */
    public function new()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $view = new View('admin/users/new.phtml');
                $view->users = (new User(Connection::getInstance()))->findAll('id, first_name, last_name');

                return $view->render();
            }

            $data = $_POST;
            $data = Sanitizer::sanitizeData($data, User::$filters);

            if (!Validator::validateRequiredFields($data)) {
                Flash::sendMessageSession('warning', 'Preencha todos os campos!');
                return header('Location: ' . HOME . '/admin/users/new');
            }

            if (!Validator::validatePasswordMinStringLenght($data['password'])) {
                Flash::sendMessageSession('warning', 'Senha deve conter pelo menos 6 caracteres!');
                return header('Location: ' . HOME . '/admin/users/new');
            }

            if (!Validator::validatePasswordConfirm($data['password'], $data['password_confirm'])) {
                Flash::sendMessageSession('warning', 'Senhas não conferem!');
                return header('Location: ' . HOME . '/admin/users/new');
            }

            $post = new User(Connection::getInstance());
            $data['password'] = PasswordHash::hashPassword($data['password']);
            unset($data['password_confirm']);

            if (!$post->insert($data)) {
                Flash::sendMessageSession('error', 'Erro ao criar usuário!');
                return header('Location: ' . HOME . '/admin/users/new');
            }

            Flash::sendMessageSession('success', 'Usuário criado com sucesso!');
        } catch (Exception $exception) {
            Flash::returnExceptionErrorMessage(
                $exception,
                'Ocorreu um erro interno ao salvar, por favor contate o administrador.'
            );

            return header('Location: ' . HOME . '/admin/users/new');
        }

        return header('Location: ' . HOME . '/admin/users');
    }

    /**
     * Edita um usuário
     *
     * @param integer|null $id
     * @return redirect
     */
    public function edit(int $id = null)
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $view = new View('admin/users/edit.phtml');
                $view->user = (new User(Connection::getInstance()))->findById($id);

                return $view->render();
            }

            $data = $_POST;
            $data = Sanitizer::sanitizeData($data, User::$filters);
            $data['id'] = (int) $id;

            if (!Validator::validateRequiredFields($data)) {
                Flash::sendMessageSession('warning', 'Preencha todos os campos!');
                return header('Location: ' . HOME . '/admin/users/edit/' . $id);
            }

            $post = new User(Connection::getInstance());

            if ($data['password']) {
                if (!Validator::validatePasswordMinStringLenght($data['password'])) {
                    Flash::sendMessageSession('warning', 'Senha deve conter pelo menos 6 caracteres!');
                    return header('Location: ' . HOME . '/admin/users/new');
                }

                if (!Validator::validatePasswordConfirm($data['password'], $data['password_confirm'])) {
                    Flash::sendMessageSession('warning', 'Senhas não conferem!');
                    return header('Location: ' . HOME . '/admin/users/new');
                }

                $data['password'] = PasswordHash::hashPassword($data['password']);
            } else {
                unset($data['password']);
            }
            unset($data['password_confirm']);

            if (!$post->update($data)) {
                Flash::sendMessageSession('error', 'Erro ao atualizar usuário!');
                return header('Location: ' . HOME . '/admin/users/edit/' . $id);
            }

            Flash::sendMessageSession('success', 'Usuário atualizado com sucesso!');
        } catch (Exception $exception) {
            Flash::returnExceptionErrorMessage(
                $exception,
                'Ocorreu um erro interno ao editar, por favor contate o administrador.'
            );

            return header('Location: ' . HOME . '/admin/users/edit/' . $id);
        }

        return header('Location: ' . HOME . '/admin/users');
    }

    /**
     * Remove um usuário
     *
     * @param integer|null $id
     * @return redirect
     */
    public function remove(int $id = null)
    {
        try {
            $post = new User(Connection::getInstance());

            if (!$post->delete($id)) {
                Flash::sendMessageSession('error', 'Erro ao realizar remoção do usuário!');
                return header('Location: ' . HOME . '/admin/users');
            }

            Flash::sendMessageSession('success', 'Usuário removido com sucesso!');
            return header('Location: ' . HOME . '/admin/users');
        } catch (Exception $exception) {
            Flash::returnExceptionErrorMessage(
                $exception,
                'Ocorreu um erro interno ao remover, por favor contate o administrador.'
            );

            return header('Location: ' . HOME . '/admin/users');
        }

        return header('Location: ' . HOME . '/admin/users');
    }
}
