/**
 * ARCHIVO MODULO COFFEE
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EDITOR: HORUS SAMAEL RIVAS PEDRAZA
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
//var inputVacaciones = false;
$(document).ready(function () {
  fechaSolicitud();
});

const today = new Date();

function fechaSolicitud() {

   inputFecha = flatpickr("#fecha_coffee", {
    locale: "es",
    dateFormat: "Y-m-d",
    minDate: today.fp_incr(1),
    disable: [

      function (date) {
        // Deshabilitar los domingos (0 es domingo, 1 es lunes, ...)
        if (date.getDay() === 0 || date.getDay() === 6) {
          return true; // Deshabilitar sábado y domingo si #tipo es 2
        }
        return false;
      }
    ],

    onChange: function (selectedDates, dateStr, instance) {
      selectedDays = selectedDates.length;
    }
  });
}

sessionStorage.setItem("menu", 0);

$("#coffee_break").on("submit", function (e) {
  e.preventDefault();
  var menus = parseInt(sessionStorage.menu);
  var sala_coffee = $("#sala_coffee").val();
  var motivo_coffee = $("#motivo_coffee").val();
  var fecha_coffee = $("#fecha_coffee").val();
  var horario_coffee = $("#horario_coffee").val();
  var no_personas = $("#no_personas").val();
  var coffee_observaciones = $("#coffee_observaciones").val();
  var menu_especial = $("#menu_si").val();

  if ($.trim(sala_coffee).length == 0) {
    var error_sala = "El campo es requerido";
    $("#error_sala").text(error_sala);
    $("#sala_coffee").addClass("has-error");
  } else {
    error_sala = "";
    $("#error_sala").text(error_sala);
    $("#sala_coffee").removeClass("has-error");
  }

  if ($.trim(motivo_coffee).length == 0) {
    var error_motivo = "El campo es requerido";
    $("#error_motivo").text(error_motivo);
    $("#motivo_coffee").addClass("has-error");
  } else {
    error_motivo = "";
    $("#error_motivo").text(error_motivo);
    $("#motivo_coffee").removeClass("has-error");
  }

  if ($.trim(fecha_coffee).length == 0) {
    var error_fecha = "El campo es requerido";
    $("#error_fecha").text(error_fecha);
    $("#fecha_coffee").addClass("has-error");
  } else {
    error_fecha = "";
    $("#error_fecha").text(error_fecha);
    $("#fecha_coffee").removeClass("has-error");
  }

  if ($.trim(horario_coffee).length == 0) {
    var error_horario = "El campo es requerido";
    $("#error_horario").text(error_horario);
    $("#horario_coffee").addClass("has-error");
  } else {
    error_horario = "";
    $("#error_horario").text(error_horario);
    $("#horario_coffee").removeClass("has-error");
  }

  if ($.trim(no_personas).length == 0) {
    var error_personas = "El campo es requerido";
    $("#error_personas").text(error_personas);
    $("#no_personas").addClass("has-error");
  } else {
    error_personas = "";
    $("#error_personas").text(error_personas);
    $("#no_personas").removeClass("has-error");
  }

  if ($.trim(menu_especial).length == 0) {
    var error_menus = "El campo es requerido";
    $("#error_menus").text(error_menus);
    $("#menu_si").addClass("has-error");

  } else {
    error_menus = "";
    $("#error_menus").text(error_menus);
    $("#menu_si").removeClass("has-error");
  }
  if (!$('#agua').is(':checked')
    && !$('#refresco').is(':checked')
    && !$('#cafe').is(':checked')
    && !$('#galletas').is(':checked')) {
    var error_checkbox = "El campo es requerido";
    $("#error_checkbox").text(error_checkbox);
  }
  else {
    error_checkbox = "";
    $("#error_checkbox").text(error_checkbox);
  }
  error_select_menus = "";

  if ($("#menu_si").val() == 1) {
    if (menus == 0) {
      console.log(menus, "escoje un menu");
      var error_select_menus = "Selecciona un Menu";
      $("#error_menus").text(error_select_menus);

    }
  }
  if (
    error_sala != "" ||
    error_motivo != "" ||
    error_fecha != "" ||
    error_horario != "" ||
    error_personas != "" ||
    error_menus != "" ||
    error_checkbox != "" ||
    error_select_menus != ""
  ) {
    return false;
  }
  $("#guardar_coffee").prop("disabled", true);

  let data = new FormData();
  if ($('#agua').is(':checked')) {
    data.append("menu_coffee[]", $("#agua").val());
  }
  /* if ($('#jarra').is(':checked')) {
    data.append("menu_coffee[]", $("#jarra").val());
   } */
  if ($('#refresco').is(':checked')) {
    data.append("menu_coffee[]", $("#refresco").val());
  }
  if ($('#cafe').is(':checked')) {
    data.append("menu_coffee[]", $("#cafe").val());
  }
  if ($('#galletas').is(':checked')) {
    data.append("menu_coffee[]", $("#galletas").val());
  }
  data.append("sala_coffee", sala_coffee);
  data.append("motivo_coffee", motivo_coffee);
  data.append("fecha_coffee", fecha_coffee);
  data.append("horario_coffee", horario_coffee);
  data.append("no_personas", no_personas);
  data.append("coffee_observaciones", coffee_observaciones);
  if ($("#menu_si").val() == 1) {
    data.append("menu_especial", menus);
  } else {
    data.append("menu_especial", 0);
  }
  //var dataString = $("#coffee_break").serialize();
  //alert('Datos serializados: '+dataString);
  $.ajax({
    data: data,
    url: `${urls}cafeteria/insertar`,
    type: "POST",
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    dataType: "json",
    success: function (resp) {
      //console.log(resp);
      if (resp) {
        /* elimina todos los form-items duplicados */
        inputFecha.clear();
        sessionStorage.setItem("menu", 0);
        $("#sala_coffee").val("");
        $("#motivo_coffee").val("");
        $("#fecha_coffee").val("");
        $("#horario_coffee").val("");
        $("#no_personas").val("");
        $("#coffee_observaciones").val("");
        $("#menus").empty();
        $("#menu_si_").removeClass("active");
        $("#menu_no_").removeClass("active");
        $(".active").removeClass("active");
        $("input[name='menu-coffe']").prop("checked", false);
        $("#menu_no").val("");
        $("#menu_si").val("");
        $("#guardar_coffee").prop("disabled", false);
        Swal.fire("!Se ha Registrado la Solicitud!", "", "success");
      } else {
        $("#guardar_coffee").prop("disabled", false);
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
    error: function () {
      $("#guardar_coffee").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ocurrio un error en el servidor! Contactar con el Administrador",
      });
    },
  }).fail(function (jqXHR, textStatus, errorThrown) {

    if (jqXHR.status === 0) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Fallo de conexión: ​​Verifique la red.",
      });
      $("#guardar_coffee").prop("disabled", false);

    } else if (jqXHR.status == 404) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No se encontró la página solicitada [404]",
      });
      $("#guardar_coffee").prop("disabled", false);
    } else if (jqXHR.status == 500) {

      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Internal Server Error [500]",
      });
      $("#guardar_coffee").prop("disabled", false);
    } else if (textStatus === 'parsererror') {

      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Error de análisis JSON solicitado.",
      });
      $("#guardar_coffee").prop("disabled", false);
    } else if (textStatus === 'timeout') {

      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Time out error.",
      });
      $("#guardar_coffee").prop("disabled", false);
    } else if (textStatus === 'abort') {

      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ajax request aborted.",
      });

      $("#guardar_coffee").prop("disabled", false);
    } else {

      alert('Uncaught Error: ' + jqXHR.responseText);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: `Uncaught Error: ${jqXHR.responseText}`,
      });
      $("#guardar_coffee").prop("disabled", false);
    }
  });
});

function validar() {
  if ($("input[name='menu-coffe']").is(':checked')) {
    $("#error_checkbox").text("");
  }
  if ($("#sala_coffee").val().length > 0) {
    $("#error_sala").text("");
    $("#sala_coffee").removeClass("has-error");
  }
  if ($("#motivo_coffee").val().length > 0) {
    $("#error_motivo").text("");
    $("#motivo_coffee").removeClass("has-error");
  }
  if ($("#fecha_coffee").val().length > 0) {
    $("#error_fecha").text("");
    $("#fecha_coffee").removeClass("has-error");
  }
  if ($("#horario_coffee").val().length > 0) {
    $("#error_horario").text("");
    $("#horario_coffee").removeClass("has-error");
  }
  if ($("#no_personas").val().length > 0) {
    $("#error_personas").text("");
    $("#no_personas").removeClass("has-error");
  }
  if ($("#sala_coffee").val().length > 0) {
    $("#error_sala").text("");
    $("#sala_coffee").removeClass("has-error");
  }
  if ($("#menu_si").is(':checked') || $("#menu_no").is(':checked')) {
    $("#error_menus").text("");
  }
  if ($("#menu_si").val() == 1) {
    if (menus != 0)
      $("#error_select_menus").text("");
  }

}

$("#menu_si").on("click", function () {
  // aqui
  sessionStorage.setItem("menu", 0);
  $("#menu_si").val("1");
  $("#menu_no").val("0");
});

$("#menu_no").on("click", function () {
  $("#menu_no").val("1");
  $("#menu_si").val("0");
});

$("#menu_no").on("click", function () {
  sessionStorage.setItem("menu", 0);
  $("#leyenda").addClass("ocultar");
  $("#menus").empty();
});

function menus_especial() {

  $(".cards").click(function () {//Clicking the card
    var inputElement = $(this).find(' input[type=hidden]').attr('id');
    removeActive();
    makeActive(inputElement);
    clickRadio(inputElement);
    error_select_menus = "";
    $("#error_menus").text(error_select_menus);
    console.log("2");
  });
};
function clickRadio(inputElement) {
  sessionStorage.setItem("menu", inputElement);

}

function removeActive() {
  $(".cards").removeClass("active");
}

function makeActive(element) {
  $(`#${element}-card`).addClass("active");
}

$("#menu_si").on("click", function () {
  $("#menus").empty();
  var cat_menu = "0";
  $("#leyenda").removeClass("ocultar");
  $.ajax({
    type: "post",
    url: `${urls}cafeteria/pintar_menu`,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (resp) {
      resp.forEach((key, value) => {
        cat_menu++;
        $("#menus").append(`<div id="${key.special_menu}-card" class="cards card col-md-4"  style="margin-bottom: 1rem;">
              <div class="card-body" role="button">
                <div style="width: 100%;margin-bottom: 2rem!important;">
                <input type="hidden" id="${key.special_menu}">
                  <h5 id="titulo_menu" class="card-title">
                    <label >${key.tittle_menu}</label>
                  </h5>
                </div>
                <hr> 
                <div class="text-center mb-3">
                  <img id="imagen" class="img-fluid mx-auto d-block rounded" style="  height:190px;" src="${key.imagen_menu}" alt="Menu 1" />
                </div>
                 <hr>
                <ol id="comida_${key.special_menu}" class="card-text">
                </ol>
                <hr>
              </div>
            </div>`).show("slow");
        let data = new FormData();
        data.append("special_menu", key.special_menu);
        $.ajax({
          data: data,
          type: "post",
          url: `${urls}cafeteria/pintar_comida`,
          processData: false,
          contentType: false,
          dataType: "json",
          success: function (food) {
            food.forEach((keyFood, valueFood) => {
              $("#comida_" + key.special_menu).append(`
                <li id="${keyFood.id_food}" > ${(keyFood.description.toUpperCase())} </li>
                `).show("slow");
            });
          }
        });
        /* if (cat_menu == 3) {
          $("#menus").append(`
           <div class="w-100"></div>
           `);
          cat_menu = 0;
        } */
      });
      menus_especial();
    }
  });
});