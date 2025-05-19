<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Menu;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any view services.
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $menus = Menu::where('nivel', 1)->with('hijos')->orderBy('orden')->get();
            $view->with('menus', $menus);
        });
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }
}
