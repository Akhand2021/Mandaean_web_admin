<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolyBook extends Model
{
    use HasFactory;

    protected $table = 'holy_books';

    protected $guards = [];
}
