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
            'detail' => 'スタイリッシュなデザインのメンズ腕時計',
            'img_url' => 'product_images/Armani+Mens+Clock.jpg',
            'condition_id' => 1,
            'seller_id' => 2,
        ];
        DB::table('products')->insert($param);
        $param = [
            'product_name' => 'HDD',
            'price' => 5000,
            'brand' => '西芝',
            'detail' => '高速で信頼性の高いハードディスク',
            'img_url' => 'product_images/HDD+Hard+Disk.jpg',
            'condition_id' => 2,
            'seller_id' => 2,
        ];
        DB::table('products')->insert($param);
        $param = [
            'product_name' => '玉ねぎ3束',
            'price' => 300,
            'brand' => 'なし',
            'detail' => '新鮮な玉ねぎ3束のセット',
            'img_url' => 'product_images/iLoveIMG+d.jpg',
            'condition_id' => 3,
            'seller_id' => 2,
        ];
        DB::table('products')->insert($param);
        $param = [
            'product_name' => '革靴',
            'price' => 4000,
            'brand' => '',
            'detail' => 'クラシックなデザインの革靴',
            'img_url' => 'product_images/Leather+Shoes+Product+Photo.jpg',
            'condition_id' => 4,
            'seller_id' => 2,
        ];
        DB::table('products')->insert($param);
        $param = [
            'product_name' => 'ノートPC',
            'price' => 45000,
            'brand' => '',
            'detail' => '高性能なノートパソコン',
            'img_url' => 'product_images/Living+Room+Laptop.jpg',
            'condition_id' => 1,
            'seller_id' => 2,
        ];
        DB::table('products')->insert($param);
        $param = [
            'product_name' => 'マイク',
            'price' => 8000,
            'brand' => 'なし',
            'detail' => '高音質のレコーディング用マイク',
            'img_url' => 'product_images/Music+Mic+4632231.jpg',
            'condition_id' => 2,
            'seller_id' => 2,
        ];
        DB::table('products')->insert($param);
        $param = [
            'product_name' => 'ショルダーバッグ',
            'price' => 3500,
            'brand' => '',
            'detail' => 'おしゃれなショルダーバッグ',
            'img_url' => 'product_images/Purse+fashion+pocket.jpg',
            'condition_id' => 3,
            'seller_id' => 2,
        ];
        DB::table('products')->insert($param);
        $param = [
            'product_name' => 'タンブラー',
            'price' => 500,
            'brand' => 'なし',
            'detail' => '使いやすいタンブラー',
            'img_url' => 'product_images/Tumbler+souvenir.jpg',
            'condition_id' => 4,
            'seller_id' => 2,
        ];
        DB::table('products')->insert($param);
        $param = [
            'product_name' => 'コーヒーミル',
            'price' => 4000,
            'brand' => 'Starbacks',
            'detail' => '手動のコーヒーミル',
            'img_url' => 'product_images/Waitress+with+Coffee+Grinder.jpg',
            'condition_id' => 1,
            'seller_id' => 2,
        ];
        DB::table('products')->insert($param);
        $param = [
            'product_name' => 'メイクセット',
            'price' => 2500,
            'brand' => '',
            'detail' => '便利なメイクアップセット',
            'img_url' => 'product_images/makeup_sets.jpg',
            'condition_id' => 2,
            'seller_id' => 2,
        ];
        DB::table('products')->insert($param);
    }
}
