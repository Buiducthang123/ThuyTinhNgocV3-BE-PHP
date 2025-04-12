<?php

namespace App\Models;

use App\Enums\ProductTransactionStatus;
use App\Enums\ProductTransactionType;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'cover_image',
        'short_description',
        'description',
        'thumbnail',
        'is_sale',
        'price',
        'discount',
        'pages',
        'weight',
        'height',
        'dimension_length',
        'dimension_width',
        'deleted_at',
    ];

    public function casts()
    {
        return [
            'thumbnail' => 'array',
        ];
    }

    protected $appends = ['quantity','rating','total_review'];
    public function getQuantityAttribute()
    {
        // Lọc các giao dịch import với điều kiện status = success
        $import = $this->productTransactions()
            ->where('type', ProductTransactionType::IMPORT)
            ->where('status', ProductTransactionStatus::SUCCESS)
            ->sum('quantity');
        $import = (int) $import;
        // Lọc các giao dịch export với điều kiện status = success
        $export = $this->productTransactions()
            ->where('type', ProductTransactionType::EXPORT)
            ->where('status', ProductTransactionStatus::SUCCESS)
            ->sum('quantity');
        $export = (int) $export;

        //Kiểm tra sản phẩm trong order bán ra
        $orderExport = $this->orderItems()
            ->whereHas('order', function ($query) {
                $query->where('status', '!=', OrderStatus::CANCELLED);
            })
            ->sum('quantity');
        // Tổng số sản phẩm = import - export
        return $import - $export - $orderExport;
    }

    public function getDiscountAttribute()
    {
        //Kiểm tra xem sản phẩm có promotion không nếu có thì lấy giảm giá từ promotion
        // kiểm tra start_date và end_date của promotion tính theo giờ phút giây
        // nếu promotion đang diễn ra thì lấy giảm giá từ promotion
        // nếu promotion đã kết thúc thì lấy giảm giá từ discount mặc định của sản phẩm
        // nếu không có promotion thì lấy giảm giá mặc định của sản phẩm
        $promotion = $this->promotion;
        if ($promotion) {
            $start_date = strtotime($promotion->start_date); // chuyển ngày về giây
            $end_date = strtotime($promotion->end_date);
            $now = strtotime(now());
            if ($start_date <= $now && $now <= $end_date) {
                return $promotion->discount;
            }
        }
        return $this->attributes['discount'];
    }

    public function getRatingAttribute()
    {
        // Tính trung bình rating của sản phẩm
        //mặc định là 5 sao
        $rating = 5;
        $reviews = $this->reviews;
        if ($reviews->count() > 0) {
            $rating = $reviews->avg('rating');
        }
        return $rating;
    }

    public function getTotalReviewAttribute()
    {
        return $this->reviews->count();
    }

    public function getThumbnailAttribute($value)
    {
        return json_decode($value);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function discountTiers()
    {
        return $this->hasMany(DiscountTiers::class);
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function productTransactions()
    {
        return $this->hasMany(ProductTransaction::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }


}
