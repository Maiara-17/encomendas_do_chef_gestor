<?php
$config = require __DIR__ . '/../../../config/app.php';
$title = 'Login';

// Força a base_url correta automaticamente
$baseUrl = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            margin: 0;
            height: 100vh;
            display: flex;
            flex-direction: column; 
            overflow: hidden; 
        }

        .header {
            background-color: #f4c430;
            color: #fff;
            padding: 10px;
            align-items: center;
            text-align: center;
            font-size: 1.2em;
            font-weight: bold;
            width: 100%;
            height: 7%;
            position: fixed; 
            top: 0;
            z-index: 1000; 
        }

        .login-card {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            width: 100%;
            height: calc(100vh - 60px); 
            text-align: center;
            margin-top: 300px;
            overflow-y: auto; 
            box-sizing: border-box;
        }

        .footer {
            background-color: #f4c430;
            color: #fff;
            padding: 10px;
            text-align: center;
            width: 100%;
            position: fixed; 
            bottom: 0;
            height: 30px; 
            z-index: 1000; 
        }

        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 3px;
            text-align: left;
        }

        .alert-error {
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #c62828;
        }

        .alert-success {
            background-color: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #2e7d32;
        }

        form {
            display: flex;
            flex-direction: column;
            max-width: 700px; 
            margin: 0 auto; 
            border-radius: 10px;
        }

        label {
            color: #d32f2f;
            font-weight: bold;
            margin-bottom: 5px;
            text-align: left;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            background-color: #e0e0e0;
            border-radius: 10px;
            font-size: 1em;
            box-sizing: border-box;
        }

        .btn-primary {
            background-color: #f4c430;
            color: #fff;
            padding: 10px;
            border: none;
            margin: 0 auto;
            border-radius: 10px;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            width: 30%;
            text-align: center;
        }

        .btn-primary:hover {
            background-color: #e0b12b;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="<?= $baseUrl ?>/index.php">
            <img src="public/assets/chef.png" alt="Logo Encomendas do Chef" style="max-width: 60px; height: auto; vertical-align: middle;">
        </a>
        Encomendas do Chef - Gestor
    </div>

    <div class="login-card">
        <!-- MENSAGENS CORRETAS (usando $_SESSION['sucesso'] e $_SESSION['erro']) -->
        <?php if (isset($_SESSION['sucesso'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['sucesso']) ?>
                <?php unset($_SESSION['sucesso']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['erro'])): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($_SESSION['erro']) ?>
                <?php unset($_SESSION['erro']); ?>
            </div>
        <?php endif; ?>

        <!-- FORMULÁRIO COM ACTION 100% CORRETO -->
        <form method="POST" action="<?= $baseUrl ?>/index.php?controller=Auth&action=entrar">
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($oldEmail ?? '') ?>" placeholder="admin@admin.com" required>
            
            <label>Senha</label>
            <input type="password" name="senha" placeholder="123" required>
            
            <button type="submit" class="btn-primary">Entrar</button>
        </form>
    </div>

    <div class="footer">
        &copy; 2025 Encomendas do Chef
    </div>
</body>
</html>