$(document).ready(function(){
    $("#buscarAccesos").click(function () {
        mostrarData();
    });
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
                    return "<button class=\"btn btn-danger\" type=\"button\" onclick=\"eliminar(" + data.ID + ");\" title=\"Eliminar Acceso\"><span class=\"bi bi-trash\"></span></button>";
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
