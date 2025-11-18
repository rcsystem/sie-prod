/**uenta
 * ARCHIVO MODULO PERMISOS
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EDITOR: HORUS SAMAEL RIVAS PEDRAZA
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * EMAIL - EDITOR :horus.riv.ped@gmail.com
 * CEL:5565429649
 */
var f_in = 0;
var f_out = 0;
var f_abs = 0;
var f_time = "";
var f_motive = "";
var t_hour = "";
var selectedDays = 0;
var selectedDaysInasisence = 0;
var inputDiaEntrada = false;
var inputDiaSalida = false;
var inputVacaciones = false;

const today = new Date();
const nextMonth = new Date();
nextMonth.setMonth(nextMonth.getMonth() + 1);

const LastDayToMounth = new Date(today.getFullYear(), today.getMonth() + 1, 0); // Obtener el último día del mes
const lastDay = new Date(nextMonth.getFullYear(), nextMonth.getMonth() + 1, 0); // Obtener el último día del siguiente mes
// const lastDay = today.fp_incr(15); // Obtener el último día del siguiente mes

if ($("#tipo").val() == 1) {
  starPermis = "today"; // inicio de permisos
  starPermisVacation = today.fp_incr(-7); // inicio de vacaciones
  daysSelect = [1, 2, 3, 4, 5]; // Habilitar solo los días de la semana seleccionados (L-V -> EMPLEADOS)
} else {
  starPermis = today.fp_incr(1); // inicio de permisos
  starPermisVacation = today.fp_incr(1); // inicio de vacaciones
  daysSelect = [1, 2, 3, 4, 5, 6]; // Habilitar solo los días de la semana seleccionados (L-S -> SINDICALIZADOS)
}

$(document).ready(function () {
  inputVacaction(1);
});
function inputVacaction(type) {
  const tipo_campo = type == 1 ? "multiple" : "single";
  const dia_limite = type == 1 ? lastDay : LastDayToMounth;

  inputVacaciones = flatpickr("#vacaciones_dias_disfrutar", {
    locale: "es",
    mode: tipo_campo,
    dateFormat: "Y-m-d",
    // Configuración de flatpickr con las fechas mínima y máxim
    minDate: starPermisVacation,
    maxDate: dia_limite,
    /* enable: [
      function (date) {
        return daysSelect.includes(date.getDay());
      }
    ], */
    disable: [
      "2023-12-25",
      "2023-12-26",
      "2023-12-27",
      "2023-12-28",
      "2023-12-29",
      "2023-12-30",
      "2024-01-01",
      function (date) {
        // Deshabilitar los domingos (0 es domingo, 1 es lunes, ...)
        if ($("#tipo").val() == 2 && date.getDay() === 0) {
          return true; // Deshabilitar domingos si #tipo es 1
        }
        if (
          $("#tipo").val() == 1 &&
          (date.getDay() === 0 || date.getDay() === 6)
        ) {
          return true; // Deshabilitar sábado y domingo si #tipo es 2
        }
        return false;
      },
    ],
    // disable: [{
    //   from: "2024-12-25",
    //   to: "2025-01-01"
    // },],
    onChange: function (selectedDates, dateStr, instance) {
      selectedDays = selectedDates.length;
    },
  });
}

const inputRegreso = flatpickr("#vacaciones_regresar_actividades", {
  locale: "es",
  dateFormat: "d/m/Y",
  // Configuración de flatpickr con las fechas mínima y máxim
  minDate: starPermisVacation.fp_incr(1),
  maxDate: lastDay.fp_incr(1),
  enable: [
    function (date) {
      return daysSelect.includes(date.getDay());
    },
  ],
});

$(document).ready(function () {
  viewPermisSpecial();
});

function viewPermisSpecial() {
  f_in = 0;
  f_out = 0;
  f_ads = 0;
  f_time = "";
  t_hour = "";
  $.ajax({
    url: `${urls}permisos/motivo_festivo`,
    type: "post",
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (days) {
      if (days != false && days != null) {
        $("#btn_opcion_4").show();
        $("#p_opcion_4").text(days.motive.toUpperCase());
        f_motive = days.motive;
        f_in = days.active_in;
        f_out = days.active_out;
        f_abs = days.active_absence;
        f_time = days.time_permis;
        t_hour = days.max_time;
        console.log("limite: ".t_hour);
      } else {
        $("#btn_opcion_4").hide();
      }
    },
  });
  $.ajax({
    url: `${urls}permisos/motivo_trafico`,
    type: "post",
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (days) {
      if (days != false && days != null) {
        f_motive = "TRAFICO";
        t_hour = days.max_time;
        $("#btn_opcion_6").show();
      } else {
        $("#btn_opcion_6").hide();
      }
    },
  });
}

function dateLibrary() {
  $("#permiso_observaciones").prop("readonly", false);
  $("#permiso_observaciones").val("");
  selectedDaysInasisence = 0;
  if ($("#tipo_permiso").val() == 1) {
    inputDiaEntrada = flatpickr("#permiso_dia_entrada", {
      locale: "es",
      dateFormat: "d/m/Y",
    });

    inputDiaSalida = flatpickr("#permiso_dia_salida", {
      locale: "es",
      dateFormat: "d/m/Y",
    });

    inputDiaInasistencia = flatpickr("#permiso_inasistencia", {
      locale: "es",
      mode: "multiple",
      dateFormat: "Y-m-d",
      onChange: function (selectedDatesInasistences, dateStr, instance) {
        selectedDaysInasisence = selectedDatesInasistences.length;
      },
    });
  } else if ($("#tipo_permiso").val() == 2) {
    var daysSelectFunction = pintarHorarios();
    if (daysSelectFunction == false) {
      $("#error_turno").text("Campo Requerido");
      $("#turno").addClass("has-error");
      daysSelectFunction = [1, 2, 3, 4, 5];
    }

    inputDiaEntrada = flatpickr("#permiso_dia_entrada", {
      locale: "es",
      dateFormat: "d/m/Y",
      minDate: starPermis,
      maxDate: LastDayToMounth,
      enable: [
        function (date) {
          return daysSelectFunction.includes(date.getDay());
        },
      ],
    });

    inputDiaSalida = flatpickr("#permiso_dia_salida", {
      locale: "es",
      dateFormat: "d/m/Y",
      minDate: "today",
      maxDate: LastDayToMounth,
      enable: [
        function (date) {
          return daysSelectFunction.includes(date.getDay());
        },
      ],
    });

    inputDiaInasistencia = flatpickr("#permiso_inasistencia", {
      locale: "es",
      mode: "multiple",
      dateFormat: "Y-m-d",
      minDate: starPermis,
      maxDate: LastDayToMounth,
      enable: [
        function (date) {
          return daysSelectFunction.includes(date.getDay());
        },
      ],
      onChange: function (selectedDatesInasistences, dateStr, instance) {
        selectedDaysInasisence = selectedDatesInasistences.length;
      },
    });
  } else if ($("#tipo_permiso").val() == 4) {
    var daysSelectFunction = pintarHorarios();
    if (daysSelectFunction == false) {
      $("#error_turno").text("Campo Requerido");
      $("#turno").addClass("has-error");
      daysSelectFunction = [1, 2, 3, 4, 5];
    }

    $.ajax({
      url: `${urls}permisos/motivo_festivo_array`,
      type: "post",
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (datos) {
        console.log(datos);
        if (datos != false && datos != null) {
          diasYmdTexto = datos.map((fecha) => fecha.diasYmd);
          diasdmYTexto = datos.map((fecha) => fecha.diasdmY);
          

          inputDiaEntrada = flatpickr("#permiso_dia_entrada", {
            locale: "es",
            dateFormat: "d/m/Y",
            enable: diasdmYTexto,
          });
           inputDiaSalida = flatpickr("#permiso_dia_salida", {
            locale: "es",
            dateFormat: "d/m/Y",
            enable: diasdmYTexto,
          }); 
        
          // Asegúrate de que 'today' esté correctamente definido como una fecha
const today = new Date();
const LastDay = new Date(today.getFullYear(), 8, 12);

/* inputDiaSalida = flatpickr("#permiso_dia_salida", {
    locale: "es",
    dateFormat: "d/m/Y",
    minDate: today, // Usar 'today' como objeto Date, no como string
    maxDate: LastDay,
    enable: [
        function (date) {
            return daysSelectFunction.includes(date.getDay());
        },
    ],
}); */

console.log("enable123:", inputDiaSalida.config.enable);



         // console.log("enable:", inputDiaSalida.config.enable);

          inputDiaInasistencia = flatpickr("#permiso_inasistencia", {
            locale: "es",
            mode: "multiple",
            dateFormat: "Y-m-d",
            enable: diasYmdTexto,
            onChange: function (selectedDatesInasistences, dateStr, instance) {
              selectedDaysInasisence = selectedDatesInasistences.length;
            },
          });

          $("#permiso_observaciones").val(datos[0].obs);
          $("#permiso_observaciones").prop("readonly", true);
        }
      },
    });
  }  else if ($("#tipo_permiso").val() == 6) {

  inputDiaEntrada = flatpickr("#permiso_dia_entrada", {
      locale: "es",
      dateFormat: "d/m/Y",
    });

    inputDiaSalida = flatpickr("#permiso_dia_salida", {
      locale: "es",
      dateFormat: "d/m/Y",
    });

     
  $.ajax({
    url: `${urls}permisos/motivo_trafico_array`,
    type: "post",
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (datos) {
      console.log("Datos recibidos:", datos);

      if (datos && datos.length > 0) {
        // Convertir de d/m/Y a Y-m-d (ISO compatible)
        const diasYmdTexto = datos.map(f => {
          const partes = f.diasdmY.split("/"); // [día, mes, año]
          return `${partes[2]}-${partes[1]}-${partes[0]}`;
        });

        console.log("Fechas convertidas a formato válido:", diasYmdTexto);

        inputDiaSalida = flatpickr("#permiso_dia_salida", {
      locale: "es",
      dateFormat: "d/m/Y",
      enable: diasYmdTexto, // formato correcto para flatpickr
        });
    

        inputDiaEntrada = flatpickr("#permiso_dia_entrada", {
          locale: "es",
          dateFormat: "d/m/Y",
          enable: diasYmdTexto, // formato correcto para flatpickr
        });

        $("#permiso_observaciones").val(datos[0].obs);
        $("#permiso_observaciones").prop("readonly", true);
      } else {
        console.warn("No se recibieron fechas válidas desde motivo_trafico_array");
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la petición AJAX:", error);
    },
  });
}
 else if ($("#tipo_permiso").val() == 8) {

    inputDiaEntrada = flatpickr("#permiso_dia_entrada", {
      locale: "es",
      dateFormat: "d/m/Y",
    });

    inputDiaSalida = flatpickr("#permiso_dia_salida", {
      locale: "es",
      dateFormat: "d/m/Y",
    });

    
    $.ajax({
      url: `${urls}permisos/motivo_trafico_array`,
      type: "post",
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (datos) {
        console.log(datos);
        let diasdmYTexto = "";
        if (datos != false && datos != null) {
          diasdmYTexto = datos.map((fecha) => fecha.diasdmY);

          inputDiaEntrada = flatpickr("#permiso_dia_entrada", {
            locale: "es",
            dateFormat: "d/m/Y",
            enable: diasdmYTexto,
          });

          inputDiaSalida = flatpickr("#permiso_dia_salida", {
            locale: "es",
            dateFormat: "d/m/Y",
            enable: diasdmYTexto,
          });

          $("#permiso_observaciones").val(datos[0].obs);
          $("#permiso_observaciones").prop("readonly", true);
        }
      },
    });
  }
}

function validarPermisos() {
  var resp_return = false;
  $.ajax({
    url: `${urls}permisos/validar_cantidad`,
    type: "POST",
    dataType: "json",
    async: false,
    success: function (resp) {
      if (resp.amount_permissions >= 5) {
        Swal.fire({
          allowOutsideClick: false,
          icon: "info",
          title: "¡LO SENTIMOS!",
          html: "<p>Ya no tienes permisos personales disponibles.<br> En caso de Emergencia Generar Permiso de Vacaciones.</p>",
          padding: "1em",
          showDenyButton: true,
          confirmButtonText: "Generar Permiso de Vacaciones",
          denyButtonText: "Entendido",
          confirmButtonColor: "#00A57C",
          background: "#FFF",
          backdrop: `rgba(189, 189, 189, 0.7)
              url("../public/images/survey/logo_2.png")
              no-repeat
              center 0rem`,
        }).then((result) => {
          $("#card_permisos").attr("class", "card card-default collapsed-card");
          $("#body_permisos").attr("style", "dysplay:none;");
          $("#footer_permisos").attr("style", "dysplay:none;");
          $("#icon_card_permisos").attr("class", "fas fa-plus");
          $("#permisos")[0].reset();
          if (result.isConfirmed) {
            /*  abrir vacaciones */
            $("#card_vacaciones").attr("class", "card card-default");
            $("#body_vacaciones").attr("style", "display: block;");
            $("#footer_vacaciones").attr("style", "display: block;");
            $("#icon_card_vacaciones").attr("class", "fas fa-minus");
          }
        });
      } else {
        $("#cantidad_permisos").empty();
        $("#cantidad_permisos").append(
          `Permisos Personales Generados Este Mes: <b>${resp.amount_permissions}</b>`
        );
        resp_return = true;
      }
    },
  });
  return resp_return;
}

function validarPagoTiempo() {
  var resp_return = false;
  $.ajax({
    url: `${urls}permisos/validar_pago_tiempo`,
    type: "POST",
    dataType: "json",
    async: false,
    success: function (resp) {
      if (resp == "noCreatePayTime") {
        Swal.fire({
          allowOutsideClick: false,
          icon: "warning",
          title: "¡LO SENTIMOS!",
          html: "<p>Tienes un permiso Personal sin pago de tiempo.<br> ¿Quieres generar pagos de tiempo para el permiso?.</p>",
          padding: "1em",
          showDenyButton: true,
          confirmButtonText: "Generar Pago de Tiempo",
          denyButtonText: "No",
          confirmButtonColor: "#00A57C",
          background: "#FFF",
          backdrop: `rgba(189, 189, 189, 0.7)
              url("../public/images/survey/logo_2.png")
              no-repeat
              center 0rem`,
        }).then((result) => {
          $("#card_permisos").attr("class", "card card-default collapsed-card");
          $("#body_permisos").attr("style", "dysplay:none;");
          $("#footer_permisos").attr("style", "dysplay:none;");
          $("#icon_card_permisos").attr("class", "fas fa-plus");
          $("#div_horario").empty();
          $("#permisos")[0].reset();
          if ($("#tipo").val() == 2) {
            $("#div_tiempo_pagado").empty();
            $("#div_tiempo_pagado").hide();
          }
          if (result.isConfirmed) {
            window.location.href = "/permisos/pago-horas";
          }
        });
      } else if (resp !== false) {
        Swal.fire({
          allowOutsideClick: false,
          icon: "warning",
          title: `Pago pendiente de: <br> <p style="font-size: 45px;"> ${resp} </p>`,
          html: `<p>Favor de pagar tiempo para generar otro permiso personal con goce de sueldo. ¡Gracias!.</p>`,
          padding: "1em",
          background: "#FFF",
          backdrop: `rgba(189, 189, 189, 0.7)
              url("../public/images/survey/logo_2.png")
              no-repeat
              center 0rem`,
        }).then((result) => {
          $("#card_permisos").attr("class", "card card-default collapsed-card");
          $("#body_permisos").attr("style", "dysplay:none;");
          $("#footer_permisos").attr("style", "dysplay:none;");
          $("#icon_card_permisos").attr("class", "fas fa-plus");
          $("#div_horario").empty();
          $("#permisos")[0].reset();
          if ($("#tipo").val() == 2) {
            $("#div_tiempo_pagado").empty();
            $("#div_tiempo_pagado").hide();
          }
        });
      }
    },
  });
  return resp_return;
}

$("#colllapse_permisos").on("click", function (e) {
  e.preventDefault();
  $("#permisos")[0].reset();
  $("#div_cards_all").hide();
  $("#tipo_permiso").val("");
  $(".btn-opcion").removeClass("active focus");
  $("#goce_sueldo").val("SI");
  $("#div_horario").empty();
  $("#card_inasistencia").hide();
  $("#hr_inasistencia").hide();
  if ($("#tipo").val() == 2) {
    $("#div_tiempo_pagado").empty();
    $("#div_tiempo_pagado").hide();
  }
  if (inputDiaSalida) {
    inputDiaEntrada.clear();
    inputDiaSalida.clear();
  }
});

function pintarHorarios() {
  $("#div_horario").empty();
  $("#error_turno").text("");
  $("#turno").removeClass("has-error");
  if ($("#turno").val().length == 0) {
    $("#error_turno").text("Campo Requerido");
    $("#turno").addClass("has-error");
    return false;
  }
  var result_horario = "";
  const data = new FormData();
  data.append("id_turn", $("#turno").val());
  // aqui datos de dia festivo
  $.ajax({
    data: data,
    url: `${urls}permisos/horarios`,
    type: "post",
    processData: false,
    contentType: false,
    async: false,
    dataType: "json",
    success: function (turn) {
      if (turn != false || turn != null) {
        $("#error_turno").text("");
        $("#turno").removeClass("has-error");
        sabado =
          turn.hour_in_saturday == "00:00:00"
            ? "<b>Sabado:</b> Sin Horario"
            : ` <b>Sabado:</b> ${turn.hour_in_saturday} - ${turn.hour_out_saturday}`;
        $("#div_horario").append(
          `<b>Lunes a Viernes:</b> ${turn.hour_in} - ${turn.hour_out} <br> ${sabado}`
        );
        result_horario =
          turn.hour_in_saturday == "00:00:00"
            ? [1, 2, 3, 4, 5]
            : [1, 2, 3, 4, 5, 6];
      } else {
        $("#error_turno").text("Campo Requerido");
        $("#turno").addClass("has-error");
        result_horario = false;
      }
    },
  });
  return result_horario;
}

$("#permisos").submit(function (event) {
  event.preventDefault();
  var campos = 0;
  if ($("#tipo_permiso").val() == 2) {
    if (validarPermisos() != true) {
      return false;
    }
  }

  if (
    $.trim($("#permiso_autoriza_salida").val()).length == 0 &&
    $.trim($("#permiso_dia_salida").val()).length == 0 &&
    $.trim($("#permiso_autoriza_entrada").val()).length == 0 &&
    $.trim($("#permiso_dia_entrada").val()).length == 0 &&
    $.trim($("#permiso_inasistencia").val()).length == 0
  ) {
    campos = 1;
  }
  $("#guardar_permiso").prop("disabled", true);

  if ($("#tipo_permiso").val().length == 0) {
    error_tipo = "Seleccione una opcion";
    $("#error_tipo").text(error_tipo);
  } else {
    error_tipo = "";
    $("#error_tipo").text(error_tipo);
  }

  if (
    $.trim($("#permiso_autoriza_salida").val()).length > 0 &&
    $.trim($("#permiso_dia_salida").val()).length == 0
  ) {
    error_form_salida = "Día requerido para Salida";
    $("#error_permiso_dia_salida").text(error_form_salida);
    $("#permiso_dia_salida").addClass("has-error");
  } else if (
    $.trim($("#permiso_dia_salida").val()).length > 0 &&
    $.trim($("#permiso_autoriza_salida").val()).length == 0
  ) {
    error_form_salida = "Hora requerido para Salida";
    $("#error_permiso_autoriza_salida").text(error_form_salida);
    $("#permiso_autoriza_salida").addClass("has-error");
  } else {
    error_form_salida = "";
    $("#error_permiso_autoriza_salida").text(error_form_salida);
    $("#error_permiso_dia_salida").text(error_form_salida);
    $("#permiso_autoriza_salida").removeClass("has-error");
    $("#permiso_dia_salida").removeClass("has-error");
  }

  if ($("#turno").val().length == 0) {
    error_turno = "Campo Requerido";
    $("#error_turno").text(error_turno);
    $("#turno").addClass("has-error");
  } else {
    error_turno = "";
    $("#error_turno").text(error_turno);
    $("#turno").removeClass("has-error");
  }

  if (
    $.trim($("#permiso_autoriza_entrada").val()).length > 0 &&
    $.trim($("#permiso_dia_entrada").val()).length == 0
  ) {
    error_form_entrada = "Día requerido para Entrada";
    $("#error_permiso_dia_entrada").text(error_form_entrada);
    $("#permiso_dia_entrada").addClass("has-error");
  } else if (
    $.trim($("#permiso_dia_entrada").val()).length > 0 &&
    $.trim($("#permiso_autoriza_entrada").val()).length == 0
  ) {
    error_form_entrada = "Hora requerido para Entrada";
    $("#error_permiso_autoriza_entrada").text(error_form_entrada);
    $("#permiso_autoriza_entrada").addClass("has-error");
  } else {
    error_form_entrada = "";
    $("#error_permiso_autoriza_entrada").text(error_form_entrada);
    $("#error_permiso_dia_entrada").text(error_form_entrada);
    $("#permiso_autoriza_entrada").removeClass("has-error");
    $("#permiso_dia_entrada").removeClass("has-error");
  }

  length = false;
  if ($.trim($("#permiso_observaciones").val()).length == 0) {
    error_form_obs = "Campo Requerido";
    $("#error_permiso_observaciones").text(error_form_obs);
    $("#permiso_observaciones").addClass("has-error");
  } else {
    if ($("#tipo_permiso").val() == 1) {
      if ($.trim($("#permiso_observaciones").val()).length < 30) {
        error_form_obs = "Observación poco especifica";
        $("#error_permiso_observaciones").text(error_form_obs);
        $("#permiso_observaciones").addClass("has-error");
      } else {
        length = true;
      }
    } else {
      if ($.trim($("#permiso_observaciones").val()).length < 4) {
        error_form_obs = "La observacion debe tener minimo 4 letras";
        $("#error_permiso_observaciones").text(error_form_obs);
        $("#permiso_observaciones").addClass("has-error");
      } else {
        length = true;
      }
    }
  }
  if (length == true) {
    const observacionesNoAceptables = [
      "abcd",
      "ABCD",
      "asdf",
      "ASDF",
      "xxxx",
      "XXXX",
      "aaaa",
      "AAAA",
      "....",
      ",,,,",
      "____",
      "----",
      "Xxxxxxx",
    ];

    if (isNaN($.trim($("#permiso_observaciones").val())) == false) {
      error_form_obs = "No se permiten solo números";
      $("#error_permiso_observaciones").text(error_form_obs);
      $("#permiso_observaciones").addClass("has-error");
    } else if (
      $.inArray(
        $.trim($("#permiso_observaciones").val()),
        observacionesNoAceptables
      ) !== -1
    ) {
      var error_form_obs = "Escribe una observacion aceptable";
      $("#error_permiso_observaciones").text(error_form_obs);
      $("#permiso_observaciones").addClass("has-error");
    } else {
      error_form_obs = "";
      $("#error_permiso_observaciones").text(error_form_obs);
      $("#permiso_observaciones").removeClass("has-error");
    }
  }

  var errores = 0;
  if ($("#tipo").val() == 2) {
    if ($("#goce_sueldo").val().length == 0) {
      errores += 1;
      $("#error_sueldo").text("Seleccione una opcion");
    } else if (
      $("#goce_sueldo").val() == "SI" &&
      $("#tipo_permiso").val() == 2
    ) {
      var tipo_pago = document.getElementById("tipo_pago_tiempo");
      if (tipo_pago.value.length == 0) {
        tipo_pago.classList.add("has-error");
        document.getElementById("error_" + tipo_pago.id).textContent =
          "Campo Requerido";
        errores += 1;
      } else {
        tipo_pago.classList.remove("has-error");
        document.getElementById("error_" + tipo_pago.id).textContent = "";
      }
    } else {
      $("#error_sueldo").text("");
    }
  }
  if (
    error_turno != "" ||
    error_form_entrada != "" ||
    error_form_salida != "" ||
    error_form_obs != "" ||
    error_tipo != "" ||
    errores != 0 ||
    campos != 0
  ) {
    $("#guardar_permiso").prop("disabled", false);
    return false;
  }
  if ($("#tipo_permiso").val() == 7) {
    var input = document.getElementById("evidencias");
    if (input.files.length == 0) {
      Swal.fire({
        icon: "info",
        title: "¡ATENCION!",
        text: "Debes seleccionar una imagen como Evidencia.",
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Aceptar",
      });
      $("#guardar_permiso").prop("disabled", false);
      return false;
    }
    for (var i = 0; i < input.files.length; i++) {
      var fileType = input.files[i].type;
      if (!fileType.startsWith("image/")) {
        Swal.fire({
          icon: "info",
          title: "¡ATENCION!",
          text: "El archivo seleccionado no es una imagen.",
          confirmButtonColor: "#3085d6",
          confirmButtonText: "Aceptar",
        });
        input.value = "";
        $("#guardar_permiso").prop("disabled", false);
        return false;
      }
    }
  }

  let timerInterval = Swal.fire({
    //se le asigna un nombre al swal
    title: "Generando Permiso!",
    html: "Espere unos Segundos.",
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading(); //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });
  const data = new FormData($("#permisos")[0]);

  let tipo_permiso = $("#tipo_permiso").val();
  data.append("tipo_empleado", $("#tipo").val());
  data.append("tipo_permiso", tipo_permiso);

  $.ajax({
    data: data,
    url: urls + "permisos/generar_des",
    type: "post",
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (response) {
      Swal.close(timerInterval);
      $("#guardar_permiso").prop("disabled", false);
      if (response == "festivoYaGenerado") {
        Swal.fire({
          icon: "info",
          title: "!Oops..!",
          html: "Ya has generado el permiso festivo de esta fecha.",
          confirmButtonText: "OK",
        });
      } else if (response == "Entrada") {
        Swal.fire({
          icon: "info",
          title: "!Oops..!",
          html: "<p>Hora de Entrada Maximo 3 Horas Despues del Horario de Entrada.<br><b>¿Desea Generar un Permiso de Vacaciones?</b></p>",
          showDenyButton: true,
          confirmButtonText: "SI",
          denyButtonText: `NO`,
        }).then((result) => {
          if (result.isConfirmed) {
            $("#card_permisos").attr(
              "class",
              "card card-default collapsed-card"
            );
            $("#body_permisos").attr("style", "dysplay:none;");
            $("#footer_permisos").attr("style", "dysplay:none;");
            $("#icon_card_permisos").attr("class", "fas fa-plus");
            $("#permisos")[0].reset();
            /*  abrir vacaciones */
            $("#card_vacaciones").attr("class", "card card-default");
            $("#body_vacaciones").attr("style", "display: block;");
            $("#footer_vacaciones").attr("style", "display: block;");
            $("#icon_card_vacaciones").attr("class", "fas fa-minus");
          }
        });
      } else if (response == "Salida") {
        Swal.fire({
          icon: "info",
          title: "!Oops..!",
          html: "<p>Hora de Salida Maximo 3 Horas Antes del Horario de Salida.<br><b>¿Desea Generar un Permiso de Vacaciones?</b></p>",
          showDenyButton: true,
          confirmButtonText: "SI",
          denyButtonText: `NO`,
        }).then((result) => {
          if (result.isConfirmed) {
            $("#card_permisos").attr(
              "class",
              "card card-default collapsed-card"
            );
            $("#body_permisos").attr("style", "dysplay:none;");
            $("#footer_permisos").attr("style", "dysplay:none;");
            $("#icon_card_permisos").attr("class", "fas fa-plus");
            $("#permisos")[0].reset();
            /*  abrir vacaciones */
            $("#card_vacaciones").attr("class", "card card-default");
            $("#body_vacaciones").attr("style", "display: block;");
            $("#footer_vacaciones").attr("style", "display: block;");
            $("#icon_card_vacaciones").attr("class", "fas fa-minus");
          }
        });
      } else if (response == "Formato") {
        Swal.fire({
          icon: "info",
          title: "!Oops..!",
          text: "El Formato de la fecha solicitada no es el correcto, intenta de nuevo.",
          confirmButtonText: "OK",
        });
      } else if (response == "NoFestivo") {
        Swal.fire({
          icon: "info",
          title: "!Oops..!",
          html: "No hay Permisos por <b>Día Festivo</b> actualmente, recarga la página de favor.",
          confirmButtonText: "OK",
        });
      } else if (response == "excesoTiempoFestivo") {
        Swal.fire({
          icon: "info",
          title: "!Oops..!",
          html: `El permiso por <b>${f_motive.toUpperCase()}</b> solo da Maximo <b>${f_time}</b>.`,
          confirmButtonText: "OK",
        });
      } else if (response == "NoTrafico") {
        Swal.fire({
          icon: "info",
          title: "!Oops..!",
          text: "No hay Permisos por <b>TRAFICO</b> actualmente, recarga la página de favor.",
          confirmButtonText: "OK",
        });
      } else if (response == "excesoTiempoTrafico") {
        Swal.fire({
          icon: "info",
          title: "!Oops..!",
          html: `El permiso por <b>${f_motive.toUpperCase()}</b> tiene como Maximo hora de entrada: <b style="font-size: 25px;font-weight: bold;">${t_hour}</b>.`,
          confirmButtonText: "OK",
        });
      } else if (response == "excesoTiempoTraficoSalida") {
        Swal.fire({
          icon: "info",
          title: "!Oops..!",
          html: `El permiso por <b>${f_motive.toUpperCase()}</b> tiene como Maximo hora de entrada: <b style="font-size: 25px;font-weight: bold;">${t_hour}</b>.`,
          confirmButtonText: "OK",
        });
      } else if (response == "limitDay") {
        Swal.fire({
          icon: "info",
          title: "!Oops..!",
          text: "La Fecha del Permiso Solicitado no es valida, intenta de nuevo.",
          confirmButtonText: "OK",
        });
      } else if (response == "mes") {
        Swal.fire({
          icon: "info",
          title: "!Oops..!",
          text: "La Fecha del Permiso Solicitado debe ser dentro del Mes en curso.",
          confirmButtonText: "OK",
        });
      } else if (response == "In&Out") {
        Swal.fire({
          icon: "info",
          title: "!Oops..!",
          text: "El permiso de Entrada & Salida debe ser el mismo día y con un tiempo máximo de 3 horas.",
          confirmButtonText: "OK",
        });
      } else if (response == "1xDia") {
        Swal.fire({
          icon: "info",
          title: "!Oops..!",
          text: "Solo puedes generar un Permiso Personal por día.",
          confirmButtonText: "OK",
        });
      } else if (response == "pagoHoras") {
        Swal.fire({
          icon: "info",
          title: "!Oops..!",
          text: "Las Horas no Cubre el Permiso.",
          confirmButtonText: "OK",
        });
      } else if (response == true || !isNaN(response)) {
        $("#permisos")[0].reset();
        if (inputDiaSalida) {
          inputDiaEntrada.clear();
          inputDiaSalida.clear();
        }
        $("#card_permisos").attr("class", "card card-default collapsed-card");
        $("#body_permisos").attr("style", "dysplay:none");
        $("#footer_permisos").attr("style", "dysplay:none");
        $("#icon_card_permisos").attr("class", "fas fa-plus");
        $("#div_horario").empty();
        $("#cantidad_permisos").empty();
        $("#tipo_permiso").val("");
        $("#goce_sueldo").val("");
        $(".btn-opcion").removeClass("active focus");
        if (document.getElementById("tipo").value == 2) {
          $("#div_tiempo_pagado").empty();
          $("#div_tiempo_pagado").hide();
        }
        mensaje =
          response == true
            ? `Permiso Generado corectamente`
            : `Permisos personales generados este mes:  ${response}`;
        if (response == 4) {
          mensaje = `<h5>Este Permiso se considera Extra,<br> tu Director será notificado</h5><p>Permisos personales generados este mes:  ${response}<p>`;
        }
        if (response == 5) {
          mensaje = `<h5>Este Permiso se considera Extra,<br> el Director General será notificado</h5><p>Permisos personales generados este mes:  ${response}<p>`;
        }
        Swal.fire({
          icon: "success",
          title: "!Se ha Generado el Permiso!",
          html: mensaje,
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
      viewPermisSpecial();
    },
  }).fail(function (jqXHR, textStatus, errorThrown) {
    $("#guardar_permiso").prop("disabled", false);
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
});

function validarNew(campo) {
  if (campo.value.length > 0) {
    campo.classList.remove("has-error");
    document.getElementById("error_" + campo.id).textContent = "";
  }
}

function showInasistencia() {
  $("#permiso_inasistencia").val("");
  $("#permiso_dia_inasistencia").val("");
  $("#card_inasistencia").hide();
  $("#hr_inasistencia").hide();
  $("#card_entrada").show();
  $("#hr_entrada").show();
  $("#card_salida").show();
  $("#hr_salida").show();
  if ($("#tipo_permiso").val() == 1) {
    $("#card_inasistencia").show();
    $("#hr_inasistencia").show();
  } else if ($("#goce_sueldo").val() == "NO" && $("#tipo_permiso").val() == 2) {
    $("#card_inasistencia").show();
    $("#hr_inasistencia").show();
  } else if ($("#tipo_permiso").val() == 4) {
    $("#card_salida").hide();
    $("#hr_salida").hide();
    $("#card_entrada").hide();
    $("#hr_entrada").hide();
    if (f_in == 1) {
      $("#card_entrada").show();
      $("#hr_entrada").show();
      $("#card_entrada").attr("class", "card card-default");
      $("#card_entrada_body").attr("style", "blook");
      $("#card_entrada_icon").attr("class", "fas fa-minus");
    }
    if (f_out == 1) {
      $("#card_salida").show();
      $("#hr_salida").show();
      $("#card_salida").attr("class", "card card-default");
      $("#card_salida_icon").attr("class", "fas fa-minus");
      $("#card_salida_body").attr("style", "blook");
    }
    if (f_abs == 1) {
      $("#card_inasistencia").show();
      $("#hr_inasistencia").show();
    }
  } else if ($("#tipo_permiso").val() == 6) {
    $("#card_salida").hide();
    $("#hr_salida").hide();
  }
}

function goce(goce) {
  const valor = goce == 1 ? "SI" : "NO";
  $("#goce_sueldo").val(valor);
  if ($("#tipo").val() == 2) {
    mostrarPagoTiempo();
  }
  showInasistencia();
}

function goceEmpleado(chbx) {
  if (chbx.checked) {
    $("#lbl_goce_empleado").addClass("focus active");
    goce(2);
  } else {
    $("#lbl_goce_empleado").removeClass("focus active");
    goce(1);
  }
}

function tipoPermiso(tipo) {
  $("#div_cards_all").show();
  $("#tipo_permiso").val(tipo);
  $("#error_tipo_permiso").text("");
  $("#div_evidencia").hide();
  $("#card_inasistencia").attr("class", "card card-default collapsed-card");
  if (tipo == 1) {
    $("#cantidad_permisos").empty();
    $("#error_tipo").text("");
    if ($("#tipo").val() == 2) {
      mostrarPagoTiempo();
    }
    showInasistencia();
    dateLibrary();
  } else if (tipo == 2) {
    validarPermisos();
    $("#error_tipo").text("");
    if ($("#tipo").val() == 2) {
      mostrarPagoTiempo();
    }
    showInasistencia();
    dateLibrary();
  } else if (tipo == 4) {
    $("#error_tipo").text("");
    showInasistencia();
    dateLibrary();
  } else if (tipo == 6) {
    $("#error_tipo").text("");
    showInasistencia();
    dateLibrary();
  } else if (tipo == 7) {
    $("#error_tipo").text("");
    $("#card_salida").hide();
    $("#card_entrada").hide();
    $("#hr_salida").hide();
    $("#hr_entrada").hide();
    $("#card_inasistencia").show();
    $("#card_inasistencia").attr("class", "card card-default");
    $("#hr_inasistencia").show();
    $("#div_evidencia").show();

    inputDiaInasistencia = flatpickr("#permiso_inasistencia", {
      locale: "es",
      mode: "multiple",
      dateFormat: "Y-m-d",
      enable: ["2024-03-08"],
      onChange: function (selectedDatesInasistences, dateStr, instance) {
        selectedDaysInasisence = selectedDatesInasistences.length;
      },
    });
  } else if (tipo == 8) {
    $("#error_tipo").text("");
    showInasistencia();
    dateLibrary();
  }
}

// El encargado de agregar más formularios
$("#acuenta").click(function () {
  if (this.checked) {
    $("#error_permiso").text("");
    $("#vacacional_1").removeClass("has-error");
    $("#acuenta_1").removeClass("has-error");
    let getid = document.getElementById("agregados").hasChildNodes();

    if (!getid) {
      // Agregamos un boton para retirar el formulario
      $("#agregados").append(` 
        <div class="col-md-12">
        <div class="form-row">
        <div class="form-group col-md-6">
          <label for="select_permiso">Tipo de Pemiso</label>
          <select id="select_permiso" name="select_permiso" class="form-control rounded-0" onchange="getval(this);">
          <option value="">Seleccionar</option>
          <option value="1">ENTRADA</option>
          <option value="2">SALIDA</option>  
          <option value="3">ENTRADA & SALIDA</option>
          </select>
          <div id="error_select_permiso" class="text-danger"></div>
        </div>
        <div id="acomodar" class=""></div>
        <div class="form-group col-md-6">
          <label id="hora_permiso" for="permiso_hora">A las </label>
          <input type="time" class="form-control rounded-0" id="permiso_hora" name="permiso_hora" value="" onchange="validarNew(${this})">
          <div id="error_permiso_hora" class="text-danger"></div>
        </div>
        <div id="entrada_salida" class="col-md-6"></div>
        <div class="form-group col-md-12">
          <label for="permiso_observacion">Observación:</label>
          <textarea id="permiso_observacion" name="permiso_observacion" class="form-control rounded-0" cols="3" rows="2" onchange="validarNew(${this})"></textarea>
          <div id="error_permiso_observacion" class="text-danger"></div>
        </div>
      </div>
      </div>
        `);
      inputVacaction(2);
    } /* else {

    } */
    $("#div_a_cargo").hide();
  }
});

function getval(sel) {
  $("#select_permiso").removeClass("has-error");
  $("#error_select_permiso").text("");
  if (sel.value != 3) {
    //alert(sel.value);
    $("#acomodar").removeClass("form-group col-md-6");
    document.getElementById("hora_permiso").innerHTML = "A las";
    $("#entrada_salida").empty();
    return false;
  }
  $("#acomodar").addClass("form-group col-md-6");
  document.getElementById("hora_permiso").innerHTML = "Entrada";
  $("#entrada_salida").append(`<div class="form-group ">
    <label for="permiso_salida">Salida</label>
    <input type="time" class="form-control rounded-0" id="permiso_salida" name="permiso_salida" value="" />
    <div id="error_salida" name="error_salida" class="text-danger"></div>
  </div>`);
}

$("#vacacional").click(function () {
  if (this.checked) {
    $("#error_permiso").text("");
    $("#vacacional_1").removeClass("has-error");
    $("#acuenta_1").removeClass("has-error");
    let getid = document.getElementById("agregados").hasChildNodes();

    if (getid) {
      $("#agregados").empty();
      inputVacaction(1);
    }
    $("#div_a_cargo").show();
  }
});

$("#vacaciones").submit(function (event) {
  event.preventDefault();
  $("#permiso_vacaciones").prop("disabled", true);

  var error_select_permiso = "";
  var error_permiso_hora = "";
  var error_form_obsv = "";
  var error_permiso_salida = "";
  var error_a_cargo = "";

  if ($('input[name="vacaciones"]').is(":checked")) {
    var error_permiso = "";
    $("#error_permiso").text(error_permiso);
    $("#vacacional_1").removeClass("has-error");
    $("#acuenta_1").removeClass("has-error");
  } else {
    var error_permiso = "El campo es requerido";
    $("#error_permiso").text(error_permiso);
    $("#vacacional_1").addClass("has-error");
    $("#acuenta_1").addClass("has-error");
  }

  let campos = document.getElementById("agregados").hasChildNodes();

  if (campos) {
    if ($.trim($("#select_permiso").val()).length == 0) {
      var error_select_permiso = "Campo requerido";
      $("#error_select_permiso").text(error_select_permiso);
      $("#select_permiso").addClass("has-error");
    } else {
      var error_select_permiso = "";
      $("#error_select_permiso").text(error_select_permiso);
      $("#select_permiso").removeClass("has-error");

      if ($("#select_permiso").val() == 3) {
        if ($.trim($("#permiso_salida").val()).length == 0) {
          var error_permiso_salida = "Campo requerido";
          $("#error_salida").text(error_permiso_salida);
          $("#permiso_salida").addClass("has-error");
        } else {
          error_permiso_salida = "";
          $("#error_salida").text(error_permiso_salida);
          $("#permiso_salida").removeClass("has-error");
        }
      }
    }

    if ($.trim($("#permiso_hora").val()).length == 0) {
      var error_permiso_hora = "Campo requerido";
      $("#error_permiso_hora").text(error_permiso_hora);
      $("#permiso_hora").addClass("has-error");
    } else {
      var error_permiso_hora = "";
      $("#error_permiso_hora").text(error_permiso_hora);
      $("#permiso_hora").removeClass("has-error");
    }

    if (
      $.trim($("#permiso_observacion").val()).length >= 1 &&
      $.trim($("#permiso_observacion").val()).length <= 3
    ) {
      error_form_obsv = "La observacion debe tener minimo 4 letras";
      $("#error_permiso_observacion").text(error_form_obsv);
      $("#permiso_observacion").addClass("has-error");
    } else if (
      $.trim($("#permiso_observacion").val()).length >= 4 &&
      isNaN($.trim($("#permiso_observacion").val())) == false
    ) {
      error_form_obsv = "No se permiten solo números";
      $("#error_permiso_observacion").text(error_form_obsv);
      $("#permiso_observacion").addClass("has-error");
    } else if ($.trim($("#permiso_observacion").val()).length == 0) {
      error_form_obsv = "Llena correctamente este campo";
      $("#error_permiso_observacion").text(error_form_obsv);
      $("#permiso_observacion").addClass("has-error");
    } else if (
      $.trim($("#permiso_observacion").val()).length >= 4 &&
      isNaN($.trim($("#permiso_observacion").val())) == false
    ) {
      error_form_obsv = "No se permiten solo números";
      $("#error_permiso_observacion").text(error_form_obsv);
      $("#permiso_observacion").addClass("has-error");
    } else if (
      ($.trim($("#permiso_observacion").val()).length >= 4 &&
        ($.trim($("#permiso_observacion").val()) == "abcd" ||
          $.trim($("#permiso_observacion").val()) == "ABCD" ||
          $.trim($("#permiso_observacion").val()) == "asdf" ||
          $.trim($("#permiso_observacion").val()) == "ASDF" ||
          $.trim($("#permiso_observacion").val()) == "xxxx" ||
          $.trim($("#permiso_observacion").val()) == "XXXX" ||
          $.trim($("#permiso_observacion").val()) == "aaaa" ||
          $.trim($("#permiso_observacion").val()) == "AAAA" ||
          $.trim($("#permiso_observacion").val()) == "...." ||
          $.trim($("#permiso_observacion").val()) == ",,,," ||
          $.trim($("#permiso_observacion").val()) == "____" ||
          $.trim($("#permiso_observacion").val()) == "----")) ||
      $.trim($("#permiso_observacion").val()) == "Xxxxxxx"
    ) {
      error_form_obsv = "Escribe una observacion aceptable";
      $("#error_permiso_observacion").text(error_form_obsv);
      $("#permiso_observacion").addClass("has-error");
    } else {
      error_form_obsv = "";
      $("#error_permiso_observacion").text(error_form_obsv);
      $("#permiso_observacion").removeClass("has-error");
    }
  } else {
    if ($("#tipo").val() == 1) {
      if ($.trim($("#a_cargo").val()).length == 0) {
        error_a_cargo = "Campo requerido";
        $("#error_a_cargo").text(error_a_cargo);
        $("#a_cargo").addClass("has-error");
      } else {
        error_a_cargo = "";
        $("#error_a_cargo").text(error_a_cargo);
        $("#a_cargo").removeClass("has-error");
      }
    }
  }
  /*--------------------------------original------------------------------- */
  if ($.trim($("#vacaciones_dias_disfrutar").val()).length == 0) {
    var error_form_disfrutar = "Campo requerido";
    $("#error_vacaciones_dias_disfrutar").text(error_form_disfrutar);
    $("#vacaciones_dias_disfrutar").addClass("has-error");
  } else {
    var error_form_disfrutar = "";
    $("#error_vacaciones_dias_disfrutar").text(error_form_disfrutar);
    $("#vacaciones_dias_disfrutar").removeClass("has-error");
  }

  if ($.trim($("#vacaciones_regresar_actividades").val()).length == 0) {
    var error_form_regreso = "Campo requerido";
    $("#error_vacaciones_regresar_actividades").text(error_form_regreso);
    $("#vacaciones_regresar_actividades").addClass("has-error");
  } else {
    var error_form_regreso = "";
    $("#error_vacaciones_regresar_actividades").text(error_form_regreso);
    $("#vacaciones_regresar_actividades").removeClass("has-error");
  }

  if (
    error_form_disfrutar != "" ||
    error_form_regreso ||
    error_permiso != "" ||
    error_select_permiso != "" ||
    error_permiso_hora != "" ||
    error_form_obsv != "" ||
    error_permiso_salida != "" ||
    error_a_cargo != ""
  ) {
    $("#permiso_vacaciones").prop("disabled", false);
    return false;
  }

  let timerInterval = Swal.fire({
    //se le asigna un nombre al swal
    title: "Generando Permiso de Vacaciones!",
    html: "Espere unos Segundos.",
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading(); //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });

  let data = new FormData();

  let tipo_vacaciones = $("input:radio[name=vacaciones]:checked").val();
  data.append("tipo_vacaciones", tipo_vacaciones);

  if (campos) {
    data.append("tipo_permiso", $("#select_permiso").val());
    data.append("permiso_hora", $("#permiso_hora").val());
    data.append("observaciones", $("#permiso_observacion").val());
    data.append("hora_salida", $("#permiso_salida").val());
  } else {
    data.append("a_cargo", $("#a_cargo").val());
  }

  data.append("usuario", $("#vacaciones_usuario").val());
  data.append("depto", $("#vacaciones_departamento").val());
  data.append("puesto_trabajo", $("#vacaciones_puesto_trabajo").val());
  data.append("num_nomina", $("#vacaciones_num_nomina").val());
  data.append("fecha_ingreso", $("#vacaciones_fecha_ingreso").val());
  data.append("tipo_empleado", $("#vacaciones_tipo_empleado").val());
  data.append("dias_disponibles", $("#vacaciones_dias_disponibles").val());
  data.append("dias_disfrutar", $("#vacaciones_dias_disfrutar").val());
  data.append("cant_dias_disfrutar", selectedDays);
  data.append(
    "regresar_actividades",
    $("#vacaciones_regresar_actividades").val()
  );

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: urls + "permisos/vacaciones", //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    dataType: "json",
    success: function (save) {
      Swal.close(timerInterval);
      //una vez que el archivo recibe el request lo procesa y lo devuelv
      /*codigo que borra todos los campos del form newProvider*/
      $("#permiso_vacaciones").prop("disabled", false);
      if (save.hasOwnProperty("xdebug_message")) {
        Swal.fire({
          icon: "error",
          title: "Oops, Exception...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
        console.log("Mensaje de xdebug:", response.xdebug_message);
      } else if (save == "faltaDias") {
        Swal.fire({
          icon: "info",
          title: "Días Insuficientes",
          html: `<p style="font-size: 25px">Vacaciones insuficientes para el permiso. <br> 
          <b style="font-size:15px;">En caso de emergencia mayor, comunicarse con <br> ADMINISTRACIÓN DE PERSONAL.</b></p>`,
        });
      } else if (save == "compaVacacionando") {
        Swal.fire({
          icon: "info",
          title: "Problema con el usuario responsable",
          text: "el usuario responsable tiene vacaciones dentro de las fechas solicitadas.",
        });
      } else if (!isNaN(save)) {
        selectedDays = 0;
        inputRegreso.clear();
        inputVacaciones.clear();
        $("#vacaciones")[0].reset();
        $("#vacaciones_dias_disponibles").val(save);
        $("#acuenta_1").removeClass("active");
        $("#vacacional_1").removeClass("active");
        $("input[name=vacaciones]").removeAttr("checked");
        $("#agregados").empty();
        $("#div_a_cargo").hide();
        $("#div_evidencia").hide();
        $("#card_entrada").attr("class", "card card-default collapsed-card");
        $("#card_salida").attr("class", "card card-default collapsed-card");
        $("#card_inasistencia").attr(
          "class",
          "card card-default collapsed-card"
        );
        $("#card_entrada_icon").attr("class", "fas fa-plus");
        $("#card_salida_icon").attr("class", "fas fa-plus");
        $("#card_inasistencia_icon").attr("class", "fas fa-plus");
        $("#card_entrada_body").attr("style", "none");
        $("#card_salida_body").attr("style", "none");
        Swal.fire(
          "!Se ha Generado el Permiso de Vacaciones con Éxito!",
          "",
          "success"
        );
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
  }).fail(function (jqXHR, textStatus, errorThrown) {
    $("#permiso_vacaciones").prop("disabled", false);
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
});

function mostrarPagoTiempo() {
  $("#div_tiempo_pagado").hide();
  $("#div_tiempo_pagado").empty();
  if (
    document.getElementById("tipo_permiso").value == 2 &&
    document.getElementById("goce_sueldo").value == "SI"
  ) {
    validarPagoTiempo();
    $("#div_tiempo_pagado").show();
    $("#div_tiempo_pagado").append(`<div class="col-md-2">
    <input type="hidden" name="tipo_pago_tiempo" id="tipo_pago_tiempo">
    <label>Pago de Tiempo</label>
    <div class="btn-group1 btn-group-toggle text-center" data-toggle="buttons">
      <label class="btn btn-outline-primary" style="margin-bottom: 10px;">
        <input type="radio" onclick="pagoTiempo(1)"> Ya Pagado
      </label>
      <label class="btn btn-outline-primary" style="margin-bottom: 10px;">
        <input type="radio" onclick="pagoTiempo(2)"> Por Pagar
      </label>
      <div id="error_tipo_pago_tiempo" class="text-danger"></div>
    </div>
  </div>
  <div id="div_opcion_pago_tiempo" class="col-md-10 row"></div>
  <div id="div_error_opcion_pago_tiempo" class="text-danger"></div>`);
  }
}

function pagoTiempo(opcion) {
  document.getElementById("div_opcion_pago_tiempo").innerHTML = "";
  document.getElementById("tipo_pago_tiempo").value = opcion;
  document.getElementById("error_tipo_pago_tiempo").textContent = "";
  if (opcion == 1) {
    $.ajax({
      url: `${urls}permisos/lista_pago_tiempo`,
      type: "post",
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (arrayTimePay) {
        if (arrayTimePay != false) {
          i = 1;
          arrayTimePay.forEach((t) => {
            i++;
            $("#div_opcion_pago_tiempo")
              .append(`<div class="col-md-2" id="div_chbx_${t.id_item}">
            <div class="form-check">
              <input type="checkbox" name="id_item_[]" id="id_item_${t.id_item}" value="${t.id_item}" class="form-check-input" style="width: 30px;;height: calc(2.25rem + 2px)">
              <label class="form-check-label" for="id_item_${t.id_item}" style="margin-left: 1rem">
                <span class="float-right">Tiempo: <b>${t.time_pay}</b> <br>  Pagado: <b>${t.day_to_pay}</b></span>
              </label>
            </div>
            </div>`);

            if (i % 2 === 0) {
              document.getElementById(
                `div_chbx_${t.id_item}`
              ).style.backgroundColor = "#F3F3F3";
            }
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "No se encontraron datos",
            text: "No existen registros de pago de tiempo.",
          });
        }
      },
    });
  } else {
    $("#div_opcion_pago_tiempo")
      .append(`<div class="alert alert-info alert-dismissible col-md-12" role="alert" style="text-align: center;font-size: 25px;">
      <strong>
        <i class="fas fa-exclamation-circle"></i>  No olvides generar tus Pagos de Tiempo..
      </strong>
    </div>`);
  }
}
