/**
 * ARCHIVO MODULO PERMISSIONS
 * AUTOR:HORUS SAMAEL RIVAS PEDRAZA
 * EMAIL: horus.riv.ped@gmail.com
 * CEL:56 2439 2632
 */
const estado = { 1: 'PENDIENTE', 2: 'AUTORIZADA', 3: 'CANCELADA' };
const usado = { 1: 'DISPONIBLE', 2: 'USADO', 3: 'DEUDA', 4: 'DESHABILITADO' };
const color_estado = { 1: 'warning', 2: 'success', 3: 'danger' };
const color_usado = { 1: '53F3F3', 2: '7FF59A', 3: 'FEAF39', 4: 'D3D3D3'};

$(document).ready(function () {
    tbl_time_pay = $("#tbl_pago_tiempo")
        .dataTable({
            processing: true,
            ajax: {
                method: "post",
                url: `${urls}permisos/datos_pago_tiempo`,
                dataSrc: "",
            },
            lengthChange: true,
            ordering: true,
            responsive: true,
            autoWidth: true,
            rowId: "staffId",
            // dom: "lBfrtip",
            buttons: [
                /* {
                  extend: "excelHtml5",
                  title: "Permisos",
                  exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 0, 7],
                  },
                }, */
                /* {
                     extend:'pdfHtml5',
                     title:'Listado de Proveedores',
                     exportOptions:{
                       columns:[1,2,3,4,5,6,7]
                     }
                   } */
            ],
            language: {
                url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
            },
            columns: [
                {
                    data: "id_item",
                    title: "FOLIO",
                    className: "text-center",
                },
                {
                    data: "nombre",
                    title: "USUARIO",
                    className: "text-center",
                },
                {
                    data: "depto",
                    title: "DEPARTAMENTO",
                    className: "text-center",
                },
                {
                    data: "day_to_pay",
                    title: "DIA",
                    className: "text-center",
                },
                {
                    data: "time_pay",
                    title: "TIEMPO A PAGAR",
                    className: "text-center",
                },
                {
                    data: null,
                    render: function (data, type, full, meta) {
                        return `<span class="badge" style="background-color:#${color_usado[data["estado"]]};">${usado[data["estado"]]}</span>`;
                    },
                    title: "ESTADO PAGO",
                    className: "text-center",
                },
                {
                    data: null,
                    render: function (data, type, full, meta) {
                        return `<span class="badge badge-${color_estado[data["status_autorize"]]}">${estado[data["status_autorize"]]}</span>`;
                    },
                    title: "ESTATUS",
                    className: "text-center",
                },
                {
                    data: null,
                    title: "ACCIONES",
                    className: "text-center",
                },
            ],
            destroy: "true",
            columnDefs: [
                {
                    targets: 7,
                    render: function (data, type, full, meta) {
                        change = (data["status_autorize"] != 1) ? '' : `onClick=statusChange(${data["id_request"]},${data["id_item"]})`;
                        btn = (data["status_autorize"] != 1) ? 'secondary' : 'primary';
                        return `<div class=" mr-auto">
                            <button type="button" class="btn btn-outline-${btn} btn-sm" title="Autorizar Permiso" ${change}>
                                <i class="fas fa-clipboard-check"></i>
                            </button>
               </div> `;

                    },
                },
                /* {
                 targets: [0],
                 visible: false,
                 searchable: false,
               },   */
            ],

            order: [[0, "DESC"]],

            createdRow: (row, data) => {
                $(row).attr("id", "permissions_" + data.id_es);
            },
        })
        .DataTable();
    $("#tbl_pago_tiempo thead").addClass("thead-dark text-center");
});

function statusChange(id_rquest, item) {
    Swal.fire({
        title: '<i class="far fa-clock" style="margin-right: 10px;"></i>¿Confirmar Pago de Tiempo?',
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-check" style="margin-right: 10px;"></i>Confirmar',
        confirmButtonColor: "#28A745",
        denyButtonText: `<i class="fas fa-times" style="margin-right: 10px;"></i>Rechazar`,
    }).then((result) => {
        if (result.isConfirmed) {
            estadoContrato(2, id_rquest, item)
        } else if (result.isDenied) {
            estadoContrato(3, id_rquest, item)
        }
    })
}

function estadoContrato(status, id, item) {
    let timerInterval = Swal.fire({ //se le asigna un nombre al swal
        title: '<i class="far fa-save" style="margin-right: 10px;"></i>¡Guardando Cambios!',
        html: 'Espere unos Segundos.',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });
    var statusContract = new FormData();
    statusContract.append('status_autorize', status)
    statusContract.append('id_contract', id)
    statusContract.append('id_item', item)
    console.log(statusContract);
    $.ajax({
        type: "post",
        url: `${urls}permisos/actualizar_pago_tiempo`,
        data: statusContract,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (save) {
            console.log(save);
            Swal.close(timerInterval);
            if (save.hasOwnProperty('xdebug_message')) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
                console.log(save.xdebug_message);
            } else if (save === true) {
                tbl_time_pay.ajax.reload(null, false);
                Swal.fire({
                    icon: 'success',
                    title: "¡Cambio Exitoso!",
                    text: 'Se registró el cambio exitosamente',
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
            }
        },
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
        } else if (textStatus === "parsererror") {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Error de análisis JSON solicitado.",
            });
        } else if (textStatus === "timeout") {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Time out error.",
            });
        } else if (textStatus === "abort") {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ajax request aborted.",
            });
        } else {
            alert("Uncaught Error: " + jqXHR.responseText);
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: `Uncaught Error: ${jqXHR.responseText}`,
            });
        }
    });

}