@extends('admin.layouts.master')

@section('content')
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Forms</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Product Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-xl-9 mx-auto">
                    <h6 class="mb-0 text-uppercase">Product Edit</h6>
                    <hr />
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin.products.update', $product->slug) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label">Name:</label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name', $product->name) }}">
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description:</label>
                                    <textarea id="description" class="form-control" name="description">{{ old('description', $product->description) }}</textarea>
                                    @error('description')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Price:</label>
                                    <input type="number" id="price" class="form-control" name="price" value="{{ old('price', $product->price) }}">
                                    @error('price')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Quantity:</label>
                                    <input type="number" id="quantity" class="form-control" name="quantity" value="{{ old('quantity', $product->quantity) }}">
                                    @error('quantity')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Category:</label>
                                    <select id="category_id" class="form-control" name="category_id">
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Brand:</label>
                                    <select id="brand_id" class="form-control" name="brand_id">
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('brand_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="multiple-select-clear-field" class="form-label">Size:</label>
                                    <select id="multiple-select-clear-field-size-2" data-placeholder="Choose anything" class="form-control" name="size_id[]" multiple>
                                        @foreach($sizes as $size)
                                            <option value="{{ $size->id }}" {{ in_array($size->id, old('size_id', $product->sizes->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $size->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('size_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="multiple-select-clear-field-color" class="form-label">Color:</label>
                                    <select class="form-select" id="multiple-select-clear-field-color-2" data-placeholder="Choose anything" name="color_id[]" multiple>
                                        @foreach($colors as $color)
                                            <option value="{{ $color->id }}" {{ in_array($color->id, old('color_id', $product->colors->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $color->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('color_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status:</label>
                                    <select id="status" class="form-control" name="status">
                                        <option value="1" {{ old('status', $product->status) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('status', $product->status) == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Thumbnail:</label>
                                    <input type="file" id="thumbnail" class="form-control" name="thumbnail">
                                    @error('thumbnail')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">First Image:</label>
                                    <input type="file" id="first_image" class="form-control" name="first_image">
                                    @error('first_image')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Second Image:</label>
                                    <input type="file" id="second_image" class="form-control" name="second_image">
                                    @error('second_image')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Third Image:</label>
                                    <input type="file" id="third_image" class="form-control" name="third_image">
                                    @error('third_image')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="py-4">
                                    <button type="submit" class="btn btn-info px-5 float-end">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>
    </div>
    <!--end page wrapper -->
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        
        $('#multiple-select-clear-field-color-2').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: false,
            allowClear: true,
        });
        
        $('#multiple-select-clear-field-size-2').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: false,
            allowClear: true,
        });
    });
</script>