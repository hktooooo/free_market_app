<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Events\Verified;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    // メールアドレス 未入力
    public function test_email_is_required()
    {
        $response = $this->post('/login', [
            'name' => 'test',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください',
        ]);
    }

    // パスワード 未入力
    public function test_password_is_required()
    {
        $response = $this->post('/login', [
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください',
        ]);
    }

    // 入力情報間違い
    public function test_login_error()
    {
        $response = $this->post('/login', [
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'ログイン情報が登録されていません'
        ]);
    }

    // ユーザーが登録してメール認証後にトップページにアクセスできる
    public function test_login()
    {
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

        $response->assertRedirect('/mypage/profile'); // コントローラの仕様どおり

        // 4. ユーザーが認証済みになったか確認
        $this->assertTrue($user->fresh()->hasVerifiedEmail());

        Event::assertDispatched(Verified::class);

        // 5. ログイン済みで `/mypage` にアクセスできる
        $this->actingAs($user->fresh())->get('/mypage')->assertStatus(200);
    }
}
