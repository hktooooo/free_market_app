<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;

Route::get('/', [ItemController::class, 'index'])->name('index.show');
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');

Route::get('/register', [AuthController::class, 'show_register']);
Route::post('/register', [AuthController::class, 'store_user']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth')->group(function () {
    Route::get('/mypage', [AuthController::class, 'mypage'])->name('mypage');
    Route::get('/mypage/profile', [AuthController::class, 'mypage_edit'])->name('mypage_edit');
    Route::post('/item/comments', [ItemController::class, 'comments_store'])->name('comments.store');
    Route::get('/purchase/{item_id}', [ItemController::class, 'purchase_confirm'])->name('purchase.confirm');
    Route::get('/purchase/address/{item_id}', [ItemController::class, 'purchase_address'])->name('purchase.address');
    Route::post('/purchase/exec', [ItemController::class, 'purchase_exec'])->name('purchase.exec');
    Route::post('/address_update', [ItemController::class, 'address_update'])->name('address.update');
});


// Route::get('/purchase', function () {
//     return view('auth.purchase');
// });

// Route::get('/purchase/address', function () {
//     return view('auth.address');
// });

// Route::get('/item', function () {
//     return view('item');
// });



