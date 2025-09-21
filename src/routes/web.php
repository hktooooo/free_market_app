<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PurchaseProductController;

Route::get('/', [ItemController::class, 'index'])->name('index.show');
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');

Route::get('/register', [AuthController::class, 'show_register']);
Route::post('/register', [AuthController::class, 'store_user']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/email/verify', [AuthController::class, 'verifyNotice'])->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/resend', [AuthController::class, 'resendVerification'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mypage', [AuthController::class, 'mypage'])->name('mypage.show');
    Route::get('/mypage/profile', [AuthController::class, 'mypage_edit'])->name('mypage.edit');
    Route::post('/mypage/profile/update', [AuthController::class, 'mypage_update'])->name('mypage.update');
    Route::post('/item/comments', [ItemController::class, 'comments_store'])->name('comments.store');
    Route::post('/item/toggle/{item_id}', [ItemController::class, 'favorite_toggle'])->name('favorite.toggle');
    Route::get('/purchase/{item_id}', [ItemController::class, 'purchase_confirm'])->name('purchase.confirm');
    Route::get('/purchase/address/{item_id}', [ItemController::class, 'purchase_address'])->name('purchase.address');
    Route::post('/address_update', [ItemController::class, 'address_update'])->name('address.update');
    Route::get('/sell', [ItemController::class, 'sell_show'])->name('sell.show');
    Route::post('/sell/exec', [ItemController::class, 'sell_exec'])->name('sell.exec');

    Route::post('/purchase/exec', [PurchaseProductController::class, 'purchase_exec'])->name('purchase.exec');
    Route::get('/success/{item_id}', [PurchaseProductController::class, 'success'])->name('product.success');
    Route::get('/cancel', [PurchaseProductController::class, 'cancel'])->name('product.cancel');
});

Route::post('/webhook/stripe', [PurchaseProductController::class, 'webhook'])
     ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class])
     ->name('stripe.webhook');

// Route::get('/sell', function () {
//      return view('auth.sell');
// });

// Route::get('/purchase/address', function () {
//     return view('auth.address');
// });

// Route::get('/item', function () {
//     return view('item');
// });



