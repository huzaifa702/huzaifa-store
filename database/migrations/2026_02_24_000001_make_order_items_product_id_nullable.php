<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Skip if order_items table doesn't exist yet
        if (!Schema::hasTable('order_items')) {
            return;
        }

        // Use Laravel Schema builder (works on MySQL, SQLite, PostgreSQL)
        try {
            Schema::table('order_items', function (Blueprint $table) {
                $table->unsignedBigInteger('product_id')->nullable()->change();
            });
        } catch (\Throwable $e) {
            // Log but don't crash — column may already be nullable
            logger()->warning('order_items migration warning: ' . $e->getMessage());
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('order_items')) {
            return;
        }

        try {
            Schema::table('order_items', function (Blueprint $table) {
                $table->unsignedBigInteger('product_id')->nullable(false)->change();
            });
        } catch (\Throwable $e) {
            logger()->warning('order_items rollback warning: ' . $e->getMessage());
        }
    }
};
