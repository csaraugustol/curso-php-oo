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
use Blog\Authenticator\CheckUserLogged;

class UsersController
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
     * Exibe todos os usuários
     *
     * @return string
     */
    public function index()
    {
        $view = new View('adm/users/index.phtml');
        $view->users = (new User(Connection::getInstance()))->findAll();
        return $view->render();
    }

    /**
     * Cria um novo usuário
     *
     * @return string
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
                Flash::sendMessageSession('warning', 'A senha deve conter no minímo 6 caracteres!');
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
            return header('Location: ' . HOME . '/users');
        } catch (Exception $exception) {
            if (APP_DEBUG) {
                Flash::sendMessageSession('danger', $exception->getMessage());
                return header('Location: ' . HOME . '/users');
            }

            Flash::sendMessageSession('danger', 'Ocorreu um erro interno. Entre em contato com o administrador!');
            return header('Location: ' . HOME . '/users');
        }
    }

    /**
     * Edição de um usuário
     *
     * @param int $id
     * @return string
     */
    public function edit($id = null)
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

            //Condição para criptografar senha
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
            return header('Location: ' . HOME . '/users');
        } catch (Exception $exception) {
            if (APP_DEBUG) {
                Flash::sendMessageSession('danger', $exception->getMessage());
                return header('Location: ' . HOME . '/users');
            }

            Flash::sendMessageSession('danger', 'Ocorreu um erro interno. Entre em contato com o administrador!');
            return header('Location: ' . HOME . '/users');
        }
    }

    /**
     * Remove um usuário
     *
     * @param int $id
     * @return string
     */
    public function remove($id = null)
    {
        try {
            $user = (new User(Connection::getInstance()));
            if (!$user->delete($id)) {
                Flash::sendMessageSession('danger', 'Erro ao apagar usuário!');
                return header('Location: ' . HOME . '/users/new');
            }

            Flash::sendMessageSession('success', 'Usuário removido com sucesso!');
            return header('Location: ' . HOME . '/users');
        } catch (Exception $exception) {
            if (APP_DEBUG) {
                Flash::sendMessageSession('danger', $exception->getMessage());
                return header('Location: ' . HOME . '/users');
            }

            Flash::sendMessageSession('danger', 'Ocorreu um erro interno. Entre em contato com o administrador!');
            return header('Location: ' . HOME . '/users');
        }
    }
}
