<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_formula_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formula_id')->constrained('production_formulas')->cascadeOnDelete();
            $table->foreignId('raw_material_id')->constrained('raw_materials')->restrictOnDelete();
            $table->decimal('quantity_required', 12, 2)->default(0);
            $table->string('unit')->nullable();
            $table->timestamps();

            $table->index(['formula_id', 'raw_material_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_formula_items');
    }
};
