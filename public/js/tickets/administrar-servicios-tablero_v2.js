/*
 * ARCHIVO MODULO TICKETS IT
 * AUTOR:Horus Samael Rivas Pedraza
 * EMAIL:horus.riv.ped@gmail.com
 * CEL:56 2439 2632
 */

const $prioridad = { 0: 'No Definido', 2: 'BAJA', 3: 'MEDIA', 4: 'ALTA' };
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
        url: `${urls}tickets/servicios_generar_tickets`,
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
                    html: `El Ticket de Servicio General generado.`,
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
        url: `${urls}tickets/servicios_reasignar_ticket`,
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
        url: `${urls}tickets/servicios_agregar_chat_ticket`,
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
    const cancel = document.getElementById("txt-solucion-agestatus").value;
    ActualizarDatos(4, cancel);
    $("#cancelarTicketModal").modal('hide');
});

$("#form_concluir").submit(function (e) {
    e.preventDefault();
    const solucion = document.getElementById('solucion_swl')
    ActualizarDatos(3, solucion.value);
    $("#concluirTicketModal").modal('hide');
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
    $.ajax({
        url: `${urls}tickets/servicios_obtener_informacion_reportes`,
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
        url: `${urls}tickets/servicios_todos_tickets`,
        data: data,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (response) {
            if (response == 'nuevoTicket') {
                Swal.fire({
                    // icon: 'info',
                    iconHtml: '<i class="fas fa-concierge-bell" style="font-size: 43px;margin-bottom: 5px;"></i>',
                    title: 'Bienvenido al Módulo de Tickets de Servicios Generales',
                    text: 'En este módulo podrás gestionar tus solicitudes de mantenimiento y reparacion de oficina. ¡Comencemos!',
                    confirmButtonText: 'Continuar',
                    confirmButtonColor: '#17a2b8'
                });
            }else if(response == false) {

                Swal.fire({
                    // icon: 'info',
                    iconHtml: '<i class="fas fa-concierge-bell" style="font-size: 43px;margin-bottom: 5px;"></i>',
                    title: 'Bienvenido al Módulo de Tickets de Servicios Generales',
                    text: 'En este módulo podrás gestionar tus solicitudes de mantenimiento y reparacion de oficina. ¡Comencemos!',
                    confirmButtonText: 'Continuar',
                    confirmButtonColor: '#17a2b8'
                });

            }else if (response != false) {
                response.forEach(valor => {
                    var actividad = '';
                    //<b>${valor.Actividad_Actividad}</b>
                    if (valor.Ticket_EstatusId == 1) {
                        $("#todo").append(`<div onclick="Detalle(${valor.TicketId});" class="card-style-personal w-100 p-2" style="cursor: pointer;">
                        <h6>Folio ${valor.TicketId} </h6>
                        <div class="row">
                            <div class="col-sm-12">
                                <h6><i class="far fa-clock"></i>&nbsp;&nbsp;<b>${valor.Ticket_FechaCreacion}</b></h6>
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
                        //: <b>${valor.Actividad_Actividad}</b>
                        $("#inprogress").append(`<div onclick="Detalle(${valor.TicketId});" class="card-style-personal w-100 p-2" style="cursor: pointer;">
                        <h6>Folio ${valor.TicketId}</h6>
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
                        //: <b>' + valor.Actividad_Actividad + '</b>
                        $("#completed").append('<div onclick="Detalle(' + valor.TicketId + ');" class="card-style-personal w-100 p-2" style="cursor: pointer;border-left-color: ' + valor.Clasificacion_Color + ';"><h6>Folio ' + valor.TicketId + '</h6><div class="row"><div class="col-sm-12"><h6>Ingeniero: <b>' + valor.Tecnico_Nombre + '</b></h6></div></div><div class="row"><div class="col-sm-12"><p align = "justify">' + valor.Ticket_Descripcion + '</p></div></div></div>');
                        concluido++;
                    }
                    if (valor.Ticket_EstatusId == 4) {
                        //: <b>' + valor.Actividad_Actividad + '</b>
                        $("#cancelled").append('<div onclick="Detalle(' + valor.TicketId + ');" class="card-style-personal w-100" style="border-left-color: ' + valor.Clasificacion_Color + ';"><h6>Folio ' + valor.TicketId + '</h6><h6 class="pt-1">Ingeniero: ' + valor.Tecnico_Nombre + '</h6><div class="row"><div class="col-sm-12"><h6><i class="far fa-clock"></i>&nbsp;&nbsp;<b>' + valor.Ticket_FechaCreacion + '</b></h6></div></div></div>');
                        cancelado++;
                    }
                    if (valor.Ticket_EstatusId == 5) {
                        //: <b>' + valor.Actividad_Actividad + '</b>
                        $("#closed").append('<div onclick="Detalle(' + valor.TicketId + ');" class="card-style-personal w-100 p-2" style="cursor: pointer;border-left-color: ' + valor.Clasificacion_Color + ';"><h6>Folio ' + valor.TicketId + '</h6><div class="row"><div class="col-sm-12"><h6>Ingeniero: <b>' + valor.Tecnico_Nombre + '</b></h6></div></div><div class="row"><div class="col-sm-12"><p align = "justify">' + valor.Ticket_Descripcion + '</p></div></div></div>');
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
        url: `${urls}tickets/servicios_buscar_tickets`,
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
    $("#txt-solucion_detalle").val('');
    $("#div-tblfile").text('');
    $("#div-tblcomment").text('');
    $("#hrf-reasign").hide();
    $("#hrf-status").hide();
    $("#hrf-status-user").hide();
    $("#hrf-file").hide();
    $("#hrf-priority").hide();
    $("#hrf-comment").hide();
    $("#btn-cancel-users").hide();

    $("#div-txtsolucion").hide();
    $("#div-txtcancel").hide();
    $("#div-txtcancel").hide();
    $("#btn-proces-admin").hide();
    $("#btn-conclud-admin").hide();
    $("#btn-cancel-users").hide();
    $("#btn-authorize-users").hide();
    $("#btn-clossed-users").hide();
    $("#btn-request-spare-part-admin").hide();
    $("#icon_refacciones").hide();

    var id = new FormData();
    id.append('id_requets', ID);
    $.ajax({
        type: "post",
        url: `${urls}tickets/servicios_detalles_ticket`,
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
                        if (data.mng) {
                            $("#btn-proces-admin").show();
                            $("#btn-cancel-users").show();
                            $("#btn-authorize-users").show();
                        }
                        if (data.mng || data.usr) {
                            $("#btn-cancel-users").show();
                        }
                        break;
                    case 2:
                        if (data.mng) {
                            $("#btn-request-spare-part-admin").show();
                            $("#btn-conclud-admin").show();
                        }
                        if (data.mng || data.usr) {
                            $("#btn-cancel-users").show();
                            $("#hrf-file").show();
                            $("#hrf-comment").show();
                        }
                        break;
                    case 3:
                        $("#hrf-status").show();
                        if (data.usr) {
                            $("#btn-clossed-users").show();
                        }
                        break;
                    /* case 5:
                        $("#li_pdf").append(`<a href="${urls}tickets/ver-ticket-mantenimiento/${$.md5(key + ID)}" target="_blank" class="btn btn-outline-info btn-sm">
                            Descargar PDF <i class="far fa-file-pdf"></i>  </a>`);
                        $("#li_pdf").show();
                        break; */

                    default:
                        break;
                }
                var cont_comentario = 0;
                var cont_archivo = 0;
                if (data.chat) {
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
                }
                $("#div-tblfile").text(cont_archivo);
                $("#div-tblcomment").text(cont_comentario);
                console.log(data.info.Ticket_EstatusId);
                if (data.info.Ticket_EstatusId == 5 || data.info.Ticket_EstatusId == 3) {
                    $("#txt-solucion_detalle").val(data.info.Ticket_Solucion);
                    $("#div-txtsolucion").show();
                }
                if (data.info.Ticket_EstatusId == 4) {
                    $("#txt-cancel_motivo").val(data.info.motive_cancel);
                    $("#div-txtcancel").show();
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

function changeStatusForAdmin(id_estado) {
    if (id_estado == 2) {
        const SwalStatus = Swal.fire({
            title: `<i class="fas fa-project-diagram nav-icon"></i> Prioridad`,
            html: '<select id="prioridad_swl" class="form-control">' +
                '<option value="2">Baja</option>' +
                '<option value="3">Media</option>' +
                '<option value="4">Alta</option>' +
                '</select>',
            showCancelButton: false, // Desactiva el botón de cancelar
            showConfirmButton: true, // Muestra el botón de confirmar
            confirmButtonText: 'Aceptar', // Texto del botón de confirmar

        }).then((result) => {
            if (result.isConfirmed) {
                const selectedValue = $("#prioridad_swl").val();
                Swal.close(SwalStatus);
                ActualizarDatos(2, selectedValue);
            }
        });
    }
    if (id_estado == 3) {
        $("#solucion_swl").val('');
        $("#concluirTicketModal").modal('show');
    }
}

function changeStatusForUser(id_estado) {
    switch (id_estado) {
        case 4:
           /*  Swal.fire({
                icon: 'question',
                title: '¿Acepta la solucion de su Tickets?',
                showCancelButton: true,
                confirmButtonColor: '#7DCD67',
                confirmButtonText: 'Aceptar',
            }).then((result) => {
                if (result.isConfirmed) {
                    ActualizarDatos(5, null);
                }
            }) */
            Swal.fire({
                title: 'Calificar el servicio',
                showCloseButton: true,
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonText: '<i class="far fa-frown btn-calif"></i><br><B class="p-calif"> MALO </B>',
                denyButtonText: '<i class="far fa-smile btn-calif"></i><br><B class="p-calif"> REGULAR </B>',
                cancelButtonText: '<i class="far fa-smile-wink btn-calif"></i><br><B class="p-calif"> BUENO </B>',
                confirmButtonColor: '#E11F1C',
                denyButtonColor: '#FFCE08',
                cancelButtonColor: '#1ED760',
            }).then((result) => {
                if (result.isConfirmed) {
                    ActualizarDatos(5, 1);
                } else if (result.isDenied) {
                    ActualizarDatos(5, 2);
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    ActualizarDatos(5, 3);
                }
            });
            break;
            break;
        case 0:
            $("#id_request_cancelar").val($("#id_Request").val());
            $("#txt-cancelar-motivo").val('');
            $("#cancelarTicketModal").modal("show");
            break;

        default:
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Algo salió Mal! Contactar con el Administrador",
            });
            break;
    }
}

function ActualizarDatos(estatus, opcion) {
    const id_requets = $("#id_Request").val();
    let timerInterval = Swal.fire({
        title: 'Cambiando Estado!',
        html: 'Espere unos Segundos.',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading()
        },
    });
    const statusUp = new FormData();
    statusUp.append('id_request', id_requets);
    statusUp.append('estatus', estatus);
    statusUp.append('opcion', opcion);
    $.ajax({
        type: "post",
        url: `${urls}tickets/servicios_estado_ticket`,
        data: statusUp,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (update) {
            Swal.close(timerInterval);
            if (update == true) {
                $("#detalleTicketModal").modal("toggle");
                Swal.fire({
                    icon: 'success',
                    title: 'Estado Actualizado',
                    showConfirmButton: false,
                    timer: 1600
                });
                setTimeout(function () {
                    Detalle(id_requets);
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
}

function Reassign() {
    $("#id_request_reasignar").val('');
    $("#reasig-tecnico").val('');
    $("#tecnico_id_ant").val('');
    $("#id_request_reasignar").val('');
    $.ajax({
        type: "post",
        url: `${urls}tickets/servicios_actividad_inge_por_area`,
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

function Agregar() { // nuevo ticket
    $("#sel-actividad").empty();
    $("#sel-actividad").val('');
    $("#txt-descripcion").val('');
 /*    $.ajax({
        type: "post",
        url: `${urls}tickets/servicios_actividades`,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (resp) {
            if (resp != false) {
                console.log(resp);
                $("#sel-actividad").append('<option value="">Opciones..</option>');
                resp.forEach(act => {
                    $("#sel-actividad").append(`<option value="${act.ActividadId}">${act.Actividad_Actividad}</option>`);
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
            }
        }
    }); */
    $("#nuevoTicketModal").modal("show");
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

function limpiarError(campo) {
    console.log('limpiar el error_', campo.id);
    document.getElementById('error_' + campo.id).textContent = '';
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
    $("#reasignarTicketModal").modal("hide");
    $("#cancelarTicketModal").modal("hide");
    $("#concluirTicketModal").modal('hide');
});

$("#btn_cerrar_header").on('click', function () {
    $("#reasignarTicketModal").modal("hide");
    $("#cancelarTicketModal").modal("hide");
    $("#concluirTicketModal").modal('hide');
});

$("#btn_reset_range").on('click', function () {
    $("#fch-inicio").val('2023-01-01');
    $("#fch-fin").val(hoy);
    $("#date_range_reports").val('2023-01-01' + " - " + hoy);
    ObtenerInfomacion();
});
