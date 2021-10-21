<?php

namespace LojaVirtual\Controller;

use LojaVirtual\Authenticator\CheckUserLogged;
use LojaVirtual\DataBase\Connection;
use LojaVirtual\Entity\UserOrder;
use LojaVirtual\Session\Session;
use LojaVirtual\View\View;

class OrdersController
{
    use CheckUserLogged;

    public function my()
    {

        if (!$this->checkAuthenticator()) {
            return header('Location: ' . HOME);
        }

        $userId = Session::verifyExistsKey('user')['id'];

        $userOrders = (new UserOrder(Connection::getInstance()))
        ->filterWithConditions(['user_id' => $userId]);

        $view = new View('site/my_orders.phtml');
        $view->userOrders = $userOrders;
        return $view->render();
    }
}
