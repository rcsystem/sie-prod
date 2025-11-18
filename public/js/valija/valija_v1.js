/**
 * ARCHIVO MODULO VALIJAS
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
//Me creo una funcion para al cambiar el select me llene un campo de texto con ese valor en este caso centro de costo dependiendo el area operativa
$("#valija_origen").on("change", function () {

  let origen = $("#valija_origen").val();

  $("#error_valija_origen").text("");
  $("#valija_origen").removeClass("has-error");

  if (origen == "OTRO") {
    //$("#inserta_otro_origen").remove();
    $("#inserta_otro_origen").addClass("form-group col-md-3");
    $("#inserta_otro_origen").append(`<label for="otro_origen">Otro Origen</label>
      <input type="text" class="form-control" name="otro_origen" id="otro_origen" onchange="validar()">
      <div id="error_otro_origen" class="text-danger"></div>  
      `);

    return false;
  } else {
    $("#inserta_otro_origen").removeClass("form-group col-md-3");
    $("#inserta_otro_origen").empty();
  }

});

//Me creo una funcion para al cambiar el select me llene un campo de texto con ese valor en este caso centro de costo dependiendo el area operativa
$("#valija_destino").on("change", function () {

  let destino = $("#valija_destino").val();
  if (destino == "OTRO") {
    $("#inserta_otro_destino").addClass("form-group col-md-3");
    $("#inserta_otro_destino").append(`<label for="otro_destino">Otro Destino</label>
      <input type="text" class="form-control" name="otro_destino" id="otro_destino" onchange="validar()">
      <div id="error_otro_destino" class="text-danger"></div>  
     `);

    return false;
  } else {
    $("#inserta_otro_destino").removeClass("form-group col-md-3");
    $("#inserta_otro_destino").empty();
  }

});

function validar() {

  if ($.trim($("#valija_origen").val()).length > 0) {
    $("#error_valija_origen").text("");
    $("#valija_origen").removeClass("has-error");
  }
  if ($.trim($("#valija_origen").val()) == "OTRO") {
    if ($.trim($("#otro_origen").val()).length > 0) {
      $("#error_otro_origen").text("");
      $("#otro_origen").removeClass("has-error");
    }
  }
  if ($.trim($("#valija_destino").val()).length > 0) {
    $("#error_valija_destino").text("");
    $("#valija_destino").removeClass("has-error");
  }
  if ($.trim($("#valija_destino").val()) == "OTRO") {
    if ($.trim($("#otro_destino").val()).length > 0) {
      $("#error_otro_destino").text("");
      $("#otro_destino").removeClass("has-error");
    }
  }
  if ($.trim($("#valija_prioridad").val()).length > 0) {
    $("#error_valija_prioridad").text("");
    $("#valija_prioridad").removeClass("has-error");
  }
  if ($.trim($("#valija_fecha").val()).length > 0) {
    $("#error_valija_fecha").text("");
    $("#valija_fecha").removeClass("has-error");
  }
  if ($.trim($("#valija_hora").val()).length > 0) {
    $("#error_valija_hora").text("");
    $("#valija_hora").removeClass("has-error");
  }
  if ($.trim($("#valija_observacion").val()).length > 0) {
    $("#error_valija_observacion").text("");
    $("#valija_observacion").removeClass("has-error");
  }
}

$("#valija").submit(function (e) {
  e.preventDefault();

  $("#guardar_valija").prop("disabled", true);

  if ($.trim($("#valija_origen").val()).length == 0) {
    var error_origen = "El campo es requerido";
    $("#error_valija_origen").text(error_origen);
    $("#valija_origen").addClass("has-error");
  } else {
    error_origen = "";
    $("#error_valija_origen").text(error_origen);
    $("#valija_origen").removeClass("has-error");
  }
  if ($.trim($("#valija_origen").val()) == "OTRO") {
    if ($.trim($("#otro_origen").val()).length == 0) {
      var error_otro_origen = "El campo es requerido";
      $("#error_otro_origen").text(error_otro_origen);
      $("#otro_origen").addClass("has-error");
    } else {
      error_otro_origen = "";
      $("#error_otro_origen").text(error_otro_origen);
      $("#otro_origen").removeClass("has-error");
    }
  } else {
    var error_otro_origen = "";
  }

  if ($.trim($("#valija_destino").val()).length == 0) {
    var error_destino = "El campo es requerido";
    $("#error_valija_destino").text(error_destino);
    $("#valija_destino").addClass("has-error");
  } else {
    error_destino = "";
    $("#error_valija_destino").text(error_destino);
    $("#valija_destino").removeClass("has-error");
  }
  if ($.trim($("#valija_destino").val()) == "OTRO") {
    if ($.trim($("#otro_destino").val()).length == 0) {
      var error_otro_destino = "El campo es requerido";
      $("#error_otro_destino").text(error_otro_destino);
      $("#otro_destino").addClass("has-error");
    } else {
      error_otro_destino = "";
      $("#error_otro_destino").text(error_otro_destino);
      $("#otro_destino").removeClass("has-error");
    }
  } else {
    var error_otro_destino = "";
  }

  if ($.trim($("#valija_prioridad").val()).length == 0) {
    var error_prioridad = "El campo es requerido";
    $("#error_valija_prioridad").text(error_prioridad);
    $("#valija_prioridad").addClass("has-error");
  } else {
    error_prioridad = "";
    $("#error_valija_prioridad").text(error_prioridad);
    $("#valija_prioridad").removeClass("has-error");
  }

  if ($.trim($("#valija_fecha").val()).length == 0) {
    var error_fecha = "El campo es requerido";
    $("#error_valija_fecha").text(error_fecha);
    $("#valija_fecha").addClass("has-error");
  } else {
    error_fecha = "";
    $("#error_valija_fecha").text(error_fecha);
    $("#valija_fecha").removeClass("has-error");
  }

  if ($.trim($("#valija_hora").val()).length == 0) {
    var error_hora = "El campo es requerido";
    $("#error_valija_hora").text(error_hora);
    $("#valija_hora").addClass("has-error");
  } else {
    error_hora = "";
    $("#error_valija_hora").text(error_hora);
    $("#valija_hora").removeClass("has-error");
  }

  if ($.trim($("#valija_observacion").val()).length == 0) {
    var error_observacion = "El campo es requerido";
    $("#error_valija_observacion").text(error_observacion);
    $("#valija_observacion").addClass("has-error");
  } else {
    error_observacion = "";
    $("#error_valija_observacion").text(error_observacion);
    $("#valija_observacion").removeClass("has-error");
  }


  if (
    error_origen != "" ||
    error_destino != "" ||
    error_prioridad != "" ||
    error_fecha != "" ||
    error_hora != "" ||
    error_observacion != "" ||
    error_otro_origen != "" ||
    error_otro_destino != ""
  ) {
    $("#guardar_valija").prop("disabled", false);
    return false;
  }


  let data = new FormData();

  data.append("origen", $("#valija_origen").val());
  data.append("destino", $("#valija_destino").val());
  data.append("prioridad", $("#valija_prioridad").val());
  data.append("fecha", $("#valija_fecha").val());
  data.append("hora", $("#valija_hora").val());
  data.append("observacion", $("#valija_observacion").val());


  let otro_origen = $("#otro_origen").val();

  if ($.trim(otro_origen).length > 0) {
    data.append("otro_origen", otro_origen);
  }

  let otro_destino = $("#otro_destino").val();

  if ($.trim(otro_destino).length > 0) {
    data.append("otro_destino", otro_destino);
  }
  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}valija/insertar`, //archivo que recibe la peticion
    type: "POST",
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    dataType: "json",
    success: function (response) {
      $("#guardar_valija").prop("disabled", false);
      if (response != "error") {
        $('#valija')[0].reset();
        $("#inserta_otro_origen").removeClass("form-group col-md-3");
        $("#inserta_otro_origen").empty();
        $("#inserta_otro_destino").removeClass("form-group col-md-3");
        $("#inserta_otro_destino").empty();
        Swal.fire("!Sea Registrado la Solicitud!", "", "success");
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador del Sistema",
        });
      }
    },
  }).fail(function (jqXHR, textStatus, errorThrown) {
    $("#guardar_valija").prop("disabled", false);

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
