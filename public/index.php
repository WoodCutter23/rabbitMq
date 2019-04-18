<?php

require(__DIR__.'/../vendor/autoload.php');

use DI\Container;
use App\Helpers\Route;
use App\SendController;

try {
    $container = new Container();

    $route = $container->get(Route::class)->defineRoute($_SERVER['REQUEST_URI']);
    $class = 'App\\Controllers\\' . $route['class'];

    $obj = $container->get($class);

    call_user_func([$obj, $route['func']]);
} catch (Exception $e) {
    $error = $e->getMessage();
    $response  = [
        'success' => false,
        'message' => $error
    ];
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}

