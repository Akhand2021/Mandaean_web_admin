<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FriendSuggestion extends Model
{
    protected $fillable = ['user_id', 'suggested_friend_id', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function suggestedFriend()
    {
        return $this->belongsTo(User::class, 'suggested_friend_id');
    }
}
