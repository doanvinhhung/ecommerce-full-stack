<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'transaction_code',
        'amount',
        'payment_method',
        'status',
        'transaction_id',
        'payment_info'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_info' => 'array'
    ];

    // Trạng thái giao dịch
    const STATUS_PENDING = 'pending';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            $transaction->transaction_code = 'TRX-' . now()->format('Ymd') . '-' . strtoupper(uniqid());
        });
    }
    public static function generateTransactionCode()
    {
        return 'TRX-' . now()->format('YmdHis') . '-' . strtoupper(substr(uniqid(), -6));
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 0, ',', '.') . '₫';
    }

    public function markAsSuccess($transactionId = null)
    {
        $this->update([
            'status' => self::STATUS_SUCCESS,
            'transaction_id' => $transactionId ?? $this->transaction_id,
            'completed_at' => now()
        ]);
    }

    public function markAsFailed($reason = null)
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'failure_reason' => $reason,
            'completed_at' => now()
        ]);
    }
}