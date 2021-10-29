<?php

namespace LojaVirtual\Controller;

use Exception;
use LojaVirtual\DataBase\Connection;
use LojaVirtual\Entity\UserOrder;
use LojaVirtual\Payment\PagSeguro\CreditCard;
use LojaVirtual\Payment\PagSeguro\Notification;
use LojaVirtual\Payment\PagSeguro\SessionPagSeguro;
use LojaVirtual\Session\Session;
use LojaVirtual\View\View;

class CheckoutController
{

    public function index()
    {
        if (!Session::hasUserSession('user')) {
            return header('Location: ' . HOME . '/store/login');
        }

        if (!Session::hasUserSession('cart')) {
            return header("Lacation: " . HOME);
        }

        $cart = Session::verifyExistsKey('cart');

        $cart = array_map(function ($line) {
            return $line['price'] * $line['qtd'];
        }, $cart);
        $totalSumCart = array_sum($cart);

        SessionPagSeguro::createSession();
        $view = new View('site/checkout.phtml');
        $view->totalSumCart =  $totalSumCart;
        return $view->render();
    }

    public function proccess()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return json_encode(['data' => ['error' => 'Método não suportado!']]);
        }

        $items = Session::verifyExistsKey('cart');
        $data = $_POST;
        $user = Session::verifyExistsKey('user');
        $reference = sha1($user['id'] . $user['email']) . uniqid() . '_LOJA_VIRTUAL';

        $creditCardPayment = new CreditCard($reference, $items, $data, $user);
        $result = $creditCardPayment->doPayment();

        $userOrder = new UserOrder(Connection::getInstance());
        $userOrder = $userOrder->createOrder([
            'user_id'          => $user['id'],
            'reference'        => $reference,
            'pagseguro_code'   => $result->getCode(),
            'pagseguro_status' => $result->getStatus(),
            'items'            => serialize($items)
        ]);

        Session::removeUserSession('pagseguro_session');
        Session::removeUserSession('cart');

        return json_encode(['data' => [
            'ref_order' => $userOrder['reference'],
            'message'   => 'Transação concluída com sucesso!'
        ]]);
    }

    public function thanks()
    {
        if (!isset($_GET['ref'])) {
            return header('Location ' . HOME);
        }

        try {
            $reference = htmlentities($_GET['ref']);
            $userOrder = (new UserOrder(Connection::getInstance()))
                ->filterWithConditions(['reference' => $reference]);

            $view = new View('site/thanks.phtml');
            $view->reference = $$userOrder['reference'];

            return $view->render();
        } catch (Exception $exception) {
            return header('Location ' . HOME);
        }
    }

    public function notification()
    {
        try {
            $notification = new Notification();
            $notification = $notification->returnNotificationTransaction();

            $userOrder = new UserOrder(Connection::getInstance());
            $orderId = $userOrder->filterWithConditions(['reference' => $notification->getReference()])['id'];

            $userOrder->update([
                'pagseguro_status' => $notification->getStatus(),
                'id' => $orderId
            ]);

            if ($notification->getStatus() == 3) {
                //Envia a notficação para o cliente
                //sobre o status da compra
            }

            http_response_code(204);
            return json_encode([]);

        } catch (Exception $exception) {
            http_response_code(500);
            return json_encode(['data' => [
                'error' => 'Erro a receber a notificação',
            ]]);
        }
    }
}
