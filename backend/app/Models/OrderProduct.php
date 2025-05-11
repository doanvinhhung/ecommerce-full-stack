<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderProduct extends Pivot
{
    protected $table = 'order_product';

    protected $fillable = [
        'order_id',
        'product_id',
        'image',
        'quantity',
        'price',
        'color',
        'size'
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];
    // Trong Model OrderProduct
// Thêm vào model OrderProduct
protected $appends = ['image_url'];

public function getImageUrlAttribute()
{
    if (!$this->image) {
        return asset('assets/images/default-product.jpg'); 
    }
    return asset($this->image); // Tự động thêm base URL
}
}