<?php

namespace App\Http\Controllers;
use App\Models\Model_Administracion;
use Illuminate\Support\Facades\Validator;
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
                case 'Registrar':
                    $validator = Validator::make($request->all(), [
                        'usuario' => 'required',
                        'menuA' => 'required',
                    ], [
                        'usuario.required' => 'Debe seleccionar un usuario.',
                        'menuA.required' => 'Debe seleccionar un menú.',
                    ]);

                    if ($validator->fails()) {
                        return back()->withErrors($validator, 'formregistrarAcceso')->withInput();
                    }

                    $params = [
                        'id_menu' => $request->input('menuA'),
                        'id_usuario' => $request->input('usuario'),
                        'Nuevo' => $request->has('permiso_nuevo') ? 1 : 0,
                        'Modificar' => $request->has('permiso_modificar') ? 1 : 0,
                        'Eliminar' => $request->has('permiso_eliminar') ? 1 : 0,
                    ];

                    $resultado = Model_Administracion::registrarAsignarAcceso($params);

                    if ($resultado === true) {
                        return redirect()->back()->with('success', 'Se asignó el acceso correctamente.');
                    } else {
                        return back()->withErrors('Hubo un error al asignar el acceso', 'formregistrarAcceso')->withInput();
                    }
                    break;

                case 'eliminar':
                    $id = $request->input('id');
                    $eliminar = Model_Administracion::eliminarAcceso($id);
                    if ($eliminar === true) {
                        $data->respuesta = 'success';
                    } else {
                        $data->respuesta = 'error';
                        $data->eliminar = Model_Administracion::getError();
                    }
                    break;
                case 'ObtenerData':
                    $id_menu = $request->input('id_menu');
                    $id_usuario = $request->input('id_usuario');
                    $acceso = Model_Administracion::obtenerAcceso($id_menu,$id_usuario);
                    
                    if ($acceso === null) {
                        $data->respuesta = 'error';
                        $data->data = Model_Administracion::getError();
                    } elseif (empty($acceso)) {
                        $data->respuesta = 'success';
                        $data->data = [];
                    } else {
                        $data->respuesta = 'success';
                        $data->data = $acceso;
                    }
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
            $data->listUsuarios = Model_Administracion::listaUsuarios();
            $data->listMenus = Model_Administracion::listaMenus();
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
