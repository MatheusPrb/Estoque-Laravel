<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductFilterRequest;
use App\Services\ProductService;
use Illuminate\View\View;
use App\Enums\ProductStatusEnum;

class ProductWebController extends Controller
{
    private ProductService $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function index(ProductFilterRequest $request): View
    {
        $filters = $request->validated();

        $products = $this->service->findAllPaginated($filters);

        foreach ($products as $product) {
            $product->isActive = $product->status === ProductStatusEnum::ACTIVE->value;
        }

        return view('products.index', compact('products'));
    }
}
