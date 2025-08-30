<?php

namespace App\Repositories;

use App\DTO\ProductData;
use App\Models\ProductModel;
use Illuminate\Database\Eloquent\Collection;

interface ProductPersistenceInterface
{
    public function create(ProductData $data): ProductModel;
    public function validateName(ProductData $data): bool;
    public function findAll(): Collection;
    public function findById(string $id): ProductModel;
}
