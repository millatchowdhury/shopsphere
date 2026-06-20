<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = ['title', 'subtitle', 'button_text', 'button_link', 'image_path', 'sort_order', 'status'];

    protected function casts(): array
    {
        return ['status' => 'boolean'];
    }
}
