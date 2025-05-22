<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Acceso extends Model
{
    protected static $error = null;

    public static function tienePermiso($usuario, $formulario, $opcion)
    {
        try {
            $resultado = DB::select('EXEC JCEAccesosPermisos ?, ?, ?', [
                $usuario,
                $formulario,
                $opcion,
            ]);
            if (empty($resultado)) {
                return [];
            }
            
            return $resultado;

        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Acceso --- Método: tienePermiso --- Error: ' . $e->getMessage();
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
