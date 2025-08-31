<?php

namespace App\Services;

use App\DTO\ProductData;
use App\Models\ProductModel;
use App\Repositories\ProductPersistenceInterface;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    use Uuid;

    private ProductPersistenceInterface $repository;

    public function __construct(ProductPersistenceInterface $repository)
    {
        $this->repository = $repository;
    }

    public function create(ProductData $data): ProductModel
    {
        $this->validateUniqueName($data);

        return $this->repository->create($data);
    }

    private function validateUniqueName(ProductData $data): void
    {
        if ($this->repository->validateName($data)) {
            throw new \Exception("Produto '{$data->name}' já cadastrado.");
        }
    }

    public function findAll(): Collection
    {
        $products = $this->repository->findAll();
        if ($products->isEmpty()) {
            throw new \InvalidArgumentException('Nenhum produto encontrado.');
        }

        return $products;
    }

    public function findAllPaginated(int $perPage = 10, int $page = 1): LengthAwarePaginator
    {
        if ($perPage <= 0 || $page <= 0) {
            throw new \InvalidArgumentException('Parâmetros de paginação inválidos.');
        }

        $products = $this->repository->findAllPaginated($perPage, $page);
        if ($products->isEmpty()) {
            throw new \InvalidArgumentException('Nenhum produto encontrado.');
        }

        return $products;
    }

    public function findById(string $id): ProductModel
    {
        if (!$this->isValidUuid($id)) {
            throw new \InvalidArgumentException('Invalid UUID format');
        }

        $product = $this->repository->findById($id);

        if (!$product) {
            throw new \Exception("Produto '{$id}' não encontrado.");
        }

        return $product;
    }
}
