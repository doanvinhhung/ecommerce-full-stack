<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Specify which attributes are mass assignable
    protected $fillable = [
        'name',
        'slug',
        'quantity',
        'brand_id',
        'category_id',
        'description',
        'price',
        'thumbnail',
        'first_image',
        'second_image',
        'third_image',
        'status'
    ];
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
    // Trong app/Models/Product.php
public function supplier()
{
    return $this->belongsTo(User::class, 'supplier_id'); // Giả sử người bán là User
}
// app/Models/Product.php
public function getAverageRatingAttribute()
{
    return $this->reviews()->avg('rating') ?: 0;
}

public function getReviewCountAttribute()
{
    return $this->reviews()->count();
}
    // Optionally, if you want to set a custom table name, uncomment the line below
    // protected $table = 'products';

    // You can also specify hidden attributes, such as sensitive data
    // protected $hidden = ['created_at', 'updated_at'];

    // Add the relationship methods
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function colors()
    {
        return $this->belongsToMany(Color::class);
    }

    public function sizes()
    {

        return $this->belongsToMany(Size::class);
    }
    public function orders()
    {

        return $this->belongsToMany(Order::class);
    }

    // public function reviews()
    // {

    //     return $this->hasMany(Review::class)
    //         ->with('user')
    //         ->where('approved', 1)
    //         ->latest();
    // }
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)
            ->with('user')
            ->where('approved',1)
            ->latest();
    }

}
