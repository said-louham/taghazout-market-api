<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->after('payment_mode', static function () use ($table) {
                $table->decimal('tax', 10, 2)->default(0.0);
                $table->decimal('shipping_cost', 10, 2)->default(0.0);
            });
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['tax', 'shipping_cost', 'coupon_discount']);
        });
    }
};
