<?php

namespace Controllers;

use Controllers\Controller;
use Core\Database;

class RelatorioController extends Controller
{
    private $db;

    public function __construct()
    {
        // Singleton Database
        $this->db = Database::getInstance();
    }

    // Método para exibir relatório de vendas
    public function vendas()
    {
        $conn = $this->db->getConnection();

        // Pega filtros da URL
        $de  = $_GET['de'] ?? date('Y-m-01'); // início do mês
        $ate = $_GET['ate'] ?? date('Y-m-d'); // hoje

        // Consulta de pedidos no período
        $stmt = $conn->prepare("
            SELECT p.ped_numero, p.ped_data_elaboracao, c.cli_nome, p.ped_valor_total, p.ped_status
            FROM pedidos p
            INNER JOIN clientes c ON p.cli_codigo = c.cli_codigo
            WHERE DATE(p.ped_data_elaboracao) BETWEEN ? AND ?
            ORDER BY p.ped_data_elaboracao DESC
        ");
        $stmt->bind_param("ss", $de, $ate);
        $stmt->execute();
        $result = $stmt->get_result();

        $pedidos = [];
        while ($row = $result->fetch_assoc()) {
            $pedidos[] = $row;
        }

        // Estatísticas gerais
        $estatisticas = [
            'total_pedidos' => count($pedidos),
            'faturamento_total' => array_sum(array_column($pedidos, 'ped_valor_total')),
            'ticket_medio' => count($pedidos) > 0 ? array_sum(array_column($pedidos, 'ped_valor_total')) / count($pedidos) : 0
        ];

        // Produtos mais vendidos
        $stmtProd = $conn->prepare("
            SELECT pr.prod_nome, SUM(ip.itp_quantidade_comprada) AS quantidade_vendida,
                   SUM(ip.itp_quantidade_comprada * ip.itp_preco_unitario) AS valor_total
            FROM itens_pedido ip
            INNER JOIN produtos pr ON ip.prod_codigo = pr.prod_codigo
            INNER JOIN pedidos p ON ip.ped_numero = p.ped_numero
            WHERE DATE(p.ped_data_elaboracao) BETWEEN ? AND ?
            GROUP BY pr.prod_codigo
            ORDER BY quantidade_vendida DESC
            LIMIT 10
        ");
        $stmtProd->bind_param("ss", $de, $ate);
        $stmtProd->execute();
        $topProdutos = $stmtProd->get_result()->fetch_all(MYSQLI_ASSOC);

        // Pedidos por status
        $stmtStatus = $conn->prepare("
            SELECT ped_status, COUNT(*) AS quantidade, SUM(ped_valor_total) AS valor_total
            FROM pedidos
            WHERE DATE(ped_data_elaboracao) BETWEEN ? AND ?
            GROUP BY ped_status
        ");
        $stmtStatus->bind_param("ss", $de, $ate);
        $stmtStatus->execute();
        $pedidosPorStatus = $stmtStatus->get_result()->fetch_all(MYSQLI_ASSOC);

        // Vendas por categoria
        $stmtCat = $conn->prepare("
            SELECT c.cat_nome,
                   COUNT(ip.prod_codigo) AS pedidos_com_categoria,
                   SUM(ip.itp_quantidade_comprada) AS quantidade_vendida,
                   SUM(ip.itp_quantidade_comprada * ip.itp_preco_unitario) AS valor_total
            FROM itens_pedido ip
            INNER JOIN produtos p ON ip.prod_codigo = p.prod_codigo
            INNER JOIN categorias c ON p.cat_codigo = c.cat_codigo
            INNER JOIN pedidos ped ON ip.ped_numero = ped.ped_numero
            WHERE DATE(ped.ped_data_elaboracao) BETWEEN ? AND ?
            GROUP BY c.cat_codigo
            ORDER BY valor_total DESC
        ");
        $stmtCat->bind_param("ss", $de, $ate);
        $stmtCat->execute();
        $categorias = $stmtCat->get_result()->fetch_all(MYSQLI_ASSOC);

        // Chama a view já existente
        $this->view('relatorios/vendas', [
            'de' => $de,
            'ate' => $ate,
            'estatisticas' => $estatisticas,
            'topProdutos' => $topProdutos,
            'pedidosPorStatus' => $pedidosPorStatus,
            'categorias' => $categorias
        ]);
    }
}
