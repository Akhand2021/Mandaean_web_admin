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
        Schema::create('prayers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('subtitle')->nullable();
            $table->text('description');
            $table->text('other_info')->nullable();
            $table->string('docs')->nullable();
            $table->string('ar_title')->nullable();
            $table->text('ar_subtitle')->nullable();
            $table->text('ar_description')->nullable();
            $table->text('ar_other_info')->nullable();
            $table->string('pe_title')->nullable();
            $table->text('pe_subtitle')->nullable();
            $table->text('pe_description')->nullable();
            $table->text('pe_other_info')->nullable();
            $table->enum('status',['active','inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prayers');
    }
};
