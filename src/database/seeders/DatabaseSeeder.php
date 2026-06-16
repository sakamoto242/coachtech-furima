<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SeasonSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            AdminSeeder::class, // 管理者アカウント作成用
        ]);
    }
}
