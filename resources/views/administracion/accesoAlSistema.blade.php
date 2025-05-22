<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="card border-container-model mb-4">
                <div class="card-header bg-model text-white d-flex align-items-center">
                    <h5 class="mb-0">Acceso al Sistema</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <label class="form-label">Usuario:</label>
                                <select id="Usuario" name="Usuario" class="form-select" required>
                                    @foreach ($usuarios as $usuario)
                                        <option value="{{ $usuario->CodUsuario }}" {{ $usuario->CodUsuario == 0 ? 'selected' : '' }}>
                                            {{ $usuario->NomUsuario }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>                                    
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <label class="form-label">Menu Principal:</label>
                                <select id="MenuPrincipal" name="MenuPrincipal" class="form-select" required>
                                    @foreach ($menuPrincipal as $menuPrincipal)
                                        <option value="{{ $menuPrincipal->ID }}" {{ $menuPrincipal->ID == 0 ? 'selected' : '' }}>
                                            {{ $menuPrincipal->Nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($permiso_nuevo)
                                <div class="col-lg-2 col-md-12 col-sm-12 pt-4">
                                    <div class="position-relative pt-2">
                                        <button class="btn btn-primary w-100 text-center position-relative" title="Nuevo" id="btnNuevo" data-bs-toggle="modal" data-bs-target="#registrarAccesoSistema" type="button">
                                            <i class="bi bi-plus-lg position-absolute start-0 top-50 translate-middle-y ms-3"></i>
                                            Nuevo
                                        </button>
                                    </div>
                                </div>
                            @endif
                            <div class="col-lg-2 col-md-12 col-sm-12 pt-4">
                                <div class="position-relative pt-2">
                                    <button class="btn btn-success w-100 text-center position-relative" title="Buscar" id="buscarAccesos" type="button">
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
                            <table class="table table-bordered table-hover text-center pt-4" id="tablaAccesos">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center bg-model2">ID</th>
                                        <th class="text-center bg-model2">Usuario</th>
                                        <th class="text-center bg-model2">Menu Principal</th>
                                        <th class="text-center bg-model2">Menu</th>
                                        <th class="text-center bg-model2">Nuevo</th>
                                        <th class="text-center bg-model2">Modificar</th>
                                        <th class="text-center bg-model2">Eliminar</th>
                                        <th class="text-center bg-model2">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                </tfoot>
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
<div class="modal fade" id="registrarAccesoSistema" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="registrarAccesoModalLabel" aria-hidden="true">    
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Registrar Acceso</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formregistrarAcceso" method="POST" action="{{ url('administracion/accesoAlSistema') }}"  enctype="multipart/form-data">
                    @csrf
                    @if ($errors->getBag('formregistrarAcceso')->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->getBag('formregistrarAcceso')->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="row">
                        <input type="hidden" name="opcion" id="opcion" value="Registrar" />

                        <div class="col-lg-12 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Usuario</label>
                            <select name="usuario" id="usuario" class="form-select readonly-style @error('usuario', 'formregistrarAcceso') is-invalid @enderror" readonly>
                                <option value="">Seleccione un usuario</option>
                                @foreach ($listUsuarios as $usuario)
                                    <option value="{{ $usuario->CodUsuario }}">{{ $usuario->Nombre }}</option>
                                @endforeach
                            </select>
                            @error('usuario', 'formregistrarAcceso')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 pt-3">
                            <label class="form-label">Menu</label>
                            <select name="menuA" id="menuA" class="form-select readonly-style @error('menuA', 'formregistrarAcceso') is-invalid @enderror" readonly>
                                <option value="">Seleccione un menú</option>
                                @foreach ($listMenus as $menu)
                                    <option value="{{ $menu->id_menu }}">{{ $menu->Principal }}</option>
                                @endforeach
                            </select>
                            @error('menuA', 'formregistrarAcceso')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 pt-4">
                            <label class="form-label d-block text-center mb-3">Permisos</label>
                            <div class="d-flex justify-content-center flex-wrap gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permiso_nuevo" id="permiso_nuevo">
                                    <label class="form-check-label" for="permiso_nuevo">Nuevo</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permiso_modificar" id="permiso_modificar">
                                    <label class="form-check-label" for="permiso_modificar">Modificar</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permiso_eliminar" id="permiso_eliminar">
                                    <label class="form-check-label" for="permiso_eliminar">Eliminar</label>
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
@if ($errors->getBag('formregistrarAcceso')->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var myModal = new bootstrap.Modal(document.getElementById('registrarAccesoSistema'), {});
        myModal.show();
    });
</script>
@endif
<script>
    $(document).ready(function () {
        $('#btnNuevo').on('click', function () {
            $('#formregistrarAcceso')[0].reset();
            $('#formregistrarAcceso').find('.is-invalid').removeClass('is-invalid');
            $('#tablaBody').empty();
            $('#registrarAccesoSistema .modal-title').text('Registrar Acceso');
            $('.alert.alert-danger').remove();
            $('#formregistrarAcceso').find('select').each(function () {
                $(this).prop('selectedIndex', 0);
                $(this).removeAttr('readonly').removeClass('readonly-style');
            });
        });
    });
</script>