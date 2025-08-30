<?php

namespace App\Repositories;

use App\Models\ProductModel;
use App\DTO\ProductData;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements ProductPersistenceInterface
{
    public function create(ProductData $data): ProductModel
    {
        return ProductModel::create([
            'id' => $data->id,
            'name' => $data->name,
            'price' => $data->price,
            'amount' => $data->amount,
        ]);
    }

    public function validateName(ProductData $data): bool
    {
        return ProductModel::where('name', $data->name)->exists();
    }

    public function findAll(): Collection
    {
        return ProductModel::all();
    }

    public function findById(string $id): ProductModel
    {
        return ProductModel::find($id);
    }
}
