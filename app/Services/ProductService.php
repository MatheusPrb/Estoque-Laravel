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

    public function findAllPaginated(?int $perPage = null, ?int $page = null): LengthAwarePaginator
    {
        $perPage = $perPage ?? 10;
        $page    = $page ?? 1;

        if ($perPage <= 0 || $page <= 0) {
            throw new \InvalidArgumentException('Parâmetros de paginação inválidos.');
        }

        $products = $this->repository->findAllPaginated($perPage, $page);
        if ($products->isEmpty()) {
            throw new \Exception('Nenhum produto encontrado.');
        }

        return $products;
    }

    public function edit(ProductData $data): ProductModel
    {
        $product = $this->repository->findOne($data);

        if ($data->price !== null) {
            $product->price = $data->price;
        }

        if ($data->amount !== null) {
            $product->amount = $data->amount;
        }

        if ($data->name !== null) {
            $this->validateUniqueName($data);

            $product->name = $data->name;
        }

        $this->repository->update($product);

        return $product;
    }

    public function findOne(ProductData $productData): ProductModel
    {
        $product = $this->repository->findOne($productData);
        if (!$product) {
            throw new \Exception("Produto '{$productData->id}' não encontrado.");
        }

        return $product;
    }
}
