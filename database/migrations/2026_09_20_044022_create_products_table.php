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
            $table->string('item_code')->unique();
            $table->string('name');
            $table->enum('category', ['machines', 'tools', 'accessories']);
            $table->string('unit')->default('unit');
            $table->integer('quantity')->default(0);
            $table->integer('standard_level')->default(5);
            $table->decimal('unit_cost', 10, 2)->default(0);
            $table->decimal('price', 10, 2);
            $table->decimal('weight', 8, 2)->nullable();
            $table->integer('warranty_months')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};