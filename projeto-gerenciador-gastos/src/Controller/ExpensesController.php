<?php

namespace GGP\Controller;

use Exception;
use GGP\View\View;
use GGP\Entity\User;
use GGP\Session\Flash;
use GGP\Entity\Expense;
use GGP\Entity\Category;
use GGP\Session\Session;
use GGP\DataBase\Connection;

class ExpensesController
{
    /**
     * Lista todas as despesas
     *
     * @return string
     */
    public function index(): string
    {
        $view = new View('expenses/index.phtml');
        $view->expenses = (new Expense(Connection::getInstance()))->filterWithConditions(
            ['user_id' => Session::verifyExistsKey('user')['id']]
        );
        return $view->render();
    }

    /**
     * Cria uma despesa
     *
     * @return string
     */
    public function new(): string
    {
        try {
            $connection = Connection::getInstance();
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $view = new View('expenses/new.phtml');
                $view->categories = (new Category($connection))->findAll();
                $view->users = (new User($connection))->findAll();
                return $view->render();
            }
            $data = $_POST;
            $data['user_id'] = Session::verifyExistsKey('user')['id'];
            $expense = new Expense($connection);
            $expense->insert($data);
            return header('Location: ' . HOME . '/expenses');
        } catch (Exception $exception) {
            Flash::returnMessageExceptionError(
                $exception,
                'Erro ao tentar criar uma despesa!'
            );
            return header('Location: ' . HOME . '/expenses');
        }
    }

    /**
     * Edita uma despesa
     *
     * @param int $id
     * @return string
     */
    public function edit(int $id): string
    {
        try {
            $view = new View('expenses/edit.phtml');
            $connection = Connection::getInstance();
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $view->categories = (new Category($connection))->findAll();
                $view->users = (new User($connection))->findAll();
                $view->expense = (new Expense($connection))->findById($id);
                return $view->render();
            }
            $data = $_POST;
            $data['id'] = $id;
            $expense = new Expense($connection);
            $expense->update($data);
            return header('Location: ' . HOME . '/expenses');
        } catch (Exception $exception) {
            Flash::returnMessageExceptionError(
                $exception,
                'Erro ao tentar editar uma despesa!'
            );
            return header('Location: ' . HOME . '/expenses');
        }
    }

    /**
     * Remove uma despesa
     *
     * @param int $id
     * @return string
     */
    public function remove(int $id)
    {
        try {
            $expense = new Expense(Connection::getInstance());
            $expense->delete($id);
            return header('Location: ' . HOME . '/expenses');
        } catch (Exception $exception) {
            Flash::returnMessageExceptionError(
                $exception,
                'Erro ao tentar deletar uma despesa!'
            );
            return header('Location: ' . HOME . '/expenses');
        }
    }
}
