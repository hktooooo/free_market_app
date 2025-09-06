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
            'img_url' => 'Armani+Mens+Clock.jpg',
            'condition_id' => 1,
            'seller_id' => 1,
            'buyer_id' => 2,
        ];
        DB::table('products')->insert($param);
        $param = [
            'product_name' => 'HDD',
            'price' => 5000,
            'brand' => '西芝',
            'detail' => '高速で信頼性の高い',
            'img_url' => 'HDD+Hard+Disk.jpg',
            'condition_id' => 2,
            'seller_id' => 1,
        ];
        DB::table('products')->insert($param);
        $param = [
            'product_name' => '玉ねぎ3束',
            'price' => 300,
            'brand' => 'なし',
            'detail' => '新鮮な玉ねぎ',
            'img_url' => 'iLoveIMG+d.jpg',
            'condition_id' => 2,
            'seller_id' => 2,
        ];
        DB::table('products')->insert($param);
        $param = [
            'product_name' => '革靴',
            'price' => 4000,
            'brand' => '',
            'detail' => 'クラシックなデザイン',
            'img_url' => 'Leather+Shoes+Product+Photo.jpg',
            'condition_id' => 3,
            'seller_id' => 2,
        ];
        DB::table('products')->insert($param);
        $param = [
            'product_name' => 'ノートPC',
            'price' => 45000,
            'brand' => '',
            'detail' => '高性能なノートパソコン',
            'img_url' => 'Living+Room+Laptop.jpg',
            'condition_id' => 1,
            'seller_id' => 2,
            'buyer_id' => 1,
        ];
        DB::table('products')->insert($param);
        $param = [
            'product_name' => 'マイク',
            'price' => 8000,
            'brand' => '',
            'detail' => '高音質のレコーディングマイク用',
            'img_url' => 'Music+Mic+4632231.jpg',
            'condition_id' => 2,
            'seller_id' => 1,
            'buyer_id' => 2,
        ];
        DB::table('products')->insert($param);
        $param = [
            'product_name' => 'ショルダーバッグ',
            'price' => 3500,
            'brand' => '',
            'detail' => 'おしゃれなショルダーバッグ',
            'img_url' => 'Purse+fashion+pocket.jpg',
            'condition_id' => 1,
            'seller_id' => 1,
        ];
        DB::table('products')->insert($param);
        $param = [
            'product_name' => 'タンブラー',
            'price' => 500,
            'brand' => '',
            'detail' => '使いやすいタンブラー',
            'img_url' => 'Tumbler+souvenir.jpg',
            'condition_id' => 1,
            'seller_id' => 1,
        ];
        DB::table('products')->insert($param);
        $param = [
            'product_name' => 'コーヒーミル',
            'price' => 4000,
            'brand' => 'Starbacks',
            'detail' => '手動のコーヒーミル',
            'img_url' => 'Waitress+with+Coffee+Grinder.jpg',
            'condition_id' => 1,
            'seller_id' => 1,
        ];
        DB::table('products')->insert($param);
        $param = [
            'product_name' => 'メイクセット',
            'price' => 2500,
            'brand' => '',
            'detail' => '便利なメイクアップセット',
            'img_url' => 'makeup_sets.jpg',
            'condition_id' => 1,
            'seller_id' => 2,
        ];
        DB::table('products')->insert($param);
    }
}
