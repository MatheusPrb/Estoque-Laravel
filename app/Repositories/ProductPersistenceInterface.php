<?php

namespace App\Repositories;

use App\DTO\ProductData;
use App\Models\ProductModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductPersistenceInterface
{
    public function create(ProductData $data): ProductModel;
    public function validateName(ProductData $data): bool;
    public function findAll(): Collection;
    public function findAllPaginated(int $perPage, int $page): LengthAwarePaginator;
    public function findById(string $id): ?ProductModel;
}
