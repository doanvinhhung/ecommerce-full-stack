<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'phone',
        'country',
        'city',
        'zip',
        'profile_image',
        'profile_completed',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $appends = [
        'image_path',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function orders()
    {
        return $this->hasMany(Order::class)->with('products')->latest();
    }
    
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

// Định nghĩa phương thức accessor trong User model
public function getImagePathAttribute()
{
    if ($this->profile_image) {
        return asset($this->profile_image);
    } else {
        return 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT5fr1owd6MRbY8x4olsdPPBsbrG4JD3iaY0Q&s';
    }
}

}
