<?php

namespace LojaVirtual\Controller;

use Exception;
use LojaVirtual\View\View;
use LojaVirtual\Session\Flash;
use LojaVirtual\Session\Session;
use LojaVirtual\Entity\UserOrder;
use LojaVirtual\DataBase\Connection;
use LojaVirtual\Authenticator\CheckUserLogged;

class OrdersController
{
    use CheckUserLogged;

    /**
     * Lista todas os pedidos do usuário
     *
     * @return redirect
     */
    public function my()
    {
        if (!$this->checkAuthenticator()) {
            return header('Location: ' . HOME);
        }

        $userId = Session::verifyExistsKey('user')['id'];

        try {
            $userOrders = (new UserOrder(Connection::getInstance()))
                ->filterWithConditions(['user_id' => $userId]);
            $view = new View('site/my_orders.phtml');
            $view->userOrders = $userOrders;

            return $view->render();
        } catch (Exception $exception) {
            Flash::returnExceptionErrorMessage(
                $exception,
                'Erro interno ao carregar os dados!'
            );
            return header('Location: ' . HOME);
        }
    }
}
