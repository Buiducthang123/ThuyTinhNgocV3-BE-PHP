<?php

namespace App\Services;

use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\Promotion\PromotionRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PromotionService {
    protected $promotionRepository;

    protected $productRepository;

    public function __construct(PromotionRepositoryInterface $promotionRepository, ProductRepositoryInterface $productRepository)
    {
        $this->promotionRepository = $promotionRepository;
        $this->productRepository = $productRepository;
    }

    public function create($data=[])
    {
        DB::beginTransaction();
        try {
            $promotion = $this->promotionRepository->create($data);
            foreach ($data['items'] as $productId) {
                $product = $this->productRepository->find($productId);
                if ($product) {
                    $product->promotion_id = $promotion->id;
                    $product->save();
                }
            }
            DB::commit();
            return $promotion;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }

    public function getAll($data=[])
    {
        $paginate = $data['paginate'] ?? null;

        $with = $data['with'] ?? [];

        $filter = $data['filter'] ?? null;

        $sort = $data['sort'] ?? null;

        return $this->promotionRepository->getAll($paginate, $with, $filter, $sort);
    }

    public function show($id, $data=[])
    {
        $with = $data['with'] ?? [];

        return $this->promotionRepository->show($id, $with);
    }

    public function update($id, $data=[])
    {
        DB::beginTransaction();
        try {
            $promotion = $this->promotionRepository->update($id, $data);
            $productIds = $data['items'] ?? [];
            //promotion and product relationship 1-n
            $products = $promotion->products;
            foreach ($products as $product) {
                $product->promotion_id = null;
                $product->save();
            }
            foreach ($productIds as $productId) {
                $product = $this->productRepository->find($productId);
                if ($product) {
                    $product->promotion_id = $promotion->id;
                    $product->save();
                }
            }

            DB::commit();
            return $promotion;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        return $this->promotionRepository->delete($id);
    }
}
