<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'zipcode_purchase',
        'address_purchase',
        'building_purchase',
    ];

    // 購入したユーザー
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 購入された商品
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
