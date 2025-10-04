<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Purchase;
use App\Models\Payment;
use App\Models\Comment;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ExhibitionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab');
        $tab = empty($tab) ? 'recommend' : $tab;    // tabが空の場合は 'recommend'
        $userId = Auth::id();
        $conditions = Condition::all();
        $q = $request->input('q');

        if ($tab === 'mylist') {
            if ($userId) {
                // お気に入り商品
                $query = Auth::user()->favoriteProducts()->with('condition');
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

        // コメント一覧を取得（商品IDで絞り込み）
        $comments = Comment::with('user')
            ->where('product_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        // コメント総数
        $comments_count = $comments->count();

        return view('item', compact('product', 'categories', 'comments', 'comments_count'));
    }

    // いいね機能
    public function favorite_toggle($item_id)
    {
        $user = Auth::user();
        $product = Product::findOrFail($item_id);

        $isFavorited = $user->favoriteProducts()->where('product_id', $item_id)->exists();

        if ($isFavorited) {
            $user->favoriteProducts()->detach($item_id);
        } else {
            $user->favoriteProducts()->attach($item_id);
        }

        // 最新のいいね数を返す
        $favoriteCount = $product->favoritedByUsers()->count();

        return response()->json([
            'favorited' => !$isFavorited,
            'count' => $favoriteCount,
        ]);
    }

    // 商品詳細ページ表示 コメント投稿
    public function comments_store(CommentRequest $request)
    {
        Comment::create([
            'comment' => $request->comment,
            'product_id' => $request->product_id,
            'user_id' => Auth::id(),
        ]);

        return redirect()->back();
    }

    // 商品購入ページ表示
    public function purchase_confirm($id)
    {
        $user = Auth::user(); // ログインユーザー

        // 商品とログインユーザーの購入情報を取得
        $product = Product::with(['condition', 'purchases' => function($query) use ($user) {
                            $query->where('user_id', $user->id);
                            }])->findOrFail($id);
        $paymentMethods = Payment::all();

        // ログインユーザーの purchase があるかチェック
        $purchase = $product->purchases->first();

        if (!$purchase) {
            // purchase がない場合はユーザーの住所情報をもとにダミー purchase を作成
            $purchase = new Purchase([
                'zipcode_purchase'  => $user->zipcode,
                'address_purchase'  => $user->address,
                'building_purchase' => $user->building,
                'user_id'           => $user->id,
                'product_id'        => $product->id,
            ]);
        } else {
            // purchase が存在する場合、住所が null ならユーザー情報で補完
            if ($purchase->zipcode_purchase === null) {
                $purchase->zipcode_purchase = $user->zipcode;
            }
            if ($purchase->address_purchase === null) {
                $purchase->address_purchase = $user->address;
            }
            if ($purchase->building_purchase === null) {
                $purchase->building_purchase = $user->building;
            }
        }
    
        return view('auth.purchase', compact('product', 'paymentMethods', 'purchase'));
    }

    // 住所変更ページ表示
    public function purchase_address($id)
    {
        $user = Auth::user(); // ログインユーザー

        // 商品とログインユーザーの購入情報を取得
        $product = Product::with(['condition', 'purchases' => function($query) use ($user) {
                            $query->where('user_id', $user->id);
                            }])->findOrFail($id);

        // ログインユーザーの purchase があるかチェック
        $purchase = $product->purchases->first();

        if (!$purchase) {
            // purchase がない場合はユーザーの住所情報をもとにダミー purchase を作成
            $purchase = new Purchase([
                'zipcode_purchase'  => $user->zipcode,
                'address_purchase'  => $user->address,
                'building_purchase' => $user->building,
                'user_id'           => $user->id,
                'product_id'        => $product->id,
            ]);
        } else {
            // purchase が存在する場合、住所が null ならユーザー情報で補完
            if ($purchase->zipcode_purchase === null) {
                $purchase->zipcode_purchase = $user->zipcode;
            }
            if ($purchase->address_purchase === null) {
                $purchase->address_purchase = $user->address;
            }
            if ($purchase->building_purchase === null) {
                $purchase->building_purchase = $user->building;
            }
        }
        
        return view('auth.address', compact('product', 'purchase'));
    }

    // 住所変更の更新、データベースへの保存
    public function address_update(AddressRequest $request)
    {
        $purchaseId = $request->get('purchase_id'); // nullの場合もある
        $productId  = $request->get('product_id');  // 商品のID
        $userId = Auth::id(); // ログインユーザーID

        $data = [
            'zipcode_purchase'  => $request->get('zipcode'),
            'address_purchase'  => $request->get('address'),
            'building_purchase' => $request->get('building'),
        ];
        
        if ($purchaseId) {
            // 既存 purchase を更新
            $purchase = Purchase::where('id', $purchaseId)
                                ->where('user_id', $userId)
                                ->first();

            if ($purchase) {
                $purchase->update($data);
            } else {
                // ID が存在しない場合は updateOrCreate で新規作成
                $purchase = Purchase::updateOrCreate(
                    ['user_id' => $userId, 'product_id' => $productId], // 条件
                    $data                                               // 更新/作成する値
                );
            }
        } else {
            // 新規作成
            $purchase = Purchase::updateOrCreate(
                ['user_id' => $userId, 'product_id' => $productId], // 条件
                $data                                               // 更新/作成する値
            );
        }

        return redirect()->route('purchase.confirm', ['item_id' => $productId]);
    }

    // 商品出品時のページ表示
    public function sell_show(){
    
        $categories = Category::all();
        $conditions = Condition::all();
    
        return view('auth.sell', compact('categories', 'conditions'));
    }

    // 商品出品時のデータ登録
    public function sell_exec(ExhibitionRequest $request){
    
        $userId = Auth::id(); // ログインユーザーID

        // 画像アップロード処理
        $file     = $request->file('img_url');
        $filename = $file->hashName();    // 自動的にユニークなファイル名を生成
        $path     = $file->storeAs('product_images', $filename, 'public');

        // 商品を作成
        $product = Product::create([
            'product_name'         => $request->product_name,
            'price'                => $request->price,
            'brand'                => $request->brand,
            'detail'               => $request->detail,
            'img_url'              => $path,
            'condition_id'         => $request->condition_id,
            'seller_id'            => $userId,
        ]);

        // チェックボックスで選ばれた categories_id を中間テーブルに保存
        if ($request->has('categories')) {
            $product->categories()->attach($request->categories);
        }

        return redirect('/');
    }
}