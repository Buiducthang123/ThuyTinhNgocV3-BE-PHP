<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    protected $fillable = [
        'user_id',
        'receiver_name',
        'receiver_phone_number',
        'province',
        'district',
        'ward',
        'specific_address',
        'is_default'
    ];

    public function casts()
    {
        return [
            'province' => 'array',
            'district' => 'array',
            'ward' => 'array',
            'is_default' => 'boolean'
        ];
    }

    public function setProvinceAttribute($value)
    {
        $this->attributes['province'] = json_encode($value);
    }


    public function setDistrictAttribute($value)
    {
        $this->attributes['district'] = json_encode($value);
    }

    public function setWardAttribute($value)
    {
        $this->attributes['ward'] = json_encode($value);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
