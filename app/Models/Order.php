<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\OrderStatus;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'shipping_address',
        'total_amount',
        'shipping_cost',
        'status',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'status' => OrderStatus::class,
    ];

    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate order_number: TB-YYMMDD-ID-RANDOM
            // Since we don't have ID yet on creating, we use a placeholder or handle it after creation
            // However, it's better to generate it here. For ID, we can use a temporary sequence or just omit if not possible,
            // but the user specifically asked for ID in the format.
            // Alternative: Generate it in 'created' event or use a reliable way.
            // Let's use Date + Random first, then update with ID if necessary, or just use a very unique random.
            // Wait, the user said TB-YYMMDD-ID-RANDOM.
            $date = now()->format('ymd');
            $random = strtoupper(bin2hex(random_bytes(2))); // 4 chars random
            $model->order_number = "TB-{$date}-PENDING-{$random}";
        });

        static::created(function ($model) {
            // Now we have ID, update the order_number
            $date = now()->format('ymd');
            $random = strtoupper(bin2hex(random_bytes(2)));
            $model->order_number = "TB-{$date}-{$model->id}-{$random}";
            $model->saveQuietly();
        });
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'order_number';
    }

    /**
     * Get the user that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all payment histories for this order.
     */
    public function paymentHistories(): HasMany
    {
        return $this->hasMany(OrderPaymentHistory::class);
    }

    /**
     * Get the latest payment attempt.
     */
    public function latestPayment()
    {
        return $this->hasOne(OrderPaymentHistory::class)->latestOfMany();
    }

    /**
     * Get all order items for this order.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}