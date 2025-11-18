$(document).ready(function () {
    $('#departamento').select2();
    $('#mes_reporte').select2();
    flatpickr("#fechas_reporte", {
        locale: "es",
        mode: "range",
        dateFormat: "Y-m-d",
        minDate: "2024-01-01",
        maxDate: "today",
        defaultDate: ["2024-01-01", "today"],
    });
    traerDatos();
});

function traerDatos() {
    const fechaInicio = document.querySelector("#fechas_reporte")._flatpickr.selectedDates[0];
    const fechaFin = document.querySelector("#fechas_reporte")._flatpickr.selectedDates[1];

    if (fechaInicio && fechaFin) {
        // const timerInterval = Swal.fire({
        //     allowOutsideClick: false,
        //     title: `<i class="fas fa-search" style="margin-rigth:5px;"></i> CARGANDO INFORMACIÓN`,
        //     timerProgressBar: true,
        //     didOpen: () => {
        //         Swal.showLoading()
        //     },
        // });
        const data_form = new FormData($("#form_campos_incidencias")[0]);
        $.ajax({
            type: 'post',
            url: `${urls}recorridos-HSE/todas_incidencias_fechas`,
            data: data_form,
            cache: false,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function (response) {
                // Swal.close(timerInterval);
                var labels = [];
                var dataTipo1 = [];
                var dataTipo2 = [];
                var fechas_orden = []

                response.data.forEach(function (item) {
                    labels.push(item.mes);
                    dataTipo1.push(parseInt(item.actos));
                    dataTipo2.push(parseInt(item.condiciones));
                    fechas_orden.push(item.orden)
                });

                // Configurar los datos del gráfico
                var data = {
                    labels: labels,
                    datasets: [{
                        label: 'Actos Inseguros',
                        backgroundColor: 'rgba(255, 99, 132, 0.3)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 3,
                        data: dataTipo1
                    }, {
                        label: 'Condiciones Inseguras',
                        backgroundColor: 'rgba(247, 215, 2, 0.3)',
                        borderColor: 'rgba(247, 215, 2, 1)',
                        borderWidth: 3,
                        data: dataTipo2
                    }]
                };

                var options = {

                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                stepSize: 1,
                                max: 10,
                            },
                            gridLines: {
                                display: false,
                            },
                        }],
                        xAxes: [{
                            gridLines: {
                                display: true,
                            },
                        }]
                    }
                };

                // Crear el gráfico de barras
                var ctx = document.getElementById('myChart').getContext('2d');
                const myChart = new Chart(ctx, {
                    type: 'bar',
                    data: data,
                    options: options
                });

                // Agregar evento de clic a las barras del gráfico
                $('#myChart').on('click', function (evt) {
                    var firstPoint = myChart.getElementAtEvent(evt)[0];

                    var tipo = myChart.data.datasets[firstPoint._datasetIndex].label[0] == 'A' ? 1 : 2;
                    abrirModal(tipo, fechas_orden[firstPoint._index], myChart.data.labels[firstPoint._index]);

                });
            },
            error: function (xhr, status, error) {
                console.error('Error al obtener los datos:', error);
            }
        });
    }

}

// Función para abrir el modal con el tipo y el mes
function abrirModal(tipo, mes_fecha, mes_texto) {
    const tipo_texto = (tipo == 1) ? "Actos Inseguros" : "Condiciones Inseguras";
    $('#modal_body').empty();
    $('#tittle_modal').empty();
    $('#tittle_modal').append(`<i class="far fa-calendar-alt" style="margin-right:10px"></i> Incidencias de ${tipo_texto} del mes de ${mes_texto}`);
    const data_modal = new FormData();
    data_modal.append('tipo', tipo);
    data_modal.append('mes', mes_fecha);
    data_modal.append('depto', $("#departamento").val());
    $.ajax({
        type: "post",
        url: `${urls}recorridos-HSE/lista_incidencias_tipo_mes`,
        data: data_modal,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (response) {
            if (response.hasOwnProperty('xdebug_message')) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal en lista_incidencias_tipo_mes! Contactar con el Administrador",
                });
                console.log(response.xdebug_message);
            } else if (response === false) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
            } else {
                response.forEach(item => {
                    if (item.type == 1) {
                        $("#modal_body").append(`<div onclick="Detalle(${item.id_incidents});" class="card-style-personal w-100 col-md-3" style="cursor: pointer;padding: 5px 15px 15px 15px !important;background-color: ${item.lvl_color};border: 2px dotted ${item.lvl_border};">
                            <div class="row" style="padding: 10px 10px 1px 10px;">
                                <div class="col-sm-8" style="align-items: center;display: flex;">
                                    <h5><b>${item.categoria}</b></h5>
                                </div>
                                <div class="col-sm-4" style="text-align:end;">
                                    <b style ="font-size: 14px;"> ${item.lvl_txt}</b>
                                </div>
                            </div> 
                            <hr style="margin: 0px 0px 15px 0px;">
                            <div class="row">
                                <div class="col-sm-12" style="text-align:end;">
                                    <h6><b>${item.fecha}  ${item.hora}</b><i class="far fa-clock" style="margin-left:5px"></i></h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <b style="text-align: justify;">- ${item.name_user}<BR>- ${item.departament}</b>
                                </div>
                            </div>
                        </div>`);
                    } else {
                        $("#modal_body").append(`<div onclick="Detalle(${item.id_incidents});" class="card-style-personal w-100 col-md-3" style="cursor: pointer;padding: 5px 15px 15px 15px !important;background-color: ${item.lvl_color};border: 2px dotted ${item.lvl_border};">
                            <div class="row" style="padding: 10px 10px 1px 10px;">
                                <div class="col-sm-8" style="align-items: center;display: flex;">
                                    <h5><b>${item.categoria}</b></h5>
                                </div>
                                <div class="col-sm-4" style="text-align:end;">
                                    <!--<b style ="font-size: 14px;"> ${item.lvl_txt}</b-->
                                </div>
                            </div> 
                            <hr style="margin: 0px 0px 15px 0px;">
                            <div class="row">
                                <div class="col-sm-12" style="text-align:end;">
                                    <h6><b>${item.fecha}  ${item.hora}</b><i class="far fa-clock" style="margin-left:5px"></i></h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <b style="text-align: justify;">- ${item.departament}</b>
                                </div>
                            </div>
                        </div>`);
                    }
                });
            }
        },
    })
    $('#miModal').modal('show');
}

function Detalle(id_request) {
    window.open(`${urls}recorridos-HSE/ver-detalles-incidencia/${$.md5(key + id_request)}`, '_blank');
}

$("#btn_descargar_excel").on("click", function (e) {
    e.preventDefault();
    const fechaInicio = document.querySelector("#fechas_reporte")._flatpickr.selectedDates[0];
    const fechaFin = document.querySelector("#fechas_reporte")._flatpickr.selectedDates[1];

    // if (fechaInicio && fechaFin) {
        const timerInterval = Swal.fire({ //se le asigna un nombre al swal
            allowOutsideClick: false,
            icon: "success",
            iconHtml: `<i class="fas fa-file-excel"></i>`,
            // iconHtml: `<i class="fas fa-tools"></i>`,
            title: '¡GENERANDO EXCEL!',
            html: `Generando reporte semanal de instrucciones diarias HSE.`,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
            },
        });
        $("#btn_descargar_excel").prop("disabled", true);
        var nomArchivo = `REPORTE SEMANAL DE INSPECCIONES DIARIAS HSE.xlsx`;
        var param = JSON.stringify({
            // fechaInicio: fechaInicio,
            // fechaFinal: fechaFin,
            fechaMes: $("#mes_reporte").val(),
            Departamento: $("#departamento").val(),
        });
        var pathservicehost = `${urls}recorridos-HSE/excel_todas_incidencias_fechas_depto`;
        var xhr = new XMLHttpRequest();
        xhr.open("POST", pathservicehost, true);
        xhr.responseType = "blob";
        //Send the proper header information along with the request
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function (e) {
            Swal.close(timerInterval);
            $("#btn_descargar_excel").prop("disabled", false);
            if (xhr.readyState === 4 && xhr.status === 200) {
                var contenidoEnBlob = xhr.response;
                var link = document.createElement("a");
                link.href = (window.URL || window.webkitURL).createObjectURL(
                    contenidoEnBlob
                );
                link.download = nomArchivo;
                var clicEvent = new MouseEvent("click", {
                    view: window,
                    bubbles: true,
                    cancelable: true,
                });
                //Simulamos un clic del usuario
                //no es necesario agregar el link al DOM.
                link.dispatchEvent(clicEvent);
                $("#equipos").empty();
                //link.click();
            } else {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "No es posible acceder al archivo, probablemente no existe. Comunicarse con el Administrador",
                });
            }
        };
        xhr.send("data=" + param);
    // }
});
