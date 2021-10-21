<?php

require __DIR__ . '/../bootstrap.php';
$url = substr($_SERVER['REQUEST_URI'], 1);
$url = parse_url($url)['path'];
$url = explode('/', $url);

$controller = isset($url[0]) && $url[0] ? $url[0] : 'home';
$action     = isset($url[1]) && $url[1] ? $url[1] : 'index';
$param      = isset($url[2]) && $url[2] ? $url[2] : null;

if ($url[0] == 'admin') {
    $controller = isset($url[1]) && $url[1] ? $url[1] : 'home';
    $action     = isset($url[2]) && $url[2] ? $url[2] : 'index';
    $param      = isset($url[3]) && $url[3] ? $url[3] : null;
}

if ($url[0] == 'admin') {
    $controller = "LojaVirtual\Controller\Admin\\" . ucfirst($controller) . 'Controller';
} else {
    $controller = "LojaVirtual\Controller\\" . ucfirst($controller) . 'Controller';
}

if (!method_exists($controller, $action)) {
    $action = 'index';
    $param  = $url[1];
}

$response = call_user_func_array([new $controller, $action], [$param]);

print $response;
