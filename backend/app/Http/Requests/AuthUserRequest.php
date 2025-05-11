<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthUserRequest extends FormRequest
{
    /**
     * Xác định xem user có được phép thực hiện request này không
     */
    public function authorize(): bool
    {
        return true; // Cho phép tất cả users
    }

    /**
     * Rules validation cho đăng nhập
     */
    public function rules(): array
    {
        return [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ];
    }
}