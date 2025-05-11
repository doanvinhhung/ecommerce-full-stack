<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    private const VALIDATION_RULES = [
        'coupon_code' => 'nullable|string|exists:coupons,code',
        'billing' => 'required|array',
        'billing.name' => 'required|string|max:255',
        'billing.email' => 'required|email|max:255',
        'billing.phone' => 'required|string|max:20',
        'billing.address' => 'required|string|max:500',
        'billing.city' => 'required|string|max:255',
        'billing.zip' => 'required|string|max:20',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.image' => 'nullable|string', // Thêm rule cho image
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.price' => 'required|numeric|min:0',
         'items.*.color' => 'nullable|string|max:50',
    'items.*.size' => 'nullable|string|max:20',
        'subtotal' => 'required|numeric|min:0',
        'shipping' => 'nullable|numeric|min:0',
        'discount' => 'required|numeric|min:0',
        'total' => 'required|numeric|min:0',
        
    ];

public function createCODOrder(Request $request)
{
    DB::beginTransaction();

    try {
        $validatedData = $request->validate(self::VALIDATION_RULES);

        // Tạo đơn hàng (coupon có thể null)
        $order = $this->createOrder($validatedData, Order::PAYMENT_METHOD_COD);

        // Giảm số lượng sản phẩm
        $this->reduceProductQuantities($validatedData['items']);

        // Xử lý coupon nếu có
        if (!empty($validatedData['coupon_code'])) {
            $this->processCoupon($validatedData['coupon_code'], $order);
        }

        // Tạo các bản ghi order_product
        $this->createOrderProducts($order, $validatedData['items']);

        // Tạo giao dịch
        $transaction = $this->createTransaction($order);

        DB::commit();

        return response()->json([
            'status' => 'success',
            'order' => $order->load('products', 'transactions'),
            'transaction' => $transaction,
            'message' => 'Đơn hàng COD đã được tạo thành công'
        ]);

    } catch (ValidationException $e) {
        DB::rollBack();
        return response()->json([
            'status' => 'error',
            'errors' => $e->errors(),
            'message' => 'Validation failed'
        ], 422);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('COD Order Error: ' . $e->getMessage(), [
            'exception' => $e
        ]);

        return response()->json([
            'status' => 'error',
            'message' => 'Tạo đơn hàng COD thất bại: ' . $e->getMessage()
        ], 500);
    }
}

    private function reduceProductQuantities(array $items): void
{
    foreach ($items as $item) {
        $product = Product::findOrFail($item['product_id']);
        
        if ($product->quantity < $item['quantity']) {
            throw new \Exception("Sản phẩm {$product->name} không đủ số lượng (Còn: {$product->quantity})");
        }
        
        $product->decrement('quantity', $item['quantity']);
    }
}

private function createOrder(array $data, string $paymentMethod): Order
{
    $coupon = null;

    if (!empty($data['coupon_code'])) {
        $coupon = Coupon::where('code', $data['coupon_code'])->first();
    }

    return Order::create([
        'user_id' => auth('sanctum')->id(),
        'order_code' => Order::generateOrderCode(),
        'billing_info' => $data['billing'],
        'coupon_id' => $coupon?->id,
        'coupon_code' => $coupon?->code,
        'subtotal' => $data['subtotal'],
        'shipping_fee' => $data['shipping'] ?? 0,
        'discount' => $data['discount'],
        'total' => $data['total'],
        'payment_method' => $paymentMethod,
        'payment_status' => Order::PAYMENT_STATUS_PENDING,
        'status' => Order::STATUS_PENDING,
        'notes' => $data['billing']['notes'] ?? null,
    ]);
}




    private function createOrderProducts(Order $order, array $items): void
    {
        $orderProducts = [];
        
        foreach ($items as $item) {
            $orderProducts[] = [
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'image' => $item['image'] ?? null,
                'color' => $item['color'] ?? null,
                'size' => $item['size'] ?? null,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
    
        try {
            DB::table('order_product')->insert($orderProducts);
        } catch (\Exception $e) {
            Log::error('Failed to insert order products: '.$e->getMessage());
            throw $e; // Re-throw để rollback transaction
        }
    }

    private function createTransaction(Order $order): Transaction
    {
        return Transaction::create([
            'order_id' => $order->id,
            'transaction_code' => Transaction::generateTransactionCode(),
            'amount' => $order->total,
            'payment_method' => $order->payment_method,
            'status' => Transaction::STATUS_PENDING
        ]);
    }

    public function checkPaymentStatus($orderId)
    {
        try {
            $order = Order::with('transactions')
                        ->where('user_id', auth('sanctum')->id())
                        ->findOrFail($orderId);
            
            return response()->json([
                'status' => $order->payment_status === Order::PAYMENT_STATUS_COMPLETED ? 'success' : 'pending',
                'payment_status' => $order->payment_status,
                'transaction_id' => optional($order->transactions->first())->transaction_id,
                'amount' => $order->total,
                'order' => $order,
                'message' => $order->payment_status === Order::PAYMENT_STATUS_COMPLETED 
                    ? null 
                    : 'Đơn hàng chưa được thanh toán'
            ]);

        } catch (\Exception $e) {
            Log::error('Payment status check failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi kiểm tra trạng thái thanh toán'
            ], 500);
        }
    }
     /**
     * Hiển thị chi tiết đơn hàng
     */
    public function show(Order $order)
    {
        // Kiểm tra đơn hàng thuộc về user hiện tại
        if ($order->user_id !== auth('sanctum')->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'order' => $order->load(['products', 'transactions'])
        ]);
    }

    /**
     * Danh sách đơn hàng của user
     */
    public function userOrders(Request $request)
    {
        $orders = Order::where('user_id', auth('sanctum')->id())
            ->with(['products', 'transactions'])
            ->latest()
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'orders' => $orders
        ]);
    }

    /**
     * Chi tiết đơn hàng của user
     */
    public function userOrderDetail(Order $order)
    {
        // Kiểm tra đơn hàng thuộc về user hiện tại
        if ($order->user_id !== auth('sanctum')->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'order' => $order->load([
                'products', 
                'transactions',
                'coupon'
            ])
        ]);
    }

    /**
     * Kiểm tra trạng thái đơn hàng
     */
    public function checkStatus(Order $order)
    {
        // Kiểm tra đơn hàng thuộc về user hiện tại
        if ($order->user_id !== auth('sanctum')->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'order_status' => $order->status,
            'payment_status' => $order->payment_status,
            'last_updated' => $order->updated_at
        ]);
    }
    // OrderController.php
// app/Http/Controllers/Api/OrderController.php

public function cancel(Order $order)
{
    DB::beginTransaction();
    
    try {
        // 1. Trả lại số lượng sản phẩm
        $this->restoreProductQuantities($order);
        
        // 2. Trả lại coupon (nếu có)
        $this->restoreCoupon($order);
        
        // 3. Cập nhật trạng thái đơn hàng
        $order->update([
            'status' => Order::STATUS_CANCELLED,
            'cancelled_at' => now()
        ]);
        
        DB::commit();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Đơn hàng đã được hủy thành công'
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'status' => 'error',
            'message' => 'Hủy đơn hàng thất bại: ' . $e->getMessage()
        ], 500);
    }
}
    private function restoreProductQuantities(Order $order): void
{
    $orderProducts = $order->products()->withPivot('quantity')->get();

    foreach ($orderProducts as $product) {
        $product->increment('quantity', $product->pivot->quantity);
    }
}


// private function processCoupon(Request $request, Order $order): void
// {
//     if ($request->has('coupon_code') && $request->coupon_code) {
//         $coupon = Coupon::where('code', $request->coupon_code)->first();
        
//         if ($coupon) {
//             // Giảm số lượng coupon
//             $coupon->decrement('quantity');
//             $coupon->increment('total_used');
            
//             // Liên kết coupon với đơn hàng
//             $order->update(['coupon_id' => $coupon->id]);
//         }
//     }
// }
private function processCoupon(string $couponCode, Order $order): void
{
    $coupon = Coupon::where('code', $couponCode)->first();

    if ($coupon) {
        $coupon->decrement('quantity');
        $coupon->increment('total_used');
        $order->update(['coupon_id' => $coupon->id]);
    }
}

// app/Http/Controllers/Api/OrderController.php

private function restoreCoupon(Order $order): void
{
    if ($order->coupon_id) {
        $coupon = Coupon::find($order->coupon_id);
        
        // Chỉ hoàn trả nếu đơn hàng chưa được xử lý
        if ($order->status === Order::STATUS_PENDING) {
            $coupon->increment('quantity');
            $coupon->decrement('total_used');
        }
    }
}
}