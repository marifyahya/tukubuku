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

    /**
     * @inheritDoc
     */
    public function getUserOrders(int $userId, array $filters): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Order::where('user_id', $userId)
            ->with(['orderItems.book', 'latestPayment'])
            ->latest()
            ->when($filters['status'] ?? null, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($filters['search'] ?? null, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                        ->orWhereHas('orderItems.book', function ($bq) use ($search) {
                            $bq->where('title', 'like', "%{$search}%");
                        });
                });
            })
            ->paginate($filters['per_page'] ?? 10);
    }
}
