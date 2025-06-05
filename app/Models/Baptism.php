<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Baptism extends Model
{
    use HasFactory;

    protected $table = 'baptisms';

    protected $fillable = ['user_id','name','last_name','date','email','code','phone','status'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
