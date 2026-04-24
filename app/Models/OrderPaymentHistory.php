<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderPaymentHistory extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_SETTLEMENT = 'settlement';
    public const STATUS_CAPTURE = 'capture';
    public const STATUS_CHALLENGE = 'challenge';
    public const STATUS_DENY = 'deny';
    public const STATUS_EXPIRE = 'expire';
    public const STATUS_CANCEL = 'cancel';

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

    /**
     * Get payment instructions (VA, Bank, etc) from Midtrans payload.
     */
    public function getPaymentInstructionsAttribute(): array
    {
        $payload = $this->payload;
        if (!$payload) {
            return [];
        }

        $instructions = [];

        // 1. Virtual Account (BCA, BNI, BRI, dll)
        if (isset($payload['va_numbers'][0])) {
            $instructions['bank'] = strtoupper($payload['va_numbers'][0]['bank']);
            $instructions['va_number'] = $payload['va_numbers'][0]['va_number'];
            $instructions['type'] = 'va';
        } 
        // 2. Mandiri Bill
        elseif (isset($payload['bill_key'])) {
            $instructions['bank'] = 'MANDIRI';
            $instructions['bill_key'] = $payload['bill_key'];
            $instructions['biller_code'] = $payload['biller_code'];
            $instructions['type'] = 'bill';
        }
        // 3. Permata VA
        elseif (isset($payload['permata_va_number'])) {
            $instructions['bank'] = 'PERMATA';
            $instructions['va_number'] = $payload['permata_va_number'];
            $instructions['type'] = 'va';
        }
        // 4. Retail (Alfamart, Indomaret)
        elseif (isset($payload['payment_code'])) {
            $instructions['bank'] = strtoupper(str_replace('_', ' ', $this->payment_method ?? 'Retail'));
            $instructions['payment_code'] = $payload['payment_code'];
            $instructions['type'] = 'retail';
        }

        return $instructions;
    }
}
