<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Danh sách đơn hàng
    public function index()
    {
        $orders = Order::with(['user', 'products'])
            ->latest()
            ->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    // Chi tiết đơn hàng
    public function show(Order $order)
    {
        $order->load(['user', 'products', 'transactions']);
        return view('admin.orders.show', compact('order'));
    }

    // Cập nhật trạng thái đơn hàng
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }

    // Cập nhật trạng thái thanh toán
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,completed,failed',
        ]);

        $order->update(['payment_status' => $request->payment_status]);

        return back()->with('success', 'Cập nhật trạng thái thanh toán thành công!');
    }

    // Cập nhật ghi chú
    public function updateNotes(Request $request, Order $order)
    {
        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $order->update(['notes' => $request->notes]);

        return back()->with('success', 'Cập nhật ghi chú thành công!');
    }
    
}