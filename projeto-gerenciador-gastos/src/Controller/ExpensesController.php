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
     * @return redirect
     */
    public function index()
    {
        try {
            $view = new View('expenses/index.phtml');
            $view->expenses = (new Expense(Connection::getInstance()))
                ->filterWithConditions(
                    ['user_id' => Session::returnUserSession('user')['id']]
                );
        } catch (Exception $exception) {
            Flash::returnMessageExceptionError(
                $exception,
                'Erro ao carregar dados das despesas!'
            );

            return header("Location: " . HOME . '/expenses');
        }

        return $view->render();
    }

    /**
     * Cria uma despesa
     *
     * @return redirect
     */
    public function new()
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
            $data['user_id'] = Session::returnUserSession('user')['id'];
            $expense = new Expense($connection);
            $expense->insert($data);

            Flash::sendMessageSession("success", "Despesa criada com sucesso!");
        } catch (Exception $exception) {
            Flash::returnMessageExceptionError(
                $exception,
                'Erro ao tentar criar uma despesa!'
            );

            return header('Location: ' . HOME . '/expenses');
        }

        return header('Location: ' . HOME . '/expenses');
    }

    /**
     * Edita uma despesa
     *
     * @param int $id
     * @return redirect
     */
    public function edit(int $id)
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

            Flash::sendMessageSession("success", "Despesa atualizada com sucesso!");
        } catch (Exception $exception) {
            Flash::returnMessageExceptionError(
                $exception,
                'Erro ao tentar editar uma despesa!'
            );

            return header('Location: ' . HOME . '/expenses');
        }

        return header('Location: ' . HOME . '/expenses');
    }

    /**
     * Remove uma despesa
     *
     * @param int $id
     * @return redirect
     */
    public function remove(int $id)
    {
        try {
            $expense = new Expense(Connection::getInstance());
            $expense->delete($id);

            Flash::sendMessageSession("success", "Despesa deletada com sucesso!");
        } catch (Exception $exception) {
            Flash::returnMessageExceptionError(
                $exception,
                'Erro ao tentar deletar uma despesa!'
            );

            return header('Location: ' . HOME . '/expenses');
        }

        return header('Location: ' . HOME . '/expenses');
    }
}
