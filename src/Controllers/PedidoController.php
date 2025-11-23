<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class PedidoController extends Controller
{
    private $db;

    public function __construct()
    {
        $this->auth();
        $this->db = Database::getInstance(); // ← CORRETO
    }

    public function index()
    {
        $sql = "SELECT p.ped_numero, 
                       p.ped_data_elaboracao, 
                       p.ped_valor_total, 
                       p.ped_status,
                       COALESCE(c.cli_nome, 'Cliente Avulso') AS cliente_nome
                FROM pedidos p
                LEFT JOIN clientes c ON c.cli_codigo = p.cli_codigo
                ORDER BY p.ped_data_elaboracao DESC";

        $stmt = $this->db->query($sql);
        $pedidos = $stmt->fetchAll();

        $this->view('pedidos/index', [
            'titulo'  => 'Pedidos',
            'pedidos' => $pedidos,
            'page'    => 'pedidos'
        ]);
    }

    public function show($id = null)
    {
        $id = $id ?? $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['erro'] = "Pedido não informado!";
            $this->redirect('?controller=Pedido&action=index');
        }

        $stmt = $this->db->query("
            SELECT p.*, COALESCE(c.cli_nome, 'Cliente Avulso') AS cliente_nome
            FROM pedidos p
            LEFT JOIN clientes c ON c.cli_codigo = p.cli_codigo
            WHERE p.ped_numero = :id
        ", [':id' => $id]);

        $pedido = $stmt->fetch();

        if (!$pedido) {
            $_SESSION['erro'] = "Pedido não encontrado!";
            $this->redirect('?controller=Pedido&action=index');
        }

        $itens = $this->db->query("
            SELECT ip.*, pr.prod_nome, pr.prod_preco
            FROM itens_pedido ip
            JOIN produtos pr ON pr.prod_codigo = ip.prod_codigo
            WHERE ip.ped_numero = :id
        ", [':id' => $id])->fetchAll();

        $this->view('pedidos/show', [
            'titulo' => 'Pedido #' . $pedido['ped_numero'],
            'pedido' => $pedido,
            'itens'  => $itens,
            'page'   => 'pedidos'
        ]);
    }
}