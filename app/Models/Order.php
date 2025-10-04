<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'customer_name',
        'total_amount',
        'status'
    ];

    // Relationship: Order belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Main relationship name
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Alias for convenience (so you can use both 'items' and 'orderItems')
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}