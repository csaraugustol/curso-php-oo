<?php

namespace Blog\Controller;

use Exception;
use Blog\View\View;
use Blog\Entity\User;
use Blog\Session\Flash;
use Blog\DataBase\Connection;
use Blog\Security\PasswordHash;
use Blog\Security\Validator\Sanitizer;
use Blog\Security\Validator\Validator;

class UsersController
{
    /**
     * Exibe todos os usuários
     *
     * @return redirect
     */
    public function index()
    {
        try {
            $view = new View('adm/users/index.phtml');
            $view->users = (new User(Connection::getInstance()))->findAll();
        } catch (Exception $exception) {
            Flash::returnErrorExceptionMessage(
                $exception,
                'Erro ao carregar dados dos usuários!',
            );

            return header('Location: ' . HOME . '/users');
        }

        return $view->render();
    }

    /**
     * Cria um novo usuário
     *
     * @return redirect
     */
    public function new()
    {
        try {
            $connection = Connection::getInstance();

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $view = new View('adm/users/new.phtml');
                return $view->render();
            }

            $data = $_POST;
            Sanitizer::sanitizeData($data, User::$filters);
            if (!Validator::validateRequiredFields($data)) {
                Flash::sendMessageSession('warning', 'Preencha todos os campos!');
                return header('Location: ' . HOME . '/users/new');
            }

            if (!Validator::validatePasswordMinStringLenght($data['password'])) {
                Flash::sendMessageSession(
                    'warning',
                    'A senha deve conter no minímo 6 caracteres!'
                );
                return header('Location: ' . HOME . '/users/new');
            }

            if (!Validator::validatePasswordConfirm($data['password'], $data['password_confirm'])) {
                Flash::sendMessageSession('warning', 'Senha digitadas não são iguais!');
                return header('Location: ' . HOME . '/users/new');
            }

            $user = new User($connection);

            $data['password'] = PasswordHash::hashPassword($data['password']);
            unset($data['password_confirm']);

            if (!$user->insert($data)) {
                Flash::sendMessageSession('danger', 'Erro ao criar usuário!');
                return header('Location: ' . HOME . '/users/new');
            }

            Flash::sendMessageSession('success', 'Usuário criado com sucesso!');
        } catch (Exception $exception) {
            Flash::returnErrorExceptionMessage(
                $exception,
                'Erro ao executar criação do usuário. Contate o administrador!'
            );

            return header('Location: ' . HOME . '/users');
        }

        return header('Location: ' . HOME . '/users');
    }

    /**
     * Edição de um usuário
     *
     * @param int|null $id
     * @return redirect
     */
    public function edit(int $id = null)
    {
        try {
            $connection = Connection::getInstance();

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $view = new View('adm/users/edit.phtml');
                $view->user = (new User($connection))->findById($id);
                return $view->render();
            }
            $data = $_POST;

            Sanitizer::sanitizeData($data, User::$filters);
            $data['id'] = (int) $id;

            if (!Validator::validateRequiredFields($data)) {
                Flash::sendMessageSession('warning', 'Preencha todos os campos!');
                return header('Location: ' . HOME . '/users/edit/' . $id);
            }

            $user = new User($connection);

            /**
             * Condição para criptografar senha
             */
            if ($data['password']) {
                if (!Validator::validatePasswordMinStringLenght($data['password'])) {
                    Flash::sendMessageSession('warning', 'A senha deve conter no minímo 6 caracteres!');
                    return header('Location: ' . HOME . '/users/new');
                }

                if (!Validator::validatePasswordConfirm($data['password'], $data['password_confirm'])) {
                    Flash::sendMessageSession('warning', 'Senha digitadas não são iguais!');
                    return header('Location: ' . HOME . '/users/new');
                }
                $data['password'] = PasswordHash::hashPassword($data['password']);
            }
            unset($data['password']);
            unset($data['password_confirm']);

            if (!$user->update($data)) {
                Flash::sendMessageSession('danger', 'Erro ao atualizar usuário!');
                return header('Location: ' . HOME . '/users/new');
            }

            Flash::sendMessageSession('success', 'Usuário atualizado com sucesso!');
        } catch (Exception $exception) {
            Flash::returnErrorExceptionMessage(
                $exception,
                'Erro ao executar edição do usuário. Contate o administrador!'
            );

            return header('Location: ' . HOME . '/users');
        }

        return header('Location: ' . HOME . '/users');
    }

    /**
     * Remove um usuário
     *
     * @param int|null $id
     * @return redirect
     */
    public function remove(int $id = null)
    {
        try {
            $user = (new User(Connection::getInstance()));
            if (!$user->delete($id)) {
                Flash::sendMessageSession('danger', 'Erro ao apagar usuário!');
                return header('Location: ' . HOME . '/users/new');
            }

            Flash::sendMessageSession('success', 'Usuário removido com sucesso!');
        } catch (Exception $exception) {
            Flash::returnErrorExceptionMessage(
                $exception,
                'Erro ao executar remoção do usuário. Contate o administrador!'
            );

            return header('Location: ' . HOME . '/users');
        }

        return header('Location: ' . HOME . '/users');
    }
}
