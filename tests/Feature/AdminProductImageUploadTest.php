<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProductImageUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_upload_product_images(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();
        $brand = Brand::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.products.store'), [
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'name' => 'Uploaded Product',
                'price' => 49.99,
                'stock_quantity' => 8,
                'sku' => 'UPL-001',
                'description' => 'A product created with an uploaded image.',
                'status' => '1',
                'images' => [
                    UploadedFile::fake()->image('product.png', 600, 600),
                ],
            ])
            ->assertRedirect(route('admin.products.index'));

        $product = Product::where('sku', 'UPL-001')->firstOrFail();
        $image = ProductImage::where('product_id', $product->id)->firstOrFail();
        $path = $image->getRawOriginal('image_path');

        $this->assertStringStartsWith('products/', $path);
        $this->assertFalse(str_starts_with($path, 'http'));
        Storage::disk('public')->assertExists($path);
        $this->get($image->image_path)->assertOk();
    }
}
