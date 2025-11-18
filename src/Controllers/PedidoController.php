<?php

namespace Controllers;

use Core\Controller;
use Core\Database;

/**
 * Controller responsável por gerenciar os pedidos.
 * Aqui ficam as funções para listar, ver detalhes, atualizar status etc.
 */
class PedidoController extends Controller
{
    private $db;

    public function __construct()
    {
        // Inicia a conexão
        $this->db = new Database();
    }

    /**
     * Lista todos os pedidos
     */
    public function index()
    {
        $sql = "SELECT p.*, u.nome AS cliente
                FROM pedidos p
                LEFT JOIN usuarios u ON u.id = p.usuario_id
                ORDER BY p.data_criacao DESC";

        $pedidos = $this->db->query($sql)->fetchAll();

        $this->view('pedidos/index', [
            'pedidos' => $pedidos
        ]);
    }

    /**
     * Exibe os detalhes de um pedido
     */
    public function show($id)
    {
        // Busca o pedido
        $pedido = $this->db->query(
            "SELECT * FROM pedidos WHERE id = :id",
            [':id' => $id]
        )->fetch();

        if (!$pedido) {
            $_SESSION['erro'] = "Pedido não encontrado!";
            $this->redirect('/pedidos');
        }

        // Busca os itens do pedido
        $itens = $this->db->query(
            "SELECT i.*, p.nome AS produto
             FROM pedidos_itens i
             LEFT JOIN produtos p ON p.id = i.produto_id
             WHERE i.pedido_id = :id",
            [':id' => $id]
        )->fetchAll();

        $this->view('pedidos/show', [
            'pedido' => $pedido,
            'itens' => $itens
        ]);
    }

    /**
     * Atualiza o status do pedido
     */
    public function updateStatus($id)
    {
        $status = $_POST['status'];

        if (!$status) {
            $_SESSION['erro'] = "Status inválido!";
            $this->redirect("/pedidos/show/$id");
        }

        $sql = "UPDATE pedidos SET status = :status WHERE id = :id";

        $this->db->query($sql, [
            ':status' => $status,
            ':id' => $id
        ]);

        $_SESSION['sucesso'] = "Status do pedido atualizado!";
        $this->redirect("/pedidos/show/$id");
    }

    /**
     * Remove um pedido
     */
    public function delete($id)
    {
        // Primeiro apaga os itens
        $this->db->query(
            "DELETE FROM pedidos_itens WHERE pedido_id = :id",
            [':id' => $id]
        );

        // Agora apaga o pedido
        $this->db->query(
            "DELETE FROM pedidos WHERE id = :id",
            [':id' => $id]
        );

        $_SESSION['sucesso'] = "Pedido removido!";
        $this->redirect('/pedidos');
    }
}
