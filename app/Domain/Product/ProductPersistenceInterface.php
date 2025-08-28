<?php

namespace App\Domain\Product;

interface ProductPersistenceInterface
{
    public function save(Product $product): void;
    public function loadById(Product $product): bool;
}
