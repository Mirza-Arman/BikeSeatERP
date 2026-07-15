<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_formulas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('version')->default('1.0');
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['product_id', 'version']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_formulas');
    }
};
