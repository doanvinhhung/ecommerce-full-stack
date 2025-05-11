@extends('admin.layouts.master')

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Coupons</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href=""><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">All Coupons</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Create Coupon
                </a>
            </div>
        </div>

        {{-- @include('admin.layouts.partials.alert') --}}

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Discount</th>
                                <th>Quantity</th>
                                <th>Validity</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($coupons as $coupon)
                            <tr>
                                <td>{{ $coupon->id }}</td>
                                <td>{{ $coupon->name }}</td>
                                <td><code>{{ $coupon->code }}</code></td>
                                <td>
                                    @if($coupon->discount_type == 'fixed')
                                        {{ number_format($coupon->discount_value) }}đ
                                    @else
                                        {{ $coupon->discount_value }}%
                                    @endif
                                </td>
                                <td>{{ $coupon->quantity }}</td>
                                <td>
                                    {{ $coupon->start_date->format('d/m/Y') }} - 
                                    {{ $coupon->end_date->format('d/m/Y') }}
                                </td>
                            <td>

                                <td>
                                    <div class="form-check form-switch">
                                        <input {{ $coupon->is_active ? 'checked' : '' }}
                                            class="form-check-input toggle-status" 
                                            type="checkbox"
                                            data-id="{{ $coupon->id }}"
                                            role="switch">
                                    </div>
                                </td>
                            
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('admin.coupons.edit', $coupon->id) }}" 
                                            class="btn btn-sm btn-warning me-1">
                                            <i class="bx bxs-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger delete-btn">
                                                <i class="bx bxs-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No coupons found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $coupons->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
$(document).ready(function() {
   

    // Delete button handling remains the same
    $('.delete-btn').click(function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to delete this coupon?')) {
            $(this).closest('form').submit();
        }
    });
});
</script>
@endpush