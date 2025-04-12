<?php

namespace App\Http\Controllers;

use App\Http\Requests\PromotionRequest;
use App\Services\PromotionService;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    protected $promotionService;

    public function __construct(PromotionService $promotionService)
    {
        $this->promotionService = $promotionService;
    }

    public function create(PromotionRequest $request)
    {
        try {
          $result = $this->promotionService->create($request->all());
            return response()->json($result, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function all(Request $request){
        $result = $this->promotionService->getAll($request->all());
        return response()->json($result);
    }

    public function show($id, Request $request){
        $result = $this->promotionService->show($id, $request->all());
        return response()->json($result);
    }

    public function update(PromotionRequest $request, $id){
        $result = $this->promotionService->update($id, $request->all());
        return response()->json($result);
    }

    public function delete($id){
        $result = $this->promotionService->delete($id);
        return response()->json($result);
    }
}
