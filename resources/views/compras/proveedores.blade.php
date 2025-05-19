<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="card border-container-model mb-4">
                <div class="card-header bg-model text-white d-flex align-items-center">
                    <h5 class="mb-0">Proveedores</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <label class="form-label">T. Persona:</label>
                                <select id="tipo_Persona" name="tipo_Persona" class="form-control" required>
                                    <option value="0">Todos</option>
                                    <option value="1">Persona Natural</option>
                                    <option value="2">Persona Juridica</option>
                                </select>
                            </div>                                    
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <label class="form-label">Proveedor:</label>
                                <input id="proveedor" name="proveedor" class="form-control" required/>
                            </div>
                            <div class="col-lg-2 col-md-12 col-sm-12 pt-4">
                                <div class="position-relative pt-2">
                                    <button class="btn btn-success w-100 text-center position-relative" title="Buscar" id="btnBuscar" type="button">
                                        <i class="bi bi-search position-absolute start-0 top-50 translate-middle-y ms-3"></i>
                                        Buscar
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-12 col-sm-12 pt-4">
                                <div class="position-relative pt-2">
                                    <button class="btn btn-primary w-100 text-center position-relative" title="Nuevo" id="btnNuevo" data-bs-toggle="modal" data-bs-target="#registrarProveedor" type="button">
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
                            <table class="table table-bordered table-hover text-center display pt-4" id="tablaAccesos">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center bg-model2">Op</th>
                                        <th class="text-center bg-model2">Tipo Persona</th>
                                        <th class="text-center bg-model2">Proveedor</th>
                                        <th class="text-center bg-model2">RUC</th>
                                        <th class="text-center bg-model2 d-none">Direccion</th>
                                        <th class="text-center bg-model2 d-none">Distrito</th>
                                        <th class="text-center bg-model2 d-none">Provincia</th>
                                        <th class="text-center bg-model2 d-none">Celular</th>
                                        <th class="text-center bg-model2 d-none">Telefono</th>
                                        <th class="text-center bg-model2 d-none">Email</th>
                                        <th class="text-center bg-model2 d-none">Tipo Doc.</th>
                                        <th class="text-center bg-model2 d-none">Documento</th>
                                        <th class="text-center bg-model2 d-none">Apellidos</th>
                                        <th class="text-center bg-model2 d-none">Nombres</th>
                                        <th class="text-center bg-model2 d-none">Observaciones</th>
                                        <th class="text-center bg-model2 d-none">Fecha Reg.</th>
                                        <th class="text-center bg-model2 d-none">Usuario Reg.</th>
                                        <th class="text-center bg-model2 d-none">Estado</th>
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
<div class="modal fade modal-lg" id="registrarProveedor" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Registrar Proveedor</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if ($errors->getBag('registrarProv')->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->getBag('registrarProv')->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                <form id="registrarProv" method="POST" action="{{ url('compras/proveedores') }}">
                    @csrf
                    <div class="row">
                        <input type="hidden" name="opcion" value="Registrar" />
                        <input type="hidden" name="CodProv" id="CodProv" value="0" />
                        <div class="col-lg-4 col-md-12 col-sm-12 pt-2">
                            <label class="form-label">T. Persona:</label>
                            <select id="tipo_PersonaR" name="tipo_PersonaR" class="form-control @error('tipo_PersonaR') is-invalid @enderror" value="{{ old('tipo_PersonaR') }}">
                                <option value="1" {{ old('tipo_PersonaR') == 1 ? 'selected' : '' }}>Persona Natural</option>
                                <option value="2" {{ old('tipo_PersonaR') == 2 ? 'selected' : '' }}>Persona Juridica</option>
                            </select>
                            @error('tipo_PersonaR')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-lg-4 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Razón Social</label>
                            <input type="text" name="razonSocialR" id="razonSocialR" class="form-control @error('razonSocialR') is-invalid @enderror" value="{{ old('razonSocialR') }}">
                            
                            @error('razonSocialR')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-lg-4 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">RUC:</label>
                            <input id="rucR" name="rucR" type="number" class="form-control @error('rucR') is-invalid @enderror" value="{{ old('rucR') }}"/>
                            @error('rucR')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-4 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Tipo Documento</label>
                            <select id="tipoDocR" name="tipoDocR" class="form-control @error('tipoDocR') is-invalid @enderror">
                                <option value="0" {{ old('tipoDocR', '0') == '0' ? 'selected' : '' }}>Ninguno</option>
                                <option value="1" {{ old('tipoDocR', '0') == '1' ? 'selected' : '' }}>DNI</option>
                                <option value="2" {{ old('tipoDocR', '0') == '2' ? 'selected' : '' }}>Carnet de Extranjeria</option>
                            </select>
                            @error('tipoDocR')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>                        

                        <div class="col-lg-4 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Documento</label>
                            <input type="number" name="docR" id="docR" class="form-control @error('docR') is-invalid @enderror" value="{{ old('docR') }}">
                            
                            @error('docR')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-4 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Nombres</label>
                            <input type="text" name="nombresR" id="nombresR" class="form-control @error('nombresR') is-invalid @enderror" value="{{ old('nombresR') }}">
                            
                            @error('nombresR')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-4 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Apellidos</label>
                            <input type="text" name="apellidosR" id="apellidosR" class="form-control @error('apellidosR') is-invalid @enderror" value="{{ old('apellidosR') }}">
                            
                            @error('apellidosR')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-lg-4 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Pais:</label>
                            <select id="paisR" name="paisR" class="form-control @error('paisR') is-invalid @enderror" value="{{ old('paisR') }}">
                            </select>
                            @error('paisR')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-lg-4 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Departamento:</label>
                            <select id="departamentoR" name="departamentoR" class="form-control @error('departamentoR') is-invalid @enderror" value="{{ old('departamentoR') }}">
                            </select>
                            @error('departamentoR')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-lg-4 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Provincia:</label>
                            <select id="provinciaR" name="provinciaR" class="form-control @error('provinciaR') is-invalid @enderror" value="{{ old('provinciaR') }}">
                            </select>
                            @error('provinciaR')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-4 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Distrito:</label>
                            <select id="distritoR" name="distritoR" class="form-control @error('distritoR') is-invalid @enderror" value="{{ old('distritoR') }}">
                            </select>
                            @error('distritoR')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-lg-4 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Dirección:</label>
                            <input id="direccionR" name="direccionR" type="text" class="form-control @error('direccionR') is-invalid @enderror" value="{{ old('direccionR') }}"/>
                            @error('direccionR')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-4 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Teléfono:</label>
                            <input id="telefonoR" name="telefonoR" type="text" class="form-control @error('telefonoR') is-invalid @enderror" value="{{ old('telefonoR') }}"/>
                            @error('telefonoR')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-4 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Celular:</label>
                            <input id="celularR" name="celularR" type="text" class="form-control @error('celularR') is-invalid @enderror" value="{{ old('celularR') }}"/>
                            @error('celularR')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-4 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Correo:</label>
                            <input id="correoR" name="correoR" type="text" class="form-control @error('correoR') is-invalid @enderror" value="{{ old('correoR') }}"/>
                            @error('correoR')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-8 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Observaciones</label>
                            <input type="text" name="observacionesR" id="observacionesR" class="form-control @error('observacionesR') is-invalid @enderror" value="{{ old('observacionesR') }}">
                            
                            @error('observacionesR')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-4 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Estado:</label>
                            <select id="estadoR" name="estadoR" class="form-control @error('estadoR') is-invalid @enderror" value="{{ old('estadoR') }}">
                                <option value="1" {{ old('estadoR') == 1 ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ old('estadoR') == 1 ? 'selected' : '' }}>Inactivo</option>
                            </select>
                            @error('estadoR')
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
@if ($errors->getBag('registrarProv')->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var myModal = new bootstrap.Modal(document.getElementById('registrarProveedor'), {});
        myModal.show();
    });
</script>
@endif