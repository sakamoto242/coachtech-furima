<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase; // テスト実行後にDBをリセットする

    /** @test */
    public function 名前が未入力の場合バリデーションエラーになること()
    {
        $response = $this->post('/register', [
            'name' => '', // 名前を空にする
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['name' => 'お名前を入力してください']);
    }

    /** @test */
    public function パスワードが8文字未満の場合バリデーションエラーになること()
    {
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => '1234567', // 7文字
            'password_confirmation' => '1234567',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードは8文字以上で入力してください']);
    }
    /** @test */
public function メールアドレスが未入力の場合バリデーションエラーになること()
{
    $response = $this->post('/register', [
        'name' => 'テスト太郎',
        'email' => '', // 空にする
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);
    $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
}
}