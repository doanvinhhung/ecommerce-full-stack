<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Đăng ký người dùng mới
     * 
     * @param StoreUserRequest $request Yêu cầu đã được validate
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Lấy thông tin user hiện tại
     */
    public function getCurrentUser(Request $request)
    {
        return response()->json([
            'user' => UserResource::make($request->user())
        ]);
    }

    public function register(StoreUserRequest $request)
    {
        // Tạo user với dữ liệu đã validate
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash password
            'profile_completed' => 0 // Mặc định chưa hoàn thiện profile
        ]);

        // Tạo token ngay sau khi đăng ký (tự động đăng nhập)
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'user' => UserResource::make($user),
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 201); // HTTP 201 Created
    }

    /**
     * Đăng nhập người dùng
     * 
     * @param AuthUserRequest $request Yêu cầu đã được validate
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(AuthUserRequest $request)
    {
        // Tìm user bằng email
        $user = User::where('email', $request->email)->first();

        // Kiểm tra mật khẩu
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'error' => 'These credentials do not match any of our records.'
            ], 401); // HTTP 401 Unauthorized
        }

        // Tạo token mới cho user
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => UserResource::make($user),
            'access_token' => $token,
            'token_type' => 'Bearer',
            'message' => 'Logged in successfully'
        ]);
    }

    /**
     * Đăng xuất người dùng
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Xóa token hiện tại
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Cập nhật thông tin người dùng
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Cập nhật profile
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|numeric|digits_between:1,12',
            'address' => 'sometimes|string|max:255', // Đã sửa từ max:25 lên max:255
            'city' => 'sometimes|string|max:255',
            'country' => 'sometimes|string|max:255',
            'zip' => 'sometimes|string|max:20',
            'profile_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
    
        if ($request->hasFile('profile_image')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($request->user()->profile_image) {
                $oldImagePath = public_path($request->user()->profile_image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
    
            // Lưu ảnh mới
            $file = $request->file('profile_image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('profile_images'), $fileName);
            
            $validated['profile_image'] = '/profile_images/' . $fileName;
        }
    
        $request->user()->update($validated);
    
        return response()->json([
            'user' => new UserResource($request->user()->fresh()),
            'message' => 'Profile updated successfully'
        ]);
    }

    /**
     * Đổi mật khẩu
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect'
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'Password changed successfully'
        ]);
    }
    /**
     * Lấy thông tin user hiện tại
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function currentUser(Request $request)
    {
        return response()->json([
            'user' => UserResource::make($request->user()),
            'access_token' => $request->bearerToken()
        ]);
    }
}
