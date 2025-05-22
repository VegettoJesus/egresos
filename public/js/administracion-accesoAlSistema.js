$(document).ready(function(){
    $("#buscarAccesos").click(function () {
        mostrarData();
    });
    mostrarData();
});

function mostrarData() {
    var usuario = $("#Usuario").val();
    var menuPrincipal = $("#MenuPrincipal").val();

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
        lengthMenu: [ 20, 50, 100], 
        ajax: {
            url: "accesoAlSistema",
            type: "POST",
            data: {
                usuario: usuario,
                menuPrincipal: menuPrincipal,
                opcion: 'Listar'
            },
            dataSrc: function(data) {      
                if (data.respuesta == "success" && data.accesos !== "vacio") {
                    return data.accesos;
                } else {
                    return [];
                }
            }
        },
        columns: [
            { data: 'ID' },
            { data: 'Usuario' },
            { data: 'MenuPrincipal' },
            { data: 'Menu' },
            {
                data: null,
                render: function (data) {
                    return '<input type="checkbox" ' + (data.Nuevo == 1 ? 'checked' : '') + ' disabled>';
                }
            },
            {
                data: null,
                render: function (data) {
                    return '<input type="checkbox" ' + (data.Modificar == 1 ? 'checked' : '') + ' disabled>';
                }
            },
            {
                data: null,
                render: function (data) {
                    return '<input type="checkbox" ' + (data.Eliminar == 1 ? 'checked' : '') + ' disabled>';
                }
            },
            {
                data: null,
                render: function (data) {
                let botones = '';

                if (permiso.modificar) {
                    botones += `<button class="btn btn-warning me-1" type="button" onclick="editar(${data.ID}, ${data.id_usuario});" title="Editar Acceso"><span class="bi bi-pencil-square"></span></button>`;
                }

                if (permiso.eliminar) {
                    botones += `<button class="btn btn-danger" type="button" onclick="eliminar(${data.ID});" title="Eliminar Acceso"><span class="bi bi-trash"></span></button>`;
                }

                return botones;
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
        }
    });
}
function eliminar(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: '¿Realmente deseas eliminar este acceso al sistema? Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'accesoAlSistema',
                type: 'POST',
                data: { opcion: 'eliminar', id: id },
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
                        title: 'El acceso ha sido eliminado correctamente.'
                    });
                    $('#tablaAccesos').DataTable().ajax.reload();
                },
                error: function() {
                    Swal.fire(
                        'Error',
                        'Ocurrió un problema al eliminar el acceso.',
                        'error'
                    );
                }
            });
        }
    });
}

function editar(id_menu, id_usuario) {
    $.ajax({
        url: 'accesoAlSistema',
        type: 'POST',
        dataType: 'json',
        data: {
            opcion: 'ObtenerData',
            id_menu: id_menu,
            id_usuario: id_usuario
        },
        beforeSend: function () {
            Swal.fire({
                title: 'Cargando...',
                html: '<div class="spinner-border text-primary" role="status"></div>',
                allowOutsideClick: false,
                showConfirmButton: false,
            });
        },
        success: function (response) {
            Swal.close();
            if (response.respuesta === 'success') {
                const acceso = response.data[0];
                console.log(acceso);

                $('#usuario').val(acceso.id_usuario)
                    .addClass('readonly-style')
                    .prop('readonly', true);

                $('#menuA').val(acceso.id_menu)
                    .addClass('readonly-style')
                    .prop('readonly', true);

                $('#permiso_nuevo').prop('checked', acceso.Nuevo == 1);
                $('#permiso_modificar').prop('checked', acceso.Modificar == 1);
                $('#permiso_eliminar').prop('checked', acceso.Eliminar == 1);

                $('#registrarAccesoSistema .modal-title').text('Editar Acceso');
                $('#registrarAccesoSistema').modal('show');
            } else {
                Swal.fire('Error', 'No se pudo obtener la información del acceso.', 'error');
            }
        }
    });
}

