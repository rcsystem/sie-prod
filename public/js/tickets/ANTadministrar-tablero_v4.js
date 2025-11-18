/*
 * ARCHIVO MODULO TICKETS IT
 * AUTOR:Horus Samael Rivas Pedraza
 * EMAIL:horus.riv.ped@gmail.com
 * CEL:56 2439 2632
 */

const $prioridad = ['Error', '---', 'BAJA', 'MEDIA', 'ALTA'];
const origen = '2023-01-01';
var d = new Date();
var month = d.getMonth() + 1;
var day = d.getDate();
const hoy = `${d.getFullYear()}-${month < 10 ? '0' : ''}${month}-${(day < 10 ? '0' : '') + day}`;

$(document).ready(function () {
    $("#fecha_inicio").val(origen);
    $("#Fecha_fin").val(hoy);
    $("#fch-fin").val(hoy);
    $("#fch-inicio").val(origen);
    $("#sel-filtro-actividad").select2();
    $("#sel-filtro-usuario").select2();
    $("#sel-filtro-tecnico").select2();
    // modal crear nuevo ticket
    $("#sel-usuario").select2({
        placeholder: "Selecciona una Opción",
    });
    $("#sel-actividad").select2();
    $("#sel-tecnico").select2();

    $(".sidebar-mini").addClass('sidebar-collapse');
    CargarTickets();
})

function vista(div) {
    $(".sidebar-mini").addClass('sidebar-collapse');
    if (div == 1) {
        $("#div_tablero").show()
        $("#div-filtros").show()
        $("#div-filtros-reports").hide();
        $("#div_reporte").hide();
        CargarTickets()
    }
    if (div == 2) {
        $("#div_tablero").hide();
        $("#div-filtros").hide();
        $("#div-filtros-reports").show();
        $("#div_reporte").show();
        ObtenerInfomacion();
    }
}

$("#form_nuevo_ticket").submit(function (e) {
    e.preventDefault();
    $("#btn_nuevo_ticket").prop('disabled', true);
    let timerInterval = Swal.fire({
        title: 'Generando Ticket!',
        html: 'Espere unos Segundos.',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading()
        },
    });
    const formCreate = new FormData($("#form_nuevo_ticket")[0]);
    $.ajax({
        type: "post",
        url: `${urls}tickets/generar_tickets`,
        data: formCreate,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (save) {
            Swal.close(timerInterval);
            $("#btn_nuevo_ticket").prop('disabled', false);
            if (save != false) {
                $("#select2-sel-usuario-container").text('Selecciona una Opción');
                $("#form_nuevo_ticket")[0].reset();
                $("#nuevoTicketModal").modal("toggle");
                Swal.fire({
                    icon: 'success',
                    title: "¡Solicitud Exitosa!",
                    html: `Se te asigno al Ingeniero : <b>${save}</b>`,
                });
                CargarTickets();
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
            }
        },
    }).fail(function (jqXHR, textStatus, errorThrown) {
        $("#btn_nuevo_ticket").prop('disabled', false);
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
});

$("#form_reasignar").submit(function (e) {
    e.preventDefault();
    const reasignar = new FormData($("#form_reasignar")[0]);
    $.ajax({
        type: "post",
        url: `${urls}tickets/reasignar_ticket`,
        data: reasignar,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (update) {
            if (update == true) {
                $("#detalleTicketModal").modal("toggle");
                $("#reasignarTicketModal").modal("toggle");
                Swal.fire({
                    icon: 'success',
                    title: 'Ticket Reasignado',
                    showConfirmButton: false,
                    timer: 1600
                });
                setTimeout(function () {
                    Detalle($("#id_request_reasignar").val());
                    CargarTickets();
                }, 200);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
            }
        }
    });
})

$("#form_prioridad").submit(function (e) {
    e.preventDefault();
    let timerInterval = Swal.fire({
        title: 'Actualizando Prioridad!',
        html: 'Espere unos Segundos.',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading()
        },
    });
    const prioridad = new FormData($("#form_prioridad")[0]);
    $.ajax({
        type: "post",
        url: `${urls}tickets/prioridad_ticket`,
        data: prioridad,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (update) {
            Swal.close(timerInterval);
            if (update == true) {
                $("#detalleTicketModal").modal("toggle");
                $("#prioridadTicketModal").modal("toggle");
                Swal.fire({
                    icon: 'success',
                    title: 'Prioridad Actualizada',
                    showConfirmButton: false,
                    timer: 1600
                });
                setTimeout(function () {
                    Detalle($("#id_request_prioridad").val());
                }, 200);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
            }
        }
    });
})

$("#form_estatus").submit(function (e) {
    e.preventDefault();
    var estado = $("#estatus_id").val();
    let timerInterval = Swal.fire({
        title: 'Cambiando Estado!',
        html: 'Espere unos Segundos.',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading()
        },
    });
    const estatus = new FormData($("#form_estatus")[0]);
    $.ajax({
        type: "post",
        url: `${urls}tickets/estado_ticket`,
        data: estatus,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (update) {
            Swal.close(timerInterval);
            if (update == true) {
                $("#detalleTicketModal").modal("toggle");
                $("#estatusTicketModal").modal("toggle");
                Swal.fire({
                    icon: 'success',
                    title: 'Estado Actualizado',
                    showConfirmButton: false,
                    timer: 1600
                });
                if (estado < 3) {
                    setTimeout(function () {
                        Detalle($("#id_request_estatus").val());
                        CargarTickets();
                    }, 200);
                }
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
            }
        }
    });
})

$("#form_nuevo_comentario").submit(function (e) {
    e.preventDefault();
    let timerInterval = Swal.fire({
        title: 'Guardando Comentario!',
        html: 'Espere unos Segundos.',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading()
        },
    });
    $("#btn_nuevo_comentario").prop('disblaed', true);
    const nuevo_comentario = new FormData($("#form_nuevo_comentario")[0]);
    $.ajax({
        type: "post",
        url: `${urls}tickets/agregar_chat_ticket`,
        data: nuevo_comentario,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (save) {
            Swal.close(timerInterval);
            $("#btn_nuevo_comentario").prop('disblaed', true);
            if (save == true) {
                $("#detalleTicketModal").modal("toggle");
                Swal.fire({
                    icon: 'success',
                    title: 'Comentario agregado',
                    showConfirmButton: false,
                    timer: 1600
                });
                setTimeout(function () {
                    Detalle($("#id_Request").val());
                    CargarTickets();
                }, 200);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
            }
        }
    });
})

$("#form_cancelar").submit(function (e) {
    e.preventDefault();
    var estado = $("#estatus_id").val();
    let timerInterval = Swal.fire({
        title: 'Cancelando Ticket!',
        html: 'Espere unos Segundos.',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading()
        },
    });
    const estatus = new FormData($("#form_cancelar")[0]);
    $.ajax({
        type: "post",
        url: `${urls}tickets/cancelar_ticket`,
        data: estatus,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (update) {
            Swal.close(timerInterval);
            if (update == true) {
                $("#detalleTicketModal").modal("toggle");
                $("#cancelarTicketModal").modal("toggle");
                Swal.fire({
                    icon: 'success',
                    title: 'Ticket Cancelado',
                    showConfirmButton: false,
                    timer: 1600
                });
                if (estado < 3) {
                    setTimeout(function () {
                        CargarTickets();
                    }, 200);
                }
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
            }
        }
    });
})

function ObtenerInfomacion() {
    $("#div-nuevo").empty();
    $("#div-proceso").empty();
    $("#div-concluido").empty();
    $("#div-cerrado").empty();
    $("#div-cumplimiento").empty();
    $("#info_box_cumplimiento").attr('class', "info-box");
    star = ($("#fch-inicio").val() == null) ? '2023-01-01' : $("#fch-inicio").val();
    end = ($("#fch-fin").val() == null) ? hoy : $("#fch-fin").val();
    var depto = new FormData();
    depto.append('id_area', $("#direct_area").val());
    depto.append('star_date', star);
    depto.append('end_date', end);
    console.log(star + " - " + end);
    $.ajax({
        url: `${urls}tickets/obtener_informacion_reportes`,
        data: depto,
        async: false,
        type: "post",
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (data) {
            if (data != false) {

                if ($("#fch-inicio").val() != '2023-01-01' && $("#fch-fin").val() != hoy) {
                    $("#btn_reset_range").show();
                } else {
                    $("#btn_reset_range").hide();
                }

                $("#div-nuevo").append("# " + data.cantidad_tickets.nuevos);
                $("#div-proceso").append("# " + data.cantidad_tickets.proceso);
                $("#div-concluido").append("# " + data.cantidad_tickets.concluido);
                $("#div-cerrado").append("# " + data.cantidad_tickets.cerrado);
                if (data.cumplimiento > 50) { color = "bg-success"; }
                else if (data.cumplimiento == 50) { color = "bg-warning"; }
                else if (data.cumplimiento < 50) { color = "bg-danger"; }
                $("#info_box_cumplimiento").addClass(color);
                $("#div-cumplimiento").append('     ', data.cumplimiento, ' %');
                console.log(data.cumplimiento);
                document.getElementById("grafico_uno").remove();
                var canvas = document.createElement("canvas");
                canvas.id = "grafico_uno";
                canvas.setAttribute("width", 0);
                canvas.setAttribute("height", 0);
                document.getElementById("contenedor_uno").append(canvas);
                Chart.defaults.font.family = 'Roboto Condensed';
                Chart.defaults.plugins.title.align = "start";
                Chart.defaults.plugins.subtitle.align = "start";

                var label_uno = [];
                var data_uno = [];
                var markp = "";
                var total = 0;
                var total_tiempo = 0;
                var a = 1;
                data.actividades.forEach(valor => {
                    label_uno.push(a);
                    data_uno.push(valor.cant_tickets);
                    markp += "<tr><td style='text-align:center;'>" + a + "</td><td style='text-align:center;'>" + valor.Actividad_Actividad + "</td><td style='text-align:center;'>" + valor.cant_tickets + "</td><td style='text-align:center;'>" + valor.total_horas + "</td></tr>";
                    total = parseFloat(total) + parseFloat(valor.cant_tickets);
                    total_tiempo = parseFloat(total_tiempo) + parseFloat(valor.total_horas);
                    a++;
                });
                const ctx_uno = document.getElementById('grafico_uno').getContext('2d');
                // const myChart_uno =
                new Chart(ctx_uno, {
                    type: 'bar',
                    data:
                    {
                        labels: label_uno,
                        datasets:
                            [{
                                label: '',
                                data: data_uno,
                                backgroundColor: "#A5DAEB",
                                borderColor: "#00B0F0",
                                borderWidth: 1,
                                borderRadius: 3
                            }]
                    },
                    options:
                    {
                        scales:
                        {
                            y:
                            {
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            /* labels:
                            {
                                fontSize: 12,
                                fontFamily: "Roboto Condensed",
                                render: (context) => {
                                    var porcentaje = ((parseFloat(context.value) * 100) / data.cantidad_tickets.total).toFixed(2);
                                    return porcentaje + "%";
                                }
                            }, */
                            title: {
                                display: true,
                                text: 'Concentrado de Actividades',
                                padding: {
                                    bottom: 20
                                },
                                font: {
                                    size: 22,
                                    family: 'Bebas Neue',
                                    color: 'black'
                                }
                            },
                            legend: {
                                display: false,
                            }
                        }
                    }
                });
                $("#div-actividades").empty();
                $("#div-actividades").append(`<table class="table table-hover" id="tabla-actividades" style="font-family: Roboto Condensed;width: 100%;">
                <thead>
                <tr style="background-color:#999999;boder-color:#999999;color:white;">
                    <th><center>No.</center></th>
                    <th><center>Actividad</center></th>
                    <th><center>Total</center></th>
                    <th><center>Tiempo (hrs)</center></th>
                </tr>
                <tr style="background-color:#F73633;boder-color:#F73633;color:white;">
                    <td colspan="2" style="text-align: center;">Concentrado</td>
                    <td><center><b>${total.toFixed(2)}</b></center></td>
                    <td><center><b>${total_tiempo.toFixed(2)}</b></center></td>
                </tr>
                </thead>
                <tbody>${markp}</tbody>
                </table>`);
                $('#tabla-actividades').DataTable({
                    pageLength: 6,
                    paging: true,
                    lengthChange: false,
                    searching: false,
                    order: [[2, 'desc']],
                    info: false,
                    autoWidth: false,
                    responsive: true,
                    language: {
                        "sProcessing": "Procesando...",
                        "sLengthMenu": "Mostrar _MENU_ registros",
                        "sZeroRecords": "No se encontraron resultados",
                        "sEmptyTable": "Ningún dato disponible en esta tabla",
                        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                        "sInfoPostFix": "",
                        "sSearch": "Buscar:",
                        "sUrl": "",
                        "sInfoThousands": ",",
                        "sLoadingRecords": "Cargando...",
                        "oPaginate": {
                            "sFirst": "Primero",
                            "sLast": "Último",
                            "sNext": "Siguiente",
                            "sPrevious": "Anterior"
                        },
                        "oAria": {
                            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                        }
                    }
                });
                if (data.tipo == 1) {
                    var total_cumpliminto_t = 0;
                    var total_tiempo_tecnicos = 0;
                    var markp_dos = "";
                    var no_inge = 0;
                    data.laboral.forEach(valor => {
                        markp_dos += "<tr><td>" + valor.nombre + "</td><td>" + valor.porcentaje + "%</td><td>" + valor.total_horas, + "</td></tr>";
                        //total_tecnicos = parseFloat(total_tecnicos) + parseFloat(valor.Total);<td>" + valor.Total + "</td>
                        total_cumpliminto_t = parseFloat(total_cumpliminto_t) + parseFloat(valor.porcentaje);
                        total_tiempo_tecnicos = parseFloat(total_tiempo_tecnicos) + parseFloat(valor.total_horas);
                        no_inge++
                    });

                    $("#div-tecnicos").empty();
                    // $("#div-tecnicos").append('<table class="table table-hover" id="tabla-tecnicos" style="font-family: Roboto Condensed;width: 100%;"><thead><tr style="background-color:#999999;boder-color:#999999;color:white;"><th>Tecnico</th><th>Cumplimiento</th><th>Tiempo (hrs)</th></tr></thead><tbody><tr style="background-color:#F73633;boder-color:#F73633;color:white;"><td></td><td><b>' + data.cumplimiento + '%</b></td><td><b>' + total_tiempo_tecnicos.toFixed(2) + '</b></td></tr>' + markp_dos + '</tbody></table>');
                    $("#div-tecnicos").append(`<table class="table table-hover" id="tabla-tecnicos" style="font-family: Roboto Condensed;width: 100%;">
                    <thead>
                        <tr style="background-color:#999999;boder-color:#999999;color:white;">
                            <th>Ingeniero</th>
                            <th>Cumplimiento Individual</th>
                            <th>Tiempo (hrs)</th>
                        </tr>
                        <tr style="background-color:#F73633;boder-color:#F73633;color:white;">
                            <td colspan="2">
                                <div class="row" style="display:flex;">
                                    <div class="col-md-6" style="text-align:center;">
                                        <b>General</b>
                                    </div>
                                    <div class="col-md-6" style="padding-left: 1rem;">
                                        ${(total_cumpliminto_t / no_inge).toFixed(2)} %
                                    </div>
                                </div>
                            </td>
                            <td><b>${total_tiempo_tecnicos.toFixed(2)} </b></td>
                        </tr>
                    </thead>
                    <tbody>
                        ${markp_dos}
                    </tbody></table>`);
                    $('#tabla-tecnicos').DataTable({
                        pageLength: 6,
                        paging: true,
                        lengthChange: false,
                        searching: false,
                        order: [[1, 'desc']],
                        info: false,
                        autoWidth: false,
                        responsive: true,
                        language: {
                            "sProcessing": "Procesando...",
                            "sLengthMenu": "Mostrar _MENU_ registros",
                            "sZeroRecords": "No se encontraron resultados",
                            "sEmptyTable": "Ningún dato disponible en esta tabla",
                            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                            "sInfoPostFix": "",
                            "sSearch": "Buscar:",
                            "sUrl": "",
                            "sInfoThousands": ",",
                            "sLoadingRecords": "Cargando...",
                            "oPaginate": {
                                "sFirst": "Primero",
                                "sLast": "Último",
                                "sNext": "Siguiente",
                                "sPrevious": "Anterior"
                            },
                            "oAria": {
                                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                            }
                        }
                    });
                }

            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
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

function CargarTickets() {
    //contadores 
    var nuevos = 0;
    var proceso = 0;
    var concluido = 0;
    var cerrado = 0;
    var cancelado = 0;

    $("#todo").empty();
    $("#inprogress").empty();
    $("#completed").empty();
    $("#cancelled").empty();
    $("#closed").empty();
    $("#div-ntbl").text();
    $("#div-eptbl").text();
    $("#div-ctbl").text();
    $("#div-cltbl").text();
    $("#div-catbl").text();
    var data = new FormData();
    data.append('folio', $("#txt-buscar").val());
    $.ajax({
        type: "post",
        url: `${urls}tickets/todos_tickets`,
        data: data,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (response) {
            if (response != false) {
                response.forEach(valor => {
                    var actividad = '';
                    if (valor.Ticket_EstatusId == 1) {
                        $("#todo").append(`<div onclick="Detalle(${valor.TicketId});" class="card-style-personal w-100 p-2" style="cursor: pointer;">
                        <h6>Folio ${valor.TicketId}: <b>${valor.Actividad_Actividad}</b></h6>
                        <div class="row">
                            <div class="col-sm-12">
                                <h6><i class="far fa-clock"></i>&nbsp;&nbsp;<b>${valor.Ticket_FechaCreacion}</b></h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <h6>Ingeniero: <b>${valor.Tecnico_Nombre}</b></h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <p style="text-align: justify;">${valor.Ticket_Descripcion}</p>
                            </div>
                        </div>
                        </div> `);
                        nuevos++;
                    }
                    if (valor.Ticket_EstatusId == 2) {
                        $("#inprogress").append(`<div onclick="Detalle(${valor.TicketId});" class="card-style-personal w-100 p-2" style="cursor: pointer;">
                        <h6>Folio ${valor.TicketId}: <b>${valor.Actividad_Actividad}</b></h6>
                        <div class="row">
                            <div class="col-sm-12">
                                <h6><i class="far fa-clock"></i>&nbsp;&nbsp;<b>${valor.Ticket_FechaCreacion}</b></h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <h6>Ingeniero: <b>${valor.Tecnico_Nombre}</b></h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <p style="text-align: justify;">${valor.Ticket_Descripcion}</p>
                            </div>
                        </div>
                    </div>`);
                        proceso++;
                    }
                    if (valor.Ticket_EstatusId == 3) {
                        $("#completed").append('<div onclick="Detalle(' + valor.TicketId + ');" class="card-style-personal w-100 p-2" style="cursor: pointer;border-left-color: ' + valor.Clasificacion_Color + ';"><h6>Folio ' + valor.TicketId + ': <b>' + valor.Actividad_Actividad + '</b></h6><div class="row"><div class="col-sm-12"><h6>Ingeniero: <b>' + valor.Tecnico_Nombre + '</b></h6></div></div><div class="row"><div class="col-sm-12"><p align = "justify">' + valor.Ticket_Descripcion + '</p></div></div></div>');
                        concluido++;
                    }
                    if (valor.Ticket_EstatusId == 4) {
                        $("#cancelled").append('<div onclick="Detalle(' + valor.TicketId + ');" class="card-style-personal w-100" style="border-left-color: ' + valor.Clasificacion_Color + ';"><h6>Folio ' + valor.TicketId + ': <b>' + valor.Actividad_Actividad + '</b></h6><h6 class="pt-1">Ingeniero: ' + valor.Tecnico_Nombre + '</h6><div class="row"><div class="col-sm-12"><h6><i class="far fa-clock"></i>&nbsp;&nbsp;<b>' + valor.Ticket_FechaCreacion + '</b></h6></div></div></div>');
                        cancelado++;
                    }
                    if (valor.Ticket_EstatusId == 5) {
                        $("#closed").append('<div onclick="Detalle(' + valor.TicketId + ');" class="card-style-personal w-100 p-2" style="cursor: pointer;border-left-color: ' + valor.Clasificacion_Color + ';"><h6>Folio ' + valor.TicketId + ': <b>' + valor.Actividad_Actividad + '</b></h6><div class="row"><div class="col-sm-12"><h6>Ingeniero: <b>' + valor.Tecnico_Nombre + '</b></h6></div></div><div class="row"><div class="col-sm-12"><p align = "justify">' + valor.Ticket_Descripcion + '</p></div></div></div>');
                        cerrado++;
                    }
                });
                $("#div-ntbl").text(nuevos);
                $("#div-eptbl").text(proceso);
                $("#div-ctbl").text(concluido);
                $("#div-cltbl").text(cerrado);
                $("#div-catbl").text(cancelado);
            } else {
                if ($("#txt-buscar").val() != '') {
                    Swal.fire({
                        icon: "error",
                        title: "Error de Folio",
                        text: "Datos no encontrados",
                    });
                    $("#txt-buscar").addClass('has-error');
                    $("#error_txt-buscar").text('Datos no encontrados');
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Algo salió Mal! Contactar con el Administrador",
                    });
                }
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

function BuscarTickets() {
    var nuevos = 0;
    var proceso = 0;
    var concluido = 0;
    var cerrado = 0;
    var cancelado = 0;

    $("#todo").empty();
    $("#inprogress").empty();
    $("#completed").empty();
    $("#closed").empty();
    $("#cancelled").empty();
    $("#div-ntbl").text();
    $("#div-eptbl").text();
    $("#div-ctbl").text();
    $("#div-cltbl").text();
    $("#div-catbl").text();
    var data = new FormData();
    data.append('prioridad', $("#sel-filtro-prioridad").val());
    data.append('actividad', $("#sel-filtro-actividad").val());
    data.append('usuario', $("#sel-filtro-usuario").val());
    data.append('tecnico', $("#sel-filtro-tecnico").val());
    data.append('fecha_inicio', $("#fecha_inicio").val());
    data.append('Fecha_fin', $("#Fecha_fin").val());
    $.ajax({
        type: "post",
        url: `${urls}tickets/buscar_tickets`,
        data: data,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (response) {
            if (response != false) {
                response.forEach(valor => {
                    if (valor.Ticket_EstatusId == 1) {
                        $("#todo").append(`<div onclick="Detalle(${valor.TicketId});" class="card-style-personal w-100 p-2" style="cursor: pointer;">
                        <h6>Folio ${valor.TicketId}: <b>${valor.Actividad_Actividad}</b></h6>
                        <div class="row">
                            <div class="col-sm-12">
                                <h6><i class="far fa-clock"></i>&nbsp;&nbsp;<b>${valor.Ticket_FechaCreacion}</b></h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <h6>Ingeniero: <b>${valor.Tecnico_Nombre}</b></h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <p style="text-align: justify;">${valor.Ticket_Descripcion}</p>
                            </div>
                        </div>
                        </div> `);
                        nuevos++;
                    }
                    if (valor.Ticket_EstatusId == 2) {
                        $("#inprogress").append(`<div onclick="Detalle(${valor.TicketId});" class="card-style-personal w-100 p-2" style="cursor: pointer;">
                        <h6>Folio ${valor.TicketId}: <b>${valor.Actividad_Actividad}</b></h6>
                        <div class="row">
                            <div class="col-sm-12">
                                <h6><i class="far fa-clock"></i>&nbsp;&nbsp;<b>${valor.Ticket_FechaCreacion}</b></h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <h6>Ingeniero: <b>${valor.Tecnico_Nombre}</b></h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <p style="text-align: justify;">${valor.Ticket_Descripcion}</p>
                            </div>
                        </div>
                    </div>`);
                        proceso++;
                    }
                    if (valor.Ticket_EstatusId == 3) {
                        $("#completed").append('<div onclick="Detalle(' + valor.TicketId + ');" class="card-style-personal w-100 p-2" style="cursor: pointer;border-left-color: ' + valor.Clasificacion_Color + ';"><h6>Folio ' + valor.TicketId + ': <b>' + valor.Actividad_Actividad + '</b></h6><div class="row"><div class="col-sm-12"><h6>Ingeniero: <b>' + valor.Tecnico_Nombre + '</b></h6></div></div><div class="row"><div class="col-sm-12"><p align = "justify">' + valor.Ticket_Descripcion + '</p></div></div></div>');
                        concluido++;
                    }
                    if (valor.Ticket_EstatusId == 4) {
                        $("#cancelled").append('<div onclick="Detalle(' + valor.TicketId + ');" class="card-style-personal w-100" style="border-left-color: ' + valor.Clasificacion_Color + ';"><h6>Folio ' + valor.TicketId + ': <b>' + valor.Actividad_Actividad + '</b></h6><h6 class="pt-1">Ingeniero: ' + valor.Tecnico_Nombre + '</h6><div class="row"><div class="col-sm-12"><h6><i class="far fa-clock"></i>&nbsp;&nbsp;<b>' + valor.Ticket_FechaCreacion + '</b></h6></div></div></div>');
                        cancelado++;
                    }
                    if (valor.Ticket_EstatusId == 5) {
                        $("#closed").append('<div onclick="Detalle(' + valor.TicketId + ');" class="card-style-personal w-100 p-2" style="cursor: pointer;border-left-color: ' + valor.Clasificacion_Color + ';"><h6>Folio ' + valor.TicketId + ': <b>' + valor.Actividad_Actividad + '</b></h6><div class="row"><div class="col-sm-12"><h6>Ingeniero: <b>' + valor.Tecnico_Nombre + '</b></h6></div></div><div class="row"><div class="col-sm-12"><p align = "justify">' + valor.Ticket_Descripcion + '</p></div></div></div>');
                        cerrado++;
                    }
                });
                $("#div-ntbl").text(nuevos);
                $("#div-eptbl").text(proceso);
                $("#div-ctbl").text(concluido);
                $("#div-cltbl").text(cerrado);
                $("#div-catbl").text(cancelado);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Registros no encontrados con los datos proporcionados.",
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

function Detalle(ID) {
    $("#div_nueva_accion").empty();
    $("#div-acciones").empty();
    $('#lbl-folio').text('');
    $('#lbl-clasificacion').text('');
    $('#lbl-hora').text('');
    $('#lbl-prioridad').text('');
    $('#lbl-usuario').text('');
    $('#lbl-tecnico').text('');
    $("#id_Request").val('');
    $("#tecnico_id").val('');
    $("#estatus_id").val('');
    $("#update-agprioridad").val('');
    $("#txt-solucion").val('');
    $("#div-tblfile").text('');
    $("#div-tblcomment").text('');
    $("#hrf-reasign").hide();
    $("#hrf-status").hide();
    $("#hrf-status-user").hide();
    $("#hrf-file").hide();
    $("#hrf-priority").hide();
    $("#hrf-comment").hide();
    $("#div-txtsolucion").hide();
    $("#btn-cancel-users").hide();
    var id = new FormData();
    id.append('id_requets', ID);
    $.ajax({
        type: "post",
        url: `${urls}tickets/detalles_ticket`,
        data: id,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (data) {
            if (data != false) {
                $('#lbl-folio').text(ID);
                $("#id_Request").val(ID);
                $("#tecnico_id").val(data.info.Ticket_TecnicoId);
                $('#lbl-clasificacion').text(data.fecha);
                $('#lbl-hora').text(data.hora);
                $('#lbl-prioridad').text($prioridad[data.info.Ticket_PrioridadId]);
                $('#lbl-usuario').text(data.info.Ticket_UsuarioCreacion);
                $('#lbl-tecnico').text(data.info.nombre);
                $("#txt-ddescripcion").val(data.info.Ticket_Descripcion);
                $("#estatus_id").val(data.info.Ticket_EstatusId);
                Ticket_PrioridadId = (data.info.Ticket_PrioridadId == 1) ? '' : data.info.Ticket_PrioridadId;
                $("#update-agprioridad").val(Ticket_PrioridadId);

                switch (parseInt(data.info.Ticket_EstatusId)) {
                    case 1:
                        $("#hrf-reasign").show();
                        $("#hrf-priority").show();
                        $("#hrf-status").show();
                        $("#btn-cancel-users").show();
                        // $("#hrf-file").show();
                        $("#hrf-comment").show();
                        break;
                    case 2:
                        // $("#hrf-reasign").show();
                        $("#hrf-status").show();
                        // $("#hrf-priority").show();
                        $("#hrf-file").show();
                        $("#hrf-comment").show();
                        $("#btn-cancel-users").show();
                        break;
                    case 3:
                        // $("#hrf-status").show();
                        $("#hrf-status-user").show();
                        $("#hrf-file").show();
                        $("#hrf-comment").show();
                        break;
                    default:
                        break;
                }
                var cont_comentario = 0;
                var cont_archivo = 0;
                data.chat.forEach(chat => {
                    if (chat.Accion_URL != "") {
                        accion = "Archivo";
                        contenido = '<a href="' + chat.Accion_URL + '" download=' + chat.Accion_Nombre + ' class="btn btn-sm btn-success" style="background-color:#DA1F1C;border-color:#DA1F1C;"> Descargar archivo </a>';
                        cont_archivo++;
                    } else {
                        contenido = "<p>" + chat.Accion_Comentario + "</p>";
                        cont_comentario++;
                    }
                    $("#div-acciones").append(`<div class="time-label">
                    <i class="fas fa-user" style="background-color:#DE37A1;color:white;"></i>
                    <div class="timeline-item">
                    <span class="time"
                    ><i class="fas fa-clock"></i>${chat.Accion_FechaCreacion}
                    </span>
                    <h3 class="timeline-header no-border">
                    <a href="#">${chat.Accion_UsuarioCreacion}</a>
                    <br><br>${contenido}</h3>
                    </div>
                    </div>`);
                });
                $("#div-tblfile").text(cont_archivo);
                $("#div-tblcomment").text(cont_comentario);
                if (data.info.Ticket_Solucion != '' && data.info.Ticket_Solucion != null && parseInt(data.info.Ticket_EstatusId) > 2) {
                    $("#txt-solucion").val(data.info.Ticket_Solucion);
                    $("#div-txtsolucion").show();
                }
                $("#detalleTicketModal").modal("show");
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
            }
        }
    });
}

function Reassign() {
    $("#id_request_reasignar").val('');
    $("#reasig-tecnico").val('');
    $("#tecnico_id_ant").val('');
    $("#id_request_reasignar").val('');
    $.ajax({
        type: "post",
        url: `${urls}tickets/actividad_inge_por_area`,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (data) {
            if (data != false) {
                $("#reasig-tecnico").empty();
                data.ingenieros.forEach(key => {
                    $("#reasig-tecnico").append(`<option value="${key.TecnicoId}">${key.nombre}</option>`);
                });
                $("#reasig-tecnico").val($("#tecnico_id").val());
                $("#tecnico_id_ant").val($("#tecnico_id").val());
                $("#id_request_reasignar").val($("#id_Request").val());
                $("#sel-filtro-tecnico").select2();
                // $("#detalleTicketModal").hide();
                $("#reasignarTicketModal").modal("show");

            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
            }
        }
    });

}

function Priority() {
    $("#id_request_prioridad").val($("#id_Request").val());
    $("#prioridadTicketModal").modal("show");
}

function Status() {
    $("#id_request_estatus").val($("#id_Request").val());
    $("#div_solucion").empty();
    $("#update-agestatus").empty();
    $("#update-agestatus").append(`<option value="">Opciones...</option>`);
    switch (parseInt($("#estatus_id").val())) {
        case 1:
            $("#update-agestatus").append(`
        <option value="2">En proceso</option>
        <option value="3">Concluido</option>
        <option value="4">Cancelado</option>
        <option value="5">Cerrado</option>`);
            break;
        case 2:
            $("#update-agestatus").append(`
        <option value="3">Concluido</option>
        <option value="4">Cancelado</option>
        <option value="5">Cerrado</option>`);
            break;
        case 3:
            $("#update-agestatus").append(`
                <option value="5">Cerrado</option>`);
            break;
        default:
            break;
    }
    $("#div_solucion_modal").empty();
    $("#estatusTicketModal").modal("show");
}

function Agregar() { // nuevo ticket
    $("#sel-actividad").empty();
    $("#sel-tecnico").empty();
    $("#sel-area").val('');
    $("#sel-actividad").val('');
    $("#txt-descripcion").val('');
    $("#sel-usuario").val('');
    $("#sel-departamento").val('');
    $("#id_depto").val('');
    $("#sel-tecnico").val('');
    $("#sel-agprioridad").val('');
    $("#sel-agestatus").val('');
    $("#txt-solucion").val('');
    $("#div_solucion").hide();
    $("#txt-solucion").attr("required", false);

    $("#nuevoTicketModal").modal("show");
}

function Cancel() {
    $("#id_request_cancelar").val($("#id_Request").val());
    $("#cancelarTicketModal").modal("show");
}

function Comment(tipo) {
    $("#div_nueva_accion").empty();
    $("#div_nueva_accion").attr('style', 'margin-bottom: 2rem;');
    if (tipo == 2) {
        $("#div_nueva_accion").append(`<div class="row" style="margin-bottom: 15px;">
          <div class="col-md-8">
            <i class="far fa-file-alt fa-2x" style="color:#999999;padding-right: 10px;"></i><label style="font-family: Roboto Condensed;font-size: 20px;">Archivo</label>
          </div>
          <div class="col-md-4">
            <div class="row" style="text-align: end;">
              <div class="col-md-6">
                <button type="button" style="width:auto;" onclick="Comment(3);" class="btn btn-secondary" >Cerrar</button>
              </div>
              <div class="col-md-6">
                <button type="submit" style="width:auto;" class="btn btn-info" id="btn_nuevo_comentario">Comentar</button>
              </div>      
            </div>
          </div>
        </div>
        <div class="row">
            <input type="hidden" name="coment_type" value="${tipo}">
            <div class="custom-file">
                <input type="file" onchange="archivo()" class="custom-file-input" name="image" id="image" accept="image/*,.pdf,.xlsx, .xls, .csv" required>
                <label class="custom-file-label" for="customFile" id="lbl_image" data-browse="Examinar">Examinar</label>
            </div>
        </div>`);
    } else if (tipo == 1) {
        $("#div_nueva_accion").append(`<div class="row" style="margin-bottom: 15px;">
            <div class="col-md-8">
                <i class="far fa-comment-dots fa-2x" style="color:#999999;padding-right: 10px;"></i><label style="font-family: Roboto Condensed;font-size: 20px;">Comentario</label>
            </div>
            <div class="col-md-4">
                <div class="row" style="text-align: end;">
                    <div class="col-md-6">
                        <button type="button" style="width:auto;" onclick="Comment(3);" class="btn btn-secondary" >Cerrar</button>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" style="width:auto;" class="btn btn-info" id="btn_nuevo_comentario">Comentar</button>
                    </div>      
                </div>
            </div>      
        </div>
        <div class="row">
            <input type="hidden" name="coment_type" value="${tipo}">
            <textarea class="form-control" name="new-txt-comentario" rows="3" maxlength="500" data-toggle="validation" data-required="true" data-message="Comentario." required></textarea>
        </div>`)
    } else {
        $("#div_nueva_accion").attr('style', '');
        return false;
    }
}

function archivo() {
    if ($("#image").val().length > 0) {
        $("#lbl_image").empty();
        $("#lbl_image").append(`${document.getElementById('image').files[0].name}`);
        $("#lbl_image").attr('style', 'color:#000000;');
        $("#lbl_image").removeClass('has-error');
    }
}

function solucion() { // campo de Nuevo Ticket
    $("#div_solucion").hide();
    $("#txt-solucion").attr("required", false);
    if ($("#sel-agestatus").val() == 3 || $("#sel-agestatus").val() == 5) {
        $("#div_solucion").show();
        $("#txt-solucion").attr("required", true);
    }
}

$("#update-agestatus").on('change', function () {
    $("#div_solucion_modal").empty();
    if ($("#update-agestatus").val() == 3) {
        $("#div_solucion_modal").append(`    
        <label style="font-family: 'Roboto Condensed';font-size:15px;">Solución:</label>
        <textarea class="form-control" name="txt-solucion-agestatus" rows="2" maxlength="450" data-toggle="validation" data-required="true" data-message="Solucion." required></textarea>
    `);
    }
    if ($("#update-agestatus").val() == 4) {
        $("#div_solucion_modal").append(`    
        <label style="font-family: 'Roboto Condensed';font-size:15px;">Motivo:</label>
        <textarea class="form-control" name="txt-solucion-agestatus" rows="2" maxlength="450" data-toggle="validation" data-required="true" data-message="Solucion." required></textarea>
    `);
    }
})

$("#sel-area").on('change', function () {
    $("#sel-actividad").empty();
    if ($("#sel-tecnico")) {
        $("#sel-tecnico").empty();
    }
    var area = new FormData();
    area.append('area', $("#sel-area").val());
    $.ajax({
        type: "post",
        url: `${urls}tickets/actividad_inge_por_area`,
        data: area,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (resp) {
            if (resp != false) {
                $("#sel-actividad").append('<option value="">Opciones..</option>');
                resp.actividades.forEach(act => {
                    $("#sel-actividad").append(`<option value="${act.ActividadId}">${act.Actividad_Actividad}</option>`);
                });
                if (resp.ingenieros != null) {
                    $("#sel-tecnico").append('<option value="">Opciones..</option>');
                    resp.ingenieros.forEach(ing => {
                        $("#sel-tecnico").append(`<option value="${ing.TecnicoId}">${ing.nombre}</option>`);
                    });
                }
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
            }
        }
    });
})

$("#sel-usuario").on('change', function () {
    $("#id_depto").val('');
    $("#name_user").val('');
    $("#sel-departamento").val('');
    $("#error_sel-usuario").text("");
    $("#sel-usuario").removeClass('has-error');
    if ($("#sel-usuario").val().length == 0) {
        return false;
    }
    var data = new FormData();
    data.append('id', $("#sel-usuario").val());
    $.ajax({
        data: data,
        url: `${urls}tickets/depto_user`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (respUser) {
            if (respUser) {
                $("#name_user").val(respUser.nombre);
                $("#id_depto").val(respUser.id_departament);
                $("#sel-departamento").val(respUser.departament);
            } else {
                $("#error_sel-usuario").text('Nomina no Encontrada');
                $("#sel-usuario").addClass('has-error');
            }
        }
    });
});

$("#date_range").daterangepicker({
    "locale":
    {
        "format": "YYYY-MM-DD",
        "separator": " - ",
        "applyLabel": "Guardar",
        "cancelLabel": "Cancelar",
        "fromLabel": "Desde",
        "toLabel": "Hasta",
        "customRangeLabel": "Personalizar",
        "daysOfWeek": ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
        "monthNames": ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Setiembre", "Octubre", "Noviembre", "Diciembre"],
        "firstDay": 1
    },

    "opens": "center",
    "drops": "down",
    "autoUpdateInput": false,
    "applyButtonClasses": "btn-danger",
    "autoApply": true

}).val(origen + " - " + hoy);

$("#date_range_reports").daterangepicker({
    "locale":
    {
        "format": "YYYY-MM-DD",
        "separator": " - ",
        "applyLabel": "Guardar",
        "cancelLabel": "Cancelar",
        "fromLabel": "Desde",
        "toLabel": "Hasta",
        "customRangeLabel": "Personalizar",
        "daysOfWeek": ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
        "monthNames": ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Setiembre", "Octubre", "Noviembre", "Diciembre"],
        "firstDay": 1
    },

    "opens": "center",
    "drops": "down",
    "autoUpdateInput": false,
    "applyButtonClasses": "btn-danger",
    "autoApply": true

}).val(origen + " - " + hoy);

$('#date_range').on('apply.daterangepicker', function (ev, picker) {
    a = picker.startDate.format('YYYY-MM-DD');
    b = picker.endDate.format('YYYY-MM-DD');
    $("#date_range").val(a + " - " + b)
    $("#fecha_inicio").val(a);
    $("#Fecha_fin").val(b);

    BuscarTickets()
});

$('#date_range_reports').on('apply.daterangepicker', function (ev, picker) {
    $("#fch-inicio").val(picker.startDate.format('YYYY-MM-DD'));
    $("#fch-fin").val(picker.endDate.format('YYYY-MM-DD'));
    $("#date_range_reports").val($("#fch-inicio").val() + " - " + $("#fch-fin").val())
    ObtenerInfomacion();

});

$("#txt-buscar").blur(function () {
    $("#txt-buscar").removeClass('has-error');
    $("#error_txt-buscar").text('');
    if (isNaN($.trim($("#txt-buscar").val())) == true) {
        $("#txt-buscar").addClass('has-error');
        $("#error_txt-buscar").text('Dato No Númerico');
        return false;
    } else {
        CargarTickets();
    }
});

$("#btn_cerrar").on('click', function () {
    $("#estatusTicketModal").modal("hide");
    $("#prioridadTicketModal").modal("hide");
    $("#reasignarTicketModal").modal("hide");
    $("#cancelarTicketModal").modal("hide");
});

$("#btn_cerrar_header").on('click', function () {
    $("#estatusTicketModal").modal("hide");
    $("#prioridadTicketModal").modal("hide");
    $("#reasignarTicketModal").modal("hide");
    $("#cancelarTicketModal").modal("hide");
});

$("#btn_reset_range").on('click', function () {
    $("#fch-inicio").val('2023-01-01');
    $("#fch-fin").val(hoy);
    $("#date_range_reports").val('2023-01-01' + " - " + hoy);
    ObtenerInfomacion();
});
