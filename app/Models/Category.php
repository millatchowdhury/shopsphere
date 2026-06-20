<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'image', 'status'];

    protected function casts(): array
    {
        return ['status' => 'boolean'];
    }

    public function getImageAttribute(?string $value): ?string
    {
        if (! $value || Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }

        $path = Str::startsWith($value, '/storage/')
            ? Str::after($value, '/storage/')
            : $value;

        if (Str::startsWith($path, 'categories/') && Storage::disk('public')->exists($path)) {
            return route('category-images.show', ['path' => $path]);
        }

        return Storage::disk('public')->url($path);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
