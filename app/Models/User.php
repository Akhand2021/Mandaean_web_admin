<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'country_code',
        'mobile_no',
        'password',
        'profile',
        'otp_time',
        'dob',
        'gender',
        'last_seen', // add last_seen to fillable
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'mobile_verified_at' => 'datetime',
    ];

    public function messages()
    {
        return $this->hasMany(\App\Models\Message::class, 'sender_id');
    }
    public function posts()
    {
        return $this->hasMany(\App\Models\Post::class);
    }
    public function likes()
    {
        return $this->hasMany(\App\Models\Like::class);
    }
    public function comments()
    {
        return $this->hasMany(\App\Models\Comment::class);
    }
    public function shares()
    {
        return $this->hasMany(\App\Models\Share::class);
    }
    public function friendSuggestionsSent()
    {
        return $this->hasMany(\App\Models\FriendSuggestion::class, 'user_id');
    }
    public function friendSuggestionsReceived()
    {
        return $this->hasMany(\App\Models\FriendSuggestion::class, 'suggested_friend_id');
    }
}
