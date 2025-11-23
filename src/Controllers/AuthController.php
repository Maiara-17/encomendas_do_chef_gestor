<?php

namespace App\Controllers;

use App\Core\Controller;

class AuthController extends Controller
{
    public function login()
    {
        if (!empty($_SESSION['usuario'] ?? '')) {
            header("Location: index.php?controller=Dashboard&action=index");
            exit;
        }
        $this->view('auth/login');
    }

    public function entrar()
    {
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        if ($email === 'admin@admin.com' && $senha === '123') {
            $_SESSION['usuario'] = $email;
            $_SESSION['sucesso'] = 'Bem-vindo de volta!';

            header("Location: index.php?controller=Dashboard&action=index");
            exit;
        }

        $_SESSION['erro'] = 'Usuário ou senha incorretos';
        header("Location: index.php?controller=Auth&action=login");
        exit;
    }

    public function sair()
    {
        session_unset();
        session_destroy();
        header("Location: index.php?controller=Auth&action=login");
        exit;
    }
}