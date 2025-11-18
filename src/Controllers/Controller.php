<?php

namespace Controllers;

class Controller
{
    protected function view($path, $data = [])
    {
        if (!empty($data)) {
            extract($data);
        }

        $file = __DIR__ . '/../Views/' . $path . '.php';

        if (!file_exists($file)) {
            die("Erro: View <b>{$file}</b> não encontrada.");
        }

        require $file;
    }
}
