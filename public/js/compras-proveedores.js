$(document).ready(function () {
    $("#btnBuscar").click(function () {
        mostrarData();
    });
    
    $.ajax({
        url: 'proveedores',
        type: 'POST',
        data: { opcion: 'paises' },
        dataType: 'json',
        success: function(response) {
            if (response.respuesta === 'success') {
                let paises = response.paises;
                paises.forEach(function(pais) {
                    $('#paisR').append(`<option value="${pais.ID}">${pais.Nombre}</option>`);
                });

                $('#paisR').val('20').trigger('change');
            } else {
                console.error('Error al cargar países:', response.paises);
            }
        }
    });
    cargarDatosUbicacion()
});

function mostrarData() {
    var tipo_Persona = $("#tipo_Persona").val();
    var proveedor = $("#proveedor").val();

    Swal.fire({
        title: 'Procesando...',
        html: '<div class="spinner-border text-primary" role="status"></div>',
        allowOutsideClick: false,
        showConfirmButton: false,
    });

    tablaAccesos = $("#tablaAccesos").DataTable({
        destroy: true,
        responsive: true,
        ordering: false,
        autoWidth: false,
        scrollX: false,
        dom: 'Bfrtip', 
        buttons: [
            {
                extend: 'excel', 
                text: 'Exportar Excel', 
                className: 'btn btn-primary'
            }
        ],
        lengthMenu: [20, 50, 100], 
        ajax: {
            url: "proveedores",
            type: "POST",
            data: {
                tipo_Persona: tipo_Persona,
                proveedor: proveedor,
                opcion: 'Listar'
            },
            dataSrc: function (data) {      
                if (data.respuesta == "success" && data.accesos !== "vacio") {
                    return data.listar;
                } else {
                    return [];
                }
            }
        },
        columns: [
            { data: 'Op' },
            { data: 'TipoPersona' },
            { data: 'Proveedor' },
            { data: 'RUC' },
            { data: 'Direccion', visible: false },
            { data: 'Distrito', visible: false },
            { data: 'Provincia', visible: false },
            { data: 'Celular', visible: false },
            { data: 'Telefono', visible: false },
            { data: 'Email', visible: false },
            { data: 'TipoDoc', visible: false },
            { data: 'Documento', visible: false },
            { data: 'Apellidos', visible: false},
            { data: 'Nombres', visible: false},
            { data: 'Observaciones', visible: false },
            { data: 'Fechareg', visible: false },
            { data: 'Usuarioreg', visible: false },
            { data: 'Estado', visible: false },
            {
                data: null,
                render: function (data) {
                    return `
                        <button class="btn btn-warning me-1" type="button" onclick="editar(${data.Op});" title="Editar Acceso">
                            <span class="bi bi-pencil-square"></span>
                        </button>
                        <button class="btn btn-danger" type="button" onclick="eliminar(${data.Op});" title="Eliminar Acceso">
                            <span class="bi bi-trash"></span>
                        </button>
                    `;
                }
            }
        ],
        language: {
            "processing": "Procesando...",
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "emptyTable": "No se encontraron registros",
            "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "search": "Buscar:",
            "infoThousands": ",",
            "loadingRecords": "Cargando...",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            },
            "info": "Mostrando _START_ a _END_ de _TOTAL_ registros"
        },
        rowCallback: function(row, data) {
            $('td', row).eq(0).html(`
                <div class="d-flex align-items-center">
                    <button class="expand-btn btn btn-success p-1 me-2">
                        <i class="bi bi-caret-right-fill"></i>
                    </button>
                    <span>${data.Op}</span>
                </div>
            `);
        }
    });

    $('#tablaAccesos').on('xhr.dt', function () {
        Swal.close();
    });
}


$('#tablaAccesos tbody').on('click', 'button.expand-btn', function () {
    var tr = $(this).closest('tr');
    var row = tablaAccesos.row(tr);

    if (row.child.isShown()) {
        row.child.hide();
        tr.removeClass('shown');
        $(this).html('<i class="bi bi-caret-right-fill"></i>'); 
    } else {
        row.child(format(row.data())).show();
        tr.addClass('shown');
        $(this).html('<i class="bi bi-caret-down-fill"></i>'); 
    }
});

function format(d) {
    let tableContent = '<table class="table table-bordered">';
    
    tablaAccesos.columns().every(function () {
        var column = this;
        if (!column.visible()) { 
            var columnTitle = $(column.header()).text().trim(); 
            var columnData = d[column.dataSrc()];  

            if (columnData) {
                tableContent += `
                    <tr>
                        <td><strong>${columnTitle}:</strong> ${columnData}</td>
                    </tr>
                `;
            }
        }
    });

    tableContent += '</table>';
    return tableContent;
}

function eliminar(op) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: '¿Realmente deseas eliminar este proveedor? Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'proveedores',
                type: 'POST',
                data: { opcion: 'eliminar', op: op },
                dataType: 'json',
                success: function(response) {
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
                        title: 'El proveedor ha sido eliminado correctamente.'
                    });
                    $('#tablaAccesos').DataTable().ajax.reload();
                },
                error: function() {
                    Swal.fire(
                        'Error',
                        'Ocurrió un problema al eliminar el proveedor.',
                        'error'
                    );
                }
            });
        }
    });
}
function editar(op) {
    $.ajax({
        url: 'proveedores',
        type: 'POST',
        dataType: 'json',
        data: {
            opcion: 'ObtenerData',
            op: op
        },
        beforeSend: function() {
            Swal.fire({
                title: 'Cargando...',
                html: '<div class="spinner-border text-primary" role="status"></div>',
                allowOutsideClick: false,
                showConfirmButton: false,
            });
        },
        success: function(response) {
            Swal.close();
            console.log(response);
            if (response.respuesta === 'success') {
                
                let proveedor = response.data[0];
        
                $('#CodProv').val(proveedor.CodProv);
                $('#tipo_PersonaR').val(proveedor.Tipo);
                $('#razonSocialR').val(proveedor.RazonSocial ?? '');
                $('#rucR').val(proveedor.RUC);
                $('#tipoDocR').val(proveedor.TipoDoc ?? '0');
                $('#docR').val(proveedor.Documento ?? '');
                $('#nombresR').val(proveedor.Nombres ?? '');
                $('#apellidosR').val(proveedor.Apellidos ?? '');
                cargarDatosUbicacion(proveedor.idpais, proveedor.iddpto, proveedor.idprov, proveedor.iddto);
                $('#direccionR').val(proveedor.Direccion ?? '');
                $('#telefonoR').val(proveedor.Telefono ?? '');
                $('#celularR').val(proveedor.Celular ?? '');
                $('#correoR').val(proveedor.Correo ?? '');
                $('#observacionesR').val(proveedor.Observaciones ?? '');
                $('#estadoR').val(proveedor.Estado ?? '1');
                $('.modal-title').text('Editar Proveedor');
                $('#registrarProveedor').modal('show');
            } else {
                Swal.fire('Error', 'No se pudo obtener la información del proveedor.', 'error');
            }
        }        
    });
}
$('#btnNuevo').on('click', function () {
    $('.modal-title').text('Registrar Proveedor');
    $('#CodProv').val('0');
    $('#tipo_PersonaR').val('1');
    $('#razonSocialR').val('');
    $('#rucR').val('');
    $('#tipoDocR').val('0');
    $('#docR').val('');
    $('#nombresR').val('');
    $('#apellidosR').val('');
    cargarDatosUbicacion("20", "23", "20", "25");
    $('#direccionR').val('');
    $('#telefonoR').val('');
    $('#celularR').val('');
    $('#correoR').val('');
    $('#observacionesR').val('');
    $('#estadoR').val('1');
});
