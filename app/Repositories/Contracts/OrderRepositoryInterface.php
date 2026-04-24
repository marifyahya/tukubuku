<?php

namespace App\Repositories\Contracts;

use App\Enums\OrderStatus;
use Illuminate\Support\Collection;

interface OrderRepositoryInterface
{
    /**
     * Get unpaid orders that have exceeded the time limit.
     *
     * @param int $minutes
     * @return Collection
     */
    public function getExpiredUnpaidOrders(int $minutes): Collection;

    /**
     * Update order status.
     *
     * @param int $orderId
     * @param OrderStatus $status
     * @return bool
     */
    public function updateStatus(int $orderId, OrderStatus $status): bool;

    /**
     * Get paginated orders for a user with search and status filters.
     *
     * @param int $userId
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getUserOrders(int $userId, array $filters): \Illuminate\Contracts\Pagination\LengthAwarePaginator;
}
