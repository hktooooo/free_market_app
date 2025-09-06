<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'product_name' => '腕時計',
            'price' => 15000,
            'brand' => 'Rolax',
            'detail' => 'スタイリッシュ',
            'img_url' => 'xxxxxx',
            'condition_id' => '1',
            'sold' => '0',
        ];
        DB::table('products')->insert($param);
        $param = [
            'product_name' => 'HDD',
            'price' => 5000,
            'brand' => '西芝',
            'detail' => '高速で信頼性の高い',
            'img_url' => 'xxxxxx',
            'condition_id' => '2',
            'sold' => '0',
        ];
        DB::table('products')->insert($param);
        $param = [
            'product_name' => '玉ねぎ3束',
            'price' => 300,
            'brand' => 'なし',
            'detail' => '新鮮な玉ねぎ',
            'img_url' => 'xxxxxx',
            'condition_id' => '2',
            'sold' => '0',
        ];
        DB::table('products')->insert($param);
        $param = [
            'product_name' => '革靴',
            'price' => 4000,
            'brand' => '',
            'detail' => 'クラシックなデザイン',
            'img_url' => 'xxxxxx',
            'condition_id' => '3',
            'sold' => '0',
        ];
        DB::table('products')->insert($param);
        $param = [
            'product_name' => 'ノートPC',
            'price' => 45000,
            'brand' => '',
            'detail' => '高性能なノートパソコン',
            'img_url' => 'xxxxxx',
            'condition_id' => '1',
            'sold' => '0',
        ];
        DB::table('products')->insert($param);
        $param = [
            'product_name' => 'マイク',
            'price' => 8000,
            'brand' => '',
            'detail' => '高音質のレコーディングマイク用',
            'img_url' => 'xxxxxx',
            'condition_id' => '2',
            'sold' => '0',
        ];
        DB::table('products')->insert($param);
        $param = [
            'product_name' => 'ショルダーバッグ',
            'price' => 3500,
            'brand' => '',
            'detail' => 'おしゃれなショルダーバッグ',
            'img_url' => 'xxxxxx',
            'condition_id' => '1',
            'sold' => '0',
        ];
        DB::table('products')->insert($param);
        $param = [
            'product_name' => 'タンブラー',
            'price' => 500,
            'brand' => '',
            'detail' => '使いやすいタンブラー',
            'img_url' => 'xxxxxx',
            'condition_id' => '1',
            'sold' => '0',
        ];
        DB::table('products')->insert($param);
        $param = [
            'product_name' => 'コーヒーミル',
            'price' => 4000,
            'brand' => 'Starbacks',
            'detail' => '手動のコーヒーミル',
            'img_url' => 'xxxxxx',
            'condition_id' => '1',
            'sold' => '0',
        ];
        DB::table('products')->insert($param);
        $param = [
            'product_name' => 'メイクセット',
            'price' => 2500,
            'brand' => '',
            'detail' => '便利なメイクアップセット',
            'img_url' => 'xxxxxx',
            'condition_id' => '1',
            'sold' => '0',
        ];
        DB::table('products')->insert($param);
    }
}
