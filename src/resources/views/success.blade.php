@if($product->buyer_payment_method === 'konbini' && $product->buyer_payment_status === 'pending')
    <h2>コンビニ支払い情報</h2>
    <p>支払いが完了するまで商品は確定されません。</p>
    <p>Stripeダッシュボードまたはセッション情報から「受付番号」を確認してください。</p>
@else
    <h2>購入が完了しました！</h2>
    <p>住所: {{ $product->buyer_address }}</p>
@endif