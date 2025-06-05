<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChooseCalender extends Model
{
    use HasFactory;

    protected $table = 'choose_calenders';

    protected $fillable = ['user_id','first_display','second_display','third_display'];
}
