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
        DB::table('favorites')->insert([
            ['user_id' => 3, 'product_id' => 1],
            ['user_id' => 3, 'product_id' => 2],
            ['user_id' => 3, 'product_id' => 5],
            ['user_id' => 3, 'product_id' => 6],
            ['user_id' => 1, 'product_id' => 4],
            ['user_id' => 2, 'product_id' => 4],        
        ]);
    }
}
