<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Model_Compras extends Model
{
    protected static $error = null;

    public static function listarProveedores($tipo_Persona, $proveedor)
    {
        try {
            $resultado = \DB::select("EXEC JCEProveedorBuscar3 ?, ?", [$tipo_Persona, $proveedor]);
            if (empty($resultado)) {
                return [];
            }
            return $resultado;

        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: listarProveedores --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);

            return null;
        }
    }

    public static function listarOCPagos($FI, $FF, $razonSocial, $sede, $pagos, $codemp)
    {
        try {
            $resultado = \DB::select("EXEC JCEOCBuscar ?, ?, ?, ?, ?, ?", [$FI, $FF, $razonSocial, $sede, $pagos, $codemp]);
            if (empty($resultado)) {
                return [];
            }
            return $resultado;

        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: listarOCPagos --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);

            return null;
        }
    }

    public static function listarRazonSocial($razon_social)
    {
        try {
            $resultado = \DB::select("EXEC JCEProveedorBuscarXAgenda ?", [$razon_social]);
            if (empty($resultado)) {
                return [];
            }
            return $resultado;

        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: listarRazonSocial --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);

            return null;
        }
    }

    public static function listaSedes()
    {
        try {
            $resultado = \DB::select("EXEC JCELocales", []);
            if (empty($resultado)) {
                return [];
            }
            return $resultado;

        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: listaSedes --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);

            return null;
        }
    }

    public static function listaSedes2($emp)
    {
        try {
            $resultado = \DB::select("EXEC JCELocales3 ?", [$emp]);
            if (empty($resultado)) {
                return [];
            }
            return $resultado;

        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: listaSedes2 --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);

            return null;
        }
    }

    public static function listaPaises()
    {
        try {
            $resultado = \DB::select("EXEC JCEPaises", []);
            if (empty($resultado)) {
                return [];
            }
            return $resultado;

        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: listaPaises --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);

            return null;
        }
    }

    public static function listaEmpresas()
    {
        try {
            $resultado = \DB::select("EXEC JCEOCEmpresa2", []);
            if (empty($resultado)) {
                return [];
            }
            return $resultado;

        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: listaEmpresas --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);

            return null;
        }
    }

    public static function listaDepartamentos($pais)
    {
        try {
            $resultado = \DB::select("EXEC JCEDepartamentos ?", [$pais]);
            if (empty($resultado)) {
                return [];
            }
            return $resultado;

        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: listaDepartamentos --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);

            return null;
        }
    }

    public static function listaProvincias($dpto)
    {
        try {
            $resultado = \DB::select("EXEC JCEProvincias ?", [$dpto]);
            if (empty($resultado)) {
                return [];
            }
            return $resultado;

        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: listaProvincias --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);

            return null;
        }
    }

    public static function listaDistritos($provincia)
    {
        try {
            $resultado = \DB::select("EXEC JCEDistritos ?", [$provincia]);
            if (empty($resultado)) {
                return [];
            }
            return $resultado;

        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: listaDistritos --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);

            return null;
        }
    }

    public static function registrarProveedor($params)
    {
        try {
            \DB::statement("EXEC JCEgresosOCProveedores 
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?",
                [
                    $params['CodProv'],
                    $params['Tipo'],
                    $params['RazonSocial'],
                    $params['RUC'],
                    $params['idpais'],
                    $params['iddpto'],
                    $params['idprov'],
                    $params['iddto'],
                    $params['Direccion'],
                    $params['Telefono'],
                    $params['Celular'],
                    $params['Correo'],
                    $params['Estado'],
                    $params['Usuarioreg'],
                    $params['TipoDoc'],
                    $params['Documento'],
                    $params['Apellidos'],
                    $params['Nombres'],
                    $params['Observaciones'],
                ]
            );

            return true;

        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: registrarProveedor --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);

            return false;
        }
    }

    protected static function eliminarProveedor($op){
        try {
            \DB::statement("EXEC JCEgresosOCProveedoresEliminar ?", [$op]);
            return true;

        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: eliminarProveedor --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);

            return false;
        }
    }

    protected static function eliminarOrdenServicio($op){
        try {
            \DB::statement("EXEC JCEOCEliminar ?", [$op]);
            return true;

        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: eliminarOrdenServicio --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);

            return false;
        }
    }

    public static function obtenerProveedor($op)
    {
        try {
            $resultado = \DB::select("EXEC JCEgresosOCProveedoresActualizar ?", [$op]);
            if (empty($resultado)) {
                return [];
            }
            return $resultado;
            
        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: obtenerProveedor --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);

            return null;
        }
    }

    public static function ObtenerSerieNumero()
    {
        try {
            $resultado = \DB::select("EXEC JCEDocumentoSeries");
    
            if (empty($resultado)) {
                return null;
            }
    
            return $resultado[0]; 
    
        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') .
                ' --- Model: Compras --- Método: ObtenerSerieNumero --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);
    
            return null;
        }
    }
    
    public static function listaSolicitantes()
    {
        try {
            $resultado = \DB::select("EXEC JCEEgresosEmpleaodos", []);
            if (empty($resultado)) {
                return [];
            }
            return $resultado;

        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: listaSolicitantes --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);

            return null;
        }
    }

    public static function listaAutorizantes()
    {
        try {
            $resultado = \DB::select("EXEC JCEEgresosEmpleadosGerencia", []);
            if (empty($resultado)) {
                return [];
            }
            return $resultado;

        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: listaAutorizantes --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);

            return null;
        }
    }

    public static function listaFormaPago()
    {
        try {
            $resultado = \DB::select("EXEC JCEEgresosFormaPago", []);
            if (empty($resultado)) {
                return [];
            }
            return $resultado;

        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: listaFormaPago --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);

            return null;
        }
    }

    public static function registrarOrdenServ1($params)
    {
        try {
            \DB::statement("EXEC JCEgresosOCGrabar ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?",
            [
                $params['Op'],
                $params['empresaR'],
                $params['fechaR'],
                $params['serieR'],
                $params['numeroR'],
                $params['proveedorR'],
                $params['solicitanteR'],
                $params['autorizanteR'],
                $params['formaPagoR'],
                $params['fechaPagoR'],
                $params['sedeR'],
                $params['tipoBienR'],
                $params['total'],
                $params['subtotal'],
                $params['igv'],
                $params['codDetraccion'],
                $params['detraccion'],
                $params['observacionesR'],
                $params['Usuarioreg'],
                $params['monedaR'],
            ]
        );
            return true;

        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: registrarOrdenServ1 --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);

            return false;
        }
    }

    public static function registrarOrdenServ2($params2)
    {
        try {
            foreach ($params2 as $linea) {
                \DB::statement("EXEC JCEgresosOCGrabarLinea ?,?,?,?,?,?,?",
                    [
                        $linea['IDL'],
                        $linea['Op'],
                        $linea['item'],
                        $linea['Descripcion'],
                        $linea['Cantidad'],
                        $linea['Precio'],
                        $linea['Importe'],
                    ]
                );
            }
            return true;

        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: registrarOrdenServ2 --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);

            return false;
        }
    }

    public static function obtenerUltimoOpOC()
    {
        try {
            $resultado = \DB::select("SELECT TOP 1 Op FROM tbltblEgresosOC ORDER BY Op DESC");
            return $resultado ? $resultado[0]->Op : null;

        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: obtenerUltimoOpOC --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);
            return null;
        }
    }

    public static function obtenerDataOrden($op)
    {
        try {
            $resultado = \DB::select("EXEC JCEOCActualizar ?", [$op]);
            if (empty($resultado)) {
                return [];
            }
            return $resultado;
            
        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: obtenerDataOrden --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);

            return null;
        }
    }

    public static function obtenerDataOrdenDetalle($op)
    {
        try {
            $resultado = \DB::select("EXEC JCEOCLineaActualizar ?", [$op]);
            if (empty($resultado)) {
                return [];
            }
            return $resultado;
            
        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: obtenerDataOrdenDetalle --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);

            return null;
        }
    }

    public static function obtenerProveedorPorCod($codProv)
    {
        try {
            $resultado = \DB::select("SELECT CodProv, RazonSocial, RUC FROM tblEgresosProveedores WHERE CodProv = ?", [$codProv]);
            if (empty($resultado)) {
                return [];
            }
            return $resultado;

        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: obtenerProveedorPorCod --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);

            return null;
        }
    }

    protected static function eliminarOCDetalle($op){
        try {
            \DB::statement("EXEC JCEgresosOCLineaEliminar ?", [$op]);
            return true;

        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: eliminarOCDetalle --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);

            return false;
        }
    }

    public static function listaTipoPagos()
    {
        try {
            $resultado = \DB::select("EXEC JCETipoPagos", []);
            if (empty($resultado)) {
                return [];
            }
            return $resultado;

        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: listaTipoPagos --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);

            return null;
        }
    }

    public static function listaBancos()
    {
        try {
            $resultado = \DB::select("EXEC JCEBancos", []);
            if (empty($resultado)) {
                return [];
            }
            return $resultado;

        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: listaBancos --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);

            return null;
        }
    }

    public static function pagoPendienteServicio($op)
    {
        try {
            $resultado = \DB::select("EXEC JCEOCPagos ?", [$op]);
            if (empty($resultado)) {
                return [];
            }
            return $resultado;

        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: pagoPendienteServicio --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);

            return null;
        }
    }

    public static function registrarPagoOrdenSer($params)
    {
        try {
            $result = \DB::select("SET NOCOUNT ON; EXEC JCEgresosOCPagosGrabar ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?",
            [
                $params['IDPago'],
                $params['Op'],
                $params['FechaPago'],
                $params['TipoPago'],
                $params['codBanco'],
                $params['Operacion'],
                $params['Autorizado'],
                $params['Pendiente'],
                $params['Importe'],
                $params['DetracPorc'],
                $params['Detraccion'],
                $params['Total'],
                $params['Usuarioreg'],
                $params['Observacion'],
                $params['Id_Moneda']
            ]);
            
            return isset($result[0]->IDPago) ? $result[0]->IDPago : false;

        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: registrarPagoOrdenSer --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);
            return false;
        }
    }

    public static function detalleXproveedor($op)
    {
        try {
            $resultado = \DB::select("EXEC JCEPagosXOCXOp ?", [$op]);
            if (empty($resultado)) {
                return [];
            }
            return $resultado;
            
        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: detalleXproveedor --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);

            return null;
        }
    }

    public static function listarDetallePDF($op)
    {
        try {
            $resultado = \DB::select("EXEC JCEOCBuscarXOp ?", [$op]);
            if (empty($resultado)) {
                return [];
            }
            return $resultado;
            
        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: listarDetallePDF --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);

            return null;
        }
    }

    public static function listarPagosOS($FI, $FF, $razonSocial, $sede)
    {
        try {
            $resultado = \DB::select("EXEC JCEOCPagosBuscar ?, ?, ?, ?", [$FI, $FF, $razonSocial, $sede]);
            if (empty($resultado)) {
                return [];
            }
            return $resultado;

        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: listarPagosOS --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);

            return null;
        }
    }

    public static function obtenerPagoxOp($op)
    {
        try {
            $resultado = \DB::select("EXEC JCEOCPagosActualizar ?", [$op]);
            if (empty($resultado)) {
                return [];
            }
            return $resultado;
            
        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: obtenerPagoxOp --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);

            return null;
        }
    }

    public static function eliminarPagoOS($op){
        try {
            \DB::statement("EXEC JCEOCPagosEliminar ?", [$op]);
            return true;

        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: eliminarPagoOS --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);

            return false;
        }
    }

    public static function listarDetallePagoPDF($op)
    {
        try {
            $resultado = \DB::select("EXEC JCEPagosXOC ?", [$op]);
            if (empty($resultado)) {
                return [];
            }
            return $resultado;
            
        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: listarDetallePagoPDF --- Error: ' . $e->getMessage();
            \Log::error($errorDetalle);
            static::setError($errorDetalle);

            return null;
        }
    }

    public static function listarTablaDetallePagoPDF($op)
    {
        try {
            $resultado = \DB::select("EXEC JCEOCPagoLineaActualizar ?", [$op]);
            if (empty($resultado)) {
                return [];
            }
            return $resultado;
            
        } catch (\Exception $e) {
            $errorDetalle = now()->format('d/m/Y H:i:s') . 
                ' --- Model: Compras --- Método: listarTablaDetallePagoPDF --- Error: ' . $e->getMessage();
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
