<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Store new review
     */
    public function store(Request $request)
    {
        // Kiểm tra dữ liệu đầu vào
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string', // Đảm bảo body không rỗng
            'rating' => 'required|integer|between:1,5',
            'product_id' => 'required|exists:products,id',
        ]);
    
        // Kiểm tra nếu sản phẩm tồn tại hay không
        $product = Product::find($validated['product_id']);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
    
        // Kiểm tra xem người dùng đã đánh giá sản phẩm chưa
        $review = $this->checkIfUserAlreadyReviewedTheProduct($validated['product_id'], $request->user()->id);
    
        if ($review) {
            return response()->json([
                'error' => 'You have already reviewed this product.'
            ]);
        } else {
            $review = Review::create([
                'product_id' => $validated['product_id'],
                'user_id' => $request->user()->id,
                'title' => $validated['title'],
                'body' => $validated['body'], // Đảm bảo dữ liệu body được lưu
                'rating' => $validated['rating']
            ]);
            return response()->json([
                'message' => 'Your review has been added and will be published soon.',
                'data' => $review // Trả về review đã tạo
            ]);
        }
    }
    
    

    /**
     * Update review
     */
    public function update(Request $request)
{
    $product = Product::find($request->product_id);
    if (!$product) {
        return response()->json(['error' => 'Product not found'], 404);
    }

    // Kiểm tra xem người dùng đã đánh giá sản phẩm chưa
    $review = $this->checkIfUserAlreadyReviewedTheProduct($request->product_id, $request->user()->id);

    if ($review) {
        $review->update([
            'product_id' => $request->product_id,
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'body' => $request->body,
            'rating' => $request->rating,
            'approved' => 0
        ]);
        return response()->json([
            'message' => 'Your review has been updated and will be published soon.'
        ]);
    } else {
        return response()->json([
            'error' => 'Something went wrong, try again later.'
        ]);
    }
}

public function delete(Request $request, $reviewId)
{
    // Find the review by ID
    $review = Review::find($reviewId);
    
    // If the review is not found, return a 404 response
    if (!$review) {
        return response()->json(['error' => 'Review not found'], 404);
    }

    // Check if the user is authorized to delete the review (optional but recommended)
    if ($review->user_id !== $request->user()->id) {
        return response()->json(['error' => 'You are not authorized to delete this review'], 403);
    }

    // Delete the review
    $review->delete();

    return response()->json(['message' => 'Your review has been deleted successfully.']);
}

    /**
     * Check if the user has already reviewed the product
     */
    public function checkIfUserAlreadyReviewedTheProduct($productId,$userId)
    {
        $review = Review::where([
            'product_id' => $productId,
            'user_id' => $userId
        ])->first();

        return $review;
    }
}