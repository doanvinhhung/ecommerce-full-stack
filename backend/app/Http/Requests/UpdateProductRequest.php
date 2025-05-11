<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:products,name,' . $this->product->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'size_id' => 'required|exists:sizes,id',
            'color_id' => 'required|exists:colors,id',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'first_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'second_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'third_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'The product name is required.',
            'name.unique' => 'The product name must be unique.',
            'description.required' => 'The product description is required.',
            'price.required' => 'The product price is required.',
            'price.numeric' => 'The product price must be a number.',
            'quantity.required' => 'The product quantity is required.',
            'quantity.integer' => 'The product quantity must be an integer.',
            'category_id.required' => 'The category is required.',
            'category_id.exists' => 'The selected category does not exist.',
            'brand_id.required' => 'The brand is required.',
            'brand_id.exists' => 'The selected brand does not exist.',
            'size_id.required' => 'The size is required.',
            'size_id.exists' => 'The selected size does not exist.',
            'color_id.required' => 'The color is required.',
            'color_id.exists' => 'The selected color does not exist.',
            'thumbnail.image' => 'The thumbnail must be an image.',
            'thumbnail.mimes' => 'The thumbnail must be a file of type: jpeg, png, jpg, gif, svg,webp.',
            'thumbnail.max' => 'The thumbnail may not be greater than 2048 kilobytes.',
            'first_image.image' => 'The first image must be an image.',
            'first_image.mimes' => 'The first image must be a file of type: jpeg, png, jpg, gif, svg,webp.',
            'first_image.max' => 'The first image may not be greater than 2048 kilobytes.',
            'second_image.image' => 'The second image must be an image.',
            'second_image.mimes' => 'The second image must be a file of type: jpeg, png, jpg, gif, svg,webp.',
            'second_image.max' => 'The second image may not be greater than 2048 kilobytes.',
            'third_image.image' => 'The third image must be an image.',
            'third_image.mimes' => 'The third image must be a file of type: jpeg, png, jpg, gif, svg,webp.',
            'third_image.max' => 'The third image may not be greater than 2048 kilobytes.',
        ];
    }
}