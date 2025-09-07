<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Condition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index()
    {
        $products = Product::with('condition')->get();
        $conditions = Condition::all();
        $userId = Auth::id();

        return view('index', compact('products', 'conditions', 'userId'));
    }

    // 商品詳細ページ表示
    public function show($id)
    {
        // id指定で1件取得
        $product = Product::with('condition')->findOrFail($id);

        return view('item', compact('product'));
    }
}