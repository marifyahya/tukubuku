<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderPaymentHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'midtrans_order_id',
        'payment_token',
        'payment_method',
        'payment_status',
        'gross_amount',
        'expiry_at',
        'payload',
        'paid_at',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'expiry_at' => 'datetime',
        'payload' => 'json',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the order that owns the payment history.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
