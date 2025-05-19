<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Model_Administracion extends Model
{
    protected static $error = null;

    public static function obtenerUsuarios()
    {
        try {
            return DB::select('EXEC JCEUsuarioWeb');
        } catch (\Exception $e) {
            \Log::error("Error: " . $e->getMessage());
            static::setError('Error');
            return null;
        }
    }

    public static function obtenerMenus()
    {
        try {
            return DB::select('EXEC JCEMenuWebPrincipal');
        } catch (\Exception $e) {
            \Log::error("Error: " . $e->getMessage());
            static::setError('Error');
            return null;
        }
    }

    public static function listarAccesos($usuario, $menuPrincipal)
    {
        try {
            $resultado = \DB::select("EXEC JCEAccesosMenuWeb ?, ?", [$usuario, $menuPrincipal]);
            if (empty($resultado)) {
                return [];
            }
            return $resultado;

        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Administracion --- Método: listarAccesos --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);

            return null;
        }
    }



    protected static function setError($message)
    {
        static::$error = $message;
    }

    public static function getError()
    {
        return static::$error;
    }
}
