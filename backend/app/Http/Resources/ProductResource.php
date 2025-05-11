<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'brand' => $this->brand,
            'category' => $this->category,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'thumbnail' => asset($this->thumbnail),
            'first_image' => $this->first_image ?asset($this->first_image):null,
            'second_image' => $this->second_image ?asset($this->second_image):null,
            'third_image' => $this->third_image ?asset($this->third_image):null,
            'status' => $this->status,
            'colors' =>$this->colors,
            'sizes' => $this->sizes,
      
            'reviews' => $this->reviews,
        ];
    }
}