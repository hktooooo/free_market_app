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
        'sold',
    ];

    public function products()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('favorite', 'comment')
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
