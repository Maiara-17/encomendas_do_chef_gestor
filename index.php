<?php

// Exibir erros (somente desenvolvimento)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// =======================
// AUTOLOAD AJUSTADO
// =======================
spl_autoload_register(function ($class) {

    // Remove prefixo App\ (ex: App\Controllers\DashboardController)
    $class = str_replace('App\\', '', $class);

    // Remove prefixo Core\ e ajusta caminho
    $class = str_replace('Core\\', 'Core/', $class);

    // Ajusta separadores de namespace para pastas
    $file = __DIR__ . '/src/' . str_replace('\\', '/', $class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

// =======================
// CARREGA CLASSES BASE
// =======================
require_once __DIR__ . '/src/Core/Router.php';
require_once __DIR__ . '/src/Core/Database.php';

// =======================
// INICIALIZA ROUTER
// =======================
$router = new Core\Router();

// =======================
// CARREGA ARQUIVO DE ROTAS
// =======================
require_once __DIR__ . '/src/routes.php';

// =======================
// OBTÉM A URL AMIGÁVEL
// =======================
$url = isset($_GET['url']) ? '/' . trim($_GET['url'], '/') : '/';

// =======================
// DESPACHA ROTA
// =======================
$router->dispatch($url);
