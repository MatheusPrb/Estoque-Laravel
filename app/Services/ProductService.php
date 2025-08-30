<?php

namespace App\Services;

use App\Domain\Product\ProductPersistenceInterface;
use App\Models\ProductModel;
use App\Traits\Uuid;

class ProductService
{
    use Uuid;

    private ProductPersistenceInterface $repository;

    public function __construct(ProductPersistenceInterface $repository)
    {
        $this->repository = $repository;
    }

    public function findById(string $id): ProductModel
    {
        if (!$this->isValidUuid($id)) {
            throw new \InvalidArgumentException('Invalid UUID format');
        }

        $product = $this->repository->findById($id);

        if (!$product) {
            throw new \Exception("Produto {$id} não encontrado.");
        }

        return $product;
    }
}
