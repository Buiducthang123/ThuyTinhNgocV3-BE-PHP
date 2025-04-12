<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'type',
        'status',
        'quantity',
        'price',
        'date',
        'note',
    ];

    public function casts(){
        return [
            'price' => 'integer',
            // 'date' => 'date:d-m-Y',
        ];
    }

    public function setDateAttribute($value){
        // chuyển định dạng ngày về Y-m-d
        $this->attributes['date'] =  \Carbon\Carbon::parse($value)->format('Y-m-d');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
