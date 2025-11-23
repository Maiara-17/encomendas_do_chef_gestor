<?php

// src/Controllers/CarrinhoController.php
// VERSÃO FINAL – SEM NAMESPACE, SEM EXTENDS

class CarrinhoController
{
    public function visualizar()
    {
        // Quando chegarmos na parte do carrinho, vamos implementar de verdade
        // Por enquanto, só carrega a view para não dar erro
        require_once __DIR__ . '/../Views/carrinho/visualizar.php';
    }

    // Você pode adicionar mais métodos depois:
 // adicionar(), remover(), limpar(), finalizar(), etc.
}