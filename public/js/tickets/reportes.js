/*
 * ARCHIVO MODULO TICKETS IT
 * AUTOR:Horus Samael Rivas Pedraza
 * EMAIL:horus.riv.ped@gmail.com
 * CEL:56 2439 2632
 */

var d = new Date();
var month = d.getMonth() + 1;
var day = d.getDate();
const origen = '2023-01-01';
const hoy = `${d.getFullYear()}-${month < 10 ? '0' : ''}${month}-${(day < 10 ? '0' : '') + day}`;

$(document).ready(function () {
    $("#fch-fin").val(hoy);
    $("#fch-inicio").val(origen);
    $(".sidebar-mini").addClass('sidebar-collapse');
    ObtenerInfomacion();
});

function ObtenerInfomacion() {
    $("#div-nuevo").empty();
    $("#div-proceso").empty();
    $("#div-concluido").empty();
    $("#div-cerrado").empty();
    $("#div-cumplimiento").empty();
    $("#info_box_cumplimiento").attr('class', "info-box");
    star = ($("#fch-inicio").val() == null) ? origen : $("#fch-inicio").val();
    end = ($("#fch-fin").val() == null) ? hoy : $("#fch-fin").val();
    var depto = new FormData();
    depto.append('id_area', $("#direct_area").val());
    depto.append('star_date', star);
    depto.append('end_date', end);
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
                console.log(data);
                if (data === 'DATOS NO EXISTENTES') {
                    Swal.fire({
                        icon: "info",
                        title: "Oops...",
                        text: "No se encontró ningún registro.",
                    });
                    return false;
                }
                if ($("#fch-inicio").val() != origen && $("#fch-fin").val() != hoy) {
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
                    <td colspan="2">
                        <div class="row" style="text-align:center;">
                            <div class="col-md-5">
                                <b>${total}</b>
                            </div>
                            <div class="col-md-7">
                                <b>${total_tiempo.toFixed(2)}</b></center>
                            </div>
                        </div>
                    </td>
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
                        markp_dos += "<tr><td>" + valor.nombre + "</td><td style='text-align:center;'>" + valor.porcentaje + "%</td><td style='text-align:center;'>" + valor.total_horas, + "</td></tr>";
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
                            <td colspan="3">
                                <div class="row" style="display:flex;text-align:center;">
                                    <div class="col-md-6">
                                        <b>General</b>
                                    </div>
                                    <div class="col-md-3">
                                        ${(total_cumpliminto_t / no_inge).toFixed(2)} %
                                    </div>
                                    <div class="col-md-3">
                                        <b>${total_tiempo_tecnicos.toFixed(2)} </b>
                                    </div>
                                </div>
                            </td>
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

$('#date_range_reports').on('apply.daterangepicker', function (ev, picker) {
    $("#fch-inicio").val(picker.startDate.format('YYYY-MM-DD'));
    $("#fch-fin").val(picker.endDate.format('YYYY-MM-DD'));
    $("#date_range_reports").val($("#fch-inicio").val() + " - " + $("#fch-fin").val())
    ObtenerInfomacion();
});

$("#btn_reset_range").on('click', function () {
    $("#fch-inicio").val(origen);
    $("#fch-fin").val(hoy);
    $("#date_range_reports").val(origen + " - " + hoy);
    ObtenerInfomacion();
});