<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Condition;
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

        return view('item', compact('product'));
    }

    // 商品詳細ページ表示
    public function purchase_confirm($id)
    {
        // id指定で1件取得
        $product = Product::with('condition')->findOrFail($id);

        return view('auth.purchase', compact('product'));
    }
}