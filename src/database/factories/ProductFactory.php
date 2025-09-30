<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'product_name' => $this->faker->word(),
            'price' => $this->faker->numberBetween(1000, 10000),
            'brand' => $this->faker->word(),
            'detail' => $this->faker->sentence(),
            'img_url' => 'default.jpg',
            'condition_id' => 1,
            'seller_id' => 1,
            'buyer_id' => null,
        ];
    }
}
