<?php

namespace App\Controllers;

class AuthController
{
    public function login()
    {
        require_once __DIR__ . '/../../views/auth/login.php';
    }

    public function entrar()
    {
        session_start();

        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        if ($email === '' || $senha === '') {
            $_SESSION['erro'] = "Preencha todos os campos!";
            header("Location: /encomendas-do-chef---gestor/?controller=Auth&action=login");
            exit;
        }

        if ($email === "admin@admin.com" && $senha === "123") {

            $_SESSION['usuario'] = $email;

            header("Location: /encomendas-do-chef---gestor/?controller=Dashboard&action=index");
            exit;
        }

        $_SESSION['erro'] = "Usuário ou senha incorretos!";
        header("Location: /encomendas-do-chef---gestor/?controller=Auth&action=login");
        exit;
    }

    public function sair()
    {
        session_start();
        session_unset();
        session_destroy();

        header("Location: /encomendas-do-chef---gestor/?controller=Auth&action=login");
        exit;
    }
}
