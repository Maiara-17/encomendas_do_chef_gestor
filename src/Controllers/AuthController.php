<?php

namespace App\Controllers;

class AuthController
{
    /**
     * Exibe a tela de login
     */
    public function showLogin()
    {
        require_once __DIR__ . '/../Views/auth/login.php';
    }

    /**
     * Processa o envio do formulário de login
     */
    public function login()
    {
        session_start();

        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        if ($email === '' || $senha === '') {
            $_SESSION['erro'] = "Preencha todos os campos!";
            header("Location: /encomendas-do-chef---gestor/login");
            exit;
        }

        // === LOGIN FIXO (temporário) ===
        if ($email === "admin@admin.com" && $senha === "123") {

            $_SESSION['usuario'] = $email;

            header("Location: /encomendas-do-chef---gestor/dashboard");
            exit;
        }

        $_SESSION['erro'] = "Usuário ou senha incorretos!";
        header("Location: /encomendas-do-chef---gestor/login");
        exit;
    }

    /**
     * Desloga o usuário
     */
    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();

        header("Location: /encomendas-do-chef---gestor/login");
        exit;
    }
}
