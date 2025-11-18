/*
 * ARCHIVO MODULO ESTACIONAMIENTO
 * AUTOR:Horus Samael Rivas Pedraza
 * EMAIL:horus.riv.ped@gmail.com
 * CEL: 56 2439 2632
 */

const tipo_vehiculo = { 1: 'AUTOMÓVIL', 2: 'MOTOCICLETA', 3: 'BICICLETA' }
const color = { 1: "bg-warning", 2: "bg-success", 3: 'bg-danger' };
const texto = { 1: "PENDIENTE", 2: "AUTORIZAR", 3: 'RECHAZADO' };

const movimiento_color = { 2: "bg-secondary", 3: 'bg-info' };
const movimiento_texto = { 2: "BAJA", 3: 'ACTUALIZACIÓN' };

$(document).ready(function () {
    tbl_registros = $("#tbl_todos_registros")
        .dataTable({
            processing: true,
            ajax: {
                method: "post",
                url: `${urls}estacionamiento/datos_movimientos_vehiculos`,
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
                    columns: [0, 1, 2, 3, 4, 5, 6],
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
                    data: "id_record",
                    title: "MARBETE",
                    className: "text-center",
                },
                {
                    data: null,
                    render: function (data, type, full, meta) {
                        var fecha = new Date(data["date_motion"]);
                        var dia = fecha.getDate();
                        var mes = fecha.getMonth() + 1; // Se suma 1 porque los meses comienzan desde 0
                        var anio = fecha.getFullYear();
                        var horas = fecha.getHours();
                        var minutos = fecha.getMinutes();
                        // Asegurarse de que el día y el mes tengan siempre dos dígitos
                        dia = (dia < 10) ? '0' + dia : dia;
                        mes = (mes < 10) ? '0' + mes : mes;
                        horas = (horas < 10) ? '0' + horas : horas;
                        minutos = (minutos < 10) ? '0' + minutos : minutos;
                        return dia + '/' + mes + '/' + anio + ' | ' + horas + ':' + minutos;
                    },
                    title: "FECHA",
                    className: "text-center",
                },
                {
                    data: "name",
                    title: "NOMBRE",
                    className: "text-center",
                },
                {
                    data: null,
                    render: function (data, type, full, meta) {
                        return `<b>${[data["type_movem"]]}</b>`;
                    },
                    title: "MOVIMIENTO",
                    className: "text-center",
                },
                {
                    data: null,
                    render: function (data, type, full, meta) {
                        return `${tipo_vehiculo[data["type_vehicle"]]}`;
                    },
                    title: "TIPO VEHICULO",
                    className: "text-center",
                },
                {
                    data: "datos_vehicule",
                    title: "DATOS",
                    className: "text-center",
                },
                {
                    data: null,
                    render: function (data, type, full, meta) {
                        if (data["motion"] == 1) {
                            return `<span class="badge ${color[data['status_authorize']]}">${texto[data['status_authorize']]}</span>`;
                        } else {
                            return `<span class="badge ${movimiento_color[data['motion']]}">${movimiento_texto[data['motion']]}</span>`;
                        }
                    },
                    title: "ESTADO",
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
                        var btn_color = (data["status_authorize"] == 1) ? 'primary' : 'secondary';
                        var change = (data["status_authorize"] == 1) ? `onclick="decision(${data["id_item"]})"` : '';
                        return ` <div class="pull-right mr-auto">
                        <button type="button" class="btn btn-${btn_color} btn-sm" title="Editar Estado Movimiento" ${change}>
                            <i class="far fa-edit"></i>
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
            order: [[1, "DESC"]],
            createdRow: (row, data) => {
                $(row).attr("id", "info_" + data.id_record);
            },
        })
        .DataTable();

    $("#tbl_todos_registros thead").addClass("thead-dark text-center");
});

function decision(id_item) {
    Swal.fire({
        title: '<i class="fas fa-car" style="margin-right: 10px;"></i>¿Confirmar Vehículo?',
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-check" style="margin-right: 10px;"></i>Confirmar',
        confirmButtonColor: "#28A745",
        denyButtonText: `<i class="fas fa-times" style="margin-right: 10px;"></i>Rechazar`,
    }).then((result) => {
        if (result.isConfirmed) {
            estadoContrato(2, id_item)
        } else if (result.isDenied) {
            estadoContrato(3, id_item)
        }
    })
}

function estadoContrato(status, id) {
    let timerInterval = Swal.fire({ //se le asigna un nombre al swal
        title: 'Guardando Cambios',
        html: 'Espere unos Segundos.',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });
    var statusContract = new FormData();
    statusContract.append('status_authorize', status);
    statusContract.append('id_item', id);
    $.ajax({
        type: "post",
        url: `${urls}estacionamiento/estado_autorizacion`,
        data: statusContract,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (save) {
            Swal.close(timerInterval);
            if (save === true) {
                tbl_registros.ajax.reload(null, false);
                Swal.fire({
                    icon: 'success',
                    title: "¡Cambio Guardado!",
                    text: 'Se registró el cambio correctamente',
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