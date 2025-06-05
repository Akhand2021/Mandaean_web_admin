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
        Schema::create('religious_occasions', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->string('year');
            $table->string('date_type');
            $table->string('occasion');
            $table->string('occasion_type')->nullable();
            $table->enum('status',['active','inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('religious_occasions');
    }
};
