<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            // チャットルーム
            $table->foreignId('chat_room_id')
                ->constrained()
                ->cascadeOnDelete();

            // 送信者
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // メッセージ本文（画像のみ投稿を許可するため nullable）
            $table->text('message')->nullable();

            // 画像パス
            $table->string('image')->nullable();

            // 既読日時
            $table->timestamp('read_at')->nullable();

            $table->timestamp('created_at')->useCurrent()->nullable();
            $table->timestamp('updated_at')->useCurrent()->nullable();

            // よく使う検索用インデックス
            $table->index(['chat_room_id', 'created_at']);
            $table->index(['chat_room_id', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
