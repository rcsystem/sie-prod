/**
 * ARCHIVO MODULO SERVICIO MEDICO
 * AUTOR:Horus Samael Rivas Pedraza
 * EMAIL:horus.riv.ped@gmail.com
 * CEL:56 2439 2632
 */

var inasistencia = "";
var salida = "";

$("#permiso_medico").submit(function (e) {
    e.preventDefault();
    if ($("#id_user").val().length == 0) {
        error_nomina = "Campo Requerido";
        $("#nomina").addClass('has-error');
        $("#error_nomina").text(error_nomina);
    } else {
        error_nomina = "";
    }
    if ($("#id_user").val().length > 0) {
        if ($("#turno").val().length == 0) {
            error_turno = "Campo Requerido";
            $("#turno").addClass('has-error');
            $("#error_turno").text(error_turno);
        } else {
            error_turno = "";
            $("#turno").removeClass('has-error');
            $("#error_turno").text(error_turno);
        }
    } else {
        error_turno = "no existe usuario";
    }
    if (salida == true) {
        if ($("#fecha_salida").val().length == 0) {
            error_fecha_salida = "Campo Requerido";
            $("#fecha_salida").addClass('has-error');
            $("#error_fecha_salida").text(error_fecha_salida);
        } else {
            error_fecha_salida = "";
            $("#fecha_salida").removeClass('has-error');
            $("#error_fecha_salida").text(error_fecha_salida);
        }

        if ($("#hora_salida").val().length == 0) {
            error_hora_salida = "Campo Requerido";
            $("#hora_salida").addClass('has-error');
            $("#error_hora_salida").text(error_hora_salida);
        } else {
            error_hora_salida = "";
            $("#hora_salida").removeClass('has-error');
            $("#error_hora_salida").text(error_hora_salida);
        }
    } else {
        error_fecha_salida = "";
        error_hora_salida = "";
    }

    if (inasistencia == true) {
        if ($("#fecha_inicio").val().length == 0) {
            error_fecha_inicio = "Campo Requerido";
            $("#fecha_inicio").addClass('has-error');
            $("#error_fecha_inicio").text(error_fecha_inicio);
        } else {
            error_fecha_inicio = "";
            $("#fecha_inicio").removeClass('has-error');
            $("#error_fecha_inicio").text(error_fecha_inicio);
        }

        if ($("#fecha_fin").val().length == 0) {
            error_fecha_fin = "Campo Requerido";
            $("#fecha_fin").addClass('has-error');
            $("#error_fecha_fin").text(error_fecha_fin);
        } else if ($("#fecha_fin").val() < $("#fecha_inicio").val()) {
            error_fecha_fin = "Fecha Incorrecta";
            $("#fecha_fin").addClass('has-error');
            $("#error_fecha_fin").text(error_fecha_fin);
        } else {
            error_fecha_fin = "";
            $("#fecha_fin").removeClass('has-error');
            $("#error_fecha_fin").text(error_fecha_fin);
        }
    } else {
        error_fecha_inicio = "";
        error_fecha_fin = "";
    }

    if ($("#motivo").val().length == 0) {
        error_motivo = "Campo Requerido";
        $("#motivo").addClass('has-error');
        $("#error_motivo").text(error_motivo);
    } else {
        error_motivo = "";
        $("#motivo").removeClass('has-error');
        $("#error_motivo").text(error_motivo);
    }
    if ($("#tipo_permiso").val().length == 0) {
        error_tipo_permiso = "Campo Requerido";
        $("#tipo_permiso").addClass('has-error');
        $("#error_tipo_permiso").text(error_tipo_permiso);
    } else {
        error_tipo_permiso = "";
        $("#tipo_permiso").removeClass('has-error');
        $("#error_tipo_permiso").text(error_tipo_permiso);
    }

    if ($("#sueldo_si").val() == 0 && $("#sueldo_no").val() == 0) {
        error_sueldo = "Seleccione una opcion";
        $("#error_sueldo").text(error_sueldo);
        $("#sueldo_si").addClass('has-error');
        $("#sueldo_no").addClass('has-error');
    } else {
        error_sueldo = "";
        $("#error_sueldo").text(error_sueldo);
        $("#sueldo_si").removeClass('has-error');
        $("#sueldo_no").removeClass('has-error');
    }

    if ($("#sistemas").val().length == 0) {
        error_sistemas = "Campo Requerido";
        $("#sistemas").addClass('has-error');
        $("#error_sistemas").text(error_sistemas);
    } else {
        error_sistemas = "";
        $("#sistemas").removeClass('has-error');
        $("#error_sistemas").text(error_sistemas);
    }
    if ($("#sistemas").val() == "OTRO" || $("#sistemas").val() == "otro") {
        console.log($.trim($("#otro_sistema").val()).length);
        if ($.trim($("#otro_sistema").val()).length == 0) {
            error_otro_sistema = "Campo Requerido";
            $("#otro_sistema").addClass('has-error');
            $("#error_otro_sistema").text(error_otro_sistema);
        } else {
            error_otro_sistema = "";
            $("#otro_sistema").removeClass('has-error');
            $("#error_otro_sistema").text(error_otro_sistema);
        }
    } else {
        error_otro_sistema = "";
    }

    if ($.trim($("#observaciones").val()).length == 0) {
        error_observaciones = "Campo Requerido";
        $("#observaciones").addClass('has-error');
        $("#error_observaciones").text(error_observaciones);
    } else {
        error_observaciones = "";
        $("#observaciones").removeClass('has-error');
        $("#error_observaciones").text(error_observaciones);
    }
    if ($.trim($("#diagnostico").val()).length == 0) {
        error_diagnostico = "Campo Requerido";
        $("#diagnostico").addClass('has-error');
        $("#error_diagnostico").text(error_diagnostico);
    } else {
        error_diagnostico = "";
        $("#diagnostico").removeClass('has-error');
        $("#error_diagnostico").text(error_diagnostico);
    }

    if (error_sistemas != "" ||
        error_otro_sistema != "" ||
        error_observaciones != "" ||
        error_diagnostico != "" ||
        error_tipo_permiso != "" ||
        error_fecha_salida != "" ||
        error_hora_salida != "" ||
        error_fecha_inicio != "" ||
        error_fecha_fin != "" ||
        error_nomina != "" ||
        error_motivo != "" ||
        error_sueldo != "" ||
        error_turno != "") {
        return false;
    }
    let timerInterval = Swal.fire({ //se le asigna un nombre al swal
        title: 'Guardando!',
        html: 'Espere unos Segundos.',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });
    $("#btn_permiso_medico").prop('disabled', true);
    const data = new FormData($("#permiso_medico")[0]);
    let goce_sueldo = $("#sueldo_si").val() == 1 ? "SI" : "NO";
    data.append('sueldo', goce_sueldo);
    $.ajax({
        data: data,
        type: "POST",
        url: `${urls}medico/generar`,
        dataType: "JSON",
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentType
        success: function (save) {
            $("#btn_permiso_medico").prop('disabled', false);
            Swal.close(timerInterval); // cierra el swal en ejecucion (nombre del swal)
            if (save == true) {
                $("#permiso_medico")[0].reset();
                $("#id_user").val("");
                inasistencia = false;
                salida = false;
                $("#div_otro_sistema").empty();
                $("#div_inasistencia").empty();
                $("#div_salida").empty();
                $("#sueldo_si").val("");
                $("#sueldo_no").val("");
                $("#goce_sueldo_si").removeClass("active focus");
                $("#goce_sueldo_no").removeClass("active focus");
                Swal.fire({
                    icon: "success",
                    title: "¡EXITO!",
                    text: "Se ha generado corectamente el registro.",
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
            }
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        $("#btn_permiso_medico").prop('disabled', false);
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
            alert('Uncaught Error: ' + jqXHR.responseText);
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: `Uncaught Error: ${jqXHR.responseText}`,
            });
        }
    });
})

function validar() {
    if ($("#turno").val().length > 0) {
        $("#turno").removeClass('has-error');
        $("#error_turno").text("");
    }
    if ($("#motivo").val().length > 0) {
        $("#motivo").removeClass('has-error');
        $("#error_motivo").text("");
    }
    if ($("#tipo_permiso").val().length > 0) {
        $("#tipo_permiso").removeClass('has-error');
        $("#error_tipo_permiso").text("");
    }
    if (salida == true) {
        if ($("#fecha_salida").val().length > 0) {
            $("#fecha_salida").removeClass('has-error');
            $("#error_fecha_salida").text("");
        }
        if ($("#hora_salida").val().length > 0) {
            $("#hora_salida").removeClass('has-error');
            $("#error_hora_salida").text("");
        }
    }
    if (inasistencia == true) {
        if ($("#fecha_inicio").val().length > 0) {
            $("#fecha_inicio").removeClass('has-error');
            $("#error_fecha_inicio").text("");
        }
        if ($("#fecha_fin").val().length > 0) {
            $("#fecha_fin").removeClass('has-error');
            $("#error_fecha_fin").text("");
        }
    }
    if ($("#sistemas").val().length > 0) {
        $("#sistemas").removeClass('has-error');
        $("#error_sistemas").text("");
    }
    if ($("#sistemas").val() == "OTRO" || $("#sistemas").val() == "otro") {
        if ($.trim($("#otro_sistema").val()).length > 0) {
            $("#otro_sistema").removeClass('has-error');
            $("#error_otro_sistema").text("");
        }
    }
    if ($.trim($("#observaciones").val()).length > 0) {
        $("#observaciones").removeClass('has-error');
        $("#error_observaciones").text("");
    }
    if ($.trim($("#diagnostico").val()).length > 0) {
        $("#diagnostico").removeClass('has-error');
        $("#error_diagnostico").text("");
    }
}

$("#sistemas").on('change', function () {
    $("#sistemas").removeClass('has-error');
    $("#error_sistemas").text("");
    $("#div_otro_sistema").empty();
    if ($("#sistemas").val() == "OTRO" || $("#sistemas").val() == "otro") {
        $("#div_otro_sistema").append(`
        <label for="otro_sitema">Definir:</label>
        <input type="text" name="otro_sistema" id="otro_sistema" class="form-control" onchange="validar()">
        <div class="text-danger" id="error_otro_sistema"></div>
        </div>`);
    }
});

$("#nomina").on('change', function (e) {
    e.preventDefault();
    $("#nomina").removeClass('has-error');
    $("#error_nomina").text("");
    $("#nombre").val("");
    $("#depto").val("");
    $("#puesto").val("");
    $("#id_user").val("");
    $("#tipo_empleado").val("");
    $("#c_costos").val("");
    $("#id_depto").val("");
    $("#turno").empty();
    let num = new FormData();
    num.append('ID', $("#nomina").val());
    $.ajax({
        data: num,
        type: "POST",
        url: `${urls}sistemas/datos_usuario`,
        dataType: "JSON",
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentType
        success: function (data) {
            if (data != false) {
                // console.log(data);
                $("#nombre").val(`${data.nombre} ${data.apep} ${data.apem}`);
                $("#depto").val(data.departamento);
                $("#puesto").val(data.puesto);
                $("#id_user").val(data.id_user);
                $("#tipo_empleado").val(data.tipo);
                $("#c_costos").val(data.costos);
                $("#id_depto").val(data.id_departament);

                $("#turno").append(`<option value="">Opciones...</option>`);
                var id_user = new FormData();
                id_user.append('id_user',data.id_user);
                $.ajax({
                    data: id_user,
                    type: "POST",
                    url: `${urls}sistemas/datos_horarios`,
                    dataType: "JSON",
                    processData: false, // dile a jQuery que no procese los datos
                    contentType: false, // dile a jQuery que no establezca contentType
                    success: function (turns) {
                        if (turns != false) {
                            turns.forEach(key => {
                                $("#turno").append(`<option value="${key.id}">${key.name_turn}</option>`);
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Turnos no cargados.",
                            });
                        }
                    }
                });

            } else {
                $("#nomina").addClass('has-error');
                $("#error_nomina").text("Nomina no encontrada.");
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
            alert('Uncaught Error: ' + jqXHR.responseText);
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: `Uncaught Error: ${jqXHR.responseText}`,
            });
        }
    });
});

$("#tipo_permiso").on('change', function (e) {
    e.preventDefault();
    inasistencia = false;
    salida = false;
    $("#div_inasistencia").empty();
    $("#div_salida").empty();
    if ($("#tipo_permiso").val() == 1 || $("#tipo_permiso").val() == 2 || $("#tipo_permiso").val() == 6) {
        salida = true;
        $("#div_salida").append(`<div class="form-group col-md-6">
            <label for="fecha_salida">Fecha de Salida:</label>
            <input type="date" name="fecha_salida" id="fecha_salida" class="form-control" onchange="validar()">
            <div class="text-danger" id="error_fecha_salida"></div>
        </div>
        <div class="form-group col-md-6">
            <label for="hora_salida">Hora de Salida:</label>
            <input type="time" name="hora_salida" id="hora_salida" class="form-control" onchange="validar()">
            <div class="text-danger" id="error_hora_salida"></div>
        </div>`);
    }
    if ($("#tipo_permiso").val() == 2 || $("#tipo_permiso").val() == 3 || $("#tipo_permiso").val() == 4 || $("#tipo_permiso").val() == 5) {
        inasistencia = true;
        $("#div_inasistencia").append(`<div class="form-group col-md-6">
            <label for="fecha_inicio">Inasistencia del día:</label>
            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" onchange="validar()">
            <div class="text-danger" id="error_fecha_inicio"></div>
        </div>
        <div class="form-group col-md-6">
            <label for="fecha_fin">Al día:</label>
            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" onchange="validar()">
            <div class="text-danger" id="error_fecha_fin"></div>
        </div>`);
    }
});

$("#sueldo_si").on("click", function (e) {
    if (this.checked) {
        $("#error_sueldo").text("");
        $("#sueldo_si").val(1);
        $("#sueldo_no").val(0);
        this.checked = false;
    }
});

$("#sueldo_no").on("click", function (e) {
    if (this.checked) {
        $("#error_sueldo").text("");
        $("#sueldo_no").val(1);
        $("#sueldo_si").val(0);
        this.checked = false;
    }
});
