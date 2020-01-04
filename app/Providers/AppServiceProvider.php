<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('admin.layouts.menu', 'App\Http\ViewComposers\AdminMenuComposer'); // 视图合成器
        view()->composer('backend.sections.header', 'App\Http\ViewComposers\Backend\ApplicationMenuComposer');
    }
}
