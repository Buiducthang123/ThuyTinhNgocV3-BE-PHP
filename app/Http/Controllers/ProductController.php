<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    public function index(Request $request)
    {
        $data = $request->all();
        $products = $this->productService->getAll($data);
        return response()->json($products);
    }

    public function create(ProductRequest $request)
    {
        try {
            $product = $this->productService->create($request->all());
            return response()->json($product);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function show($id, Request $request)
    {
        $product = $this->productService->show($id, $request->all());
        return response()->json($product);
    }

    public function update(ProductRequest $request, $id)
    {
        $product = $this->productService->update($request->all(), $id);
        return response()->json($product);
    }

    public function getProductByCategory($category_id, Request $request)
    {
        $data = $request->all();
        $products = $this->productService->getProductByCategory($category_id, $data);
        return response()->json($products);
    }
}
