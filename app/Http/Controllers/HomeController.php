<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Repositories\Contracts\ProductRepositoryInterface;

class HomeController extends Controller
{
    public function __construct(private readonly ProductRepositoryInterface $products)
    {
    }

    public function index()
    {
        return view('frontend.home', [
            'banners' => Banner::where('status', true)->orderBy('sort_order')->get(),
            'categories' => Category::where('status', true)->latest()->limit(8)->get(),
            'featuredProducts' => $this->products->featured(),
            'newArrivals' => $this->products->newArrivals(),
        ]);
    }
}
