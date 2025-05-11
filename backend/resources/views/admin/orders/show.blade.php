@extends('admin.layouts.master')

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Quản lý đơn hàng</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Danh sách đơn hàng</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Chi tiết đơn hàng</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="mb-3">Thông tin đơn hàng</h5>
                        <p><strong>Mã đơn:</strong> {{ $order->order_code }}</p>
                        <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Trạng thái:</strong>
                            <span class="badge bg-{{ $order->status_badge_color }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </p>
                        <p><strong>Thanh toán:</strong>
                            <span class="badge bg-{{ $order->payment_status_badge_color }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </p>
                        <p><strong>Phương thức:</strong> {{ ucfirst($order->payment_method) }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-3">Thông tin khách hàng</h5>
                        <p><strong>Tên:</strong> {{ $order->customer_name }}</p>
                        <p><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
                        <p><strong>Điện thoại:</strong> {{ $order->billing_info['phone'] ?? 'N/A' }}</p>
                        <p><strong>Địa chỉ:</strong> {{ $order->full_address }}</p>
                    </div>
                </div>

                <div class="table-responsive mb-4">
                    <h5 class="mb-3">Sản phẩm</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Tạm tính</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->products as $product)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset($product->pivot->image) }}" width="50" class="me-3">
                                        <div>
                                            <p class="mb-0">{{ $product->name }}</p>
                                            @if($product->pivot->color)
                                                <small class="text-muted">Màu: {{ $product->pivot->color }}</small>
                                            @endif
                                            @if($product->pivot->size)
                                                <small class="text-muted">Size: {{ $product->pivot->size }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ number_format($product->pivot->price, 0, ',', '.') }}₫</td>
                                <td>{{ $product->pivot->quantity }}</td>
                                <td>{{ number_format($product->pivot->price * $product->pivot->quantity, 0, ',', '.') }}₫</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Tổng tiền:</strong></td>
                                <td>{{ $order->formatted_total }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <h5 class="mb-3">Cập nhật trạng thái đơn hàng</h5>
                        <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                            @csrf
                            <div class="input-group mb-3">
                                <select name="status" class="form-select">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Đang giao</option>
                                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Đã giao</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                </select>
                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                            </div>
                        </form>
                    </div>
                
                    <!-- Thêm form cập nhật Payment Status -->
                    <div class="col-md-4">
                        <h5 class="mb-3">Cập nhật thanh toán</h5>
                        <form action="{{ route('admin.orders.update-payment-status', $order) }}" method="POST">
                            @csrf
                            <div class="input-group mb-3">
                                <select name="payment_status" class="form-select">
                                    <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Chưa thanh toán</option>
                                    <option value="completed" {{ $order->payment_status == 'completed' ? 'selected' : '' }}>Đã thanh toán</option>
                                    <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Thanh toán thất bại</option>
                                </select>
                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                            </div>
                        </form>
                    </div>
                
                    <div class="col-md-4">
                        <h5 class="mb-3">Ghi chú đơn hàng</h5>
                        <form action="{{ route('admin.orders.update-notes', $order) }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <textarea name="notes" class="form-control" rows="1">{{ $order->notes }}</textarea>
                                <button type="submit" class="btn btn-primary">Lưu</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection