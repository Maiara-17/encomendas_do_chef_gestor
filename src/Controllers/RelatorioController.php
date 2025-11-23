<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use PDO;

class RelatorioController extends Controller
{
    private $db;

    public function __construct()
    {
        $this->auth(); // Protege o relatório
        $this->db = Database::getInstance(); // ← CORRETO (Singleton)
    }

    /**
     * Relatório de Vendas com filtro por período
     */
    public function vendas()
    {
        // Filtros de data (padrão: primeiro dia do mês até hoje)
        $de  = $_GET['de'] ?? date('Y-m-01');
        $ate = $_GET['ate'] ?? date('Y-m-d');

        // === 1. Total de pedidos e faturamento no período ===
        $stmt = $this->db->query("
            SELECT 
                COUNT(*) as total_pedidos,
                COALESCE(SUM(ped_valor_total), 0) as faturamento
            FROM pedidos 
            WHERE DATE(ped_data_elaboracao) BETWEEN :de AND :ate
        ", [':de' => $de, ':ate' => $ate]);

        $estatisticas = $stmt->fetch(PDO::FETCH_ASSOC); // ← CORRIGIDO AQUI!
        $totalPedidos = $estatisticas['total_pedidos'] ?? 0;
        $faturamento  = $estatisticas['faturamento'] ?? 0;
        $ticketMedio  = $totalPedidos > 0 ? $faturamento / $totalPedidos : 0;

        // === 2. Pedidos detalhados no período ===
        $pedidos = $this->db->query("
            SELECT 
                p.ped_numero as id, 
                p.ped_data_elaboracao as data_pedido, 
                p.ped_valor_total as total, 
                p.ped_status as status,
                COALESCE(c.cli_nome, 'Cliente Avulso') as cliente
            FROM pedidos p
            LEFT JOIN clientes c ON c.cli_codigo = p.cli_codigo
            WHERE DATE(p.ped_data_elaboracao) BETWEEN :de AND :ate
            ORDER BY p.ped_data_elaboracao DESC
        ", [':de' => $de, ':ate' => $ate])->fetchAll(PDO::FETCH_ASSOC);

        // === 3. Produtos mais vendidos ===
        $topProdutos = $this->db->query("
            SELECT 
                pr.prod_nome as produto,
                SUM(ip.itp_quantidade_comprada) as quantidade,
                SUM(ip.itp_quantidade_comprada * ip.itp_preco_unitario) as valor_total
            FROM itens_pedido ip
            JOIN produtos pr ON pr.prod_codigo = ip.prod_codigo
            JOIN pedidos p ON p.ped_numero = ip.ped_numero
            WHERE DATE(p.ped_data_elaboracao) BETWEEN :de AND :ate
            GROUP BY pr.prod_codigo
            ORDER BY quantidade DESC
            LIMIT 10
        ", [':de' => $de, ':ate' => $ate])->fetchAll(PDO::FETCH_ASSOC);

        // === 4. Vendas por categoria ===
        $porCategoria = $this->db->query("
            SELECT 
                c.cat_nome as categoria,
                COUNT(DISTINCT p.ped_numero) as pedidos,
                SUM(ip.itp_quantidade_comprada) as itens_vendidos,
                SUM(ip.itp_quantidade_comprada * ip.itp_preco_unitario) as valor_total
            FROM itens_pedido ip
            JOIN produtos pr ON pr.prod_codigo = ip.prod_codigo
            JOIN categorias c ON c.cat_codigo = pr.cat_codigo
            JOIN pedidos p ON p.ped_numero = ip.ped_numero
            WHERE DATE(p.ped_data_elaboracao) BETWEEN :de AND :ate
            GROUP BY c.cat_codigo
            ORDER BY valor_total DESC
        ", [':de' => $de, ':ate' => $ate])->fetchAll(PDO::FETCH_ASSOC);

        // === 5. Pedidos por status ===
        $porStatus = $this->db->query("
            SELECT ped_status as status, COUNT(*) as quantidade, SUM(ped_valor_total) as valor
            FROM pedidos
            WHERE DATE(ped_data_elaboracao) BETWEEN :de AND :ate
            GROUP BY ped_status
        ", [':de' => $de, ':ate' => $ate])->fetchAll(PDO::FETCH_ASSOC);

        // Dados finais para a view
        $dados = [
            'titulo'         => 'Relatório de Vendas',
            'page'           => 'relatorios',
            'de'             => $de,
            'ate'            => $ate,
            'totalPedidos'   => $totalPedidos,
            'faturamento'    => number_format($faturamento, 2, ',', '.'),
            'ticketMedio'    => number_format($ticketMedio, 2, ',', '.'),
            'pedidos'        => $pedidos,
            'topProdutos'    => $topProdutos,
            'porCategoria'   => $porCategoria,
            'porStatus'      => $porStatus,
        ];

        $this->view('relatorios/vendas', $dados);
    }
}