<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCodOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'billing' => 'required|array',
            'billing.name' => 'required|string|max:255',
            'billing.email' => 'required|email|max:255',
            'billing.phone' => 'required|string|max:20',
            'billing.address' => 'required|string|max:500',
            'billing.city' => 'required|string|max:255',
            'billing.zip' => 'required|string|max:20',
            'billing.notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'shipping' => 'nullable|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'billing.name.required' => 'The name field is required',
            'items.*.product_id.exists' => 'One or more products are invalid',
            'items.*.quantity.min' => 'Quantity must be at least 1',
        ];
    }
}