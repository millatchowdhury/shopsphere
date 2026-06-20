<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function before(User $user): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Product $product): bool
    {
        return $product->status;
    }
}
