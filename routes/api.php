<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Rota de cadastro de usuário
Route::post('/users', [UserController::class, 'store']);

// Rota de cadastro de produto
Route::post('/products', [ProductController::class, 'store']);
