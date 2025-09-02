<?php

namespace App\Http\Controllers;

use App\DTO\ProductData;
use App\Http\Requests\ProductFilterRequest;
use App\Services\ProductService;
use Illuminate\Http\Request;

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

    public function viewEdit(string $id)
    {
        try {
            $productData = new ProductData($id);

            $product = $this->service->findOne($productData);

            return view('products.viewEdit', compact('product'));
        } catch (\Exception $e) {
            return redirect()->route('products.index')->withInput()->withErrors([$e->getMessage()]);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $data = new ProductData(
                $id,
                $request->input('name'),
                $request->input('status'),
                $request->input('price'),
                $request->input('amount')
            );

            $product = $this->service->edit($data);

            return redirect()->route('products.viewEdit', $product->id)->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('products.viewEdit', $id)->withInput()->withErrors([$e->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        try {
            $productData = new ProductData($id);

            $this->service->delete($productData);

            return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('products.index')->withInput()->withErrors([$e->getMessage()]);
        }
    }
}
