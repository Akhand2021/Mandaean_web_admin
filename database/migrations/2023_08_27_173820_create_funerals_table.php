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
        Schema::create('funerals', function (Blueprint $table) {
            $table->id();
            $table->integer('coffin')->nullable();
            $table->integer('coffin_flower')->nullable();
            $table->integer('transfers')->nullable();
            $table->integer('cremation')->nullable();
            $table->string('salutation')->nullable();
            $table->string('name')->nullable();
            $table->string('family_name')->nullable();
            $table->date('dob')->nullable();
            $table->date('dod')->nullable();
            $table->string('register_address')->nullable();
            $table->string('pass_away')->nullable();
            $table->string('body_now')->nullable();
            $table->string('identity')->nullable();
            $table->string('kins_salutation')->nullable();
            $table->string('kins_name')->nullable();
            $table->string('kins_family_name')->nullable();
            $table->string('kins_address')->nullable();
            $table->string('kins_mobile')->nullable();
            $table->string('kins_email')->nullable();
            $table->string('relationship')->nullable();
            $table->string('kins_identity')->nullable();
            $table->string('signature')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funerals');
    }
};
