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
            $table->string('order_number')->unique()->after('id');
            
            // Cleanup old payment columns if they exist
            if (Schema::hasColumn('orders', 'payment_token')) {
                $table->dropColumn([
                    'payment_token',
                    'payment_status',
                    'payment_method',
                    'paid_at'
                ]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('order_number');
            
            // Re-add columns if needed (optional since we are refactoring)
            $table->string('payment_token')->nullable();
            $table->string('payment_status')->default('pending');
            $table->string('payment_method')->nullable();
            $table->timestamp('paid_at')->nullable();
        });
    }
};
