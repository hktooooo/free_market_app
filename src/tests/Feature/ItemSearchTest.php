<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Condition;
use App\Models\Product;
use Database\Seeders\ConditionsTableSeeder;

class ItemSearchTest extends TestCase
{

    use RefreshDatabase;
   
    // 商品名で部分一致検索できるか
    public function test_displays_search_products()
    {
        // 1. ユーザー作成
        $user = User::factory()->create();
        $seller = User::factory()->create();

        // 3. 条件と商品作成
        $this->seed(ConditionsTableSeeder::class);
        $condition = Condition::first();

        $product = Product::factory()->create([
            'product_name' => '検索対象商品',
            'condition_id' => $condition->id,
            'seller_id'    => $seller->id,
        ]);
        $product = Product::factory()->create([
            'product_name' => '玉ねぎ',
            'condition_id' => $condition->id,
            'seller_id'    => $seller->id,
        ]);

        // 検索ワードなし
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('検索対象商品');
        $response->assertSee('玉ねぎ');

        // 検索ワード"対象"で実行
        $response = $this->get('/?q=対象&tab=recommend');
        $response->assertStatus(200);
        $response->assertSee('検索対象商品');
        $response->assertDontSee('玉ねぎ');
    }

    // 検索状態がマイリストでも保持されている
    public function test_mylist_search_keeps_conditions()
    {
        // 1. ユーザー登録・ログイン・メール認証
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'email_verified_at' => now(),
        ]);
        $this->actingAs($user);

        // 2. 別ユーザー作成
        $seller = User::factory()->create();

        // 3. 条件と商品作成
        $this->seed(ConditionsTableSeeder::class);
        $condition = Condition::first();

        $product = Product::factory()->create([
            'product_name' => '検索対象いいね商品',
            'condition_id' => $condition->id,
            'seller_id'    => $seller->id,
        ]);
        $product = Product::factory()->create([
            'product_name' => '玉ねぎ',
            'condition_id' => $condition->id,
            'seller_id'    => $seller->id,
        ]);

        // 4. いいねボタンを押す
        $product = Product::where('product_name', '検索対象いいね商品')->first();
        $this->actingAs($user)->post("/item/toggle/{$product->id}");

        // 5. favorites テーブルに登録されているか確認
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        // 検索ワードなし
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('検索対象いいね商品');
        $response->assertSee('玉ねぎ');
    
        // 検索ワード"対象"で実行
        $response = $this->actingAs($user)->get('/?q=対象&tab=recommend');
        $response->assertStatus(200);
        $response->assertSee('検索対象いいね商品');
        $response->assertDontSee('玉ねぎ');
    
        // タブ切り替え 再検証
        $response = $this->actingAs($user)->get('/?q=対象&tab=mylist');
        $response->assertStatus(200);
        $response->assertSee('検索対象いいね商品');
        $response->assertDontSee('玉ねぎ');
    }
}
