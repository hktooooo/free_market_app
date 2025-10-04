<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Condition;
use App\Models\Product;
use Database\Seeders\ConditionsTableSeeder;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    // 商品の購入
    public function test_purchase_exec()
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
            'product_name' => '購入対象商品',
            'condition_id' => $condition->id,
            'seller_id'    => $seller->id,
        ]);

        $item_id = $product->id;

        // 4. 商品購入画面の表示
        $response = $this->get("/purchase/{$item_id}");
        $response->assertStatus(200);
        $response->assertSee('購入対象商品');

        // 5. 購入する
        // 購入商品データ
        $data = [
            'product_id' => $product->id,
            'payment_method' => 'konbini',
            'zipcode' => '123-4567',
            'address' => 'テスト住所',
            'building' => 'テスト詳細',
        ];

        // 購入商品実行
        $response = $this->post(route('purchase.exec'), $data);

        // 6. DBに保存されているか確認
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'buyer_id' => $user->id,
            'buyer_payment_method' => 'konbini',
            'buyer_payment_status' => 'pending',
            'buyer_zipcode' => '123-4567',
            'buyer_address' => 'テスト住所',
            'buyer_building' => 'テスト詳細',
        ]);
    }

    // 購入した商品にSoldが表示される
    public function test_purchase_display_sold()
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
            'product_name' => '購入対象商品',
            'condition_id' => $condition->id,
            'seller_id'    => $seller->id,
        ]);

        $item_id = $product->id;

        // 4. indexページにアクセス（tab未指定 → recommend）
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('購入対象商品');
        $response->assertDontSee('Sold');

        // 5. 商品購入画面の表示
        $response = $this->get("/purchase/{$item_id}");
        $response->assertStatus(200);
        $response->assertSee('購入対象商品');

        // 6. 購入する
        // 購入商品データ
        $data = [
            'product_id' => $product->id,
            'payment_method' => 'konbini',
            'zipcode' => '123-4567',
            'address' => 'テスト住所',
            'building' => 'テスト詳細',
        ];

        // 購入商品実行
        $response = $this->post(route('purchase.exec'), $data);

        // 7. DBに保存されているか確認
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'buyer_id' => $user->id,
            'buyer_payment_method' => 'konbini',
            'buyer_payment_status' => 'pending',
            'buyer_zipcode' => '123-4567',
            'buyer_address' => 'テスト住所',
            'buyer_building' => 'テスト詳細',
        ]);

        // 8. indexページにアクセス（tab未指定 → recommend）
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('購入対象商品');
        $response->assertSee('Sold');
    }

    // プロフィール/購入した商品一覧に追加
    public function test_purchase_display_mypage_buy()
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
            'product_name' => '購入対象商品',
            'condition_id' => $condition->id,
            'seller_id'    => $seller->id,
        ]);

        $item_id = $product->id;

        // 4. mypageページにアクセス（tab指定 → buy）
        $response = $this->get('/mypage?page=buy');
        $response->assertStatus(200);
        $response->assertDontSee('購入対象商品');
        $response->assertDontSee('Sold');

        // 5. 商品購入画面の表示
        $response = $this->get("/purchase/{$item_id}");
        $response->assertStatus(200);
        $response->assertSee('購入対象商品');

        // 6. 購入する
        // 購入商品データ
        $data = [
            'product_id' => $product->id,
            'payment_method' => 'konbini',
            'zipcode' => '123-4567',
            'address' => 'テスト住所',
            'building' => 'テスト詳細',
        ];

        // 購入商品実行
        $response = $this->post(route('purchase.exec'), $data);

        // 7. DBに保存されているか確認
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'buyer_id' => $user->id,
            'buyer_payment_method' => 'konbini',
            'buyer_payment_status' => 'pending',
            'buyer_zipcode' => '123-4567',
            'buyer_address' => 'テスト住所',
            'buyer_building' => 'テスト詳細',
        ]);

        // 8. mypageページにアクセス（tab指定 → buy）
        $response = $this->get('/mypage?page=buy');
        $response->assertStatus(200);
        $response->assertSee('購入対象商品');
        $response->assertSee('Sold');
    }
}
