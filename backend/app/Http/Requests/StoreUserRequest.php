<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Xác định xem user có được phép thực hiện request này không
     */
    public function authorize(): bool
    {
        return true; // Cho phép tất cả users
    }

    /**
     * Rules validation cho đăng ký user
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // Yêu cầu xác nhận password
            'password_confirmation' => 'required|string|min:8|same:password'
        ];
    }
    
    public function messages(): array
    {
        return [
            'password.confirmed' => 'Password confirmation does not match.',
            'email.unique' => 'This email is already registered.'
        ];
    }
}