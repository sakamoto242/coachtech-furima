<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin; // Adminモデルを読み込む
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'name'     => '管理者',
            'email'    => 'admin@example.com', // ログイン用メールアドレス
            'password' => Hash::make('password'), // ログイン用パスワード
        ]);
    }
}