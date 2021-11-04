<?php

namespace LojaVirtual\Controller;

use Exception;
use LojaVirtual\View\View;
use LojaVirtual\Session\Flash;
use LojaVirtual\Session\Session;
use LojaVirtual\Entity\UserOrder;
use LojaVirtual\DataBase\Connection;
use LojaVirtual\Payment\PagSeguro\CreditCard;
use LojaVirtual\Payment\PagSeguro\Notification;
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
        if (!Session::hasUserSession('user')) {
            return header('Location: ' . HOME . '/store/login');
        }

        if (!Session::hasUserSession('cart')) {
            return header("Lacation: " . HOME);
        }

        $cart = Session::verifyExistsKeyAndAddInCart('cart');

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

            $items = Session::verifyExistsKeyAndAddInCart('cart');
            $data = $_POST;
            $user = Session::verifyExistsKeyAndAddInCart('user');
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
        } catch (Exception $exception) {
            Flash::returnExceptionErrorMessage(
                $exception,
                'Ocorreu um erro interno e não foi possível finalizar a compra!'
            );
        }
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
            $view->reference = $$userOrder['reference'];

            return $view->render();
        } catch (Exception $exception) {
            Flash::returnExceptionErrorMessage(
                $exception,
                'Ocorreu um erro interno!'
            );

            return header('Location ' . HOME);
        }
    }

    /**
     * Envia notificação
     *
     * @return redirect
     */
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
            Flash::returnExceptionErrorMessage(
                $exception,
                json_encode(['data' => [
                    'error' => 'Erro a receber a notificação',
                ]])
            );

            return header('Location ' . HOME);
        }
    }
}
