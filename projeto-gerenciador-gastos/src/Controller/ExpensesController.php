<?php

namespace GGP\Controller;

use GGP\View\View;
use GGP\Entity\User;
use GGP\Session\Flash;
use GGP\Entity\Expense;
use GGP\Entity\Category;
use GGP\Session\Session;
use GGP\DataBase\Connection;
use GGP\Authenticator\CheckUserLogged;

class ExpensesController
{
    use CheckUserLogged;

    //Método para verificar se o usuário está  autenticado
    //e pode acessar o sistema
    public function __construct()
    {
        if (!$this->checkAuthenticator()) {
            Flash::sendMessageSession("danger", "Faça o login para acesar!");
            return header("Location: " . HOME . '/auth/login');
        }
    }

    //Método de listagem de despesas
    public function index()
    {
        $view = new View('expenses/index.phtml');
        $view->expenses = (new Expense(Connection::getInstance()))->filterWithConditions(
            ['user_id' => Session::verifyExistsKey('user')['id']]
        );

        return $view->render();
    }

    //Método de criação de despesa
    public function new()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $connection = Connection::getInstance();

        if ($method == 'POST') {
            $data = $_POST;
            $data['user_id'] = Session::verifyExistsKey('user')['id'];
            $expense = new Expense($connection);
            $expense->insert($data);
            return header('Location: ' . HOME . '/expenses');
        }

        $view = new View('expenses/new.phtml');
        $view->categories = (new Category($connection))->findAll();
        $view->users = (new User($connection))->findAll();
        return $view->render();
    }

    //Método de edição de despesa
    public function edit($id)
    {
        $view = new View('expenses/edit.phtml');
        $connection = Connection::getInstance();
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method == 'POST') {
            $data = $_POST;
            $data['id'] = $id;

            $expense = new Expense($connection);
            $expense->update($data);
            return header('Location: ' . HOME . '/expenses');
        }

        $view->categories = (new Category($connection))->findAll();
        $view->users = (new User($connection))->findAll();
        $view->expense = (new Expense($connection))->findById($id);
        return $view->render();
    }

    //Método de remoção de despesa
    public function remove($id)
    {
        $expense = new Expense(Connection::getInstance());
        $expense->delete($id);
        return header('Location: ' . HOME . '/expenses');
    }
}
