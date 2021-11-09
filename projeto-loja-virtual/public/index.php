<?php

use LojaVirtual\View\View;
use LojaVirtual\Session\Flash;
use LojaVirtual\Authenticator\CheckUserLogged;

require __DIR__ . '/../bootstrap.php';
$url = substr($_SERVER['REQUEST_URI'], 1);
$url = parse_url($url)['path'];
$url = explode('/', $url);

$controller = isset($url[0]) && $url[0] ? $url[0] : 'home';
$action     = isset($url[1]) && $url[1] ? $url[1] : 'index';
$param      = isset($url[2]) && $url[2] ? $url[2] : null;

if ($url[0] === 'admin') {
    $controller = isset($url[1]) && $url[1] ? $url[1] : 'home';
    $action     = isset($url[2]) && $url[2] ? $url[2] : 'index';
    $param      = isset($url[3]) && $url[3] ? $url[3] : null;
}

$pathAdmin = "LojaVirtual\Controller\Admin\\";
$pathNormalUser = "LojaVirtual\Controller\\";

$url[0] === 'admin' ? $controller = $pathAdmin . ucfirst($controller) . 'Controller' :
$controller = $pathNormalUser . ucfirst($controller) . 'Controller';

//Verifica se o usuário tem autorização para acesso
if (
    in_array(
        $controller,
        [
            $pathAdmin      . 'CategoriesController',
            $pathAdmin      . 'ImagesController',
            $pathAdmin      . 'ProductsController',
            $pathAdmin      . 'UsersController',
            $pathNormalUser . 'OrdersController',
        ]
    )
) {
    $logged = CheckUserLogged::checkAuthenticator();
    if (!$logged) {
        Flash::sendMessageSession('danger', 'Efetue o login para ter acesso!');
        print (new View('auth/index.phtml'))->render();
        die;
    }
}

if (!method_exists($controller, $action)) {
    $action = 'index';
    $param  = $url[1];
}

$response = call_user_func_array([new $controller, $action], [$param]);

print $response;
