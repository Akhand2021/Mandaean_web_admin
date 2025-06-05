<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $table = 'programs';

    protected $fillable = ['title','group','description','image','docs','ar_title','ar_group','ar_description','pe_title','pe_group','pe_description','status'];
}
