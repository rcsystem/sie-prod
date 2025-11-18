/*
 * ARCHIVO MODULO ESTACIONAMIENTO
 * AUTOR:Horus Samael Rivas Pedraza
 * EMAIL:horus.riv.ped@gmail.com
 * CEL: 56 2439 2632
 */

const tipo_vehiculo = { 1: 'AUTOMÓVIL', 2: 'MOTOCICLETA', 3: 'BICICLETA' }
const color = { 1: "bg-warning", 2: "bg-success", 3: 'bg-danger' };
const texto = { 1: "PENDIENTE", 2: "AUTORIZAR", 3: 'RECHAZADO' };
$(document).ready(function () {
    tbl_entradas = $("#tbl_todos_entradas")
        .dataTable({
            processing: true,
            ajax: {
                method: "post",
                url: `${urls}estacionamiento/datos_vehiculos_cajon`,
                dataSrc: "",
            },
            lengthChange: true,
            ordering: true,
            responsive: true,
            autoWidth: true,
            rowId: "staffId",
            dom: "Bfrtip",
            buttons: [],
            language: {
                url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
            },
            columnDefs: [
                { "width": "20%", "targets": "_all" }
            ],
            columns: [
                {
                    data: "id_record",
                    title: "MARBETE",
                    className: "text-center",
                },                
                {
                    data: null,
                    render: function (data, type, full, meta) {
                        const readonly = (data["location"] === null) ? '' : 'readonly';
                        const change = (data["location"] === null) ? 'onchange="limpiarError(this)"' : '';
                        return ` <input type="number" min="1" max="200" id="cajon_${data["id_request"]}" class="form-control" value="${data["location"]}" ${change} ${readonly}>`;
                    },
                    title: "CAJON",
                    className: "text-center",
                },
                {
                    data: null,
                    title: `<i class="far fa-save"></i>`,
                    className: "text-center",
                },
            ],
            destroy: "true",
            columnDefs: [
                {
                    targets: 2,
                    render: function (data, type, full, meta) {
                        const btn_color = (data["location"] == null) ? 'primary' : 'secondary';
                        const change = (data["location"] == null) ? `onclick="Asignar(${data["id_request"]})"` : '';
                        return ` <div class="pull-right mr-auto">
                        <button type="button" class="btn btn-${btn_color} btn-sm" title="Editar Estado Movimiento" ${change}>
                            <i class="far fa-save"></i>
                        </button>
                        </div> `;
                    },
                },
                /* {
                  targets: [0],
                  visible: false,
                  searchable: false,
                }, */
            ],
            order: [[0, "DESC"]],
            createdRow: (row, data) => {
                $(row).attr("id", "info_" + data.id_request);
            },
        })
        .DataTable();

    $("#tbl_todos_entradas thead").addClass("thead-dark text-center");
});

function Asignar(id_record) {
    const valor = document.getElementById('cajon_'+id_record);
    if (valor.value.length == 0) {
        valor.classList.add('has-error');
        return false;
    }
    // console.log('cajon ->',valor,'\n id:',id_record);
    let timerInterval = Swal.fire({ //se le asigna un nombre al swal
        allowOutsideClick: false,
        title: 'Guardando Cambio',
        html: 'Espere unos Segundos.',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });
    var formData = new FormData();
    formData.append("id_request", id_record);
    formData.append("location", valor);
    $.ajax({
        data: formData,
        url: `${urls}estacionamiento/asignar_cajon`,
        type: "post",
        dataType: "json",
        processData: false,
        contentType: false,
        cache: false,
        success: function (delet) {
            Swal.close(timerInterval);
            if (delet != false && delet != null) {
                tbl_entradas.ajax.reload(null, false);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "¡Algo salió Mal! Contactar con el Administrador",
                });
            }
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        if (jqXHR.status === 0) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Fallo de conexión: ​​Verifique la red.",
            });
        } else if (jqXHR.status == 404) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No se encontró la página solicitada [404]",
            });
        } else if (jqXHR.status == 500) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Internal Server Error [500]",
            });
        } else if (textStatus === 'parsererror') {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Error de análisis JSON solicitado.",
            });
        } else if (textStatus === 'timeout') {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Time out error.",
            });
        } else if (textStatus === 'abort') {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ajax request aborted.",
            });
        } else {
            alert('Uncaught Error: ' + jqXHR.saveText);
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: `Uncaught Error: ${jqXHR.responseText}`,
            });
        }
    });

}

function limpiarError(campo) {
    if (campo.value.length > 0) {
        campo.classList.remove('has-error');
    }
}
