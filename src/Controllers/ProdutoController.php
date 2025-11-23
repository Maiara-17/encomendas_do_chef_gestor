<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class ProdutoController extends Controller
{
    private $db;

    public function __construct()
    {
        $this->auth();
        $this->db = Database::getInstance(); // CORRIGIDO: getInstance()
    }

    public function index()
    {
        $stmt = $this->db->query("
            SELECT p.*, c.cat_nome AS categoria_nome 
            FROM produtos p
            LEFT JOIN categorias c ON c.cat_codigo = p.cat_codigo
            ORDER BY p.prod_nome ASC
        ");

        $produtos = $stmt->fetchAll();

        $this->view('produtos/index', [
            'titulo'   => 'Produtos',
            'produtos' => $produtos,
            'page'     => 'produtos'
        ]);
    }

    public function create()
    {
        $categorias = $this->db->query("SELECT cat_codigo, cat_nome FROM categorias ORDER BY cat_nome ASC")->fetchAll();

        $this->view('produtos/create', [
            'titulo'     => 'Novo Produto',
            'categorias' => $categorias,
            'page'       => 'produtos'
        ]);
    }

    public function store()
    {
        $nome        = trim($_POST['nome'] ?? '');
        $preco       = str_replace(['.', ','], ['', '.'], $_POST['preco'] ?? '0');
        $categoria   = $_POST['categoria_id'] ?? null;
        $descricao   = $_POST['descricao'] ?? '';
        $foto        = $_FILES['foto'] ?? null;

        if (empty($nome) || empty($preco) || empty($categoria)) {
            $_SESSION['erro'] = "Preencha todos os campos obrigatórios!";
            $this->redirect('?controller=Produto&action=create');
        }

        // Upload da foto
        $fotoNome = 'sem-foto.jpg';
        if ($foto && $foto['error'] === UPLOAD_ERR_OK) {
            $extensoes = ['jpg', 'jpeg', 'png', 'webp'];
            $ext = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $extensoes)) {
                $fotoNome = uniqid('prod_') . '.' . $ext;
                $destino = __DIR__ . '/../../public/assets/produtos/' . $fotoNome;
                // Cria a pasta se não existir
                if (!is_dir(dirname($destino))) {
                    mkdir(dirname($destino), 0777, true);
                }
                move_uploaded_file($foto['tmp_name'], $destino);
            }
        }

        $this->db->query("
            INSERT INTO produtos (prod_nome, prod_descricao, prod_preco, prod_imagem, cat_codigo, prod_ativo)
            VALUES (:nome, :desc, :preco, :foto, :cat, 1)
        ", [
            ':nome' => $nome,
            ':desc' => $descricao,
            ':preco' => $preco,
            ':foto' => $fotoNome,
            ':cat'  => $categoria
        ]);

        $_SESSION['sucesso'] = "Produto cadastrado com sucesso!";
        $this->redirect('?controller=Produto&action=index');
    }

    public function edit($id = null)
    {
        $id = $id ?? $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('?controller=Produto&action=index');
        }

        $produto = $this->db->query("SELECT * FROM produtos WHERE prod_codigo = :id", [':id' => $id])->fetch();
        $categorias = $this->db->query("SELECT cat_codigo, cat_nome FROM categorias ORDER BY cat_nome ASC")->fetchAll();

        if (!$produto) {
            $_SESSION['erro'] = "Produto não encontrado!";
            $this->redirect('?controller=Produto&action=index');
        }

        $this->view('produtos/edit', [
            'titulo'     => 'Editar Produto',
            'produto'    => $produto,
            'categorias' => $categorias,
            'page'       => 'produtos'
        ]);
    }

    public function update($id = null)
    {
        $id         = $id ?? $_POST['id'] ?? null;
        $nome       = trim($_POST['nome'] ?? '');
        $preco      = str_replace(['.', ','], ['', '.'], $_POST['preco'] ?? '0');
        $categoria  = $_POST['categoria_id'] ?? null;
        $descricao  = $_POST['descricao'] ?? '';
        $fotoAtual  = $_POST['foto_atual'] ?? 'sem-foto.jpg';
        $foto       = $_FILES['foto'] ?? null;

        if (!$id || empty($nome) || empty($preco) || empty($categoria)) {
            $_SESSION['erro'] = "Preencha todos os campos!";
            $this->redirect("?controller=Produto&action=edit&id=$id");
        }

        $fotoNome = $fotoAtual;

        if ($foto && $foto['error'] === UPLOAD_ERR_OK) {
            $extensoes = ['jpg', 'jpeg', 'png', 'webp'];
            $ext = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $extensoes)) {
                // Apaga foto antiga
                if ($fotoAtual !== 'sem-foto.jpg') {
                    $antiga = __DIR__ . '/../../public/assets/produtos/' . $fotoAtual;
                    if (file_exists($antiga)) unlink($antiga);
                }
                $fotoNome = uniqid('prod_') . '.' . $ext;
                move_uploaded_file($foto['tmp_name'], __DIR__ . '/../../public/assets/produtos/' . $fotoNome);
            }
        }

        $this->db->query("
            UPDATE produtos SET 
                prod_nome = :nome,
                prod_descricao = :desc,
                prod_preco = :preco,
                prod_imagem = :foto,
                cat_codigo = :cat
            WHERE prod_codigo = :id
        ", [
            ':nome' => $nome,
            ':desc' => $descricao,
            ':preco' => $preco,
            ':foto' => $fotoNome,
            ':cat'  => $categoria,
            ':id'   => $id
        ]);

        $_SESSION['sucesso'] = "Produto atualizado com sucesso!";
        $this->redirect('?controller=Produto&action=index');
    }

    public function delete($id = null)
    {
        $id = $id ?? $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('?controller=Produto&action=index');
        }

        $foto = $this->db->query("SELECT prod_imagem FROM produtos WHERE prod_codigo = :id", [':id' => $id])->fetchColumn();
        if ($foto && $foto !== 'sem-foto.jpg') {
            $caminho = __DIR__ . '/../../public/assets/produtos/' . $foto;
            if (file_exists($caminho)) unlink($caminho);
        }

        $this->db->query("DELETE FROM produtos WHERE prod_codigo = :id", [':id' => $id]);
        $_SESSION['sucesso'] = "Produto excluído com sucesso!";
        $this->redirect('?controller=Produto&action=index');
    }
}