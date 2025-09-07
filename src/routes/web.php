<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;

Route::get('/', [ItemController::class, 'index']);
Route::get('/item/{id}', [ItemController::class, 'show'])->name('item.show');;

Route::get('/register', [AuthController::class, 'show_register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'store_user']);
    Route::post('/', [ItemController::class, 'index']);
});

// Route::get('/', [ItemController::class, 'index']);

// Route::get('/login', function () {
//     return view('auth.login');
// });

// Route::get('/register', function () {
//     return view('auth.register');
// });

// Route::get('/mypage', function () {
//     return view('auth.mypage');
// });

Route::get('/item', function () {
    return view('item');
});



