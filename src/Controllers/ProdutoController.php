<?php

namespace Controllers;

use Core\Controller;
use Core\Database;

/**
 * Controller responsável por gerenciar produtos.
 * Possui funções para listar, criar, editar e excluir produtos.
 */
class ProdutoController extends Controller
{
    private $db;

    public function __construct()
    {
        // Conexão com o banco
        $this->db = new Database();
    }

    /**
     * Lista todos os produtos
     */
    public function index()
    {
        $sql = "SELECT p.*, c.nome AS categoria 
                FROM produtos p
                LEFT JOIN categorias c ON c.id = p.categoria_id
                ORDER BY p.nome ASC";

        $produtos = $this->db->query($sql)->fetchAll();

        $this->view('produtos/index', [
            'produtos' => $produtos
        ]);
    }

    /**
     * Formulário para cadastrar novo produto
     */
    public function create()
    {
        // Pegando categorias existentes
        $categorias = $this->db->query("SELECT * FROM categorias ORDER BY nome ASC")->fetchAll();

        $this->view('produtos/create', [
            'categorias' => $categorias
        ]);
    }

    /**
     * Salva um novo produto no banco
     */
    public function store()
    {
        $nome = trim($_POST['nome']);
        $preco = trim($_POST['preco']);
        $categoria_id = $_POST['categoria_id'];

        // Validações básicas
        if (empty($nome) || empty($preco) || empty($categoria_id)) {
            $_SESSION['erro'] = "Todos os campos são obrigatórios!";
            $this->redirect('/produtos/create');
        }

        $sql = "INSERT INTO produtos (nome, preco, categoria_id) 
                VALUES (:nome, :preco, :categoria_id)";

        $this->db->query($sql, [
            ':nome' => $nome,
            ':preco' => $preco,
            ':categoria_id' => $categoria_id
        ]);

        $_SESSION['sucesso'] = "Produto cadastrado com sucesso!";
        $this->redirect('/produtos');
    }

    /**
     * Formulário de edição de um produto
     */
    public function edit($id)
    {
        $produto = $this->db->query(
            "SELECT * FROM produtos WHERE id = :id",
            [':id' => $id]
        )->fetch();

        $categorias = $this->db->query("SELECT * FROM categorias ORDER BY nome ASC")->fetchAll();

        if (!$produto) {
            $_SESSION['erro'] = "Produto não encontrado!";
            $this->redirect('/produtos');
        }

        $this->view('produtos/edit', [
            'produto' => $produto,
            'categorias' => $categorias
        ]);
    }

    /**
     * Atualiza os dados do produto
     */
    public function update($id)
    {
        $nome = trim($_POST['nome']);
        $preco = trim($_POST['preco']);
        $categoria_id = $_POST['categoria_id'];

        if (empty($nome) || empty($preco) || empty($categoria_id)) {
            $_SESSION['erro'] = "Todos os campos são obrigatórios!";
            $this->redirect("/produtos/edit/$id");
        }

        $sql = "UPDATE produtos 
                SET nome = :nome, preco = :preco, categoria_id = :categoria_id 
                WHERE id = :id";

        $this->db->query($sql, [
            ':nome' => $nome,
            ':preco' => $preco,
            ':categoria_id' => $categoria_id,
            ':id' => $id
        ]);

        $_SESSION['sucesso'] = "Produto atualizado com sucesso!";
        $this->redirect('/produtos');
    }

    /**
     * Exclui um produto
     */
    public function delete($id)
    {
        $sql = "DELETE FROM produtos WHERE id = :id";
        $this->db->query($sql, [':id' => $id]);

        $_SESSION['sucesso'] = "Produto removido!";
        $this->redirect('/produtos');
    }
}
