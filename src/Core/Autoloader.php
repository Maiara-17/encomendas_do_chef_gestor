<?php
// src/Core/Autoloader.php 
 
spl_autoload_register(function ($class) {
    // Exemplo: App\Controllers\ProdutoController
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../'; // aponta para a pasta src/

    // Se não começar com App\, ignora (deixa outros autoloaders cuidarem)
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    // Remove "App\" do início → fica Controllers\ProdutoController
    $relative_class = substr($class, strlen($prefix));

    // Converte \ em / e adiciona .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // Se o arquivo existir, inclui
    if (file_exists($file)) {
        require $file;
    }
});