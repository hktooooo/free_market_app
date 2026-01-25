<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->id();

            // 商品
            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            // 購入者
            $table->foreignId('buyer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // 出品者
            $table->foreignId('seller_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // 取引完了管理
            $table->boolean('is_buyer_completed')->default(false);
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();

            // 評価点
            $table->unsignedTinyInteger('seller_rating')->nullable();
            $table->unsignedTinyInteger('buyer_rating')->nullable();

            $table->timestamps();
            
            // 同一商品 × 同一購入者の重複防止
            $table->unique(['product_id', 'buyer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_rooms');
    }
};
