<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EcommerceSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Store Admin', 'password' => Hash::make('password'), 'role' => 'admin']
        );

        User::updateOrCreate(
            ['email' => 'customer@example.com'],
            ['name' => 'Demo Customer', 'password' => Hash::make('password'), 'role' => 'customer', 'phone' => '+1 555 0100', 'address' => '100 Commerce Street']
        );

        $categoryImages = [
            'Electronics' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?auto=format&fit=crop&w=900&q=80',
            'Fashion' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=900&q=80',
            'Home Living' => 'https://images.unsplash.com/photo-1513161455079-7dc1de15ef3e?auto=format&fit=crop&w=900&q=80',
            'Accessories' => 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&w=900&q=80',
        ];

        $categories = collect($categoryImages)->map(function (string $image, string $name) {
            return Category::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'description' => "Curated {$name} products.", 'image' => $image, 'status' => true]
            );
        });

        $brands = collect(['Northline', 'Urban Craft', 'Aster Goods', 'Bright Home'])->map(function (string $name) {
            return Brand::updateOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'status' => true]);
        });

        $products = [
            ['Wireless Headphones', 'Electronics', 'Northline', 129.99, 99.99, 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=900&q=80'],
            ['Smart Desk Lamp', 'Home Living', 'Bright Home', 74.00, null, 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?auto=format&fit=crop&w=900&q=80'],
            ['Everyday Backpack', 'Fashion', 'Urban Craft', 89.50, 69.50, 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=900&q=80'],
            ['Minimal Sunglasses', 'Accessories', 'Aster Goods', 45.00, null, 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&w=900&q=80'],
            ['Bluetooth Speaker', 'Electronics', 'Northline', 59.99, 49.99, 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?auto=format&fit=crop&w=900&q=80'],
            ['Ceramic Coffee Set', 'Home Living', 'Bright Home', 39.99, null, 'https://images.unsplash.com/photo-1514228742587-6b1558fcca3d?auto=format&fit=crop&w=900&q=80'],
            ['Cotton Overshirt', 'Fashion', 'Urban Craft', 64.00, null, 'https://images.unsplash.com/photo-1520975954732-35dd22299614?auto=format&fit=crop&w=900&q=80'],
            ['Leather Card Holder', 'Accessories', 'Aster Goods', 32.00, 24.00, 'https://images.unsplash.com/photo-1627123424574-724758594e93?auto=format&fit=crop&w=900&q=80'],
        ];

        foreach ($products as $index => [$name, $category, $brand, $price, $discount, $image]) {
            $product = Product::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'category_id' => $categories[$category]->id,
                    'brand_id' => $brands->firstWhere('name', $brand)->id,
                    'name' => $name,
                    'price' => $price,
                    'discount_price' => $discount,
                    'stock_quantity' => 25 + $index,
                    'sku' => 'DEMO-'.str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT),
                    'description' => 'A carefully selected product with reliable quality, strong presentation, and simple checkout support.',
                    'is_featured' => $index < 4,
                    'status' => true,
                ]
            );

            ProductImage::updateOrCreate(
                ['product_id' => $product->id, 'sort_order' => 0],
                ['image_path' => $image, 'is_primary' => true]
            );
        }

        Banner::updateOrCreate(
            ['title' => 'Build Your Better Everyday'],
            [
                'subtitle' => 'Shop electronics, fashion, home goods, and accessories with Cash on Delivery.',
                'button_text' => 'Shop Collection',
                'button_link' => '/products',
                'image_path' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=1800&q=80',
                'sort_order' => 1,
                'status' => true,
            ]
        );

        Coupon::updateOrCreate(
            ['code' => 'WELCOME10'],
            ['type' => 'percent', 'value' => 10, 'minimum_order_amount' => 50, 'status' => true]
        );

        foreach ([
            'website_title' => 'ShopSphere',
            'store_name' => 'ShopSphere',
            'store_email' => 'support@shopsphere.test',
            'store_phone' => '+1 555 0100',
            'store_address' => '100 Commerce Street',
            'currency_symbol' => '$',
        ] as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
