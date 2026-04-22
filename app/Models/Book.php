<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'author',
        'description',
        'price',
        'stock',
        'cover_image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (Book $book) {
            if (!$book->slug) {
                $book->slug = \Illuminate\Support\Str::slug($book->title);
            }
        });

        static::updating(function (Book $book) {
            if ($book->isDirty('title') && !$book->isDirty('slug')) {
                $book->slug = \Illuminate\Support\Str::slug($book->title);
            }
        });
    }

    /**
     * Get all order items for this book.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get all carts for this book.
     */
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }
}