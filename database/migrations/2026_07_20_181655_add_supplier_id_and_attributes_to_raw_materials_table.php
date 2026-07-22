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
        Schema::table('raw_materials', function (Blueprint $table) {
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete()->after('category_id');
            $table->json('attributes')->nullable()->after('name');
            $table->decimal('purchase_price', 12, 2)->default(0)->after('cost_per_unit');
            $table->decimal('average_cost', 12, 2)->default(0)->after('purchase_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('raw_materials', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropColumn(['supplier_id', 'attributes', 'purchase_price', 'average_cost']);
        });
    }
};
