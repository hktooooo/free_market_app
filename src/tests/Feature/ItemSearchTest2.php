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

class ItemSearchTest2 extends TestCase
{

    use RefreshDatabase;
   
    // 検索状態がマイリストでも保持されている
    public function test_mylist_search_keeps_conditions()
    {
        Event::fake();

        // Seederでデータ作成
        $this->seed(UsersTableSeeder::class);
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(ProductsTableSeeder::class);

        // 2. ユーザー登録・ログイン・メール認証
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

        // 4. いいねボタンを押す
        $product = Product::where('product_name', '腕時計')->first();
        $this->actingAs($user)->post("/item/toggle/{$product->id}");

        // 5. favorites テーブルに登録されているか確認
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);    
    
        // 検索ワード"時計"で実行
        $response = $this->actingAs($user)->get('/?q=時計&tab=recommend');
        $response->assertStatus(200);
        $response->assertSee('腕時計');
        $response->assertDontSee('玉ねぎ');
    
        // タブ切り替え 再検証
        $response = $this->actingAs($user)->get('/?q=時計&tab=mylist');
        $response->assertStatus(200);
        $response->assertSee('腕時計');
        $response->assertDontSee('玉ねぎ');
    }
}
