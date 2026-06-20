<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCategoryImageUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_upload_and_replace_category_image(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('admin.categories.store'), [
                'name' => 'Uploaded Category',
                'description' => 'A category created with an uploaded image.',
                'status' => '1',
                'image' => UploadedFile::fake()->image('category.png', 600, 400),
            ])
            ->assertRedirect(route('admin.categories.index'));

        $category = Category::where('slug', 'uploaded-category')->firstOrFail();
        $firstPath = $category->getRawOriginal('image');

        $this->assertStringStartsWith('categories/', $firstPath);
        $this->assertFalse(str_starts_with($firstPath, 'http'));
        Storage::disk('public')->assertExists($firstPath);
        $this->get($category->image)->assertOk();

        $this->actingAs($admin)
            ->put(route('admin.categories.update', $category), [
                'name' => 'Uploaded Category',
                'slug' => 'uploaded-category',
                'description' => 'A category with a replacement image.',
                'status' => '1',
                'image' => UploadedFile::fake()->image('replacement.webp', 800, 500),
            ])
            ->assertRedirect(route('admin.categories.index'));

        $category->refresh();
        $replacementPath = $category->getRawOriginal('image');

        $this->assertNotSame($firstPath, $replacementPath);
        Storage::disk('public')->assertMissing($firstPath);
        Storage::disk('public')->assertExists($replacementPath);
        $this->get($category->image)->assertOk();
    }
}
