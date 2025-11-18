/**
 * ARCHIVO MODULO PAQUETERIA
 * AUTOR:HORUS SAMAEL RIVAS PEDRAZA
 * horus.riv.ped@gmail.com
 * CEL:56 2439 2632
 */
$(document).ready(function () {
    $(".select2bs4").select2({
        placeholder: "Selecciona una Opción",
    });





});


$("#empresa_solicitante").on("change", function(){

    
    $("#solicitante_R").val($("#nombre").val());
   
    if ($.trim($("#empresa_solicitante").val()) != "ALMACEN VILLAHERMOSA") {

        
    $("#calle_R").val("Avenida Industrial");
    $("#numero_R").val("Lt. 16");
    $("#colonia_R").val("Fraccionamiento Industrial");
    $("#localidad_R").val("Tepotzotlán");
    
    $("#estado_R").val("Estado de México");
     $("#pais_R").val("México");
    $("#cp_R").val(54610);
    }

    if ($.trim($("#empresa_solicitante").val()) == "OTRO") {
        $("#agrega_otro").addClass('col-md-4');
        $("#agrega_otro").append(
            `
         <div class="form-group">
             <label for="monto">Empresa:</label>
             <input type="text" class="form-control" id="empresa" name="empresa" value="">
             <div id="error_empresa_R" class="text-danger"></div>
         </div>
         `);
        }else{
            $("#error_empresa_R").text("");
            $("#agrega_otro").removeClass('col-md-4');
            $("#agrega_otro").empty();
            error_empresa_R = "";
        }


});

/* CODIGO PARA VALIDAR Y PASAR A LA SIGUIENTE FIELDSETS */

var current_fs, next_fs, previous_fs; //fieldsets
var opacity;
var current = 0;
var steps = $("fieldset").length;

var error_solicitante = "";
var error_telefono_R = "";
var error_calle_R = "";
var error_numero_R = "";
var error_colonia_R = "";
var error_localidad_R = "";
var error_estado_R = "";
var error_pais_R = "";
var error_cp_R = "";
var error_empresa_R = "";
var error_solicitante_R="";
setProgressBar(current);

$(".next").click(function () {

    if ($.trim($("#empresa_solicitante").val()).length == 0) {
        error_solicitante = "El campo es requerido";
        $("#error_empresa_solicitante").text(error_solicitante);
        $("#empresa_solicitante").addClass('has-error');
    }

    if ($.trim($("#empresa_solicitante").val()) == "OTRO") {

        if ($.trim($("#empresa").val()).length == 0) {
            error_empresa_R = "El campo es requerido";
            $("#error_empresa_R").text(error_empresa_R);
            $("#empresa").addClass('has-error');
        }else{
            error_empresa_R = "";
            $("#error_empresa_R").text(error_empresa_R);
            $("#empresa").removeClass('has-error');
        }
    }

    if ($.trim($("#solicitante_R").val()).length == 0) {
        error_solicitante_R = "El campo es requerido";
        $("#error_solicitante_R").text(error_solicitante_R);
        $("#solicitante_R").addClass('has-error');
    }else{
        error_solicitante_R = "";
        $("#error_solicitante_R").text(error_solicitante_R);
        $("#solicitante_R").removeClass('has-error');
    }


    if ($.trim($("#telefono_R").val()).length == 0) {
        error_telefono_R = "El campo es requerido";
        $("#error_telefono_R").text(error_telefono_R);
        $("#telefono_R").addClass('has-error');
    }
    if (isNaN($.trim($("#telefono_R").val())) == true) {
        error_telefono_R = "Ingresa un numero telefonico";
        $('#error_telefono_R').text(error_telefono_R);
        $('#telefono_R').addClass('has-error');
    }
    if ($.trim($("#calle_R").val()).length == 0) {
        error_calle_R = "El campo es requerido";
        $("#error_calle_R").text(error_calle_R);
        $("#calle_R").addClass('has-error');
    }
    if ($.trim($("#numero_R").val()).length == 0) {
        error_numero_R = "El campo es requerido";
        $("#error_numero_R").text(error_numero_R);
        $("#numero_R").addClass('has-error');
    }
    if ($.trim($("#colonia_R").val()).length == 0) {
        error_colonia_R = "El campo es requerido";
        $("#error_colonia_R").text(error_colonia_R);
        $("#colonia_R").addClass('has-error');
    }
    if ($.trim($("#localidad_R").val()).length == 0) {
        error_localidad_R = "El campo es requerido";
        $("#error_localidad_R").text(error_localidad_R);
        $("#localidad_R").addClass('has-error');
    }
    if ($.trim($("#estado_R").val()).length == 0) {
        error_estado_R = "El campo es requerido";
        $("#error_estado_R").text(error_estado_R);
        $("#estado_R").addClass('has-error');
    }
    if ($.trim($("#pais_R").val()).length == 0) {
        error_pais_R = "El campo es requerido";
        $("#error_pais_R").text(error_pais_R);
        $("#pais_R").addClass('has-error');
    }
    if ($.trim($("#cp_R").val()).length == 0) {
        error_cp_R = "El campo es requerido";
        $("#error_cp_R").text(error_cp_R);
        $("#cp_R").addClass('has-error');
    }
    if (
        error_solicitante != ""
        || error_telefono_R != ""
        || error_calle_R != ""
        || error_numero_R != ""
        || error_colonia_R != ""
        || error_localidad_R != ""
        || error_estado_R != ""
        || error_pais_R != ""
        || error_cp_R != ""
        || error_empresa_R != ""
        || error_solicitante_R != ""
    ) {
        return false;
    }

    current_fs = $(this).parent();
    next_fs = $(this).parent().next();

    //Add Class Active
    $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

    //show the next fieldset
    next_fs.show();
    //hide the current fieldset with style
    current_fs.animate(
        {
            opacity: 0,
        },
        {
            step: function (now) {
                // for making fielset appear animation
                opacity = 1 - now;

                current_fs.css({
                    display: "none",
                    position: "relative",
                });
                next_fs.css({
                    opacity: opacity,
                });
            },
            duration: 500,
        }
    );
    setProgressBar(1);
});

/* SEGUNDA SECCION DE CREAR REQUISICION */
var error_empresa_destino = "";
var error_nombre_D = "";
var error_telefono_D = "";
var error_calle_D = "";
var error_numero_D = "";
var error_colonia_D = "";
var error_localidad_D = "";
var error_estado_D = "";
var error_pais_D = "";
/* var error_cp_D = ""; */

$(".next2").click(function () {

    if ($.trim($("#empresa_destino").val()).length == 0) {
        error_empresa_destino = "El campo es requerido";
        $("#error_empresa_destino").text(error_empresa_destino);
        $("#empresa_destino").addClass('has-error');
    }
    if ($.trim($("#nombre_D").val()).length == 0) {
        error_nombre_D = "El campo es requerido";
        $("#error_nombre_D").text(error_nombre_D);
        $("#nombre_D").addClass('has-error');
    }
    if ($.trim($("#telefono_D").val()).length == 0) {
        error_telefono_D = "El campo es requerido";
        $("#error_telefono_D").text(error_telefono_D);
        $("#telefono_D").addClass('has-error');
    }
    if (!isNaN($.trim($("#telefono_D").val())) == false) {
        error_telefono_D = "Ingresa un numero telefonico";
        $('#error_telefono_D').text(error_telefono_D);
        $('#telefono_D').addClass('has-error');
    }
    if ($.trim($("#calle_D").val()).length == 0) {
        error_calle_D = "El campo es requerido";
        $("#error_calle_D").text(error_calle_D);
        $("#calle_D").addClass('has-error');
    }
    if ($.trim($("#numero_D").val()).length == 0) {
        error_numero_D = "El campo es requerido";
        $("#error_numero_D").text(error_numero_D);
        $("#numero_D").addClass('has-error');
    }
    if ($.trim($("#colonia_D").val()).length == 0) {
        error_colonia_D = "El campo es requerido";
        $("#error_colonia_D").text(error_colonia_D);
        $("#colonia_D").addClass('has-error');
    }
    if ($.trim($("#localidad_D").val()).length == 0) {
        error_localidad_D = "El campo es requerido";
        $("#error_localidad_D").text(error_localidad_D);
        $("#localidad_D").addClass('has-error');
    }
    if ($.trim($("#estado_D").val()).length == 0) {
        error_estado_D = "El campo es requerido";
        $("#error_estado_D").text(error_estado_D);
        $("#estado_D").addClass('has-error');
    }
    if ($.trim($("#pais_D").val()).length == 0) {
        error_pais_D = "El campo es requerido";
        $("#error_pais_D").text(error_pais_D);
        $("#pais_D").addClass('has-error');
    }
    if (
        error_empresa_destino != ""
        || error_nombre_D != ""
        || error_telefono_D != ""
        || error_calle_D != ""
        || error_numero_D != ""
        || error_colonia_D != ""
        || error_localidad_D != ""
        || error_estado_D != ""
        || error_pais_D != ""
        // || error_cp_D != "" 
    ) {
        return false;
    }

    current_fs = $(this).parent();
    next_fs = $(this).parent().next();

    //Add Class Active
    $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

    //show the next fieldset
    next_fs.show();
    //hide the current fieldset with style
    current_fs.animate(
        {
            opacity: 0,
        },
        {
            step: function (now) {
                // for making fielset appear animation
                opacity = 1 - now;
                current_fs.css({
                    display: "none",
                    position: "relative",
                });
                next_fs.css({
                    opacity: opacity,
                });
            },
            duration: 500,
        }
    );
    setProgressBar(2);
});



function valida() {
    if ($.trim($("#empresa_solicitante").val()).length > 0) {
        error_solicitante = "";
        $("#error_empresa_solicitante").text(error_solicitante);
        $("#empresa_solicitante").removeClass("has-error");
        if ($.trim($("#empresa_solicitante").val()) != "ALMACEN VILLAHERMOSA") {

            $("#error_calle_R").text("");
            $("#calle_R").removeClass("has-error");
           // $("#calle_R").val("Avenida Industrial");

            $("#error_numero_R").text("");
            $("#numero_R").removeClass("has-error");
           // $("#numero_R").val("Lt. 16");

            $("#error_colonia_R").text("");
            $("#colonia_R").removeClass("has-error");
           // $("#colonia_R").val("Fraccionamiento Industrial");

            $("#error_localidad_R").text("");
            $("#localidad_R").removeClass("has-error");
           // $("#localidad_R").val("Tepotzotlán");

            $("#error_estado_R").text("");
            $("#estado_R").removeClass("has-error");
           // $("#estado_R").val("Estado de México");

            $("#error_pais_R").text("");
            $("#pais_R").removeClass("has-error");
           // $("#pais_R").val("México");

            $("#error_cp_R").text("");
            $("#cp_R").removeClass("has-error");
          //  $("#cp_R").val(54610);

        }
    }
    if ($.trim($("#telefono_R").val()).length > 0) {
        error_telefono_R = "";
        $("#error_telefono_R").text(error_telefono_R);
        $("#telefono_R").removeClass("has-error");
    }
    if ($.trim($("#calle_R").val()).length > 0) {
        error_calle_R = "";
        $("#error_calle_R").text(error_calle_R);
        $("#calle_R").removeClass("has-error");
    }
    if ($.trim($("#numero_R").val()).length > 0) {
        error_numero_R = "";
        $("#error_numero_R").text(error_numero_R);
        $("#numero_R").removeClass("has-error");
    }
    if ($.trim($("#colonia_R").val()).length > 0) {
        error_colonia_R = "";
        $("#error_colonia_R").text(error_colonia_R);
        $("#colonia_R").removeClass("has-error");
    }
    if ($.trim($("#localidad_R").val()).length > 0) {
        error_localidad_R = "";
        $("#error_localidad_R").text(error_localidad_R);
        $("#localidad_R").removeClass("has-error");
    }
    if ($.trim($("#estado_R").val()).length > 0) {
        error_estado_R = "";
        $("#error_estado_R").text(error_estado_R);
        $("#estado_R").removeClass("has-error");
    }
    if ($.trim($("#pais_R").val()).length > 0) {
        error_pais_R = "";
        $("#error_pais_R").text(error_pais_R);
        $("#pais_R").removeClass("has-error");
    }
    if ($.trim($("#cp_R").val()).length > 0) {
        error_cp_R = "";
        $("#error_cp_R").text(error_cp_R);
        $("#cp_R").removeClass("has-error");
    }

    if ($.trim($("#empresa_destino").val()).length > 0) {
        error_destino = "";
        $("#error_empresa_destino").text(error_destino);
        $("#empresa_destino").removeClass("has-error");
    }
    if ($.trim($("#nombre_D").val()).length > 0) {
        error_nombre_D = "";
        $("#error_nombre_D").text(error_nombre_D);
        $("#nombre_D").removeClass("has-error");
    }
    if ($.trim($("#telefono_D").val()).length > 0) {
        error_telefono_D = "";
        $("#error_telefono_D").text(error_telefono_D);
        $("#telefono_D").removeClass("has-error");
    }
    if ($.trim($("#calle_D").val()).length > 0) {
        error_calle_D = "";
        $("#error_calle_D").text(error_calle_D);
        $("#calle_D").removeClass("has-error");
    }
    if ($.trim($("#numero_D").val()).length > 0) {
        error_numero_D = "";
        $("#error_numero_D").text(error_numero_D);
        $("#numero_D").removeClass("has-error");
    }
    if ($.trim($("#colonia_D").val()).length > 0) {
        error_colonia_D = "";
        $("#error_colonia_D").text(error_colonia_D);
        $("#colonia_D").removeClass("has-error");
    }
    if ($.trim($("#localidad_D").val()).length > 0) {
        error_localidad_D = "";
        $("#error_localidad_D").text(error_localidad_D);
        $("#localidad_D").removeClass("has-error");
    }
    if ($.trim($("#estado_D").val()).length > 0) {
        error_estado_D = "";
        $("#error_estado_D").text(error_estado_D);
        $("#estado_D").removeClass("has-error");
    }
    if ($.trim($("#pais_D").val()).length > 0) {
        error_pais_D = "";
        $("#error_pais_D").text(error_pais_D);
        $("#pais_D").removeClass("has-error");
    }
    /* if ($.trim($("#cp_D").val()).length > 0) {
        error_cp_D = "";
        $("#error_cp_D").text(error_cp_D);
        $("#cp_D").removeClass("has-error");
    } */

    if ($("#seguro_si").val() != 0 || $("#seguro_no").val() != 0) {
        error_seguro = "";
        $("#error_seguro").text(error_seguro);
    }
    if ($("#dia_sig").val() != 0 || $("#terrestre").val() != 0) {
        error_tipo_envio = "";
        $("#error_tipo_envio").text(error_tipo_envio);
    }
    if ($("#seguro_si").val() == 1) {
        if ($("#monto").val().length > 0) {
            error_monto = "";
            $("#error_monto").text(error_monto);
        }
    }
    if ($("#recoleccion_si").val() != 0 || $("#recoleccion_no").val() != 0) {
        error_recoleccion = "";
        $("#error_recoleccion").text(error_recoleccion);
    }
    if ($("#obs").val().length > 0) {

        error_obs = "";
        $("#error_obs").text(error_obs);
        $("#obs").removeClass('has-error');
    }
}
var error_seguro = "";
var error_monto = "";
var error_cantidad = "";
var error_peso = "";
var error_base = "";
var error_altura = "";
var error_profundidad = "";
var error_array = "";
var cont = 0;
var arrayPacker = [];
var error_obs = "";
var error_recoleccion = "";
var error_tipo_envio = "";
$("#dia_sig").on("click", function () {
    $("#dia_sig").val("1");
    $("#terrestre").val("0");
});

$("#terrestre").on("click", function () {
    $("#terrestre").val("2");
    $("#dia_sig").val("0");
});

$("#seguro_si").on("click", function () {
    $("#monto").empty();
    $("#seguro_si").val("1");
    $("#seguro_no").val("0");
    $("#monto").append(
        `
     <div class="form-group">
         <label for="monto">Monto:</label>
         <input type="number" step="0.01" min="1" class="form-control" id="monto" name="monto" value="" onchange="valida()">
         <div id="error_monto" class="text-danger"></div>
     </div>
     `);
});

$("#seguro_no").on("click", function () {
    $("#seguro_no").val("2");
    $("#seguro_si").val("0");
    $("#monto").empty();
});

$("#recoleccion_si").on("click", function () {
    $("#recoleccion_no").val("0");
    $("#recoleccion_si").val("1");
});
$("#recoleccion_no").on("click", function () {
    $("#recoleccion_no").val("2");
    $("#recoleccion_si").val("0");
});

$("#btn_agregar").on("click", function (e) {
    e.preventDefault();

    if (arrayPacker.length < 3) {
        if (arrayPacker.length == 0) {
            cont++;
        } else {
            cont++;
            arrayPacker.forEach(item => {
                if (item === cont) {
                    cont++;
                }
            });
        }
        $("#paquetes").append(
            `<div class="row" id="paquete_${cont}">
                 <div class="col-md-2">
                     <div class="form-group">
                         <label for="cantidad_${cont}">Cantidad:</label>
                         <input type="number" min="1" class="form-control" id="cantidad_${cont}" name="cantidad_[]" value="" onchange="validaClon(1)">
                         <div id="error_cantidad_${cont}" class="text-danger"></div>
                     </div>
                 </div>
                 <div class="col-md-2">
                     <div class="form-group">
                         <label for="peso_${cont}">Peso:</label>
                         <input type="number" step="0.01" min="1" class="form-control" id="peso_${cont}" name="peso_[]" value="" onchange="validaClon(1)">
                         <div id="error_peso_${cont}" class="text-danger"></div>
                     </div>
                 </div>
                 <div class="col-md-2">
                     <div class="form-group">
                         <label for="base_${cont}">Base:</label>
                         <input type="number" step="0.01" min="1" class="form-control" id="base_${cont}" name="base_[]" value="" onchange="validaClon(1)">
                         <div id="error_base_${cont}" class="text-danger"></div>
                     </div>
                 </div>
                 <div class="col-md-2">
                     <div class="form-group">
                         <label for="altura_${cont}">Altura:</label>
                         <input type="number" step="0.01" min="1" class="form-control" id="altura_${cont}" name="altura_[]" value="" onchange="validaClon(1)">
                         <div id="error_altura_${cont}" class="text-danger"></div>
                     </div>
                 </div>
                 <div class="col-md-2">
                     <div class="form-group">
                         <label for="profundidad_${cont}">Profundidad:</label>
                         <input type="number" step="0.01" min="1" class="form-control" id="profundidad_${cont}" name="profundidad_[]" value="" onchange="validaClon(1)">
                         <div id="error_profundidad_${cont}" class="text-danger"></div>
                     </div>
                 </div>
                 <div class="col-md-1">
                     <div id="btn_eliminar_${cont}" class="form-group">
                         <button type="button" class="btn btn-danger btn-retirar-item" style="margin-top:33px;" onclick="retirarItem(${cont}) ">
                         <i class="fas fa-times"></i>
                         </button>
                     </div>
                 </div>
                 <div class="col-md-1">
                 </div>
             </div>`
        );
        arrayPacker.push(cont);
        // Se guarda en localStorage despues de JSON stringificarlo 
        sessionStorage.setItem('arrayPacker', JSON.stringify(arrayPacker));
    } else {

        $("#error_paquete").html(
            `<div class="alert alert-warning alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                  <strong>NO SE PERMITEN MAS DE 3 ITEMS ...</strong>
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
$("#btn_documentos").on("click", function (e) {
    e.preventDefault();
    if (arrayPacker.length < 3) {
        if (arrayPacker.length == 0) {
            cont++;
        } else {
            cont++;
            arrayPacker.forEach(item => {
                if (item === cont) {
                    cont++;
                }
            });
        }
        $("#paquetes").append(
            `<div class="row" id="paquete_${cont}">
                 <div class="col-md-2">
                     <div class="form-group">
                         <label for="cantidad_${cont}">Cantidad:</label>
                         <input type="number" min="1" class="form-control" id="cantidad_${cont}" name="cantidad_[]" value="" onchange="validaClon(1)">
                         <div id="error_cantidad_${cont}" class="text-danger"></div>
                     </div>
                 </div>
                 <div class="col-md-1">
                     <div id="btn_eliminar_${cont}" class="form-group">
                         <button type="button" class="btn btn-danger btn-retirar-item" style="margin-top:33px;" onclick="retirarItem(${cont}) ">
                         <i class="fas fa-times"></i>
                         </button>
                     </div>
                 </div>
                 <div class="col-md-2">
                     <div class="form-group">
                         <input type="hidden" step="0.01" min="1" class="form-control" id="peso_${cont}" name="peso_[]" value="0" onchange="validaClon(1)">
                         <div id="error_peso_${cont}" class="text-danger"></div>
                     </div>
                 </div>
                 <div class="col-md-2">
                     <div class="form-group">
                         <input type="hidden" step="0.01" min="1" class="form-control" id="base_${cont}" name="base_[]" value="0" onchange="validaClon(1)">
                         <div id="error_base_${cont}" class="text-danger"></div>
                     </div>
                 </div>
                 <div class="col-md-2">
                     <div class="form-group">
                         <input type="hidden" step="0.01" min="1" class="form-control" id="altura_${cont}" name="altura_[]" value="0" onchange="validaClon(1)">
                         <div id="error_altura_${cont}" class="text-danger"></div>
                     </div>
                 </div>
                 <div class="col-md-2">
                     <div class="form-group">
                         <input type="hidden" step="0.01" min="1" class="form-control" id="profundidad_${cont}" name="profundidad_[]" value="0" onchange="validaClon(1)">
                         <div id="error_profundidad_${cont}" class="text-danger"></div>
                     </div>
                 </div>
                 <div class="col-md-1">
                 </div>
             </div>`
        );
        arrayPacker.push(cont);
        // Se guarda en localStorage despues de JSON stringificarlo 
        sessionStorage.setItem('arrayPacker', JSON.stringify(arrayPacker));
    } else {

        $("#error_paquete").html(
            `<div class="alert alert-warning alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                  <strong>NO SE PERMITEN MAS DE 3 ITEMS ...</strong>
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

function validaClon(cont_) {
    if ($("#cantidad_" + cont_).val().length > 0) {
        error_cantidad = "";
        $("#error_cantidad_" + cont_).text(error_cantidad);
        $("#cantidad_" + cont_).removeClass('has-error');
    }
    if ($("#peso_" + cont_).val().length > 0) {
        error_peso = "";
        $("#error_peso_" + cont_).text(error_peso);
        $("#peso_" + cont_).removeClass('has-error');
    }
    if ($("#base_" + cont_).val().length > 0) {
        error_base = "";
        $("#error_base_" + cont_).text(error_base);
        $("#base_" + cont_).removeClass('has-error');
    }
    if ($("#altura_" + cont_).val().length > 0) {
        error_altura = "";
        $("#error_altura_" + cont_).text(error_altura);
        $("#altura_" + cont_).removeClass('has-error');
    }
    if ($("#profundidad_" + cont_).val().length > 0) {
        error_profundidad = "";
        $("#error_profundidad_" + cont_).text(error_profundidad);
        $("#profundidad_" + cont_).removeClass('has-error');
    }
}
function retirarItem(item) {
    var i = arrayPacker.indexOf(item);
    arrayPacker.splice(i, 1);
    sessionStorage.setItem('arrayPacker', JSON.stringify(arrayPacker));

    $("#paquete_" + item).remove();
    if (cont > 0) {
        cont = 0;
    }

}

/* TERCERA SECCION DE CREAR REQUISICION */

$("#msform").submit(function (e) {
    e.preventDefault();
    console.log(arrayPacker.length);
    if (arrayPacker.length == 0) {
        $("#error_paquete").html(
            `<div class="alert alert-warning alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                  <strong> 1 ITEM MINIMO PARA GENERAR SOLICITUD </strong>
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
    if ($("#seguro_si").val() == 0 && $("#seguro_no").val() == 0) {
        error_seguro = "El campo es requerido";
        $("#error_seguro").text(error_seguro);
    }
    if ($("#dia_sig").val() == 0 && $("#terrestre").val() == 0) {
        error_tipo_envio = "El campo es requerido";
        $("#error_tipo_envio").text(error_tipo_envio);
    }
    if ($("#recoleccion_si").val() == 0 && $("#recoleccion_no").val() == 0) {
        error_recoleccion = "El campo es requerido";
        $("#error_recoleccion").text(error_recoleccion);
    }
    if ($.trim($("#obs").val()).length <= 4) {
        error_obs = "El campo es requerido";
        $("#error_obs").text(error_obs);
        $("#obs").addClass("has-error");
    }
    arrayPacker.forEach(item => {
        if ($("#cantidad_" + item).val().length == 0) {
            error_cantidad = "El campo requerido";
            $("#error_cantidad_" + item).text(error_cantidad);
            $("#cantidad_" + item).addClass('has-error');
        }
        if ($("#peso_" + item).val().length == 0) {
            error_peso = "El campo requerido";
            $("#error_peso_" + item).text(error_peso);
            $("#peso_" + item).addClass('has-error');
        }
        if ($("#base_" + item).val().length == 0) {
            error_base = "El campo requerido";
            $("#error_base_" + item).text(error_base);
            $("#base_" + item).addClass('has-error');
        }
        if ($("#altura_" + item).val().length == 0) {
            error_altura = "El campo requerido";
            $("#error_altura_" + item).text(error_altura);
            $("#altura_" + item).addClass('has-error');
        }
        if ($("#profundidad_" + item).val().length == 0) {
            error_profundidad = "El campo requerido";
            $("#error_profundidad_" + item).text(error_profundidad);
            $("#profundidad_" + item).addClass('has-error');
        }
    });

    if (
        error_seguro != ""
        || error_monto != ""
        || error_cantidad != ""
        || error_peso != ""
        || error_base != ""
        || error_altura != ""
        || error_profundidad != ""
        || error_obs != ""
        || error_recoleccion != ""
        || error_tipo_envio != ""
        || error_array != ""
    ) {
        return false;
    }

    $("#btn_generar").prop("disabled", true);
    setProgressBar(3);

    var data = new FormData($('#msform')[0]);

    $.ajax({
        data: data,
        url: `${urls}paqueteria/crear`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (response) {
            //console.log(response);

            if (response == true) {
                document.getElementById("msform").reset();
                $("#monto").empty();
                $("#paquetes").slideUp("slow", function () {
                    $(".clon").remove();
                });
                cont = 1;
                arrayPacker = [];
                sessionStorage.setItem('arrayPacker', JSON.stringify(arrayPacker));
                $("#seguro_si").val("0");
                $("#seguro_no").val("0");
                $("#recoleccion_si").val("0");
                $("#recoleccion_no").val("0");
                $("#terrestre").val("0");
                $("#dia_sig").val("0");
                $('#seguro_si').prop("checked", false);
                $('#seguro_no').prop("checked", false);
                $('#recoleccion_si').prop("checked", false);
                $('#recoleccion_no').prop("checked", false);
                $('#terrestre').prop("checked", false);
                $('#dia_sig').prop("checked", false);
                $("#personal").removeClass("active");
                $("#confirm").removeClass("active");

                $("#fieldset_final").css({
                    display: "none",
                    position: "relative",
                });
                $("#fieldset_inicial").css({
                    display: "block",
                    position: "relative",
                    opacity: 1,
                });

                setProgressBar(0);
                Swal.fire(
                    "!La Solicitud de Paqueria se ha generado correctamente!",
                    "",
                    "success"
                );
                $("#btn_generar").prop("disabled", false);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador del Sistema",
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
            $("#btn_generar").prop("disabled", false);

        } else if (jqXHR.status == 404) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No se encontró la página solicitada [404]",
            });
            $("#btn_generar").prop("disabled", false);
        } else if (jqXHR.status == 500) {

            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Internal Server Error [500]",
            });
            $("#btn_generar").prop("disabled", false);
        } else if (textStatus === 'parsererror') {

            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Error de análisis JSON solicitado.",
            });
            $("#btn_generar").prop("disabled", false);
        } else if (textStatus === 'timeout') {

            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Time out error.",
            });
            $("#btn_generar").prop("disabled", false);
        } else if (textStatus === 'abort') {

            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ajax request aborted.",
            });

            $("#btn_generar").prop("disabled", false);
        } else {

            alert('Uncaught Error: ' + jqXHR.responseText);
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: `Uncaught Error: ${jqXHR.responseText}`,
            });
            $("#btn_generar").prop("disabled", false);
        }
    });
});

$(".previous").click(function () {
    current_fs = $(this).parent();
    previous_fs = $(this).parent().prev();

    //Remove class active
    $("#progressbar li")
        .eq($("fieldset").index(current_fs))
        .removeClass("active");

    //show the previous fieldset
    previous_fs.show();

    //hide the current fieldset with style
    current_fs.animate(
        {
            opacity: 0,
        },
        {
            step: function (now) {
                // for making fielset appear animation
                opacity = 1 - now;

                current_fs.css({
                    display: "none",
                    position: "relative",
                });
                previous_fs.css({
                    opacity: opacity,
                });
            },
            duration: 500,
        }
    );
    setProgressBar(--current);
});

function setProgressBar(curStep) {
    var percent = parseFloat(100 / steps) * curStep;
    percent = percent.toFixed();
    $(".progress-bar").css("width", percent + "%");
}

$(".submit").click(function () {
    return false;
});