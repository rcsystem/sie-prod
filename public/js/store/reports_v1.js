/**
 * ARCHIVO MODULO ALMACEN
 * AUTOR:HORUS SAMAEL RIVAS PEDRAZA
 * CEL:56 2439 2632
 */

function validar() {
  if ($("#fecha_inicio").val().length > 0) {
    $("#fecha_inicio").removeClass("has-error");
    $("#error_fecha_inicio").text("");
  }
  if ($("#fecha_fin").val().length > 0) {
    $("#fecha_fin").removeClass("has-error");
    $("#error_fecha_fin").text("");
  }
}
//generar_pdf

$("#reporte_almacen").on("submit", function (e) {
  e.preventDefault();
  if ($("#fecha_inicio").val().length == 0) {
    error_fecha_inicio = "Campo Requerido";
    $("#fecha_inicio").addClass("has-error");
    $("#error_fecha_inicio").text(error_fecha_inicio);
  } else {
    error_fecha_inicio = "";
    $("#fecha_inicio").removeClass("has-error");
    $("#error_fecha_inicio").text(error_fecha_inicio);
  }
  if ($("#fecha_fin").val().length == 0) {
    error_fecha_fin = "Campo Requerido";
    $("#fecha_fin").addClass("has-error");
    $("#error_fecha_fin").text(error_fecha_fin);
  } else if ($("#fecha_inicio").val() >= $("#fecha_fin").val()) {
    error_fecha_fin = "La Fecha Final debe ser mayo a la Fecha de Inico.";
    $("#fecha_fin").addClass("has-error");
    $("#error_fecha_fin").text(error_fecha_fin);
  } else {
    error_fecha_fin = "";
    $("#fecha_fin").removeClass("has-error");
    $("#error_fecha_fin").text(error_fecha_fin);
  }
  if (error_fecha_inicio != "" || error_fecha_fin != "") {
    return false;
  }
  $("#btn_reporte_almacen").prop("disabled", true);

  let fecha_inicio = $("#fecha_inicio").val();
  let fecha_fin = $("#fecha_fin").val();

  var nomArchivo = `reporteAlmacen_${fecha_inicio}_${fecha_fin}.xlsx`;
  var param = JSON.stringify({
    fecha_inicio: fecha_inicio,
    fecha_fin: fecha_fin,
  });
  var pathservicehost = `${urls}/almacen/genera_reportes`;

  var xhr = new XMLHttpRequest();
  xhr.open("POST", pathservicehost, true);
  xhr.responseType = "blob";

  //Send the proper header information along with the request
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onload = function (e) {
    if (xhr.readyState === 4 && xhr.status === 200) {
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
      $("#btn_reporte_almacen").prop("disabled", false);
      //link.click();
    } else {
      alert(" No es posible acceder al archivo, probablemente no existe.");
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No es posible acceder al archivo, probablemente no existe.",
      });
    }
  };
  xhr.send("data=" + param);
});

$("#reporte_almacen_pdf").on("submit", function (e) {
  e.preventDefault();

  if ($("#fecha_inicio_pdf").val().length == 0) {
    error_fecha_inicio_pdf = "Campo Requerido";
    $("#fecha_inicio_pdf").addClass("has-error");
    $("#error_fecha_inicio_pdf").text(error_fecha_inicio_pdf);
  } else {
    error_fecha_inicio_pdf = "";
    $("#fecha_inicio_pdf").removeClass("has-error");
    $("#error_fecha_inicio_pdf").text(error_fecha_inicio_pdf);
  }

  if ($("#fecha_fin_pdf").val().length == 0) {
    error_fecha_fin_pdf = "Campo Requerido";
    $("#fecha_fin_pdf").addClass("has-error");
    $("#error_fecha_fin_pdf").text(error_fecha_fin_pdf);
  } else {
    error_fecha_fin_pdf = "";
    $("#fecha_fin_pdf").removeClass("has-error");
    $("#error_fecha_fin_pdf").text(error_fecha_fin_pdf);
  }
  if (error_fecha_inicio_pdf != "" || error_fecha_fin_pdf != "") {
    return false;
  }

  $("#btn_reporte_almacen_pdf").prop("disabled", true);

  let fecha_inicio = $("#fecha_inicio_pdf").val();
  let fecha_fin = $("#fecha_fin_pdf").val();

  // Crear un objeto FormData para enviar los datos del formulario
  const formData = new FormData();
  formData.append("fecha_inicio", fecha_inicio);
  formData.append("fecha_fin", fecha_fin);

  // Enviar los datos por AJAX
  $.ajax({
    url: `${urls}almacen/generar_pdf`, // URL del servidor
    method: "POST",
    data: formData,
    processData: false, // Evitar que jQuery procese los datos
    contentType: false, // Evitar que jQuery establezca el tipo de contenido
    success: function (response) {
      $("#btn_reporte_almacen_pdf").prop("disabled", false);
      console.log("Respuesta del servidor:", response);

      if (response.status === "success") {
        // Crear un enlace temporal para forzar la descarga
        var link = document.createElement("a");
        link.href = response.pdf_url; // URL del PDF generado
        link.download = "reporte_almacen.pdf"; // Nombre del archivo para descargar
        document.body.appendChild(link); // Agregar el enlace al DOM
        link.click(); // Simular clic en el enlace
        document.body.removeChild(link); // Eliminar el enlace del DOM
    } else {
        alert("Error al generar el PDF.");
    }

      // Limpiar el formulario después de guardar
      //$("#reporte_almacen_pdf")[0].reset();
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un error al guardar el activo.");
    },
  });
});
