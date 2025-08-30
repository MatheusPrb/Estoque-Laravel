<?php

namespace App\Repositories;

use App\Domain\Product\Product;
use App\Models\ProductModel;
use App\Domain\Product\ProductPersistenceInterface;
use Illuminate\Support\Facades\Log;

class ProductRepository implements ProductPersistenceInterface
{
    public function save(Product $product): void
    {
        try {
            ProductModel::create([
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'amount' => $product->getAmount(),
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao salvar produto: {message}', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw new \RuntimeException($e->getMessage(), 500, $e);
        }
    }

    public function findById(string $id): ProductModel
    {
        return ProductModel::find($id);
    }
}
