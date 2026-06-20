<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'price',
        'discount_price',
        'stock_quantity',
        'sku',
        'description',
        'is_featured',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'discount_price' => 'decimal:2',
            'is_featured' => 'boolean',
            'status' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getSellingPriceAttribute(): float
    {
        return (float) ($this->discount_price ?: $this->price);
    }

    public function getDisplayImageAttribute(): string
    {
        return $this->primaryImage?->image_path
            ?: $this->images->first()?->image_path
            ?: 'https://images.unsplash.com/photo-1498049794561-7780e7231661?auto=format&fit=crop&w=900&q=80';
    }
}
