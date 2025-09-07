<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FavoritesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'user_id' => 3,
            'product_id' => 1,
            'favorite' => true,
        ];
        DB::table('favorites')->insert($param);
        $param = [
            'user_id' => 3,
            'product_id' => 2,
            'favorite' => true,
        ];
        DB::table('favorites')->insert($param);
        $param = [
            'user_id' => 3,
            'product_id' => 5,
            'favorite' => true,
        ];
        DB::table('favorites')->insert($param);
        $param = [
            'user_id' => 3,
            'product_id' => 6,
            'favorite' => true,
        ];
        DB::table('favorites')->insert($param);
    }
}
