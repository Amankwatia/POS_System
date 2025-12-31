<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Performance Optimization Indexes
 *
 * This migration adds database indexes to optimize frequently queried columns
 * and improve overall application performance.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Orders table indexes
        Schema::table('orders', function (Blueprint $table) {
            // Composite index for user orders filtering and date range queries
            if (!$this->indexExists('orders', 'orders_user_created_at_idx')) {
                $table->index(['user_id', 'created_at'], 'orders_user_created_at_idx');
            }

            // Index for status filtering (pending/completed/cancelled)
            if (!$this->indexExists('orders', 'orders_status_idx')) {
                $table->index('status', 'orders_status_idx');
            }

            // Composite index for dashboard queries (status + date)
            if (!$this->indexExists('orders', 'orders_status_created_at_idx')) {
                $table->index(['status', 'created_at'], 'orders_status_created_at_idx');
            }
        });

        // Payments table indexes
        Schema::table('payments', function (Blueprint $table) {
            // Composite index for payment status and date queries
            if (!$this->indexExists('payments', 'payments_status_created_at_idx')) {
                $table->index(['status', 'created_at'], 'payments_status_created_at_idx');
            }

            // Index for order lookup
            if (!$this->indexExists('payments', 'payments_order_id_idx')) {
                $table->index('order_id', 'payments_order_id_idx');
            }

            // Index for sum queries filtered by status
            if (!$this->indexExists('payments', 'payments_status_amount_idx')) {
                $table->index(['status', 'amount'], 'payments_status_amount_idx');
            }
        });

        // Products table indexes
        Schema::table('products', function (Blueprint $table) {
            // Composite index for available products (active + in stock)
            if (!$this->indexExists('products', 'products_active_stock_idx')) {
                $table->index(['is_active', 'stock'], 'products_active_stock_idx');
            }

            // Index for low stock queries
            if (!$this->indexExists('products', 'products_stock_reorder_idx')) {
                $table->index(['stock', 'reorder_level'], 'products_stock_reorder_idx');
            }
        });

        // Order items table indexes
        Schema::table('order_items', function (Blueprint $table) {
            // Index for product sales aggregation
            if (!$this->indexExists('order_items', 'order_items_product_id_idx')) {
                $table->index('product_id', 'order_items_product_id_idx');
            }

            // Composite index for order lookup with product
            if (!$this->indexExists('order_items', 'order_items_order_product_idx')) {
                $table->index(['order_id', 'product_id'], 'order_items_order_product_idx');
            }
        });

        // Users table indexes
        Schema::table('users', function (Blueprint $table) {
            // Index for recent users query
            if (!$this->indexExists('users', 'users_created_at_idx')) {
                $table->index('created_at', 'users_created_at_idx');
            }
        });

        // Role user pivot table
        Schema::table('role_user', function (Blueprint $table) {
            // Composite index for role lookups
            if (!$this->indexExists('role_user', 'role_user_user_role_idx')) {
                $table->index(['user_id', 'role_id'], 'role_user_user_role_idx');
            }
        });
    }

    /**
     * Check if an index exists on a table.
     */
    private function indexExists(string $table, string $indexName): bool
    {
        $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]);
        return count($indexes) > 0;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if ($this->indexExists('orders', 'orders_user_created_at_idx')) {
                $table->dropIndex('orders_user_created_at_idx');
            }
            if ($this->indexExists('orders', 'orders_status_idx')) {
                $table->dropIndex('orders_status_idx');
            }
            if ($this->indexExists('orders', 'orders_status_created_at_idx')) {
                $table->dropIndex('orders_status_created_at_idx');
            }
        });

        Schema::table('payments', function (Blueprint $table) {
            if ($this->indexExists('payments', 'payments_status_created_at_idx')) {
                $table->dropIndex('payments_status_created_at_idx');
            }
            if ($this->indexExists('payments', 'payments_order_id_idx')) {
                $table->dropIndex('payments_order_id_idx');
            }
            if ($this->indexExists('payments', 'payments_status_amount_idx')) {
                $table->dropIndex('payments_status_amount_idx');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if ($this->indexExists('products', 'products_active_stock_idx')) {
                $table->dropIndex('products_active_stock_idx');
            }
            if ($this->indexExists('products', 'products_stock_reorder_idx')) {
                $table->dropIndex('products_stock_reorder_idx');
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            if ($this->indexExists('order_items', 'order_items_product_id_idx')) {
                $table->dropIndex('order_items_product_id_idx');
            }
            if ($this->indexExists('order_items', 'order_items_order_product_idx')) {
                $table->dropIndex('order_items_order_product_idx');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if ($this->indexExists('users', 'users_created_at_idx')) {
                $table->dropIndex('users_created_at_idx');
            }
        });

        Schema::table('role_user', function (Blueprint $table) {
            if ($this->indexExists('role_user', 'role_user_user_role_idx')) {
                $table->dropIndex('role_user_user_role_idx');
            }
        });
    }
};
