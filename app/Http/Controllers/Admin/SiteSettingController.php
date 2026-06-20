<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class SiteSettingController extends Controller
{
    public function edit()
    {
        return view('admin.settings.edit', ['settings' => SiteSetting::allAsArray()]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'website_title' => ['nullable', 'string', 'max:255'],
            'store_name' => ['nullable', 'string', 'max:255'],
            'website_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'website_favicon' => ['nullable', 'file', 'mimes:ico,png,jpg,jpeg,svg', 'max:1024'],
            'delete_website_logo' => ['nullable', 'boolean'],
            'delete_website_favicon' => ['nullable', 'boolean'],
            'store_email' => ['required', 'email', 'max:255'],
            'store_phone' => ['nullable', 'string', 'max:50'],
            'store_address' => ['nullable', 'string', 'max:1000'],
            'currency_symbol' => ['required', 'string', 'max:10'],
        ]);

        unset($data['website_logo'], $data['website_favicon'], $data['delete_website_logo'], $data['delete_website_favicon']);

        foreach (['website_logo', 'website_favicon'] as $fileKey) {
            $oldPath = SiteSetting::getValue($fileKey);

            if ($request->boolean('delete_'.$fileKey) && ! $request->hasFile($fileKey)) {
                $this->deleteUploadedSettingFile($oldPath);
                $data[$fileKey] = null;

                continue;
            }

            if (! $request->hasFile($fileKey)) {
                continue;
            }

            $storedPath = $request->file($fileKey)->store('settings', 'public');

            if (! is_string($storedPath)) {
                throw ValidationException::withMessages([
                    $fileKey => 'The file could not be saved. Check the server storage permissions.',
                ]);
            }

            $data[$fileKey] = $storedPath;
            $this->deleteUploadedSettingFile($oldPath);
        }

        foreach ($data as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => filled($value) ? $value : null]);
        }

        return back()->with('success', 'Website settings saved.');
    }

    private function deleteUploadedSettingFile(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
