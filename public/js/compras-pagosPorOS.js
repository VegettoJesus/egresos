$(document).ready(function(){
    document.getElementById('importePay').addEventListener('input', calcularDetraccionYTotal);
    document.getElementById('porcentajePay').addEventListener('input', calcularDetraccionYTotal);
    configurarAutocomplete({
        selectorId: "proveedor",
        url: "ordenDeServicios",
        opcion: "buscar_razon_social",
        campos: { codigo: "Codigo", descripcion: "RazonSocial", informacion: "Doc" }
    });
    $('#adjuntarPay').on('change', function () {
        if (this.files.length > 0) {
            $('#archivoPdfPreview').html('');
        }
    });
});

$("#buscarPagos").click(function () {
    mostrarData();
});

function mostrarData() {
    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();
    var razon_social = $("#proveedor").val() ? $("#proveedor").val().split(" - ")[1].split(" (")[0] : "";
    var sede = $("#sede").val();

    Swal.fire({
        title: 'Procesando...',
        html: '<div class="spinner-border text-primary" role="status"></div>',
        allowOutsideClick: false,
        showConfirmButton: false,
    });

    tablaPagosOS = $("#tablaPagosOS").DataTable({
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
            url: "pagosPorOS",
            type: "POST",
            data: {
                fecha_inicio: fecha_inicio,
                fecha_fin: fecha_fin,
                razon_social: razon_social,
                sede: sede,
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
            { data: 'IDPago' },
            { data: 'Op' },
            { data: 'Sede' },
            { data: 'RUC' },
            { data: 'RazonSocial' },
            { data: 'FechaPago', visible: false },
            { data: 'TipoPago', visible: false },
            { data: 'Banco', visible: false },
            { data: 'Operacion', visible: false },
            { data: 'Pendiente', visible: false },
            { data: 'Moneda', visible: false },
            { data: 'Importe', visible: false },         
            { data: 'Detraccion', visible: false },
            { data: 'Pagado', visible: false },            
            { data: 'Porcentaje', visible: false },
            { data: 'Autorizado', visible: false },
            { data: 'FechaReg', visible: false },
            { data: 'Usuarioreg', visible: false },
            { data: 'codemp', visible: false },
            { data: 'empresa', visible: false },
            {
                data: null,
                render: function (data) {
                    let botones = "";
                    botones += `
                        <button class="btn btn-warning me-1" type="button" onclick="editar('${data.IDPago}');" title="Editar Pago">
                            <span class="bi bi-pencil-square"></span>
                        </button>
                        <button class="btn btn-danger me-1" type="button" onclick="eliminar('${data.IDPago}');" title="Eliminar Pago">
                            <span class="bi bi-trash"></span>
                        </button>
                        <button class="btn btn-danger me-1" type="button" onclick="GenerarPdf('${data.IDPago}');" title="Generar PDF">
                                <span class="bi bi-file-earmark-pdf-fill"></span>
                        </button>
                    `;

                    if (data.archivo_pdf != null) {
                        botones += `
                            <button class="btn btn-success me-1" type="button" onclick="VisualizarPdf('${data.archivo_pdf}');" title="PDF Pago">
                                <span class="bi bi-file-earmark-arrow-up"></span>
                            </button>
                        `;
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
        },
        rowCallback: function(row, data) {
            $('td', row).eq(0).html(`
                <div class="d-flex align-items-center">
                    <button class="expand-btn btn btn-success p-1 me-2">
                        <i class="bi bi-caret-right-fill"></i>
                    </button>
                    <span>${data.IDPago}</span>
                </div>
            `);
        }
    });

    $('#tablaPagosOS').on('xhr.dt', function () {
        Swal.close();
    });
}

$('#tablaPagosOS tbody').on('click', 'button.expand-btn', function () {
    var tr = $(this).closest('tr');
    var row = tablaPagosOS.row(tr);

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
    
    tablaPagosOS.columns().every(function () {
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

function editar(Op){
    $.ajax({
        url: 'pagosPorOS',
        method: 'POST',
        dataType: 'json',
        data: {
            op: Op,
            opcion: "obtenerPago"
        },
        beforeSend: function() {
            Swal.fire({
                title: 'Cargando...',
                html: '<div class="spinner-border text-primary" role="status"></div>',
                allowOutsideClick: false,
                showConfirmButton: false,
            });
        },
        success: function (data) {
            Swal.close();
            if (data.respuesta === 'success') {
                const pago = data.info[0]

                $('#idPay').val(pago.IDPago);
                $('#opPay').val(pago.Op);
                $('#fechaPay').val(pago.FechaPago.substring(0,10));
                $('#proveedorPay').val(pago.Proveedor);
                $('#transaccionPay').val(pago.Transaccion);
                $('#tipoPay').val(pago.TipoPago);
                $('#bancoPay').val(pago.codBanco);
                $('#numOperacionPay').val(pago.Operacion);
                $('#autorizadoPay').val(pago.Autorizado);
                $('#pendientePay').val(pago.Pendiente);
                $('#importePay').val(pago.Importe);
                $('#porcentajePay').val(pago.DetracPorc);
                $('#observacionPay').val(pago.Observacion);
                $('#monedaPay').val(pago.Id_moneda);
                calcularDetraccionYTotal()
                if (data.archivo_pdf) {
                    $('#archivoPdfPreview').html(`
                        <div class="mt-3">
                            <a href="${data.archivo_pdf}" target="_blank" class="btn btn-outline-primary btn-sm">
                                Ver archivo PDF adjunto
                            </a>
                        </div>
                    `);
                } else {
                    $('#archivoPdfPreview').html('');
                }
                $('#PayModal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo obtener la información.',
                    confirmButtonText: 'Aceptar'
                });
            }
        }
    });
}

function calcularDetraccionYTotal() {
    let importePay = parseFloat(document.getElementById('importePay').value) || 0;
    let porcentajePay = parseFloat(document.getElementById('porcentajePay').value) || 0;
    let detracPay = (porcentajePay / 100) * importePay;
    let totalPay = importePay - detracPay;
    document.getElementById('detracPay').value = detracPay.toFixed(2);  
    document.getElementById('totalPay').value = totalPay.toFixed(2);   
}

function eliminar(op) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: '¿Realmente deseas eliminar este pago? Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'pagosPorOS',
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
                        title: 'El pago ha sido eliminado correctamente.'
                    });
                    $('#tablaPagosOS').DataTable().ajax.reload();
                },
                error: function() {
                    Swal.fire(
                        'Error',
                        'Ocurrió un problema al eliminar el pago.',
                        'error'
                    );
                }
            });
        }
    });
}

function VisualizarPdf(url) {
    if (url) {
        window.open(url, '_blank');
    } else {
        Swal.fire('No se encontró el archivo PDF.');
    }
}

function GenerarPdf(op) {
    $.ajax({
        url: "pagosPorOS",
        type: "POST",
        dataType: "JSON",
        data: {
            opcion: "PDF", 
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
        success: function (response) {
            Swal.close();
            if (response.respuesta === "success") {
                $("#modalVistaPreviaPDF").modal("show");
                $('#modalVistaPreviaPDF .modal-body #divIframePDF').html("");

                let pdfDataUrl = 'data:application/pdf;base64,' + response.pdf.data;

                let pdf = '<iframe id="pdfIframe" src="' + pdfDataUrl + '" frameborder="0" width="100%" style="width: 100%; height: 65vh; display: block;"></iframe>';
                $('#modalVistaPreviaPDF .modal-body #divIframePDF').html(pdf);
            } else {
                $('#modalVistaPreviaPDF .modal-body #divIframePDF').html("");
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo obtener la información.',
                    confirmButtonText: 'Aceptar'
                });
            }
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo obtener la información.',
                confirmButtonText: 'Aceptar'
            });
        }
    });
}