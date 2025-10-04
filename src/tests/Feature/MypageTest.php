<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Condition;
use App\Models\Product;
use App\Models\Purchase;
use Database\Seeders\ConditionsTableSeeder;

class MypageTest extends TestCase
{
    use RefreshDatabase;

    // ユーザー情報表示
    public function test_displays_mypage()
    {
        // 1. ユーザー登録・ログイン・メール認証
        $user = User::factory()->create([
            'name' => 'test_user',
            'email' => 'test@example.com',
            'zipcode' => '123-4567',
            'address' => 'テスト住所',
            'building' => 'テスト詳細',
            'img_url' => 'profile_images/test_img.jpg',
            'email_verified_at' => now(),
        ]);
        $this->actingAs($user);

        // 2. 別ユーザー作成
        $seller = User::factory()->create();

        // 3. 条件と商品作成
        $this->seed(ConditionsTableSeeder::class);
        $condition = Condition::first();

        $myProduct = Product::factory()->create([
            'product_name' => '出品した商品',
            'condition_id' => $condition->id,
            'seller_id'    => $user->id,
        ]);

        $otherProduct = Product::factory()->create([
            'product_name' => '購入した商品',
            'condition_id' => $condition->id,
            'seller_id'    => $seller->id,
            'buyer_id'    => $user->id,
        ]);

        // 4. DBに保存されているか確認
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'test_user',
            'email' => 'test@example.com',
            'zipcode' => '123-4567',
            'address' => 'テスト住所',
            'building' => 'テスト詳細',
            'img_url' => 'profile_images/test_img.jpg',
        ]);

        // 5. mypageページにアクセス（tab指定 → sell）
        $response = $this->get('/mypage?page=sell');
        $response->assertStatus(200);
        $response->assertSee('test_user');
        $response->assertSee('storage/profile_images/test_img.jpg');
        $response->assertSee('出品した商品');
        
        // 6. mypageページにアクセス（tab指定 → buy）
        $response = $this->get('/mypage?page=buy');
        $response->assertStatus(200);
        $response->assertSee('購入した商品');
    }
}