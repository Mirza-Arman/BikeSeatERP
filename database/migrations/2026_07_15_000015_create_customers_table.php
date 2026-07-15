<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code')->unique();
            $table->string('customer_name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->decimal('balance', 12, 2)->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['customer_name', 'city']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
