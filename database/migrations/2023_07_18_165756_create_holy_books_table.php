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
        Schema::create('holy_books', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('author')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->text('url')->nullable();
            $table->string('ar_title')->nullable();
            $table->text('ar_description')->nullable();
            $table->string('pe_title')->nullable();
            $table->text('pe_description')->nullable();
            $table->string('other_title')->nullable();
            $table->text('other_description')->nullable();
            $table->string('other_ar_title')->nullable();
            $table->text('other_ar_description')->nullable();
            $table->string('other_pe_title')->nullable();
            $table->text('other_pe_description')->nullable();
            $table->string('other_image')->nullable();
            $table->text('other_url')->nullable();
            $table->enum('status',['active','inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holy_books');
    }
};
