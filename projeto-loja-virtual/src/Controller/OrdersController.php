<?php

namespace LojaVirtual\Controller;

use LojaVirtual\View\View;
use LojaVirtual\Session\Session;
use LojaVirtual\Entity\UserOrder;
use LojaVirtual\DataBase\Connection;
use LojaVirtual\Authenticator\CheckUserLogged;

class OrdersController
{
    public function my()
    {
        if (!CheckUserLogged::checkAuthenticator()) {
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
