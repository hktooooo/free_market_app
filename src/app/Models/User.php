<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'zipcode',
        'address',
        'building',
        'img_url',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function favoriteProducts()
    {
        return $this->belongsToMany(Product::class, 'favorites', 'user_id', 'product_id')
                    ->withPivot('favorite')
                    ->withTimestamps();
    }

    public function commentedProducts()
    {
        return $this->belongsToMany(Product::class, 'comments')
            ->withPivot('comment')
            ->withTimestamps();
    }

    // 購入した商品
    public function purchasedProducts()
    {
        return $this->hasMany(Product::class, 'buyer_id');
    }

    // 販売した商品
    public function soldProducts()
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

}
