<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = ['transaction_id','payment_method','user_id','amount','response'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
