<?php

// app/Http/Resources/ReviewResource.php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'rating' => $this->rating,
            'images' => $this->image_urls,
            'approved' => $this->approved,
            'created_at' => $this->created_at->diffForHumans(),
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'avatar' => $this->user->avatar_url
            ]
        ];
    }
}