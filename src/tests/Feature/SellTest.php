<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Product;
use Database\Seeders\CategoriesTableSeeder;
use Database\Seeders\ConditionsTableSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SellTest extends TestCase
{
    use RefreshDatabase;

    // 出品情報の保存   
    public function test_sell_store()
    {
        // Storage をフェイク化（テスト用）
        Storage::fake('public');

        // 1. ユーザー登録・ログイン・メール認証
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'email_verified_at' => now(),
        ]);
        $this->actingAs($user);

        // 2. 条件とカテゴリーを作成
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(CategoriesTableSeeder::class);
        $condition = Condition::first();
        $categories = Category::take(3)->pluck('id'); // 3つ id取り出し

        // ダミー画像を作成
        $image = UploadedFile::fake()->image('test_product.jpg');

        // 3. 出品ページにアクセス
        $response = $this->actingAs($user)->get('/sell');
        $response->assertStatus(200);

        // 4. 出品データを登録        
        $data = [
            'product_name' => 'test_product',
            'price' => '999999',
            'brand' => 'test_brand',
            'detail' => 'テスト商品です',
            'img_url' => $image,
            'condition_id' => $condition->id,
            'seller_id' => $user->id,
            'categories' => $categories->toArray(),
        ];

        $response = $this->actingAs($user)->post(route('sell.exec'), $data);
        $response->assertStatus(302); // リダイレクト確認

        // 5. DBに保存されているか確認
        $product = Product::first();
        $this->assertNotNull($product);

        $this->assertDatabaseHas('products', [
            'product_name' => 'test_product',
            'price' => '999999',
            'brand' => 'test_brand',
            'detail' => 'テスト商品です',
            'condition_id' => $condition->id,
            'seller_id' => $user->id,
        ]);

        // 6. 中間テーブルに保存されているか確認（複数カテゴリ分ループ）
        foreach ($categories as $categoryId) {
            $this->assertDatabaseHas('categories_products', [
                'category_id' => $categoryId,
                'product_id' => $product->id,
            ]);
        }

        // 7. Storage 上に画像が保存されたか確認
        Storage::disk('public')->assertExists($product->img_url);
    }
}
