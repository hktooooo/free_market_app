<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PurchaseProductController extends Controller
{
    // 商品購入時のデータ登録
    public function purchase_exec(PurchaseRequest $request){

        $productId = $request->input('product_id');  // 商品のID
        $userId = Auth::id(); // ログインユーザーID
        $product = Product::with('condition')->findOrFail($productId);

        $paymentMethod = $request->input('payment_method'); // 'card' or 'konbini'

        // Stripe セッション作成
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = CheckoutSession::create([
            'payment_method_types' => [$paymentMethod],
            'mode' => 'payment',
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => ['name' => $product->product_name],
                    'unit_amount' => intval($product->price),
                ],
                'quantity' => 1,
            ]],
            'success_url' => route('product.success', ['item_id' => $product->id]) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('product.cancel'),

            'metadata' => [
                'product_id' => $product->id,
                'user_id'    => $userId,
                'zipcode'    => $request->input('zipcode'),
                'address'    => $request->input('address'),
                'building'   => $request->input('building'),
                'payment_method' => $paymentMethod,
            ],
        ]);

        if ($paymentMethod === 'konbini') {
            // DB 更新
            $product->buyer_id             = $userId;
            $product->buyer_zipcode        = $request->input('zipcode');
            $product->buyer_address        = $request->input('address');
            $product->buyer_building       = $request->input('building');
            $product->buyer_payment_method = $paymentMethod;
            $product->buyer_payment_status = 'pending';
            $product->save();
        }

        return redirect($session->url);
    }

    // 成功
    public function success(Request $request, Product $product)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $sessionId = $request->get('session_id');
        $session   = \Stripe\Checkout\Session::retrieve($sessionId);

        $product = Product::findOrFail($session->metadata->product_id);

        $paymentMethod = $session->metadata->payment_method;

        if ($paymentMethod === 'card') {
            // DB 更新
            $product->buyer_id             = $session->metadata->user_id;
            $product->buyer_zipcode        = $session->metadata->zipcode;
            $product->buyer_address        = $session->metadata->address;
            $product->buyer_building       = $session->metadata->building;
            $product->buyer_payment_method = $paymentMethod;
            $product->buyer_payment_status = 'succeeded';
            $product->save();
        }

        return view('success', compact('product'));
    }
    
    // キャンセル
    public function cancel()
    {
        return "購入をキャンセルしました";
    }
}
