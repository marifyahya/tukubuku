<?php

namespace App\Console\Commands;

use App\Services\OrderService;
use Illuminate\Console\Command;

class AutoCancelOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:auto-cancel {minutes=30 : The number of minutes after which an unpaid order is considered expired}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically cancel unpaid orders that have exceeded the time limit and restore book stock.';

    /**
     * Create a new command instance.
     *
     * @param OrderService $orderService
     */
    public function __construct(
        private OrderService $orderService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $minutes = (int) $this->argument('minutes');

        $this->info("Checking for unpaid orders older than {$minutes} minutes...");

        $cancelledOrders = $this->orderService->cancelExpiredOrders($minutes);

        if (empty($cancelledOrders)) {
            $this->info('No expired orders found.');
            return self::SUCCESS;
        }

        $this->info('Successfully cancelled ' . count($cancelledOrders) . ' order(s):');
        foreach ($cancelledOrders as $orderNumber) {
            $this->line(" - {$orderNumber}");
        }

        return self::SUCCESS;
    }
}
