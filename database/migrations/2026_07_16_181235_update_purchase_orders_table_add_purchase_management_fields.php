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
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->string('purchase_number')->unique()->after('id');
            $table->date('expected_delivery')->nullable()->after('purchase_date');
            $table->string('invoice_number')->nullable()->after('invoice_no');
            $table->decimal('subtotal', 12, 2)->default(0)->after('invoice_number');
            $table->decimal('discount', 12, 2)->default(0)->after('subtotal');
            $table->decimal('tax', 12, 2)->default(0)->after('discount');
            $table->decimal('tax_amount', 12, 2)->default(0)->after('tax');
            $table->decimal('transport_cost', 12, 2)->default(0)->after('tax_amount');
            $table->decimal('other_cost', 12, 2)->default(0)->after('transport_cost');
            $table->string('payment_status')->default('unpaid')->after('remaining_amount');
            $table->text('notes')->nullable()->after('status');
            $table->foreignId('created_by')->nullable()->constrained('users')->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn([
                'purchase_number',
                'expected_delivery',
                'invoice_number',
                'subtotal',
                'discount',
                'tax',
                'tax_amount',
                'transport_cost',
                'other_cost',
                'payment_status',
                'notes',
                'created_by',
            ]);
        });
    }
};
