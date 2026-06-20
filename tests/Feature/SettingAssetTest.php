<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SettingAssetTest extends TestCase
{
    public function test_settings_asset_can_be_served_from_public_disk(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('settings/logo.png', 'logo-content');

        $this->get(route('settings.assets.show', ['path' => 'settings/logo.png']))
            ->assertOk();
    }

    public function test_settings_asset_route_does_not_expose_other_public_disk_files(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('private.txt', 'private-content');

        $this->get(route('settings.assets.show', ['path' => 'private.txt']))
            ->assertNotFound();
    }
}
