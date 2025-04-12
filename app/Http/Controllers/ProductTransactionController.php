<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductTransactionRequest;
use App\Services\ProductTransactionService;
use Illuminate\Http\Request;

class ProductTransactionController extends Controller
{
    protected $ProductTransactionService;

    public function __construct(ProductTransactionService $ProductTransactionService)
    {
        $this->ProductTransactionService = $ProductTransactionService;
    }

    public function index(Request $request)
    {
        $paginate = $request['paginate'] ?? null;
        $with = $request['with'] ?? [];
        $filters = $request['filters'] ?? null;
        $search = $request['search'] ?? null;
        $transactions = $this->ProductTransactionService->getAll($paginate, $with, $filters, $search);
        return response()->json($transactions);
    }

    public function show($id)
    {
        $with = request('with') ?? [];
        $transaction = $this->ProductTransactionService->show($id, $with);
        return response()->json($transaction);
    }

    public function update(ProductTransactionRequest $request, $id)
    {
        $data = $request->all();
        $transaction = $this->ProductTransactionService->update($data, $id);
        return response()->json($transaction);
    }

    public function create(ProductTransactionRequest $request)
    {
        $data = $request->all();
        $transaction = $this->ProductTransactionService->create($data);
        return response()->json($transaction);
    }
}
