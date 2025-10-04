<?php

use App\Http\Controllers\ProductWebController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('products', [ProductWebController::class, 'index'])->name('products.index');

Route::get('products/view-create', [ProductWebController::class, 'viewCreate'])->name('products.viewCreate');
Route::post('products', [ProductWebController::class, 'create'])->name('products.create');

Route::get('products/{id}', [ProductWebController::class, 'show'])->name('products.show');
Route::get('products/{id}/edit', [ProductWebController::class, 'viewEdit'])->name('products.viewEdit');
Route::put('products/{id}', [ProductWebController::class, 'update'])->name('products.update');
Route::delete('products/{id}', [ProductWebController::class, 'destroy'])->name('products.destroy');
