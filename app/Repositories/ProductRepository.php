<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function search(array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        return Product::query()
            ->with(['category', 'brand', 'primaryImage'])
            ->where('status', true)
            ->when($filters['query'] ?? null, function ($query, string $term) {
                $query->where(function ($nested) use ($term) {
                    $nested->where('name', 'like', "%{$term}%")
                        ->orWhere('sku', 'like', "%{$term}%")
                        ->orWhere('description', 'like', "%{$term}%");
                });
            })
            ->when($filters['category'] ?? null, fn ($query, string $slug) => $query->whereHas('category', fn ($category) => $category->where('slug', $slug)))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function featured(int $limit = 8): Collection
    {
        return Product::with(['category', 'primaryImage'])->where('status', true)->where('is_featured', true)->latest()->limit($limit)->get();
    }

    public function newArrivals(int $limit = 8): Collection
    {
        return Product::with(['category', 'primaryImage'])->where('status', true)->latest()->limit($limit)->get();
    }
}
