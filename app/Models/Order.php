<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'shipping_address_id',
        'total_amount',
        'payment_method',
        'status',
        'shipping_fee',
        'voucher_code',
        'discount_amount',
        'final_amount',
        'shipping_address',
        'amount',
        'payment_date',
        'transaction_id',
        'ref_id',
        'note',
    ];

    protected $casts = [
        'status' => 'integer',
    ];


    //encode shipping address
    public function setShippingAddress(){
        $this->attributes['shipping_address'] = json_encode($this->attributes['shipping_address']);
    }

    //decode shipping address

    public function getShippingAddressAttribute($value){
        return json_decode($value);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
