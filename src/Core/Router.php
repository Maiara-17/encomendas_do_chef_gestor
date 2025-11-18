<?php
namespace Core;

class Router
{
    private array $routes = [];

    public function get(string $route, string $action)
    {
        $this->routes['GET'][$route] = $action;
    }

    public function post(string $route, string $action)
    {
        $this->routes['POST'][$route] = $action;
    }

    public function dispatch(string $url)
    {
        // Corrigido para não duplicar barras
        $url = '/' . ltrim($url, '/');
        $method = $_SERVER['REQUEST_METHOD'];

        if (!isset($this->routes[$method])) {
            http_response_code(404);
            echo "404 - Página não encontrada";
            return;
        }

        foreach ($this->routes[$method] as $route => $action) {
            $pattern = preg_replace('/{[^\/]+}/', '([^/]+)', $route);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $url, $matches)) {
                array_shift($matches);

                [$controller, $methodName] = explode('@', $action);
                $controllerClass = "\\App\\Controllers\\$controller";

                if (!class_exists($controllerClass)) {
                    echo "Controller não encontrado: $controllerClass";
                    return;
                }

                $instance = new $controllerClass();
                return call_user_func_array([$instance, $methodName], $matches);
            }
        }

        http_response_code(404);
        echo "404 - Página não encontrada";
    }
}
