<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    // app/Http/Controllers/Api/CouponController.php

    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'total_amount' => 'required|numeric'
        ]);

        $coupon = Coupon::where('code', $request->code)
            ->active()
            ->first();

        if (!$coupon) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn'
            ], 404);
        }
        // Kiểm tra số lần sử dụng của user (kể cả đơn hàng đang pending)
    $userId = auth('sanctum')->id();
    $usageCount = Order::where('user_id', $userId)
        ->where('coupon_id', $coupon->id)
        ->count();

    // Kiểm tra max_use_per_user (nếu > 0)
    if ($coupon->max_use_per_user > 0 && $usageCount >= $coupon->max_use_per_user) {
        return response()->json([
            'status' => 'error',
            'message' => 'Bạn đã sử dụng mã này đủ số lần ('.$usageCount.'/'.$coupon->max_use_per_user.')'
        ], 400);
    }
        // Kiểm tra số lượng coupon còn lại
        if ($coupon->quantity <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mã giảm giá đã hết lượt sử dụng'
            ], 400);
        }

        // Kiểm tra ngày hiệu lực
        if ($coupon->start_date > now() || $coupon->end_date < now()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mã giảm giá chưa/nhưng hết hiệu lực'
            ], 400);
        }

        // Kiểm tra giá trị đơn hàng tối thiểu
        if ($request->total_amount < $coupon->min_order_amount) {
            return response()->json([
                'status' => 'error',
                'message' => 'Giá trị đơn hàng tối thiểu: ' . number_format($coupon->min_order_amount) . 'đ'
            ], 400);
        }

        // Tính toán giảm giá
        $discount = $coupon->discount_type === 'fixed'
            ? $coupon->discount_value
            : ($request->total_amount * $coupon->discount_value / 100);

        return response()->json([
            'status' => 'success',
            'coupon' => $coupon,
            'discount_amount' => $discount,
          'current_usage' => $usageCount // Trả về số lần đã dùng
        ]);
    }
    public function checkUserUsage(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|exists:coupons,code'
        ]);
    
        $coupon = Coupon::where('code', $request->coupon_code)->first();
        $userId = auth('sanctum')->id();
    
        // Đếm số lần sử dụng bằng cả coupon_id và coupon_code để đảm bảo chính xác
        $usageCount = Order::where('user_id', $userId)
            ->where(function($query) use ($coupon) {
                $query->where('coupon_id', $coupon->id)
                      ->orWhere('coupon_code', $coupon->code);
            })
            ->count();
    
        return response()->json([
            'status' => 'success',
            'usage_count' => $usageCount,
            'max_use_per_user' => $coupon->max_use_per_user,
            'coupon_code' => $coupon->code
        ]);
    }
}
