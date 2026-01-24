<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChatRoom;

class ChatRoomSeeder extends Seeder
{
    public function run(): void
    {
        // チャットルーム作成（重複防止）
        $room = ChatRoom::firstOrCreate(
            [
                'product_id' => 1,
                'buyer_id'   => 3,
            ],
            [
                'seller_id'  => 1,
            ]
        );

        // すでにメッセージがある場合は追加しない
        if ($room->messages()->count() === 0) {
            $room->messages()->createMany([
                [
                    'user_id' => 1,
                    'message' => 'はじめまして',
                ],
                [
                    'user_id' => 3,
                    'message' => '商品を購入をさせていただきました',
                ],
            ]);
        }
        
        $room = ChatRoom::firstOrCreate(
            [
                'product_id' => 2,
                'buyer_id'   => 2,
            ],
            [
                'seller_id'  => 1,
            ]
        );
        $room = ChatRoom::firstOrCreate(
            [
                'product_id' => 3,
                'buyer_id'   => 2,
            ],
            [
                'seller_id'  => 1,
            ]
        );
    }
}
