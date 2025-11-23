<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class DashboardController extends Controller
{
    private $db;

    public function __construct()
    {
        $this->auth();
        $this->db = Database::getInstance();
    }

    public function index()
    {
        // === CONTAGEM TOTAL DE PEDIDOS ===
        $totalPedidos = $this->db->query("SELECT COUNT(*) AS total FROM pedidos")->fetch()['total'] ?? 0;

        // === PEDIDOS DO DIA ATUAL ===
        $pedidosHoje = $this->db->query("
            SELECT COUNT(*) AS total 
            FROM pedidos 
            WHERE DATE(ped_data_elaboracao) = CURDATE()
        ")->fetch()['total'] ?? 0;

        // === TOTAL DE PRODUTOS ===
        $totalProdutos = $this->db->query("SELECT COUNT(*) AS total FROM produtos WHERE prod_ativo = 1")->fetch()['total'] ?? 0;

        // === TOTAL DE CATEGORIAS ===
        $totalCategorias = $this->db->query("SELECT COUNT(*) AS total FROM categorias")->fetch()['total'] ?? 0;

        // === FATURAMENTO DO DIA ===
        $faturamentoHoje = $this->db->query("
            SELECT COALESCE(SUM(ped_valor_total), 0) AS total 
            FROM pedidos 
            WHERE DATE(ped_data_elaboracao) = CURDATE()
        ")->fetch()['total'] ?? 0.00;

        // === DADOS QUE SERÃO ENVIADOS PARA A VIEW ===
        $dados = [
            'titulo'           => 'Dashboard - Encomendas do Chef',
            'usuario'          => $_SESSION['usuario'] ?? 'Usuário',
            'page'             => 'dashboard',
            'totalPedidos'     => $totalPedidos,
            'pedidosHoje'      => $pedidosHoje,
            'totalProdutos'    => $totalProdutos,
            'totalCategorias'  => $totalCategorias,
            'faturamentoHoje'  => number_format($faturamentoHoje, 2, ',', '.'),
        ];

        // === CARREGA A VIEW CORRETA DO DASHBOARD (NÃO O LAYOUT COMO VIEW!) ===
        $this->view('dashboard/index', $dados);
    }
}