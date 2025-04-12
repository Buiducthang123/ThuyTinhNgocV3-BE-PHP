<?php

namespace App\Http\Controllers;

use App\Services\ReviewService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    protected $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function create(Request $request)
    {
        return $this->reviewService->create($request->all());
    }

    public function getAll(Request $request)
    {
        $paginate = $request->get('paginate') ?? null;

        $with = $request->get('with') ?? [];

        $sort = $request->get('sort') ?? null;

        $filter = $request->get('filter') ?? null;

        return $this->reviewService->getAll($paginate, $with, $filter, $sort);
    }

    public function update(Request $request, $id)
    {
        $result = $this->reviewService->update($id, $request->all());

        if($result){
            return response()->json(['message' => 'Cập nhật thành công'], 200);
        }
        return response()->json(['message' => 'Cập nhật thất bại'], 400);
    }

    public function showByProduct(Request $request, $product_id)
    {
        $paginate = $request->get('paginate') ?? 10;

        $with = $request->get('with') ?? [];

        return $this->reviewService->showByProduct($paginate, $product_id, $with);
    }
}
