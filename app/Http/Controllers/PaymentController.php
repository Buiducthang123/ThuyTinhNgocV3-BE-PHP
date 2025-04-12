<?php

namespace App\Http\Controllers;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function getAll(Request $request)
    {
        $paginate = $request->get('paginate') ?? null;

        $with = $request->get('with') ?? [];

        $filter = $request->get('filter') ?? null;

        $sort = $request->get('sort') ?? null;

        return $this->paymentService->getAll($paginate, $with, $filter, $sort);
    }

    public function getMyPayment(Request $request)
    {
        $paginate = $request->get('paginate') ?? null;

        $with = $request->get('with') ?? [];

        $filter = $request->get('filter') ?? null;

        $sort = $request->get('sort') ?? null;

        try{
            $result = $this->paymentService->getMyPayment($paginate, $with, $filter, $sort);
            return response()->json($result, 200);
        }
        catch(\Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

}
