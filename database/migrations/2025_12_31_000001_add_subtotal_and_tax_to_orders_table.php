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
            if (!Schema::hasColumn('orders', 'subtotal')) {
                $table->decimal('subtotal', 12, 2)->default(0)->after('status');
            }
            if (!Schema::hasColumn('orders', 'tax')) {
                $table->decimal('tax', 12, 2)->default(0)->after('subtotal');
            }
        });

        // Update existing orders: set subtotal = total (assuming no tax was applied before)
        \DB::statement('UPDATE orders SET subtotal = total WHERE subtotal = 0');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'tax']);
        });
    }
};
