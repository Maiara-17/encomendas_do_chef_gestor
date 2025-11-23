<?php
$currentPage = $_GET['controller'] ?? 'dashboard';
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="index.php?controller=Dashboard" class="brand-link text-center">
        <span class="brand-text font-weight-light"><strong>Encomendas do Chef</strong></span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <li class="nav-item">
                    <a href="index.php?controller=Dashboard" class="nav-link <?= $currentPage === 'Dashboard' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="index.php?controller=Pedido" class="nav-link <?= $currentPage === 'Pedido' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>Pedidos</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="index.php?controller=Produto" class="nav-link <?= $currentPage === 'Produto' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-box"></i>
                        <p>Produtos</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="index.php?controller=Categoria" class="nav-link <?= $currentPage === 'Categoria' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>Categorias</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="index.php?controller=Promocao" class="nav-link <?= $currentPage === 'Promocao' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-percentage"></i>
                        <p>Promoções</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="index.php?controller=Relatorio&action=vendas" class="nav-link <?= $currentPage === 'Relatorio' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>Relatórios</p>
                    </a>
                </li>

                <li class="nav-item mt-5">
                    <a href="index.php?controller=Auth&action=sair" class="nav-link text-danger">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Sair</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>