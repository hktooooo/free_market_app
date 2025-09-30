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

    // 購入済み商品の「Sold」表示
    public function test_sold_product_displays()
    {
        $this->seed(ConditionsTableSeeder::class);
        $condition = Condition::first();

        $seller = User::factory()->create();
        $buyer  = User::factory()->create();

        // 売れた商品
        $soldProduct = Product::factory()->create([
            'product_name' => '売れた商品',
            'condition_id' => $condition->id,
            'seller_id'    => $seller->id,
            'buyer_id'     => $buyer->id, // ← buyerがいるので「sold」
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        // 売れた商品には sold 表示
        $response->assertSee('売れた商品');
        $response->assertSee('sold');
    }

    // 自分が出品した商品は表示されない
    public function test_my_sold_product_displays()
    {
        // ユーザーの登録とログイン
        Event::fake(); // Verified イベントの発火を検証用にフェイクする

        // 1. ユーザー登録
        $this->post('/register', [
            'name' => 'validname',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'test@example.com')->first();

        // 登録直後はログイン済み & 未認証
        $this->assertAuthenticatedAs($user);
        $this->assertFalse($user->hasVerifiedEmail());

        // 2. 認証リンクをシミュレート
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        // 3. 認証リンクアクセス
        $response = $this->actingAs($user)->get($verificationUrl);

        $other = User::factory()->create();

        // 商品情報の登録
        $this->seed(ConditionsTableSeeder::class);
        $condition = Condition::first();

        // 自分の商品
        $myProduct = Product::factory()->create([
            'product_name' => '出品した商品',
            'condition_id' => $condition->id,
            'seller_id'    => $user->id,
        ]);

        // 他人の商品
        $otherProduct = Product::factory()->create([
            'product_name' => '出品していない商品',
            'condition_id' => $condition->id,
            'seller_id'    => $other->id,
        ]);

        // 商品一覧表示
        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('出品した商品');
        $response->assertSee('出品していない商品');
    }
}
