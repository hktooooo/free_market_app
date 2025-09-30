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

class MylistTest extends TestCase
{
    use RefreshDatabase;

    // いいねした商品の表示
    public function test_displays_mylist_products()
    {
        Event::fake();

        // 1. ユーザー登録・ログイン・メール認証
        $this->post('/register', [
            'name' => 'validname',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertAuthenticatedAs($user);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );
        $this->actingAs($user)->get($verificationUrl);

        // 2. 別ユーザー作成
        $seller = User::factory()->create();

        // 3. 条件と商品作成
        $this->seed(ConditionsTableSeeder::class);
        $condition = Condition::first();

        $myProduct = Product::factory()->create([
            'product_name' => 'いいねした商品',
            'condition_id' => $condition->id,
            'seller_id'    => $seller->id,
        ]);

        $otherProduct = Product::factory()->create([
            'product_name' => 'いいねしない商品',
            'condition_id' => $condition->id,
            'seller_id'    => $seller->id,
        ]);

        // 4. いいねボタンを押す
        $this->actingAs($user)->post("/item/toggle/{$myProduct->id}");

        // 5. favorites テーブルに登録されているか確認
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'product_id' => $myProduct->id,
        ]);

        // 6. マイリスト表示
        $response = $this->actingAs($user)->get('/?tab=mylist');
        $response->assertStatus(200);
        $response->assertDontSee('いいねしない商品');
        $response->assertSee('いいねした商品');
    }

    // 購入済み商品の「Sold」表示
    public function test_displays_sold_mylist_product()
    {
        Event::fake();

        // 1. ユーザー登録・ログイン・メール認証
        $this->post('/register', [
            'name' => 'validname',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertAuthenticatedAs($user);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );
        $this->actingAs($user)->get($verificationUrl);

        // 2. 別ユーザー作成
        $seller = User::factory()->create();
        $buyer  = User::factory()->create();

        // 3. 条件と商品作成
        $this->seed(ConditionsTableSeeder::class);
        $condition = Condition::first();

        $myProduct = Product::factory()->create([
            'product_name' => 'いいねして売れた商品',
            'condition_id' => $condition->id,
            'seller_id'    => $seller->id,
            'buyer_id'     => $buyer->id, // ← buyerがいるので「sold」
        ]);

        $otherProduct = Product::factory()->create([
            'product_name' => 'いいねしない商品',
            'condition_id' => $condition->id,
            'seller_id'    => $seller->id,
        ]);

        // 4. いいねボタンを押す
        $this->actingAs($user)->post("/item/toggle/{$myProduct->id}");

        // 5. favorites テーブルに登録されているか確認
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'product_id' => $myProduct->id,
        ]);

        // 6. マイリスト表示
        $response = $this->actingAs($user)->get('/?tab=mylist');
        $response->assertStatus(200);
        $response->assertDontSee('いいねしない商品');
        $response->assertSee('いいねして売れた商品');
        $response->assertSee('sold');
    }

    // 未認証の場合 何も表示されない
    public function test_displays_mylist_no_products(){
        Event::fake();

        // 1. ユーザー登録・ログイン・メール認証
        $this->post('/register', [
            'name' => 'validname',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertAuthenticatedAs($user);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );
        $this->actingAs($user)->get($verificationUrl);

        // 2. 別ユーザー作成
        $seller = User::factory()->create();

        // 3. 条件と商品作成
        $this->seed(ConditionsTableSeeder::class);
        $condition = Condition::first();

        $myProduct = Product::factory()->create([
            'product_name' => 'いいねした商品',
            'condition_id' => $condition->id,
            'seller_id'    => $seller->id,
        ]);

        $otherProduct = Product::factory()->create([
            'product_name' => 'いいねしない商品',
            'condition_id' => $condition->id,
            'seller_id'    => $seller->id,
        ]);

        // 4. いいねボタンを押す
        $this->actingAs($user)->post("/item/toggle/{$myProduct->id}");

        // 5. favorites テーブルに登録されているか確認
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'product_id' => $myProduct->id,
        ]);

        // 6. ログアウト
        auth()->logout();

        // 7. マイリスト表示
        $response = $this->get('/?tab=mylist');
        $response->assertStatus(200);
        $response->assertDontSee('いいねしない商品');
        $response->assertDontSee('いいねした商品');
    }
}
