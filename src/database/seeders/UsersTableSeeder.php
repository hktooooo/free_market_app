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
            'name' => 'aaa',
            'email' => 'test@test.com',
            'password' => bcrypt('12345678'),
        ];
        DB::table('users')->insert($param);
        $param = [
            'name' => 'bbb',
            'email' => 'test1@test.com',
            'password' => bcrypt('12345678'),
        ];
        DB::table('users')->insert($param);
    }
}
