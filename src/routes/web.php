<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;

Route::get('/', [ItemController::class, 'index'])->name('index.show');
Route::get('/item/{id}', [ItemController::class, 'show'])->name('item.show');

Route::get('/register', [AuthController::class, 'show_register']);
Route::post('/register', [AuthController::class, 'store_user']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth')->group(function () {
    Route::get('/mypage', [AuthController::class, 'mypage'])->name('mypage');
    Route::get('/mypage/profile', [AuthController::class, 'mypage_edit'])->name('mypage_edit');
    Route::get('/purchase/{id}', [ItemController::class, 'purchase_confirm'])->name('purchase.confirm');
});

Route::get('/purchase', function () {
    return view('auth.purchase');
});

// Route::get('/item', function () {
//     return view('item');
// });



