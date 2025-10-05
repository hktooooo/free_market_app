<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Condition;
use App\Models\Product;
use App\Models\Purchase;
use Database\Seeders\ConditionsTableSeeder;

class MypageEditTest extends TestCase
{
    use RefreshDatabase;

    // ユーザー情報編集    
    public function test_displays_mypage_updete()
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

        // 2. DBに保存されているか確認
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'test_user',
            'email' => 'test@example.com',
            'zipcode' => '123-4567',
            'address' => 'テスト住所',
            'building' => 'テスト詳細',
            'img_url' => 'profile_images/test_img.jpg',
        ]);

        // 3. mypage編集ページにアクセス
        $response = $this->actingAs($user)->get('/mypage/profile');
        $response->assertStatus(200);
        $response->assertSee('test_user');
        $response->assertSee('123-4567');
        $response->assertSee('テスト住所');
        $response->assertSee('テスト詳細');
        $response->assertSee('storage/profile_images/test_img.jpg');

        // 4. データを更新        
        $data = [
            'name' => 'update_user',
            'zipcode' => '888-8888',
            'address' => '変更後住所',
            'building' => '変更後詳細',
        ];
        $response = $this->actingAs($user)->post(route('mypage.update'), $data);

        // 更新後 / にリダイレクトしているか確認
        $response->assertRedirect('/');

        // 2. DBに保存されているか確認
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'update_user',
            'zipcode' => '888-8888',
            'address' => '変更後住所',
            'building' => '変更後詳細',
        ]);

        // 5. mypageページにアクセス（tab指定なし）
        $response = $this->actingAs($user)->get('/mypage');
        $response->assertStatus(200);
        $response->assertSee('update_user');
        $response->assertSee('storage/profile_images/test_img.jpg');

        // 3. mypage編集ページにアクセス
        $response = $this->actingAs($user)->get('/mypage/profile');
        $response->assertStatus(200);
        $response->assertSee('update_user');
        $response->assertSee('888-8888');
        $response->assertSee('変更後住所');
        $response->assertSee('変更後詳細');
        $response->assertSee('storage/profile_images/test_img.jpg');        
    }
}
