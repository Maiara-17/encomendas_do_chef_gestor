<?php

/**
 * ARQUIVO DE ROTAS DO SISTEMA
 * OBS: $router já deve existir antes de incluir este arquivo
 */

// ==================== ROTAS DE AUTENTICAÇÃO ====================
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');

// ==================== DASHBOARD ====================
$router->get('/', 'DashboardController@index');
$router->get('/dashboard', 'DashboardController@index');

// ==================== CATEGORIAS ====================
$router->get('/categorias', 'CategoriaController@index');
$router->get('/categorias/add', 'CategoriaController@create');
$router->post('/categorias/add', 'CategoriaController@store');
$router->get('/categorias/edit/{id}', 'CategoriaController@edit');
$router->post('/categorias/edit/{id}', 'CategoriaController@update');
$router->post('/categorias/delete/{id}', 'CategoriaController@delete');

// ==================== PRODUTOS ====================
$router->get('/produtos', 'ProdutoController@index');
$router->get('/produtos/add', 'ProdutoController@create');
$router->post('/produtos/add', 'ProdutoController@store');
$router->get('/produtos/edit/{id}', 'ProdutoController@edit');
$router->post('/produtos/edit/{id}', 'ProdutoController@update');
$router->post('/produtos/delete/{id}', 'ProdutoController@delete');

// ==================== PEDIDOS ====================
$router->get('/pedidos', 'PedidoController@index');
$router->get('/pedidos/view/{id}', 'PedidoController@show');
$router->post('/pedidos/update-status/{id}', 'PedidoController@updateStatus');

// ==================== PROMOÇÕES ====================
$router->get('/promocoes', 'PromocaoController@index');
$router->get('/promocoes/add', 'PromocaoController@create');
$router->post('/promocoes/add', 'PromocaoController@store');
$router->get('/promocoes/edit/{id}', 'PromocaoController@edit');
$router->post('/promocoes/edit/{id}', 'PromocaoController@update');

// ==================== RELATÓRIOS ====================
$router->get('/relatorios/vendas', 'RelatorioController@vendas');

// ==================== CARRINHO ====================
$router->get('/carrinho/visualizar', 'CarrinhoController@visualizar');
