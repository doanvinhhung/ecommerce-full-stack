<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.categories.index')->with([ 
            'categories'=> Category::latest()->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(AddCategoryRequest $request)
    {
        if ($request->validated()) {
            $data = $request->validated(); // Lấy toàn bộ dữ liệu hợp lệ
            $data['slug'] = Str::slug($request->name); // Thêm slug vào mảng dữ liệu
            Category::create($data); // Lưu toàn bộ dữ liệu vào DB
            return redirect()->route('admin.categories.index')->with([
                'success' => "Category is created successfully"
            ]);
        }
    }
    
    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
       abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit')->with([
            'category'=>$category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        if($request->validated()){
            $data = $request->validated();
            $data['slug'] = Str::slug($request->name);
            $category->update($data);
            return redirect()->route('admin.categories.index')->with([
                'success'=>"Category is update"
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category = Category::findOrFail($id);
          
            $category->delete();
            return response(['status' => 'success', 'message' => __('admin.Deleted Successfully!')]);
        } catch (\Throwable $th) {
            return response(['status' => 'error', 'message' => __('admin.something went wrong!')]);
        }

    }
}
