<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Condition;
use App\Models\Product;
use Database\Seeders\ConditionsTableSeeder;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    // コメントの投稿、コメント数の表示
    public function test_login_comment()
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
            'product_name' => 'コメント対象商品',
            'condition_id' => $condition->id,
            'seller_id'    => $seller->id,
        ]);

        $item_id = $product->id;

        // 4. 商品詳細の表示とコメント数の確認
        $response = $this->get("/item/{$item_id}");
        $response->assertStatus(200);
        $response->assertSee('コメント対象商品');
        $response->assertSee('<p id="comment-count">0</p>', false); // 数字0確認

        // 5. コメントする
        // 投稿データ
        $data = [
            'comment' => 'これはテストコメントです',
            'product_id' => $product->id,
        ];

        // コメント投稿
        $response = $this->post(route('comments.store'), $data);

        // 6. リダイレクトを確認
        $response->assertRedirect();

        // 7. DBに保存されているか確認
        $this->assertDatabaseHas('comments', [
            'comment' => 'これはテストコメントです',
            'product_id' => $product->id,
            'user_id' => $user->id,
        ]);

        // 8. 商品詳細の表示とコメント数の確認
        $response = $this->get("/item/{$item_id}");
        $response->assertStatus(200);
        $response->assertSee('コメント対象商品');
        $response->assertSee('これはテストコメントです');
        $response->assertSee('<p id="comment-count">1</p>', false); // 数字1確認
    }

    // ログイン前はコメントできない
    public function test_logout_do_not_comment()
    {
        // 1. ユーザー作成、商品作成用
        $seller = User::factory()->create();

        // 2. 条件と商品作成
        $this->seed(ConditionsTableSeeder::class);
        $condition = Condition::first();

        $product = Product::factory()->create([
            'product_name' => 'コメント対象商品',
            'condition_id' => $condition->id,
            'seller_id'    => $seller->id,
        ]);

        $item_id = $product->id;

        // 3. 商品詳細の表示とコメント数の確認
        $response = $this->get("/item/{$item_id}");
        $response->assertStatus(200);
        $response->assertSee('コメント対象商品');
        $response->assertSee('<p id="comment-count">0</p>', false); // 数字0確認

        // 4. コメントする
        // 投稿データ
        $data = [
            'comment' => 'これはテストコメントです',
            'product_id' => $product->id,
        ];

        // コメント投稿
        $response = $this->post(route('comments.store'), $data);

        // 5. リダイレクトを確認
        $response->assertRedirect();

        // 6. 商品詳細の表示とコメント数の確認
        $response = $this->get("/item/{$item_id}");
        $response->assertStatus(200);
        $response->assertSee('コメント対象商品');
        $response->assertDontSee('これはテストコメントです');
        $response->assertSee('<p id="comment-count">0</p>', false); // 数字0確認
    }

    // コメントが空の場合のバリデーションを確認
    public function test_login_empty_comment()
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
            'product_name' => 'コメント対象商品',
            'condition_id' => $condition->id,
            'seller_id'    => $seller->id,
        ]);

        $item_id = $product->id;

        // 4. 商品詳細の表示とコメント数の確認
        $response = $this->get("/item/{$item_id}");
        $response->assertStatus(200);
        $response->assertSee('コメント対象商品');
        $response->assertSee('<p id="comment-count">0</p>', false); // 数字0確認

        // 5. コメントする
        // 投稿データ
        $data = [
            'comment' => '', // 空
            'product_id' => $product->id,
        ];

        // コメント投稿
        $response = $this->post(route('comments.store'), $data);

        // 6. バリデーションエラーがあることを確認
        $response->assertSessionHasErrors(['comment']);

        // 7. エラーメッセージを確認
        $errors = session('errors');
        $this->assertEquals(
            'コメントを入力してください',
            $errors->first('comment')
        );

        // 8. DBに保存されていないことを確認
        $this->assertDatabaseMissing('comments', [
            'product_id' => $product->id,
            'user_id' => $user->id,
        ]);
    }

    // コメントが256文字以上場合のバリデーションを確認
    public function test_login_max_comment()
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
            'product_name' => 'コメント対象商品',
            'condition_id' => $condition->id,
            'seller_id'    => $seller->id,
        ]);

        $item_id = $product->id;

        // 4. 商品詳細の表示とコメント数の確認
        $response = $this->get("/item/{$item_id}");
        $response->assertStatus(200);
        $response->assertSee('コメント対象商品');
        $response->assertSee('<p id="comment-count">0</p>', false); // 数字0確認

        // 5. コメントする
        // 投稿データ
        $longComment = str_repeat('あ', 256); // 256文字

        $data = [
            'comment' => $longComment,
            'product_id' => $product->id,
        ];

        // コメント投稿
        $response = $this->post(route('comments.store'), $data);

        // 6. バリデーションエラーがあることを確認
        $response->assertSessionHasErrors(['comment']);

        // 7. エラーメッセージを確認
        $errors = session('errors');
        $this->assertEquals(
            'コメントは最大 255 文字までです',
            $errors->first('comment')
        );

        // 8. DBに保存されていないことを確認
        $this->assertDatabaseMissing('comments', [
            'product_id' => $product->id,
            'user_id' => $user->id,
        ]);
    }

}
