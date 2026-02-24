<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Check if already nullable (idempotent)
        $column = collect(DB::select("SHOW COLUMNS FROM order_items WHERE Field = 'product_id'"))->first();
        if ($column && str_contains(strtoupper($column->Null ?? ''), 'YES')) {
            return; // Already nullable, skip
        }

        // Use raw SQL for maximum compatibility (no doctrine/dbal needed)
        try {
            // Drop existing foreign key
            $foreignKeys = DB::select("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'order_items' AND COLUMN_NAME = 'product_id' AND REFERENCED_TABLE_NAME IS NOT NULL");
            foreach ($foreignKeys as $fk) {
                DB::statement("ALTER TABLE order_items DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
            }

            // Make column nullable
            DB::statement("ALTER TABLE order_items MODIFY product_id BIGINT UNSIGNED NULL");

            // Re-add foreign key with SET NULL
            DB::statement("ALTER TABLE order_items ADD CONSTRAINT order_items_product_id_foreign FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL");
        } catch (\Exception $e) {
            // Log but don't crash
            logger()->warning('order_items migration warning: ' . $e->getMessage());
        }
    }

    public function down(): void
    {
        try {
            $foreignKeys = DB::select("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'order_items' AND COLUMN_NAME = 'product_id' AND REFERENCED_TABLE_NAME IS NOT NULL");
            foreach ($foreignKeys as $fk) {
                DB::statement("ALTER TABLE order_items DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
            }

            DB::statement("ALTER TABLE order_items MODIFY product_id BIGINT UNSIGNED NOT NULL");

            DB::statement("ALTER TABLE order_items ADD CONSTRAINT order_items_product_id_foreign FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE");
        } catch (\Exception $e) {
            logger()->warning('order_items rollback warning: ' . $e->getMessage());
        }
    }
};
