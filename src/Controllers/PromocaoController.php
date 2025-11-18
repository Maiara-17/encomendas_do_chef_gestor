<?php
// Controller: PromocaoController
// Propósito: Controlar as promoções dos produtos.
// Observação: Código simples e pronto para integrar com DB depois.

class PromocaoController
{
    // Lista promoções
    public function index()
    {
        require_once __DIR__ . '/../views/promocoes/index.php';
    }

    // Formulário de criação
    public function criar()
    {
        require_once __DIR__ . '/../views/promocoes/criar.php';
    }

    // Salvar promoção
    public function salvar()
    {
        // Aqui entra a lógica de salvar no banco
        header("Location: /encomendas-do-chef---gestor/?controller=Promocao&action=index");
        exit;
    }

    // Editar promoção
    public function editar()
    {
        require_once __DIR__ . '/../views/promocoes/editar.php';
    }

    // Atualizar promoção
    public function atualizar()
    {
        // Lógica de update
        header("Location: /encomendas-do-chef---gestor/?controller=Promocao&action=index");
        exit;
    }

    // Excluir promoção
    public function excluir()
    {
        // Lógica de delete
        header("Location: /encomendas-do-chef---gestor/?controller=Promocao&action=index");
        exit;
    }
}
