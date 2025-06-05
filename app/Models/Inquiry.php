<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    protected $table = 'inquiries';

    protected $fillable = ['user_id', 'product_id', 'name', 'email', 'mobile', 'query', 'reply_message', 'status'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
