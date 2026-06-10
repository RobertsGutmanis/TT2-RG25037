<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'tracking_number',
        'created_at',
        'delivered_at',
        'sum',
        'total',
        'delivery_method',
        'status',
        'user_id',
    ];

    protected $casts = [
        'created_at'  => 'date',
        'delivered_at' => 'date',
        'total'        => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
