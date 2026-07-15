<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_code')->unique();
            $table->string('company_name');
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->decimal('balance', 12, 2)->default(0);
            $table->string('status')->default('active');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['status', 'company_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
