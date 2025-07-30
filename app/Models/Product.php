<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class Product extends Model
{
    use HasFactory, HasJsonRelationships;

    protected $table = 'products';

    protected $guarded = [];

    protected $fillable = ['status'];

    protected $casts = [
        'color_ids' => 'json',
        'size_ids' => 'json',
        'sizeprice' => 'json'
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    public function colors()
    {
        return $this->belongsToJson(Color::class, 'color_ids');
    }

    public function sizes()
    {
        return $this->belongsToJson(Size::class, 'size_ids');
    }

    public function brands()
    {
        return $this->hasOne(Brand::class, 'id' ,'brand_id');
    }
}
