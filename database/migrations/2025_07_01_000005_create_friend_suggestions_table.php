<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFriendSuggestionsTable extends Migration
{
    public function up()
    {
        Schema::create('friend_suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('suggested_friend_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'accepted', 'rejected', 'suggested'])->default('suggested');
            $table->timestamps();
            $table->unique(['user_id', 'suggested_friend_id']);
        });
    }
    public function down()
    {
        Schema::dropIfExists('friend_suggestions');
    }
}
