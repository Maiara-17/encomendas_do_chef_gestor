<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'Encomendas do Chef - Gestor' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/adminlte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Source Sans Pro', sans-serif; }
        .content-wrapper { background-color: #f4f6f9; }
        .main-header .navbar { background-color: #ffc107; }
        .main-sidebar { background-color: #dc3545; }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Topbar -->
    <nav class="main-header navbar navbar-expand navbar-warning navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link text-white" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link text-white" data-toggle="dropdown" href="#">
                    <i class="far fa-user"></i> Olá, <?= htmlspecialchars($_SESSION['usuario'] ?? 'Usuário') ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="index.php?controller=Auth&action=sair" class="dropdown-item text-danger">
                        <i class="fas fa-sign-out-alt mr-2"></i> Sair
                    </a>
                </div>
            </li>
        </ul>
    </nav>

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-danger elevation-4">
        <a href="index.php?controller=Dashboard&action=index" class="brand-link text-center bg-white">
            <span class="brand-text font-weight-bold text-danger">ENCOMENDAS DO CHEF</span>
        </a>
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    
                    <li class="nav-item">
                        <a href="index.php?controller=Dashboard&action=index" class="nav-link <?= ($_GET['controller'] ?? '') === 'Dashboard' ? 'active bg-white text-danger' : '' ?>">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="index.php?controller=Pedido&action=index" class="nav-link <?= ($_GET['controller'] ?? '') === 'Pedido' ? 'active bg-white text-danger' : '' ?>">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <p>Pedidos</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="index.php?controller=Produto&action=index" class="nav-link <?= ($_GET['controller'] ?? '') === 'Produto' ? 'active bg-white text-danger' : '' ?>">
                            <i class="nav-icon fas fa-box"></i>
                            <p>Produtos</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="index.php?controller=Categoria&action=index" class="nav-link <?= ($_GET['controller'] ?? '') === 'Categoria' ? 'active bg-white text-danger' : '' ?>">
                            <i class="nav-icon fas fa-tags"></i>
                            <p>Categorias</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="index.php?controller=Promocao&action=index" class="nav-link <?= ($_GET['controller'] ?? '') === 'Promocao' ? 'active bg-white text-danger' : '' ?>">
                            <i class="nav-icon fas fa-percentage"></i>
                            <p>Promoções</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="index.php?controller=Relatorio&action=vendas" class="nav-link <?= ($_GET['controller'] ?? '') === 'Relatorio' ? 'active bg-white text-danger' : '' ?>">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>Relatórios</p>
                        </a>
                    </li>

                    <li class="nav-item mt-5">
                        <a href="index.php?controller=Auth&action=sair" class="nav-link text-white">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Sair</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Conteúdo Principal -->
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><?= $titulo ?? 'Dashboard' ?></h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <?php include __DIR__ . '/../components/alerts.php'; ?>
                <?= $content ?? '' ?>
            </div>
        </section>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/adminlte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>