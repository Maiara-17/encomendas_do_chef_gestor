<?php

namespace App\Controllers;

use Core\Controller;
use Core\Database;

/**
 * Controller responsável por gerenciar as categorias.
 */
class CategoriaController extends Controller
{
    private $db;

    public function __construct()
    {
        // Inicia a conexão com o banco
        $this->db = new Database();
        session_start();
    }

    /**
     * Lista todas as categorias
     */
    public function index()
    {
        $categorias = $this->db->query("SELECT * FROM categorias ORDER BY nome ASC")->fetchAll();

        $this->view('categorias/index', [
            'categorias' => $categorias
        ]);
    }

    /**
     * Exibe o formulário de criação
     */
    public function create()
    {
        $this->view('categorias/create');
    }

    /**
     * Salva uma nova categoria
     */
    public function store()
    {
        $nome = trim($_POST['nome'] ?? '');

        if (empty($nome)) {
            $_SESSION['erro'] = "O nome da categoria é obrigatório!";
            $this->redirect('/categorias/add');
        }

        $sql = "INSERT INTO categorias (nome) VALUES (:nome)";
        $this->db->query($sql, [':nome' => $nome]);

        $_SESSION['sucesso'] = "Categoria cadastrada com sucesso!";
        $this->redirect('/categorias');
    }

    /**
     * Exibe formulário de edição
     */
    public function edit($id)
    {
        $categoria = $this->db->query(
            "SELECT * FROM categorias WHERE id = :id",
            [':id' => $id]
        )->fetch();

        if (!$categoria) {
            $_SESSION['erro'] = "Categoria não encontrada!";
            $this->redirect('/categorias');
        }

        $this->view('categorias/edit', [
            'categoria' => $categoria
        ]);
    }

    /**
     * Atualiza categoria existente
     */
    public function update($id)
    {
        $nome = trim($_POST['nome'] ?? '');

        if (empty($nome)) {
            $_SESSION['erro'] = "O nome da categoria é obrigatório!";
            $this->redirect("/categorias/edit/$id");
        }

        $sql = "UPDATE categorias SET nome = :nome WHERE id = :id";
        $this->db->query($sql, [
            ':nome' => $nome,
            ':id' => $id
        ]);

        $_SESSION['sucesso'] = "Categoria atualizada com sucesso!";
        $this->redirect('/categorias');
    }

    /**
     * Remove uma categoria
     */
    public function delete($id)
    {
        $sql = "DELETE FROM categorias WHERE id = :id";
        $this->db->query($sql, [':id' => $id]);

        $_SESSION['sucesso'] = "Categoria removida com sucesso!";
        $this->redirect('/categorias');
    }
}
