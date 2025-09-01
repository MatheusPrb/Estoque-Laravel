<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Rota de cadastro de usuário
Route::post('/users', [UserController::class, 'store']);

// Rota de cadastro de produto
Route::post('/register/products', [ProductController::class, 'register']);

// Rota de listagem de todos os produtos
Route::get('/all/products', [ProductController::class, 'findAll']);

// Rota de listagem de um produto
Route::get('/{id}/products', [ProductController::class, 'findOne']);

// Rota de edição de um produto
Route::patch('/edit/{id}/products', [ProductController::class, 'update']);

// Rota de exclusão de um produto
Route::delete('/delete/{id}/products', [ProductController::class, 'delete']);
