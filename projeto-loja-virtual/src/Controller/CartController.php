<?php

namespace LojaVirtual\Controller;

use Exception;
use LojaVirtual\View\View;
use LojaVirtual\Session\Flash;
use LojaVirtual\Session\Session;

class CartController
{
    /**
     * Lista produtos no carrinho
     *
     * @return redirect
     */
    public function index()
    {
        $view = new View('site/cart.phtml');
        $view->cart = Session::verifyExistsKey('cart');
        return $view->render();
    }

    /**
     * Adiciona produto no carrinho
     *
     * @return redirect
     */
    public function add()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $product = $_POST;

                $cart = Session::verifyExistsKey('cart');

                if (!is_null($cart)) {
                    array_push($cart, $product);
                } else {
                    $cart[] = $product;
                }

                Session::addUserSession('cart', $cart);
                Flash::sendMessageSession('success', 'Produto adicionado ao carrinho!');
            }
        } catch (Exception $exception) {
            Flash::returnExceptionErrorMessage(
                $exception,
                'Erro ao adicionar produto ao carrinho!'
            );

            return header('Location: ' . HOME . '/product/view/' . $product['slug']);
        }

        return header('Location: ' . HOME . '/product/view/' . $product['slug']);
    }

    /**
     * Remove produto do carrinho
     *
     * @param string $slug
     * @return redirect
     */
    public function remove(string $slug)
    {
        try {
            $cart = Session::verifyExistsKey('cart');

            if (is_null($cart)) {
                return header('Location: ' . HOME);
            }

            $cart = array_filter($cart, function ($item) use ($slug) {
                return $item['slug'] != $slug;
            });

            $cart = count($cart) == 0 ? null : $cart;

            Session::addUserSession('cart', $cart);
        } catch (Exception $exception) {
            Flash::returnExceptionErrorMessage(
                $exception,
                'Erro ao remover produto do carrinho!'
            );

            return header('Location: ' . HOME . '/cart');
        }

        return header('Location: ' . HOME . '/cart');
    }
}
