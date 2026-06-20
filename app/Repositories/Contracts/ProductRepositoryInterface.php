<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    public function search(array $filters = [], int $perPage = 12): LengthAwarePaginator;

    public function featured(int $limit = 8): Collection;

    public function newArrivals(int $limit = 8): Collection;
}
