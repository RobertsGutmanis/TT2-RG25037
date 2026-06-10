<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status', 32)->default('pending')->after('user_id');
            $table->decimal('total', 10, 2)->default(0)->after('sum');
            $table->date('delivered_at')->nullable()->change();
        });

        Schema::table('orders_items', function (Blueprint $table) {
            $table->integer('quantity')->default(1)->after('product_id');
            $table->decimal('price', 10, 2)->default(0)->after('quantity');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['status', 'total']);
            $table->date('delivered_at')->nullable(false)->change();
        });

        Schema::table('orders_items', function (Blueprint $table) {
            $table->dropColumn(['quantity', 'price']);
        });
    }
};
