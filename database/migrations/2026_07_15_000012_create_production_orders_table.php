<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->unique();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->foreignId('formula_id')->nullable()->constrained('production_formulas')->nullOnDelete();
            $table->decimal('quantity_to_produce', 12, 2)->default(0);
            $table->date('production_date');
            $table->string('status')->default('pending');
            $table->text('remarks')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['product_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_orders');
    }
};
