<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaptismVenue extends Model
{
    use HasFactory;

    protected $table = 'baptism_venue';
    
    protected $fillable = ['name', 'ar_name', 'pe_name', 'status'];
}
