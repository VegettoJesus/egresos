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
                                <select id="Usuario" name="Usuario" class="form-control" required>
                                    @foreach ($usuarios as $usuario)
                                        <option value="{{ $usuario->CodUsuario }}" {{ $usuario->CodUsuario == 0 ? 'selected' : '' }}>
                                            {{ $usuario->NomUsuario }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>                                    
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <label class="form-label">Menu Principal:</label>
                                <select id="MenuPrincipal" name="MenuPrincipal" class="form-control" required>
                                    @foreach ($menuPrincipal as $menuPrincipal)
                                        <option value="{{ $menuPrincipal->ID }}" {{ $menuPrincipal->ID == 0 ? 'selected' : '' }}>
                                            {{ $menuPrincipal->Nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 pt-4">
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