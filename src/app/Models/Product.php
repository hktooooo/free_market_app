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
        'buyer_zipcode',
        'buyer_address',
        'buyer_building',
        'buyer_payment_method',
        'buyer_payment_status',
    ];

    // お気に入りされたユーザー
    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    // 商品に付いたコメント一覧
    public function comments()
    {
        return $this->hasMany(Comment::class);
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

    // 購入時の情報
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    // 購入者
    public function buyer()
    {
        return $this->belongsTo(User::class); 
    }

    // 販売者
    public function seller()
    {
        return $this->belongsTo(User::class); 
    }
}
