<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = ['code', 'type', 'value', 'minimum_order_amount', 'starts_at', 'expires_at', 'status'];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'minimum_order_amount' => 'decimal:2',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'status' => 'boolean',
        ];
    }

    public function isValidFor(float $subtotal): bool
    {
        return $this->status
            && $subtotal >= (float) $this->minimum_order_amount
            && (! $this->starts_at || now()->greaterThanOrEqualTo($this->starts_at))
            && (! $this->expires_at || now()->lessThanOrEqualTo($this->expires_at));
    }

    public function discountFor(float $subtotal): float
    {
        if (! $this->isValidFor($subtotal)) {
            return 0;
        }

        return $this->type === 'percent'
            ? round($subtotal * ((float) $this->value / 100), 2)
            : min((float) $this->value, $subtotal);
    }
}
