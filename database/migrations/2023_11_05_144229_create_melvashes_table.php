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
        Schema::create('melvashes', function (Blueprint $table) {
            $table->id();
            $table->string('mother_name');
            $table->string('birth_month');
            $table->string('gender');
            $table->string('time_type');
            $table->string('from');
            $table->string('to')->nullable();
            $table->string('talea');
            $table->string('first_melvashe_name');
            $table->string('second_melvashe_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('melvashes');
    }
};
