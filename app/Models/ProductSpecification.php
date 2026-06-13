<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSpecification extends Model
{
    public $timestamps = false;

    protected $fillable = ['key', 'value', 'product_id'];
}
