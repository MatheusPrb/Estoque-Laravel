<?php

namespace App\Repositories;

use App\Http\Requests\ProductFilterRequest;
use App\Models\ProductModel;
use App\DTO\ProductData;
use App\Enums\ProductStatusEnum;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

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

    public function findAllPaginated(int $perPage, int $page, array $filters = []): LengthAwarePaginator
    {
        $query = ProductModel::query();

        if (isset($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (isset($filters['status'])) {
            $query->where('status', ProductStatusEnum::translateStatus($filters['status']));
        }

        if (isset($filters['sort'])) {
            $sortMap = ProductFilterRequest::matchSort($filters['sort']);

            $query->orderBy($sortMap[0], $sortMap[1]);
        }

        return $query->withTrashed()->orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'page', $page);
    }

    public function update(ProductModel $product): ProductModel
    {
        $product->save();
        return $product;
    }

    public function findOne(ProductData $data): ?ProductModel
    {
        return ProductModel::withTrashed()->find($data->id);
    }
}
