/**
 * ARCHIVO MODULO PAPELERIA
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
var nombreUsuario = {};
$(document).ready(function () {
  $("#name_by_user").select2();
  nombreUsuarioArchivo();
  /*   $("#name_user").select2();
  nombreUsuarioArchivo() */
});

function validarInput(campo) {
  const input = campo;
  if (input.value.length > 0) {
    input.classList.remove("has-error");
    $("#error_" + input.id).text("");
  }
}

async function nombreUsuarioArchivo() {
  nombreUsuario = {};
  $.ajax({
    url: `${urls}viajes/nombres_usuarios_archivo`,
    type: "POST",
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (name_ajax) {
      console.log("result ajax 1: ");
      // console.log(name_ajax);
      if (name_ajax != null) {
        name_ajax.reduce((obj, usuario) => {
          nombreUsuario[usuario.id_user] = usuario.user_name;
        }, {});
      }
    },
  });
  console.log(nombreUsuario);
}

$("#form_report_by_user").submit(function (e) {
  e.preventDefault();
  var error = 0;
  const btn = document.getElementById("btn_report_by_user");
  const input = document.getElementById("name_by_user");
  if (input.value.length == 0) {
    error++;
    $(input.id).addClass("has-error");
    $("#error_" + input.id).text("Campo requerido");
  } else {
    $(input.id).removeClass("has-error");
    $("#error_" + input.id).text("");
  }

  if (error != 0) {
    return false;
  }

  let timerInterval = Swal.fire({
    //se le asigna un nombre al swal
    title:
      '<i class="fas fa-file-excel" style="margin-right:5px"></i> Generando Reporte por Usuarios en Excel!',
    html: "Espere unos Segundos.",
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading(); //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });
  var param = JSON.stringify({
    id_user: input.value,
  });
  btn.disabled = true;
  const nombre = nombreUsuario[input.value];
  console.log(nombre);
  var nomArchivo = `Reporte_VG_${nombre}.xlsx`;
  console.log(nomArchivo);
  var pathservicehost = `${urls}/viajes/reporte_folios_activos_por_usuario`;
  var xhr = new XMLHttpRequest();
  xhr.open("POST", pathservicehost, true);
  xhr.responseType = "blob";
  //Send the proper header information along with the request
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onload = function (e) {
    btn.disabled = false;
    Swal.close(timerInterval); // cierra el swal en ejecucion (nombre del swal)
    if (xhr.readyState === 4 && xhr.status === 200) {
      $("#" + input.id)
        .val("")
        .trigger("change");
      var contenidoEnBlob = xhr.response;
      var link = document.createElement("a");
      link.href = (window.URL || window.webkitURL).createObjectURL(
        contenidoEnBlob
      );
      link.download = nomArchivo;
      var clicEvent = new MouseEvent("click", {
        view: window,
        bubbles: true,
        cancelable: true,
      });
      // $("#form_reportes_por_direccion")[0].reset();
      // $("#permisos_div").empty();
      // $("#parametro").empty();
      //Simulamos un clic del usuario
      //no es necesario agregar el link al DOM.
      link.dispatchEvent(clicEvent);
      //link.click();
    } else {
      Swal.fire({
        icon: "info",
        title: "Oops...",
        text: "No es posible acceder al archivo, probablemente no existe.",
      });
    }
  };
  xhr.send("data=" + param);
});

$("#form_report_by_date").submit(function (e) {
  e.preventDefault();
  var error = 0;
  const btn = document.getElementById("btn_report_by_date");
  const type = document.getElementById("tipo_by_date");
  const start_date = document.getElementById("fecha_inicial_by_date");
  const end_date = document.getElementById("fecha_final_by_date");

  if (type.value.length == 0) {
    error++;
    type.classList.add("has-error");
    $("#error_" + type.id).text("Campo requerido");
  } else {
    type.classList.remove("has-error");
    $("#error_" + type.id).text("");
  }

  if (start_date.value.length == 0) {
    error++;
    start_date.classList.add("has-error");
    $("#error_" + start_date.id).text("Campo requerido");
  } else {
    start_date.classList.remove("has-error");
    $("#error_" + start_date.id).text("");
  }

  if (end_date.value.length == 0) {
    error++;
    end_date.classList.add("has-error");
    $("#error_" + end_date.id).text("Campo requerido");
  } else {
    end_date.classList.remove("has-error");
    $("#error_" + end_date.id).text("");
  }

  if (
    start_date.value.length > 0 &&
    end_date.value.length > 0 &&
    start_date.value > end_date.value
  ) {
    error++;
    end_date.classList.add("has-error");
    start_date.classList.add("has-error");
    $("#error_" + end_date.id).text("Verifica Fecha");
    $("#error_" + start_date.id).text("Verifica Fecha");
  }

  if (error != 0) {
    return false;
  }

  let timerInterval = Swal.fire({
    //se le asigna un nombre al swal
    title:
      '<i class="fas fa-file-excel" style="margin-right:5px"></i> Generando Reporte por Fecha en Excel!',
    html: "Espere unos Segundos.",
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading(); //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });
  var param = JSON.stringify({
    type: type.value,
    star: start_date.value,
    end: end_date.value,
  });
  const array_tipo = { 1: "Viaticos", 2: "Gastos", 3: "Viaticos_Gastos" };
  btn.disabled = true;
  var nomArchivo = `Reporte_${array_tipo[type.value]}_${start_date.value}_${
    end_date.value
  }.xlsx`;
  console.log(nomArchivo);
  var pathservicehost = `${urls}/viajes/reporte_folios_activos_por_fecha`;
  var xhr = new XMLHttpRequest();
  xhr.open("POST", pathservicehost, true);
  xhr.responseType = "blob";
  //Send the proper header information along with the request
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onload = function (e) {
    btn.disabled = false;
    Swal.close(timerInterval); // cierra el swal en ejecucion (nombre del swal)
    if (xhr.readyState === 4 && xhr.status === 200) {
      $("#form_report_by_date")[0].reset();
      var contenidoEnBlob = xhr.response;
      var link = document.createElement("a");
      link.href = (window.URL || window.webkitURL).createObjectURL(
        contenidoEnBlob
      );
      link.download = nomArchivo;
      var clicEvent = new MouseEvent("click", {
        view: window,
        bubbles: true,
        cancelable: true,
      });
      //Simulamos un clic del usuario
      //no es necesario agregar el link al DOM.
      link.dispatchEvent(clicEvent);
      //link.click();
    } else {
      Swal.fire({
        icon: "info",
        title: "Oops...",
        text: "No es posible acceder al archivo, probablemente no existe.",
      });
    }
  };
  xhr.send("data=" + param);
});

function reportType() {
  const selectValue = document.getElementById("tipo").value;
  const inputContainer = document.getElementById("resultado_reporte");

  // Limpiar el contenedor antes de agregar el nuevo input
  inputContainer.innerHTML = "";
  $(inputContainer).removeClass("form-group col-md-3");
  // Agregar la clase "my-class" al input
  if (selectValue == 2) {
    $(inputContainer).addClass("form-group col-md-3");
    $(inputContainer).append(`
      <label for="tipo">Numero de Nomina</label>
      <input type="number" id="num_nomina" name="num_nomina" class="form-control">`);
  }
}

/*****
 * Enviar formualrio para reporte de gastos viaticos por fecha
 * ****/
$("#form_report_date").submit(function (e) {
  e.preventDefault();
  var error = 0;
  const btn = document.getElementById("btn_report");
  const type = document.getElementById("type_report");
  const start_date = document.getElementById("date_initial_report");
  const end_date = document.getElementById("date_term_report");

  if (type.value.length == 0) {
    error++;
    type.classList.add("has-error");
    $("#error_" + type.id).text("Campo requerido");
  } else {
    type.classList.remove("has-error");
    $("#error_" + type.id).text("");
  }

  if (start_date.value.length == 0) {
    error++;
    start_date.classList.add("has-error");
    $("#error_" + start_date.id).text("Campo requerido");
  } else {
    start_date.classList.remove("has-error");
    $("#error_" + start_date.id).text("");
  }

  // Verifica si el campo de fecha final está vacío
  if (end_date.value.length === 0) {
    // Incrementa la variable de error
    error++;

    // Agrega la clase CSS de error al campo de fecha final
    end_date.classList.add("has-error");

    // Muestra un mensaje de error asociado al campo de fecha final
    $(`#error_${end_date.id}`).text("Campo requerido");
  } else {
    // Si el campo no está vacío, elimina la clase de error
    end_date.classList.remove("has-error");

    // Borra el mensaje de error asociado al campo de fecha final
    $(`#error_${end_date.id}`).text("");
  }

  if (
    start_date.value.length > 0 &&
    end_date.value.length > 0 &&
    start_date.value > end_date.value
  ) {
    error++;
    end_date.classList.add("has-error");
    start_date.classList.add("has-error");
    $(`#error_${end_date.id}`).text("Verifica Fecha");
    $(`#error_${start_date.id}`).text("Verifica Fecha");
  }

  if (error != 0) {
    return false;
  }

  let timerInterval = Swal.fire({
    //se le asigna un nombre al swal
    title:
      '<i class="fas fa-file-excel" style="margin-right:5px"></i> Generando Reporte por Fecha en Excel!',
    html: "Espere unos Segundos.",
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading(); //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });
  var param = JSON.stringify({
    type: type.value,
    star: start_date.value,
    end: end_date.value,
  });
  const array_tipo = { 1: "Viaticos", 2: "Gastos", 3: "Viaticos_Gastos" };
  btn.disabled = true;
  var nomArchivo = `Reporte_${array_tipo[type.value]}_${start_date.value}_${
    end_date.value
  }.xlsx`;
  console.log(nomArchivo);
  var pathservicehost = `${urls}/viajes/reporte_folios_comparativos`;
  var xhr = new XMLHttpRequest();
  xhr.open("POST", pathservicehost, true);
  xhr.responseType = "blob";
  //Send the proper header information along with the request
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onload = function (e) {
    btn.disabled = false;
    Swal.close(timerInterval); // cierra el swal en ejecucion (nombre del swal)
    if (xhr.readyState === 4 && xhr.status === 200) {
      $("#form_report_date")[0].reset();
      var contenidoEnBlob = xhr.response;
      var link = document.createElement("a");
      link.href = (window.URL || window.webkitURL).createObjectURL(
        contenidoEnBlob
      );
      link.download = nomArchivo;
      var clicEvent = new MouseEvent("click", {
        view: window,
        bubbles: true,
        cancelable: true,
      });
      //Simulamos un clic del usuario
      //no es necesario agregar el link al DOM.
      link.dispatchEvent(clicEvent);
      //link.click();
    } else {
      Swal.fire({
        icon: "info",
        title: "Oops...",
        text: "No es posible acceder al archivo, probablemente no existe.",
      });
    }
  };
  xhr.send("data=" + param);
});
