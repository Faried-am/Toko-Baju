<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
protected $fillable = [
    'user_id', 'order_number', 'total_price', 'status', 'payment_url',
    'recipient_name', 'phone', 'address', 'city', 'postal_code', 'note',
    'shipping_status', 'courier', 'tracking_number', 'shipped_at', 'completed_at',
];

protected $casts = [
    'shipped_at' => 'datetime',
    'completed_at' => 'datetime',
];    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}