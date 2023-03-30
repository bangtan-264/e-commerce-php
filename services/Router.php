<?php

declare(strict_types=1);

namespace App;

require_once "Buyer.php";
require_once "Seller.php";


class Router
{

    private array $routes;

    public function register(string $requestMethod, string $route, callable|array $action): self{
        $this->routes[$requestMethod][$route] = $action;
        return $this;
    }

    public function get(string $route, callable|array $action): self{
        return $this->register('get', $route, $action);
    }

    public function post(string $route, callable|array $action): self{
        return $this->register('post', $route, $action);
    }

    public function routes(): array{
        return $this->routes;
    }

    public function resolve(string $requestUri, string $requestMethod){
        $route = explode('?', $requestUri)[0];
        $action = $this->routes[$requestMethod][$route] ?? null;

        if (!$action) {
            throw new RouteNotFoundException();
        }
        if (is_callable($action)) {
            // action();
            return call_user_func($action);
        }

        if (is_array($action)) {
            [$class, $method] = $action;

            if (class_exists($class)) {
                $class = new $class();

                if (method_exists($class, $method)) {
                    $x = file_get_contents('php://input');
                    $data = json_decode($x,true);
                    // echo "data".$data.PHP_EOL;
                    return $class->$method($data);
                    // return call_user_func_array([$class, $method], $data);
                }
            }
        }
        throw new RouteNotFoundException();
    }
}



