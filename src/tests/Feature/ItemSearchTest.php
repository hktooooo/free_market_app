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

class ItemSearchTest extends TestCase
{

    use RefreshDatabase;
   
    // 商品名で部分一致検索できるか
    public function test_displays_search_products()
    {
        // Seederでデータ作成
        $this->seed(UsersTableSeeder::class);
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(ProductsTableSeeder::class);

        // 検索ワード"時計"で実行
        $response = $this->get('/?q=時計&tab=recommend');

        // 検証
        $response->assertStatus(200);
        $response->assertSee('腕時計');
        $response->assertDontSee('玉ねぎ');
    }
}
