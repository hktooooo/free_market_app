<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Condition;
use App\Models\Product;
use App\Models\Payment;
use Database\Seeders\ConditionsTableSeeder;

class PaymethodTest extends TestCase
{
    use RefreshDatabase;

    // 支払い方法選択機能
    public function test_paymethod_select()
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

        // 支払い方法作成
        Payment::factory()->create(['content_name' => 'konbini', 'content' => 'コンビニ払い']);
        Payment::factory()->create(['content_name' => 'card', 'content' => 'カード払い']);

        // 4. 商品購入画面の表示
        $response = $this->get("/purchase/{$item_id}");
        $response->assertStatus(200);
        $response->assertSee('購入対象商品');

        // 5. 支払い方法選択
        // コンビニ払い選択
        $browser->select('payment_method', 'konbini')
                ->pause(500) // JS反映待ち
                ->assertSeeIn('#selectedText', 'コンビニ払い');

        // カード払い選択
        $browser->select('payment_method', 'card')
                ->pause(500)
                ->assertSeeIn('#selectedText', 'カード払い'); 
    }
}