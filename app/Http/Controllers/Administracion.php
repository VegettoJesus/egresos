<?php

namespace App\Http\Controllers;
use App\Models\Model_Administracion;

use Illuminate\Http\Request;

class Administracion extends Controller
{
    public function accesoAlSistema(Request $request)
    {
        if ($request->isMethod('post')) {
            $opcion = $request->input('opcion');
            $data = new \stdClass();
            switch ($opcion) {
                case 'Listar':
                    $usuario = $request->input('usuario');
                    $menuPrincipal = $request->input('menuPrincipal');
                    $accesos = Model_Administracion::listarAccesos($usuario, $menuPrincipal);

                    if ($accesos === null) {
                        $data->respuesta = 'error';
                        $data->accesos = Model_Administracion::getError();
                    } elseif (empty($accesos)) {
                        $data->respuesta = 'success';
                        $data->accesos = [];
                    } else {
                        $data->respuesta = 'success';
                        $data->accesos = $accesos;
                    }
                    break;

                case 'Eliminar':
                    break;

                default:
                    $data->respuesta = 'error';
                    $data->mensaje = 'Opción inválida';
                    break;
            }

            echo json_encode($data);
            exit();

        } else {
            $data = new \stdClass();
            $data->usuarios = Model_Administracion::obtenerUsuarios();
            $data->menuPrincipal = Model_Administracion::obtenerMenus();
            $data->script = 'js/administracion-accesoAlSistema.js';
            $data->css = 'css/administracion.css';
            $data->contenido = 'administracion.accesoAlSistema';

            return view('layouts.contenido', (array) $data);
        }
    }


    public function articulos(Request $request)
    {
        if ($request->isMethod('post')) {
            $opcion = $request->input('opcion');
            $data = new \stdClass();
            switch ($opcion) {
                case 'Listar':
                    break;

                default:
                    $data->respuesta = 'error';
                    $data->mensaje = 'Opción inválida';
                    break;
            }

            echo json_encode($data);
            exit();

        }else {
            $data = new \stdClass();
            $data->script = 'js/administracion-articulos.js';
            $data->css = 'css/administracion.css';
            $data->contenido = 'administracion.articulos';

            return view('layouts.contenido', (array) $data);
        }
    }

}
