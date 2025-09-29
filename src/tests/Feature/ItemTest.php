<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Condition;
use App\Models\Product;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\ConditionsTableSeeder;
use Database\Seeders\ProductsTableSeeder;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Events\Verified;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    // 全商品を取得できるか
    public function test_displays_all_products()
    {
        // 1. Seederでデータ作成
        $this->seed(UsersTableSeeder::class);
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(ProductsTableSeeder::class);

        // DBに作られた条件と商品を取得
        $condition = Condition::first();
        $products = Product::all();

        // 2. indexページにアクセス（tab未指定 → recommend）
        $response = $this->get('/');

        // 3. 正しいビューが返っていることを確認
        $response->assertViewIs('index');

        // 4. ビューに商品が渡されていることを確認
        $response->assertViewHas('products', function ($viewProducts) use ($products) {
            return $viewProducts->count() === $products->count()
                && $viewProducts->pluck('id')->sort()->values()->all()
                   === $products->pluck('id')->sort()->values()->all();
        });

        // 5. 条件も渡っていることを確認
        $response->assertViewHas('conditions', function ($viewConditions) use ($condition) {
            return $viewConditions->contains($condition);
        });
    }
}
