<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Condition;
use App\Models\Product;
use App\Models\Purchase;
use Database\Seeders\ConditionsTableSeeder;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    // 送付先、住所登録
    public function test_address_update()
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

        // 4. 住所登録
        $data = [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'zipcode' => '123-4567',
            'address' => 'テスト住所',
            'building' => 'テスト詳細',
        ];
        $response = $this->post(route('address.update'), $data);

        // 5. DBに保存されているか確認
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'zipcode_purchase' => '123-4567',
            'address_purchase' => 'テスト住所',
            'building_purchase' => 'テスト詳細',
        ]);

        // 6. 商品購入画面の表示
        $response = $this->actingAs($user)->get("/purchase/{$item_id}");
        $response->assertStatus(200);
        $response->assertSeeText('購入対象商品');
        $response->assertSeeText('〒 123-4567');
        $response->assertSeeText('テスト住所');
        $response->assertSeeText('テスト詳細');
    }

    // 購入実行後の送付先住所の確認
    public function test_purchase_address()
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

        // 4. 住所登録
        $data = [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'zipcode' => '123-4567',
            'address' => 'テスト住所',
            'building' => 'テスト詳細',
        ];
        $response = $this->post(route('address.update'), $data);

        // 5. DBに保存されているか確認
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'zipcode_purchase' => '123-4567',
            'address_purchase' => 'テスト住所',
            'building_purchase' => 'テスト詳細',
        ]);

        // 6. 商品購入画面の表示
        $response = $this->actingAs($user)->get("/purchase/{$item_id}");
        $response->assertStatus(200);
        $response->assertSeeText('購入対象商品');
        $response->assertSeeText('〒 123-4567');
        $response->assertSeeText('テスト住所');
        $response->assertSeeText('テスト詳細');

        $purchase = Purchase::first();

        // 購入商品実行
        $data = [
            'product_id' => $product->id,
            'payment_method' => 'konbini',
            'zipcode' => $purchase->zipcode_purchase,
            'address' => $purchase->address_purchase,
            'building' => $purchase->building_purchase,
        ];

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
}
