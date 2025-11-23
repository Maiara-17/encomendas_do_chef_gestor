<?php

namespace App\Core;

class Controller
{
    /**
     * REDIRECT 100% SEGURO E INFALÍVEL
     * Sempre força o index.php na frente — nunca mais vai dar 404 em lugar nenhum
     */
    protected function redirect(string $url)
    {
        // Remove o "?" inicial se existir e força o index.php
        $cleanUrl = ltrim($url, '?');
        header("Location: index.php?" . $cleanUrl);
        exit;
    }

    /**
     * Protege rotas que precisam de login
     */
    protected function auth()
    {
        if (empty($_SESSION['usuario'] ?? '')) {
            $this->redirect('?controller=Auth&action=login');
        }
    }

    /**
     * Carrega a view com layout
     */
    protected function view(string $view, array $data = [])
    {
        extract($data);

        $viewFile = __DIR__ . '/../Views/' . $view . '.php';
        if (!file_exists($viewFile)) {
            die("View não encontrada: {$view}.php");
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        $layoutFile = __DIR__ . '/../Views/layouts/app.php';
        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }

    /**
     * Mensagens de sucesso e erro (flash)
     */
    protected function withSuccess(string $msg)
    {
        $_SESSION['sucesso'] = $msg;
    }

    protected function withError(string $msg)
    {
        $_SESSION['erro'] = $msg;
    }
}