<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('user._user', 'App\Http\ViewComposers\UserComposer');
        view()->composer('layouts.header', 'App\Http\ViewComposers\HeaderComposer');
        view()->composer('layouts.footer', 'App\Http\ViewComposers\FooterComposer');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
