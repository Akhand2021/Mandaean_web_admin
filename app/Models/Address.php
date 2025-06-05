<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $table = 'user_addresses';

    protected $fillable = ['user_id','name','code','mobile_no','first_address','second_address','state','city','postal_code','is_primary'];
}
