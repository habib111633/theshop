<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'billing_name',
        'billing_email',
        'billing_city',
        'billing_state',
        'billing_zip',
        'billing_phone',
        'payment_method',
        'subtotal',
        'tax',
        'shipping',
        'total',
        'user_id',
        'status',
        'shipping_zip',
        'shipping_address',
    ];

    public function orderItems()
    {
        return $this->hasMany(\App\Models\OrderItem::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
