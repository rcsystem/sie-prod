/*
 * ARCHIVO MODULO TICKETS IT
 * AUTOR:Horus Samael Rivas Pedraza
 * EMAIL:horus.riv.ped@gmail.com
 * CEL:56 2439 2632
*/

$(document).ready(function () {
    $("#viaticos").empty();
    $("#gastos").empty();
    $.ajax({
        url: `${urls}viajes/mis_comprobaciones`,
        // async: false,
        type: "post",
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (data) {
            if (data != false) {
                if (data.viaticos.length > 0) {
                    data.viaticos.forEach(data => {
                        $("#viaticos").append(`<a class="my-a-card" href="${urls}viajes/ver_datos_folio/1/${$.md5(key + data.folio)}"><div onclick="openFolio(${data.folio},1);" class="card-style-personal w-100 p-2" style="cursor: pointer;">
                        <div class="row">
                            <div class="col-sm-8 row">
                                <div class="col-sm-6">
                                    <h6><b class="nav-icon">FOLIO:</b>${data.folio}</h6>
                                    <h6><b class="nav-icon">INICIO VIAJE:</b>${data.inicio}</h6>
                                </div>
                                <div class="col-sm-6">
                                    <h6><b class="nav-icon">MONTO:</b>$${data.monto}</h6>
                                    <h6><b class="nav-icon">FIN VIAJE:</b>${data.fin}</h6>
                                </div>
                            </div>
                            <div class="col-sm-4" style="padding-top:14px">
                                <label class="nav-icon">ESTADO</label>
                                <span class="badge badge-${data.color}">${data.txt}</span>
                            </div>
                        </div>
                    </div></a>`);
                    });

                } else {
                    $("#viaticos").append(`<div class="card-style-personal w-100 p-2" style="cursor: pointer;">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center;">
                                <H2>SIN REGISTROS DISPONIBLES</H2>
                            </div>
                        </div>
                    </div>`);
                }
                if (data.gastos.length > 0) {
                    data.gastos.forEach(data => {

                        $("#gastos").append(`<a class="my-a-card" href="${urls}viajes/ver_datos_folio/2/${$.md5(key + data.folio)}"><div class="card-style-personal w-100 p-2" style="cursor: pointer;">
                        <div class="row">
                            <div class="col-sm-8 row">
                                <div class="col-sm-6">
                                    <h6><b class="nav-icon">FOLIO:</b>${data.folio}</h6>
                                    <h6><b class="nav-icon">INICIO VIAJE:</b>${data.inicio}</h6>
                                </div>
                                <div class="col-sm-6">
                                    <h6><b class="nav-icon">MONTO:</b>$${data.monto}</h6>
                                    <h6><b class="nav-icon">FIN VIAJE:</b>${data.fin}</h6>
                                </div>
                            </div>
                            <div class="col-sm-4" style="padding-top:14px">
                                <label class="nav-icon">ESTADO</label>
                                <span class="badge badge-${data.color}">${data.txt}</span>
                            </div>
                        </div>
                    </div></a>`);
                    });

                } else {
                    $("#gastos").append(`<div class="card-style-personal w-100 p-2" style="cursor: pointer;">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center;">
                                <H2>SIN REGISTROS DISPONIBLES</H2>
                            </div>
                        </div>
                    </div>`);
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
    })
});