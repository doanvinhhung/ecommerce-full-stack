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

class ProductController extends Controller
{
    // public function index(Request $request) {
    //     $query = Product::with(['colors', 'sizes', 'category', 'brand'])->latest();
    
    //     // Lọc theo danh mục nếu có tham số category_id
    //     if ($request->has('category_id')) {
    //         $query->where('category_id', $request->category_id);
    //     }
    
    //     return ProductResource::collection($query->get())->additional([
    //         'colors' => Color::has('products')->get(),
    //         'sizes' => Size::has('products')->get(),
    //         'categories' => Category::has('products')->get(),
    //         'brands' => Brand::has('products')->get(),
    //     ]);
    // }
  // Thay đổi các phương thức lọc để hỗ trợ mảng giá trị
  public function index(Request $request) 
  {
      $query = Product::with(['colors', 'sizes', 'category', 'brand']);
      
      // 1. Lọc theo từ khóa tìm kiếm
      if ($request->has('search')) {
          $query->where(function($q) use ($request) {
              $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
          });
      }
      
      // 2. Lọc theo danh mục sản phẩm (nhiều danh mục)
      if ($request->has('category_id')) {
          $query->whereIn('category_id', (array)$request->category_id);
      }
      
      // 3. Lọc theo thương hiệu (nhiều thương hiệu)
      if ($request->has('brand_id')) {
          $query->whereIn('brand_id', (array)$request->brand_id);
      }
  
      // 4. Lọc theo khoảng giá
      if ($request->has('min_price')) {
          $query->where('price', '>=', $request->min_price);
      }
      if ($request->has('max_price')) {
          $query->where('price', '<=', $request->max_price);
      }
  
      // 5. Lọc theo màu sắc (nhiều màu)
      if ($request->has('color_id')) {
          $query->whereHas('colors', function($q) use ($request) {
              $q->whereIn('colors.id', (array)$request->color_id);
          });
      }
      
      // 6. Lọc theo kích thước (nhiều kích thước)
      if ($request->has('size_id')) {
          $query->whereHas('sizes', function($q) use ($request) {
              $q->whereIn('sizes.id', (array)$request->size_id);
          });
      }
      
      // Sắp xếp
      $sort = $request->get('sort', 'newest');
      switch ($sort) {
          case 'price_asc':
              $query->orderBy('price', 'asc');
              break;
          case 'price_desc':
              $query->orderBy('price', 'desc');
              break;
          default:
              $query->latest();
      }
      
      $products = $query->paginate(12);
      
      return ProductResource::collection($products)->additional([
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
