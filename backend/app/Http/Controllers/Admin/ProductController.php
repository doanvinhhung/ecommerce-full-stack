<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Size;
use App\Models\Color;
use App\Http\Requests\AddProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.products.index')->with([
            'products' => Product::with(['colors', 'sizes','category', 'brand'])->latest()->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $sizes = Size::all();
        $colors = Color::all();
        return view('admin.products.create', compact('categories', 'brands', 'sizes', 'colors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddProductRequest $request)
    {
        if ($request->validated()) {
            $data = $request->validated();
            $data['slug'] = Str::slug($request->name);
    
            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = $this->saveImage($request->file('thumbnail'));
            }
    
            if ($request->hasFile('first_image')) {
                $data['first_image'] = $this->saveImage($request->file('first_image'));
            }
            if ($request->hasFile('second_image')) {
                $data['second_image'] = $this->saveImage($request->file('second_image'));
            }
            if ($request->hasFile('third_image')) {
                $data['third_image'] = $this->saveImage($request->file('third_image'));
            }
    
            // Create the product
            $product = Product::create($data);
    
            // Sync the selected colors and sizes with the product
            $product->colors()->sync($request->color_id);  // Sync multiple colors
            $product->sizes()->sync($request->size_id);  // Sync multiple sizes
    
            return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
        }
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        $sizes = Size::all();
        $colors = Color::all();
        return view('admin.products.edit', compact('product', 'categories', 'brands', 'sizes', 'colors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        if ($request->validated()) {
            $data = $request->validated();
            $data['slug'] = Str::slug($request->name);
            $data['status'] = $request->status;
    
            if ($request->hasFile('thumbnail')) {
                $this->removeProductImageFromStorage($product->thumbnail);
                $data['thumbnail'] = $this->saveImage($request->file('thumbnail'));
            }
    
            if ($request->hasFile('first_image')) {
                $this->removeProductImageFromStorage($product->first_image);
                $data['first_image'] = $this->saveImage($request->file('first_image'));
            }
            if ($request->hasFile('second_image')) {
                $this->removeProductImageFromStorage($product->second_image);
                $data['second_image'] = $this->saveImage($request->file('second_image'));
            }
            if ($request->hasFile('third_image')) {
                $this->removeProductImageFromStorage($product->third_image);
                $data['third_image'] = $this->saveImage($request->file('third_image'));
            }
    
            // Update the product
            $product->update($data);
    
            // Sync the selected colors and sizes with the product
            $product->colors()->sync($request->color_id);  // Sync multiple colors
            $product->sizes()->sync($request->size_id);  // Sync multiple sizes
    
            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
        }
    }
    

    // public function saveImage($file)
    // {
    //     $image_name = time() . '.' . $file->getClientOriginalExtension();
    //     $file->storeAs('images/products', $image_name, 'public');
    //     return 'storage/images/products/' . $image_name;
    // }

    public function saveImage($file)
    {
        if (!$file) {
            return null; // Trả về null nếu không có file
        }

        $image_name = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        // Di chuyển file vào thư mục public/images/products/
        $file->move(public_path('images/products'), $image_name);

        return 'images/products/' . $image_name; // Trả về đường dẫn ảnh
    }


    public function removeProductImageFromStorage($file)
    {
        if ($file) {
            $path = public_path($file);
            if (File::exists($path)) {
                File::delete($path);
            }
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::findOrFail($id);

            // Remove images from storage
            if ($product->thumbnail) {
                $this->removeProductImageFromStorage($product->thumbnail);
            }
            if ($product->first_image) {
                $this->removeProductImageFromStorage($product->first_image);
            }
            if ($product->second_image) {
                $this->removeProductImageFromStorage($product->second_image);
            }
            if ($product->third_image) {
                $this->removeProductImageFromStorage($product->third_image);
            }

            // Delete the product
            $product->delete();


            return response(['status' => 'success', 'message' => __('admin.Deleted Successfully!')]);
        } catch (\Throwable $th) {
            return response(['status' => 'error', 'message' => __('admin.something went wrong!')]);
        }
    }
}
