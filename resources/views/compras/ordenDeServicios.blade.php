@php
    $fechaHoy = \Carbon\Carbon::now(); 
    $inicioMes = $fechaHoy->copy()->startOfMonth();
@endphp
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="card border-container-model mb-4">
                <div class="card-header bg-model text-white d-flex align-items-center">
                    <h5 class="mb-0">Orden de Servicios</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-12 pt-3">
                                <label class="form-label">Fecha Inicio:</label>
                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio"
                                       value="{{ $inicioMes->format('Y-m-d') }}">
                            </div>
                        
                            <div class="col-lg-3 col-md-6 col-sm-12 pt-3">
                                <label class="form-label">Fecha Final:</label>
                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin"
                                       value="{{ $fechaHoy->format('Y-m-d') }}">
                            </div>

                            <div class="col-lg-3 col-md-12 col-sm-12 pt-3">
                                <label class="form-label">Empresa:</label>
                                <select id="codemp" name="codemp" class="form-control">
                                    <option value="0">TODOS</option>
                                    @foreach ($empresa as $empresa2)
                                        <option value="{{ $empresa2->codigo }}"> {{ $empresa2->Nombre }} </option>
                                    @endforeach
                                </select>
                                
                            </div>

                            <div class="col-lg-3 col-md-12 col-sm-12 pt-3">
                                <label class="form-label">Sede:</label>
                                <select id="sede" name="sede" class="form-control" required>
                                    @foreach ($sedes as $sede)
                                        @if ($sede->ID == '00' && strtoupper(trim($sede->Nombre)) == 'NINGUNO')
                                            <option value="0">TODOS</option>
                                        @elseif (!(strtoupper(trim($sede->Nombre)) == 'NINGUNO'))
                                            <option value="{{ $sede->ID }}">{{ strtoupper($sede->Nombre) }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>                            

                            <div class="col-lg-3 col-md-12 col-sm-12 pt-3">
                                <label class="form-label">Pagos:</label>
                                <select id="pagos" name="pagos" class="form-control">
                                    <option value="0">TODOS</option>
                                    <option value="1">A PAGAR</option>
                                </select>
                            </div>   

                            <div class="col-lg-5 col-md-12 col-sm-12 pt-3">
                                <label class="form-label">Proveedor:</label>
                                <input id="razon_social" name="razon_social" codigo="" class="form-control ui-autocomplete-input" />
                            </div>

                            <div class="col-lg-2 col-md-12 col-sm-12 pt-4 mt-3">
                                <div class="position-relative pt-2">
                                    <button class="btn btn-success w-100 text-center position-relative" title="Buscar" id="buscarOrdenServicios" type="button">
                                        <i class="bi bi-search position-absolute start-0 top-50 translate-middle-y ms-3"></i>
                                        Buscar
                                    </button>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-12 col-sm-12 pt-4 mt-3">
                                <div class="position-relative pt-2">
                                    <button class="btn btn-primary w-100 text-center position-relative" title="Nuevo" id="btnNuevo" data-bs-toggle="modal" data-bs-target="#registrarOrdenServicio" type="button">
                                        <i class="bi bi-plus-lg position-absolute start-0 top-50 translate-middle-y ms-3"></i>
                                        Nuevo
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card border-container-model m-4">
                    <div class="card-header bg-model text-white">
                        <h6 class="mb-0">Lista</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover text-center display pt-4" id="tablaOrdenServicio">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center bg-model2">Op</th>
                                        <th class="text-center bg-model2">Empresa</th>
                                        <th class="text-center bg-model2">Fecha</th>
                                        <th class="text-center bg-model2">Transaccion</th>
                                        <th class="text-center bg-model2">Proveedor</th>
                                        <th class="text-center bg-model2 d-none">RUC</th>
                                        <th class="text-center bg-model2 d-none">Forma Pago</th>
                                        <th class="text-center bg-model2 d-none">Fecha Pago</th>
                                        <th class="text-center bg-model2 d-none">Moneda</th>
                                        <th class="text-center bg-model2 d-none">SubTotal</th>
                                        <th class="text-center bg-model2 d-none">IGV</th>
                                        <th class="text-center bg-model2 d-none">Total</th>
                                        <th class="text-center bg-model2 d-none">%Detracc</th>
                                        <th class="text-center bg-model2 d-none">Detraccion</th>
                                        <th class="text-center bg-model2 d-none">A pagar</th>
                                        <th class="text-center bg-model2 d-none">Pagado</th>
                                        <th class="text-center bg-model2 d-none">Nro. Pagos</th>
                                        <th class="text-center bg-model2 d-none">Sede</th>
                                        <th class="text-center bg-model2 d-none">Tipo Bien</th>
                                        <th class="text-center bg-model2 d-none">Observacion</th>
                                        <th class="text-center bg-model2 d-none">Fecha Reg.</th>
                                        <th class="text-center bg-model2 d-none">Usuario Reg.</th>
                                        <th class="text-center bg-model2 d-none">codemp</th>
                                        <th class="text-center bg-model2">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot></tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="modalVistaPreviaPDF" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalVistaPreviaPDFLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">VISTA PREVIA</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0"> 
                <div id="divIframePDF" style="width: 100%;">          
                </div>             
            </div>

        </div>
    </div>
</div>

<div class="modal fade modal-xl" id="verPagosModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="verPayModalLabel" aria-hidden="true">    
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Pagos por proveedor</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3 px-5 mx-5 text-center" style="place-self: center;align-items: center;">
                    <div class="col-lg-4 col-md-12 pt-2">
                        <label class="form-label fw-bold">Op</label>
                        <p id="verOp" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-lg-8 col-md-12 pt-2">
                        <label class="form-label fw-bold">Proveedor</label>
                        <p id="verProveedor" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-lg-4 col-md-12 pt-2">
                        <label class="form-label fw-bold">RUC</label>
                        <p id="verRUC" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-lg-8 col-md-12 pt-2">
                        <label class="form-label fw-bold">Sede</label>
                        <p id="verSede" class="form-control-plaintext"></p>
                    </div>
                </div>
                <div class="card border-container-model m-4">
                    <div class="card-header bg-model text-white">
                        <h6 class="mb-0">Lista</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <div id="infoImportePendiente" class="mb-2 fw-bold text-center"></div>
                            <table class="table table-bordered table-hover text-center display pt-4" id="tablaVisualizarPagos">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center bg-model2">CodPago</th>
                                        <th class="text-center bg-model2">F. Pago</th>
                                        <th class="text-center bg-model2">Transaccion</th>
                                        <th class="text-center bg-model2">Descripcion</th>
                                        <th class="text-center bg-model2">Mon</th>
                                        <th class="text-center bg-model2">Importe</th>
                                        <th class="text-center bg-model2">Detraccion</th>
                                        <th class="text-center bg-model2">Pagos</th>
                                        <th class="text-center bg-model2">FechaReg</th>
                                        <th class="text-center bg-model2">UsuarioReg</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5" class="text-end">Totales:</th>
                                        <th class="text-center" id="totalImporte"></th>
                                        <th class="text-center" id="totalDetraccion"></th>
                                        <th class="text-center" id="totalPagos"></th>
                                        <th colspan="2"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@if (session('success'))
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
    Toast.fire({
        icon: "success",
        title: '{{ session('success') }}'
    });
</script>
@endif
<div class="modal fade modal-lg" id="PayModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="registrarPayModalLabel" aria-hidden="true">    
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Registrar Pago</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formregistrarPay" method="POST" action="{{ url('compras/ordenDeServicios') }}"  enctype="multipart/form-data">
                    @csrf
                    @if ($errors->getBag('formregistrarPay')->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->getBag('formregistrarPay')->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="row">
                        <input type="hidden" name="opcion" id="opcion" value="RegistrarPay" />
                        <input type="hidden" name="idPay" id="idPay" value="{{ old('idPay', '0') }}" />

                        <div class="col-lg-6 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Op</label>
                            <input type="text" name="opPay" id="opPay" class="form-control readonly-style @error('opPay', 'formregistrarPay') is-invalid @enderror" value="{{ old('opPay') }}" readonly>
                            @error('opPay', 'formregistrarPay')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Proveedor</label>
                            <input type="text" name="proveedorPay" id="proveedorPay" class="form-control readonly-style @error('proveedorPay', 'formregistrarPay') is-invalid @enderror" value="{{ old('proveedorPay') }}" readonly>
                            @error('proveedorPay', 'formregistrarPay')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Transaccion</label>
                            <input type="text" name="transaccionPay" id="transaccionPay" class="form-control readonly-style @error('transaccionPay', 'formregistrarPay') is-invalid @enderror" value="{{ old('transaccionPay') }}" readonly>
                            @error('transaccionPay', 'formregistrarPay')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-lg-6 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Fecha Pago</label>
                            <input type="date" name="fechaPay" id="fechaPay" class="form-control @error('fechaPay', 'formregistrarPay') is-invalid @enderror" value="{{ old('fechaPay', $fechaHoy->format('Y-m-d')) }}">
                            @error('fechaPay', 'formregistrarPay')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                
                        <div class="col-lg-6 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Tipo</label>
                            <select id="tipoPay" name="tipoPay" class="form-control @error('tipoPay', 'formregistrarPay') is-invalid @enderror">
                                @foreach ($tipoPago as $tipoPagos)
                                    <option value="{{ $tipoPagos->id }}" {{ old('tipoPay') == $tipoPagos->id ? 'selected' : '' }}> {{ strtoupper($tipoPagos->Nombre) }} </option>
                                @endforeach
                            </select>                            
                            @error('tipoPay', 'formregistrarPay')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Banco</label>
                            <select id="bancoPay" name="bancoPay" class="form-control @error('bancoPay', 'formregistrarPay') is-invalid @enderror">
                                @foreach ($banco as $banco)
                                    <option value="{{ $banco->id }}" {{ old('bancoPay') == $banco->id ? 'selected' : '' }}> {{ strtoupper($banco->Nombre) }} </option>
                                @endforeach
                            </select>                            
                            @error('tipoPay', 'formregistrarPay')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Num. Operacion</label>
                            <input type="text" name="numOperacionPay" id="numOperacionPay" class="form-control @error('numOperacionPay', 'formregistrarPay') is-invalid @enderror" value="{{ old('numOperacionPay') }}">
                            @error('numOperacionPay', 'formregistrarPay')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Autorizado</label>
                            <select id="autorizadoPay" name="autorizadoPay" class="form-control @error('autorizadoPay', 'formregistrarPay') is-invalid @enderror"> 
                                @foreach ($autorizantes as $autorizadoP) 
                                    <option value="{{ $autorizadoP->CodEmpleado }}" {{ old('autorizadoPay', '000106') == $autorizadoP->CodEmpleado ? 'selected' : '' }}> {{ strtoupper($autorizadoP->Empleado) }} 
                                    </option> 
                                @endforeach 
                            </select>                           
                            @error('autorizadoPay', 'formregistrarPay')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Pendiente</label>
                            <input type="text" name="pendientePay" id="pendientePay" class="form-control @error('pendientePay', 'formregistrarPay') is-invalid @enderror" value="{{ old('pendientePay') }}" readonly>
                            @error('pendientePay', 'formregistrarPay')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Moneda</label>
                            <select id="monedaPay" name="monedaPay" class="form-control @error('monedaPay', 'formregistrarPay') is-invalid @enderror">
                                <option value="1" {{ old('monedaR', '1') == '1' ? 'selected' : '' }}>SOLES</option>
                                <option value="2" {{ old('monedaR', '1') == '2' ? 'selected' : '' }}>DOLARES</option>
                            </select>
                            @error('monedaPay', 'formregistrarPay')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>  

                        <div class="col-lg-6 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Importe</label>
                            <input type="number" name="importePay" id="importePay" step="any" class="form-control @error('importePay', 'formregistrarPay') is-invalid @enderror" value="{{ old('importePay') ?? 0 }}">
                            @error('importePay', 'formregistrarPay')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-lg-6 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">% Detrac.</label>
                            <input type="number" name="porcentajePay" id="porcentajePay" step="any" class="form-control @error('porcentajePay', 'formregistrarPay') is-invalid @enderror" value="{{ old('porcentajePay') ?? 0 }}">
                            @error('porcentajePay', 'formregistrarPay')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-lg-6 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Detraccion</label>
                            <input type="number" name="detracPay" id="detracPay" step="any" class="form-control @error('detracPay', 'formregistrarPay') is-invalid @enderror" value="{{ old('detracPay') ?? 0 }}">
                            @error('detracPay', 'formregistrarPay')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-lg-6 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Total</label>
                            <input type="number" name="totalPay" id="totalPay" step="any" class="form-control @error('totalPay', 'formregistrarPay') is-invalid @enderror" value="{{ old('totalPay') ?? 0 }}">
                            @error('totalPay', 'formregistrarPay')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>                        

                        <div class="col-lg-6 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Observacion</label>
                            <input type="text" name="observacionPay" id="observacionPay" step="any" class="form-control @error('observacionPay', 'formregistrarPay') is-invalid @enderror" value="{{ old('observacionPay') }}">
                            @error('observacionPay', 'formregistrarPay')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Adjuntar</label>
                            <input type="file" name="adjuntarPay" id="adjuntarPay" class="form-control @error('adjuntarPay', 'formregistrarPay') is-invalid @enderror" value="{{ old('adjuntarPay') }}">
                            @error('adjuntarPay', 'formregistrarPay')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer pt-5">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>             
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-lg" id="registrarOrdenServicio" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">    
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="tituloModalOrdenServicio">Registrar Orden de Servicio</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="registrarOrServ" method="POST" action="{{ url('compras/ordenDeServicios') }}">
                    @csrf
                    @if ($errors->getBag('registrarOrServ')->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->getBag('registrarOrServ')->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="row">
                        <input type="hidden" name="opcion" id="opcion" value="Registrar" />
                        <input type="hidden" name="Op" id="Op" value="{{ old('Op', '0') }}" />
                        
                        <div class="col-lg-6 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Serie y Número</label>
                            <div class="input-group">
                                <input type="text" name="serieR" id="serieR" class="form-control readonly-style" placeholder="Serie" value="{{ old('serieR', $serieNum->Serie) }}" readonly>
                                <input type="text" name="numeroR" id="numeroR" class="form-control readonly-style" placeholder="Número" value="{{ old('numeroR', $serieNum->Numero) }}" readonly>
                            </div>
                        </div>
                        
                        <div class="col-lg-6 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Fecha</label>
                            <input type="date" name="fechaR" id="fechaR" class="form-control @error('fechaR', 'registrarOrServ') is-invalid @enderror" value="{{ old('fechaR', $fechaHoy->format('Y-m-d')) }}">
                            @error('fechaR', 'registrarOrServ')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                
                        <div class="col-lg-6 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Empresa</label>
                            <select id="empresaR" name="empresaR" class="form-control @error('empresaR', 'registrarOrServ') is-invalid @enderror">
                                @foreach ($empresa as $empresa)
                                    <option value="{{ $empresa->codigo }}" {{ old('empresaR') == $empresa->codigo ? 'selected' : '' }}> {{ $empresa->Nombre }} </option>
                                @endforeach
                            </select>
                            @error('empresaR', 'registrarOrServ')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>   
                        
                        <div class="col-lg-6 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Proveedor</label>
                            <input type="text" name="proveedorR" id="proveedorR" class="form-control ui-autocomplete-input @error('proveedorR', 'registrarOrServ') is-invalid @enderror" value="{{ old('proveedorR') }}">
                            @error('proveedorR', 'registrarOrServ')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                
                        <div class="col-lg-6 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Solicitado</label>
                            <select id="solicitanteR" name="solicitanteR" class="form-control @error('solicitanteR', 'registrarOrServ') is-invalid @enderror">
                                @foreach ($solicitantes as $solicitantes)
                                    <option value="{{ $solicitantes->CodEmpleado }}" {{ old('solicitanteR') == $solicitantes->CodEmpleado ? 'selected' : '' }}> {{ $solicitantes->Empleado }} </option>
                                @endforeach
                            </select>                            
                            @error('solicitanteR', 'registrarOrServ')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                
                        <div class="col-lg-6 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Autorizado</label>
                            <select id="autorizanteR" name="autorizanteR" class="form-control @error('autorizanteR', 'registrarOrServ') is-invalid @enderror">
                                @foreach ($autorizantes as $autorizantesR)
                                    <option value="{{ $autorizantesR->CodEmpleado }}" {{ old('autorizanteR') == $autorizantesR->CodEmpleado ? 'selected' : '' }}> {{ $autorizantesR->Empleado }} </option>
                                @endforeach
                            </select>                            
                            @error('autorizanteR', 'registrarOrServ')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                
                        <div class="col-lg-4 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Forma Pago</label>
                            <select id="formaPagoR" name="formaPagoR" class="form-control @error('formaPagoR', 'registrarOrServ') is-invalid @enderror">
                                @foreach ($formaPago as $formaPago)
                                    <option value="{{ $formaPago->IDFP }}" {{ old('formaPagoR') == $formaPago->IDFP ? 'selected' : '' }}> {{ strtoupper($formaPago->Nombre) }} </option>
                                @endforeach
                            </select>                            
                            @error('formaPagoR', 'registrarOrServ')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                
                        <div class="col-lg-4 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Fecha Pago</label>
                            <input type="date" name="fechaPagoR" id="fechaPagoR" class="form-control @error('fechaPagoR', 'registrarOrServ') is-invalid @enderror" value="{{ old('fechaPagoR', $fechaHoy->format('Y-m-d')) }}">
                            @error('fechaPagoR', 'registrarOrServ')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                
                        <div class="col-lg-4 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Moneda</label>
                            <select id="monedaR" name="monedaR" class="form-control @error('monedaR', 'registrarOrServ') is-invalid @enderror">
                                <option value="1" {{ old('monedaR', '1') == '1' ? 'selected' : '' }}>SOLES</option>
                                <option value="2" {{ old('monedaR', '1') == '2' ? 'selected' : '' }}>DOLARES</option>
                            </select>
                            @error('monedaR', 'registrarOrServ')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>    
                
                        <div class="col-lg-6 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Local</label>
                            <select id="sedeR" name="sedeR" class="form-control @error('sedeR', 'registrarOrServ') is-invalid @enderror" data-old="{{ old('sedeR') }}">
                            </select>
                            @error('sedeR', 'registrarOrServ')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                
                        <div class="col-lg-6 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Tipo Bien</label>
                            <select id="tipoBienR" name="tipoBienR" class="form-control @error('tipoBienR', 'registrarOrServ') is-invalid @enderror">
                                <option value="1" {{ old('tipoBienR', '1') == '1' ? 'selected' : '' }}>SERVICIO</option>
                                <option value="2" {{ old('tipoBienR', '1') == '2' ? 'selected' : '' }}>PRODUCTO</option>
                            </select>
                            @error('tipoBienR', 'registrarOrServ')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> 
                        
                        <div class="col-lg-12 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Observaciones</label>
                            <input type="text" name="observacionesR" id="observacionesR" class="form-control @error('observacionesR', 'registrarOrServ') is-invalid @enderror" value="{{ old('observacionesR') }}">
                            @error('observacionesR', 'registrarOrServ')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 pt-5">
                            <div class="card border-container-model">
                                <div class="card-header bg-model text-white">
                                    <h6 class="mb-0">Lista</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover text-center display pt-4" id="tablaPagoServicio">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-center bg-model2">Item</th>
                                                    <th class="text-center bg-model2">Descripcion</th>
                                                    <th class="text-center bg-model2">Cantidad</th>
                                                    <th class="text-center bg-model2">Precio</th>
                                                    <th class="text-center bg-model2">Importe</th>
                                                    <th class="text-center bg-model2">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tablaBody">
                                            </tbody>
                                        </table>
                                    </div>
                                    <button type="button" class="btn btn-success" id="agregarFila">Agregar Fila</button>
                                    <div class="mt-4">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-12 col-sm-12 pt-3">
                                                <input type="text" class="form-control readonly-style" id="total" name="total" value="{{ old('total', "") }}" placeholder="Total" readonly>
                                            </div>
                                            <div class="col-lg-3 col-md-12 col-sm-12 pt-3">
                                                <input type="text" class="form-control readonly-style" id="subtotal" name="subtotal" value="{{ old('subtotal', "") }}" placeholder="SubTotal" readonly>
                                            </div>
                                            <div class="col-lg-3 col-md-12 col-sm-12 pt-3">
                                                <input type="text" class="form-control readonly-style" id="igv" name="igv" value="{{ old('igv', "") }}" placeholder="IGV (18%)" readonly>
                                            </div>
                                            <div class="col-lg-3 col-md-12 col-sm-12 pt-3">
                                                <input type="number" class="form-control" id="detraccion" name="detraccion" value="{{ old('detraccion', "") }}" placeholder="Detracción (%)">
                                            </div>
                                            <div class="col-lg-3 col-md-12 col-sm-12 pt-3">
                                                <input type="text" class="form-control readonly-style" id="valor" name="valor" value="{{ old('valor', "") }}" placeholder="Valor" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <div class="modal-footer pt-5">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>             
            </div>
        </div>
    </div>
</div>
@if ($errors->getBag('registrarOrServ')->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var myModal = new bootstrap.Modal(document.getElementById('registrarOrdenServicio'), {});
        myModal.show();
    });
</script>
@endif

@if ($errors->getBag('formregistrarPay')->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var myModal = new bootstrap.Modal(document.getElementById('PayModal'), {});
        myModal.show();
    });
</script>
@endif

@php
    $filasAntiguas = [];
    if (old('descripcion')) {
        $filasAntiguas = array_map(null, old('descripcion'), old('cantidad'), old('precio'), old('importe'), old('item'), old('id'));
    }
@endphp
<script>
    $('#PayModal').on('hidden.bs.modal', function () {
        $.ajax({
            url: 'ordenDeServicios', 
            method: 'POST',
            data: {
                opcion: 'eliminarArchTemp',
                _token: '{{ csrf_token() }}', 
                temp_adjunto: '{{ session('temp_adjunto') }}'
            },
            success: function (response) {
            },
            error: function (xhr, status, error) {
            }
        });
    });
    $(document).ready(function () {
        $('#btnNuevo').on('click', function () {
            $('#registrarOrServ')[0].reset();
            $('#registrarOrServ').find('.is-invalid').removeClass('is-invalid');
            $('#tablaBody').empty();
            $('#total, #subtotal, #igv, #detraccion, #valor').val('');
            $('#proveedorR').val('').removeAttr('codigo');
            $('#tituloModalOrdenServicio').text('Registrar Orden de Servicio');
            $('#Op').val('0');
            itemCounter = 1;
            $('.alert.alert-danger').remove();
            $('#registrarOrServ').find('select').each(function () {
                $(this).prop('selectedIndex', 0);
            });
            $('#autorizanteR').val('000106');
        });
    });
    
    let datosAntiguos = @json($filasAntiguas);
    let itemCounter = 1;

    if (datosAntiguos.length > 0) {
        datosAntiguos.forEach(function(fila) {
            let descripcion = fila[0] || '';
            let cantidad = fila[1] || 1;
            let precio = fila[2] || 0;
            let importe = fila[3] || 0;
            let item = fila[4] || itemCounter;
            let id = fila[5] || 0;

            let nuevaFila = `
                <tr id="item${itemCounter}">
                    <td>${itemCounter}</td>
                    <td><input type="text" name="descripcion[]" class="form-control" value="${descripcion}" required></td>
                    <td><input type="number" name="cantidad[]" class="form-control cantidad" min="1" value="${cantidad}" required></td>
                    <td><input type="number" name="precio[]" class="form-control precio" min="0" value="${precio}" required></td>
                    <td><input type="number" name="importe[]" class="form-control importe" value="${importe}" readonly></td>
                    <td>
                        <button type="button" class="btn btn-danger eliminarFila" data-item="${itemCounter}" data-idl="${id}">Eliminar</button>
                        <input type="hidden" name="item[]" value="${item}" />
                        <input type="hidden" name="id[]" value="${id}" />
                    </td>
                </tr>
            `;
            $('#tablaBody').append(nuevaFila);
            itemCounter++;
        });

        actualizarTotales();
    }
</script>

    