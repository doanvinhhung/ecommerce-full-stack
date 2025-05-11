<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
         'coupon_id',
    'coupon_code',
        'order_code',
        'billing_info',
        'subtotal',
        'shipping_fee',
        'discount',
        'total',
        'payment_method',
        'payment_status',
        'transaction_id',
        'status',
        'notes'
    ];

    protected $casts = [
        'billing_info' => 'array',
        'subtotal' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // Các trạng thái đơn hàng
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    // Các phương thức thanh toán
    const PAYMENT_METHOD_COD = 'cod';
    const PAYMENT_METHOD_MOMO = 'momo';
    const PAYMENT_METHOD_VNPAY = 'vnpay';

    // Trạng thái thanh toán
    const PAYMENT_STATUS_PENDING = 'pending';
    const PAYMENT_STATUS_COMPLETED = 'completed';
    const PAYMENT_STATUS_FAILED = 'failed';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->using(OrderProduct::class)
            ->withPivot('quantity', 'price',  'image','color', 'size') // Đảm bảo có color và size
            ->withTimestamps();
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

// Trong app/Models/Order.php
protected static function boot()
{
    parent::boot();

    static::creating(function ($order) {
        $order->order_code = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(uniqid());
        
        // Validate coupon usage khi tạo đơn
        if ($order->coupon_id) {
            $coupon = Coupon::find($order->coupon_id);
            if ($coupon && $coupon->max_use_per_user > 0) {
                $usageCount = self::where('user_id', $order->user_id)
                    ->where(function($query) use ($coupon) {
                        $query->where('coupon_id', $coupon->id)
                              ->orWhere('coupon_code', $coupon->code);
                    })
                    ->count();
                
                if ($usageCount >= $coupon->max_use_per_user) {
                    throw new \Exception("Bạn đã sử dụng mã này đủ số lần cho phép");
                }
            }
        }
    });
}
    public static function generateOrderCode()
    {
        return 'ORD-' . now()->format('YmdHis') . '-' . strtoupper(substr(uniqid(), -6));
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->total, 0, ',', '.') . '₫';
    }
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
    public function getFullAddressAttribute()
    {
        $billing = $this->billing_info;
        return implode(', ', array_filter([
            $billing['address'] ?? null,
            $billing['city'] ?? null,
            $billing['zip'] ?? null
        ]));
    }

    public function getCustomerNameAttribute()
    {
        $billing = $this->billing_info;
        return $billing['name'] ?? '';
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
     // Màu badge trạng thái đơn hàng
     public function getStatusBadgeColorAttribute()
     {
         return match($this->status) {
             self::STATUS_PENDING => 'warning',
             self::STATUS_PROCESSING => 'info',
             self::STATUS_SHIPPED => 'primary',
             self::STATUS_DELIVERED => 'success',
             self::STATUS_CANCELLED => 'danger',
             default => 'secondary',
         };
     }
 
     // Màu badge trạng thái thanh toán
     public function getPaymentStatusBadgeColorAttribute()
     {
         return match($this->payment_status) {
             self::PAYMENT_STATUS_PENDING => 'warning',
             self::PAYMENT_STATUS_COMPLETED => 'success',
             self::PAYMENT_STATUS_FAILED => 'danger',
             default => 'secondary',
         };
     }
}