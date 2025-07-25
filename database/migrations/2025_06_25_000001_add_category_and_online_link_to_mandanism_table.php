<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mandanism', function (Blueprint $table) {
            $table->string('category')->nullable()->after('title');
            $table->string('online_link')->nullable()->after('pe_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mandanism', function (Blueprint $table) {
            $table->dropColumn(['category', 'online_link']);
        });
    }
};
