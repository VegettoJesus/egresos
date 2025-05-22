const permisosRaw = document.getElementById('permisos-json').textContent;
const permiso = JSON.parse(permisosRaw);
$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});

const menu = document.getElementById('menu');
const sidebar = document.getElementById('sidebar');
sidebar.classList.add('menu-toggle');
const main = document.getElementById('main')
main.classList.add('menu-toggle');
menu.addEventListener('click', () => {
    sidebar.classList.toggle('menu-toggle');
    main.classList.toggle('menu-toggle')
});

document.querySelectorAll('.parent-menu').forEach(item => {
    item.addEventListener('click', function (e) {
        e.preventDefault();

        let submenu = this.nextElementSibling;

        document.querySelectorAll('.submenu').forEach(otherSubmenu => {
            if (otherSubmenu !== submenu) {
                otherSubmenu.style.display = 'none';
            }
        });

        if (submenu) {
            submenu.style.display = (submenu.style.display === 'none' || submenu.style.display === '') ? 'block' : 'none';
        }

        document.querySelectorAll('.sidebar a').forEach(a => a.classList.remove('selected'));

        this.classList.add('selected');
    });
});

document.querySelectorAll('.submenu a').forEach(item => {
    item.addEventListener('click', function () {
        document.querySelectorAll('.sidebar a').forEach(a => a.classList.remove('selected'));

        const parent = this.closest('.submenu').previousElementSibling;
        if (parent && parent.classList.contains('parent-menu')) {
            parent.classList.add('selected');
        }
    });
});

window.addEventListener('DOMContentLoaded', () => {
    const currentUrl = window.location.href;

    document.querySelectorAll('.sidebar a').forEach(link => {
        if (link.href === currentUrl) {
            link.classList.add('selected');

            const parent = link.closest('.submenu')?.previousElementSibling;
            if (parent) {
                parent.classList.add('selected');
                link.closest('.submenu').style.display = 'block';
            }
        }
    });
});

function cargarDatosUbicacion(idPais = "20", idDepartamento = "23", idProvincia = "20", idDistrito = "25") {
    $.ajax({
        url: 'proveedores',
        type: 'POST',
        data: { opcion: 'paises' },
        dataType: 'json',
        success: function(response) {
            if (response.respuesta === 'success') {
                let paises = response.paises;
                $('#paisR').empty();
                paises.forEach(function(pais) {
                    $('#paisR').append(`<option value="${pais.ID}">${pais.Nombre}</option>`);
                });
    
                if (idPais) {
                    $('#paisR').val(idPais);
                }

                cargarDepartamentos(idPais).then(() => {
                    if (idDepartamento) {
                        $('#departamentoR').val(idDepartamento);
                    } else {
                        let firstDepartamento = $('#departamentoR option:first').val();
                        $('#departamentoR').val(firstDepartamento);
                    }
                        return cargarProvincias(idDepartamento);
                }).then(() => {
                    if (idProvincia) {
                        $('#provinciaR').val(idProvincia);
                    } else {
                        let firstProvincia = $('#provinciaR option:first').val();
                        $('#provinciaR').val(firstProvincia);
                    }
                        return cargarDistritos(idProvincia);
                }).then(() => {
                    if (idDistrito) {
                        $('#distritoR').val(idDistrito);
                    } else {
                        let firstDistrito = $('#distritoR option:first').val();
                        $('#distritoR').val(firstDistrito);
                    }
                }).catch(error => {
                    console.error("Error al cargar ubicación por defecto:", error);
                });
    
                $('#paisR').off('change').on('change', function() {
                    let paisID = $(this).val();
                    $('#departamentoR').empty();
                    $('#provinciaR').empty();
                    $('#distritoR').empty();
                    cargarDepartamentos(paisID).then(() => {
                        let firstDepartamento = $('#departamentoR option:first').val();
                        $('#departamentoR').val(firstDepartamento).trigger('change');
                    });
                });
    
                $('#departamentoR').off('change').on('change', function() {
                    let dptoID = $(this).val();
                    $('#provinciaR').empty();
                    $('#distritoR').empty();
                    cargarProvincias(dptoID).then(() => {
                        let firstProvincia = $('#provinciaR option:first').val();
                        $('#provinciaR').val(firstProvincia).trigger('change');
                    });
                });
    
                $('#provinciaR').off('change').on('change', function() {
                    let provinciaID = $(this).val();
                    $('#distritoR').empty();
                    cargarDistritos(provinciaID).then(() => {
                        let firstDistrito = $('#distritoR option:first').val();
                        $('#distritoR').val(firstDistrito);
                    });
                });
            } else {
                console.error('Error al cargar países:', response.paises);
            }
        },
        error: function() {
            console.error('Error en la petición AJAX para países');
        }
    });
}

function cargarDepartamentos(paisID = "20") {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: 'proveedores',
            type: 'POST',
            data: { opcion: 'departamentos', pais: paisID },
            dataType: 'json',
            success: function(response) {
                if (response.respuesta === 'success' && response.departamentos.length > 0) {
                    $('#departamentoR').empty();
                    $('#provinciaR').empty();
                    $('#distritoR').empty();

                    response.departamentos.forEach(function(depto) {
                        $('#departamentoR').append(`<option value="${depto.ID}">${depto.Nombre}</option>`);
                    });

                    resolve();
                } else {
                    reject('No se encontraron departamentos');
                }
            },
            error: function() {
                reject('Error al cargar departamentos');
            }
        });
    });
}

function cargarProvincias(dptoID = "23") {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: 'proveedores',
            type: 'POST',
            data: { opcion: 'provincias', departamento: dptoID },
            dataType: 'json',
            success: function(response) {
                if (response.respuesta === 'success' && response.provincias.length > 0) {
                    $('#provinciaR').empty();
                    $('#distritoR').empty();

                    response.provincias.forEach(function(provincia) {
                        $('#provinciaR').append(`<option value="${provincia.ID}">${provincia.Nombre}</option>`);
                    });

                    resolve();
                } else {
                    reject('No se encontraron provincias');
                }
            },
            error: function() {
                reject('Error al cargar provincias');
            }
        });
    });
}

function cargarDistritos(provinciaID = "20", distritoID = "25") {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: 'proveedores',
            type: 'POST',
            data: { opcion: 'distritos', provincia: provinciaID },
            dataType: 'json',
            success: function(response) {
                if (response.respuesta === 'success' && response.distritos.length > 0) {
                    $('#distritoR').empty();

                    response.distritos.forEach(function(distrito) {
                        $('#distritoR').append(`<option value="${distrito.ID}">${distrito.Nombre}</option>`);
                    });

                    $('#distritoR').val(distritoID);
                    resolve();
                } else {
                    reject('No se encontraron distritos');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error al cargar distritos:", error); 
                reject('Error al cargar distritos');
            }
        });
    });
}

function configurarAutocomplete({
    selectorId,
    url,
    opcion,
    campos = { codigo: "Codigo", descripcion: "descripcion", informacion: "informacion" }
}) {
    const $input = $("#" + selectorId);

    $input.autocomplete({
        source: function (request, response) {
            $.ajax({
                url: url,
                type: 'POST',
                dataType: "json",
                data: {
                    term: request.term,
                    opcion: opcion
                },
                success: function (data) {
                    if (!data.item || data.item.length === 0) {
                        response([{ [campos.descripcion]: "No se encontraron resultados", [campos.codigo]: "", [campos.informacion]: "", sinResultado: true }]);
                    } else {
                        response(data.item);
                    }
                }
            });
        },
        minLength: 3,
        select: function (event, ui) {
            const item = ui.item;
            const codigo = item[campos.codigo]?.trim() || "";
            const descripcion = item[campos.descripcion]?.trim() || "";
            const informacion = item[campos.informacion]?.trim() || "";

            if (item.vacio) {
                event.preventDefault();
            } else {
                $input.val(`${codigo} - ${descripcion} (${informacion})`);
                $input.attr('codigo', codigo);
            }

            return false;
        },
        open: function () {
            $(this).autocomplete("widget").css("z-index", 9999);
        }
    }).autocomplete("instance")._renderItem = function (ul, item) {
        if (item.sinResultado) {
            return $("<li>")
                .append(`<div style='color: #999; font-style: italic;'>🔍 ${item[campos.descripcion]}</div>`)
                .appendTo(ul);
        } else {
            return $("<li>")
                .append(`<div>${item[campos.codigo]} - ${item[campos.descripcion]} (${item[campos.informacion]})</div>`)
                .appendTo(ul);
        }
    };

    $input.on('change', function () {
        const valorInput = $(this).val();
        const codigoAttr = $(this).attr('codigo');
        if (!valorInput || !codigoAttr) {
            $(this).attr('codigo', '');
        }
    });
}
