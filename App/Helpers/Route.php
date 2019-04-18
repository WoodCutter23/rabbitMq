<?php

namespace App\Helpers;

use App\Controllers\SendController;
use Exception;
use DI\Container;

class Route

{
    public function defineRoute($uri) : array
    {
       require_once(__DIR__ . "/../route.php");
       $key = array_key_exists($uri, $routes);

       if(!$key) {
           header('Location: http://computerologia.ru/wp-content/uploads/2016/09/post-4-ne-sushhestvuyushhie-stranitsyi.jpg');
           exit;
       }

        $class =  stristr($routes[$uri], '@', true);
        $isHaveClass = $this->checkClass($class);

        if(!$isHaveClass) {
            throw new Exception('Такого класса не существует');
        }

        $func = stristr($routes[$uri], '@', false);
        $func = mb_substr($func, 1);
        $isHaveFunc = $this->checkFunc($func, $class);

        if(!$isHaveFunc) {
            throw new Exception('Такой функции не существует');
        }

        return[
            'func' => $func,
            'class' => $class
        ];
    }

    protected function checkClass($className) :bool
    {
       return  class_exists('App\\Controllers\\'.$className);
    }

    protected function checkFunc($func,$class) :bool
    {
        $class = 'App\\Controllers\\'.$class;
        $container = new Container();
        $obj = $container->get($class);

        return method_exists($obj, $func);
    }

}