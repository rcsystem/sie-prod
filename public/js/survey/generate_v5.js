/**
 * ARCHIVO MODULO DATOS GENERALES
 * AUTOR:RAFAEL CRUZ AGUILAR
 * 2° AUTOR : HORUS SAMAEL RIVAS PEDRAZA
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
var content = 1;

$(document).ready(function () {
  Swal.fire({
    icon:'info',
    title: 'COMPROBANTES NECESARIOS:',
  html: '<b style="font-size: 22px;"> - Acta de Nacimiento. <BR> - Domicilio. <BR> - Ultimo grado de Estudios. <BR> - CURP. <BR> - RFC Constancia de Situación Fiscal).</b>',
    padding: '1em',
    confirmButtonText:"CONTINUAR",
    confirmButtonColor:"#00A57C",
    background: "#FFF",
    /* `url("../public/images/survey/transporte-y-envio-de-documentos.jpg")` */
    backdrop: `
      rgba(0, 165, 124,0.3)
      url("../public/images/survey/logo_2.png")
      no-repeat
      center 0rem
    `});
    /* 
      url("../public/images/survey/WW-180LogoVert-ESP-FCOLOR.png")
      200px 100px --- no puedo modificar el tamaño
      no-repeat
      center center */
});

// Cuando hacemos click en el boton de retirar
$("#item-duplica").on("click", ".btn-retirar-item", function (e) {
  e.preventDefault();
  $(this).closest(".extras").remove();
  --content;
  return false;
});

// El formulario que queremos replicar
var formUser = $("#form_duplica").clone(true, true).html();

// El encargado de agregar más formularios
$("#btn-agregar-item").click(function (e) {
  e.preventDefault();
  if (content < 5) {
    content++;

    // Agregamos el formulario
    $("#item-duplica").prepend(formUser).show("slow");
    $("#extra_1").attr("id", `extra_${content}`);

    //Editamos el valor del input con data de la sugerencia pulsada
    $("#hijo_1").attr("id", `hijo_(${content})`);
    $("#hijo_fecha_1").attr("id", `hijo_fecha_${content}`);
    $("#hijo_genero_1").attr("id", `hijo_genero_${content}`);
    $("#btn_eliminar_1").attr("id", "btn_eliminar_" + content);


    // Agregamos un boton para retirar el formulario
    $("#btn_eliminar_" + content).append(
      `<div class="item-duplica card-tools" style="margin-top: 2rem;">
                                           
     <button type="button" class="btn btn-danger btn-retirar-item" onclick="">
         <i class="fas fa-times"></i>
     </button>
 </div>`
    );
    // Hacemos focus en el primer input del formulario
    $(`#hijo_${content}`).focus();

    $("#extra_" + content).addClass("extras");
    // Volvemos a cargar todo los plugins que teníamos, dentro de esta función esta el del datepicker assets/js/ini.js
  } else {
    /* Mostrar error */
    $("#resultado").html(
      `<div class="alert alert-warning alert-dismissible" role="alert">
               <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
               </button>
               <strong>SOLO SE PUEDEN REGISTRAR 5 HIJOS EN LA SOLICITUD...</strong>
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

/* CODIGO PARA VALIDAR Y PASAR A LA SIGUIENTE FIELDSETS */

var current_fs, next_fs, previous_fs; //fieldsets
var opacity;
var current = 0;
var steps = $("fieldset").length;

setProgressBar(current);

$(".next").click(function (e) {
  e.preventDefault();

  var num_nomina = $("#num_nomina").val();
  var nombre_usuario = $("#nombre_usuario").val();
  var ape_paterno = $("#ape_paterno").val();
  var ape_materno = $("#ape_materno").val();
  var genero = $("#genero").val();
  var fecha_nacimiento = $("#fecha_nacimiento").val();
  var estado_civil = $("#estado_civil").val();
  var edad_usuario = $("#edad_usuario").val();

  if ($.trim(num_nomina).length == 0) {
    var error_num_nomina = "El campo es requerido";
    $("#error_num_nomina").text(error_num_nomina);
    $("#num_nomina").addClass("has-error");
  } else {
    error_num_nomina = "";
    $("#error_num_nomina").text(error_num_nomina);
    $("#num_nomina").removeClass("has-error");
  }

  if ($.trim(nombre_usuario).length == 0) {
    var error_nombre_usuario = "El campo es requerido";
    $("#error_nombre_usuario").text(error_nombre_usuario);
    $("#nombre_usuario").addClass("has-error");
  } else {
    error_nombre_usuario = "";
    $("#error_nombre_usuario").text(error_nombre_usuario);
    $("#nombre_usuario").removeClass("has-error");
  }

  if ($.trim(ape_paterno).length == 0) {
    var error_ape_paterno = "El campo es requerido";
    $("#error_ape_paterno").text(error_ape_paterno);
    $("#ape_paterno").addClass("has-error");
  } else {
    error_ape_paterno = "";
    $("#error_ape_paterno").text(error_ape_paterno);
    $("#ape_paterno").removeClass("has-error");
  }

  if ($.trim(ape_materno).length == 0) {
    var error_ape_materno = "El campo es requerido";
    $("#error_ape_materno").text(error_ape_materno);
    $("#ape_materno").addClass("has-error");
  } else {
    error_ape_materno = "";
    $("#error_ape_materno").text(error_ape_materno);
    $("#ape_materno").removeClass("has-error");
  }

  if ($.trim(genero).length == 0) {
    var error_genero = "El campo es requerido";
    $("#error_genero").text(error_genero);
    $("#genero").addClass("has-error");
  } else {
    error_genero = "";
    $("#error_genero").text(error_genero);
    $("#genero").removeClass("has-error");
  }

  if ($.trim(edad_usuario).length == 0) {
    var error_edad_usuario = "El campo es requerido";
    $("#error_edad_usuario").text(error_edad_usuario);
    $("#edad_usuario").addClass("has-error");
  } else {
    error_edad_usuario = "";
    $("#error_edad_usuario").text(error_edad_usuario);
    $("#edad_usuario").removeClass("has-error");
  }

  if ($.trim(fecha_nacimiento).length == 0) {
    var error_fecha = "El campo es requerido";
    $("#error_fecha").text(error_fecha);
    $("#fecha_nacimiento").addClass("has-error");
  } else {
    error_fecha = "";
    $("#error_fecha").text(error_fecha);
    $("#fecha_nacimiento").removeClass("has-error");
  }

  if ($.trim(estado_civil).length == 0) {
    var error_estado_civil = "El campo es requerido";
    $("#error_estado_civil").text(error_estado_civil);
    $("#estado_civil").addClass("has-error");
  } else {
    error_estado_civil = "";
    $("#error_estado_civil").text(error_estado_civil);
    $("#estado_civil").removeClass("has-error");
  }

  if ($.trim($("#fecha_ingreso").val()).length == 0) {
    var error_fecha_ingreso = "El campo es requerido";
    $("#error_fecha_ingreso").text(error_fecha_ingreso);
    $("#fecha_ingreso").addClass("has-error");
  } else {
    error_fecha_ingreso = "";
    $("#error_fecha_ingreso").text(error_fecha_ingreso);
    $("#fecha_ingreso").removeClass("has-error");
  }

  if ($.trim($("#curp").val()).length == 0) {
    error_curp = "El campo es requerido";
    $("#error_curp").text(error_curp);
    $("#curp").addClass("has-error");
  } else {
    error_curp = "";
    $("#error_curp").text(error_curp);
    $("#curp").removeClass("has-error");
  }

  if ($.trim($("#rfc").val()).length == 0) {
    error_rfc = "El campo es requerido";
    $("#error_rfc").text(error_rfc);
    $("#rfc").addClass("has-error");
  } else {
    error_rfc = "";
    $("#error_rfc").text(error_rfc);
    $("#rfc").removeClass("has-error");
  }

  if (
    error_num_nomina != "" ||
    error_nombre_usuario != "" ||
    error_ape_paterno != "" ||
    error_ape_materno != "" ||
    error_genero != "" ||
    error_fecha != "" ||
    error_edad_usuario != "" ||
    error_estado_civil != "" ||
    error_fecha_ingreso != "" ||
    error_curp != "" ||
    error_rfc != ""
  ) { return false; }
  // errores

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

$(".next2").click(function (e) {
  e.preventDefault();

  var parentesco = $("#parentesco_1").val();
  var contacto_emergencia = $("#contacto_emergencia_1").val();
  var tel_contacto = $("#tel_contacto_1").val();

  var parentesco2 = $("#parentesco_2").val();
  var contacto_emergencia2 = $("#contacto_emergencia_2").val();
  var tel_contacto2 = $("#tel_contacto_2").val();

  if ($.trim(parentesco).length == 0) {
    error_parentesco_1 = "El campo es requerido";
    $("#error_parentesco_1").text(error_parentesco_1);
    $("#parentesco_1").addClass("has-error");
  } else {
    error_parentesco_1 = "";
    $("#error_parentesco_1").text(error_parentesco_1);
    $("#parentesco_1").removeClass("has-error");
  }

  if ($.trim(contacto_emergencia).length == 0) {
    error_contacto_emergencia1 = "El campo es requerido";
    $("#error_contacto_emergencia_1").text(error_contacto_emergencia1);
    $("#contacto_emergencia_1").addClass("has-error");
  } else {
    error_contacto_emergencia1 = "";
    $("#error_contacto_emergencia_1").text(error_contacto_emergencia1);
    $("#contacto_emergencia_1").removeClass("has-error");
  }

  if ($.trim(tel_contacto).length == 0) {
    var error_tel_contacto1 = "El campo es requerido";
    $("#error_tel_contacto_1").text(error_tel_contacto1);
    $("#tel_contacto_1").addClass("has-error");
  } else {

    var phone = /^([0-9]{2})[-]?([0-9]{2})[-]?([0-9]{2})[-]?([0-9]{2})[-]?([0-9]{2})$/;

    if (tel_contacto.match(phone)) {
      error_tel_contacto1 = "";
      $("#error_tel_contacto_1").text(error_tel_contacto1);
      $("#tel_contacto_1").removeClass("has-error");
    }
    else {
      var error_tel_contacto1 = "El Formato del Teléfono no es Correcto";
      $("#error_tel_contacto_1").text(error_tel_contacto1);
      $("#tel_contacto_1").addClass("has-error");
      return false;
    }

  }

  if ($.trim(parentesco2).length == 0) {
    error_parentesco2 = "El campo es requerido";
    $("#error_parentesco_2").text(error_parentesco2);
    $("#parentesco_2").addClass("has-error");
  } else {
    error_parentesco2 = "";
    $("#error_parentesco_2").text(error_parentesco2);
    $("#parentesco_2").removeClass("has-error");
  }

  if ($.trim(contacto_emergencia2).length == 0) {
    error_contacto_emergencia2 = "El campo es requerido";
    $("#error_contacto_emergencia_2").text(error_contacto_emergencia2);
    $("#contacto_emergencia_2").addClass("has-error");
  } else {
    error_contacto_emergencia2 = "";
    $("#error_contacto_emergencia_2").text(error_contacto_emergencia2);
    $("#contacto_emergencia_2").removeClass("has-error");
  }

  if ($.trim(tel_contacto2).length == 0) {
    var error_tel_contacto2 = "El campo es requerido";
    $("#error_tel_contacto_2").text(error_tel_contacto2);
    $("#tel_contacto_2").addClass("has-error");
  } else {

    var phone = /^([0-9]{2})[-]?([0-9]{2})[-]?([0-9]{2})[-]?([0-9]{2})[-]?([0-9]{2})$/;

    if (tel_contacto2.match(phone)) {
      error_tel_contacto2 = "";
      $("#error_tel_contacto_2").text(error_tel_contacto2);
      $("#tel_contacto_2").removeClass("has-error");
    }
    else {
      var error_tel_contacto2 = "El Formato del Teléfono no es Correcto";
      $("#error_tel_contacto_2").text(error_tel_contacto2);
      $("#tel_contacto_2").addClass("has-error");
      return false;
    }

  }

  if (
    error_parentesco_1 != "" ||
    error_contacto_emergencia1 != "" ||
    error_tel_contacto1 != "" ||
    error_parentesco2 != "" ||
    error_contacto_emergencia2 != "" ||
    error_tel_contacto2 != ""
  ) { return false; }
  // errores

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

/* TERCERA SECCION DE CREAR REQUISICION */

$(".next3").click(function (e) {
  e.preventDefault();
  var estado = $("#estado").val();
  var municipio = $("#municipio").val();
  var colonia = $("#colonia").val();
  var cp = $("#codigo_postal").val();
  var calle = $("#calle").val();
  var num_exterior = $("#num_exterior").val();
  var num_interior = $("#num_interior").val();

  if ($.trim(estado).length == 0) {
    var error_estado = "El campo es requerido";
    $("#error_estado").text(error_estado);
    $("#estado").addClass("has-error");
  } else {
    error_estado = "";
    $("#error_estado").text(error_estado);
    $("#estado").removeClass("has-error");
  }

  if ($.trim(municipio).length == 0) {
    var error_municipio = "El campo es requerido";
    $("#error_municipio").text(error_municipio);
    $("#municipio").addClass("has-error");
  } else {
    error_municipio = "";
    $("#error_municipio").text(error_municipio);
    $("#municipio").removeClass("has-error");
  }

  if ($.trim(colonia).length == 0) {
    var error_colonia = "El campo es requerido";
    $("#error_colonia").text(error_colonia);
    $("#colonia").addClass("has-error");
  } else {
    error_colonia = "";
    $("#error_colonia").text(error_colonia);
    $("#colonia").removeClass("has-error");
  }

  if ($.trim(cp).length == 0) {
    var error_cp = "El campo es requerido";
    $("#error_cp").text(error_cp);
    $("#codigo_postal").addClass("has-error");
  } else {
    error_cp = "";
    $("#error_cp").text(error_cp);
    $("#codigo_postal").removeClass("has-error");
  }

  if ($.trim(calle).length == 0) {
    var error_calle = "El campo es requerido";
    $("#error_calle").text(error_calle);
    $("#calle").addClass("has-error");
  } else {
    error_calle = "";
    $("#error_calle").text(error_calle);
    $("#calle").removeClass("has-error");
  }

  if ($.trim(num_exterior).length == 0) {
    var error_num_exterior = "El campo es requerido";
    $("#error_num_exterior").text(error_num_exterior);
    $("#num_exterior").addClass("has-error");
  } else {
    error_num_exterior = "";
    $("#error_num_exterior").text(error_num_exterior);
    $("#num_exterior").removeClass("has-error");
  }

  if ($.trim(num_interior).length == 0) {
    var error_num_interior = "El campo es requerido";
    $("#error_num_interior").text(error_num_interior);
    $("#num_interior").addClass("has-error");
  } else {
    error_num_interior = "";
    $("#error_num_interior").text(error_num_interior);
    $("#num_interior").removeClass("has-error");
  }

  if (
    error_estado != "" ||
    error_municipio != "" ||
    error_colonia != "" ||
    error_calle != "" ||
    error_num_exterior != "" ||
    error_num_interior != "" ||
    error_cp != ""
  ) { return false; }
  // errores

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
  setProgressBar(3);
});

/* CUARTA SECCION DE CREAR REQUISICION */

$(".next4").click(function (e) {
  e.preventDefault();
  var conyuge = $("#conyuge").val();
  var edad_conyuge = $("#edad_conyuge").val();
  var ocupacion_conyuge = $("#ocupacion_conyuge").val();
  var tel_conyuge = $("#tel_conyuge").val();
  var estado_civil = $("#estado_civil").val();
  if (estado_civil == "Unión Libre" || estado_civil == "Casado") {
    if ($.trim(conyuge).length == 0) {
      error_conyuge = "El campo es requerido";
      $("#error_conyuge").text(error_conyuge);
      $("#conyuge").addClass("has-error");
    } else {
      error_conyuge = "";
      $("#error_conyuge").text(error_conyuge);
      $("#conyuge").removeClass("has-error");
    }

    if ($.trim(edad_conyuge).length == 0) {
      error_edad_conyuge = "El campo es requerido";
      $("#error_edad_conyuge").text(error_edad_conyuge);
      $("#edad_conyuge").addClass("has-error");
    } else {
      error_edad_conyuge = "";
      $("#error_edad_conyuge").text(error_edad_conyuge);
      $("#edad_conyuge").removeClass("has-error");
    }

    if ($.trim(ocupacion_conyuge).length == 0) {
      error_ocupacion_conyuge = "El campo es requerido";
      $("#error_ocupacion_conyuge").text(error_ocupacion_conyuge);
      $("#ocupacion_conyuge").addClass("has-error");
    } else {
      error_ocupacion_conyuge = "";
      $("#error_ocupacion_conyuge").text(error_ocupacion_conyuge);
      $("#ocupacion_conyuge").removeClass("has-error");
    }

    if ($.trim(tel_conyuge).length == 0) {
      var error_tel_conyuge = "El campo es requerido";
      $("#error_tel_conyuge").text(error_tel_conyuge);
      $("#tel_conyuge").addClass("has-error");
    } else {

      var phone = /^([0-9]{2})[-]?([0-9]{2})[-]?([0-9]{2})[-]?([0-9]{2})[-]?([0-9]{2})$/;

      if (tel_conyuge.match(phone)) {
        error_tel_conyuge = "";
        $("#error_tel_conyuge").text(error_tel_conyuge);
        $("#tel_conyuge").removeClass("has-error");
      }
      else {
        var error_tel_conyuge = "El Formato del Teléfono no es Correcto";
        $("#error_tel_conyuge").text(error_tel_conyuge);
        $("#tel_conyuge").addClass("has-error");
        return false;
      }

    }
  } else {
    $("#error_conyuge").text("");
    error_conyuge = "";
    $("#conyuge").removeClass("has-error");
    $("#error_edad_conyuge").text("");
    error_edad_conyuge = "";
    $("#edad_conyuge").removeClass("has-error");
    $("#error_ocupacion_conyuge").text("");
    error_ocupacion_conyuge = "";
    $("#ocupacion_conyuge").removeClass("has-error");
    $("#error_tel_conyuge").text("");
    error_tel_conyuge = "";
    $("#tel_conyuge").removeClass("has-error");
  }

  if (
    error_conyuge != "" ||
    error_edad_conyuge != "" ||
    error_ocupacion_conyuge != "" ||
    error_tel_conyuge != ""
  ) { return false; }
  // errores
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
  setProgressBar(4);
});

/* QUINTA SECCION DE CREAR REQUISICION */

$(".next5").click(function (e) {
  e.preventDefault();
  var padres_1 = $("#padres_1").val();
  var padres_fecha_1 = $("#padres_fecha_1").val();
  var padres_genero_1 = $("#padres_genero_1").val();
  var padres_edad_1 = $("#padres_edad_1").val();
  var estatus_padres_1 = $("#estatus_padres_1").val();


  var padres_2 = $("#padres_2").val();
  var padres_fecha_2 = $("#padres_fecha_2").val();
  var padres_genero_2 = $("#padres_genero_2").val();
  var padres_edad_2 = $("#padres_edad_2").val();
  var estatus_padres_2 = $("#estatus_padres_2").val();

  if ($.trim(padres_1).length == 0) {
    error_padres_1 = "El campo es requerido";
    $("#error_padres_1").text(error_padres_1);
    $("#padres_1").addClass("has-error");
  } else {
    error_padres_1 = "";
    $("#error_padres_1").text(error_padres_1);
    $("#padres_1").removeClass("has-error");
  }

  if ($.trim(padres_fecha_1).length == 0) {
    error_padres_fecha_1 = "El campo es requerido";
    $("#error_padres_fecha_1").text(error_padres_fecha_1);
    $("#padres_fecha_1").addClass("has-error");
  } else {
    error_padres_fecha_1 = "";
    $("#error_padres_fecha_1").text(error_padres_fecha_1);
    $("#padres_fecha_1").removeClass("has-error");
  }

  if ($.trim(padres_genero_1).length == 0) {
    error_padres_genero_1 = "El campo es requerido";
    $("#error_padres_genero_1").text(error_padres_genero_1);
    $("#padres_genero_1").addClass("has-error");
  } else {
    error_padres_genero_1 = "";
    $("#error_padres_genero_1").text(error_padres_genero_1);
    $("#padres_genero_1").removeClass("has-error");
  }

  if ($.trim(estatus_padres_1).length == 0) {
    error_estatus_padres_1 = "El campo es requerido";
    $("#error_estatus_padres_1").text(error_estatus_padres_1);
    $("#estatus_padres_1").addClass("has-error");
  } else {
    error_estatus_padres_1 = "";
    $("#error_estatus_padres_1").text(error_estatus_padres_1);
    $("#estatus_padres_1").removeClass("has-error");
  }

  if ($.trim(estatus_padres_1) == "Vive") {

    if ($.trim(padres_edad_1).length == 0) {
      error_padres_edad_1 = "El campo es requerido";
      $("#error_padres_edad_1").text(error_padres_edad_1);
      $("#padres_edad_1").addClass("has-error");
      return false;
    } else {
      error_padres_edad_1 = "";
      $("#error_padres_edad_1").text(error_padres_edad_1);
      $("#padres_edad_1").removeClass("has-error");
    }

  }
  /*------------------------------------------------------------------ */
  if ($.trim(padres_2).length == 0) {
    error_padres_2 = "El campo es requerido";
    $("#error_padres_2").text(error_padres_2);
    $("#padres_2").addClass("has-error");
  } else {
    error_padres_2 = "";
    $("#error_padres_2").text(error_padres_2);
    $("#padres_2").removeClass("has-error");
  }

  if ($.trim(padres_fecha_2).length == 0) {
    error_padres_fecha_2 = "El campo es requerido";
    $("#error_padres_fecha_2").text(error_padres_fecha_2);
    $("#padres_fecha_2").addClass("has-error");
  } else {
    error_padres_fecha_2 = "";
    $("#error_padres_fecha_2").text(error_padres_fecha_2);
    $("#padres_fecha_2").removeClass("has-error");
  }

  if ($.trim(padres_genero_2).length == 0) {
    error_padres_genero_2 = "El campo es requerido";
    $("#error_padres_genero_2").text(error_padres_genero_2);
    $("#padres_genero_2").addClass("has-error");
  } else {
    error_padres_genero_2 = "";
    $("#error_padres_genero_2").text(error_padres_genero_2);
    $("#padres_genero_2").removeClass("has-error");
  }

  if ($.trim(estatus_padres_2).length == 0) {
    error_estatus_padres_2 = "El campo es requerido";
    $("#error_estatus_padres_2").text(error_estatus_padres_2);
    $("#estatus_padres_2").addClass("has-error");
  } else {
    error_estatus_padres_2 = "";
    $("#error_estatus_padres_2").text(error_estatus_padres_2);
    $("#estatus_padres_2").removeClass("has-error");
  }

  if ($.trim(estatus_padres_2) == "Vive") {

    if ($.trim(padres_edad_2).length == 0) {
      error_padres_edad_2 = "El campo es requerido";
      $("#error_padres_edad_2").text(error_padres_edad_2);
      $("#padres_edad_2").addClass("has-error");
      return false;

    } else {
      error_padres_edad_2 = "";
      $("#error_padres_edad_2").text(error_padres_edad_2);
      $("#padres_edad_2").removeClass("has-error");
    }

  }

  if (
    error_padres_1 != "" ||
    error_padres_fecha_1 != "" ||
    error_padres_genero_1 != "" ||
    error_estatus_padres_1 != "" ||
    error_padres_2 != "" ||
    error_padres_fecha_2 != "" ||
    error_padres_genero_2 != "" ||
    error_estatus_padres_2 != ""
  ) { return false; }
  // errores

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
  setProgressBar(5);
});


/* SEXTA SECCION DE CREAR REQUISICION */

/* var pagina = "";
$(".next6").click(function (e) {
  e.preventDefault();

  // errores
  pagina = 7;
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
  setProgressBar(6);
}); */


// documentos 
$("#msform").submit(function (e) {
  e.preventDefault();

  if ($.trim($("#escolaridad").val()).length == 0) {
    error_esc = "El campo es requerido";
    $("#error_esc").text(error_esc);
    $("#escolaridad").addClass("has-error");
  } else {
    error_esc = "";
    $("#error_esc").text(error_esc);
    $("#escolaridad").removeClass("has-error");
  }
  if ($("#escolaridad").val() == "Licenciatura" || $("#escolaridad").val() == "Ingenieria" || $("#escolaridad").val() == "Maestria" || $("#escolaridad").val() == "Doctorado" || $("#escolaridad").val() == "Especialidad" || $("#escolaridad").val() == "Bachillerato Técnico") {
    if ($("#tipo_estudio").val().length == 0) {
      error_esc = "El campo es requerido";
      $("#error_tipo_estudio").text(error_esc);
      $("#tipo_estudio").addClass("has-error");
    } else {
      error_esc = "";
      $("#error_tipo_estudio").text(error_esc);
      $("#tipo_estudio").removeClass("has-error");
    }
  }

  if (error_esc != "") {
    return false;
  }
  // errores
  $("#btn_msform").prop("disabled", true);
  setProgressBar(6);

  let timerInterval = Swal.fire({ //se le asigna un nombre al swal
    title: 'Guardando!',
    html: 'Espere unos Segundos.',
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });
  let dataString = new FormData($("#msform")[0]);
  $.ajax({
    url: `${urls}permisos/info_personal`, //archivo que recibe la peticion
    type: "POST",
    data: dataString, //datos que se envian a traves de ajax
    processData: false,
    contentType: false,
    cache: false,
    dataType: "json",
    success: function (response) {
      $("#btn_msform").prop("disabled", false);
      Swal.close(timerInterval); // cierra el swal en ejecucion (nombre del swal)
      if (response != false) {
        $('#msform').trigger("reset");  //Limpiar el formulario
        /* $("#ingles_no").attr('style', ''); $("#ingles_si").attr('style', '');
        $("#cv_no").attr('style', ''); $("#cv_si").attr('style', '');
        $("#diplimas_div").empty(); $("#cusos_div").empty();
        arrayDiplomas = []; arrayCursos = [];
        contDiploma = 0; contCursos = 0; */
        /* pagina = ""; */
        $("#fieldset_final").css({
          display: "none",
          position: "relative",
        });
        $("#fieldset_inicial").css({
          display: "block",
          position: "relative",
          opacity: 1,
        });
        //Remove class active
        $("#progressbar li").removeClass("active");
        $("#item-duplica").slideUp("slow", function () {
          $(".extras").remove()
        });
        setProgressBar(0);
        Swal.fire({
          icon: 'success',
          title: 'DATOS REGISTRADOS',
          text: "!Pulsa 'CONTINUAR' para subir tus comprobantes!",
          confirmButtonText: "CONTINUAR",
          allowOutsideClick: false,
        }).then((result) => {
          setTimeout(function () {
            location.href = `${urls}encuesta/comprobantes`;
          }, 100);
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador del Sistema",
        });
      }
    },
    error: function (jqXHR, status, error) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Algo salió Mal! Contactar con el Administrador",
      });
      console.log("Mal Revisa entro en el error: " + error);
    },
  });
});

$(".previous").click(function (e) {
  e.preventDefault();
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

function validaNumericos(event) {
  return event.charCode >= 48 && event.charCode <= 57 ? true : false;
}

/* Ponemos evento blur a la escucha sobre id nombre en id cliente. */
$("#msform").on("blur", '#num_nomina', function () {
  /* Obtenemos el valor del campo */
  var num_nomina = this.value;

  let data = new FormData();

  data.append("num_nomina", num_nomina);

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}usuarios/info_usuario`, //archivo que recibe la peticion
    type: "POST", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    dataType: "json",
    async: true,
    success: function (resp) {
      // Limpiamos el select

      resp.forEach(value => {
        $("#nombre_usuario").val(`${value.name}`);
        $("#error_nombre_usuario").text("");
        $("#nombre_usuario").removeClass('has-error');

        $("#ape_paterno").val(`${value.surname}`);
        $("#error_ape_paterno").text("");
        $("#ape_paterno").removeClass('has-error');

        $("#ape_materno").val(`${value.second_surname}`);
        $("#error_ape_materno").text("");
        $("#ape_materno").removeClass('has-error');

        $("#fecha_ingreso").val(`${value.date_admission}`);
        $("#error_fecha_ingreso").text("");
        $("#fecha_ingreso").removeClass('has-error');
      });
    },
    error: function () {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ocurrio un error en el servidor! Contactar con el Administrador",
      });
    },
  });
});

//Me creo una funcion para al cambiar el select me agregue un input para poder agregar la carrera que estan buscando
$("#escolaridad").on("change", function (e) {
  e.preventDefault();
  $("#error_esc").text("");
  $("#escolaridad").removeClass("has-error");
  let escolaridad = $("#escolaridad").val();

  if (escolaridad == "Licenciatura" || escolaridad == "Ingenieria" || escolaridad == "Maestria" || escolaridad == "Doctorado" || escolaridad == "Especialidad" || escolaridad == "Bachillerato Técnico") {
    $("#tipo_escolaridad").empty();
    $("#tipo_escolaridad").addClass("col-md-4");
    if (escolaridad == "Bachillerato Técnico") { escolaridad = "Técnico"; }
    $("#tipo_escolaridad").append(`<div class="form-group">
      <label for="tipo_estudio">${escolaridad} en:</label>
      <input type="text" class="form-control" id="tipo_estudio" name="tipo_estudio" placeholder="Ejemplo: Lic.Administración" onchange="validar()">
      <div id="error_tipo_estudio" class="request-error text-danger"></div>
     </div>`);
  } else {
    $("#tipo_escolaridad").empty();
    $("#tipo_escolaridad").removeClass("col-md-4");
  }
  // Guardamos el select de cursos
});

//Me creo una funcion para al cambiar el select me agregue un input para poder agregar la carrera que estan buscando
$("#estatus_padres_1").on("change", function () {
  let estatus = $("#estatus_padres_1").val();

  if (estatus == "Vive") {
    $("#status_padres_1").empty();
    $("#status_padres_1").addClass("col-md-2");
    campo = `<div class="form-group">
                 <label for="padres_edad_1">Edad:</label>
                 <input type="number" min="1" class="form-control" id="padres_edad_1" name="padres_edad[]" onchange="validar()">
                 <div id="error_padres_edad_1" class="request-error text-danger"></div>
             </div>`;
    $("#status_padres_1").append(campo);
  } else {
    $("#status_padres_1").empty();
    $("#status_padres_1").append(`
     <input type="hidden" class="form-control" id="padres_edad_1" name="padres_edad[]" value="0">`);
    $("#status_padres_1").removeClass("col-md-2");
  }
});

//Me creo una funcion para al cambiar el select me agregue un input para poder agregar la carrera que estan buscando
$("#estatus_padres_2").on("change", function () {
  let estatus = $("#estatus_padres_2").val();
  if (estatus == "Vive") {
    $("#status_padres_2").empty();
    $("#status_padres_2").addClass("col-md-2");
    campo = `<div class="form-group">
                 <label for="padres_edad_2">Edad:</label>
                 <input type="number" min="1" class="form-control" id="padres_edad_2" name="padres_edad[]" onchange="validar()">
                 <div id="error_padres_edad_2" class="request-error text-danger"></div>
             </div>`;
    $("#status_padres_2").append(campo);
  } else {
    $("#status_padres_2").empty();
    $("#status_padres_2").append(`
     <input type="hidden" class="form-control" id="padres_edad_2" name="padres_edad[]" value="0">`);
    $("#status_padres_2").removeClass("col-md-2");
  }
  // Guardamos el select de cursos
});

function validar() {
  if ($("#num_nomina").val().length > 0) {
    $("#error_num_nomina").text("");
    $("#num_nomina").removeClass('has-error');
  }
  if ($("#nombre_usuario").val().length > 0) {
    $("#error_nombre_usuario").text("");
    $("#nombre_usuario").removeClass('has-error');
  }
  if ($("#ape_paterno").val().length > 0) {
    $("#error_ape_paterno").text("");
    $("#ape_paterno").removeClass('has-error');
  }
  if ($("#ape_materno").val().length > 0) {
    $("#error_ape_materno").text("");
    $("#ape_materno").removeClass('has-error');
  }
  if ($("#fecha_nacimiento").val().length > 0) {
    $("#error_fecha").text("");
    $("#fecha_nacimiento").removeClass('has-error');
  }
  if ($("#fecha_ingreso").val().length > 0) {
    $("#error_fecha_ingreso").text("");
    $("#fecha_ingreso").removeClass("has-error");
  }
  if ($("#genero").val().length > 0) {
    $("#error_genero").text("");
    $("#genero").removeClass('has-error');
  }
  if ($("#edad_usuario").val().length > 0) {
    $("#error_edad_usuario").text("");
    $("#edad_usuario").removeClass('has-error');
  }
  if ($("#curp").val().length > 0) {
    $("#error_curp").text("");
    $("#curp").removeClass('has-error');
  }
  if ($("#rfc").val().length > 0) {
    $("#error_rfc").text("");
    $("#rfc").removeClass('has-error');
  }
  if ($("#estado_civil").val().length > 0) {
    $("#error_estado_civil").text("");
    $("#estado_civil").removeClass('has-error');
  }

  if ($("#parentesco_1").val().length > 0) {
    $("#error_parentesco_1").text("");
    $("#parentesco_1").removeClass('has-error');
  }
  if ($("#contacto_emergencia_1").val().length > 0) {
    $("#error_contacto_emergencia_1").text("");
    $("#contacto_emergencia_1").removeClass('has-error');
  }
  if ($("#tel_contacto_1").val().length > 0) {
    $("#error_tel_contacto_1").text("");
    $("#tel_contacto_1").removeClass('has-error');
  }
  if ($("#parentesco_2").val().length > 0) {
    $("#error_parentesco_2").text("");
    $("#parentesco_2").removeClass('has-error');
  }
  if ($("#contacto_emergencia_2").val().length > 0) {
    $("#error_contacto_emergencia_2").text("");
    $("#contacto_emergencia_2").removeClass('has-error');
  }
  if ($("#tel_contacto_2").val().length > 0) {
    $("#error_tel_contacto_2").text("");
    $("#tel_contacto_2").removeClass('has-error');
  }

  if ($("#calle").val().length > 0) {
    $("#error_calle").text("");
    $("#calle").removeClass('has-error');
  }
  if ($("#num_exterior").val().length > 0) {
    $("#error_num_exterior").text("");
    $("#num_exterior").removeClass('has-error');
  }
  if ($("#num_interior").val().length > 0) {
    $("#error_num_interior").text("");
    $("#num_interior").removeClass('has-error');
  }
  if ($("#colonia").val().length > 0) {
    $("#error_colonia").text("");
    $("#colonia").removeClass('has-error');
  }
  if ($("#municipio").val().length > 0) {
    $("#error_municipio").text("");
    $("#municipio").removeClass('has-error');
  }
  if ($("#estado").val().length > 0) {
    $("#error_estado").text("");
    $("#estado").removeClass('has-error');
  }
  if ($("#codigo_postal").val().length > 0) {
    $("#error_cp").text("");
    $("#codigo_postal").removeClass('has-error');
  }

  if ($("#conyuge").val().length > 0) {
    $("#error_conyuge").text("");
    $("#conyuge").removeClass('has-error');
  }
  if ($("#edad_conyuge").val().length > 0) {
    $("#error_edad_conyuge").text("");
    $("#edad_conyuge").removeClass('has-error');
  }
  if ($("#ocupacion_conyuge").val().length > 0) {
    $("#error_ocupacion_conyuge").text("");
    $("#ocupacion_conyuge").removeClass('has-error');
  }
  if ($("#tel_conyuge").val().length > 0) {
    $("#error_tel_conyuge").text("");
    $("#tel_conyuge").removeClass('has-error');
  }

  if ($("#padres_1").val().length > 0) {
    $("#error_padres_1").text("");
    $("#padres_1").removeClass('has-error');
  }
  if ($("#padres_fecha_1").val().length > 0) {
    $("#error_padres_fecha_1").text("");
    $("#padres_fecha_1").removeClass('has-error');
  }
  if ($("#padres_genero_1").val().length > 0) {
    $("#error_padres_genero_1").text("");
    $("#padres_genero_1").removeClass('has-error');
  }
  if ($("#estatus_padres_1").val().length > 0) {
    $("#error_estatus_padres_1").text("");
    $("#estatus_padres_1").removeClass('has-error');
  }
  setTimeout(function () {
    if ($.trim($("#estatus_padres_1").val()) == "Vive") {
      if ($("#padres_edad_1").val().length > 0) {
        $("#error_padres_edad_1").text("");
        $("#padres_edad_1").removeClass("has-error");
      }
    }
    if ($.trim($("#estatus_padres_2").val()) == "Vive") {
      if ($("#padres_edad_2").val().length > 0) {
        $("#error_padres_edad_2").text("");
        $("#padres_edad_2").removeClass("has-error");
      }
    }
  }, 100);
  if ($("#padres_2").val().length > 0) {
    $("#error_padres_2").text("");
    $("#padres_2").removeClass('has-error');
  }
  if ($("#padres_fecha_2").val().length > 0) {
    $("#error_padres_fecha_2").text("");
    $("#padres_fecha_2").removeClass('has-error');
  }
  if ($("#padres_genero_2").val().length > 0) {
    $("#error_padres_genero_2").text("");
    $("#padres_genero_2").removeClass('has-error');
  }
  if ($("#estatus_padres_2").val().length > 0) {
    $("#error_estatus_padres_2").text("");
    $("#estatus_padres_2").removeClass('has-error');
  }
  if ($("#escolaridad").val() == "Licenciatura" || $("#escolaridad").val() == "Ingenieria") {
    if ($("#tipo_estudio").val().length > 0) {
      $("#error_tipo_estudio").text("");
      $("#tipo_estudio").removeClass("has-error");
    }
  }


  // aqui
}


