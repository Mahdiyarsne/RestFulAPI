<?php

use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Buyer\BuyerController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Category\CategoryProductController;
use App\Http\Controllers\Category\CategorySellerController;
use App\Http\Controllers\Category\CategoryTransactionController;
use App\Http\Controllers\Category\CategoryBuyerController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Seller\SellerController;
use App\Http\Controllers\Transaction\TransactionController;
use App\Http\Controllers\Transaction\TransactionCategoryController;
use App\Http\Controllers\Transaction\TransactionSellerController;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



//خریدار
Route::resource('buyers', BuyerController::class, ['only' => ['show', 'index']]);

//دسته بندی
Route::resource('categories', CategoryController::class, ['except' => ['create', 'edit']]);
Route::resource('categories.products', CategoryProductController::class, ['only' => ['index']]);
Route::resource('categories.sellers', CategorySellerController::class, ['only' => ['index']]);
Route::resource('categories.transactions', CategoryTransactionController::class, ['only' => ['index']]);
Route::resource('categories.buyers', CategoryBuyerController::class, ['only' => ['index']]);

//محصولات
Route::resource('products', ProductController::class, ['only' => ['show', 'index']]);

//فروشنده ها
Route::resource('sellers', SellerController::class, ['only' => ['show', 'index']]);

//پرداخت ها
Route::resource('transactions', TransactionController::class, ['only' => ['show', 'index']]);
Route::resource('transactions.categories', TransactionCategoryController::class, ['only' => ['index']]);
Route::resource('transactions.sellers', TransactionSellerController::class, ['only' => ['index']]);
//کاربران
Route::resource('users', UserController::class, ['except' => ['create', 'edit']]);
