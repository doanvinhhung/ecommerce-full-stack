<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;

class ApiProductController extends Controller
{
    // public function index() {
    //     return ProductResource::collection(
    //         Product::with(['colors', 'sizes', 'category', 'brand'])
    //             ->latest()
    //             ->get()
    //     )->additional([
    //         'colors' => Color::has('products')->get(),
    //         'sizes' => Size::has('products')->get(),
    //         'categories' => Category::has('products')->get(),
    //         'brands' => Brand::has('products')->get(),
    //     ]);
    // }
    public function index(Request $request) {
        $query = Product::with(['colors', 'sizes', 'category', 'brand'])->latest();
    
        // Lọc theo danh mục nếu có tham số category_id
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
    
        return ProductResource::collection($query->get())->additional([
            'colors' => Color::has('products')->get(),
            'sizes' => Size::has('products')->get(),
            'categories' => Category::has('products')->get(),
            'brands' => Brand::has('products')->get(),
        ]);
    }
    
  
    public function show($slug)
{
    $product = Product::where('slug', $slug)->first();

    if (!$product) {
        abort(404);
    }

    return ProductResource::make(
        $product->load(['colors', 'sizes', 'category', 'reviews', 'brand'])
    );
}

    // public function show(Product $product) {
    //     if (!$product) {
    //         abort(404);
    //     }
    //     return ProductResource::make(
    //      Product::load(['colors', 'sizes','category','reviews', 'brand'])
        
    //     );
    // }

    public function filterProductByCategory(Category $category) {
        return ProductResource::collection(
            $category->products()->with(['colors', 'sizes', 'category', 'brand'])->latest()->get()
        )->additional([
            'colors' => Color::has('products')->get(),
            'sizes' => Size::has('products')->get(),
            'categories' => Category::has('products')->get(),
            'brands' => Brand::has('products')->get(),
            'filter' => $category->name
        ]);
    }
    
    public function filterProductByBrand(Brand $brand) {
        return (ProductResource::collection(
            $brand->products()->with(['colors', 'sizes', 'category', 'brand'])->latest()->get()
        ))->additional([
            'colors' => Color::has('products')->get(),
            'sizes' => Size::has('products')->get(),
            'categories' => Category::has('products')->get(),
            'brands' => Brand::has('products')->get(),
            'filter' => $brand->name
        ]);
        
    }
    public function filterProductByColor(Color $color) {
        return (ProductResource::collection(
            $color->products()->with(['colors', 'sizes','category', 'brand'])->latest()->get()
            ))->additional([
               'colors'=>  Color::has('products')->get(),
               'sizes'=>  Size::has('products')->get(),
               'categories'=>  Category::has('products')->get(),
               'brands'=>  Brand::has('products')->get(),
               'filter'=>$color->name

            ]);
           
    } 
    public function filterProductBySize(Size $size) {
        return (ProductResource::collection(
            $size->products()->with(['colors', 'sizes','category', 'brand'])->latest()->get()
            ))->additional([
               'colors'=>  Color::has('products')->get(),
               'sizes'=>  Size::has('products')->get(),
               'categories'=>  Category::has('products')->get(),
               'brands'=>  Brand::has('products')->get(),
               'filter'=>$size->name

            ]) ;

    }
    public function findProductByTerm($searchTerm) {
        // Kiểm tra và xử lý ký tự đặc biệt trong từ khóa
        $searchTerm = urldecode($searchTerm);  // Giải mã từ khóa nếu cần thiết
        
        return ProductResource::collection(
            Product::where('name', 'LIKE', '%' . $searchTerm . '%')
                ->with(['colors', 'sizes', 'category', 'brand'])
                ->latest()
                ->get()
        )->additional([
            'colors' => Color::has('products')->get(),
            'sizes' => Size::has('products')->get(),
            'categories' => Category::has('products')->get(),
            'brands' => Brand::has('products')->get(),
        ]);
    }
    
}
