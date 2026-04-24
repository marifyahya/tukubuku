<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:sync-payment-status')]
#[Description('Sync pending payment status with Midtrans API for reliability.')]
class SyncPaymentStatus extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(\App\Services\PaymentService $paymentService)
    {
        $this->info('Starting payment status synchronization...');

        $pendingPayments = \App\Models\OrderPaymentHistory::where('payment_status', \App\Models\OrderPaymentHistory::STATUS_PENDING)
            ->where('created_at', '<=', now()->subMinutes(15))
            ->get();

        $this->info("Found {$pendingPayments->count()} pending payments to check.");

        foreach ($pendingPayments as $payment) {
            $this->info("Checking status for Midtrans Order ID: {$payment->midtrans_order_id}");
            
            if ($paymentService->syncStatus($payment->midtrans_order_id)) {
                $this->info("Successfully synced status for {$payment->midtrans_order_id}");
            } else {
                $this->warn("Failed to sync or no changes for {$payment->midtrans_order_id}");
            }
        }

        $this->info('Payment status synchronization completed.');
    }
}
