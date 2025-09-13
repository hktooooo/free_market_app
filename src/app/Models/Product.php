<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'price',
        'brand',
        'detail',
        'img_url',
        'condition_id',
        'buyer_id',
        'seller_id',
        'payment_id',
        'zipcode_purchase',
        'address_purchase',
        'building_purchase',
    ];

    // お気に入りされたユーザー
    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites', 'product_id', 'user_id')
                    ->withPivot('favorite')
                    ->withTimestamps();
    }

    // コメントしたユーザー
    public function commentedByUsers()
    {
        return $this->belongsToMany(User::class, 'comments')
                    ->withPivot('comment')
                    ->withTimestamps();
    }

    // 商品のカテゴリー
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'categories_products', 'product_id', 'category_id');
    }

    // 商品の状態
    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    // 購入者
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id'); 
    }

    // 販売者
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id'); 
    }

    // 支払い方法
    public function paymentMethod()
    {
        return $this->belongsTo(Payment::class);
    }
}
