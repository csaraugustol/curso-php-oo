<?php

use GGP\Authenticator\CheckUserLogged;
use GGP\View\View;
use GGP\Session\Flash;

require __DIR__ . '/../bootstrap.php';

$url = substr($_SERVER['REQUEST_URI'], 1);
$url = explode('/', $url);

$controller = isset($url[0]) && $url[0] ? $url[0] : 'home';
$action     = isset($url[1]) && $url[1] ? $url[1] : 'index';
$param      = isset($url[2]) && $url[2] ? $url[2] : null;

$path = "GGP\Controller\\";

//Verifica se a classe existe
if (!class_exists($controller = $path . ucfirst($controller) . 'Controller')) {
    print (new View('404.phtml'))->render();
    die;
}

if (in_array($controller, [$path . 'ExpensesController'])) {
    $isLogged = CheckUserLogged::checkController();
    if (!$isLogged) {
        Flash::sendMessageSession('danger', 'Faça o login para acessar as informações!');
        print (new View('auth/index.phtml'))->render();
        die;
    }
}

//Verfica se existe método, se não, chama a index
if (!method_exists($controller, $action)) {
    $action = 'index';
    $param = $url[1];
}

$response = call_user_func_array([new $controller, $action], [$param]);

print $response;
