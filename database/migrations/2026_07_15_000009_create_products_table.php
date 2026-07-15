<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_code')->unique();
            $table->string('product_name');
            $table->string('model')->nullable();
            $table->decimal('selling_price', 12, 2)->default(0);
            $table->decimal('minimum_stock', 12, 2)->default(0);
            $table->decimal('current_stock', 12, 2)->default(0);
            $table->string('status')->default('active');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['status', 'product_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
