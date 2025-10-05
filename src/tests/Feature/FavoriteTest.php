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

class FavoriteTest extends TestCase
{

    use RefreshDatabase;
   
    // いいね機能テスト
    public function test_favorite()
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

        // 腕時計を取得
        $product = Product::where('product_name', '腕時計')->first();

        // 3. 腕時計を表示しいいね数 0を確認
        $response = $this->actingAs($user)->get("/item/{$product->id}");
        $response->assertStatus(200);
        $response->assertSee('<p id="favorite-count">0</p>', false); // 数字0確認
        $response->assertSee('images/like_off.png'); // いいね前アイコン確認

        // 4. いいねボタンを押す 腕時計
        $this->actingAs($user)->post("/item/toggle/{$product->id}");

        // 5. favorites テーブルに登録されているか確認
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);    
    
        // 6. 腕時計を表示しいいね数 1を確認
        $response = $this->actingAs($user)->get("/item/{$product->id}");
        $response->assertStatus(200);
        $response->assertSee('<p id="favorite-count">1</p>', false); // 数字1確認
        $response->assertSee('images/like_on.png'); // いいね後アイコン確認

        // 7. いいねボタンを再度押す、解除 腕時計
        $this->actingAs($user)->post("/item/toggle/{$product->id}");

        // 8. 腕時計を表示しいいね数 0を確認        
        $response = $this->actingAs($user)->get("/item/{$product->id}");
        $response->assertStatus(200);
        $response->assertSee('<p id="favorite-count">0</p>', false); // 解除後は0
        $response->assertSee('images/like_off.png'); // アイコン戻る
    }
}
