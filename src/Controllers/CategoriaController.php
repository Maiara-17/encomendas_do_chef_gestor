<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use PDO; // ← ESSA LINHA É OBRIGATÓRIA!

class CategoriaController extends Controller
{
    private $db;

    public function __construct()
    {
        $this->auth();
        $this->db = Database::getInstance();
    }

    public function index()
    {
        $stmt = $this->db->query("SELECT * FROM categorias ORDER BY cat_nome ASC");
        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->view('categorias/index', [
            'titulo'     => 'Categorias',
            'categorias' => $categorias,
            'page'       => 'categorias'
        ]);
    }

    public function create()
    {
        $this->view('categorias/create', [
            'titulo' => 'Nova Categoria',
            'page'   => 'categorias'
        ]);
    }

    public function store()
    {
        $nome = trim($_POST['nome'] ?? '');

        if (empty($nome)) {
            $_SESSION['erro'] = "O nome da categoria é obrigatório!";
            $this->redirect('?controller=Categoria&action=create');
        }

        $this->db->query("INSERT INTO categorias (cat_nome) VALUES (:nome)", [':nome' => $nome]);

        $_SESSION['sucesso'] = "Categoria cadastrada com sucesso!";
        $this->redirect('?controller=Categoria&action=index');
    }

    public function edit($id = null)
    {
        $id = $id ?? $_GET['id'] ?? null;
        $stmt = $this->db->query("SELECT * FROM categorias WHERE cat_codigo = :id", [':id' => $id]);
        $categoria = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$categoria) {
            $_SESSION['erro'] = "Categoria não encontrada!";
            $this->redirect('?controller=Categoria&action=index');
        }

        $this->view('categorias/edit', [
            'titulo'    => 'Editar Categoria',
            'categoria' => $categoria,
            'page'      => 'categorias'
        ]);
    }

    public function update($id = null)
    {
        $id   = $id ?? $_POST['id'] ?? null;
        $nome = trim($_POST['nome'] ?? '');

        if (empty($nome) || empty($id)) {
            $_SESSION['erro'] = "Dados inválidos!";
            $this->redirect('?controller=Categoria&action=index');
        }

        $this->db->query("UPDATE categorias SET cat_nome = :nome WHERE cat_codigo = :id", [
            ':nome' => $nome,
            ':id'   => $id
        ]);

        $_SESSION['sucesso'] = "Categoria atualizada com sucesso!";
        $this->redirect('?controller=Categoria&action=index');
    }

    public function delete($id = null)
    {
        $id = $id ?? $_GET['id'] ?? null;

        if ($id) {
            $this->db->query("DELETE FROM categorias WHERE cat_codigo = :id", [':id' => $id]);
            $_SESSION['sucesso'] = "Categoria removida com sucesso!";
        }

        $this->redirect('?controller=Categoria&action=index');
    }
}