<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountTiers extends Model
{
    protected $fillable = [
        'product_id',
        'minimum_quantity',
        'price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
