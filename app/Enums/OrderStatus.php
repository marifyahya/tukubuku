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
     * Mendapatkan warna badge untuk UI (opsional).
     */
    public function color(): string
    {
        return match($this) {
            self::UNPAID    => 'gray',
            self::PACKING   => 'info',
            self::SHIPPED   => 'primary',
            self::COMPLETED => 'success',
            self::CANCELLED => 'danger',
            self::RETURNED  => 'warning',
        };
    }
}
