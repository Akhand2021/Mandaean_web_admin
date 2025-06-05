<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventReminder extends Model
{
    use HasFactory;

    protected $table = 'event_reminders';

    protected $fillable = ['user_id','date_type','event_type','set_before_reminder'];
}
