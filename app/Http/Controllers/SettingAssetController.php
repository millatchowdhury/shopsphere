<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class SettingAssetController extends Controller
{
    public function show(string $path)
    {
        return $this->publicDiskResponse($path, 'settings/');
    }

    public function productImage(string $path)
    {
        return $this->publicDiskResponse($path, 'products/');
    }

    public function categoryImage(string $path)
    {
        return $this->publicDiskResponse($path, 'categories/');
    }

    private function publicDiskResponse(string $path, string $allowedDirectory)
    {
        $path = ltrim($path, '/');

        abort_if(str_contains($path, '..') || ! str_starts_with($path, $allowedDirectory), 404);
        abort_unless(Storage::disk('public')->exists($path), 404);

        return Storage::disk('public')->response($path);
    }
}
