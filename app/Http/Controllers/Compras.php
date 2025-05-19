<?php

namespace App\Http\Controllers;
use App\Models\Model_Compras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Storage; 

class Compras extends Controller
{
    public function proveedores(Request $request)
    {
        if ($request->isMethod('post')) {
            $opcion = $request->input('opcion');
            $data = new \stdClass();

            switch ($opcion) {
                case 'Listar':
                    $tipo_Persona = $request->input('tipo_Persona');
                    $proveedor = $request->input('proveedor');
                    $listar = Model_Compras::listarProveedores($tipo_Persona, $proveedor);

                    if ($listar === null) {
                        $data->respuesta = 'error';
                        $data->listar = Model_Compras::getError();
                    } elseif (empty($listar)) {
                        $data->respuesta = 'success';
                        $data->listar = [];
                    } else {
                        $data->respuesta = 'success';
                        $data->listar = $listar;
                    }
                    break;

                case 'paises':
                    $paises = Model_Compras::listaPaises();
                    if ($paises === null) {
                        $data->respuesta = 'error';
                        $data->paises = Model_Compras::getError();
                    } elseif (empty($paises)) {
                        $data->respuesta = 'success';
                        $data->paises = [];
                    } else {
                        $data->respuesta = 'success';
                        $data->paises = $paises;
                    }
                    break;

                case 'departamentos':
                    $pais = $request->input('pais');
                    $departamentos = Model_Compras::listaDepartamentos($pais);
                    if ($departamentos === null) {
                        $data->respuesta = 'error';
                        $data->departamentos = Model_Compras::getError();
                    } elseif (empty($departamentos)) {
                        $data->respuesta = 'success';
                        $data->departamentos = [];
                    } else {
                        $data->respuesta = 'success';
                        $data->departamentos = $departamentos;
                    }
                    break;

                case 'provincias':
                    $dpto = $request->input('departamento');
                    $provincias = Model_Compras::listaProvincias($dpto);
                    if ($provincias === null) {
                        $data->respuesta = 'error';
                        $data->provincias = Model_Compras::getError();
                    } elseif (empty($provincias)) {
                        $data->respuesta = 'success';
                        $data->provincias = [];
                    } else {
                        $data->respuesta = 'success';
                        $data->provincias = $provincias;
                    }
                    break;

                case 'distritos':
                    $provincia = $request->input('provincia');
                    $distritos = Model_Compras::listaDistritos($provincia);
                    if ($distritos === null) {
                        $data->respuesta = 'error';
                        $data->distritos = Model_Compras::getError();
                    } elseif (empty($distritos)) {
                        $data->respuesta = 'success';
                        $data->distritos = [];
                    } else {
                        $data->respuesta = 'success';
                        $data->distritos = $distritos;
                    }
                    break;
                case 'Registrar':
                    
                $validator = Validator::make($request->all(), [
                    'tipo_PersonaR' => 'required|integer',
                    'razonSocialR' => 'nullable|string|max:255',
                    'rucR' => 'nullable|integer|digits:11',
                    'paisR' => 'required|integer',
                    'departamentoR' => 'required|integer',
                    'provinciaR' => 'required|integer',
                    'distritoR' => 'required|integer',
                    'direccionR' => 'required|string|max:450',
                    'telefonoR' => 'nullable|string|max:150',
                    'celularR' => 'nullable|string|max:150',
                    'correoR' => 'nullable|email|max:150',
                    'estadoR' => 'required|boolean',
                    'observacionesR' => 'nullable|string|max:350',
                    'tipoDocR' => 'nullable|integer',
                    'docR' => 'nullable|integer',
                    'apellidosR' => 'nullable|string|max:350',
                    'nombresR' => 'nullable|string|max:350',
                ], [
                    'tipo_PersonaR.required' => 'El tipo de persona es obligatorio.',
                    'tipo_PersonaR.integer' => 'El tipo de persona debe ser un número.',
                    'razonSocialR.max' => 'La razón social no debe superar los 255 caracteres.',
                    'rucR.digits' => 'El RUC no debe superar los 11 caracteres.',
                    'paisR.required' => 'El país es obligatorio.',
                    'departamentoR.required' => 'El departamento es obligatorio.',
                    'provinciaR.required' => 'La provincia es obligatoria.',
                    'distritoR.required' => 'El distrito es obligatorio.',
                    'direccionR.required' => 'La dirección es obligatoria.',
                    'direccionR.max' => 'La dirección no debe superar los 450 caracteres.',
                    'telefonoR.max' => 'El teléfono no debe superar los 150 caracteres.',
                    'celularR.max' => 'El celular no debe superar los 150 caracteres.',
                    'correoR.email' => 'El correo debe ser un email válido.',
                    'correoR.max' => 'El correo no debe superar los 150 caracteres.',
                    'estadoR.required' => 'El estado es obligatorio.',
                    'observacionesR.max' => 'Las observaciones no debe superar los 350 caracteres.',
                    'tipoDocR.integer' => 'El tipo de documento debe ser un número.',
                    'docR.integer' => 'El documento debe ingresar número.',
                    'apellidosR.max' => 'Los apellidos no deben superar los 350 caracteres.',
                    'nombresR.max' => 'Los nombres no deben superar los 350 caracteres.',
                ]);

                $usuarioLogueado = Auth::user();
                $params = [
                    'CodProv' => $request->input('CodProv'),
                    'Tipo' => $request->input('tipo_PersonaR'),
                    'RazonSocial' => $request->input('razonSocialR'),
                    'RUC' => $request->input('rucR'),
                    'idpais' => $request->input('paisR'),
                    'iddpto' => $request->input('departamentoR'),
                    'idprov' => $request->input('provinciaR'),
                    'iddto' => $request->input('distritoR'),
                    'Direccion' => $request->input('direccionR'),
                    'Telefono' => $request->input('telefonoR'),
                    'Celular' => $request->input('celularR'),
                    'Correo' => $request->input('correoR'),
                    'Estado' => $request->input('estadoR'),
                    'TipoDoc' => $request->input('tipoDocR'),
                    'Documento' => $request->input('docR'),
                    'Apellidos' => $request->input('apellidosR'),
                    'Nombres' => $request->input('nombresR'),
                    'Observaciones' => $request->input('observacionesR'),
                    'Usuarioreg' => $usuarioLogueado->Login,
                ];

                if ($validator->fails()) {
                    return back()->withErrors($validator, 'registrarProv')->withInput();
                }

                $resultado = Model_Compras::registrarProveedor($params);

                if ($resultado === true) {
                    return redirect()->back()->with('success', 'Proveedor registrado o actualizado correctamente');
                } else {
                    return back()->withErrors('Hubo un error al registrar o actualizar la orden', 'registrarProv')->withInput();
                }
                break;
                case 'eliminar':
                    $op = $request->input('op');
                    $eliminar = Model_Compras::eliminarProveedor($op);
                    if ($eliminar === true) {
                        $data->respuesta = 'success';
                    } else {
                        $data->respuesta = 'error';
                        $data->eliminar = Model_Compras::getError();
                    }
                    break;
                case 'ObtenerData':
                    $op = $request->input('op');
                    $proveedor = Model_Compras::obtenerProveedor($op);
                    
                    if ($proveedor === null) {
                        $data->respuesta = 'error';
                        $data->data = Model_Compras::getError();
                    } elseif (empty($proveedor)) {
                        $data->respuesta = 'success';
                        $data->data = [];
                    } else {
                        $data->respuesta = 'success';
                        $data->data = $proveedor;
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
            $data->script = 'js/compras-proveedores.js';
            $data->css = 'css/administracion.css';
            $data->contenido = 'compras.proveedores';

            return view('layouts.contenido', (array) $data);
        }
    }

    public function ordenDeServicios(Request $request)
    {
        if ($request->isMethod('post')) {
            $opcion = $request->input('opcion');
            $data = new \stdClass();
            switch ($opcion) {
                case 'Listar':
                    $FI = str_replace('-', '', $request->input('fecha_inicio'));
                    $FF = str_replace('-', '', $request->input('fecha_fin'));
                    $razonSocial = $request->input('razon_social');
                    $pagos = $request->input('pagos');
                    $codemp = $request->input('codemp');
                    $sede = $request->input('sede');
                    $listar = Model_Compras::listarOCPagos($FI, $FF, $razonSocial, $sede, $pagos, $codemp);

                    if ($listar === null) {
                        $data->respuesta = 'error';
                        $data->listar = Model_Compras::getError();
                    } elseif (empty($listar)) {
                        $data->respuesta = 'success';
                        $data->listar = [];
                    } else {
                        $data->respuesta = 'success';
                        $data->listar = $listar;
                    }
                    break;
                case 'eliminar':
                    $op = $request->input('op');
                    $eliminar = Model_Compras::eliminarOrdenServicio($op);
                    if ($eliminar === true) {
                        $data->respuesta = 'success';
                    } else {
                        $data->respuesta = 'error';
                        $data->eliminar = Model_Compras::getError();
                    }
                    break;
                case 'sede':
                    $empresa = $request->input('empresa');
                    $item = Model_Compras::listaSedes2($empresa);
                    if ($item === null) {
                        $data->respuesta = 'error';
                        $data->item = Model_Compras::getError();
                    } elseif (empty($item)) {
                        $data->respuesta = 'success';
                        $data->item = [];
                    } else {
                        $data->respuesta = 'success';
                        $data->item = $item;
                    }
                    break;
                case 'buscar_razon_social':
                    $razon_social = $request->input('term');
                    $item = Model_Compras::listarRazonSocial($razon_social);
                    if ($item === null) {
                        $data->respuesta = 'error';
                        $data->item = Model_Compras::getError();
                    } elseif (empty($item)) {
                        $data->respuesta = 'success';
                        $data->item = [];
                    } else {
                        $data->respuesta = 'success';
                        $data->item = $item;
                    }
                    break;
                case 'pagos_pendientes':
                    $op = $request->input('op');
                    $deuda = Model_Compras::pagoPendienteServicio($op);
                    if ($deuda === null) {
                        $data->respuesta = 'error';
                        $data->deuda = Model_Compras::getError();
                    } elseif (empty($deuda)) {
                        $data->respuesta = 'success';
                        $data->deuda = [];
                    } else {
                        $data->respuesta = 'success';
                        $data->deuda = $deuda;
                    }
                    break;
                case 'Registrar':

                    $validator = Validator::make($request->all(), [
                        'fechaR'        => 'required|date',
                        'empresaR'      => 'required|numeric',
                        'proveedorR'    => 'required|string',
                        'solicitanteR'  => 'required|string|max:150',
                        'autorizanteR'  => 'required|string|max:150',
                        'formaPagoR'    => 'required|numeric',
                        'fechaPagoR'    => 'required|date',
                        'sedeR'         => 'required|string|max:150',
                        'tipoBienR'     => 'required|in:1,2',
                        'monedaR'       => 'required|in:1,2',
                        'observacionesR'=> 'nullable|string|max:350',
                    ], [
                        'fechaR.required'         => 'La fecha es obligatoria.',
                        'fechaR.date'             => 'La fecha no tiene un formato válido.',
                        'empresaR.required'       => 'La empresa es obligatoria.',
                        'empresaR.numeric'        => 'El valor de empresa no es válido.',
                        'proveedorR.required'     => 'El proveedor es obligatorio.',
                        'solicitanteR.required'   => 'El nombre del solicitante es obligatorio.',
                        'solicitanteR.max'        => 'El nombre del solicitante es demasiado largo.',
                        'autorizanteR.required'   => 'El nombre del autorizante es obligatorio.',
                        'autorizanteR.max'        => 'El nombre del autorizante es demasiado largo.',
                        'formaPagoR.required'     => 'La forma de pago es obligatoria.',
                        'fechaPagoR.required'     => 'La fecha de pago es obligatoria.',
                        'fechaPagoR.date'         => 'La fecha de pago no tiene un formato válido.',
                        'sedeR.required'          => 'El local (sede) es obligatorio.',
                        'sedeR.max'               => 'El nombre del local no debe superar los 150 caracteres.',
                        'tipoBienR.required'      => 'El tipo de bien es obligatorio.',
                        'tipoBienR.in'            => 'El tipo de bien seleccionado no es válido.',
                        'monedaR.required'        => 'La moneda es obligatoria.',
                        'monedaR.in'              => 'La moneda seleccionada no es válida.',
                        'observacionesR.max'      => 'Las observaciones no debe superar los 350 caracteres.',
                    ]);
                    
                    $usuarioLogueado = Auth::user();

                    $resultado = true;

                    $params = [
                        'Op'            => $request->input('Op'),
                        'empresaR'      => $request->input('empresaR'),
                        'fechaR'        => str_replace('-', '', $request->input('fechaR')),
                        'serieR'        => $request->input('serieR'),
                        'numeroR'       => $request->input('numeroR'),
                        'proveedorR'    => explode(' - ', $request->input('proveedorR'))[0],
                        'solicitanteR'  => $request->input('solicitanteR'),
                        'autorizanteR'  => $request->input('autorizanteR'),
                        'formaPagoR'    => $request->input('formaPagoR'),
                        'fechaPagoR'    => str_replace('-', '', $request->input('fechaPagoR')),
                        'sedeR'         => $request->input('sedeR'),
                        'tipoBienR'     => $request->input('tipoBienR'),
                        'total'         => $request->input('total'),
                        'subtotal'      => $request->input('subtotal'),
                        'igv'           => $request->input('igv'),
                        'codDetraccion' => ($request->input('detraccion')=== null) ? 0 : $request->input('detraccion'),
                        'detraccion'    => $request->input('valor'),
                        'observacionesR'=> $request->input('observacionesR'),
                        'Usuarioreg'    => $usuarioLogueado->Login,
                        'monedaR'       => $request->input('monedaR'),
                    ];

                    if ($validator->fails()) {
                        return back()->withErrors($validator, 'registrarOrServ')->withInput();
                    }

                    $resultado = Model_Compras::registrarOrdenServ1($params);

                    if ($resultado === true) {
                        
                        $ids           = $request->input('id');
                        $descripciones = $request->input('descripcion');
                        $cantidades    = $request->input('cantidad');
                        $precios       = $request->input('precio');
                        $importes      = $request->input('importe');
                        $op            = ($request->input('Op') === "0") ? Model_Compras::obtenerUltimoOpOC() : $request->input('Op');

                        $resultado2 = true;

                        $detalles = [];

                        foreach ($descripciones as $index => $descripcion) {
                            $detalles[] = [
                                'IDL'         => $ids[$index],
                                'Op'          => $op,
                                'item'        => $index + 1,
                                'Descripcion' => $descripcion,
                                'Cantidad'    => $cantidades[$index],
                                'Precio'      => $precios[$index],
                                'Importe'     => $importes[$index],
                            ];
                        }

                        $res = Model_Compras::registrarOrdenServ2($detalles);

                        if ($res === false) {
                            $resultado2 = false;
                        }

                        if ($resultado2 === true) {
                            return redirect()->back()->with('success', 'La orden se ha registrado o actualizado correctamente');
                        } else {
                            return back()->withErrors('Hubo un error al registrar o actualizar la orden', 'registrarOrServ')->withInput();
                        }

                    } else {
                        return back()->withErrors('Hubo un error al registrar o actualizar la orden', 'registrarOrServ')->withInput();
                    }
                    break;
                case 'ObtenerData':
                    $op = $request->input('op');
                    $info1 = Model_Compras::obtenerDataOrden($op);

                    if ($info1 === null) {
                        $data->respuesta = 'error';
                        $data->data = Model_Compras::getError();
                    } elseif (empty($info1)) {
                        $data->respuesta = 'success';
                        $data->data = [];
                    } else {
                        $info2 = Model_Compras::obtenerDataOrdenDetalle($op);

                        if ($info2 === null) {
                            $data->respuesta = 'error';
                            $data->data = Model_Compras::getError();
                        } else {
                            $data->respuesta = 'success';
                            $data->data = [
                                'cabecera' => $info1,
                                'detalle'  => $info2 ?? [],
                            ];
                        }
                    }
                    break;
                case 'obtener_proveedor':
                    $cod = $request->input('cod');
                    $prov = Model_Compras::obtenerProveedorPorCod($cod);
                    if ($prov === null) {
                        $data->respuesta = 'error';
                        $data->prov = Model_Compras::getError();
                    } elseif (empty($prov)) {
                        $data->respuesta = 'success';
                        $data->prov = [];
                    } else {
                        $data->respuesta = 'success';
                        $data->prov = $prov;
                    }
                    break;
                case 'eliminarDetalle':
                    $op = $request->input('IDL');
                    $eliminar = Model_Compras::eliminarOCDetalle($op);
                    if ($eliminar === true) {
                        $data->respuesta = 'success';
                    } else {
                        $data->respuesta = 'error';
                        $data->eliminar = Model_Compras::getError();
                    }
                    break;
                case 'RegistrarPay':
                    try {

                        if ($request->hasFile('adjuntarPay') && $request->file('adjuntarPay')->isValid()) {
                            $file = $request->file('adjuntarPay');

                            if ($file->getClientMimeType() === 'application/pdf') {
                                if (session()->has('temp_adjunto')) {
                                    $oldTemp = session('temp_adjunto');
                                    if (\Storage::disk('public')->exists($oldTemp)) {
                                        \Storage::disk('public')->delete($oldTemp);
                                    }
                                }

                                $tempPath = $file->store('temp', 'public');
                                session(['temp_adjunto' => $tempPath]);
                            }
                        }

                        $validator = Validator::make($request->all(), [
                            'fechaPay'          => 'required|date',
                            'adjuntarPay'       => 'nullable|file|mimes:pdf|max:4194304',
                            'opPay'             => 'required|numeric', 
                            'numOperacionPay'   => 'required|string',
                            'importePay'        => 'required|numeric|min:1|lte:pendientePay', 
                            'totalPay'          => 'required|numeric|min:1', 
                            'tipoPay'           => 'required', 
                            'bancoPay'          => 'required',
                            'monedaPay'         => 'required|in:1,2', 
                            'autorizadoPay'     => 'required', 
                            'porcentajePay'     => 'nullable|numeric|min:0|max:100',
                            'detracPay'         => 'nullable|numeric|min:0',
                            'totalPay'          => 'required|numeric|min:1|gt:0',
                            'pendientePay'      => 'required|numeric',
                            ], [
                            'fechaPay.required'         => 'La fecha de pago es obligatoria.',
                            'adjuntarPay.file'          => 'El archivo debe ser un PDF.',
                            'adjuntarPay.mimes'         => 'El archivo debe tener formato PDF.',
                            'adjuntarPay.max'           => 'El archivo no debe exceder los 4MB.',
                            'opPay.required'            => 'El campo de operación es obligatorio.',
                            'numOperacionPay.required'  => 'El número de operación es obligatorio.',
                            'importePay.required'       => 'El importe es obligatorio.',
                            'importePay.min'            => 'El importe debe ser mayor a 0.',
                            'importePay.lte'            => 'El importe no puede ser mayor que el pendiente.',
                            'totalPay.required'         => 'El total es obligatorio.',
                            'totalPay.min'              => 'El total debe ser mayor a 0.',
                            'tipoPay.required'          => 'El tipo de pago es obligatorio.',
                            'bancoPay.required'         => 'El banco es obligatorio.',
                            'monedaPay.required'        => 'La moneda es obligatoria.',
                            'monedaPay.in'              => 'La moneda seleccionada no es válida.',
                            'autorizadoPay.required'    => 'El autorizado es obligatorio.',
                            'porcentajePay.numeric'     => 'El porcentaje de detracción debe ser un número.',
                            'porcentajePay.min'         => 'El porcentaje de detracción debe ser mayor o igual a 0.',
                            'porcentajePay.max'         => 'El porcentaje de detracción no puede ser mayor a 100.',
                            'detracPay.numeric'         => 'El campo de detracción debe ser un número.',
                            'detracPay.min'             => 'El campo de detracción debe ser mayor o igual a 0.',
                            'totalPay.required'         => 'El campo de total es obligatorio.',
                            'totalPay.numeric'          => 'El campo de total debe ser un número.',
                            'totalPay.min'              => 'El total debe ser mayor a 0.',
                            'totalPay.gt'               => 'El total debe ser mayor a 0.',
                            'pendientePay.required'     => 'El campo de pendiente es obligatorio.',
                            'pendientePay.numeric'      => 'El campo de pendiente debe ser un número.',
                        ]);

                        $usuarioLogueado = Auth::user();
                        $params = [
                            'IDPago'        => $request->input('idPay'),
                            'Op'            => $request->input('opPay'),
                            'FechaPago'     => str_replace('-', '', $request->input('fechaPay')),
                            'TipoPago'      => $request->input('tipoPay'),
                            'codBanco'      => $request->input('bancoPay'),
                            'Operacion'     => $request->input('numOperacionPay'),
                            'Autorizado'    => $request->input('autorizadoPay'),
                            'Pendiente'     => $request->input('pendientePay'),
                            'Importe'       => $request->input('importePay'),
                            'DetracPorc'    => $request->input('porcentajePay'),
                            'Detraccion'    => $request->input('detracPay'),
                            'Total'         => $request->input('totalPay'),
                            'Usuarioreg'    => $usuarioLogueado->Login,
                            'Observacion'   => $request->input('observacionPay'),
                            'Id_Moneda'     => $request->input('monedaPay'),
                        ];

                        if ($validator->fails()) {
                            return back()->withErrors($validator, 'formregistrarPay')->withInput();
                        }
                        $resultado = Model_Compras::registrarPagoOrdenSer($params);
                        
                        if ($resultado === false) {
                            return back()->withErrors('Hubo un error al registrar o actualizar la orden', 'formregistrarPay')->withInput();
                        } else {
                            if (session()->has('temp_adjunto')) {
                                $oldPath = session('temp_adjunto');
                                $IDPago = $resultado; 
                                $extension = pathinfo($oldPath, PATHINFO_EXTENSION);
                                $filename = $IDPago . '.' . $extension;
                                $folder = 'ArchivosOS';
                                
                                if (!\Storage::disk('public')->exists($folder)) {
                                    \Storage::disk('public')->makeDirectory($folder);
                                }
                                
                                $newPath = $folder . '/' . $filename;
                                
                                \Storage::disk('public')->move($oldPath, $newPath);
                                session()->forget('temp_adjunto');
                                $urlPublica = asset('storage/' . $newPath); //url del archivo
                            }
                            return redirect()->back()->with('success', 'El pago se ha registrado o actualizado correctamente');
                        }
                    
                    } catch (\Illuminate\Validation\ValidationException $e) {
                        throw $e; 
                    }
                    
                    break;
                case 'eliminarArchTemp':
                    $tempPath = $request->input('temp_adjunto');
    
                    if ($tempPath) {
                        if (\Storage::disk('public')->exists($tempPath)) {
                            \Storage::disk('public')->delete($tempPath);
                        }
                    }

                    break;
                case 'pagosXproveedor':
                    $op = $request->input('op');
                    $detalle = Model_Compras::detalleXproveedor($op);
                    if ($detalle === null) {
                        $data->respuesta = 'error';
                        $data->detalle = Model_Compras::getError();
                    } elseif (empty($detalle)) {
                        $data->respuesta = 'success';
                        $data->detalle = [];
                    } else {
                        $data->respuesta = 'success';
                        $data->detalle = $detalle;
                    }
                    break;
                case 'PDF':
                    $op = $request->input('op'); 
                    $detalle = Model_Compras::listarDetallePDF($op);  

                    if ($detalle === null) {
                        $data->respuesta = 'error';
                        $data->detalle = Model_Compras::getError();
                    } elseif (empty($detalle)) {
                        $data->respuesta = 'success';
                        $data->detalle = [];
                    } else {
                        $data->respuesta = 'success';
                        $data->detalle = [];
                        $pdf = \PDF::loadView('compras.ordenCompraPdf', [
                            'detalle' => $detalle 
                        ]);

                        $pdfContent = $pdf->output();
                        $pdfBase64 = base64_encode($pdfContent);
                        $data->pdf = [
                            'data' => $pdfBase64,
                            'filename' => "orden_compra_{$op}.pdf",
                            'mime' => 'application/pdf'
                        ];
                    }
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

            $data->sedes = Model_Compras::listaSedes();
            $data->empresa = Model_Compras::listaEmpresas();
            $data->serieNum = Model_Compras::ObtenerSerieNumero();
            $data->solicitantes = Model_Compras::listaSolicitantes();
            $data->autorizantes = Model_Compras::listaAutorizantes();
            $data->formaPago = Model_Compras::listaFormaPago();
            $data->tipoPago = Model_Compras::listaTipoPagos();
            $data->banco = Model_Compras::listaBancos();
            $data->script = 'js/compras-ordenDeServicios.js';
            $data->css = 'css/administracion.css';
            $data->contenido = 'compras.ordenDeServicios';

            return view('layouts.contenido', (array) $data);
        } 
    }

    public function pagosPorOS(Request $request)
    {
        if ($request->isMethod('post')) {
            $opcion = $request->input('opcion');
            $data = new \stdClass();
            switch ($opcion) {
                case 'Listar':
                    $FI = str_replace('-', '', $request->input('fecha_inicio'));
                    $FF = str_replace('-', '', $request->input('fecha_fin'));
                    $razonSocial = $request->input('razon_social');
                    $sede = $request->input('sede');
                    $listar = Model_Compras::listarPagosOS($FI, $FF, $razonSocial, $sede);

                    if ($listar === null) {
                        $data->respuesta = 'error';
                        $data->listar = Model_Compras::getError();
                    } elseif (empty($listar)) {
                        $data->respuesta = 'success';
                        $data->listar = [];
                    } else {
                        foreach ($listar as &$pago) {
                            $filename = 'ArchivosOS/' . $pago->IDPago . '.pdf';
                            if (Storage::disk('public')->exists($filename)) {
                                $pago->archivo_pdf = asset('storage/' . $filename);
                            } else {
                                $pago->archivo_pdf = null;
                            }
                        }

                        $data->respuesta = 'success';
                        $data->listar = $listar;
                    }
                    break;

                case 'obtenerPago':
                    $op = $request->input('op');
                    $info = Model_Compras::obtenerPagoxOp($op);

                    if ($info === null) {
                        $data->respuesta = 'error';
                        $data->info = Model_Compras::getError();
                    } elseif (empty($info)) {
                        $data->respuesta = 'success';
                        $data->info = [];
                    } else {
                        $pago = $info[0];
                        $filename = 'ArchivosOS/' . $pago->IDPago . '.pdf';
                        if (Storage::disk('public')->exists($filename)) {
                            $data->archivo_pdf = asset('storage/' . $filename); 
                        } else {
                            $data->archivo_pdf = null;
                        }
                        $data->respuesta = 'success';
                        $data->info = $info;
                    }
                    break;
                case 'RegistrarPay':
                    try {

                        if ($request->hasFile('adjuntarPay') && $request->file('adjuntarPay')->isValid()) {
                            $file = $request->file('adjuntarPay');

                            if ($file->getClientMimeType() === 'application/pdf') {
                                if (session()->has('temp_adjunto')) {
                                    $oldTemp = session('temp_adjunto');
                                    if (\Storage::disk('public')->exists($oldTemp)) {
                                        \Storage::disk('public')->delete($oldTemp);
                                    }
                                }

                                $tempPath = $file->store('temp', 'public');
                                session(['temp_adjunto' => $tempPath]);
                            }
                        }

                        $validator = Validator::make($request->all(), [
                            'fechaPay'          => 'required|date',
                            'adjuntarPay'       => 'nullable|file|mimes:pdf|max:4194304',
                            'opPay'             => 'required|numeric', 
                            'numOperacionPay'   => 'required|string',
                            'importePay'        => 'required|numeric|min:1|lte:pendientePay', 
                            'totalPay'          => 'required|numeric|min:1', 
                            'tipoPay'           => 'required', 
                            'bancoPay'          => 'required',
                            'monedaPay'         => 'required|in:1,2', 
                            'autorizadoPay'     => 'required', 
                            'porcentajePay'     => 'nullable|numeric|min:0|max:100',
                            'detracPay'         => 'nullable|numeric|min:0',
                            'totalPay'          => 'required|numeric|min:1|gt:0',
                            'pendientePay'      => 'required|numeric',
                            ], [
                            'fechaPay.required'         => 'La fecha de pago es obligatoria.',
                            'adjuntarPay.file'          => 'El archivo debe ser un PDF.',
                            'adjuntarPay.mimes'         => 'El archivo debe tener formato PDF.',
                            'adjuntarPay.max'           => 'El archivo no debe exceder los 4MB.',
                            'opPay.required'            => 'El campo de operación es obligatorio.',
                            'numOperacionPay.required'  => 'El número de operación es obligatorio.',
                            'importePay.required'       => 'El importe es obligatorio.',
                            'importePay.min'            => 'El importe debe ser mayor a 0.',
                            'importePay.lte'            => 'El importe no puede ser mayor que el pendiente.',
                            'totalPay.required'         => 'El total es obligatorio.',
                            'totalPay.min'              => 'El total debe ser mayor a 0.',
                            'tipoPay.required'          => 'El tipo de pago es obligatorio.',
                            'bancoPay.required'         => 'El banco es obligatorio.',
                            'monedaPay.required'        => 'La moneda es obligatoria.',
                            'monedaPay.in'              => 'La moneda seleccionada no es válida.',
                            'autorizadoPay.required'    => 'El autorizado es obligatorio.',
                            'porcentajePay.numeric'     => 'El porcentaje de detracción debe ser un número.',
                            'porcentajePay.min'         => 'El porcentaje de detracción debe ser mayor o igual a 0.',
                            'porcentajePay.max'         => 'El porcentaje de detracción no puede ser mayor a 100.',
                            'detracPay.numeric'         => 'El campo de detracción debe ser un número.',
                            'detracPay.min'             => 'El campo de detracción debe ser mayor o igual a 0.',
                            'totalPay.required'         => 'El campo de total es obligatorio.',
                            'totalPay.numeric'          => 'El campo de total debe ser un número.',
                            'totalPay.min'              => 'El total debe ser mayor a 0.',
                            'totalPay.gt'               => 'El total debe ser mayor a 0.',
                            'pendientePay.required'     => 'El campo de pendiente es obligatorio.',
                            'pendientePay.numeric'      => 'El campo de pendiente debe ser un número.',
                        ]);

                        $usuarioLogueado = Auth::user();
                        $params = [
                            'IDPago'        => $request->input('idPay'),
                            'Op'            => $request->input('opPay'),
                            'FechaPago'     => str_replace('-', '', $request->input('fechaPay')),
                            'TipoPago'      => $request->input('tipoPay'),
                            'codBanco'      => $request->input('bancoPay'),
                            'Operacion'     => $request->input('numOperacionPay'),
                            'Autorizado'    => $request->input('autorizadoPay'),
                            'Pendiente'     => $request->input('pendientePay'),
                            'Importe'       => $request->input('importePay'),
                            'DetracPorc'    => $request->input('porcentajePay'),
                            'Detraccion'    => $request->input('detracPay'),
                            'Total'         => $request->input('totalPay'),
                            'Usuarioreg'    => $usuarioLogueado->Login,
                            'Observacion'   => $request->input('observacionPay'),
                            'Id_Moneda'     => $request->input('monedaPay'),
                        ];

                        if ($validator->fails()) {
                            return back()->withErrors($validator, 'formregistrarPay')->withInput();
                        }
                        $resultado = Model_Compras::registrarPagoOrdenSer($params);
                        
                        if ($resultado === false) {
                            return back()->withErrors('Hubo un error al registrar o actualizar la orden', 'formregistrarPay')->withInput();
                        } else {
                            if (session()->has('temp_adjunto')) {
                                $oldPath = session('temp_adjunto');
                                $IDPago = $resultado; 
                                $extension = pathinfo($oldPath, PATHINFO_EXTENSION);
                                $filename = $IDPago . '.' . $extension;
                                $folder = 'ArchivosOS';
                                
                                if (!\Storage::disk('public')->exists($folder)) {
                                    \Storage::disk('public')->makeDirectory($folder);
                                }

                                $newPath = $folder . '/' . $filename;

                                if (\Storage::disk('public')->exists($newPath)) {
                                    \Storage::disk('public')->delete($newPath);
                                }

                                \Storage::disk('public')->move($oldPath, $newPath);

                                session()->forget('temp_adjunto');
                                $urlPublica = asset('storage/' . $newPath);
                            }
                            return redirect()->back()->with('success', 'El pago se ha registrado o actualizado correctamente');
                        }
                    
                    } catch (\Illuminate\Validation\ValidationException $e) {
                        throw $e; 
                    }
                    
                    break;
                case 'eliminarArchTemp':
                    $tempPath = $request->input('temp_adjunto');
    
                    if ($tempPath) {
                        if (\Storage::disk('public')->exists($tempPath)) {
                            \Storage::disk('public')->delete($tempPath);
                        }
                    }

                    break;
                case 'eliminar':
                    $op = $request->input('op');
                    $eliminar = Model_Compras::eliminarPagoOS($op);
                    if ($eliminar === true) {
                        $filename = $op . '.pdf';
                        $folder = 'ArchivosOS';
                        $newPath = $folder . '/' . $filename;

                        if (\Storage::disk('public')->exists($newPath)) {
                            \Storage::disk('public')->delete($newPath);
                        }

                        $data->respuesta = 'success';
                    } else {
                        $data->respuesta = 'error';
                        $data->eliminar = Model_Compras::getError();
                    }
                    break;
                case 'PDF':
                    $op = $request->input('op');

                    $detalle = Model_Compras::listarDetallePagoPDF($op);
                    $tabla   = Model_Compras::listarTablaDetallePagoPDF($op);

                    if ($detalle === null || $tabla === null) {
                        $data->respuesta = 'error';
                        $data->mensaje = 'Error al obtener los datos del pago.';
                        $data->detalle = Model_Compras::getError();
                        $data->tabla = null;
                        break;
                    }

                    if (empty($detalle)) {
                        $data->respuesta = 'vacio';
                        $data->mensaje = 'No hay detalles disponibles para este pago.';
                        $data->detalle = [];
                        $data->tabla = [];
                        break;
                    }

                    try {
                        $pdf = \PDF::loadView('compras.PagoOsPdf', [
                            'detalle' => $detalle,
                            'tabla'   => $tabla
                        ]);

                        $pdfContent = $pdf->output();
                        $pdfBase64 = base64_encode($pdfContent);

                        $data->respuesta = 'success';
                        $data->detalle = $detalle;
                        $data->tabla = $tabla;
                        $data->pdf = [
                            'data' => $pdfBase64,
                            'filename' => "pago_{$op}.pdf",
                            'mime' => 'application/pdf'
                        ];
                    } catch (\Exception $e) {
                        $data->respuesta = 'error';
                        $data->mensaje = 'Ocurrió un error al generar el PDF.';
                        $data->error = $e->getMessage();
                    }
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
            $data->sedes = Model_Compras::listaSedes();
            $data->solicitantes = Model_Compras::listaSolicitantes();
            $data->autorizantes = Model_Compras::listaAutorizantes();
            $data->formaPago = Model_Compras::listaFormaPago();
            $data->tipoPago = Model_Compras::listaTipoPagos();
            $data->banco = Model_Compras::listaBancos();
            $data->script = 'js/compras-pagosPorOS.js';
            $data->css = 'css/administracion.css';
            $data->contenido = 'compras.pagosPorOS';

            return view('layouts.contenido', (array) $data);
        }
    }

}
