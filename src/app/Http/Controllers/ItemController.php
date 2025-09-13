<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Condition;
use App\Models\Payment;
use App\Models\Comment;
use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'recommend');
        $userId = Auth::id();
        $conditions = Condition::all();
        $q = $request->input('q');

        if ($tab === 'mylist') {
            if ($userId) {
                // お気に入り商品
                $query = Auth::user()->favoriteProducts()
                                    ->wherePivot('favorite', true)
                                    ->with('condition');
            } else {
                // ログインしていない場合は空のコレクション
                $products = collect();
            }
        } else {
            // 通常の商品一覧
            $query = Product::with('condition');
        }

        // 検索機能
        if (isset($query) && $q = $request->input('q')) {
            $query->where('product_name', 'like', "%{$q}%");
        }

        if (!isset($products)) {
            $products = $query->get();
        }

        return view('index', compact('products', 'conditions', 'userId', 'q', 'tab'));
    }

    // 商品詳細ページ表示
    public function show($id)
    {
        // id指定で1件取得
        $product = Product::with('condition')->findOrFail($id);

        // カテゴリ名の配列を取得
        $categories = $product->categories->pluck('content')->toArray();

        // いいねの数をカウント
        $favorite_count = $product->favoritedByUsers()->count();

        // コメント一覧を取得（商品IDで絞り込み）
        $comments = Comment::with('user')
            ->where('product_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        // コメント総数
        $comments_count = $comments->count();

        return view('item', compact('product', 'categories', 'favorite_count', 'comments', 'comments_count'));
    }

    // 商品詳細ページ表示 コメント投稿
    public function comments_store(CommentRequest $request)
    {
        Comment::create([
            'comment' => $request->comment,
            'product_id' => $request->product_id,
            'user_id' => $request->user_id,
        ]);

        return redirect()->back();
    }

    // 商品購入ページ表示
    public function purchase_confirm($id)
    {
        // id指定で1件取得
        $product = Product::with('condition', 'paymentMethod')->findOrFail($id);
        $paymentMethods = Payment::all();

        if($product->zipcode_purchase === null){
            $product->zipcode_purchase = Auth::user()->zipcode;
        }

        if($product->address_purchase === null){
            $product->address_purchase = Auth::user()->address;
        }
        
        if($product->building_purchase === null){
            $product->building_purchase = Auth::user()->building;
        }
        
        return view('auth.purchase', compact('product', 'paymentMethods'));
    }

    // 住所変更ページ表示
    public function purchase_address($id)
    {
        // id指定で1件取得
        $product = Product::with('condition')->findOrFail($id);

        if($product->zipcode_purchase === null){
            $product->zipcode_purchase = Auth::user()->zipcode;
        }

        if($product->address_purchase === null){
            $product->address_purchase = Auth::user()->address;
        }
        
        if($product->building_purchase === null){
            $product->building_purchase = Auth::user()->building;
        }
        
        return view('auth.address', compact('product'));
    }

    // 住所変更の更新、データベースへの保存
    public function address_update(Request $request)
    {
        $id = $request->get('item_id');

        // id指定で1件取得
        $product = Product::with('condition', 'paymentMethod')->findOrFail($id);
        $paymentMethods = Payment::all();

        // リクエストの値で上書き
        $product->zipcode_purchase = $request->get('zipcode');
        $product->address_purchase = $request->get('address');
        $product->building_purchase = $request->get('building');

        // DBに保存
        $product->save();

        return view('auth.purchase', compact('product', 'paymentMethods'));
    }

}