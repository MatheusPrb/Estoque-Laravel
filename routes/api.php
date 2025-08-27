<?php

use App\Http\Controllers\Api\StockController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Rota de cadastro de usuário
Route::post('/users', [UserController::class, 'store']);

// Rota de cadastro de estoque
Route::post('/stocks', [StockController::class, 'store']);
