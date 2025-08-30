<?php

namespace App\Http\Controllers\Api;

use App\DTO\ProductData;
use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    private ProductService  $service ;

    public function __construct(ProductService $service)
    {
        $this->service  = $service ;
    }

    public function register(Request $request)
    {
        try {
            $params = $request->validate([
                'name' => 'required|string|max:255',
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

    public function findAll()
    {
       try {
            $products = $this->service->findAll();

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
            $product = $this->service->findById($id);

            return response()->json($product->only(['id', 'name', 'price', 'amount']), 200);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
