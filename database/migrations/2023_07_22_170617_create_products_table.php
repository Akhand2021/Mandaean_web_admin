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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->nullable();
            $table->string('sku');
            $table->double('price', 10, 4);
            $table->integer('inventory')->nullable();
            $table->json('color_ids')->nullable();
            $table->json('size_ids')->nullable();
            $table->json('sizeprice')->nullable();
            $table->integer('brand_id')->nullable();
            $table->string('material')->nullable();
            $table->string('condition')->nullable();
            $table->enum('status',['active','inactive','deleted'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};