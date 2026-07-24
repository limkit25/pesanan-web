<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add customer_name column
        Schema::table('orders', function (Blueprint $table) {
            $table->string('customer_name')->nullable()->after('user_id');
        });

        // Modifying foreign key constrained columns in SQLite or some MySQL versions might need raw DB statements, 
        // but Laravel schema builder can do it if doctrine/dbal is installed. 
        // Let's use raw statements for MySQL:
        DB::statement('ALTER TABLE orders MODIFY user_id bigint unsigned NULL');
        DB::statement('ALTER TABLE orders MODIFY shipping_address text NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE orders MODIFY user_id bigint unsigned NOT NULL');
        DB::statement('ALTER TABLE orders MODIFY shipping_address text NOT NULL');
        
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('customer_name');
        });
    }
};
