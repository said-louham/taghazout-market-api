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
            $table->decimal('tax', 10, 2)->default(0.0);
            $table->decimal('shipping_cost', 10, 2)->after('tax')->default(0.0);
            $table->decimal('coupon_discount', 10, 2)->after('shipping_cost')->default(0.0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['tax', 'shipping_cost', 'coupon_discount']);

        });
    }
};
