<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Enums\OrderStatus;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Support\Collection;

class OrderRepository implements OrderRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function getExpiredUnpaidOrders(int $minutes): Collection
    {
        return Order::with(['orderItems.book', 'paymentHistories'])
            ->where('status', OrderStatus::UNPAID)
            ->where('created_at', '<=', now()->subMinutes($minutes))
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function updateStatus(int $orderId, OrderStatus $status): bool
    {
        return Order::where('id', $orderId)->update(['status' => $status]);
    }
}
