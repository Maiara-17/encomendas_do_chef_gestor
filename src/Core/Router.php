<?php

namespace App\Core;

class Router
{
    public function dispatch()
    {
        // FORÇA DASHBOARD QUANDO ABRE A RAIZ DO PROJETO
        if (empty($_GET['controller']) && (empty($_SERVER['REQUEST_URI']) || $_SERVER['REQUEST_URI'] === '/' || strpos($_SERVER['REQUEST_URI'], 'index.php') !== false)) {
            $_GET['controller'] = 'Dashboard';
            $_GET['action'] = 'index';
        }

        // Usa os parâmetros GET (funciona com os links do menu que já têm index.php)
        $controller = $_GET['controller'] ?? 'Dashboard';
        $action = $_GET['action'] ?? 'index';

        $controllerClass = 'App\\Controllers\\' . $controller . 'Controller';

        if (!class_exists($controllerClass)) {
            die("Controller não encontrado: $controller");
        }

        $instance = new $controllerClass();

        if (!method_exists($instance, $action)) {
            die("Ação não encontrada: $action");
        }

        // Executa a ação
        $instance->$action();
    }
}