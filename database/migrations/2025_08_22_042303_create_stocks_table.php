<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->uuid('id')->primary();           // UUID como chave primária
            $table->string('product_name');          // Nome do produto
            $table->string('sku')->unique();         // Código único do produto (opcional)
            $table->integer('quantity')->default(0); // Quantidade em estoque
            $table->decimal('price', 10, 2)->nullable(); // Preço do produto (opcional)
            $table->timestamps();                    // created_at e updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};


