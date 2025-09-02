<?php

namespace App\Http\Controllers;

use App\DTO\ProductData;
use App\Http\Requests\ProductFilterRequest;
use App\Services\ProductService;

class ProductWebController extends Controller
{
    private ProductService $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function index(ProductFilterRequest $request)
    {
        try {
            $filters = $request->validated();

            $products = $this->service->findAllPaginated($filters);

            return view('products.index', compact('products'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors([$e->getMessage()]);
        }
    }

    public function show(string $id)
    {
        try {
            $productData = new ProductData($id);

            $product = $this->service->findOne($productData);

            return view('products.show', compact('product'));
        } catch (\Exception $e) {
            return redirect()->route('products.index')->withInput()->withErrors([$e->getMessage()]);
        }
    }
}
