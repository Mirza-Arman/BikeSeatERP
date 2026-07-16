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
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->decimal('previous_quantity', 12, 2)->default(0)->after('quantity');
            $table->string('supplier_name')->nullable()->after('remarks');
            $table->foreignId('user_id')->nullable()->constrained('users')->after('supplier_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['previous_quantity', 'supplier_name', 'user_id']);
        });
    }
};
