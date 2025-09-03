<?php

namespace App\Services;

use App\DTO\ProductData;
use App\Enums\ProductStatusEnum;
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

        $this->updatePrice($product, $data);
        $this->updateAmount($product, $data);
        $this->updateName($product, $data);
        $this->updateStatus($product, $data);

        if ($product->isDirty()) {
            $this->repository->update($product);
        }

        return $product;
    }

    private function updatePrice(ProductModel $product, ProductData $data): void
    {
        if ($data->price !== null && $data->price != $product->price) {
            $product->price = $data->price;
        }
    }

    private function updateAmount(ProductModel $product, ProductData $data): void
    {
        if ($data->amount !== null && $data->amount != $product->amount) {
            $product->amount = $data->amount;

            $this->syncStatusWithAmount($product, $data);
        }
    }

    private function updateName(ProductModel $product, ProductData $data): void
    {
        if ($data->name !== null && $data->name != $product->name) {
            $this->validateUniqueName($data);

            $product->name = $data->name;
        }
    }

    private function updateStatus(ProductModel $product, ProductData $data): void
    {
        if ($data->status !== null && $data->status != $product->status) {
            $product->status = ProductStatusEnum::translateStatus($data->status);
            
            $this->syncAmountWithStatus($product);
        }
    }

    private function syncStatusWithAmount(ProductModel $product, ProductData $data): void
    {
        $data->status = $product->amount > 0
            ? ProductStatusEnum::ACTIVE->value
            : ProductStatusEnum::INACTIVE->value;
    }

    private function syncAmountWithStatus(ProductModel $product): void
    {
        if ($product->status == ProductStatusEnum::INACTIVE->value) {
            $product->amount = 0;
        }
    }

    public function findOne(ProductData $productData): ProductModel
    {
        $product = $this->repository->findOne($productData);
        if (!$product) {
            throw new \Exception("Produto não encontrado.");
        }

        return $product;
    }

    public function delete(ProductData $productData): void
    {
        $product = $this->repository->findOne($productData);

        if (!$product) {
            throw new \Exception("Produto não encontrado.");
        }
    
        if ($product->status === ProductStatusEnum::INACTIVE->value) {
           throw new \Exception("Produto {$product->name} já está inativo.");
        }

        $this->repository->delete($product);
    }
}
