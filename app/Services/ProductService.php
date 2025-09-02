<?php

namespace App\Services;

use App\DTO\ProductData;
use App\Http\Requests\ProductFilterRequest;
use App\Models\ProductModel;
use App\Repositories\ProductPersistenceInterface;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    use Uuid;

    public const DEFAULT_PER_PAGE = 10;
    public const DEFAULT_PAGE = 1;

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

    public function findAllPaginated(array $filters = []): LengthAwarePaginator
    {
        $perPage = (int) ($filters[ProductFilterRequest::PER_PAGE] ?? self::DEFAULT_PER_PAGE);
        $page    = (int) ($filters[ProductFilterRequest::PAGE] ?? self::DEFAULT_PAGE);

        if ($perPage <= 0 || $page <= 0) {
            throw new \InvalidArgumentException('Parâmetros de paginação inválidos.');
        }

        return $this->repository->findAllPaginated($perPage, $page, $filters);
    }

    public function edit(ProductData $data): ProductModel
    {
        $product = $this->repository->findOne($data);

        if ($data->price !== null) {
            if ($data->price != $product->price) {
                $product->price = $data->price;
            }
        }

        if ($data->amount !== null) {
            if ($data->amount != $product->amount) {
                $product->amount = $data->amount;
            }
        }

        if ($data->name !== null) {
            if ($data->name != $product->name) {
                $this->validateUniqueName($data);

                $product->name = $data->name;
            }
        }

        if ($data->status !== null) {
            if ($data->status != $product->status) {
                $product->status = $data->status;
            }
        }

        $this->repository->update($product);

        return $product;
    }

    public function findOne(ProductData $productData): ProductModel
    {
        $product = $this->repository->findOne($productData);
        if (!$product) {
            throw new \Exception("Produto não encontrado.");
        }

        return $product;
    }
}
