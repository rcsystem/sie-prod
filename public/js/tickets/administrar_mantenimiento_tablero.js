/*
 * ARCHIVO MODULO MANTENIMINETO
 * AUTOR:Horus Samael Rivas Pedraza
 * EMAIL:horus.riv.ped@gmail.com
 * CEL:56 2439 2632
 */

const $prioridad = ['ERROR', 'BAJA', 'MEDIA', 'ALTA'];
const origen = '2023-03-31';
var d = new Date();
var month = d.getMonth() + 1;
var day = d.getDate();
const hoy = `${d.getFullYear()}-${month < 10 ? '0' : ''}${month}-${(day < 10 ? '0' : '') + day}`;


$(document).ready(function () {
    // $("#fch-fin").val(hoy);
    $("#sel-filtro-actividad").select2();
    $("#sel-filtro-usuario").select2();
    $("#sel-filtro-tecnico").select2();
    $("#sel-asignar-tecnico").select2();
    $("#sel-tipo-equipo").select2()
    $("#sel-clave").select2()
    $(".sidebar-mini").addClass('sidebar-collapse');
    CargarTickets();
});

function solicitarCalificacion() {
    Swal.fire({
        title: 'Calificar el servicio',
        text: 'Selecciona una opción',
        html: '<input id="comentario" class="swal2-input">',
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
            a = 1; // Asignar valor si se confirma
        } else if (result.isDenied) {
            a = 2; // Asignar valor si se deniega
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            a = 3; // Asignar valor si se cancela
        } else { a = 0;}
        console.log("valor :", a);
        if (a > 0) {
            $.ajax({});
        }
    });
}

//todos los tickes del usuario o area (en caso de ser Tecnicos)
function CargarTickets() {
    $.ajax({
        type: "post",
        url: `${urls}tickets/todos_tickets_mantenimiento`,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (response) {
            if (response === false) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
            } else {
                pintarTickets(response);
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
// consulta con los 4 select
function BuscarTickets() {
    if ($("#sel-filtro-prioridad").val().length == 0 && $("#sel-filtro-actividad").val().length == 0 &&
        $("#sel-filtro-usuario").val().length == 0 && $("#sel-filtro-tecnico").val().length == 0) {
        CargarTickets(); return false;
    }
    var data = new FormData();
    data.append('prioridad', $("#sel-filtro-prioridad").val());
    data.append('actividad', $("#sel-filtro-actividad").val());
    data.append('usuario', $("#sel-filtro-usuario").val());
    data.append('tecnico', $("#sel-filtro-tecnico").val());
    $.ajax({
        type: "post",
        url: `${urls}tickets/mantenimiento_buscar_tickets`,
        data: data,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (response) {
            if (response != false) {
                pintarTickets(response);
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
// busqueda por fechas
$('#date_range').on('apply.daterangepicker', function (ev, picker) {
    a = picker.startDate.format('YYYY-MM-DD');
    b = picker.endDate.format('YYYY-MM-DD');
    $("#date_range").val(a + " - " + b)
    var fechas = new FormData();
    fechas.append('date_star', a);
    fechas.append('date_end', b);
    $.ajax({
        type: "post",
        url: `${urls}tickets/mantenimiento_buscar_fecha_tickets`,
        data: fechas,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (response) {
            if (response != false) {
                pintarTickets(response);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "No se encontraron registros dentro de las fechas proporcionadas",
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
});
// busqueda por folio
$("#txt-buscar").blur(function () {
    $("#txt-buscar").removeClass('has-error');
    $("#error_txt-buscar").text('');
    if ($.trim($("#txt-buscar").val()).length > 0) {
        if (isNaN($.trim($("#txt-buscar").val())) == true) {
            $("#txt-buscar").addClass('has-error');
            $("#error_txt-buscar").text('Dato No Númerico');
            return false;
        } else {
            var folio = new FormData();
            folio.append('folio', $.trim($("#txt-buscar").val()));
            $.ajax({
                type: "post",
                url: `${urls}tickets/mantenimiento_buscar_folio_tickets`,
                data: folio,
                cache: false,
                dataType: "json",
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response != false) {
                        pintarTickets(response);
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "No se encontraron registros con el Folio proporcionado",
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
    } else {
        CargarTickets();
    }
});

// pinta los tickets obtenidos por las consultas
function pintarTickets(dataArray) {
    //contadores 
    var nuevos = 0;
    var autorizado = 0;
    var proceso = 0;
    var concluido = 0;
    var cerrado = 0;
    var cancelado = 0;

    $("#todo").empty();
    $("#autorizado").empty();
    $("#inprogress").empty();
    $("#completed").empty();
    $("#cancelled").empty();
    $("#closed").empty();
    $("#div-ntbl").text();
    $("#div-auto").text();
    $("#div-eptbl").text();
    $("#div-ctbl").text();
    $("#div-cltbl").text();
    $("#div-catbl").text();

    dataArray.forEach(valor => {
        if (valor.estatus == 1) {
            $("#todo").append(`<div onclick="Detalle(${valor.id_order});" class="card-style-personal w-100 p-2" style="cursor: pointer;">
            <h6>Folio ${valor.id_order}: <b>${valor.actividad}</b></h6>
            <div class="row">
                <div class="col-sm-12">
                    <h6><i class="far fa-clock"></i>&nbsp;&nbsp;<b>${valor.created_at}</b></h6>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <h6>Solicitado por: <b>${valor.name_user}</b></h6>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <p style="text-align: justify;">${valor.description}</p>
                </div>
            </div>
            </div> `);
            nuevos++;
        }
        if (valor.estatus == 2) {
            $("#autorizado").append(`<div onclick="Detalle(${valor.id_order});" class="card-style-personal w-100 p-2" style="cursor: pointer;">
            <h6>Folio ${valor.id_order}: <b>${valor.actividad}</b></h6>
            <div class="row">
                <div class="col-sm-12">
                    <h6><i class="far fa-clock"></i>&nbsp;&nbsp;<b>${valor.created_at}</b></h6>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <h6>Solicitado por: <b>${valor.name_user}</b></h6>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <p style="text-align: justify;">${valor.description}</p>
                </div>
            </div>
            </div> `);
            autorizado++;
        }
        if (valor.estatus == 3) {
            $("#inprogress").append(`<div onclick="Detalle(${valor.id_order});" class="card-style-personal w-100 p-2" style="cursor: pointer;">
            <h6>Folio ${valor.id_order}: <b>${valor.actividad}</b></h6>
            <div class="row">
                <div class="col-sm-12">
                    <h6><i class="far fa-clock"></i>&nbsp;&nbsp;<b>${valor.created_at}</b></h6>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <h6>Solicitado por: <b>${valor.name_user}</b></h6>
                </div>
                <div class="col-sm-12">
                    <h6>Tecnico: <b>${valor.tecnico}</b></h6>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <p style="text-align: justify;">${valor.description}</p>
                </div>
            </div>
                    </div>`);
            proceso++;
        }
        if (valor.estatus == 4) {
            $("#completed").append(`<div onclick="Detalle(${valor.id_order});" class="card-style-personal w-100 p-2" style="cursor: pointer;">
            <h6>Folio ${valor.id_order}: <b>${valor.actividad}</b></h6>
            <div class="row">
                <div class="col-sm-12">
                    <h6>Solicitado por: <b>${valor.name_user}</b></h6>
                </div>
                <div class="col-sm-12">
                    <h6>Tecnico: <b>${valor.tecnico}</b></h6>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <p style="text-align: justify;">${valor.description}</p>
                </div>
            </div>
            </div> `);
            concluido++;
        }
        if (valor.estatus == 5) {
            $("#closed").append(`<div onclick="Detalle(${valor.id_order});" class="card-style-personal w-100 p-2" style="cursor: pointer;">
            <h6>Folio ${valor.id_order}: <b>${valor.actividad}</b></h6>
            <div class="row">
                <div class="col-sm-12">
                    <h6>Solicitado por: <b>${valor.name_user}</b></h6>
                </div>
                <div class="col-sm-12">
                    <h6>Tecnico: <b>${valor.tecnico}</b></h6>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <p style="text-align: justify;">${valor.description}</p>
                </div>
            </div>
            </div> `);
            cerrado++;
        }
        if (valor.estatus == 0) {
            $("#cancelled").append(`<div onclick="Detalle(${valor.id_order});" class="card-style-personal w-100 p-2" style="cursor: pointer;">
            <h6>Folio ${valor.id_order}: <b>${valor.actividad}</b></h6>
            <div class="row">
                <div class="col-sm-12">
                    <h6><i class="far fa-clock"></i>&nbsp;&nbsp;<b>${valor.cancel_at}</b></h6>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <h6>Solicitado por: <b>${valor.name_user}</b></h6>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <p style="text-align: justify;">${valor.description}</p>
                </div>
            </div>
            </div> `);
            cancelado++;
        }
    });
    $("#div-ntbl").text(nuevos);
    $("#div-auto").text(autorizado);
    $("#div-eptbl").text(proceso);
    $("#div-ctbl").text(concluido);
    $("#div-cltbl").text(cerrado);
    $("#div-catbl").text(cancelado);
}

function Agregar() { // nuevo ticket
    $("#sel-tipo-serv-equipo").val(1);
    // $("#sel-tipo-serv-equipo").val('');
    $("#sel-mantenimiento").val('');
    $("#sel-codigo").val('');
    $("#txt-descripcion").val('');
    $("#sel-tipo-equipo").empty();
    $("#sel-clave").empty();
    $("#div_opcion2").hide();
    $("#div_clave2").hide();
    $("#sel-otro").val('');
    $("#sel-otro").attr('required', false);
    machineData(1)
    $("#nuevoTicketModal").modal("show");
}

function Detalle(ID) {
    $("#id_Request").val('');
    $("#estatus_id").val('');
    $("#txt_detalle_equipo").val('');
    $("#txt_detalle_mante").val('');
    $("#txt_detalle_falla").val('');
    $("#txt-ddescripcion").val('');
    $("#txt-solucion").val('');
    $("#txt-cancel").val('');
    $("#txt_detalle_causa").val('');
    $("#date_star_procces").val('');
    $("#date_end_procces").val('');

    $("#lbl-folio").text('');
    $("#lbl-fecha").text('');
    $("#lbl-hora").text('');
    $("#lbl-prioridad").text('');
    $("#lbl-usuario").text('');
    $("#lbl-tecnico").text('');
    $("#fecha_cancelado").text('');

    $("#div-txtsolucion").hide();
    $("#div-txtcancel").hide();
    $("#btn-proces-admin").hide();
    $("#btn-conclud-admin").hide();
    $("#btn-cancel-users").hide();
    $("#btn-authorize-users").hide();
    $("#btn-clossed-users").hide();
    $("#btn-request-spare-part-admin").hide();
    $("#icon_refacciones").hide();

    $("#li_pdf").empty();
    $("#li_pdf").hide();

    $("#div_titulo_acciones").hide()
    $("#div_refacciones").hide();
    $("#div_date_star_procces").hide();
    $("#div_date_end_procces").hide();
    $("#div_refacciones").empty();

    var id = new FormData();
    id.append('id_requets', ID);
    $.ajax({
        type: "post",
        url: `${urls}tickets/detalles_ticket_mantenimiento`,
        data: id,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (data) {
            if (data != false) {
                $('#lbl-folio').text(ID);
                $("#id_Request").val(ID);
                $("#estatus_id").val(data.info.status);
                $("#txt_detalle_equipo").val(data.info.equipo);
                $("#txt_detalle_mante").val(data.info.Actividad_Actividad);
                $("#txt_detalle_falla").val(data.info.name_fail);
                $("#txt-ddescripcion").val(data.info.description);
                if (parseInt(data.info.status) > 3) {
                    $("#txt-solucion").val(data.info.work_done);
                    $("#txt_detalle_causa").val(data.info.cause_code);
                    $("#div-txtsolucion").show();
                }
                if (parseInt(data.info.status) == 0) {
                    $("#txt-cancel").val(data.info.motive_cancel);
                    $("#fecha_cancelado").text(data.fecha_cancel);
                    $("#div-txtcancel").show();
                }
                $('#lbl-folio').text(ID);
                $("#lbl-fecha").text(data.fecha);
                $("#lbl-hora").text(data.hora);
                priority = (data.info.id_priority != null && data.info.id_priority != '') ? $prioridad[data.info.id_priority] : 'Sin Definir';
                $("#lbl-prioridad").text(priority);
                $("#lbl-usuario").text(data.info.name_user);
                tecnico = (data.info.tecnico != null && data.info.tecnico != '') ? data.info.tecnico : 'No Asignado';
                $("#lbl-tecnico").text(tecnico);

                if (data.info.status != 5 && data.info.status != 0) {
                    $("#div_titulo_acciones").show();
                }
                switch (parseInt(data.info.status)) {
                    case 1:
                        $("#btn-cancel-users").show();
                        $("#btn-authorize-users").show();
                        break;
                    case 2:
                        $("#btn-proces-admin").show();
                        $("#btn-cancel-users").show();
                        break;
                    case 3:
                        $("#btn-request-spare-part-admin").show();
                        $("#btn-conclud-admin").show();
                        $("#btn-cancel-users").show();
                        $("#hrf-status").show();
                        break;
                    case 4:
                        $("#btn-clossed-users").show();
                        break;
                    case 5:
                        $("#li_pdf").append(`<a href="${urls}tickets/ver-ticket-mantenimiento/${$.md5(key + ID)}" target="_blank" class="btn btn-outline-info btn-sm">
                            Descargar PDF <i class="far fa-file-pdf"></i>  </a>`);
                        $("#li_pdf").show();
                        break;

                    default:
                        break;
                }

                if (data.refaccion != null) {
                    data.refaccion.forEach(key => {
                        console.log(key.date_star);
                        $("#icon_refacciones").show();
                        $("#div_refacciones").show();
                        if (key.date_end != null && key.date_end != '' && key.date_end != '0000-00-00') {
                            opcion_campo = `<h6>Fecha de Recepcion: <i class="far fa-clock"></i><input type="date" value="${key.date_end}" style="border:none; font-weight:bold;" readonly></h6>`
                        } else {
                            if (data.nivel == true) {
                                opcion_campo = `<button type="button" onclick="submitRecepcion(${key.id_item})" class="btn btn-success" style="margin-top: -10px;">Confirmar Recepcion de Pieza</button>`;
                            } else {
                                opcion_campo = `<h6>Fecha de Recepcion: <b>En camino.</b></h6>`;
                            }
                        }
                        $("#div_refacciones").append(`
                        <div class="card-style-personal w-100 p-2" style="margin-bottom: 1rem;">
                            <div class="row">
                                <div class="col-sm-6">
                                    <p>Código de Pieza: <b>${key.code_spare_part}</b></p>
                                </div>
                                <div class="col-sm-6">
                                    <p>Numero de Orden: <b>${key.num_order}</b></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <p>Comprador: <b>${key.assigned_buyer_name}</b></p>
                                </div>
                                <div class="col-sm-6">
                                    <p>Fecha Estimada: <input type="date" value="${key.estimated_delivery_date}" style="border:none; font-weight:bold;" readonly></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <h6>Fecha de Solicitud: <i class="far fa-clock"></i><input type="date" value="${key.date_star}" style="border:none; font-weight:bold;" readonly></h6>
                                </div>
                                <div class="col-sm-6">
                                    ${opcion_campo}
                                </div>
                            </div>
                        </div>`);
                    });
                }
                if (data.info.star != null) {
                    $("#div_date_star_procces").show();
                    $("#date_star_procces").val(data.info.star);
                    if (data.info.end != null) {
                        $("#div_date_end_procces").show();
                        $("#date_end_procces").val(data.info.end);
                    }
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

function changeStatusForUser(id_estado) {
    switch (id_estado) {
        case 2:
            Swal.fire({
                icon: 'question',
                title: '¿Seguro de Autorizar este Ticket?',
                showCancelButton: true,
                confirmButtonColor: '#7DCD67',
                confirmButtonText: 'Autorizar',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    submitSwal(1);
                }
            })
            break;
        case 5:
            Swal.fire({
                icon: 'question',
                title: '¿Acepta la solucion de su Tickets?',
                showCancelButton: true,
                confirmButtonColor: '#7DCD67',
                confirmButtonText: 'Autorizar',
            }).then((result) => {
                if (result.isConfirmed) {
                    submitSwal(2);
                }
            })
            break;
        case 0:
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

function changeStatusForAdmin(id_estado) {
    switch (id_estado) {
        case 3:
            $("#sel-asignar-tecnico").val('');
            $("#sel-asignar-prioridad").val('');
            $("#asignarTicketModal").modal("show");
            break;
        case 4:
            $("#sel-concluir-causa").val('');
            $("#sel-concluir-otro-causa").val('');
            $("#txt-concluir-realizado").val('');
            $("#div_concluir_otra_causa").hide();
            $("#concluirTicketModal").modal("show");
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

function requestSparePart(active) {
    $("#form_solicitar")[0].reset();
    $("#solicitarTicketModal").modal("show");
}

function machineData(type) {
    $("#div_opcion2").hide();
    $("#div_opcion1").show();
    $("#sel-otro").val('');
    $("#sel-clave").attr('required', true);
    $("#sel-otro").attr('required', false);
    if (type == 1) {
        $("#sel-tipo-equipo").empty();
        $("#sel-tipo-equipo").append(`<option value="">Opciones...</option>`);
        $("#sel-clave").empty();
    }
    if (type == 2) {
        if ($("#sel-tipo-equipo").val() === 'OTRO') {
            $("#div_opcion2").show();
            $("#div_opcion1").hide();
            $("#sel-clave").attr('required', false);
            $("#sel-otro").attr('required', true);
        } else {
            $("#sel-clave").empty();
            $("#sel-clave").append(`<option value="">Opciones...</option>`);
        }
    }
    let data = new FormData();
    data.append('id_area', $("#sel-area-equipo").val());
    data.append('equip', $("#sel-tipo-equipo").val());
    $.ajax({
        type: "post",
        url: `${urls}tickets/mantenimiento_datos_maquinas`,
        data: data,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (data) {
            if (data != false) {
                data.forEach(key => {
                    if (type == 1) {
                        $("#sel-tipo-equipo").append(`<option value="${key.equip}">${key.equip}</option>`);
                    }
                    if (type == 2) {
                        $("#sel-clave").append(`<option value="${key.id_machine}">${key.id_machine}</option>`);
                    }
                });

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

$("#sel-tipo-serv-equipo").on('change', function () {
    if (this.value == 2) {
        console.log("Opción     2");

        $("#sel-tipo-equipo").val("OTRO").trigger('change');
        // selectElement.value = "OTRO";
        machineData(2);
    } else {
        console.log("Opción     1");
        $("#sel-tipo-equipo").val("").trigger('change');
        // selectElement.value = "";
        machineData(1);
    }


    // selectElement.insertBefore(option, selectElement.firstChild);

    console.log('valor del', selectElement.value);
})

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
        url: `${urls}tickets/mantenimiento_generar_ticket`,
        data: formCreate,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (save) {
            Swal.close(timerInterval);
            $("#btn_nuevo_ticket").prop('disabled', false);
            if (save == true) {
                $("#form_nuevo_ticket")[0].reset();
                $("#nuevoTicketModal").modal("hide");
                Swal.fire({
                    icon: 'success',
                    title: "¡Solicitud Exitosa!",
                    text: 'Se generó correctamente el Ticket',
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

function submitSwal(tipo) {
    let timerInterval = Swal.fire({
        title: 'Guardando Cambio!',
        html: 'Espere unos Segundos.',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading()
        },
    });
    if (tipo == 1) { urlsSwal = `${urls}tickets/mantenimiento_autorizar_ticket`; }
    else { urlsSwal = `${urls}tickets/mantenimiento_cerrar_ticket` }
    var formSwal = new FormData();
    formSwal.append('id_request', $("#id_Request").val());
    $.ajax({
        type: "post",
        url: urlsSwal,
        data: formSwal,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (save) {
            Swal.close(timerInterval);
            if (save == true) {
                Swal.fire({
                    icon: 'success',
                    title: "¡Cambio Exitoso!",
                    text: 'Se guardo correctamente el cambio del Ticket',
                });
                CargarTickets();
                $("#detalleTicketModal").modal("toggle");
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

function submitRecepcion(id_item) {
    $("#btn_submit_refaccion").prop('disabled', true);
    let timerInterval = Swal.fire({
        title: 'Guardando Cambio del Ticket!',
        html: 'Espere unos Segundos.',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading()
        },
    });
    const id = $("#id_Request").val();
    var requestSpare = new FormData();
    requestSpare.append('id_item', id_item);
    requestSpare.append('fase-refaccion', 2);
    $.ajax({
        type: "post",
        url: `${urls}tickets/mantenimiento_refaccion_ticket`,
        data: requestSpare,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (save) {
            Swal.close(timerInterval);
            $("#btn_submit_refaccion").prop('disabled', false);
            if (save == true) {
                $("#div_add_refacciones").empty();
                $("#detalleTicketModal").modal("toggle");
                Swal.fire({
                    icon: 'success',
                    title: "¡Cambio Exitoso!",
                    text: 'Se registró la fecha de recepción',
                });
                setTimeout(function () {
                    Detalle(id);
                }, 200);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
            }
        },
    }).fail(function (jqXHR, textStatus, errorThrown) {
        $("#btn_submit_refaccion").prop('disabled', false);
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

$("#form_asignar").submit(function (e) {
    e.preventDefault();
    $("#btn_asignar").prop('disabled', true);
    let timerInterval = Swal.fire({
        title: 'Guardando Cambio del Ticket!',
        html: 'Espere unos Segundos.',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading()
        },
    });
    var formAssig = new FormData($("#form_asignar")[0]);
    formAssig.append('id_request', $("#id_Request").val());
    $.ajax({
        type: "post",
        url: `${urls}tickets/mantenimiento_asignar_ticket`,
        data: formAssig,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (save) {
            Swal.close(timerInterval);
            $("#btn_asignar").prop('disabled', false);
            if (save == true) {
                $("#form_asignar")[0].reset();
                $("#asignarTicketModal").modal("toggle");
                $("#detalleTicketModal").modal("toggle");
                Swal.fire({
                    icon: 'success',
                    title: "¡Cambio Exitoso!",
                    text: 'Se Asigno un tecnico al Ticket y ser inicio el proceso del Ticket correctamente',
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
        $("#btn_asignar").prop('disabled', false);
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

$("#form_concluir").submit(function (e) {
    e.preventDefault();
    $("#btn_concluir").prop('disabled', true);
    let timerInterval = Swal.fire({
        title: 'Guardando Cambio del Ticket!',
        html: 'Espere unos Segundos.',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading()
        },
    });
    var formAssig = new FormData($("#form_concluir")[0]);
    formAssig.append('id_request', $("#id_Request").val());
    $.ajax({
        type: "post",
        url: `${urls}tickets/mantenimiento_concluir_ticket`,
        data: formAssig,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (save) {
            Swal.close(timerInterval);
            $("#btn_concluir").prop('disabled', false);
            if (save == true) {
                $("#form_concluir")[0].reset();
                $("#concluirTicketModal").modal("toggle");
                $("#detalleTicketModal").modal("toggle");
                Swal.fire({
                    icon: 'success',
                    title: "¡Cambio Exitoso!",
                    text: 'Se concluyo el Ticket',
                });
                CargarTickets();
            } else if (save == "pz") {
                $("#concluirTicketModal").modal("toggle");
                Swal.fire({
                    icon: 'info',
                    title: "¡Datos Faltante!",
                    text: 'Confirma Recepcion de las Pieza faltante',
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
        $("#btn_concluir").prop('disabled', false);
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

$("#form_cancelar").submit(function (e) {
    e.preventDefault();
    $("#btn_cancelar").prop('disabled', true);
    let timerInterval = Swal.fire({
        title: 'Cancelando el Ticket!',
        html: 'Espere unos Segundos.',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading()
        },
    });
    var formAssig = new FormData($("#form_cancelar")[0]);
    formAssig.append('id_request', $("#id_Request").val());
    $.ajax({
        type: "post",
        url: `${urls}tickets/mantenimiento_cancelar_ticket`,
        data: formAssig,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (save) {
            Swal.close(timerInterval);
            $("#btn_cancelar").prop('disabled', false);
            if (save == true) {
                $("#form_cancelar")[0].reset();
                $("#cancelarTicketModal").modal("toggle");
                $("#detalleTicketModal").modal("toggle");
                Swal.fire({
                    icon: 'success',
                    title: "¡Cancelacion Exitoso!",
                    text: 'Se cancelo el Ticket',
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
        $("#btn_cancelar").prop('disabled', false);
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

$("#form_solicitar").submit(function (e) {
    e.preventDefault();
    $("#btn_solicitar").prop('disabled', true);
    let timerInterval = Swal.fire({
        title: 'Guardando Cambio del Ticket!',
        html: 'Espere unos Segundos.',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading()
        },
    });
    const id = $("#id_Request").val();
    var requestSpare = new FormData($("#form_solicitar")[0]);
    requestSpare.append('id_request', id);
    /*     requestSpare.append('orden_compra', $("#orden_compra").val());
        requestSpare.append('code_pz', $("#orden_codigo").val());
        requestSpare.append('name_buyer', $("#orden_nombre").val());
        requestSpare.append('date_contemplated', $("#orden_fecha").val());
        requestSpare.append('date_contemplated', $("#cant_pz").val());
        requestSpare.append('date_contemplated', $("#orden_fecha").val());
        requestSpare.append('date_contemplated', $("#orden_fecha").val()); */
    requestSpare.append('fase-refaccion', 1);
    $.ajax({
        type: "post",
        url: `${urls}tickets/mantenimiento_refaccion_ticket`,
        data: requestSpare,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (save) {
            Swal.close(timerInterval);
            $("#btn_solicitar").prop('disabled', false);
            if (save == true) {
                $("#solicitarTicketModal").modal("hide");
                $("#detalleTicketModal").modal("toggle");
                Swal.fire({
                    icon: 'success',
                    title: "¡Solicitud Exitosa!",
                    text: 'Se registro la solicitud de refaccion',
                });
                setTimeout(function () {
                    Detalle(id);
                }, 200);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
            }
        },
    }).fail(function (jqXHR, textStatus, errorThrown) {
        $("#btn_solicitar").prop('disabled', false);
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

})

$("#btn_cerrar").on('click', function () {
    $("#asignarTicketModal").modal("hide");
    $("#concluirTicketModal").modal("hide");
    $("#cancelarTicketModal").modal("hide");
    $("#solicitarTicketModal").modal("hide");
});

$("#btn_cerrar_header").on('click', function () {
    $("#asignarTicketModal").modal("hide");
    $("#concluirTicketModal").modal("hide");
    $("#cancelarTicketModal").modal("hide");
    $("#solicitarTicketModal").modal("hide");
});

$("#sel-concluir-causa").on('change', function () {
    $("#div_concluir_otra_causa").hide();
    $("#sel-concluir-otro-causa").val('');
    $("#sel-concluir-otro-causa").attr('required', false);
    if ($("#sel-concluir-causa").val() == "Otro") {
        $("#div_concluir_otra_causa").show();
        $("#sel-concluir-otro-causa").attr('required', true);
    }
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