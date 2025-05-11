<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
      // Specify which attributes are mass assignable
      protected $fillable = ['name'];

      // You can also define relationships

      public function products()
      {
          return $this->belongsToMany(Product::class);
      }
      
}
