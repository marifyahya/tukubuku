<?php

namespace App\Enums;

enum OrderStatus: int
{
    case UNPAID = 0;
    case PACKING = 1;
    case SHIPPED = 2;
    case COMPLETED = 3;
    case CANCELLED = 4;
    case RETURNED = 5;

    /**
     * Mendapatkan label yang bisa dibaca manusia.
     */
    public function label(): string
    {
        return match($this) {
            self::UNPAID    => 'Belum Dibayar',
            self::PACKING   => 'Dikemas',
            self::SHIPPED   => 'Dikirim',
            self::COMPLETED => 'Selesai',
            self::CANCELLED => 'Dibatalkan',
            self::RETURNED  => 'Dikembalikan',
        };
    }
    
    /**
     * Mendapatkan class CSS badge berdasarkan status.
     */
    public function badgeClasses(): string
    {
        return match($this) {
            self::UNPAID    => 'bg-gray-100 text-gray-700 border-gray-200',
            self::PACKING   => 'bg-blue-100 text-blue-700 border-blue-200',
            self::SHIPPED   => 'bg-indigo-100 text-indigo-700 border-indigo-200',
            self::COMPLETED => 'bg-emerald-100 text-emerald-700 border-emerald-200',
            self::CANCELLED => 'bg-rose-100 text-rose-700 border-rose-200',
            self::RETURNED  => 'bg-amber-100 text-amber-700 border-amber-200',
        };
    }

    /**
     * Mendapatkan icon FontAwesome berdasarkan status.
     */
    public function icon(): string
    {
        return match($this) {
            self::UNPAID    => 'fas fa-clock',
            self::PACKING   => 'fas fa-box',
            self::SHIPPED   => 'fas fa-truck',
            self::COMPLETED => 'fas fa-check-circle',
            self::CANCELLED => 'fas fa-times-circle',
            self::RETURNED  => 'fas fa-undo',
        };
    }
}
