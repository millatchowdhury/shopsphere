<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index() { return view('admin.brands.index', ['brands' => Brand::latest()->paginate(15)]); }
    public function create() { return view('admin.brands.form', ['brand' => new Brand()]); }
    public function edit(Brand $brand) { return view('admin.brands.form', compact('brand')); }

    public function store(Request $request)
    {
        Brand::create($this->validated($request));
        return redirect()->route('admin.brands.index')->with('success', 'Brand created.');
    }

    public function update(Request $request, Brand $brand)
    {
        $brand->update($this->validated($request, $brand));
        return redirect()->route('admin.brands.index')->with('success', 'Brand updated.');
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();
        return back()->with('success', 'Brand deleted.');
    }

    private function validated(Request $request, ?Brand $brand = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:brands,slug,'.$brand?->id],
            'logo' => ['nullable', 'string', 'max:1000'],
            'status' => ['nullable', 'boolean'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['status'] = $request->boolean('status', true);

        return $data;
    }
}
