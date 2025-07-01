<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Melvashe extends Model
{
    use HasFactory;

    protected $fillable = [
        'mother_name',
        'birth_month',
        'gender',
        'time_type',
        'from',
        'to',
        'talea',
        'first_melvashe_name',
        'second_melvashe_name'
    ];
}
