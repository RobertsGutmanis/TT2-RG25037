<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'manufacturer',
        'description',
        'price',
        'last_price',
        'price_change',
        'image_url',
        'category_id',
    ];

    protected $casts = [
        'price'        => 'double',
        'last_price'   => 'double',
        'price_change' => 'date',
    ];

    // Relationship — a product belongs to a category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}