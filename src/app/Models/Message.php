<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    /**
     * 一括代入を許可するカラム
     */
    protected $fillable = [
        'chat_room_id',
        'user_id',
        'message',
        'image',
        'read_at',
    ];

    /**
     * 日付として扱うカラム
     */
    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * チャットルーム
     */
    public function chatRoom()
    {
        return $this->belongsTo(ChatRoom::class);
    }

    /**
     * 送信者
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * 自分のメッセージか判定
     */
    public function isMine(int $userId): bool
    {
        return $this->user_id === $userId;
    }

    /**
     * 未読かどうか
     */
    public function isUnread(): bool
    {
        return is_null($this->read_at);
    }

    /**
     * 画像付きかどうか
     */
    public function hasImage(): bool
    {
        return !empty($this->image);
    }
}
