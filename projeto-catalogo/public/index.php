<?php

use Catalogo\View\View;

require __DIR__ . '/../bootstrap.php';

$url = substr($_SERVER['REQUEST_URI'], 1);
$url = explode('/', $url);

$controller = isset($url[0]) && $url[0] ? $url[0] : 'home';
$action     = isset($url[1]) && $url[1] ? $url[1] : 'index';
$param      = isset($url[2]) && $url[2] ? $url[2] : null;

/**
 * Verifica se existe o Controller
 */
if (!class_exists($controller = "Catalogo\Controller\\" . ucfirst($controller) . 'Controller')) {
    print (new View('404.phtml'))->render();
    die;
}

/**
 * Verifica se existe método, se não, chama a index
 */
if (!method_exists($controller, $action)) {
    $action = 'index';
    $param = $url[1];
}

$response = call_user_func_array([new $controller, $action], [$param]);

print $response;
