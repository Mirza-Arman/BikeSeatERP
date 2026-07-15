<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_workers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_order_id')->constrained('production_orders')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->restrictOnDelete();
            $table->string('assigned_work')->nullable();
            $table->decimal('completed_quantity', 12, 2)->default(0);
            $table->timestamps();

            $table->index(['production_order_id', 'employee_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_workers');
    }
};
