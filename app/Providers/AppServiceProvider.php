<?php

namespace App\Providers;

use App\Mail\UserCreated;
use App\Mail\UserMailChanged;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Passport::tokensExpireIn(Carbon::now()->addMinute(30));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
        

        //
        Schema::defaultStringLength(191);

        User::created(function ($user) {

            Mail::to($user->email)->queue(new UserCreated($user));
        });


        User::updated(function ($user) {
            if ($user->isDirty('email')) {
                Mail::to($user->email)->queue(new UserMailChanged($user));
            }
        });


        Product::updated(function ($product) {
            if ($product->quantity == 0 && $product->isAvailable()) {
                $product->status = Product::UNAVAILABLE_PRODUCT;

                $product->save();
            }
        });
    }
}
