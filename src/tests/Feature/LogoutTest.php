<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Events\Verified;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    // ユーザーが登録してメール認証後にトップページにアクセスできる
    public function test_login_logout()
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

        // 6. ログアウトして `/` にアクセスできる
        auth()->logout();
        $this->get('/')->assertStatus(200);
    }
}
