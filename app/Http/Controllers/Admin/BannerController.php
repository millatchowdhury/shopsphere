<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index() { return view('admin.banners.index', ['banners' => Banner::orderBy('sort_order')->paginate(15)]); }
    public function create() { return view('admin.banners.form', ['banner' => new Banner()]); }
    public function edit(Banner $banner) { return view('admin.banners.form', compact('banner')); }

    public function store(Request $request)
    {
        Banner::create($this->validated($request));
        return redirect()->route('admin.banners.index')->with('success', 'Banner created.');
    }

    public function update(Request $request, Banner $banner)
    {
        $banner->update($this->validated($request));
        return redirect()->route('admin.banners.index')->with('success', 'Banner updated.');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return back()->with('success', 'Banner deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_link' => ['nullable', 'string', 'max:1000'],
            'image_path' => ['required', 'string', 'max:1000'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ]);

        $data['status'] = $request->boolean('status', true);

        return $data;
    }
}
