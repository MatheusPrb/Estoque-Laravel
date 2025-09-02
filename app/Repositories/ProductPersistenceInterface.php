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
    public function findAllPaginated(int $perPage, int $page, array $filters = []): LengthAwarePaginator;
    public function update(ProductModel $product): ProductModel;
    public function findOne(ProductData $data): ?ProductModel;
}
