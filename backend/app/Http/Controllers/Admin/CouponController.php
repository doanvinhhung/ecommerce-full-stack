<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Http\Requests\CouponRequest;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(10);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        $discountTypes = [
            'fixed' => 'Fixed Amount',
            'percent' => 'Percentage'
        ];
        return view('admin.coupons.create', compact('discountTypes'));
    }

    public function store(CouponRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        
        Coupon::create($data);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon created successfully');
    }

    public function edit(Coupon $coupon)
    {
        $discountTypes = [
            'fixed' => 'Fixed Amount',
            'percent' => 'Percentage'
        ];
        return view('admin.coupons.edit', compact('coupon', 'discountTypes'));
    }

    public function update(CouponRequest $request, Coupon $coupon)
    {
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        
        $coupon->update($data);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon updated successfully');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Coupon deleted successfully'
        ]);
    }


}

