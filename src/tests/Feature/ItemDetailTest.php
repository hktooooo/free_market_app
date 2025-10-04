<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Condition;
use App\Models\Product;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\CategoriesTableSeeder;
use Database\Seeders\ConditionsTableSeeder;
use Database\Seeders\ProductsTableSeeder;
use Database\Seeders\CategoriesProductsTableSeeder;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Events\Verified;

class ItemDetailTest extends TestCase
{

    use RefreshDatabase;
   
    // 商品詳細情報表示
    public function test_displays_products_detail()
    {
        // Seederでデータ作成
        $this->seed(UsersTableSeeder::class);
        $this->seed(CategoriesTableSeeder::class);
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(ProductsTableSeeder::class);
        $this->seed(CategoriesProductsTableSeeder::class);

        $product = Product::where('product_name', '腕時計')->first();
        $item_id = $product->id;

        // 腕時計を表示
        $response = $this->get("/item/{$item_id}");

        // 検証
        $response->assertStatus(200);
        $response->assertSee('Armani+Mens+Clock.jpg');
        $response->assertSee('腕時計');
        $response->assertSee('Rolax');
        $response->assertSee('15,000');
        $response->assertSee('スタイリッシュなデザインのメンズ腕時計');
        $response->assertSee('ファッション');
        $response->assertSee('メンズ');
        $response->assertSee('良好');
    }
}
