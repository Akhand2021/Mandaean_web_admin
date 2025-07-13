<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prayer extends Model
{
    use HasFactory;

    protected $table = 'prayers';

    protected $guarded = [];

    // Optionally, you can specify fillable fields instead:
    // protected $fillable = [
    //     'title', 'subtitle', 'description', 'other_info', 'docs',
    //     'ar_title', 'ar_subtitle', 'ar_description', 'ar_other_info',
    //     'pe_title', 'pe_subtitle', 'pe_description', 'pe_other_info',
    //     'prayer_time', 'prayer_type', 'prayer_date', 'status'
    // ];
}
