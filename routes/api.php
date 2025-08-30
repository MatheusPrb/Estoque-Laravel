<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Rota de cadastro de usuário
Route::post('/users', [UserController::class, 'store']);

// Rota de cadastro de produto
Route::post('/products', [ProductController::class, 'store']);

// Rota de listagem de todos os produtos
Route::get('/products', [ProductController::class, 'show']);

// Rota de listagem de um produto
Route::get('/products/{id}', [ProductController::class, 'showOne']);

