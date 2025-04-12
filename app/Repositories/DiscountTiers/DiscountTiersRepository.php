<?php
namespace App\Repositories\DiscountTiers;

use App\Models\DiscountTiers;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class DiscountTiersRepository extends BaseRepository implements DiscountTiersRepositoryInterface
{
    public function getModel()
    {
        return DiscountTiers::class;
    }

    public function createMany($attributes = [])
    {

        $product_id = $attributes['product_id'];
        $discountTiers = $attributes['tiers'];

        $data = [];
        foreach ($discountTiers as $tier) {
            $data[] = [
                'product_id' => $product_id,
                'minimum_quantity' => $tier['minimum_quantity'],
                'price' => $tier['price'],
            ];
        }

        DB::beginTransaction();
        try {
            // $this->model->insert($data);
            //Tìm kiếm theo product_id và minimum_quantity nếu tồn tại thì update giá cũng như tạo mới
            foreach ($data as $tier) {
                $discountTier = $this->model->where('product_id', $tier['product_id'])
                    ->where('minimum_quantity', $tier['minimum_quantity'])
                    ->first();
                if ($discountTier) {
                    $discountTier->update($tier);
                } else {
                    $this->model->create($tier);
                }
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function create($attributes = [])
    {
        $result = $this->model->updateOrCreate(
            [
                'product_id' => $attributes['product_id'],
                'minimum_quantity' => $attributes['minimum_quantity']
            ],
            [
                'price' => $attributes['price']
            ]
        );
        if ($result) {
            return $result;
        }
        return false;
    }

}
