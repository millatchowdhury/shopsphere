<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public const STATUSES = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];

    protected $fillable = [
        'order_number',
        'user_id',
        'coupon_id',
        'subtotal',
        'discount',
        'shipping',
        'total',
        'payment_method',
        'payment_status',
        'status',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'shipping' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
