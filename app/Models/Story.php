<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Story extends Model
{
    protected $fillable = ['user_id', 'media_url', 'media_type', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(StoryView::class);
    }

    public function viewedBy($user): bool
    {
        return $this->views()->where('user_id', $user->id)->exists();
    }

    public function viewsCount(): int
    {
        return $this->views()->count();
    }

    // ADD THIS MISSING SCOPE METHOD
    public function scopeNotExpired($query)
    {
        return $query->where('expires_at', '>', now());
    }
}
