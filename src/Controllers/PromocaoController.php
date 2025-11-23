<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use PDO;

class PromocaoController extends Controller
{
    private $db;

    public function __construct()
    {
        $this->auth();
        $this->db = Database::getInstance();
    }

    public function index()
    {
        $stmt = $this->db->query("
            SELECT * FROM promocoes 
            ORDER BY prm_data_fim DESC, prm_id DESC
        ");
        $promocoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->view('promocoes/index', [
            'titulo'    => 'Promoções',
            'promocoes' => $promocoes,
            'page'      => 'promocoes'
        ]);
    }

    public function create()
    {
        $this->view('promocoes/create', [
            'titulo' => 'Nova Promoção',
            'page'   => 'promocoes'
        ]);
    }

    public function store()
    {
        $nome        = trim($_POST['nome'] ?? '');
        $descricao   = trim($_POST['descricao'] ?? '');
        $tipo        = $_POST['tipo'] ?? 'percentual';
        $valor       = str_replace(',', '.', $_POST['valor'] ?? 0);
        $data_inicio = $_POST['data_inicio'] ?? null;
        $data_fim    = $_POST['data_fim'] ?? null;
        $ativo       = isset($_POST['ativo']) ? 1 : 0;

        if (empty($nome) || empty($valor) || empty($data_inicio) || empty($data_fim)) {
            $_SESSION['erro'] = "Preencha todos os campos obrigatórios!";
            $this->redirect('?controller=Promocao&action=create');
        }

        $this->db->query("
            INSERT INTO promocoes 
            (prm_nome, prm_descricao, prm_tipo, prm_valor, prm_data_inicio, prm_data_fim, ativo)
            VALUES (:nome, :desc, :tipo, :valor, :ini, :fim, :ativo)
        ", [
            ':nome'  => $nome,
            ':desc'  => $descricao,
            ':tipo'  => $tipo,
            ':valor' => $valor,
            ':ini'   => $data_inicio,
            ':fim'   => $data_fim,
            ':ativo' => $ativo
        ]);

        $_SESSION['sucesso'] = "Promoção criada com sucesso!";
        $this->redirect('?controller=Promocao&action=index');
    }

    public function edit($id = null)
    {
        $id = $id ?? $_GET['id'] ?? null;
        $promocao = $this->db->query("SELECT * FROM promocoes WHERE prm_id = :id", [':id' => $id])
                             ->fetch(PDO::FETCH_ASSOC);

        if (!$promocao) {
            $_SESSION['erro'] = "Promoção não encontrada!";
            $this->redirect('?controller=Promocao&action=index');
        }

        $this->view('promocoes/edit', [
            'titulo'   => 'Editar Promoção',
            'promocao' => $promocao,
            'page'     => 'promocoes'
        ]);
    }

    public function update($id = null)
    {
        $id          = $id ?? $_POST['id'] ?? null;
        $nome        = trim($_POST['nome'] ?? '');
        $descricao   = trim($_POST['descricao'] ?? '');
        $tipo        = $_POST['tipo'] ?? 'percentual';
        $valor       = str_replace(',', '.', $_POST['valor'] ?? 0);
        $data_inicio = $_POST['data_inicio'] ?? null;
        $data_fim    = $_POST['data_fim'] ?? null;
        $ativo       = isset($_POST['ativo']) ? 1 : 0;

        if (empty($nome) || empty($valor) || empty($data_inicio) || empty($data_fim) || empty($id)) {
            $_SESSION['erro'] = "Preencha todos os campos!";
            $this->redirect("?controller=Promocao&action=edit&id=$id");
        }

        $this->db->query("
            UPDATE promocoes SET
                prm_nome = :nome,
                prm_descricao = :desc,
                prm_tipo = :tipo,
                prm_valor = :valor,
                prm_data_inicio = :ini,
                prm_data_fim = :fim,
                ativo = :ativo
            WHERE prm_id = :id
        ", [
            ':nome'  => $nome,
            ':desc'  => $descricao,
            ':tipo'  => $tipo,
            ':valor' => $valor,
            ':ini'   => $data_inicio,
            ':fim'   => $data_fim,
            ':ativo' => $ativo,
            ':id'    => $id
        ]);

        $_SESSION['sucesso'] = "Promoção atualizada com sucesso!";
        $this->redirect('?controller=Promocao&action=index');
    }

    public function delete($id = null)
    {
        $id = $id ?? $_GET['id'] ?? null;
        if ($id) {
            $this->db->query("DELETE FROM promocoes WHERE prm_id = :id", [':id' => $id]);
            $_SESSION['sucesso'] = "Promoção excluída com sucesso!";
        }
        $this->redirect('?controller=Promocao&action=index');
    }
}