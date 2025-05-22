<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Menu;
use App\Models\Permisos;
use Illuminate\Support\Facades\Auth;
use App\Models\Acceso;
class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any view services.
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $menus = collect();
            $permiso_nuevo = false;
            $permiso_modificar = false;
            $permiso_eliminar = false;

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

                $rutaActual = request()->path();
                $menu = Menu::where('href', $rutaActual)->first();
                $idMenu = $menu ? $menu->id_menu : null;

                if ($idMenu) {
                    $permiso_nuevo     = Acceso::tienePermiso($userId, $idMenu, 'N');
                    $permiso_modificar = Acceso::tienePermiso($userId, $idMenu, 'U');
                    $permiso_eliminar  = Acceso::tienePermiso($userId, $idMenu, 'D');
                }
            }

            $view->with([
                'menus' => $menus,
                'permiso_nuevo' => $permiso_nuevo,
                'permiso_modificar' => $permiso_modificar,
                'permiso_eliminar' => $permiso_eliminar,
            ]);
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
