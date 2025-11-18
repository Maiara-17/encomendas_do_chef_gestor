<?php

namespace Core;

/**
 * Autoloader - Carrega automaticamente as classes do projeto
 */
class Autoloader
{
    /**
     * Registra o autoloader
     */
    public static function register()
    {
        spl_autoload_register([__CLASS__, 'load']);
    }

    /**
     * Carrega uma classe automaticamente
     *
     * @param string $className Nome completo da classe com namespace
     */
    private static function load($className)
    {
        // Converte namespace para caminho
        $className = str_replace('\\', '/', $className);

        // Caminho correto: src/
        $file = __DIR__ . '/../' . $className . '.php';

        if (file_exists($file)) {
            require_once $file;
        }
    }
}
