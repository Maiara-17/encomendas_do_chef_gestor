<?php

namespace App\Controllers;

class CarrinhoController extends Controller
{
    public function visualizar()
    {
        return $this->view('carrinho/visualizar');
    }
}
