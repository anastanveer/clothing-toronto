<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CouponRequest;
use App\Models\Coupon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CouponController extends Controller
{
    public function index(): View
    {
        $coupons = Coupon::query()
            ->latest()
            ->paginate(12);

        return view('admin.coupons.index', [
            'coupons' => $coupons,
        ]);
    }

    public function create(): View
    {
        return view('admin.coupons.create', [
            'coupon' => new Coupon(),
        ]);
    }

    public function store(CouponRequest $request): RedirectResponse
    {
        Coupon::create($request->validated());

        return redirect()
            ->route('admin.coupons.index')
            ->with('status', 'Coupon created successfully.');
    }

    public function edit(Coupon $coupon): View
    {
        return view('admin.coupons.edit', [
            'coupon' => $coupon,
        ]);
    }

    public function update(CouponRequest $request, Coupon $coupon): RedirectResponse
    {
        $coupon->update($request->validated());

        return redirect()
            ->route('admin.coupons.index')
            ->with('status', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon): RedirectResponse
    {
        $coupon->delete();

        return redirect()
            ->route('admin.coupons.index')
            ->with('status', 'Coupon removed.');
    }
}
