<?php

namespace App\Domain\Product;

use App\Models\ProductModel;

interface ProductPersistenceInterface
{
    public function save(Product $product): void;
    public function findById(string $id): ProductModel;
}
