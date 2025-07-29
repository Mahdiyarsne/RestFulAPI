<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



//خریدار
Route::resource('buyers', 'Buyer\BuyerController', ['only' => 'show', 'index']);

//دسته بندی
Route::resource('categories', 'Category\CategoryController', ['except' => 'create', 'edit']);

//محصولات
Route::resource('products', 'Product\ProductController', ['only' => 'show', 'index']);

//فروشنده ها
Route::resource('sellers', 'Seller\SellerController', ['only' => 'show', 'index']);

//پرداخت ها
Route::resource('transactions', 'Transaction\TransactionController', ['only' => 'show', 'index']);

//کاربران
Route::resource('users', 'User\UserController', ['only' => 'show', 'index']);
