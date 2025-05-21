<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Menu;
use App\Models\Permisos;
use Illuminate\Support\Facades\Auth;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any view services.
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $menus = collect(); 

            if (Auth::check()) {
                $userId = Auth::user()->CodUsuario;

                $permisosIds = Permisos::where('id_usuario', $userId)->pluck('id_menu')->toArray();

                if (!empty($permisosIds)) {
                    $menus = Menu::where('nivel', 1)
                        ->whereIn('id_menu', $permisosIds)
                        ->with(['hijos' => function($query) use ($permisosIds) {
                            $query->whereIn('id_menu', $permisosIds);
                        }])
                        ->orderBy('orden')
                        ->get();
                }
            }
            
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
