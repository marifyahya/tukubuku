<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CancelOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param Order $order
     */
    public function __construct(
        protected Order $order
    ) {
    }

    /**
     * Execute the job.
     *
     * @param OrderService $orderService
     * @return void
     */
    public function handle(OrderService $orderService): void
    {
        Log::info("Executing CancelOrderJob for Order: {$this->order->order_number}");

        $result = $orderService->cancelOrder($this->order);

        if ($result) {
            Log::info("Order {$this->order->order_number} has been automatically cancelled by Job.");
        } else {
            Log::info("Order {$this->order->order_number} was not cancelled (already paid or cancelled).");
        }
    }
}
