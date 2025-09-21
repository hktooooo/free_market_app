<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'content' => 'コンビニ払い',
            'content_name' => 'konbini',
        ];
        DB::table('payments')->insert($param);
        $param = [
            'content' => 'カード払い',
            'content_name' => 'card',
        ];
        DB::table('payments')->insert($param);
    }
}
