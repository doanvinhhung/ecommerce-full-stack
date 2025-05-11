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
                        <li class="breadcrumb-item"><a href="">Coupons</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Coupon</li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- @include('admin.layouts.partials.alert') --}}

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit Coupon</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Coupon Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $coupon->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="code" class="form-label">Coupon Code *</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                   id="code" name="code" value="{{ old('code', $coupon->code) }}" required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="discount_type" class="form-label">Discount Type *</label>
                            <select class="form-select @error('discount_type') is-invalid @enderror" 
                                    id="discount_type" name="discount_type" required>
                                <option value="">Select Type</option>
                                @foreach($discountTypes as $key => $type)
                                    <option value="{{ $key }}" @selected(old('discount_type', $coupon->discount_type) == $key)>{{ $type }}</option>
                                @endforeach
                            </select>
                            @error('discount_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="discount_value" class="form-label">Discount Value *</label>
                            <input type="number" step="0.01" class="form-control @error('discount_value') is-invalid @enderror" 
                                   id="discount_value" name="discount_value" 
                                   value="{{ old('discount_value', $coupon->discount_value) }}" required>
                            @error('discount_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="min_order_amount" class="form-label">Min Order Amount</label>
                            <input type="number" step="0.01" class="form-control @error('min_order_amount') is-invalid @enderror" 
                                   id="min_order_amount" name="min_order_amount" 
                                   value="{{ old('min_order_amount', $coupon->min_order_amount) }}">
                            @error('min_order_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="quantity" class="form-label">Total Quantity *</label>
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                   id="quantity" name="quantity" value="{{ old('quantity', $coupon->quantity) }}" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="max_use_per_user" class="form-label">Max Use Per User *</label>
                            <input type="number" class="form-control @error('max_use_per_user') is-invalid @enderror" 
                                   id="max_use_per_user" name="max_use_per_user" 
                                   value="{{ old('max_use_per_user', $coupon->max_use_per_user) }}" required>
                            @error('max_use_per_user')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="is_active" class="form-label">Status *</label>
                            <select class="form-select @error('is_active') is-invalid @enderror" 
                                    id="is_active" name="is_active" required>
                                <option value="1" @selected(old('is_active', $coupon->is_active) == 1)>Active</option>
                                <option value="0" @selected(old('is_active', $coupon->is_active) == 0)>Inactive</option>
                            </select>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Start Date *</label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                   id="start_date" name="start_date" 
                                   value="{{ old('start_date', $coupon->start_date->format('Y-m-d')) }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">End Date *</label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                   id="end_date" name="end_date" 
                                   value="{{ old('end_date', $coupon->end_date->format('Y-m-d')) }}" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4">Update Coupon</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set min date for end date based on start date
        document.getElementById('start_date').addEventListener('change', function() {
            const startDate = this.value;
            const endDateField = document.getElementById('end_date');
            endDateField.min = startDate;
            
            if (endDateField.value && endDateField.value < startDate) {
                endDateField.value = startDate;
            }
        });
    });
</script>
@endpush