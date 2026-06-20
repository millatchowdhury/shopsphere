<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index() { return view('admin.coupons.index', ['coupons' => Coupon::latest()->paginate(15)]); }
    public function create() { return view('admin.coupons.form', ['coupon' => new Coupon()]); }
    public function edit(Coupon $coupon) { return view('admin.coupons.form', compact('coupon')); }

    public function store(Request $request)
    {
        Coupon::create($this->validated($request));
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created.');
    }

    public function update(Request $request, Coupon $coupon)
    {
        $coupon->update($this->validated($request, $coupon));
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', 'Coupon deleted.');
    }

    private function validated(Request $request, ?Coupon $coupon = null): array
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:coupons,code,'.$coupon?->id],
            'type' => ['required', 'in:fixed,percent'],
            'value' => ['required', 'numeric', 'min:0'],
            'minimum_order_amount' => ['required', 'numeric', 'min:0'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'status' => ['nullable', 'boolean'],
        ]);

        $data['code'] = strtoupper($data['code']);
        $data['status'] = $request->boolean('status', true);

        return $data;
    }
}
