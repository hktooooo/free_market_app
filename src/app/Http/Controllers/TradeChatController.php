<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TradeChatController extends Controller
{
    public function tradechat_show(ChatRoom $room)
    {
        $userId = Auth::id();

        // 参加者チェック（不正アクセス防止）
        if (! $room->isParticipant($userId)) {
            abort(403);
        }

        // 商品・購入者・出品者・メッセージを取得
        $room->load([
            'product',
            'buyer',
            'seller',
        ]);

        $messages = $room->messages()
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        // ロール判定
        $isBuyer  = $room->buyer_id === $userId;
        $isSeller = $room->seller_id === $userId;

        // 既読処理（相手の未読メッセージを既読に）
        $room->messages()
            ->where('user_id', '!=', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // その他の取引取得
        $otherRooms = ChatRoom::with('product')
            ->where('is_completed', false)
            ->where('id', '!=', $room->id)
            ->where(function ($q) use ($userId) {
                $q->where('buyer_id', $userId)
                ->orWhere('seller_id', $userId);
            })
            ->get();

        return view('auth.tradechat', compact(
            'room',
            'messages',
            'isBuyer',
            'isSeller',
            'otherRooms'
        ));
    }

    /**
     * メッセージ投稿
     */
    public function tradechat_store(Request $request, ChatRoom $room)
    {
        $userId = Auth::id();

        // 参加者チェック
        if (! $room->isParticipant($userId)) {
            abort(403);
        }

        // バリデーション *****要件確認、あとで******
        $validated = $request->validate([
            'message' => 'nullable|string|max:1000',
            'image'   => 'nullable|image|max:2048', // 2MB
        ]);

        // メッセージと画像が両方空はNG
        if (empty($validated['message']) && ! $request->hasFile('image')) {
            return back()->withErrors([
                'message' => 'メッセージまたは画像を入力してください。',
            ]);
        }

        // 画像アップロード（あれば）
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('chat_images', 'public');
        }

        // メッセージ保存
        Message::create([
            'chat_room_id' => $room->id,
            'user_id'      => $userId,
            'message'      => $validated['message'] ?? null,
            'image'        => $imagePath,
        ]);

        return redirect()->route('tradechat.show', $room);
    }

    /**
     * メッセージ削除（自分の投稿のみ）
     */
    public function tradechat_destroy(Message $message)
    {
        $userId = Auth::id();

        // 自分のメッセージ以外は削除不可
        if ($message->user_id !== $userId) {
            abort(403);
        }

        // 画像があればストレージから削除
        if ($message->image) {
            Storage::disk('public')->delete($message->image);
        }

        $roomId = $message->chat_room_id;

        // メッセージ削除
        $message->delete();

        return redirect()->route('tradechat.show', $roomId);
    }

    /**
     * 出品者を評価（購入者が行う）
     */
    public function rating_store(Request $request, ChatRoom $room)
    {
        $request->validate([
            'seller_rating' => 'required|integer|min:1|max:5',
        ]);

        if (auth()->id() !== $room->buyer_id) {
            abort(403);
        }

        if ($room->seller_rating !== null) {
            abort(400, 'すでに評価済みです');
        }

        DB::transaction(function () use ($room, $request) {
            $room->update([
                'seller_rating' => (int) $request->seller_rating,
            ]);

            // 🔥 総合評価を再計算
            $room->seller->recalcAvgRating();
        });

        return back();
    }

    /**
     * 購入者を評価（購入者が行う）
     */
    public function rateBuyer(Request $request, ChatRoom $room)
    {
        $request->validate([
            'buyer_rating' => 'required|integer|min:1|max:5',
        ]);

        if (auth()->id() !== $room->seller_id) {
            abort(403);
        }

        if ($room->buyer_rating !== null) {
            abort(400, 'すでに評価済みです');
        }

        DB::transaction(function () use ($room, $request) {
            $room->update([
                'buyer_rating' => $request->buyer_rating,
            ]);

            // 🔥 総合評価を再計算
            $room->buyer->recalcAvgRating();
        });

        return back();
    }

}