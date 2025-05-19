
function actualizarTotales() {
    let total = 0;
    $('#tablaBody tr').each(function() {
        let importe = parseFloat($(this).find('.importe').val()) || 0;
        total += importe;
    });

    $('#total').val(total.toFixed(2));

    let subtotal = total / 1.18;
    $('#subtotal').val(subtotal.toFixed(2));

    let igv = subtotal * 0.18;
    $('#igv').val(igv.toFixed(2));

    let detraccion = parseFloat($('#detraccion').val()) || 0;
    let valor = (detraccion/100) * total;
    $('#valor').val(valor.toFixed(2));

    if ($('#tablaBody tr').length === 0) {
        $('#total, #subtotal, #igv, #valor').val('0');
    }
}

$(document).ready(function(){
    document.getElementById('importePay').addEventListener('input', calcularDetraccionYTotal);
    document.getElementById('porcentajePay').addEventListener('input', calcularDetraccionYTotal);
    configurarAutocomplete({
        selectorId: "razon_social",
        url: "ordenDeServicios",
        opcion: "buscar_razon_social",
        campos: { codigo: "Codigo", descripcion: "RazonSocial", informacion: "Doc" }
    });
    
    configurarAutocomplete({
        selectorId: "proveedorR",
        url: "ordenDeServicios",
        opcion: "buscar_razon_social",
        campos: { codigo: "Codigo", descripcion: "RazonSocial", informacion: "Doc" } 
    });
 
    $('#agregarFila').click(function() {
        let nuevaFila = `
            <tr id="item${itemCounter}">
                <td>${itemCounter}</td>
                <td><input type="text" name="descripcion[]" id="descripcion[]" rows="3" class="form-control" required></td>
                <td><input type="number" name="cantidad[]" id="cantidad[]" class="form-control cantidad" min="1" value="1" required></td>
                <td><input type="number" name="precio[]" id="precio[]" class="form-control precio" min="0" value="0" required></td>
                <td><input type="number" name="importe[]" id="importe[]" class="form-control importe" value="0" readonly></td>
                <td>
                    <button type="button" class="btn btn-danger eliminarFila" data-item="${itemCounter}" data-idl="0">Eliminar</button>
                    <input type="hidden" name="item[]" id="item[]" value="${itemCounter}" />
                    <input type="hidden" name="id[]" id="id[]" value="0" />
                </td>
            </tr>
        `;
        $('#tablaBody').append(nuevaFila);
        itemCounter++; 

        actualizarTotales();
    });

    $('#detraccion').on('input', function() {
        actualizarTotales();  
    });
    
    $(document).on('click', '.eliminarFila', function() {
        let itemId = $(this).data('item');
        let idl = $(this).data('idl'); 
    
        if (idl != 0) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Este registro será eliminado permanentemente.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'ordenDeServicios',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            opcion: 'eliminarDetalle',
                            IDL: idl
                        },
                        success: function(response) {
                            if (response.respuesta === 'success') {
                                $('#item' + itemId).remove();
                                actualizarNumeracionItems();
                                actualizarTotales();
                                Swal.fire('Eliminado', 'El registro fue eliminado.', 'success');
                            } else {
                                Swal.fire('Error', 'No se pudo eliminar el registro.', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error', 'Hubo un problema con la solicitud.', 'error');
                        }
                    });
                }
            });
        } else {
            
            $('#item' + itemId).remove();
            actualizarNumeracionItems();
            actualizarTotales();
        }
    });

    $(document).on('input', '.cantidad, .precio', function() {
        let fila = $(this).closest('tr');
        let cantidad = parseFloat(fila.find('.cantidad').val()) || 0;
        let precio = parseFloat(fila.find('.precio').val()) || 0;
        let importe = cantidad * precio;
        fila.find('.importe').val(importe.toFixed(2)); 

        actualizarTotales();  
    });

    function actualizarNumeracionItems() {
        $('#tablaBody tr').each(function(index) {
            $(this).find('td').first().text(index + 1);  
        });
        itemCounter = $('#tablaBody tr').length + 1;
    }
    
    $("#buscarOrdenServicios").click(function () {
        mostrarData();
    });

    function cargarSedes(empresaId, selectedSede) {
        if (empresaId) {
            $.ajax({
                url: 'ordenDeServicios',
                method: 'POST',
                data: {
                    empresa: empresaId,
                    _token: $('input[name="_token"]').val(),
                    opcion: "sede"
                },
                dataType: 'json',
                success: function (data) {
                    let $sedeR = $('#sedeR');
                    $sedeR.empty();
    
                    var selectedSedeValue = selectedSede || $sedeR.data('old');
    
                    $.each(data.item, function (index, item) {
                        let option = $('<option></option>')
                            .attr('value', item.ID)
                            .text(item.Nombre);
    
                        if (item.ID == selectedSedeValue) {
                            option.attr('selected', 'selected');
                        }
    
                        $sedeR.append(option);
                    });
                },
                error: function () {
                    console.error('Error al cargar las sedes');
                }
            });
        }
    }
    
    let empresaInicial = $('#empresaR').val();
    cargarSedes(empresaInicial);

    $('#empresaR').on('change', function () {
        let nuevaEmpresaId = $(this).val();
        cargarSedes(nuevaEmpresaId);
    });
});

function mostrarData() {
    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();
    var razon_social = $("#razon_social").val() ? $("#razon_social").val().split(" - ")[1].split(" (")[0] : "";
    var sede = ($("#sede").val() === "00") ? "0" : $("#sede").val();
    var pagos = $("#pagos").val();
    var codemp  = $("#codemp").val();

    Swal.fire({
        title: 'Procesando...',
        html: '<div class="spinner-border text-primary" role="status"></div>',
        allowOutsideClick: false,
        showConfirmButton: false,
    });

    tablaOrdenServicio = $("#tablaOrdenServicio").DataTable({
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
            url: "ordenDeServicios",
            type: "POST",
            data: {
                fecha_inicio: fecha_inicio,
                fecha_fin: fecha_fin,
                razon_social: razon_social,
                sede: sede,
                pagos: pagos,
                codemp: codemp,
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
            { data: 'empresa' },
            { data: 'Fecha' },
            { data: 'Transaccion' },
            { data: 'RazonSocial' },
            { data: 'RUC', visible: false },
            { data: 'FormaPago', visible: false },
            { data: 'FechaPago', visible: false },
            { data: 'Moneda', visible: false },
            { data: 'SubTotal', visible: false },
            { data: 'IGV', visible: false },
            { data: 'Total', visible: false },
            { data: 'Porcentaje', visible: false },         
            { data: 'Detraccion', visible: false },
            { data: 'Apagar', visible: false },            
            { data: 'Pagado', visible: false },
            { data: 'NroPagos', visible: false },
            { data: 'Sede', visible: false },
            { data: 'TipoBien', visible: false },
            { data: 'Observacion', visible: false },
            { data: 'FechaReg', visible: false },
            { data: 'UsuarioReg', visible: false },
            { data: 'codemp', visible: false },
            {
                data: null,
                render: function (data) {
                    let botones = "";
                    if (parseFloat(data.Apagar) > 0) {
                        botones += `
                            <button class="btn btn-warning me-1" type="button" onclick="editar('${data.Op}');" title="Editar Acceso">
                                <span class="bi bi-pencil-square"></span>
                            </button>
                            <button class="btn btn-success me-1" type="button"
                                onclick="RegPago('${data.Op}', '${data.RazonSocial}', '${data.Transaccion}');" title="Registrar Pago">
                                <span class="bi bi-cash-stack"></span>
                            </button>
                        `;
                    }
                    if (parseFloat(data.NroPagos) > 0) {
                        botones += `
                            <button class="btn btn-primary" type="button" onclick="pagos('${data.Op}','${data.Apagar}');" title="Pagos">
                                <span class="bi bi-piggy-bank-fill"></span>
                            </button>
                        `;
                    }

                    botones += `
                        <button class="btn btn-danger me-1" type="button" onclick="eliminar('${data.Op}');" title="Eliminar Orden Servicio">
                            <span class="bi bi-trash"></span>
                        </button>
                        <button class="btn btn-danger me-1" type="button" onclick="pdf('${data.Op}');" title="PDF">
                                <span class="bi bi-file-earmark-pdf-fill"></span>
                        </button>
                    `;

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
                    <span>${data.Op}</span>
                </div>
            `);
        }
    });

    $('#tablaOrdenServicio').on('xhr.dt', function () {
        Swal.close();
    });
}


$('#tablaOrdenServicio tbody').on('click', 'button.expand-btn', function () {
    var tr = $(this).closest('tr');
    var row = tablaOrdenServicio.row(tr);

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
    
    tablaOrdenServicio.columns().every(function () {
        var column = this;
        if (!column.visible()) {
            var columnTitle = $(column.header()).text().trim();
            var columnData = d[column.dataSrc()];

            if (column.dataSrc() === 'Porcentaje') {
                columnData = `${parseFloat(columnData).toFixed(2)}%`;
            }

            if (column.dataSrc() === 'Apagar') {
                const valor = parseFloat(columnData);
                const color = valor === 0 ? 'green' : 'red';
                columnData = `<div style="background-color:${color}; color:white; padding:4px 8px; border-radius:5px; display:inline-block;">${valor.toFixed(2)}</div>`;
            }

            if (columnData !== null && columnData !== undefined && columnData !== "") {
                tableContent += `
                    <tr>
                        <td><strong>${columnTitle}:</strong></td>
                        <td>${columnData}</td>
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
        text: '¿Realmente deseas eliminar este orden de servicio? Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'ordenDeServicios',
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
                    $('#tablaOrdenServicio').DataTable().ajax.reload();
                },
                error: function() {
                    Swal.fire(
                        'Error',
                        'Ocurrió un problema al eliminar el orden de servicio.',
                        'error'
                    );
                }
            });
        }
    });
}

function editar(op) {
    itemCounter = 1;
    $.ajax({
        url: 'ordenDeServicios',
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
            if (response.respuesta === 'success') {
                const cabecera = response.data.cabecera[0];
                $('#Op').val(cabecera.Op);
                $('#serieR').val(cabecera.Serie);
                $('#numeroR').val(cabecera.Numero);
                $('#fechaR').val(cabecera.Fecha.substring(0, 10));
                $('#empresaR').val(cabecera.codemp);
                cargarProvCod(cabecera.CodProv);
                $('#solicitanteR').val(cabecera.Solicitado);
                $('#autorizanteR').val(cabecera.Autorizado);
                $('#formaPagoR').val(cabecera.FormaPago);
                $('#fechaPagoR').val(cabecera.FechaPago.substring(0, 10));
                $('#monedaR').val(cabecera.Id_moneda);
                $('#sedeR').val(cabecera.Sede);
                $('#tipoBienR').val(cabecera.TipoBien);
                $('#observacionesR').val(cabecera.Observacion);
                $('#detraccion').val(parseInt(cabecera.CodDetraccion));

                const detalle = response.data.detalle;
                if (detalle !== null && detalle !== "" && detalle !== undefined) {
                    $('#tablaBody').empty();
                    detalle.forEach(function(item, index) {
                        let importe = parseFloat(item.Cantidad) * parseFloat(item.Precio);
                        let nuevaFila = `
                            <tr id="item${itemCounter}">
                                <td>${itemCounter}</td>
                                <td><input type="text" name="descripcion[]" id="descripcion[]" class="form-control" value="${item.Descripcion}" required></td>
                                <td><input type="number" name="cantidad[]" id="cantidad[]" class="form-control cantidad" min="1" value="${item.Cantidad}" required></td>
                                <td><input type="number" name="precio[]" id="precio[]" class="form-control precio" min="0" value="${item.Precio}" required></td>
                                <td><input type="number" name="importe[]" id="importe[]" class="form-control importe" value="${importe.toFixed(2)}" readonly></td>
                                <td>
                                    <button type="button" class="btn btn-danger eliminarFila" data-item="${itemCounter}" data-idl="${item.IDL}">Eliminar</button>
                                    <input type="hidden" name="item[]" id="item[]" value="${itemCounter}" />
                                    <input type="hidden" name="id[]" id="id[]" value="${item.IDL}" />
                                </td>
                            </tr>
                        `;
                        $('#tablaBody').append(nuevaFila);
                        itemCounter++; 
                    });
                    itemCounter = $('#tablaBody tr').length + 1;
                    actualizarTotales(); 
                }
                
                $('#tituloModalOrdenServicio').text('Editar Orden de Servicio');
                $('#registrarOrdenServicio').modal('show');
            } else {
                Swal.fire('Error', 'No se pudo obtener la información de la orden.', 'error');
            }
        }        
    });
}

function cargarProvCod(cod) {
    if (cod) {
        $.ajax({
            url: 'ordenDeServicios',
            method: 'POST',
            data: {
                cod: cod,
                opcion: "obtener_proveedor"
            },
            dataType: 'json',
            success: function (data) {
                if (data.respuesta === 'success' && data.prov) {
                    const prov = data.prov[0];
                    const texto = `${prov.CodProv} - ${prov.RazonSocial} (${prov.RUC})`;
                    $('#proveedorR').val(texto);
                    $('#proveedorR').attr('codigo', prov.CodProv);
                } else {
                    $('#proveedorR').val('');
                    $('#proveedorR').attr('codigo', '');
                }
            },
            error: function () {
                $('#proveedorR').val('');
                $('#proveedorR').attr('codigo', '');
            }
        });
    }
}

function RegPago(Op,RazonSocial,Transaccion){
    $.ajax({
        url: 'ordenDeServicios',
        method: 'POST',
        dataType: 'json',
        data: {
            op: Op,
            opcion: "pagos_pendientes"
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
                $('#opPay').val(Op);
                $('#proveedorPay').val(RazonSocial);
                $('#transaccionPay').val(Transaccion);
                $('#pendientePay').val(data.deuda[0].Apagar);
        
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

function pagos(Op, pendiente){
    $.ajax({
        url: 'ordenDeServicios',
        method: 'POST',
        dataType: 'json',
        data: {
            op: Op,
            opcion: "pagosXproveedor"
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
                const cabecera = data.detalle[0];

                $('#verOp').text(cabecera.Op);
                $('#verProveedor').text(cabecera.RazonSocial);
                $('#verRUC').text(cabecera.RUC);
                $('#verSede').text(cabecera.Sede);

                if ($.fn.DataTable.isDataTable('#tablaVisualizarPagos')) {
                    $('#tablaVisualizarPagos').DataTable().destroy();
                }

                $('#tablaVisualizarPagos tbody').empty();

                data.detalle.forEach(pago => {
                    $('#tablaVisualizarPagos tbody').append(`
                        <tr>
                            <td class="align-content-center">${pago.CodPago}</td>
                            <td class="align-content-center">${pago.FechaPago}</td>
                            <td class="align-content-center">${pago.Transaccion}</td>
                            <td class="align-content-center">${pago.TipoBien || '-'}</td>
                            <td class="align-content-center">${pago.Moneda}</td>
                            <td class="align-content-center">${pago.PImporte}</td>
                            <td class="align-content-center">${pago.Detraccion}</td>
                            <td class="align-content-center">${pago.Pagos}</td>
                            <td class="align-content-center">${pago.FechaReg}</td>
                            <td class="align-content-center">${pago.UsuarioReg}</td>
                        </tr>
                    `);
                });

                $('#tablaVisualizarPagos').DataTable({
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
                    responsive: true,
                    pageLength: 5,
                    lengthChange: false,
                    destroy: true,
                    ordering: false,
                    dom: 'tip',
                    footerCallback: function (row, data, start, end, display) {
                        const api = this.api();

                        const sumCol = (index) => {
                            return api
                                .column(index, { page: 'current' })
                                .data()
                                .reduce((a, b) => {
                                    a = parseFloat(a) || 0;
                                    b = parseFloat(b) || 0;
                                    return a + b;
                                }, 0)
                                .toFixed(2);
                        };

                        const totalImporte = sumCol(5);
                        $('#totalImporte').html(totalImporte);
                        $('#totalDetraccion').html(sumCol(6));
                        $('#totalPagos').html(sumCol(7));

                        const pendienteVal = parseFloat(pendiente) || 0;
                        const pendienteClass = pendienteVal === 0 ? 'text-success' : 'text-danger';

                        $('#infoImportePendiente').html(
                            `IMPORTE: <span>S/ ${totalImporte}</span> &nbsp;&nbsp; | &nbsp;&nbsp; <span class="text-danger">PENDIENTE:</span> <span class="${pendienteClass}">S/ ${pendienteVal.toFixed(2)}</span>`
                        );

                    }
                });

                $('#verPagosModal').modal('show');
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
function pdf(op) {
    $.ajax({
        url: "ordenDeServicios",
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

