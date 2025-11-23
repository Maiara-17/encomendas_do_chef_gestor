<?php
// index.php — RAIZ DO PROJETO (esse é o correto pro seu sistema)

session_start();

// CARREGA O AUTOLOADER QUE EXISTE NO SEU PROJETO
require_once __DIR__ . '/src/Core/Autoloader.php';

use App\Core\Router;

// Inicia o roteamento
$router = new Router();
$router->dispatch();