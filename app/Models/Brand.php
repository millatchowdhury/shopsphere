<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'logo', 'status'];

    protected function casts(): array
    {
        return ['status' => 'boolean'];
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
