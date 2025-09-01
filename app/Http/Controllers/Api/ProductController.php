<?php

namespace App\Http\Controllers\Api;

use App\DTO\ProductData;
use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Traits\Uuid;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    use Uuid;

    private ProductService  $service ;

    public function __construct(ProductService $service)
    {
        $this->service  = $service ;
    }

    public function register(Request $request)
    {
        try {
            $params = $request->validate([
                'name' => 'required|string',
                'price' => 'required|numeric',
                'amount' => 'required|integer',
            ]);

            $productData = new ProductData(
                ProductData::generateUuid(),
                $params['name'],
                $params['price'],
                $params['amount']
            );

            $product = $this->service->create($productData);

            return response()->json($product->only(['id', 'name', 'price', 'amount']), 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function findAll(Request $request)
    {
       try {
            $validated = $request->validate([
                'per_page' => 'integer|min:1',
                'page' => 'integer|min:1',
            ]);

            $perPage = $validated['per_page'] ?? null;
            $page    = $validated['page'] ?? null;

            $products = $this->service->findAllPaginated($perPage, $page);

            return response()->json($products->toArray(), 200);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function findOne(string $id)
    {
        try {
            $productData = new ProductData($id);

            $product = $this->service->findOne($productData);

            return response()->json($product->only(['id', 'name', 'price', 'amount']), 200);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request)
    {
        try {
            $data = new ProductData(
                $request->route('id'),
                $request->input('name'),
                $request->input('price'),
                $request->input('amount')
            );

            $product = $this->service->edit($data);

            return response()->json($product->only(['id', 'name', 'price', 'amount']), 200);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function destroy(string $id)
    {
        //
    }
}
