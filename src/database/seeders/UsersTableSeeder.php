<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => 'test_user',
            'email' => 'test@test.com',
            'password' => bcrypt('12345678'),
            'zipcode' => '123-4567',
            'address' => '東京都',
            'building' => '東京タワー'
        ];
        DB::table('users')->insert($param);
        $param = [
            'name' => 'seller',
            'email' => 'test1@test.com',
            'password' => bcrypt('12345678'),
            'zipcode' => '123-4567',
            'address' => '東京都',
            'building' => '東京タワー'
        ];
        DB::table('users')->insert($param);
    }
}
