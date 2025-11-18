/*
 * ARCHIVO MODULO SERVICIO MEDICO
 * AUTOR:Horus Samael Rivas Pedraza
 * EMAIL:horus.riv.ped@gmail.com
 * CEL:56 2439 2632
 */
var arrayVisual = [1];
var arrayDX = [1];
var contVisual = 1;
var contDX = 1;

$("#form_examen_medico").submit(function (e) {
    e.preventDefault();
    var error_genero = "";
    var error_edad = "";
    var error_escolaridad = "";
    var error_estado_civil = "";

    if ($("#id_user").val().length == 0) {
        error_nomina = "Nomina Valida Requerida"
        $("#nomina").addClass('has-error');
        $("#error_nomina").text(error_nomina);
    } else {
        error_nomina = ""
        $("#nomina").removeClass('has-error');
        $("#error_nomina").text(error_nomina);

        if ($("#genero").val().length == 0) {
            error_genero = "Campo Requerido"
            $("#genero").addClass('has-error');
            $("#error_genero").text(error_genero);
        } else {
            $("#genero").removeClass('has-error');
            $("#error_genero").text(error_genero);
        }

        if ($("#edad").val().length == 0) {
            error_edad = "Campo Requerido"
            $("#edad").addClass('has-error');
            $("#error_edad").text(error_edad);
        } else {
            $("#edad").removeClass('has-error');
            $("#error_edad").text(error_edad);
        }

        if ($("#escolaridad").val().length == 0) {
            error_escolaridad = "Campo Requerido"
            $("#escolaridad").addClass('has-error');
            $("#error_escolaridad").text(error_escolaridad);
        } else {
            $("#escolaridad").removeClass('has-error');
            $("#error_escolaridad").text(error_escolaridad);
        }

        if ($("#estado_civil").val().length == 0) {
            error_estado_civil = "Campo Requerido"
            $("#estado_civil").addClass('has-error');
            $("#error_estado_civil").text(error_estado_civil);
        } else {
            $("#estado_civil").removeClass('has-error');
            $("#error_estado_civil").text(error_estado_civil);
        }
    }

    if ($("#grado_salud").val().length == 0) {
        error_grado_salud = "Campo Requerido"
        $("#grado_salud").addClass('has-error');
        $("#error_grado_salud").text(error_grado_salud);
    } else {
        error_grado_salud = ""
        $("#grado_salud").removeClass('has-error');
        $("#error_grado_salud").text(error_grado_salud);
    }

    /* if ($("#motivo_comun").val().length == 0) {
        error_motivo_comun = "Campo Requerido"
        $("#error_motivo_comun").text(error_motivo_comun);
    } else {
        $("#error_motivo_comun").text(error_motivo_comun);
    } */
    error_motivo_comun = ""

    if ($("#imc").val().length == 0) {
        error_imc = "Campo Requerido"
        $("#imc").addClass('has-error');
        $("#error_imc").text(error_imc);
    } else {
        error_imc = ""
        $("#imc").removeClass('has-error');
        $("#error_imc").text(error_imc);
    }

    if ($("#has").val().length == 0) {
        error_has = "Campo Requerido"
        $("#has").addClass('has-error');
        $("#error_has").text(error_has);
    } else {
        error_has = ""
        $("#has").removeClass('has-error');
        $("#error_has").text(error_has);
    }

    if ($("#dm").val().length == 0) {
        error_dm = "Campo Requerido"
        $("#dm").addClass('has-error');
        $("#error_dm").text(error_dm);
    } else {
        error_dm = ""
        $("#dm").removeClass('has-error');
        $("#error_dm").text(error_dm);
    }
    var cont_error_visual = 0;
    arrayVisual.forEach(item => {
        if ($("#visual_" + item).val().length == 0) {
            cont_error_visual++;
            $("#visual_" + item).addClass('has-error');
            $("#error_visual_" + item).text("Campo Requerido");
        } else {
            $("#visual_" + item).removeClass('has-error');
            $("#error_visual_" + item).text("");
        }
    });

    var cont_error_dx = 0;
    arrayDX.forEach(item => {
        if ($("#dx_" + item).val().length == 0) {
            cont_error_dx++;
            $("#dx_" + item).addClass('has-error');
            $("#error_dx_" + item).text("Campo Requerido");
        } else {
            $("#dx_" + item).removeClass('has-error');
            $("#error_dx_" + item).text("");
        }

        if ($("#sistema_" + item).val().length == 0) {
            cont_error_dx++;
            $("#sistema_" + item).addClass('has-error');
            $("#error_sistema_" + item).text("Campo Requerido");
        } else {
            $("#sistema_" + item).removeClass('has-error');
            $("#error_sistema_" + item).text("");
        }
    });

    if (error_nomina != "" || error_genero != "" || error_edad != "" || error_escolaridad != "" || error_estado_civil != "" ||
        error_grado_salud != "" || error_motivo_comun != "" || error_imc != "" || error_has != "" || error_dm != "" ||
        cont_error_dx != 0 || cont_error_visual != 0
    ) {
        return false;
    }
    let timerInterval = Swal.fire({ //se le asigna un nombre al swal
        allowOutsideClick: false,
        title: '¡Generando Reporte!',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });
    $("#btn_examen_medico").prop('disabled', true);
    const data = new FormData($("#form_examen_medico")[0]);
    $.ajax({
        data: data,
        type: "POST",
        url: `${urls}medico/generar_examen`,
        dataType: "JSON",
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentType
        success: function (save) {
            $("#btn_examen_medico").prop('disabled', false);
            Swal.close(timerInterval);
            if (save == true) {
                $("#form_examen_medico")[0].reset();
                $("#id_user").val('');
                $(".radio").val(0);
                $("#motivo_comun").val('');
                $(".btn-opcion").removeClass("active focus");
                $(".extra").hide();
                $("#genero").attr('readonly', true);
                $("#edad").attr('readonly', true);
                $("#escolaridad").attr('readonly', true);
                $("#estado_civil").attr('readonly', true);
                $(".yes").attr('checked', false);
                $(".no").attr('checked', true);
                radioButton(1,0);
                radioButton(2,0);
                radioButton(3,0);
                radioButton(4,0);
                arrayVisual = [1];
                arrayDX = [1];
                contVisual = 1;
                contDX = 1;
                Swal.fire({
                    icon: "success",
                    title: "!Exito¡",
                    text: "!Se ha Registrado El Examen!",
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
        $("#btn_examen_medico").prop('disabled', false);
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
    return false;
})

function radioButton(i, val) {
    if (val == 1) {
        $("#lbl_yes_" + i).attr('style', 'color:#fff');
        $("#lbl_no_" + i).attr('style', 'color:rgba(0, 0, 0, 0.4)');
        $("#no_" + i).prop('checked', false);
        $("#dato_" + i).val(val);
    } else {
        $("#lbl_no_" + i).attr('style', 'color:#fff');
        $("#lbl_yes_" + i).attr('style', 'color:rgba(0, 0, 0, 0.4)');
        $("#yes_" + i).prop('checked', false);
        $("#dato_" + i).val(val);
    }
}

$("#nomina").on('change', function (e) {
    e.preventDefault();
    $("#nomina").removeClass('has-error');
    $("#error_nomina").text("");
    $("#id_user").val("");
    $("#depto").val("");
    $("#id_depto").val("");
    $("#puesto").val("");
    $("#nombre").val("");
    $("#tipo_empleado").val("");
    $("#genero").val("");
    $("#edad").val("");
    $("#escolaridad").val("");
    $("#estado_civil").val("");
    $("#antiguedad_general").val("");
    $("#tipo_empleado").val("");
    $("#genero").attr('readonly', true);
    $("#edad").attr('readonly', true);
    $("#escolaridad").attr('readonly', true);
    $("#estado_civil").attr('readonly', true);
    if ($.trim($("#nomina").val()).length == 0) {
        return false;
    }
    let num = new FormData();
    num.append('ID', $("#nomina").val());
    $.ajax({
        data: num,
        type: "POST",
        url: `${urls}medico/datos_usuario`,
        dataType: "JSON",
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentType
        success: function (resp) {
            if (resp != false) {
                $("#nombre").val(resp.data.nombre);
                $("#depto").val(resp.data.departamento);
                $("#puesto").val(resp.data.puesto);
                $("#id_user").val(resp.data.id_user);
                $("#id_depto").val(resp.data.id_departament);
                $("#escolaridad").val(resp.data.escolaridad);
                $("#genero").val(resp.data.genero);
                $("#edad").val(resp.data.edad_usuario);
                $("#tipo_empleado").val(resp.data.tipo);
                $("#estado_civil").val(resp.data.estado_civil);
                if (resp.date.y == '') {
                    fecha_opcion = "MENOS DE 1 AÑO";
                } else if (resp.date.y >= 1 && resp.date.y <= 3) {
                    fecha_opcion = "1 - 3 AÑOS";
                } else if (resp.date.y >= 4 && resp.date.y <= 6) {
                    fecha_opcion = "4 - 6 AÑOS";
                } else if (resp.date.y >= 7 && resp.date.y <= 9) {
                    fecha_opcion = "7 - 9 AÑOS";
                } else if (resp.date.y >= 10 && resp.date.y <= 12) {
                    fecha_opcion = "10 - 12 AÑOS";
                } else if (resp.date.y >= 13 && resp.date.y <= 15) {
                    fecha_opcion = "13 - 15 AÑOS";
                } else if (resp.date.y < 15) {
                    fecha_opcion = "MÁS DE 15 AÑOS";
                } else {
                    fecha_opcion = "ERROR";
                }

                $("#antiguedad_general").val(fecha_opcion);

                if (resp.data.genero != "" && resp.data.genero != null) {
                    $("#genero").removeClass('has-error');
                    $("#error_genero").text("");
                }
                if (resp.data.edad_usuario > 0) {
                    $("#edad").removeClass('has-error');
                    $("#error_edad").text("");
                }
                if (resp.data.escolaridad != "" && resp.data.escolaridad != null) {
                    $("#escolaridad").removeClass('has-error');
                    $("#error_escolaridad").text("");
                }
                if (resp.data.estado_civil != "" && resp.data.estado_civil != null) {
                    $("#estado_civil").removeClass('has-error');
                    $("#error_estado_civil").text("");
                }
                $("#genero").attr('readonly', false);
                $("#edad").attr('readonly', false);
                $("#escolaridad").attr('readonly', false);
                $("#estado_civil").attr('readonly', false);
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

$("#btn_add_visual").on('click', function () {
    $("#btn_remove_visual").prop('disabled', false);
    $("#btn_remove_visual").attr('class', 'btn btn-outline-danger');
    if (contVisual < 4) {
        contVisual++;
        arrayVisual.push(contVisual);
        $("#div_visual_" + contVisual).show();

    } else {
        $("#div_error_visual").html(
            `<div class="alert alert-warning alert-dismissible" role="alert">
                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
                   </button>
                   <strong>EL SISTEMA SOLO PERMITE 4 MEDICAMENTOS ...</strong>
                   </div>
                   <span></span>`
        );
        setTimeout(function () {
            $(".alert")
                .fadeTo(1000, 0)
                .slideUp(800, function () {
                    $(this).remove();
                });
        }, 3000);
        return false;
    }
})

$("#btn_remove_visual").on('click', function () {
    var i = arrayVisual.indexOf(contVisual);
    arrayVisual.splice(i, 1);
    // sessionStorage.setItem('arrayVisual', JSON.stringify(arrayVisual));+
    $("#div_visual_" + contVisual).hide();
    $("#visual_" + contVisual).removeClass('has-error');
    $("#error_visual_" + contVisual).text('');
    contVisual--;
    if (contVisual == 1) {
        $("#btn_remove_visual").prop('disabled', true);
        $("#btn_remove_visual").attr('class', 'btn btn-secondary');
    }
});

$("#btn_add_dx").on('click', function () {
    $("#btn_remove_dx").prop('disabled', false);
    $("#btn_remove_dx").attr('class', 'btn btn-outline-danger');
    if (contDX < 4) {
        contDX++;
        arrayDX.push(contDX);
        $("#div_dx_" + contDX).show();

    } else {
        $("#div_error_dx").html(
            `<div class="alert alert-warning alert-dismissible" role="alert">
                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
                   </button>
                   <strong>EL SISTEMA SOLO PERMITE 4 MEDICAMENTOS ...</strong>
                   </div>
                   <span></span>`
        );
        setTimeout(function () {
            $(".alert")
                .fadeTo(1000, 0)
                .slideUp(800, function () {
                    $(this).remove();
                });
        }, 3000);
        return false;
    }
})

$("#btn_remove_dx").on('click', function () {
    var i = arrayDX.indexOf(contDX);
    arrayDX.splice(i, 1);
    // sessionStorage.setItem('arrayDX', JSON.stringify(arrayDX));
    $("#dx_" + contDX).removeClass('has-error');
    $("#error_dx_" + contDX).text('');
    $("#sistema_" + contDX).removeClass('has-error');
    $("#error_sistema_" + contDX).text('');
    $("#div_dx_" + contDX).hide();
    contDX--;
    if (contDX == 1) {
        $("#btn_remove_dx").prop('disabled', true);
        $("#btn_remove_dx").attr('class', 'btn btn-secondary');
    }
});


function validar() {
    if ($("#genero").val().length > 0) {
        $("#genero").removeClass('has-error');
        $("#error_genero").text('');
    }
    if ($("#edad").val().length > 0) {
        $("#edad").removeClass('has-error');
        $("#error_edad").text('');
    }
    if ($("#escolaridad").val().length > 0) {
        $("#escolaridad").removeClass('has-error');
        $("#error_escolaridad").text('');
    }
    if ($("#estado_civil").val().length > 0) {
        $("#estado_civil").removeClass('has-error');
        $("#error_estado_civil").text('');
    }

    if ($("#imc").val().length > 0) {
        $("#imc").removeClass('has-error');
        $("#error_imc").text('');
    }
    if ($("#has").val().length > 0) {
        $("#has").removeClass('has-error');
        $("#error_has").text('');
    }
    if ($("#dm").val().length > 0) {
        $("#dm").removeClass('has-error');
        $("#error_dm").text('');
    }

    if ($("#grado_salud").val().length > 0) {
        $("#grado_salud").removeClass('has-error');
        $("#error_grado_salud").text('');
    }
}

function validarVisual(item) {
    if ($("#visual_" + item).val().length > 0) {
        $("#visual_" + item).removeClass('has-error');
        $("#error_visual_" + item).text('');
    }
}

function validarDX(item) {
    if ($("#dx_" + item).val().length > 0) {
        $("#dx_" + item).removeClass('has-error');
        $("#error_dx_" + item).text('');
    }
    if ($("#sistema_" + item).val().length > 0) {
        $("#sistema_" + item).removeClass('has-error');
        $("#error_sistema_" + item).text('');
    }
}

function motivoComun(val) {
    $("#motivo_comun").val(val);
    $("#error_motivo_comun").text('');
}