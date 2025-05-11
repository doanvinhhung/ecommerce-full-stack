<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CouponRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:50', Rule::unique('coupons', 'code')->ignore($this->coupon)],
            'discount_type' => 'required|in:fixed,percent',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'min_order_amount' => 'nullable|numeric|min:0',
            'is_active' => 'sometimes|boolean',
            'quantity' => 'required|integer|min:0',
            'max_use_per_user' => 'required|integer|min:1'
        ];

        if ($this->isMethod('POST')) {
            $rules['code'] = 'required|string|max:50|unique:coupons,code';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'The coupon name is required.',
            'code.required' => 'The coupon code is required.',
            'code.unique' => 'This coupon code already exists.',
            'discount_type.required' => 'The discount type is required.',
            'discount_value.required' => 'The discount value is required.',
            'start_date.required' => 'The start date is required.',
            'end_date.required' => 'The end date is required.',
            'quantity.required' => 'The quantity is required.',
            'max_use_per_user.required' => 'The max use per user is required.'
        ];
    }
}