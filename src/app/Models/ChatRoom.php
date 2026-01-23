<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use HasFactory;

    /**
     * 一括代入を許可するカラム
     */
    protected $fillable = [
        'product_id',
        'buyer_id',
        'seller_id',
        'is_buyer_completed',
        'is_completed',
        'completed_at',
        'seller_rating',
        'buyer_rating',
    ];

    /**
     * 商品
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * 購入者
     */
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /**
     * 出品者
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * メッセージ一覧
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * 自分がこのルームの参加者か判定（便利メソッド）
     */
    public function isParticipant(int $userId): bool
    {
        return $this->buyer_id === $userId || $this->seller_id === $userId;
    }
}
