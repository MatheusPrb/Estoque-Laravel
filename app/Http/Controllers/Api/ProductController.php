<?php

namespace App\Http\Controllers\Api;

use App\Domain\Product\Product;
use App\Http\Controllers\Controller;
use App\Repositories\ProductRepository;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    private ProductService  $service ;

    public function __construct(ProductService $service)
    {
        $this->service  = $service ;
    }

    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        try {
            $params = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric',
                'amount' => 'required|integer',
            ]);

            $product = (new Product(new ProductRepository()))
                ->setId(Product::generateUuid())
                ->setName($params['name'])
                ->setPrice($params['price'])
                ->setAmount($params['amount'])
            ;

            $product->saveProduct();

            $response = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'amount' => $product->getAmount(),
            ];

            return response()->json($response, 201,);
        } catch (ValidationException $e) {
            Log::error('Erro ao salvar produto: {message}', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Erro ao salvar produto: {message}', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(string $id)
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
