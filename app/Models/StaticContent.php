<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaticContent extends Model
{
    use HasFactory;

    protected $table = 'static_pages';

    protected $fillable = ['slug', 'title', 'content', 'ar_title', 'ar_content', 'pe_title', 'pe_content'];
}
