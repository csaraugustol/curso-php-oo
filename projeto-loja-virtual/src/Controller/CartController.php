<?php

namespace LojaVirtual\Controller;

use LojaVirtual\Session\Flash;
use LojaVirtual\Session\Session;
use LojaVirtual\View\View;

class CartController
{
    public function index()
    {
        $view = new View('site/cart.phtml');
        $view->cart = Session::verifyExistsKey('cart');
        return $view->render();
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $product = $_POST;
            Session::removeUserSession('pagseguro_session');
            Session::removeUserSession('cart');
            $cart = Session::verifyExistsKey('cart');

            if (!is_null($cart)) {
                array_push($cart, $product);
            } else {
                $cart[] = $product;
            }

            Session::addUserSession('cart', $cart);

            Flash::sendMessageSession('success', 'Produto adicionado ao carrinho!');
            return header('Location: ' . HOME . '/product/view/' . $product['slug']);
        }
    }

    public function cancel()
    {
        Session::removeUserSession('cart');
        Flash::sendMessageSession('success', 'Você limpou o carrinho!');
        return header('Location: ' . HOME . '/cart');
    }

    public function remove($slug)
    {
        $cart = Session::verifyExistsKey('cart');

        if (is_null($cart)) {
            return header('Location: ' . HOME);
        }

        $cart = array_filter($cart, function ($item) use ($slug) {
            return $item['slug'] != $slug;
        });

        $cart = count($cart) == 0 ? null : $cart;

        Session::addUserSession('cart', $cart);
        return header('Location: ' . HOME . '/cart');
    }
}
