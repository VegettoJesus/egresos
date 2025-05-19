@php
    $fechaHoy = \Carbon\Carbon::now(); 
    $inicioMes = $fechaHoy->copy()->startOfMonth();
@endphp
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="card border-container-model mb-4">
                <div class="card-header bg-model text-white d-flex align-items-center">
                    <h5 class="mb-0">Pagos Orden Servicios</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12 pt-3">
                                <label class="form-label">Fecha Inicio:</label>
                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio"
                                       value="{{ $inicioMes->format('Y-m-d') }}">
                            </div>
                        
                            <div class="col-lg-4 col-md-6 col-sm-12 pt-3">
                                <label class="form-label">Fecha Final:</label>
                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin"
                                       value="{{ $fechaHoy->format('Y-m-d') }}">
                            </div>

                            <div class="col-lg-4 col-md-12 col-sm-12 pt-3">
                                <label class="form-label">Sede:</label>
                                <select id="sede" name="sede" class="form-control" required>
                                    @foreach ($sedes as $sede)
                                        @if ($sede->ID == '00' && strtoupper(trim($sede->Nombre)) == 'NINGUNO')
                                            <option value="00">TODOS</option>
                                        @elseif (!(strtoupper(trim($sede->Nombre)) == 'NINGUNO'))
                                            <option value="{{ $sede->ID }}">{{ strtoupper($sede->Nombre) }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>                              

                            <div class="col-lg-6 col-md-12 col-sm-12 pt-3">
                                <label class="form-label">Proveedor:</label>
                                <input id="proveedor" name="proveedor" codigo="" class="form-control ui-autocomplete-input" />
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 pt-4 mt-3">
                                <div class="position-relative pt-2">
                                    <button class="btn btn-success w-100 text-center position-relative" title="Buscar" id="buscarPagos" type="button">
                                        <i class="bi bi-search position-absolute start-0 top-50 translate-middle-y ms-3"></i>
                                        Buscar
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
                            <table class="table table-bordered table-hover text-center display pt-4" id="tablaPagosOS">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center bg-model2">IDPago</th>
                                        <th class="text-center bg-model2">OpOC</th>
                                        <th class="text-center bg-model2">Sede</th>
                                        <th class="text-center bg-model2">RUC</th>
                                        <th class="text-center bg-model2">Proveedor</th>
                                        <th class="text-center bg-model2 d-none">F. Pago</th>
                                        <th class="text-center bg-model2 d-none">Tipo Pago</th>
                                        <th class="text-center bg-model2 d-none">Banco</th>
                                        <th class="text-center bg-model2 d-none">Operacion</th>
                                        <th class="text-center bg-model2 d-none">Pendiente</th>
                                        <th class="text-center bg-model2 d-none">Moneda</th>
                                        <th class="text-center bg-model2 d-none">Importe</th>
                                        <th class="text-center bg-model2 d-none">Detraccion</th>
                                        <th class="text-center bg-model2 d-none">Pagado</th>
                                        <th class="text-center bg-model2 d-none">Porcentaje</th>
                                        <th class="text-center bg-model2 d-none">Autorizado</th>
                                        <th class="text-center bg-model2 d-none">Fecha Reg.</th>
                                        <th class="text-center bg-model2 d-none">Usuario Reg.</th>
                                        <th class="text-center bg-model2 d-none">codemp</th>
                                        <th class="text-center bg-model2 d-none">Empresa</th>
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
                <form id="formregistrarPay" method="POST" action="{{ url('compras/pagosPorOS') }}"  enctype="multipart/form-data">
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
                                    <option value="{{ $autorizadoP->CodEmpleado }}" {{ old('autorizadoPay') == $autorizadoP->CodEmpleado ? 'selected' : '' }}> {{ strtoupper($autorizadoP->Empleado) }} </option>
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
                            <div id="archivoPdfPreview"></div>
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
@if ($errors->getBag('formregistrarPay')->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var myModal = new bootstrap.Modal(document.getElementById('PayModal'), {});
        myModal.show();
    });
</script>
@endif
<script>
    $('#PayModal').on('hidden.bs.modal', function () {
        $('#adjuntarPay').val('');
        $.ajax({
            url: 'pagosPorOS', 
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
</script>