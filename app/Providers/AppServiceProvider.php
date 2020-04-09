<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Schema\Builder;
use App\Eloquent\User;
use App\Eloquent\Notification;
use App\Eloquent\Office;
use Auth;
use URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {   
//        URL::forceScheme('https');
        Builder::defaultStringLength(191);

        view()->composer('layout.header', function ($view) {
            if (Auth::check()) {
                $view->with('roles', User::getRoles(Auth::id()));
                $view->with('new', Notification::countNew(Auth::id()));
            } else {
                $view->with('roles', null);
            }
            $view->with('offices', Office::getAll());
        });
        view()->composer('*', function ($view) {
            if (Auth::check()) {
                $view->with('bookAboutToExpire', Auth::user()->bookAboutToExpire());
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
