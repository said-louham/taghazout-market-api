<?php

use App\Enums\ProductStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->tinyInteger('status')->default(ProductStatusEnum::NORMAL->value);
            $table->dropColumn('featured');
            $table->dropColumn('trending');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->tinyInteger('trending')->default(true);
            $table->tinyInteger('featured')->default(true);
        });
    }
};
