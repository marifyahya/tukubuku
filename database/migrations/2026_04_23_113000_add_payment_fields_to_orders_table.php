<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('shipping_address')->nullable()->after('user_id');
            $table->decimal('shipping_cost', 10, 2)->default(16000)->after('total_amount');
            $table->string('payment_token')->nullable()->after('status');
            $table->string('payment_status')->default('pending')->after('payment_token');
            $table->string('payment_method')->nullable()->after('payment_status');
            $table->timestamp('paid_at')->nullable()->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_address',
                'shipping_cost',
                'payment_token',
                'payment_status',
                'payment_method',
                'paid_at',
            ]);
        });
    }
};
