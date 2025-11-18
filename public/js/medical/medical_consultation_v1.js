/*
 * ARCHIVO MODULO SERVICIO MEDICO
 * AUTOR:Horus Samael Rivas Pedraza
 * EMAIL:horus.riv.ped@gmail.com
 * CEL:56 2439 2632
 */
const presentacion = ['ERROR', 'TABLETAS', 'AMPULAS', 'FRASCO', 'CAPSULAS', 'AMPOLLETAS', 'AMPOLLETA', 'GOTERO', 'TUBO', 'COMPRIMIDOS'];
const asignation = document.getElementById("asignacion");
var arrayProduct = [];
var contProduct = 0;
var user = 0;


/* $(document).ready(function () {
}); */

function ProductosList(item) {
    asignation.style.display = "none";
    $(`#product_${item}`).select2({
        placeholder: "Selecciona una Opción",
    });
    $(".js-example-basic-multiple").select2();
}

$("#form_consulta_medica").submit(function (e) {
    e.preventDefault();
    var error_nomina = "";
    var error_nombre = "";
    var error_genero = "";
    var error_edad = "";
    var error_escolaridad = "";
    var error_turno = "";
    var error_planta = "";
    if (user == 1) {
        error_datos = "";
        $("#error_datos").text(error_datos);
        if ($.trim($("#nomina").val()).length == 0) {
            error_nomina = "Campo Requerido";
            $("#nomina").addClass('has-error');
            $("#error_nomina").text(error_nomina);
        } else {
            $("#nomina").removeClass('has-error');
            $("#error_nomina").text(error_nomina);
        }
        if ($("#genero").val().length == 0) {
            error_genero = "Campo Requerido";
            $("#genero").addClass('has-error');
            $("#error_genero").text(error_genero);
        } else {
            $("#genero").removeClass('has-error');
            $("#error_genero").text(error_genero);
        }
        if ($("#edad").val().length == 0) {
            error_edad = "Campo Requerido";
            $("#edad").addClass('has-error');
            $("#error_edad").text(error_edad);
        } else {
            $("#edad").removeClass('has-error');
            $("#error_edad").text(error_edad);
        }
        if ($("#escolaridad").val().length == 0) {
            error_escolaridad = "Campo Requerido";
            $("#escolaridad").addClass('has-error');
            $("#error_escolaridad").text(error_escolaridad);
        } else {
            $("#escolaridad").removeClass('has-error');
            $("#error_escolaridad").text(error_escolaridad);
        }
        if ($("#id_user").val() == null || $("#id_user").val().length == 0) {
            error_turno = "Campo Requerido";
            $("#turno").addClass('has-error');
            $("#error_turno").text(error_turno);
        } else {
            if ($("#turno").val().length == 0) {
                error_turno = "Campo Requerido";
                $("#turno").addClass('has-error');
                $("#error_turno").text(error_turno);
            } else {
                $("#turno").removeClass('has-error');
                $("#error_turno").text(error_turno);
            }
        }
        if ($("#planta").val().length == 0) {
            error_planta = "Campo Requerido";
            $("#planta").addClass('has-error');
            $("#error_planta").text(error_planta);
        } else {
            $("#planta").removeClass('has-error');
            $("#error_planta").text(error_planta);
        }
    } else if (user == 2) {
        error_datos = "";
        $("#error_datos").text(error_datos);
        if ($.trim($("#nombre").val()).length == 0) {
            error_nombre = "Campo Requerido";
            $("#nombre").addClass('has-error');
            $("#error_nombre").text(error_nombre);
        } else {
            $("#nombre").removeClass('has-error');
            $("#error_nombre").text(error_nombre);
        }
        if ($("#genero").val().length == 0) {
            error_genero = "Campo Requerido";
            $("#genero").addClass('has-error');
            $("#error_genero").text(error_genero);
        } else {
            $("#genero").removeClass('has-error');
            $("#error_genero").text(error_genero);
        }
        if ($("#edad").val().length == 0) {
            error_edad = "Campo Requerido";
            $("#edad").addClass('has-error');
            $("#error_edad").text(error_edad);
        } else {
            $("#edad").removeClass('has-error');
            $("#error_edad").text(error_edad);
        }
        if ($("#escolaridad").val().length == 0) {
            error_escolaridad = "Campo Requerido";
            $("#escolaridad").addClass('has-error');
            $("#error_escolaridad").text(error_escolaridad);
        } else {
            $("#escolaridad").removeClass('has-error');
            $("#error_escolaridad").text(error_escolaridad);
        }
    } else {
        error_datos = "Campo Requerido";
        $("#error_datos").text(error_datos);
    }

    if ($("#tipo_atencion").val() == "") {
        error_tipo_atencion = "Campo Requerido";
        $("#tipo_atencion").addClass('has-error');
        $("#error_tipo_atencion").text(error_tipo_atencion);
    } else {
        error_tipo_atencion = "";
        $("#tipo_atencion").removeClass('has-error');
        $("#error_tipo_atencion").text(error_tipo_atencion);
    }
    if ($("#procedimientos").val() == "") {
        error_procedimientos = "Campo Requerido";
        $("#procedimientos").addClass('has-error');
        $("#error_procedimientos").text(error_procedimientos);
    } else {
        error_procedimientos = "";
        $("#procedimientos").removeClass('has-error');
        $("#error_procedimientos").text(error_procedimientos);
    }
    if ($("#sistema").val() == "") {
        error_sistema = "Campo Requerido";
        $("#sistema").addClass('has-error');
        $("#error_sistema").text(error_sistema);
    } else {
        error_sistema = "";
        $("#sistema").removeClass('has-error');
        $("#error_sistema").text(error_sistema);
    }
    if ($("#clasificacion").val() == "") {
        error_clasificacion = "Campo Requerido";
        $("#clasificacion").addClass('has-error');
        $("#error_clasificacion").text(error_clasificacion);
    } else {
        error_clasificacion = "";
        $("#clasificacion").removeClass('has-error');
        $("#error_clasificacion").text(error_clasificacion);
    }
    if ($("#clasificacion").val() == 1 || $("#clasificacion").val() == 2) {
        if ($("#tipo_lesion").val() == "") {
            error_tipo_lesion = "Campo Requerido";
            $("#tipo_lesion").addClass('has-error');
            $("#error_tipo_lesion").text(error_tipo_lesion);
        } else {
            error_tipo_lesion = "";
            $("#tipo_lesion").removeClass('has-error');
            $("#error_tipo_lesion").text(error_tipo_lesion);
        }
        if ($("#anatomical_area").val() == "") {
            error_anatomical_area = "Campo Requerido";
            $("#anatomical_area").addClass('has-error');
            $("#error_anatomical_area").text(error_anatomical_area);
        } else {
            error_anatomical_area = "";
            $("#anatomical_area").removeClass('has-error');
            $("#error_anatomical_area").text(error_anatomical_area);
        }
    } else {
        error_tipo_lesion = "";
        error_anatomical_area = "";
    }

    /* if ($.trim($("#diagnostico").val()).length == 0) {
        error_diagnostico = "Campo Requerido";
        $("#diagnostico").addClass('has-error');
        $("#error_diagnostico").text(error_diagnostico);
    } else {
        error_diagnostico = "";
        $("#diagnostico").removeClass('has-error');
        $("#error_diagnostico").text(error_diagnostico);
    } */
    if ($.trim($("#observaciones").val()).length == 0) {
        error_observaciones = "Campo Requerido";
        $("#observaciones").addClass('has-error');
        $("#error_observaciones").text(error_observaciones);
    } else {
        error_observaciones = "";
        $("#observaciones").removeClass('has-error');
        $("#error_observaciones").text(error_observaciones);
    }
    if (arrayProduct.length != 0) {
        error_product_ = 0;
        error_cantidad_ = 0;
        arrayProduct.forEach(item => {
            if ($("#product_" + item).val() == "") {
                error_product_ = error_product_ + 1;
                $("#product_" + item).addClass('has-error');
                $("#error_product_" + item).text("Campo Requerido");
            } else {
                $("#product_" + item).removeClass('has-error');
                $("#error_product_" + item).text("");
            }
            if ($("#cantidad_" + item).val().length == 0) {
                error_cantidad_ = error_cantidad_ + 1;
                $("#cantidad_" + item).addClass('has-error');
                $("#error_cantidad_" + item).text("Campo Requerido");
            } else {
                $("#cantidad_" + item).removeClass('has-error');
                $("#error_cantidad_" + item).text("");
            }
        });
    } else {
        error_product_ = 0;
        error_cantidad_ = 0;
    }

    if (
        error_nomina != "" || error_nombre != "" || error_genero != "" ||
        error_edad != "" || error_escolaridad != "" || error_turno != "" ||
        error_planta != "" || error_datos != ""
        ||
        error_tipo_atencion != "" || error_tipo_atencion != "" || error_procedimientos != "" ||
        error_sistema != "" || error_clasificacion != "" || error_tipo_lesion != "" ||
        error_anatomical_area != ""
        ||
        /* error_diagnostico != "" || */ error_observaciones != "" || error_product_ != 0 || error_cantidad_ != 0
    ) {
        return false;
    }
    $("#btn_consulta_medica").prop('disabled', true);
    let timerInterval = Swal.fire({ //se le asigna un nombre al swal
        title: 'Generando Permiso!',
        html: 'Espere unos Segundos.',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });
    const datas = new FormData($("#form_consulta_medica")[0]);
    $.ajax({
        data: datas,
        url: `${urls}medico/generar_consulta`,
        type: "post",
        dataType: "json",
        processData: false,
        contentType: false,
        cache: false,
        success: function (response) {
            Swal.close(timerInterval);
            $("#btn_consulta_medica").prop('disabled', false);
            if (response == true) {
                $("#form_consulta_medica")[0].reset();
                $("#items_duplica").empty();
                $("#tipo_datos_").removeClass("active focus");
                $("#tipo_datos").removeClass("active focus");
                $(".btn-opcion").removeClass("active focus");
                $("#nomina").attr('readonly', true);
                $("#nombre").attr('readonly', true);
                $("#genero").attr('readonly', true);
                $("#edad").attr('readonly', true);
                $("#escolaridad").attr('readonly', true);
                $("#turno").attr('readonly', true);
                $("#planta").attr('readonly', true);
                user = 0;
                contProduct = 0;
                arrayProduct = [];
                Swal.fire({
                    icon: "success",
                    title: "!Exito¡",
                    text: "!Se ha Registrado la Consulta!",
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
        $("#btn_consulta_medica").prop('disabled', false);
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

function validar() {
    if (user == 1) {
        if ($.trim($("#nomina").val()).length > 0) {
            $("#nomina").removeClass('has-error');
            $("#error_nomina").text("");
        }
        if ($("#genero").val().length > 0) {
            $("#genero").removeClass('has-error');
            $("#error_genero").text("");
        }
        if ($("#edad").val().length > 0) {
            $("#edad").removeClass('has-error');
            $("#error_edad").text("");
        }
        if ($("#escolaridad").val().length > 0) {
            $("#escolaridad").removeClass('has-error');
            $("#error_escolaridad").text("");
        }
        if ($("#id_user").val().length > 0) {
            if ($("#turno").val().length > 0) {
                $("#turno").removeClass('has-error');
                $("#error_turno").text("");
            }
        }
        if ($("#planta").val().length > 0) {
            $("#planta").removeClass('has-error');
            $("#error_planta").text("");
        }
    }
    if (user == 2) {
        if ($.trim($("#nombre").val()).length > 0) {
            $("#nombre").removeClass('has-error');
            $("#error_nombre").text("");
        }
        if ($("#genero").val().length > 0) {
            $("#genero").removeClass('has-error');
            $("#error_genero").text("");
        }
        if ($("#edad").val().length > 0) {
            $("#edad").removeClass('has-error');
            $("#error_edad").text("");
        }
        if ($("#escolaridad").val().length > 0) {
            $("#escolaridad").removeClass('has-error');
            $("#error_escolaridad").text("");
        }
    }
    if ($("#tipo_atencion").val() != "") {
        $("#tipo_atencion").removeClass('has-error');
        $("#error_tipo_atencion").text("");
    }
    if ($("#procedimientos").val() != "") {
        $("#procedimientos").removeClass('has-error');
        $("#error_procedimientos").text("");
    }
    if ($("#sistema").val() != "") {
        $("#sistema").removeClass('has-error');
        $("#error_sistema").text("");
    }
    if ($("#clasificacion").val() == 1 || $("#clasificacion").val() == 2) {
        if ($("#tipo_lesion").val() != "") {
            $("#tipo_lesion").removeClass('has-error');
            $("#error_tipo_lesion").text("");
        }
        if ($("#anatomical_area").val() != "") {
            $("#anatomical_area").removeClass('has-error');
            $("#error_anatomical_area").text("");
        }
    }
    /* if ($.trim($("#diagnostico").val()).length > 0) {
        $("#diagnostico").removeClass('has-error');
        $("#error_diagnostico").text("");
    } */
    if ($.trim($("#observaciones").val()).length > 0) {
        $("#observaciones").removeClass('has-error');
        $("#error_observaciones").text("");
    }
}

function validarClon(item) {
    if ($("#product_" + item).val() != "") {
        $("#product_" + item).removeClass('has-error');
        $("#error_product_" + item).text("");
    }
    if ($("#cantidad_" + item).val().length >= 0) {
        $("#cantidad_" + item).removeClass('has-error');
        $("#error_cantidad_" + item).text("");
    }
}

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
    $("#genero").val("");
    $("#edad").val("");
    $("#supervisor").val("");
    $("#id_supervisor").val("");
    $("#antiguedad").val("");
    $("#antiguedad_general").val("");
    $("#escolaridad").val("");
    $("#turno").empty();
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
                $("#turno").append(`<option value="">Opciones...</option>`);
                resp.turn.forEach(key => {
                    $("#turno").append(`<option value="${key.turn}">${key.name_turn}</option>`);
                });
                $("#genero").val(resp.data.genero);
                $("#edad").val(resp.data.edad_usuario);
                $("#supervisor").val(resp.data.supervisor);
                $("#id_supervisor").val(resp.data.id_supervisor);
                _s = (resp.date.y != 1) ? `s` : ''; _s_ = (resp.date.m != 1) ? `s` : ''; s_ = (resp.date.d != 1) ? `s` : '';
                año = (resp.date.y != 0) ? `${resp.date.y} año${_s}` : '';
                mes = (resp.date.m != 0) ? `${resp.date.m} mese${_s_}` : '';
                dia = (resp.date.d != 0) ? `${resp.date.d} dia${s_}` : '';
                $("#antiguedad").val(`${año} ${mes} ${dia}`);
                if ((resp.date.y == '' && resp.date.m < 6) || (resp.date.y == '' && resp.date.m == 6 && resp.date.d == 0)) {
                    fecha_opcion = "0 - 6 meses";
                } else if ((resp.date.m > 6 && resp.date.y == "") || (resp.date.m == 6 && resp.date.d >= 1 && resp.date.y == "") || (resp.date.y >= 1 && resp.date.y < 3)) {
                    fecha_opcion = "6 - 24 meses";
                } else if (resp.date.y > 2) {
                    fecha_opcion = "+ 24 meses";
                } else {
                    fecha_opcion = "error";
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

var formUser = $("#items_duplica").clone(true, true).html();
$("#btn_agregar_item").on("click", function (e) {
    e.preventDefault();
    $("#btn_agregar_item").prop('disabled', true);
    $("#btn_agregar_item").hide();
    $.ajax({
        async: false,
        url: `${urls}medico/datos_medicamento`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (dataProduct) {
            $("#btn_agregar_item").prop('disabled', false);
            if (dataProduct != false) {
                if (arrayProduct.length < 4) {
                    contProduct++;
                    arrayProduct.forEach(item => {
                        if (item === contProduct) {
                            contProduct++;
                        }
                    });
                    $("#items_duplica").append(`
                    <div class="row" id="items_clon_${contProduct}">
                        <div class="form-group col-md-1" style="text-align:center;">
                            <button type="button" class="btn btn-danger btn-retirar-item" style="margin-top:31px;" onclick="retirarItem(${contProduct}) ">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="form-group col-md-7">
                        <label>Agente Activo:</label>
                            <select name="product_[]" id="product_${contProduct}" class="form-control list-medicine select2-hidden-accessible" style="width: 100%; height: calc(2.25rem + 2px);" onchange="medicina(${contProduct})">
                                <option value="">Seleccionar Opción...</option>
                            </select>
                            <div id="error_product_${contProduct}" class="text-danger"></div>
                        </div>
                        <div class="form-group col-md-4">
                            <div class="row">
                                <div class="form-group col-md-8">
                                    <input type="hidden" name="cant_org_[]" id="cant_org_${contProduct}">
                                    <label>Cantidad</label>
                                    <input type="number" name="cantidad_[]" id="cantidad_${contProduct}" min="1" class="form-control" onchange="cantMedicina(${contProduct})" readonly>
                                    <div id="error_cantidad_${contProduct}" class="text-danger"></div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label id="pieza_${contProduct}" style="padding-top:36px;"></label>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    `)
                    arrayProduct.push(contProduct);
                    sessionStorage.setItem('arrayProduct', JSON.stringify(arrayProduct));
                    dataProduct.forEach(medic => {
                        $("#product_" + contProduct).append(`<option value="${medic.id_medicamento}">${medic.activo}</option>`);
                    });
                    ProductosList(contProduct);

                } else {
                    $("#error_item").html(
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
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador del Sistema",
                });
            }
        }
    });
    $("#btn_agregar_item").show();
});

function medicina(item) {
    if ($("#product_" + item).val().length > 0) {
        $("#product_" + item).removeClass('has-error');
        $("#error_product_" + item).text('');
    }
    $("#cant_org_" + item).val("");
    $("#pieza_" + item).empty();
    $("#cantidad_" + item).removeAttr('style', 'background-color:#ffff97;');
    $("#cantidad_" + item).val('');
    $("#cantidad_" + item).removeClass('has-error');
    $("#error_cantidad_" + item).text('');

    const id_medic = new FormData();
    id_medic.append('id_medicamento', $("#product_" + item).val());
    $.ajax({
        data: id_medic,
        async: false,
        url: `${urls}medico/datos_medicamento`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (dataProduct) {
            if (dataProduct != false) {
                $("#cant_org_" + item).val(dataProduct.piezas);
                $("#pieza_" + item).append(`${presentacion[dataProduct.id_presentation]}`);
                if (dataProduct.id_presentation == 3 || dataProduct.id_presentation == 7 || dataProduct.id_presentation == 8) {
                    $("#cantidad_" + item).attr("readonly", true);
                    $("#cantidad_" + item).val(0);
                } else {
                    $("#cantidad_" + item).attr("readonly", false);
                }
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador del Sistema",
                });
            }
        }
    });
}

function cantMedicina(item) {
    if ($("#cantidad_" + item).val().length > 0) {
        if (parseInt($("#cantidad_" + item).val()) > parseInt($("#cant_org_" + item).val())) {
            $("#cantidad_" + item).addClass('has-error');
            $("#error_cantidad_" + item).text(`Cantidad Disponible: ${$("#cant_org_" + item).val()}`);
        } else {
            $("#cantidad_" + item).removeClass('has-error');
            $("#error_cantidad_" + item).text('');
        }
    }
}

function retirarItem(item) {
    var i = arrayProduct.indexOf(item);
    arrayProduct.splice(i, 1);
    sessionStorage.setItem('arrayProduct', JSON.stringify(arrayProduct));
    $("#items_clon_" + item).remove();
    if (contProduct > 0) {
        contProduct = 0;
    }
}

$("#datos_interno").on("click", function (e) {
    if (this.checked) {
        $("#error_datos").text("");
        $("#nombre").removeClass('has-error');
        $("#error_nombre").text("");
        $("#nomina").removeClass('has-error');
        $("#error_nomina").text("");
        $("#genero").removeClass('has-error');
        $("#error_genero").text("");
        $("#edad").removeClass('has-error');
        $("#error_edad").text("");
        $("#escolaridad").removeClass('has-error');
        $("#error_escolaridad").text("");
        user = 1;
        $("#nomina").attr('readonly', false);
        $("#nomina").val("");
        $("#nombre").attr('readonly', true);
        $("#nombre").val("");
        $("#genero").attr('readonly', false);
        $("#genero").val("");
        $("#edad").attr('readonly', false);
        $("#edad").val("");
        $("#escolaridad").attr('readonly', false);
        $("#escolaridad").val("");
        $("#turno").attr('readonly', false);
        $("#turno").val("");
        $("#planta").attr('disabled', false);
        $("#planta").val("");
        $("#clasificacion").val("");
        $("#div_tipo_lesion").hide();
        $("#div_anatomical_area").hide();
        $("#tipo_lesion").val("");
        $("#anatomical_area").val("");
        $("#tipo_lesion").removeClass('has-error');
        $("#anatomical_area").removeClass('has-error');
    }
});

$("#datos_externo").on("click", function (e) {
    if (this.checked) {
        user = 2;
        $("#id_user").val("");
        $("#error_datos").text("");
        $("#nomina").removeClass('has-error');
        $("#error_nomina").text("");
        $("#genero").removeClass('has-error');
        $("#error_genero").text("");
        $("#edad").removeClass('has-error');
        $("#error_edad").text("");
        $("#escolaridad").removeClass('has-error');
        $("#error_escolaridad").text("");
        $("#turno").removeClass('has-error');
        $("#error_turno").text("");
        $("#planta").removeClass('has-error');
        $("#error_planta").text("");
        $("#nomina").attr('readonly', true);
        $("#nomina").val("");
        $("#nombre").attr('readonly', false);
        $("#nombre").val("");
        $("#genero").attr('readonly', false);
        $("#genero").val("");
        $("#id_depto").val("");
        $("#depto").val("");
        $("#puesto").val("");
        $("#edad").attr('readonly', false);
        $("#edad").val("");
        $("#supervisor").val("");
        $("#antiguedad").val("");
        $("#antiguedad_general").val("");
        $("#escolaridad").attr('readonly', false);
        $("#escolaridad").val("");
        $("#turno").attr('readonly', true);
        $("#turno").val("");
        $("#turno").empty();
        $("#planta").attr('disabled', true);
        $("#planta").val("");
        $("#clasificacion").val(12);
        $("#clasificacion").removeClass('has-error');
        $("#error_clasificacion").text("");
        $("#div_tipo_lesion").hide();
        $("#div_anatomical_area").hide();
        $("#tipo_lesion").val("");
        $("#anatomical_area").val("");
        $("#tipo_lesion").removeClass('has-error');
        $("#anatomical_area").removeClass('has-error');
    }
});

$("#clasificacion").on('change', function () {
    if ($("#clasificacion").val() != "") {
        $("#clasificacion").removeClass('has-error');
        $("#error_clasificacion").text("");
    }
    if ($("#clasificacion").val() == 1 || $("#clasificacion").val() == 2) {
        $("#div_tipo_lesion").show();
        $("#div_anatomical_area").show();
    } else {
        $("#div_tipo_lesion").hide();
        $("#div_anatomical_area").hide();
        $("#tipo_lesion").val("");
        $("#anatomical_area").val("");
        $("#tipo_lesion").removeClass('has-error');
        $("#anatomical_area").removeClass('has-error');
    }

});

$("#estres_laboral").on("click", function (e) {
    if (this.checked) {
        $("#motivo_comun").val(1);
    }
});

$("#estres_personal").on("click", function (e) {
    if (this.checked) {
        $("#motivo_comun").val(2);
    }
});

$("#egronomia").on("click", function (e) {
    if (this.checked) {
        $("#motivo_comun").val(3);
    }
});

/* id="motivo_enfermedad_"
id="motivo_enfermedad"
id="_motivo_enfermedad" */