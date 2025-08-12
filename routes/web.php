<?php


use Illuminate\Support\Facades\Route;

// Authentication Routes...
Route::get('login', 'App\Http\Controllers\Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'App\Http\Controllers\Auth\LoginController@login');
Route::get('logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');


// Password Reset Routes...
Route::get('password/reset', 'App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'App\Http\Controllers\Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'App\Http\Controllers\Auth\ResetPasswordController@reset');

Route::get('/home/authorized-clients', 'App\Http\Controllers\HomeController@getAuthorizedClients')->name('authorized-clients');
Route::get('/home/my-clients', 'App\Http\Controllers\HomeController@getClients')->name('personal-clients');
Route::get('/home/my-tokens', 'App\Http\Controllers\HomeController@getTokens')->name('personal-tokens');
Route::get('/home', 'App\Http\Controllers\HomeController@index');


Route::get('/', function () {
    return view('welcome');
})->middleware('guest');
