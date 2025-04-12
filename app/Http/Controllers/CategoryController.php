<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    public function index(Request $request)
    {
        $categories = $this->categoryService->getAll($request->all());
        return response()->json($categories);
    }

    public function show($slug, Request $request)
    {
        $category = $this->categoryService->show($slug, $request->all());
        return response()->json($category);
    }

    public function update($id, CategoryRequest $request)
    {
        try{
            $category = $this->categoryService->update($id, $request->all());
            return response()->json($category);
        }
        catch(\Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function create(CategoryRequest $request)
    {
        try{
            $category = $this->categoryService->create($request->all());
            return response()->json($category);
        }
        catch(\Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try{
            $category = $this->categoryService->delete($id);
            return response()->json($category);
        }
        catch(\Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
