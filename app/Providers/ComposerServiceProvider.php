<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(
            ['layout.footer', 'index', 'admin.setting.option'],
            'App\Http\ViewComposers\ProfileComposer'
        );
        view()->composer(
            ['admin.layout.main', 'layout.app'],
            'App\Http\ViewComposers\TranslationComposer'
        );
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
