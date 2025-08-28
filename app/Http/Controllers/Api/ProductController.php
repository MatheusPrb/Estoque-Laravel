<?php

namespace App\Http\Controllers\Api;

use App\Domain\Product\Product;
use App\Http\Controllers\Controller;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $params = $request->validate([
                'name' => 'required|alpha|max:255',
                'value' => 'required|numeric',
                'amount' => 'required|integer',
            ]);

            $product = (new Product(new ProductRepository()))
                ->setId(Product::generateUuid())
                ->setName($params['name'])
                ->setPrice($params['value'])
                ->setAmount($params['amount'])
            ;

            $product->saveProduct();

            $response = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'value' => $product->getPrice(),
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
