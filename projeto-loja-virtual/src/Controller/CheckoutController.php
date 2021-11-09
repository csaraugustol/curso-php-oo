<?php

namespace LojaVirtual\Controller;

use Exception;
use LojaVirtual\View\View;
use LojaVirtual\Session\Flash;
use LojaVirtual\Session\Session;
use LojaVirtual\Entity\UserOrder;
use LojaVirtual\DataBase\Connection;
use LojaVirtual\Payment\PagSeguro\CreditCard;
use LojaVirtual\Payment\PagSeguro\SessionPagSeguro;

class CheckoutController
{
    /**
     * Prepara acesso para concluir
     * compra do usuário
     *
     * @return redirect
     */
    public function index()
    {
        if (!Session::hasKeySession('user')) {
            return header('Location: ' . HOME . '/store/login');
        }

        if (!Session::hasKeySession('cart')) {
            return header("Lacation: " . HOME);
        }

        $cart = Session::verifyExistsKeyOfArray('cart');

        $cart = array_map(function ($line) {
            return $line['price'] * $line['qtd'];
        }, $cart);
        $totalSumCart = array_sum($cart);

        SessionPagSeguro::createSession();
        $view = new View('site/checkout.phtml');
        $view->totalSumCart =  array($totalSumCart);
        return $view->render();
    }

    /**
     * Finaliza o processo de compra
     *
     * @return redirect
     */
    public function proccess()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                return json_encode(['data' => ['error' => 'Método não suportado!']]);
            }

            $items = Session::verifyExistsKeyOfArray('cart');
            $data = $_POST;
            $user = Session::verifyExistsKeyOfArray('user');
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

            Session::removekeySession('pagseguro_session');
            Session::removekeySession('cart');
        } catch (Exception $exception) {
            Flash::returnExceptionErrorMessage(
                $exception,
                'Ocorreu um erro interno e não foi possível finalizar a compra!'
            );
        }

        return json_encode(['data' => [
            'ref_order' => $userOrder['reference'],
            'message'   => 'Transação concluída com sucesso!'
        ]]);
    }

    /**
     * Envia menssagem de agradecimento
     * com a referência do pedido
     *
     * @return redirect
     */
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
            $view->reference = array($userOrder['reference']);

            return $view->render();
        } catch (Exception $exception) {
            Flash::returnExceptionErrorMessage(
                $exception,
                'Ocorreu um erro interno!'
            );

            return header('Location ' . HOME);
        }
    }
}
