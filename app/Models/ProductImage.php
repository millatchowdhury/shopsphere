<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'image_path', 'is_primary', 'sort_order'];

    protected function casts(): array
    {
        return ['is_primary' => 'boolean'];
    }

    public function getImagePathAttribute(?string $value): ?string
    {
        if (! $value || Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }

        $path = Str::startsWith($value, '/storage/')
            ? Str::after($value, '/storage/')
            : $value;

        if (Str::startsWith($path, 'products/') && Storage::disk('public')->exists($path)) {
            return route('product-images.show', ['path' => $path]);
        }

        return Storage::disk('public')->url($path);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
