<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecentSearch extends Model
{
    use HasFactory;

    protected $table = 'recent_search';

    protected $fillable = ['user_id','search_text'];
}
