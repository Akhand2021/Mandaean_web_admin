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
        Schema::table('prayers', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->enum('prayer_time', ['morning', 'afternoon', 'evening'])->nullable()->after('pe_other_info');
            $table->enum('prayer_type', ['Barkha', 'Reshma', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])->nullable()->after('prayer_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prayers', function (Blueprint $table) {
            $table->dropColumn(['prayer_time', 'prayer_type']);
            $table->enum('type', ['morning', 'afternoon', 'evening', 'Barkha', 'Reshma', 'Monday'])->nullable();
        });
    }
};
