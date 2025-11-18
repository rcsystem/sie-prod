/**
 * ARCHIVO MODULO "MI INFORMACION"
 * AUTOR: Horus Samael Rivas Pedraza
 * EMAIL_ horus.riv.ped@gmail.com
 * CEL:55 2439 2632
 */
$(document).ready(function () {
    $.ajax({
        url: `${urls}usuarios/check_doc`,
        type: "POST",
        dataType: "json",
        success: async function (respFile) {
            PersonalData();
            Emergency();
            Family();
            if (respFile.acta == null || respFile.domicilio == null || respFile.estudios == null || respFile.rfc == null || respFile.curp == null) {
                $("#lbl_doc_estudios_m").empty()
                $("#lbl_doc_estudios_m").append(`Comprobante de ${respFile.lvl_estudio.escolaridad.toLowerCase()}:`);
                $("#archivosModal").modal("show");
            } else {
                Documents();
            }
        }
    });
});

function validarFileModal() {
    if ($("#doc_acta_m").val().length > 0) {
        $("#lbl_acta_m").empty();
        $("#lbl_acta_m").append(`${document.getElementById('doc_acta_m').files[0].name}`);
        $("#lbl_acta_m").attr('style', 'color:#000000;');
        $("#lbl_acta_m").removeClass('has-error');
    }
    if ($("#curp_m").val().length > 0) {
        $("#curp_m").removeClass('has-error');
    }
    if ($("#doc_curp_m").val().length > 0) {
        $("#lbl_curp_m").empty();
        $("#lbl_curp_m").append(`${document.getElementById('doc_curp_m').files[0].name}`);
        $("#lbl_curp_m").attr('style', 'color:#000000;');
        $("#lbl_curp_m").removeClass('has-error');
    }
    if ($("#rfc_m").val().length > 0) {
        $("#rfc_m").removeClass('has-error');
    }
    if ($("#doc_rfc_m").val().length > 0) {
        $("#lbl_rfc_m").empty();
        $("#lbl_rfc_m").append(`${document.getElementById('doc_rfc_m').files[0].name}`);
        $("#lbl_rfc_m").attr('style', 'color:#000000;');
        $("#lbl_rfc_m").removeClass('has-error');
    }
    if ($("#doc_domicilio_m").val().length > 0) {
        $("#lbl_domicilio_m").empty();
        $("#lbl_domicilio_m").append(`${document.getElementById('doc_domicilio_m').files[0].name}`);
        $("#lbl_domicilio_m").attr('style', 'color:#000000;');
        $("#lbl_domicilio_m").removeClass('has-error');
    }
    if ($("#doc_estudios_m").val().length > 0) {
        $("#lbl_estudios_m").empty();
        $("#lbl_estudios_m").append(`${document.getElementById('doc_estudios_m').files[0].name}`);
        $("#lbl_estudios_m").attr('style', 'color:#000000;');
        $("#lbl_estudios_m").removeClass('has-error');
    }
}

$("#form_archivos").submit(function (e) {
    e.preventDefault();
    mensaje_m = "<p>";

    if ($("#doc_acta_m").val().length == 0) {
        $("#lbl_acta_m").addClass('has-error');
        error_acta = "error";
    } else if ($("#doc_acta_m").val().split(".").pop() != "pdf") {
        error_acta = "Archivo Requerido";
        $("#lbl_acta_m").addClass('has-error');
    } else if (document.getElementById("doc_acta_m").files[0].size > 2000000) {
        error_acta = "2MB";
        peso_acta_m = document.getElementById("doc_acta_m").files[0].size / 1000000;
        mensaje_m = mensaje_m + `Peso Doc. Acta = ${peso_acta_m} MB <br> `;
        $("#lbl_acta_m").addClass('has-error');
    } else {
        $("#lbl_acta_m").removeClass('has-error');
        error_acta = "";
    }
    if ($("#doc_curp_m").val().length == 0) {
        $("#lbl_curp_m").addClass('has-error');
        error_doc_curp = "error";
    } else if ($("#doc_curp_m").val().split(".").pop() != "pdf") {
        error_doc_curp = "Archivo Requerido";
        $("#lbl_curp_m").addClass('has-error');
    } else if (document.getElementById("doc_curp_m").files[0].size > 2000000) {
        error_doc_curp = "2MB";
        peso_curp_m = document.getElementById("doc_curp_m").files[0].size / 1000000;
        mensaje_m = mensaje_m + `Peso Doc. CRUP = ${peso_curp_m} MB <br> `;
        $("#lbl_curp_m").addClass('has-error');
    } else {
        $("#lbl_curp_m").removeClass('has-error');
        error_doc_curp = "";
    }
    if ($("#curp_m").val().length == 0) {
        $("#curp_m").addClass('has-error');
        error_curp = "error";
    } else {
        $("#curp_m").removeClass('has-error');
        error_curp = "";
    }
    if ($("#doc_rfc_m").val().length == 0) {
        $("#lbl_rfc_m").addClass('has-error');
        error_doc_rfc = "error";
    } else if ($("#doc_rfc_m").val().split(".").pop() != "pdf") {
        error_doc_rfc = "Archivo Requerido";
        $("#lbl_rfc_m").addClass('has-error');
    } else if (document.getElementById("doc_rfc_m").files[0].size > 2000000) {
        error_doc_rfc = "2MB";
        peso_rfc_m = document.getElementById("doc_rfc_m").files[0].size / 1000000;
        mensaje_m = mensaje_m + `Peso Doc. RFC = ${peso_rfc_m} MB <br> `;
        $("#lbl_rfc_m").addClass('has-error');
    } else {
        $("#lbl_rfc_m").removeClass('has-error');
        error_doc_rfc = "";
    }
    if ($("#rfc_m").val().length == 0) {
        $("#rfc_m").addClass('has-error');
        error_rfc = "error";
    } else {
        $("#rfc_m").removeClass('has-error');
        error_rfc = "";
    }
    if ($("#doc_domicilio_m").val().length == 0) {
        $("#lbl_domicilio_m").addClass('has-error');
        error_domicilio = "error";
    } else if ($("#doc_domicilio_m").val().split(".").pop() != "pdf") {
        error_domicilio = "Archivo Requerido";
        $("#lbl_domicilio_m").addClass('has-error');
    } else if (document.getElementById("doc_domicilio_m").files[0].size > 2000000) {
        error_domicilio = "2MB";
        peso_domicilio_m = document.getElementById("doc_domicilio_m").files[0].size / 1000000;
        mensaje_m = mensaje_m + `Peso Doc. domicilio = ${peso_domicilio_m} MB <br> `;
        $("#lbl_domicilio_m").addClass('has-error');
    } else {
        $("#lbl_domicilio_m").removeClass('has-error');
        error_domicilio = "";
    }
    if ($("#doc_estudios_m").val().length == 0) {
        $("#lbl_estudios_m").addClass('has-error');
        error_estudios = "error";
    } else if ($("#doc_estudios_m").val().split(".").pop() != "pdf") {
        error_estudios = "Archivo Requerido";
        $("#lbl_estudios_m").addClass('has-error');
    } else if (document.getElementById("doc_estudios_m").files[0].size > 2000000) {
        error_estudios = "2MB";
        peso_estudios_m = document.getElementById("doc_estudios_m").files[0].size / 1000000;
        mensaje_m = mensaje_m + `Peso Doc. domicilio = ${peso_estudios_m} MB <br> `;
        $("#lbl_estudios_m").addClass('has-error');
    } else {
        $("#lbl_estudios_m").removeClass('has-error');
        error_estudios = "";
    }
    if (error_acta != "" || error_curp != "" || error_rfc != "" || error_doc_curp != "" || error_doc_rfc != "" || error_domicilio != "" || error_estudios != "") {
        if (error_acta == "2MB" || error_curp == "2MB" || error_rfc == "2MB" || error_doc_curp == "2MB" || error_doc_rfc == "2MB" || error_domicilio == "2MB" || error_estudios == "2MB") {
            mensaje_m = mensaje_m + "</p>";
            Swal.fire({
                icon: "error",
                title: "PESO DE ARCHIVOS",
                html: mensaje_m,
            });
            return false;
        }

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
    $("#btn_archivos").prop("disbled", true);
    let dataForm = new FormData($("#form_archivos")[0]);
    $.ajax({
        data: dataForm,
        type: "post",
        url: `${urls}usuarios/check_doc_save`,
        dataType: "json",
        cache: false,
        contentType: false,
        processData: false,
        success: function (save) {
            Swal.close(timerInterval);
            $("#btn_archivos").prop("disbled", false);
            if (save == true) {
                $("#archivosModal").modal("toggle");
                Swal.fire(
                    "!TUS DATOS SE HAN REGISTRADO CORRECTAMENTE... GRACIAS!",
                    "",
                    "success"
                ).then((result) => {
                    PersonalData();
                    Emergency();
                    Family();
                    Documents();
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador del Sistema",
                });
            }
        }
    });
})
/* async function swalDoc(lvl) {
    const steps = ['1', '2', '3', '4']
    const swalQueueStep = Swal.mixin({
        allowOutsideClick: false,
        confirmButtonText: 'Siguiente',
        progressSteps: steps,
        inputAttributes: {
            required: true,
            accept: "application/pdf",
        },
        reverseButtons: false,
        showDenyButton: false,
        validationMessage: 'Campo Requerido'
    })
    var doc_acta = "";
    var doc_domi = "";
    var doc_estu = "";
    let currentStep
    for (currentStep = 0; currentStep < steps.length;) {
        text_doc = "";
        input_opc = 'file';
        coment = false;
        icono = false;
        if (currentStep == 1) {
            text_doc = `Acta de Nacimiento`;
        } else if (currentStep == 2) {
            text_doc = `Comprobante de Domicilio`;
        } else if (currentStep == 3) {
            text_doc = `Comprobante de Estudios (${lvl})`;
        }
        else {
            icono = "warning";
            text_doc = `Documentación Requerida`;
            coment = "Sube tus documentación faltantes";
            input_opc = false;
        }
        const result = await swalQueueStep.fire({
            icon: icono,
            title: text_doc,
            text: coment,
            input: input_opc,
            showCancelButton: false,
            currentProgressStep: currentStep,

        });
        if (result.value) {
            if (currentStep == 1) {
                doc_acta = result.value;
            } else if (currentStep == 2) {
                doc_domi = result.value;
            } else if (currentStep == 3) {
                doc_estu = result.value;
            }
            currentStep++
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            currentStep--
        } else {
            break
        }
    }
    if (currentStep === steps.length) {
        let timerInterval = Swal.fire({ //se le asigna un nombre al swal
            title: 'Guardando!',
            html: 'Espere unos Segundos.',
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
            },
        });
        var formData = new FormData();
        formData.append('doc_acta', doc_acta);
        formData.append('doc_domi', doc_domi);
        formData.append('doc_estu', doc_estu);
        $.ajax({
            data: formData,
            method: "post",
            url: `${urls}usuarios/check_doc_save`,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            success: function (respSave) {
                Swal.close(timerInterval);
                if (respSave == true) {
                    Swal.fire({
                        icon: "success",
                        title: "Exito",
                        text: "¡Se Guardo Correctamente!",
                    });
                    PersonalData();
                    Emergency();
                    Family();
                    Documents();
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Algo salió Mal! Contactar con el Administrador del Sistema",
                    }).then((result) => {
                        swalDoc(lvl);
                    });
                }
            }
        });
    }
} */

/*-------- DATOS PERSONALES FUNCTIONS ------- */
var escolaridad_db = "";
var titulo_db = "";
var nueva_direccion = 0;
function PersonalData() {
    escolaridad_db = "";
    titulo_db = "";
    nueva_direccion = 0;
    $.ajax({
        url: `${urls}usuarios/datos_personal`,
        type: "post",
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentTypeasignacion
        dataType: "json",
        success: function (resp) {
            $("#edad").prop("disabled", true);
            $("#escolaridad").prop("disabled", true);
            $("#genero").prop("disabled", true);
            $("#fecha_nacimiento").prop("disabled", true);
            $("#edo_civil").prop("disabled", true);
            $("#nombre_cony").prop("disabled", true);
            $("#edad_cony").prop("disabled", true);
            $("#ocupacion_cony").prop("disabled", true);
            $("#cel_cony").prop("disabled", true);
            $("#calle").prop("disabled", true);
            $("#num_int").prop("disabled", true);
            $("#num_ext").prop("disabled", true);
            $("#cp").prop("disabled", true);
            $("#colonia").prop("disabled", true);
            $("#municipio").prop("disabled", true);
            $("#estado").prop("disabled", true);
            $("#doc_titulo_div").empty();
            $("#col_1").attr('class', 'form-group col-md-4');
            $("#col_2").attr('class', 'form-group col-md-4');
            $("#col_3").attr('class', 'form-group col-md-4');
            $("#col_4").empty();
            $("#col_4").attr('class', '');
            $("#nomina").val("");
            if (resp != false) {
                $("#id").val(resp.id_datos);
                $("#nombre").val(resp.nombre);
                $("#apep").val(resp.ape_paterno);
                $("#apem").val(resp.ape_materno);
                $("#edad").val(resp.edad_usuario);
                $("#escolaridad").val(resp.escolaridad);
                escolaridad_db = resp.escolaridad;
                $("#titulo_div").empty();
                if (resp.escolaridad == "LICENCIATURA" || resp.escolaridad == "INGENIERIA" || resp.escolaridad == "DOCTORADO" || resp.escolaridad == "MAESTRIA" || resp.escolaridad == "BACHILLERATO TéCNICO" || resp.escolaridad == "ESPECIALIDAD") {
                    $("#titulo_div").attr('class', 'form-group col-md-4');
                    $("#titulo_div").append(`
                     <label for="titulo">Titulo:</label>
                     <input type="text" class="form-control" id="titulo" name="titulo" value="${resp.lic_ing}" aria-describedby="inputGroupFileAddon01" onchange="valida()" disabled>
                     `);
                    titulo_db = resp.lic_ing;
                }
                $("#genero").val(resp.genero);
                $("#fecha_nacimiento").val(resp.fecha_nacimiento);
                $("#edo_civil").val(resp.estado_civil);
                $("#fecha_ingreso").val(resp.fecha_ingreso);
                $("#nombre_cony").val(resp.nombre_conyuge);
                if (resp.edad_conyuge == 0) {
                    $("#edad_cony").val("");
                } else {
                    $("#edad_cony").val(resp.edad_conyuge);
                }
                $("#ocupacion_cony").val(resp.ocupacion_conyuge);
                $("#cel_cony").val(resp.tel_conyuge);
                $("#calle").val(resp.calle);
                $("#num_int").val(resp.numero_interior);
                $("#num_ext").val(resp.numero_exterior);
                $("#cp").val(resp.codigo_postal);
                $("#colonia").val(resp.colonia);
                $("#municipio").val(resp.municipio);
                $("#estado").val(resp.estado);
                $("#btn_cancel_informacion_personal").hide();
                $("#btn_informacion_personal").hide();
                $("#btn_direccion").hide();
                $("#nota_info").hide();
                $("#btn_edit_informacion_personal").show();
            } else {
                $("#nota_info").hide();
                $("#btn_cancel_informacion_personal").hide();
                $("#btn_informacion_personal").hide();
                $("#btn_direccion").hide();
                $("#btn_edit_informacion_personal").hide();
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Tu Informacion no ha sido Encontada",
                });
            }
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: `Error: ​​[ ${jqXHR.status} ]`,
        });
    });
}

$("#btn_direccion").on("click", function (e) {
    e.preventDefault();
    nueva_direccion = 1;
    $("#col_1").attr('class', 'form-group col-md-3');
    $("#col_2").attr('class', 'form-group col-md-3');
    $("#col_3").attr('class', 'form-group col-md-3');
    $("#col_4").attr('class', 'form-group col-md-3');
    $("#calle").val("");
    $("#num_int").val("");
    $("#num_ext").val("");
    $("#cp").val("");
    $("#colonia").val("");
    $("#municipio").val("");
    $("#estado").val("");
    $("#col_4").empty("");
    $("#col_4").append(`
     <label for="doc_domicilio">Comprobante de Domicilio:</label>
      <div class="custom-file">
       <input type="file" class="custom-file-input" accept="application/pdf" id="doc_domicilio" name="doc_domicilio" aria-describedby="inputGroupFileAddon01" onchange="valida()">
       <label id="lbl_domicilio" class="custom-file-label" style="color:#BDBDBD;" for="doc_domicilio">Domicilio</label>
     </div>`);
})

function lvlEscolaridad() {
    if ($("#escolaridad").val() != escolaridad_db) {
        $("#titulo_div").empty();
        $("#titulo_div").attr('class', '');
        if ($("#escolaridad").val() == "LICENCIATURA" || $("#escolaridad").val() == "INGENIERIA" || $("#escolaridad").val() == "DOCTORADO" || $("#escolaridad").val() == "MAESTRIA" || $("#escolaridad").val() == "BACHILLERATO TéCNICO" || $("#escolaridad").val() == "ESPECIALIDAD") {
            $("#titulo_div").attr('class', 'form-group col-md-4');
            $("#titulo_div").append(`
             <label for="titulo">Titulo:</label>
             <input type="text" class="form-control" id="titulo" name="titulo" aria-describedby="inputGroupFileAddon01" onchange="valida()">
             `);
        }
        $("#doc_titulo_div").empty();
        $("#doc_titulo_div").append(`
         <label for="doc_estudios">Comprobante:</label>
         <div class="custom-file">
             <input type="file" class="custom-file-input" accept="application/pdf" id="doc_estudios" name="doc_estudios" aria-describedby="inputGroupFileAddon01" onchange="valida()">
             <label id="lbl_estudios" class="custom-file-label" style="color:#BDBDBD;" for="doc_estudios">Ultimo Grado de Estudios</label>
         </div>`);
    } else {
        $("#doc_titulo_div").empty();
        if ($("#escolaridad").val() == "LICENCIATURA" || $("#escolaridad").val() == "INGENIERIA" || $("#escolaridad").val() == "DOCTORADO" || $("#escolaridad").val() == "MAESTRIA" || $("#escolaridad").val() == "BACHILLERATO TéCNICO" || $("#escolaridad").val() == "ESPECIALIDAD") {
            $("#titulo_div").empty();
            $("#titulo_div").attr('class', 'form-group col-md-4');
            $("#titulo_div").append(`
             <label for="titulo">Titulo:</label>
             <input type="text" class="form-control" id="titulo" name="titulo" value="${titulo_db}" aria-describedby="inputGroupFileAddon01">
             `);
        }
    }
}

function valida() {
    if ($("#edad").val().length > 0) {
        $("#edad").removeClass('has-error');
    }

    if ($("#fecha_nacimiento").val().length > 0) {
        $("#fecha_nacimiento").removeClass('has-error');
    }

    if ($("#nombre_cony").val().length > 0) {
        if ($("#edad_cony").val().length > 0) {
            $("#edad_cony").removeClass('has-error');
        }
        if ($("#ocupacion_cony").val().length > 0) {
            $("#ocupacion_cony").removeClass('has-error');
        }
        if ($("#cel_cony").val().length > 0) {
            $("#cel_cony").removeClass('has-error');
        }
    }
    if ($("#escolaridad").val() != escolaridad_db) {
        if ($("#doc_estudios").val().length > 0) {
            $("#lbl_estudios").empty();
            $("#lbl_estudios").attr('style', 'color:#0D0D0D;');
            $("#lbl_estudios").append(`${document.getElementById('doc_estudios').files[0].name}`);
            $("#lbl_estudios").removeClass('has-error');
        }
    }
    if (nueva_direccion == 1) {
        if ($("#doc_domicilio").val().length > 0) {
            $("#lbl_domicilio").empty();
            $("#lbl_domicilio").attr('style', 'color:#0D0D0D;');
            $("#lbl_domicilio").append(`${document.getElementById('doc_domicilio').files[0].name}`);
            $("#lbl_domicilio").removeClass('has-error');
        }
    }
    if ($("#escolaridad").val() == "LICENCIATURA" || $("#escolaridad").val() == "INGENIERIA" || $("#escolaridad").val() == "DOCTORADO" || $("#escolaridad").val() == "MAESTRIA" || $("#escolaridad").val() == "BACHILLERATO TéCNICO" || $("#escolaridad").val() == "ESPECIALIDAD") {
        if ($("#titulo").val().length > 0) {
            $("#titulo").removeClass('has-error');
        }
    }

    if ($("#calle").val().length > 0) {
        $("#calle").removeClass('has-error');
    }

    if ($("#num_int").val().length > 0) {
        $("#num_int").removeClass('has-error');
    }

    if ($("#num_ext").val().length > 0) {
        $("#num_ext").removeClass('has-error');
    }

    if ($("#cp").val().length > 0) {
        $("#cp").removeClass('has-error');
    }

    if ($("#colonia").val().length > 0) {
        $("#colonia").removeClass('has-error');
    }

    if ($("#municipio").val().length > 0) {
        $("#municipio").removeClass('has-error');
    }

    if ($("#estado").val().length > 0) {
        $("#estado").removeClass('has-error');
    }
}

$("#btn_edit_informacion_personal").on("click", function (e) {
    e.preventDefault();
    $("#btn_edit_informacion_personal").hide();
    $("#edad").prop("disabled", false);
    $("#escolaridad").prop("disabled", false);
    if ($("#escolaridad").val() == "LICENCIATURA" || $("#escolaridad").val() == "INGENIERIA" || $("#escolaridad").val() == "DOCTORADO" || $("#escolaridad").val() == "MAESTRIA" || $("#escolaridad").val() == "BACHILLERATO TéCNICO" || $("#escolaridad").val() == "ESPECIALIDAD") {
        $("#titulo").prop("disabled", false);
    }
    $("#genero").prop("disabled", false);
    $("#fecha_nacimiento").prop("disabled", false);
    $("#edo_civil").prop("disabled", false);
    $("#nombre_cony").prop("disabled", false);
    $("#edad_cony").prop("disabled", false);
    $("#ocupacion_cony").prop("disabled", false);
    $("#cel_cony").prop("disabled", false);
    $("#calle").prop("disabled", false);
    $("#num_int").prop("disabled", false);
    $("#num_ext").prop("disabled", false);
    $("#cp").prop("disabled", false);
    $("#colonia").prop("disabled", false);
    $("#municipio").prop("disabled", false);
    $("#estado").prop("disabled", false);
    $("#btn_informacion_personal").show();
    $("#btn_cancel_informacion_personal").show();
    $("#btn_direccion").show();
    $("#nota_info").show();
});

$("#btn_cancel_informacion_personal").on("click", function (e) {
    e.preventDefault();
    $(".has-error").removeClass('has-error');
    PersonalData();
});

$("#informacion_personal").submit(function (e) {
    e.preventDefault();

    if ($.trim($("#edad").val()).length == 0) {
        var error_edad = "Campo Requerido";
        $("#edad").addClass('has-error');
    } else {
        error_edad = "";
        $("#edad").removeClass('has-error');
    }

    if ($("#fecha_nacimiento").val().length == 0) {
        var error_fecha_nacimiento = "Campo Requerido";
        $("#fecha_nacimiento").addClass('has-error');
    } else {
        error_fecha_nacimiento = "";
        $("#fecha_nacimiento").removeClass('has-error');
    }
    var error_doc_estudios = "";
    var error_titulo = "";
    if ($("#escolaridad").val() != escolaridad_db) {
        if ($("#doc_estudios").val().length == 0) {
            error_doc_estudios = "error";
            $("#lbl_estudios").addClass('has-error');
        } if ($("#doc_estudios").val().split(".").pop() != "pdf") {
            error_doc_estudios = "Archivo pdf Requerido";
            $("#lbl_estudios").addClass('has-error');
        } else {
            $("#lbl_estudios").removeClass('has-error');
        }
    }
    if ($("#escolaridad").val() == "LICENCIATURA" || $("#escolaridad").val() == "INGENIERIA" || $("#escolaridad").val() == "DOCTORADO" || $("#escolaridad").val() == "MAESTRIA" || $("#escolaridad").val() == "BACHILLERATO TéCNICO" || $("#escolaridad").val() == "ESPECIALIDAD") {
        if ($.trim($("#titulo").val()).length == 0) {
            error_titulo = "error";
            $("#titulo").addClass('has-error');
        } else {
            $("#titulo").removeClass('has-error');
        }
    }

    if ($("#edo_civil").val() == "UNION LIBRE" || $("#edo_civil").val() == "CASADO") {
        if ($("#nombre_cony").val().length == 0) {
            error_nombre_cony = "Campo Requerido";
            $("#nombre_cony").addClass('has-error');
        } else {
            error_nombre_cony = "";
            $("#nombre_cony").removeClass('has-error');
        }
        if ($("#edad_cony").val().length == 0) {
            error_edad_cony = "Campo Requerido";
            $("#edad_cony").addClass('has-error');
        } else {
            error_edad_cony = "";
            $("#edad_cony").removeClass('has-error');
        }
        if ($.trim($("#ocupacion_cony").val()).length == 0) {
            error_ocupacion_cony = "Campo Requerido";
            $("#ocupacion_cony").addClass('has-error');
        } else {
            error_ocupacion_cony = "";
            $("#ocupacion_cony").removeClass('has-error');
        }
        if ($.trim($("#cel_cony").val()).length < 10) {
            error_cel_cony = "Campo Requerido";
            $("#cel_cony").addClass('has-error');
        } else {
            error_cel_cony = "";
            $("#cel_cony").removeClass('has-error');
        }
    } else {
        $("#nombre_cony").removeClass('has-error');
        $("#edad_cony").removeClass('has-error');
        $("#ocupacion_cony").removeClass('has-error');
        $("#cel_cony").removeClass('has-error');
        error_edad_cony = "";
        error_ocupacion_cony = "";
        error_cel_cony = "";
        error_nombre_cony = "";
    }

    if ($.trim($("#calle").val()).length == 0) {
        var error_calle = "Campo Requerido";
        $("#calle").addClass('has-error');
    } else {
        error_calle = "";
        $("#calle").removeClass('has-error');
    }

    if ($.trim($("#num_int").val()).length == 0) {
        var error_num_int = "Campo Requerido";
        $("#num_int").addClass('has-error');
    } else {
        error_num_int = "";
        $("#num_int").removeClass('has-error');
    }

    if ($.trim($("#num_ext").val()).length == 0) {
        var error_num_ext = "Campo Requerido";
        $("#num_ext").addClass('has-error');
    } else {
        error_num_ext = "";
        $("#num_ext").removeClass('has-error');
    }

    if ($.trim($("#cp").val()).length == 0) {
        var error_cp = "Campo Requerido";
        $("#cp").addClass('has-error');
    } else {
        error_cp = "";
        $("#cp").removeClass('has-error');
    }

    if ($.trim($("#colonia").val()).length == 0) {
        var error_colonia = "Campo Requerido";
        $("#colonia").addClass('has-error');
    } else {
        error_colonia = "";
        $("#colonia").removeClass('has-error');
    }

    if ($.trim($("#municipio").val()).length == 0) {
        var error_municipio = "Campo Requerido";
        $("#municipio").addClass('has-error');
    } else {
        error_municipio = "";
        $("#municipio").removeClass('has-error');
    }

    if ($.trim($("#estado").val()).length == 0) {
        var error_estado = "Campo Requerido";
        $("#estado").addClass('has-error');
    } else {
        error_estado = "";
        $("#estado").removeClass('has-error');
    }
    var error_doc_domicilio = "";
    if (nueva_direccion == 1) {
        if ($("#doc_domicilio").val().length == 0) {
            error_doc_domicilio = "error";
            $("#lbl_domicilio").addClass('has-error');
        }
        if ($("#doc_domicilio").val().split(".").pop() != "pdf") {
            error_doc_domicilio = "Archivo pdf Requerido";
            $("#lbl_domicilio").addClass('has-error');
        } else {
            error_doc_domicilio = "";
            $("#lbl_domicilio").removeClass('has-error');
        }
    }

    if (error_edad != "" || error_fecha_nacimiento != "" || error_edad_cony != "" || error_ocupacion_cony != "" || error_cel_cony != ""
        || error_calle != "" || error_nombre_cony != "" || error_num_int != "" || error_num_ext != "" || error_cp != "" || error_colonia != ""
        || error_municipio != "" || error_estado != "" || error_doc_estudios != "" || error_titulo != "" || error_doc_domicilio != "") {
        return false;
    }
    let timerInterval = Swal.fire({ //se le asigna un nombre al swal
        allowOutsideClick: false,
        title: '¡Actualizando Datos!',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });
    $("#btm_informacion_personal").prop("disabled", true);
    let data = new FormData($('#informacion_personal')[0]);
    $.ajax({
        data: data,
        url: `${urls}usuarios/datos_personal_guardar`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (save) {
            Swal.close(timerInterval);
            $("#btm_informacion_personal").prop("disabled", false);
            if (save == true) {
                $('#informacion_personal')[0].reset();
                setTimeout(function () {
                    PersonalData();
                }, 100);
                Swal.fire("!Los datos se han Actualizado!", "", "success");
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador del Sistema",
                });
            }
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: `Error: ​​[ ${jqXHR.status} ]`,
        });
        $("#btm_informacion_personal").prop("disabled", false);
    });
});

/* ------ CONTACTO DE EMERGENCIA FUNTIONS ------  */
var contContac = 0;
function Emergency() {
    $.ajax({
        url: `${urls}usuarios/contacto_emergancia`,
        type: "post",
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentTypeasignacion
        dataType: "json",
        success: function (resp) {
            $("#contac_nombre_1").prop("disabled", true);
            $("#contac_pariente_1").prop("disabled", true);
            $("#contac_tel_1").prop("disabled", true);
            $("#contac_nombre_2").prop("disabled", true);
            $("#contac_pariente_2").prop("disabled", true);
            $("#contac_tel_2").prop("disabled", true);
            if (resp) {
                contContac = 1;
                $("#btn_contacto_emergencia").hide();
                $("#btn_cancel_contacto_emergencia").hide();
                resp.forEach(element => {
                    $("#id_" + contContac).val(element.id_emergencia);
                    $("#contac_nombre_" + contContac).val(element.contacto_emergencia);
                    $("#contac_pariente_" + contContac).val(element.parentesco_emergencia);
                    $("#contac_tel_" + contContac).val(element.tel_emergencia);
                    contContac++;
                });
                $("#btn_edit_contacto_emergencia").show();
            } else {
                $("#btn_contacto_emergencia").hide();
                $("#btn_cancel_contacto_emergencia").hide();
                $("#btn_edit_contacto_emergencia").hide();
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "La Informacion de tus Contactos de Emergendia no ha sido Encontada",
                });
            }
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: `Error: ​​[ ${jqXHR.status} ]`,
        });
    });
}

function validaContac() {
    if ($("#contac_nombre_1").val().length > 0) {
        $("#contac_nombre_1").removeClass('has-error');
    }
    if ($("#contac_pariente_1").val().length > 0) {
        $("#contac_pariente_1").removeClass('has-error');
    }
    if ($("#contac_tel_1").val().length > 0) {
        $("#contac_tel_1").removeClass('has-error');
    }

    if ($("#contac_nombre_2").val().length > 0) {
        $("#contac_nombre_2").removeClass('has-error');
    }
    if ($("#contac_pariente_2").val().length > 0) {
        $("#contac_pariente_2").removeClass('has-error');
    }
    if ($("#contac_tel_2").val().length > 0) {
        $("#contac_tel_2").removeClass('has-error');
    }
}

$("#btn_edit_contacto_emergencia").on("click", function (e) {
    e.preventDefault();
    $("#btn_edit_contacto_emergencia").hide();
    for (var iContac = 1; iContac < contContac; iContac++) {
        $("#contac_nombre_" + iContac).prop("disabled", false);
        $("#contac_pariente_" + iContac).prop("disabled", false);
        $("#contac_tel_" + iContac).prop("disabled", false);
    }
    $("#btn_contacto_emergencia").show();
    $("#btn_cancel_contacto_emergencia").show();
});

$("#btn_cancel_contacto_emergencia").on("click", function (e) {
    e.preventDefault();
    $(".has-error").removeClass('has-error');
    Emergency();
})

$("#contacto_emergencia").submit(function (e) {
    e.preventDefault();
    for (var iContacSub = 1; iContacSub < contContac; iContacSub++) {
        if ($("#contac_nombre_" + iContacSub).val().length == 0) {
            var error_nombre = "error";
            $("#contac_nombre_" + iContacSub).addClass('has-error');
        } else {
            error_nombre = "";
            $("#contac_nombre_" + iContacSub).removeClass('has-error');
        }
        if ($("#contac_pariente_" + iContacSub).val().length == 0) {
            var error_pariente = "error";
            $("#contac_pariente_" + iContacSub).addClass('has-error');
        } else {
            error_pariente = "";
            $("#contac_pariente_" + iContacSub).removeClass('has-error');
        }
        if ($("#contac_tel_" + iContacSub).val().length == 0) {
            var error_tel = "error";
            $("#contac_tel_" + iContacSub).addClass('has-error');
        } else {
            error_tel = "";
            $("#contac_tel_" + iContacSub).removeClass('has-error');
        }
    }
    if (error_nombre != "" || error_pariente != "" || error_tel != "") {
        return false;
    }
    let timerInterval = Swal.fire({ //se le asigna un nombre al swal
        allowOutsideClick: false,
        title: '¡Actualizando Datos!',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });
    $("#cantidad").val(iContacSub);
    $("#btn_contacto_emergencia").prop("disabled", true);
    let dataContac = new FormData($("#contacto_emergencia")[0]);
    $.ajax({
        data: dataContac,
        url: `${urls}usuarios/contacto_emergancia_guardar`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (save) {
            Swal.close(timerInterval);
            $("#btn_contacto_emergencia").prop("disabled", false);
            if (save == true) {
                $('#contacto_emergencia')[0].reset();
                setTimeout(function () {
                    Emergency();
                }, 100);
                Swal.fire("!Los datos se han Actualizado!", "", "success");
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador del Sistema",
                });
            }
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: `Error: ​​[ ${jqXHR.status} ]`,
        });
        $("#btn_contacto_emergencia").prop("disabled", false);
    });
});

/* ------ FAMILIA FUNTIONS ------  */
var arraySon = [];
var contSon = arraySon.length;

function Family() {
    $.ajax({
        url: `${urls}usuarios/familia`,
        type: "post",
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentTypeasignacion
        dataType: "json",
        success: function (resp) {
            $("#padres_nombre_1").prop("disabled", true);
            $("#padres_fecha_1").prop("disabled", true);
            $("#padres_genero_1").prop("disabled", true);
            $("#padres_finado_1").prop("disabled", true);
            $("#padres_edad_1").prop("disabled", true);
            $("#padres_nombre_2").prop("disabled", true);
            $("#padres_fecha_2").prop("disabled", true);
            $("#padres_genero_2").prop("disabled", true);
            $("#padres_finado_2").prop("disabled", true);
            $("#padres_edad_2").prop("disabled", true);
            if (resp) {
                contIntPadres = 1;
                $("#btn_agregar").hide();
                $("#btn_familia").hide();
                $("#btn_cancel_familia").hide();
                resp.padres.forEach(padre => {
                    $("#id_datos").val(padre.id_datos);
                    $("#id_nomina").val(padre.num_nomina);
                    $("#padres_id_" + contIntPadres).val(padre.id_padres);
                    $("#padres_nombre_" + contIntPadres).val(padre.nombre_padres);
                    $("#padres_fecha_" + contIntPadres).val(padre.fecha_nacimiento_padres);
                    $("#padres_genero_" + contIntPadres).val(padre.genero_padres);
                    $("#padres_finado_" + contIntPadres).val(padre.finado);
                    if (padre.edad != 0) { $("#padres_edad_" + contIntPadres).val(padre.edad); }
                    else { $("#padres_edad_" + contIntPadres).val(""); }
                    contIntPadres++;
                });
                $("#hijos").empty();
                if (resp.hijos != "") {
                    contIntHijo = 1;
                    resp.hijos.forEach(hijo => {

                        $("#hijos").append(`
              <div class="row">
                  <input type="hidden" id="hijos_id_${contIntHijo}" name="hijos_id_[]" value="${hijo.id_son}">
                  <div class=" form-group col-md-5">
                    <label for="hijos_nombre_${contIntHijo}">Nombre Completo:</label>
                    <input type="text" class="form-control" id="hijos_nombre_${contIntHijo}" name="hijos_nombre_[]" value="${hijo.nombre_hijo}" onchange="validaHijos(${contIntHijo})" disabled>
                  </div>
                  <div class=" form-group col-md-2">
                    <label for="hijos_fecha_${contIntHijo}">Fecha de Nacimiento:</label>
                    <input type="date" class="form-control" id="hijos_fecha_${contIntHijo}" name="hijos_fecha_[]" value="${hijo.fecha_nacimiento}" onchange="validaHijos(${contIntHijo})" disabled>
                  </div>
                  <div class=" form-group col-md-2">
                    <label for="hijos_genero_${contIntHijo}">Genero:</label>
                    <select class="form-control" id="hijos_genero_${contIntHijo}" name="hijos_genero_[]" value="" disabled>
                      <option value="Masculino">MASCULINO</option>
                      <option value="Femenino">FEMENINO</option>
                      <option value="Indistinto">INDISTINTO</option>
                    </select>
                  </div>
                  <div class=" form-group col-md-1">
                    <label for="hijos_edad_${contIntHijo}">Edad:</label>
                    <input type="number" min="1" class="form-control" id="hijos_edad_${contIntHijo}" name="hijos_edad_[]" value="${hijo.edad_hijo}" onchange="validaHijos(${contIntHijo})" disabled>
                  </div>
                  </div>`);
                        $("#hijos_genero_" + contIntHijo).val(hijo.genero);
                        arraySon.push(contIntHijo);
                        sessionStorage.setItem('arraySon', JSON.stringify(arraySon));
                        contIntHijo++;
                    });
                } else {
                    $("#hijos").append(`<div id="mensaje_hijos" class="form-group col-md-12" style="text-align: center;"><h3 style="margin-top:2rem;font-style:oblique;font-family:initial;">SIN HIJOS</h3></div>`);
                }
                $("#btn_edit_familia").show();
            } else {
                $("#btn_agregar").hide();
                $("#btn_familia").hide();
                $("#btn_cancel_familia").hide();
                $("#btn_edit_familia").hide();
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "La Informacion de tus Familiares no ha sido Encontada",
                });
            }
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: `Error: ​​[ ${jqXHR.status} ]`,
        });
    });
}

function validaPadres() {
    if ($("#padres_nombre_1").val().length > 0) {
        $("#padres_nombre_1").removeClass('has-error');
    }
    if ($("#padres_fecha_1").val().length > 0) {
        $("#padres_fecha_1").removeClass('has-error');
    }
    if ($("#padres_genero_1").val().length > 0) {
        $("#padres_genero_1").removeClass('has-error');
    }
    if ($("#padres_finado_1").val().length > 0) {
        $("#padres_finado_1").removeClass('has-error');
    }
    if ($("#padres_edad_1").val().length > 0) {
        $("#padres_edad_1").removeClass('has-error');
    }
    if ($("#padres_nombre_2").val().length > 0) {
        $("#padres_nombre_2").removeClass('has-error');
    }
    if ($("#padres_fecha_2").val().length > 0) {
        $("#padres_fecha_2").removeClass('has-error');
    }
    if ($("#padres_genero_2").val().length > 0) {
        $("#padres_genero_2").removeClass('has-error');
    }
    if ($("#padres_finado_2").val().length > 0) {
        $("#padres_finado_2").removeClass('has-error');
    }
    if ($("#padres_edad_2").val().length > 0) {
        $("#padres_edad_2").removeClass('has-error');
    }
}

function validaHijos(num) {
    if ($("#hijos_nombre_" + num).val().length > 0) {
        $("#hijos_nombre_" + num).removeClass('has-error');
    }
    if ($("#hijos_fecha_" + num).val().length > 0) {
        $("#hijos_fecha_" + num).removeClass('has-error');
    }
    if ($("#hijos_edad_" + num).val().length > 0) {
        $("#hijos_edad_" + num).removeClass('has-error');
    }
}

$("#btn_edit_familia").on("click", function (e) {
    e.preventDefault();
    $("#mensaje_hijos").empty();
    $("#btn_edit_familia").hide();
    for (var i = 1; i < contIntPadres; i++) {
        $("#padres_nombre_" + i).prop("disabled", false);
        $("#padres_fecha_" + i).prop("disabled", false);
        $("#padres_genero_" + i).prop("disabled", false);
        $("#padres_finado_" + i).prop("disabled", false);
        $("#padres_edad_" + i).prop("disabled", false);
    }
    if (arraySon.length > 0) {
        arraySon.forEach(item => {
            $("#hijos_nombre_" + item).prop("disabled", false);
            $("#hijos_fecha_" + item).prop("disabled", false);
            $("#hijos_genero_" + item).prop("disabled", false);
            $("#hijos_edad_" + item).prop("disabled", false);
        });
    }
    $("#btn_agregar").show();
    $("#btn_familia").show();
    $("#btn_cancel_familia").show();
});

$("#btn_cancel_familia").on("click", function (e) {
    e.preventDefault();
    $(".has-error").removeClass('has-error');
    Family();
})

$("#btn_agregar").on("click", function (e) {
    e.preventDefault();
    if (document.getElementById("mensaje_hijos")) {
        $("#mensaje_hijos").remove();
    }
    if (arraySon.length < 5) {
        contSon++;
        arraySon.forEach(item => {
            if (item === contSon) {
                contSon++;
            }
        });
        $("#hijos").append(`
          <div class="row"  id="NewSon_${contSon}">
                  <input type="hidden" id="hijos_id_${contSon}" name="hijos_id_[]">
                  <div class=" form-group col-md-5">
                    <label for="hijos_nombre_${contSon}">Nombre Completo:</label>
                    <input type="text" class="form-control" id="hijos_nombre_${contSon}" name="hijos_nombre_[]" onchange="validaHijos(${contSon})">
                  </div>
                  <div class=" form-group col-md-2">
                    <label for="hijos_fecha_${contSon}">Fecha de Nacimiento:</label>
                    <input type="date" class="form-control" id="hijos_fecha_${contSon}" name="hijos_fecha_[]" onchange="validaHijos(${contSon})">
                  </div>
                  <div class=" form-group col-md-2">
                    <label for="hijos_genero_${contSon}">Genero:</label>
                    <select class="form-control" id="hijos_genero_${contSon}" name="hijos_genero_[]">
                      <option value="Masculino">MASCULINO</option>
                      <option value="Femenino">FEMENINO</option>
                      <option value="Indistinto">INDISTINTO</option>
                    </select>
                  </div>
                  <div class=" form-group col-md-1">
                    <label for="hijos_edad_${contSon}">Edad:</label>
                    <input type="number" min="1" class="form-control" id="hijos_edad_${contSon}" name="hijos_edad_[]" onchange="validaHijos(${contSon})">
                  </div>
                  <div class="col-md-1">
                      <div id="btn_eliminar_${contSon}" class="form-group">
                          <button type="button" class="btn btn-danger btn-retirar-item" style="margin-top:33px;" onclick="retirarItem(${contSon}) ">
                          <i class="fas fa-times"></i>
                          </button>
                      </div>
                  </div>
              </div>`);
        arraySon.push(contSon);
        sessionStorage.setItem('arraySon', JSON.stringify(arraySon));
        contSon++;
    } else {
        $("#error_hijos").html(
            `<div class="alert alert-warning alert-dismissible" role="alert">
                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
                   </button>
                   <strong>EL SISTEMA SOLO PERMITE EL REGISTRO MAXIMO DE 5 HIJOS ...</strong>
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
});

function retirarItem(item) {
    var i = arraySon.indexOf(item);
    arraySon.splice(i, 1);
    sessionStorage.setItem('arraySon', JSON.stringify(arraySon));
    $("#NewSon_" + item).remove();
    if (contSon > 0) {
        contSon = 0;
    }
}

$("#familia").submit(function (e) {
    e.preventDefault()

    if ($("#padres_nombre_1").val().length == 0) {
        var error_padres_nombre_1 = "error";
        $("#padres_nombre_1").addClass('has-error');
    } else {
        error_padres_nombre_1 = "";
        $("#padres_nombre_1").removeClass('has-error');
    }
    if ($("#padres_fecha_1").val().length == 0) {
        var error_padres_fecha_1 = "error";
        $("#padres_fecha_1").addClass('has-error');
    } else {
        error_padres_fecha_1 = "";
        $("#padres_fecha_1").removeClass('has-error');
    }
    if ($("#padres_genero_1").val().length == 0) {
        var error_padres_genero_1 = "error";
        $("#padres_genero_1").addClass('has-error');
    } else {
        error_padres_genero_1 = "";
        $("#padres_genero_1").removeClass('has-error');
    }
    if ($("#padres_finado_1").val().length == 0) {
        var error_padres_finado_1 = "error";
        $("#padres_finado_1").addClass('has-error');
    } else {
        error_padres_finado_1 = "";
        $("#padres_finado_1").removeClass('has-error');
    }

    var error_padres_edad_1 = "";
    if ($("#padres_finado_1").val() == "VIVE") {
        if ($("#padres_edad_1").val().length == 0) {
            error_padres_edad_1 = "error";
            $("#padres_edad_1").addClass('has-error');
        } else {
            error_padres_edad_1 = "";
            $("#padres_edad_1").removeClass('has-error');
        }
    }

    if ($("#padres_nombre_2").val().length == 0) {
        var error_padres_nombre_2 = "error";
        $("#padres_nombre_2").addClass('has-error');
    } else {
        error_padres_nombre_2 = "";
        $("#padres_nombre_2").removeClass('has-error');
    }
    if ($("#padres_fecha_2").val().length == 0) {
        var error_padres_fecha_2 = "error";
        $("#padres_fecha_2").addClass('has-error');
    } else {
        error_padres_fecha_2 = "";
        $("#padres_fecha_2").removeClass('has-error');
    }
    if ($("#padres_genero_2").val().length == 0) {
        var error_padres_genero_2 = "error";
        $("#padres_genero_2").addClass('has-error');
    } else {
        error_padres_genero_2 = "";
        $("#padres_genero_2").removeClass('has-error');
    }
    if ($("#padres_finado_2").val().length == 0) {
        var error_padres_finado_2 = "error";
        $("#padres_finado_2").addClass('has-error');
    } else {
        error_padres_finado_2 = "";
        $("#padres_finado_2").removeClass('has-error');
    }

    var error_padres_edad_2 = "";
    if ($("#padres_finado_2").val() == "VIVE") {
        if ($("#padres_edad_2").val().length == 0) {
            error_padres_edad_2 = "error";
            $("#padres_edad_2").addClass('has-error');
        } else {
            error_padres_edad_2 = "";
            $("#padres_edad_2").removeClass('has-error');
        }
    }
    var error_hijos_nombre_ = "";
    var error_hijos_fecha_ = "";
    var error_hijos_edad_ = "";
    arraySon.forEach(item => {
        if ($("#hijos_nombre_" + item).val().length == 0) {
            error_hijos_nombre_ = "error";
            $("#hijos_nombre_" + item).addClass('has-error');
        } else {
            error_hijos_nombre_ = "";
            $("#hijos_nombre_" + item).removeClass('has-error');
        }
        if ($("#hijos_fecha_" + item).val().length == 0) {
            error_hijos_fecha_ = "error";
            $("#hijos_fecha_" + item).addClass('has-error');
        } else {
            error_hijos_fecha_ = "";
            $("#hijos_fecha_" + item).removeClass('has-error');
        }
        if ($("#hijos_edad_" + item).val().length == 0) {
            error_hijos_edad_ = "error";
            $("#hijos_edad_" + item).addClass('has-error');
        } else {
            error_hijos_edad_ = "";
            $("#hijos_edad_" + item).removeClass('has-error');
        }
    });

    if (error_padres_nombre_1 != "" || error_padres_fecha_1 != "" || error_padres_genero_1 != "" || error_padres_finado_1 != "" ||
        error_padres_edad_1 != "" || error_padres_nombre_2 != "" || error_padres_fecha_2 != "" || error_padres_genero_2 != "" ||
        error_padres_finado_2 != "" || error_padres_edad_2 != ""
        || error_hijos_nombre_ != "" || error_hijos_fecha_ != "" || error_hijos_edad_ != "") {
        return false;
    }
    let timerInterval = Swal.fire({ //se le asigna un nombre al swal
        allowOutsideClick: false,
        title: '¡Actualizando Datos!',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });
    $("#cantidad_hijos").val(arraySon.length);
    $("#btn_familia").prop("disabled", true);
    let dataFamily = new FormData($("#familia")[0]);
    $.ajax({
        data: dataFamily,
        url: `${urls}usuarios/familia_guardar`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (save) {
            Swal.close(timerInterval);
            $("#btn_familia").prop("disabled", false);
            if (save == true) {
                // $('#familia')[0].reset();
                arraySon = [];
                setTimeout(function () {
                    Family();
                }, 100);
                Swal.fire("!Los datos se han Actualizado!", "", "success");
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador del Sistema",
                });
            }
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: `Error: ​​[ ${jqXHR.status} ]`,
        });
        $("#btn_familia").prop("disabled", false);
    });
});

/* ------ CONTACTO DE DOCUMENTOS ------  */
var arrayCursos = [];
var arrayDiplomas = [];
var contAjaxCursos = 0;
var contAjaxDiplomas = 0;
var contCursos = 0;
var contDiplomas = 0;
var issetIng = 0;
var curp_db = "";
var rfc_db = "";
function Documents() {
    arrayCursos = [];
    arrayDiplomas = [];
    contCursos = 0;
    contDiplomas = 0;
    contAjaxCursos = 0;
    contAjaxDiplomas = 0;
    $.ajax({
        url: `${urls}usuarios/documentos`,
        type: "post",
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentTypeasignacion
        dataType: "json",
        success: function (resp) {
            $("#lbl_1").attr('style', 'margin-top:25px;');
            $("#lbl_2").attr('style', 'margin-top:25px;');
            $("#ingles_div").empty();
            $("#diplimas_div").empty();
            $("#acta_div").empty();
            $("#curp_div").empty();
            $("#rfc_div").empty();
            $("#cv_div").empty();
            $("#estudios_div").empty();
            $("#cursos_div").empty();
            $("#ingles_div").prop('style', false);
            $("#diplimas_div").prop('style', false);
            $("#curp_div").prop('style', false);
            $("#rfc_div").prop('style', false);
            $("#cv_div").prop('style', false);
            $("#estudios_div").prop('style', false);
            if (resp) {
                $("#btn_document").hide();
                $("#btn_cancel_document").hide();
                $("#btn_diploma").hide();
                $("#btn_curso").hide();
                $("#acta_div").append(`
                 <label for="doc_acta">Acta de Nacimiento:</label>
                   <p>Fecha: <input type="text" style="border:none;width:5rem;" value ="${resp.acta.created_at}">
                     <br>Nombre del Archivo: <input type="text" style="border:none;" value="${resp.acta.nombre_original}">
                   </p>`)
                $("#id_doc_acta").val(resp.acta.id_doc);
                $("#estudios_div").append(`
                <label for="doc_estudios">Comprobante de Estudios:</label>
                  <p>Fecha: <input type="text" style="border:none;width:5rem;" value ="${resp.estudios.created_at}">
                    <br>Nombre del Archivo: <input type="text" style="border:none;" value="${resp.estudios.nombre_original}">
                  </p>`)
                $("#id_doc_estudios").val(resp.estudios.id_doc);
                curp_db = resp.curp.descripcion;
                $("#curp_div").append(`
                 <label for="doc_curp">CURP: ${resp.curp.descripcion}</label>
                   <p>Fecha: <input type="text" style="border:none;width:5rem;" value ="${resp.curp.created_at}">
                     &nbsp;&nbsp;&nbsp;Nombre del Archivo: <input type="text" style="border:none;" value="${resp.curp.nombre_original}">
                   </p>`)
                $("#id_doc_curp").val(resp.curp.id_doc);
                rfc_db = resp.rfc.descripcion;
                $("#rfc_div").append(`
                 <label for="doc_rfc">RFC (Constancia de Situación Fiscal): ${resp.rfc.descripcion}</label>
                   <p>Fecha: <input type="text" style="border:none;width:5rem;" value ="${resp.rfc.created_at}">
                     &nbsp;&nbsp;&nbsp;Nombre del Archivo: <input type="text" style="border:none;" value="${resp.rfc.nombre_original}">
                   </p>`)
                $("#id_doc_rfc").val(resp.rfc.id_doc);

                $("#id_datos_doc").val(resp.acta.id_datos);
                if (resp.ingles != null) {
                    $("#id_doc_ingles").val(resp.ingles.id_doc);
                    $("#ingles_div").append(`
                     <label>Certificado de Ingles:</label>
                     <p>Fecha: <input type="text" style="border:none;width:5rem;" value="${resp.ingles.created_at}">
                     <br>Nombre del Archivo: <input type="text" style="border:none;" value="${resp.ingles.nombre_original}"></p>`);
                } else {
                    $("#ingles_div").append(`
                     <label>Certificado de Ingles:</label>
                     <p>SIN CERTIFICACION</p>`);
                }
                if (resp.cv != null) {
                    $("#id_doc_cv").val(resp.cv.id_doc);
                    $("#cv_div").append(`
                     <label>Curriculum:</label>
                     <p>Fecha: <input type="text" style="border:none;width:5rem;" value="${resp.cv.created_at}">
                     <br>Nombre del Archivo: <input type="text" style="border:none;" value="${resp.cv.nombre_original}"></p>`);
                } else {
                    $("#cv_div").append(`
                     <label>Curriculum:</label>
                     <p>SIN CURRICULUM</p>`);
                }
                if (resp.diploma != "") {
                    resp.diploma.forEach(diplo => {
                        if (arrayDiplomas.length == 0) {
                            contAjaxDiplomas++;
                        } else {
                            contAjaxDiplomas++;
                            arrayDiplomas.forEach(item => {
                                if (item === contAjaxDiplomas) {
                                    contAjaxDiplomas++;
                                }
                            });
                        }
                        $("#diplimas_div").append(`
                             <label>${diplo.descripcion}</label>
                             <p>Fecha: <input type="text" style="border:none;width:5rem;" value="${diplo.created_at}">
                             &nbsp;&nbsp;&nbsp; Comprobante: <input type="text" style="border:none;" value="${diplo.nombre_original}"></p>`);
                    });
                } else {
                    $("#diplimas_div").attr('style', 'text-align: center;');
                    $("#diplimas_div").append(`<label>SIN DIPLOMAS</label>`);
                }
                if (resp.cursos != "") {
                    resp.cursos.forEach(curso => {
                        if (arrayCursos.length == 0) {
                            contAjaxCursos++;
                        } else {
                            contAjaxCursos++;
                            arrayCursos.forEach(item => {
                                if (item === contAjaxCursos) {
                                    contAjaxCursos++;
                                }
                            });
                        }
                        $("#cursos_div").append(`
                             <label>${curso.descripcion}</label>
                                 <p>Fecha: <input type="text" style="border:none;width:5rem;" value="${curso.created_at}">
                                 &nbsp;&nbsp;&nbsp; Comprobante: <input type="text" style="border:none;"value="${curso.nombre_original}"></p>`);
                    });
                } else {
                    $("#cursos_div").attr('style', 'text-align: center;');
                    $("#cursos_div").append(`<label>SIN CURSOS</label>`);
                }
                $("#btn_edit_document").show();
            } else {
                $("#btn_document").hide();
                $("#btn_cancel_document").hide();
                $("#btn_edit_document").hide();
                $("#btn_diploma").hide();
                $("#btn_curso").hide();
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "La Informacion de tus Contactos de Emergendia no ha sido Encontada",
                });
            }
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: `Error: ​​[ ${jqXHR.status} ]`,
        });
    });
}

$("#btn_diploma").on("click", function (e) {
    e.preventDefault();
    if ((arrayDiplomas.length + contAjaxDiplomas) < 3) {
        if (arrayDiplomas.length == 0) {
            contDiplomas++;
            if (contAjaxDiplomas == 0) { $("#diplimas_div").empty(); }
        } else {
            contDiplomas++;
            arrayDiplomas.forEach(item => {
                if (item === contDiplomas) {
                    contDiplomas++;
                }
            });
        }
        $("#diplimas_div").append(`
       <div id="diplimas_div_${contDiplomas}" class="row">
       <input type="hidden" name="id_diploma_[]" value="${contDiplomas}">
         <div class="col-md-5"><label for="diploma_${contDiplomas}">Titulo</label>
             <input type="text" name="diploma_${contDiplomas}" id="diploma_${contDiplomas}" class="form-control" aria-describedby="inputGroupFileAddon01" onchange="validaDiploma(${contDiplomas}) required">
         </div>
         <div class="col-md-5">
             <label for="diploma_${contDiplomas}">Comprobante</label>
             <div class="custom-file">
                 <input type="file" accept="application/pdf" class="custom-file-input" id="doc_diploma_${contDiplomas}" name="doc_diploma_${contDiplomas}" aria-describedby="inputGroupFileAddon01" onchange="validaDiploma(${contDiplomas})">
                 <label id="lbl_diploma_${contDiplomas}" class="custom-file-label" for="doc_diploma_${contDiplomas}" style="color:#DBDBDB!important;">comprobante</label>
                 <div class="text-danger" id="error_doc_diploma_${contDiplomas}"></div>
             </div>
         </div>
         <div class="col-md-1">
             <button type="button" class="btn btn-danger btn-retirar-item" style="margin-top:2rem;" onclick="retirarDiploma(${contDiplomas})">
                 <i class="fas fa-times"></i>
             </button>
         </div>
       </div>
       `);
        arrayDiplomas.push(contDiplomas);
        sessionStorage.setItem('arrayDiplomas', JSON.stringify(arrayDiplomas));
    } else {
        $("#diplomas_div_error").html(
            `<div class="alert alert-warning alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                <strong>NO SE PERMITEN MAS DE 3 DIPLOMAS ...</strong>
                </div>
                <span></span>`
        );
        setTimeout(function () {
            $(".alert")
                .fadeTo(1000, 0)
                .slideUp(800, function () {
                    $(this).remove();
                });
        }, 2000);
        return false;
    }
});

function validaDiploma(cont_D) {
    if ($("#diploma_" + cont_D).val().length > 0) {
        $("#error_doc_diploma_" + cont_D).text('');
        $("#diploma_" + cont_D).removeClass('has-error');
    }
    if ($("#doc_diploma_" + cont_D).val().length > 0) {
        $("#lbl_diploma_" + cont_D).empty();
        $("#lbl_diploma_" + cont_D).append(`${document.getElementById(`doc_diploma_${cont_D}`).files[0].name}`);
        $("#lbl_diploma_" + cont_D).attr('style', 'color:#343a40!important;');
        $("#lbl_diploma_" + cont_D).removeClass('has-error');
    }
}

function retirarDiploma(item_D) {
    var i = arrayDiplomas.indexOf(item_D);
    arrayDiplomas.splice(i, 1);
    sessionStorage.setItem('arrayDiplomas', JSON.stringify(arrayDiplomas));

    $("#diplimas_div_" + item_D).remove();
    if (contDiplomas > 0) {
        contDiplomas = 0;
    }
}

$("#btn_curso").on("click", function (e) {
    e.preventDefault();
    if ((arrayCursos.length + contAjaxCursos) < 3) {
        if (arrayCursos.length == 0) {
            contCursos++;
            if (contAjaxCursos == 0) { $("#cursos_div").empty(); }
        } else {
            contCursos++;
            arrayCursos.forEach(item => {
                if (item === contCursos) {
                    contCursos++;
                }
            });
        }
        $("#cursos_div").append(`
       <div id="cusos_div_${contCursos}" class="row" style="margin-bottom:5px;">
         <div class="col-md-5">
         <input type="hidden" name="id_cusos_[]" value="${contCursos}">
             <label for="curso_${contCursos}">Titulo</label>
             <input type="text" name="curso_${contCursos}" id="curso_${contCursos}" class="form-control" aria-describedby="inputGroupFileAddon01" onchange="validaCurso(${contCursos}) required">
         </div>
         <div class="col-md-5">
             <label for="diploma_${contCursos}">Comprobante</label>
             <div class="custom-file">
                 <input type="file" accept="application/pdf" class="custom-file-input" id="doc_curso_${contCursos}" name="doc_curso_${contCursos}" aria-describedby="inputGroupFileAddon01" onchange="validaCurso(${contCursos})">
                 <label id="lbl_curso_${contCursos}" class="custom-file-label" for="doc_curso_${contCursos}" style="color:#DBDBDB!important;">comprobante</label>
                 <div class="text-danger" id="error_doc_curso_${contCursos}"></div>
             </div>
         </div>
         <div class="col-md-1">
             <button type="button" class="btn btn-danger btn-retirar-item" style="margin-top:2rem;" onclick="retirarCurso(${contCursos})">
                 <i class="fas fa-times"></i>
             </button>
         </div>
       </div>
       `);
        arrayCursos.push(contCursos);
        // Se guarda en localStorage despues de JSON stringificarlo 
        sessionStorage.setItem('arrayCursos', JSON.stringify(arrayCursos));
    } else {
        $("#cursos_div_error").html(
            `<div class="alert alert-warning alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                <strong>NO SE PERMITEN MAS DE 3 CURSOS ...</strong>
                </div>
                <span></span>`
        );
        setTimeout(function () {
            $(".alert")
                .fadeTo(1000, 0)
                .slideUp(800, function () {
                    $(this).remove();
                });
        }, 2000);
        return false;
    }
});

function validaCurso(cont_C) {
    if ($("#curso_" + cont_C).val().length > 0) {
        $("#curso_" + cont_C).removeClass('has-error');
        $("#error_doc_curso_" + cont_C).text('');
    }
    if ($("#doc_curso_" + cont_C).val().length > 0) {
        $("#lbl_curso_" + cont_C).empty();
        $("#lbl_curso_" + cont_C).append(`${document.getElementById(`doc_curso_${cont_C}`).files[0].name}`);
        $("#lbl_curso_" + cont_C).attr('style', 'color:#343a40!important;');
        $("#lbl_curso_" + cont_C).removeClass('has-error');
    }
}

function retirarCurso(item_C) {
    var i = arrayCursos.indexOf(item_C);
    arrayCursos.splice(i, 1);
    sessionStorage.setItem('arrayCursos', JSON.stringify(arrayCursos));
    $("#cusos_div_" + item_C).remove();
    if (contCursos > 0) {
        contCursos = 0;
    }
}
var file_ing = 0;
var file_acta = 0;
var file_estudios = 0;
var file_curp = 0;
var file_rfc = 0;
var file_cv = 0;
function validaFile(id) {
    if (id == 1) {
        if ($("#doc_acta").val().length > 0) {
            $("#lbl_acta").attr('style', 'color:#0D0D0D;');
            $("#lbl_acta").empty();
            $("#lbl_acta").append(`${document.getElementById('doc_acta').files[0].name}`);
            $("#lbl_acta").removeClass('has-error');
        }
    }
    if (id == 2) {
        if ($("#doc_ingles").val().length > 0) {
            $("#lbl_ingles").attr('style', 'color:#0D0D0D;');
            $("#lbl_ingles").empty();
            $("#lbl_ingles").append(`${document.getElementById('doc_ingles').files[0].name}`);
            $("#lbl_ingles").removeClass('has-error');
        }
    }
    if (id == 3) {
        if ($("#doc_curp").val().length > 0) {
            $("#lbl_curp").attr('style', 'color:#0D0D0D;');
            $("#lbl_curp").empty();
            $("#lbl_curp").append(`${document.getElementById('doc_curp').files[0].name}`);
            $("#lbl_curp").removeClass('has-error');
        }
    }
    if (id == 4) {
        if ($("#doc_rfc").val().length > 0) {
            $("#lbl_rfc").attr('style', 'color:#0D0D0D;margin-top:2rem;');
            $("#lbl_rfc").empty();
            $("#lbl_rfc").append(`${document.getElementById('doc_rfc').files[0].name}`);
            $("#lbl_rfc").removeClass('has-error');
        }
        if ($("#rfc").val().length > 0) {
            $("#rfc").removeClass('has-error');
        }
    }
    if (id == 5) {
        if ($("#doc_cv").val().length > 0) {
            $("#lbl_cv").attr('style', 'color:#0D0D0D;');
            $("#lbl_cv").empty();
            $("#lbl_cv").append(`${document.getElementById('doc_cv').files[0].name}`);
            $("#lbl_cv").removeClass('has-error');
        }
    }
    if (id == 6) {
        if ($("#doc_estudios").val().length > 0) {
            $("#lbl_estudios").attr('style', 'color:#0D0D0D;');
            $("#lbl_estudios").empty();
            $("#lbl_estudios").append(`${document.getElementById('doc_estudios').files[0].name}`);
            $("#lbl_estudios").removeClass('has-error');
        }
    }
}

function addFile(id) {
    if (id == 1) {
        file_acta = 1;
        $("#acta_div").empty();
        $("#acta_div").append(`
         <div class="row">
             <div class=" form-group col-md-9">
                 <label for="doc_acta">Acta de Nacimiento:</label>
                 <div class="custom-file">
                     <input type="file" accept="application/pdf" class="custom-file-input" id="doc_acta" name="doc_acta" aria-describedby="inputGroupFileAddon01" onchange="validaFile(1)">
                     <label id="lbl_acta" class="custom-file-label" style="color:#BDBDBD;" for="doc_ingles">Acta de Nacimiento</label>
                 </div>
             </div>
             <div class=" form-group col-md-3">
             <button type="button" class="btn btn-danger btn-retirar-item" style="margin-top:2rem;" onclick="retirarFile(1)">
                 <i class="fas fa-times"></i>
             </button>
             </div>
         </div>`);
    }
    if (id == 2) {
        file_ing = 1;
        $("#ingles_div").empty();
        $("#ingles_div").append(`
         <div class="row">
             <div class=" form-group col-md-9">
             <label for="doc_ingles">Certificado de Ingles:</label>
             <div class="custom-file">
                 <input type="file" accept="application/pdf" class="custom-file-input" id="doc_ingles" name="doc_ingles" aria-describedby="inputGroupFileAddon01" onchange="validaFile(2)">
                 <label id="lbl_ingles" class="custom-file-label" style="color:#BDBDBD;" for="doc_ingles">Ingles</label>
             </div>
             </div>
             <div class=" form-group col-md-3">
             <button type="button" class="btn btn-danger btn-retirar-item" style="margin-top:2rem;" onclick="retirarFile(2)">
                 <i class="fas fa-times"></i>
             </button>
         </div>`);
    }
    if (id == 3) {
        file_curp = 1;
        $("#curp_div").empty();
        $("#curp_div").append(`
         <div class="row">
             <div class=" form-group col-md-9">
                 <label for="doc_curp">CURP: ${curp_db}</label>
                 <div class="custom-file">
                     <input type="file" accept="application/pdf" class="custom-file-input" id="doc_curp" name="doc_curp" aria-describedby="inputGroupFileAddon01" onchange="validaFile(3)">
                     <label id="lbl_curp" class="custom-file-label" style="color:#BDBDBD;" for="doc_curp">CURP</label>
                 </div>
             </div>
             <div class=" form-group col-md-3">
             <button type="button" class="btn btn-danger btn-retirar-item" style="margin-top:2rem;" onclick="retirarFile(3)">
                 <i class="fas fa-times"></i>
             </button>
             </div>
         </div>`);
    }
    if (id == 4) {
        file_rfc = 1;
        $("#rfc_div").empty();
        $("#rfc_div").append(`
         <div class="row">
            <div class=" form-group col-md-4">
                <label for="doc_rfc">RFC:</label>
                    <input type="text" style="text-transform:uppercase;" value="${rfc_db}" class="form-control" name="rfc" id="rfc" onchange="validaFile(4)">
                </div>  
            <div class=" form-group col-md-5">
                 <div class="custom-file">
                     <input type="file" accept="application/pdf" class="custom-file-input" id="doc_rfc" name="doc_rfc" aria-describedby="inputGroupFileAddon01" onchange="validaFile(4)">
                     <label id="lbl_rfc" class="custom-file-label" style="color:#BDBDBD;margin-top:2rem;" for="doc_rfc">RFC</label>
                 </div>
             </div>
             <div class=" form-group col-md-3">
             <button type="button" class="btn btn-danger btn-retirar-item" style="margin-top:2rem;" onclick="retirarFile(4)">
                 <i class="fas fa-times"></i>
             </button>
             </div>
         </div>`);
    }
    if (id == 5) {
        file_cv = 1;
        $("#cv_div").empty();
        $("#cv_div").append(`
         <div class="row">
             <div class=" form-group col-md-9">
             <label for="doc_cv">Curriculum:</label>
             <div class="custom-file">
                 <input type="file" accept="application/pdf" class="custom-file-input" id="doc_cv" name="doc_cv" aria-describedby="inputGroupFileAddon01" onchange="validaFile(5)">
                 <label id="lbl_cv" class="custom-file-label" style="color:#BDBDBD;" for="doc_cv">Curriculum</label>
             </div>
             </div>
             <div class=" form-group col-md-3">
             <button type="button" class="btn btn-danger btn-retirar-item" style="margin-top:2rem;" onclick="retirarFile(5)">
                 <i class="fas fa-times"></i>
             </button>
         </div>`);
    }
    if (id == 6) {
        file_estudios = 1;
        $("#estudios_div").empty();
        $("#estudios_div").append(`
         <div class="row">
             <div class=" form-group col-md-9">
             <label for="doc_estudios">Comprobante de Estudios:</label>
                 <div class="custom-file">
                     <input type="file" accept="application/pdf" class="custom-file-input" id="doc_estudios" name="doc_estudios" aria-describedby="inputGroupFileAddon01" onchange="validaFile(6)">
                     <label id="lbl_estudios" class="custom-file-label" style="color:#BDBDBD;" for="doc_ingles">Estudios</label>
                 </div>
             </div>
             <div class=" form-group col-md-3">
             <button type="button" class="btn btn-danger btn-retirar-item" style="margin-top:2rem;" onclick="retirarFile(6)">
                 <i class="fas fa-times"></i>
             </button>
             </div>
         </div>`);
    }
}

function retirarFile(id) {
    if (id == 1) {
        file_acta = 0;
        $("#acta_div").empty();
        $("#acta_div").append(`
         <label>Acta de Nacimiento:</label><br>
         <button class="btn btn-guardar btn-style" style="padding-top:3px;height:2rem;margin-top:0px;background-color:#0056B3!important;margin-left:1rem;" onclick="addFile(1)"><i class="fas fa-file-upload"></i>&nbsp;&nbsp;&nbsp;Actualizar</button>`);
    }
    if (id == 2) {
        file_ing = 0;
        $("#ingles_div").empty();
        $("#ingles_div").append(`
         <label>Certificado de Ingles:</label><br>
         <button class="btn btn-guardar btn-style" style="padding-top:3px;height:2rem;margin-top:0px;background-color:#0056B3!important;margin-left:1rem;" onclick="addFile(2)"><i class="fas fa-file-upload"></i>&nbsp;&nbsp;&nbsp;${txt_ing}</button>`);
    }
    if (id == 3) {
        file_curp = 0;
        $("#curp_div").empty();
        $("#curp_div").append(`
          <label>CURP: ${curp_db}</label><br>
          <button class="btn btn-guardar btn-style" style="padding-top:3px;height:2rem;margin-top:0px;background-color:#0056B3!important;margin-left:1rem;" onclick="addFile(3)"><i class="fas fa-file-upload"></i>&nbsp;&nbsp;&nbsp;Actualizar</button>`);
    }
    if (id == 4) {
        file_rfc = 0;
        $("#rfc_div").empty();
        $("#rfc_div").append(`
         <label>RFC: ${rfc_db}</label><br>
         <button class="btn btn-guardar btn-style" style="padding-top:3px;height:2rem;margin-top:0px;background-color:#0056B3!important;margin-left:1rem;" onclick="addFile(4)"><i class="fas fa-file-upload"></i>&nbsp;&nbsp;&nbsp;Actualizar</button>`);
    }
    if (id == 5) {
        file_cv = 0;
        $("#cv_div").empty();
        $("#cv_div").append(`
          <label>Curriculum:</label><br>
          <button class="btn btn-guardar btn-style" style="padding-top:3px;height:2rem;margin-top:0px;background-color:#0056B3!important;margin-left:1rem;" onclick="addFile(5)"><i class="fas fa-file-upload"></i>&nbsp;&nbsp;&nbsp;${txt_cv}</button>`);
    }
    if (id == 6) {
        file_estudios = 0;
        $("#estudios_div").empty();
        $("#estudios_div").append(`
         <label>Comprobante de Estudios:</label><br>
         <button class="btn btn-guardar btn-style" style="padding-top:3px;height:2rem;margin-top:0px;background-color:#0056B3!important;margin-left:1rem;" onclick="addFile(6)"><i class="fas fa-file-upload"></i>&nbsp;&nbsp;&nbsp;Actualizar</button>`);
    }
}

var txt_ing = "";
var txt_cv = "";
$("#btn_edit_document").on("click", function (e) {
    e.preventDefault();

    $("#lbl_1").attr('style', '');
    $("#lbl_2").attr('style', '');
    $("#btn_edit_document").hide();
    file_acta = 0;
    file_ing = 0;
    file_curp = 0;
    file_cv = 0;
    file_rfc = 0;
    file_estudios = 0;
    $("#acta_div").empty();
    $("#acta_div").append(`
     <label>Acta de Nacimiento:</label><br>
     <button class="btn btn-guardar btn-style" style="padding-top:3px;height:2rem;margin-top:0px;background-color:#0056B3!important;margin-left:1rem;" onclick="addFile(1)"><i class="fas fa-file-upload"></i>&nbsp;&nbsp;&nbsp;Actualizar</button>`);
    $("#estudios_div").empty();
    $("#estudios_div").append(`
    <label>Comprobante de Estudios:</label><br>
      <button class="btn btn-guardar btn-style" style="padding-top:3px;height:2rem;margin-top:0px;background-color:#0056B3!important;margin-left:1rem;" onclick="addFile(6)"><i class="fas fa-file-upload"></i>&nbsp;&nbsp;&nbsp;Actualizar</button>`);
    $("#curp_div").empty();
    $("#curp_div").append(`
     <label>CURP: ${curp_db}</label><br>
     <button class="btn btn-guardar btn-style" style="padding-top:3px;height:2rem;margin-top:0px;background-color:#0056B3!important;margin-left:1rem;" onclick="addFile(3)"><i class="fas fa-file-upload"></i>&nbsp;&nbsp;&nbsp;Actualizar</button>`);
    $("#rfc_div").empty();
    $("#rfc_div").append(`
     <label>RFC: ${rfc_db}</label><br>
     <button class="btn btn-guardar btn-style" style="padding-top:3px;height:2rem;margin-top:0px;background-color:#0056B3!important;margin-left:1rem;" onclick="addFile(4)"><i class="fas fa-file-upload"></i>&nbsp;&nbsp;&nbsp;Actualizar</button>`);
    if ($("#id_doc_ingles").val().length == 0) {
        txt_ing = "Subir";
    } else {
        txt_ing = "Actualizar";
    }
    $("#ingles_div").empty();
    $("#ingles_div").append(`
     <label>Certificado de Ingles:</label><br>
     <button class="btn btn-guardar btn-style" style="padding-top:3px;height:2rem;margin-top:0px;background-color:#0056B3!important;margin-left:1rem;" onclick="addFile(2)"><i class="fas fa-file-upload"></i>&nbsp;&nbsp;&nbsp;${txt_ing}</button>`);
    if ($("#id_doc_cv").val().length == 0) {
        txt_cv = "Subir";
    } else {
        txt_cv = "Actualizar";
    }
    $("#cv_div").empty();
    $("#cv_div").append(`
     <label>Curriculum:</label><br>
     <button class="btn btn-guardar btn-style" style="padding-top:3px;height:2rem;margin-top:0px;background-color:#0056B3!important;margin-left:1rem;" onclick="addFile(5)"><i class="fas fa-file-upload"></i>&nbsp;&nbsp;&nbsp;${txt_cv}</button>`);
    $("#btn_document").show();
    $("#btn_cancel_document").show();
    $("#btn_diploma").show();
    $("#btn_curso").show();
});

$("#btn_cancel_document").on("click", function (e) {
    e.preventDefault();
    $(".has-error").removeClass('has-error');
    Documents();
})

$("#document").submit(function (e) {
    e.preventDefault()
    if (file_ing == 0 && file_acta == 0 && file_curp == 0 && file_rfc == 0 && file_cv == 0 && file_estudios == 0 && 
        arrayCursos.length == 0 && arrayDiplomas.length == 0 ) {
        Documents();
        return false;
    }
    mensaje = "<p>";
    var error_doc_ingles = "";
    if (file_ing == 1) {
        if ($("#doc_ingles").val().length == 0) {
            error_doc_ingles = "error";
            $("#lbl_ingles").addClass('has-error');
        } else if ($("#doc_ingles").val().split(".").pop() != "pdf") {
            error_doc_ingles = "Archivo PDF Necesario";
            $("#lbl_ingles").addClass('has-error');
        } else if (document.getElementById("doc_ingles").files[0].size > 2000000) {
            error_doc_ingles = "2MB";
            peso_doc_ingles = document.getElementById("doc_ingles").files[0].size / 1000000;
            mensaje = mensaje + `Peso Doc. Ingles = ${peso_doc_ingles} MB <br> `;
            $("#lbl_ingles").addClass('has-error');
        } else {
            error_doc_ingles = "";
            $("#lbl_ingles").removeClass('has-error');
        }
    }

    var error_doc_acta = "";
    if (file_acta == 1) {
        if ($("#doc_acta").val().length == 0) {
            error_doc_acta = "error";
            $("#lbl_acta").addClass('has-error');
        } else if ($("#doc_acta").val().split(".").pop() != "pdf") {
            error_doc_acta = "Archivo PDF Necesario";
            $("#lbl_acta").addClass('has-error');
        } else if (document.getElementById("doc_acta").files[0].size > 2000000) {
            peso_doc_acta = document.getElementById("doc_acta").files[0].size / 1000000;
            mensaje = mensaje + `Peso Doc. Acta = ${peso_doc_acta} MB <br> `;
            error_doc_acta = "2MB";
            $("#lbl_acta").addClass('has-error');
        } else {
            error_doc_acta = "";
            $("#lbl_acta").removeClass('has-error');
        }
    }

    var error_doc_curp = "";
    if (file_curp == 1) {
        if ($("#doc_curp").val().length == 0) {
            error_doc_curp = "error";
            $("#lbl_curp").addClass('has-error');
        } else if ($("#doc_curp").val().split(".").pop() != "pdf") {
            error_doc_curp = "Archivo PDF Necesario";
            $("#lbl_curp").addClass('has-error');
        } else if (document.getElementById("doc_curp").files[0].size > 2000000) {
            peso_doc_curp = document.getElementById("doc_curp").files[0].size / 1000000;
            mensaje = mensaje + `Peso Doc. CURP = ${peso_doc_curp} MB <br> `;
            error_doc_curp = "2MB";
            $("#lbl_curp").addClass('has-error');
        } else {
            error_doc_curp = "";
            $("#lbl_curp").removeClass('has-error');
        }
    }

    var error_doc_rfc = "";
    var error_rfc = "";
    if (file_rfc == 1) {
        if ($("#doc_rfc").val().length == 0) {
            error_doc_rfc = "error";
            $("#lbl_rfc").addClass('has-error');
        } else if ($("#doc_rfc").val().split(".").pop() != "pdf") {
            error_doc_rfc = "Archivo PDF Necesario";
            $("#lbl_rfc").addClass('has-error');
        } else if (document.getElementById("doc_rfc").files[0].size > 2000000) {
            error_doc_rfc = "2MB";
            peso_doc_rfc = document.getElementById("doc_rfc").files[0].size / 1000000;
            mensaje = mensaje + `Peso Doc. RFC = ${peso_doc_rfc} MB <br> `;
            $("#lbl_rfc").addClass('has-error');
        } else {
            error_doc_rfc = "";
            $("#lbl_rfc").removeClass('has-error');
        }
        if ($.trim($("#rfc").val()).length == 0) {
            error_rfc = "error";
            $("#rfc").addClass('has-error');
        } else {
            error_rfc = "";
            $("#rfc").removeClass('has-error');
        }
    }

    var error_doc_cv = "";
    if (file_cv == 1) {
        if ($("#doc_cv").val().length == 0) {
            error_doc_cv = "error";
            $("#lbl_cv").addClass('has-error');
        } else if ($("#doc_cv").val().split(".").pop() != "pdf") {
            error_doc_cv = "Archivo PDF Necesario";
            $("#lbl_cv").addClass('has-error');
        } else if (document.getElementById("doc_cv").files[0].size > 2000000) {
            error_doc_cv = "2MB";
            peso_doc_cv = document.getElementById("doc_cv").files[0].size / 1000000;
            mensaje = mensaje + `Peso Doc. CV = ${peso_doc_cv} MB <br> `;
            $("#lbl_cv").addClass('has-error');
        } else {
            error_doc_cv = "";
            $("#lbl_cv").removeClass('has-error');
        }
    }

    var error_doc_estudios = "";
    if (file_estudios == 1) {
        if ($("#doc_estudios").val().length == 0) {
            error_doc_estudios = "error";
            $("#lbl_estudios").addClass('has-error');
        } else if ($("#doc_estudios").val().split(".").pop() != "pdf") {
            error_doc_estudios = "Archivo PDF Necesario";
            $("#lbl_estudios").addClass('has-error');
        } else if (document.getElementById("doc_estudios").files[0].size > 2000000) {
            error_doc_estudios = "2MB";
            peso_doc_estudios = document.getElementById("doc_estudios").files[0].size / 1000000;
            mensaje = mensaje + `Peso Doc. Estudios = ${peso_doc_estudios} MB <br> `;
            $("#lbl_estudios").addClass('has-error');
        } else {
            error_doc_estudios = "";
            $("#lbl_estudios").removeClass('has-error');
        }
    }
var cont_error_curso = 0;
    var error_curso = "";
    if (arrayCursos.length > 0) {
        arrayCursos.forEach(item => {
            $("#error_doc_curso_" + item).text('');
            if ($("#curso_" + item).val().length == 0) {
                $("#curso_" + item).addClass('has-error');
                error_curso = "error";
                cont_error_curso = cont_error_curso + 1; 
            } else {
                error_curso = "";
                $("#curso_" + item).removeClass('has-error');
            }
            if ($("#doc_curso_" + item).val().length == 0) {
                $("#lbl_curso_" + item).addClass('has-error');
                error_curso = "error";
                cont_error_curso = cont_error_curso + 1; 
            } else if ($("#doc_curso_" + item).val().split(".").pop() != "pdf") {
                error_curso = "Archivo PDF Necesario";
                $("#error_doc_curso_" + item).text(error_curso);
                $("#lbl_curso_" + item).addClass('has-error');
                cont_error_curso = cont_error_curso + 1; 
            } else if (document.getElementById(`doc_curso_${item}`).files[0].size > 2000000) {
                error_curso = "2MB";
                $("#error_doc_curso_" + item).text('Archivo con Peso Mayor a 2MB');
                $("#lbl_curso_" + item).addClass('has-error');
                cont_error_curso = cont_error_curso + 1; 
            } else {
                error_curso = "";
                $("#lbl_curso_" + item).removeClass('has-error');
            }
        });
    }

    var cont_error_diplo = 0;
    var error_diplo = "";
    if (arrayDiplomas.length > 0) {
        arrayDiplomas.forEach(item => {
            $("#error_doc_diploma_" + item).text('');
            if ($("#diploma_" + item).val().length == 0) {
                $("#diploma_" + item).addClass('has-error');
                error_diplo = "error";
                cont_error_diplo = cont_error_diplo + 1;
            } else {
                error_diplo = "";
                $("#diploma_" + item).removeClass('has-error');
            }
            if ($("#doc_diploma_" + item).val().length == 0) {
                $("#lbl_diploma_" + item).addClass('has-error');
                error_diplo = "error";
                cont_error_diplo = cont_error_diplo + 1;
            } else if ($("#doc_diploma_" + item).val().split(".").pop() != "pdf") {
                error_diplo = "Archivo PDF Necesario";
                $("#error_doc_diploma_" + item).text(error_diplo);
                $("#lbl_diplo_" + item).addClass('has-error');
                cont_error_diplo = cont_error_diplo + 1;
            } else if (document.getElementById(`doc_diploma_${item}`).files[0].size > 2000000) {
                error_diplo = "2MB";
                $("#error_doc_diploma_" + item).text('Archivo con Peso Mayor a 2MB');
                $("#lbl_diploma_" + item).addClass('has-error');
                cont_error_diplo = cont_error_diplo + 1;
            } else {
                error_diplo = "";
                $("#lbl_diploma_" + item).removeClass('has-error');
            }
        });
    }


    if (error_doc_acta != "" || error_doc_ingles != "" || error_curso != "" || error_diplo != "" || error_doc_curp != "" || error_doc_rfc != "" || error_rfc != "" || error_doc_cv != "" || error_doc_estudios != "" || cont_error_curso != 0 || cont_error_diplo != 0) {
        if (error_doc_acta == "2MB" || error_doc_ingles == "2MB" || error_curso == "2MB" || error_diplo == "2MB" || error_doc_curp == "2MB" || error_doc_rfc == "2MB" || error_rfc == "2MB" || error_doc_cv == "2MB" || error_doc_estudios == "2MB") {
            mensaje = mensaje + "</p>";
            Swal.fire({
                icon: "error",
                title: "PESO DE ARCHIVOS",
                html: mensaje,
            });
            return false;
        }
        return false;
    }
    let timerInterval = Swal.fire({ //se le asigna un nombre al swal
        allowOutsideClick: false,
        title: '¡Actualizando Datos!',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });
    $("#btn_document").prop("disabled", true);
    let dataContac = new FormData($("#document")[0]);
    dataContac.append('curp', curp_db);
    $.ajax({
        data: dataContac,
        url: `${urls}usuarios/documentos_guardar`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (save) {
            Swal.close(timerInterval);
            $("#btn_document").prop("disabled", false);
            if (save == true) {
                $('#document')[0].reset();
                setTimeout(function () {
                    Documents();
                }, 100);
                Swal.fire("!Los datos se han Actualizado!", "", "success");
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: `Algo salió Mal! Contactar con el Administrador del Sistema
                     ${save}`,
                });
            }
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: `Error: ​​[ ${jqXHR.status} ]`,
        });
        $("#btn_document").prop("disabled", false);
    });
});
