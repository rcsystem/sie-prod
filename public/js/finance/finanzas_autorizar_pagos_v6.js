$(document).ready(function () {
  $('[data-toggle="tooltip"]').tooltip(); // Inicializar tooltips
  tbl_request = $("#tbl_autorizar_pagos")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}finanzas/tbl_autorizar_pagos`,
        dataSrc: "data",
      },
      lengthChange: true,
      ordering: true,
      responsive: true,
      autoWidth: false,
      rowId: "staffId",
      dom: "lBfrtip",
      buttons: [
        {
          extend: "excelHtml5",
          title: "Solicitudes de Pago",
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5],
          },
        },
        /* {
               extend:'pdfHtml5',
               title:'Listado de Proveedores',
               exportOptions:{
                 columns:[1,2,3,4,5,6,7]
               }
             } */
      ],
      language: {
        url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
      },
      columns: [
        {
          data: "id_request",
          title: "Folio",
          className: "text-center concepto-wrap",
        },
        {
          data: "user_name",
          title: "Solicitante",
          className: "text-center concepto-wrap",
        },
        {
          data: "nombre_empresa",
          title: "Empresa",
          className: "text-center concepto-wrap",
        },

        {
          data: "concepto",
          title: "Concepto",
          className: "text-center concepto-wrap",
        },
        
        
        {
          data: "cantidad",
          title: "Cantidad",
          className: "text-center concepto-wrap",
          render: function (data, type, row) {
            // Agregar comas a la cantidad
            if (type === "display" || type === "filter") {
              return (
                "$" +
                parseFloat(data).toLocaleString(undefined, {
                  minimumFractionDigits: 2,
                  maximumFractionDigits: 2,
                })
              );
            }
            return data;
          },
        },

        {
          data: null,
          render: function (data, type, full, meta) {
            if (data["request_status"] != "") {
              switch (data["request_status"]) {
                case "1":
                  return `<span class="bg-amarillo">Pendiente</span>`;
                  break;
                case "2":
                  return `<span class="bg-verde">Aprobada</span>`;
                  break;
                case "3":
                  return `<span class="bg-azul">Autorizada</span>`;
                  break;
                case "4":
                  return `<span class="bg-teals">Pagada</span>`;
                  break;
                case "5":
                  return `<span class="bg-rojo">Rechazada</span>`;
                  break;

                default:
                  return `<span class="badge badge">Error</span>`;
                  break;
              }
            } else {
              return "----";
            }
          },
          title: "Estatus",
          className: "text-center",
        },
        {
          data: "created_at",
          title: "Fecha",
          className: "text-center concepto-wrap",
          render: function (data, type, row) {
            let date = new Date(data);
            let opciones = { day: "numeric", month: "short", year: "numeric" };
            return date.toLocaleDateString("es-ES", opciones);
          },
        },
        {
          data: "banco",
          title: "Banco",
          className: "text-center concepto-wrap",
        },

        {
          data: null,
          title: "Acciones",
          className: "text-center",
          render: function (data) {
            // console.log(data);
            if (!data || !data["ruta_pdf"]) {
              return `<span class="text-muted">Sin archivo</span>`;
            }
            const url = data["ruta_pdf"];
            const filename = url.split("/").pop();
            return `
              <div class="float-left mr-auto">
                <div class="btn-group" role="group">
                  <button id="btnGroupDropPermisos" type="button" class="btn btn-outline-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-file-archive"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-left">
                     <a class="dropdown-item" style="cursor:pointer;" onclick="openPdfModal('${urls}${data["ruta_pdf"]}',${data["id_request"]},${data["request_status"]})">
                  <i class="fas fa-file-pdf text-danger"></i> 
                   
                    ${filename}
                </a>
                  </div>
                </div>
                  <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Descarga de Archivos" onclick="downloadZip(${data["id_request"]} )">
                  <i class="fas fa-download"></i> 
                </button>
                <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Rechazar Solicitud" onclick="handleDeletePaymentRequest(${data["id_request"]})">
                  <i class="fas fa-power-off"></i>
                </button>
              </div>
            `;
          },
        },
      ],
      destroy: "true",
      columnDefs: [
        /* {
             targets: [0],
             visible: false,
             searchable: false,
           },  */
      ],

      order: [[0, "DESC"]],

      createdRow: (row, data) => {
        $(row).attr("id", "solicitud_" + data.id_request);

        // Pinta la celda de estatus con colores según el estado
        let statusCell = $(row).find("td:eq(5)"); // Índice de la columna "Estatus"
        //const estatus = data[7]; // columna "Estatus"
        switch (data["request_status"]) {
          case "1":
            // statusCell.css("background-color", "#ffcc00");
            statusCell.addClass("bg-amarillo");
            break; // Amarillo
          case "2":
            //statusCell.css("background-color", "#99ff99");
            statusCell.addClass("bg-verde");
            break; // Verde
          case "3":
            //statusCell.css("background-color", "#99ccff");
            statusCell.addClass("bg-azul");
            break; // Rojo
          case "4":
            //statusCell.css("background-color", "#00bc8c");
            statusCell.addClass("bg-teals");
            break; // Rojo fuerte
          case "5":
            // statusCell.css("background-color", "#ff6666");
            statusCell.addClass("bg-rojo");
            break; // Rojo fuerte
        }
      },
    })
    .DataTable();
  $("#tbl_autorizar_pagos thead").addClass("text-center");
});

function aplicarEstilosResponsive() {
  $('li[data-dtr-index="5"]').each(function () {
    const valor = $(this).text().trim();
    console.log("RESPONSIVE:", valor);

    // Elimina clases previas para evitar duplicados
    $(this).removeClass("bg-teals bg-verde bg-azul bg-rojo");

    switch (valor) {
      case "Estatus Pagada":
        $(this).addClass("bg-teals");
        break;
      case "Estatus Autorizada":
        $(this).addClass("bg-azul");
        break;
      case "Estatus Aprobada":
        $(this).addClass("bg-verde");
        break;
      case "Estatus Rechazada":
        $(this).addClass("bg-rojo");
        break;
    }
  });
}

// Aplica al iniciar DataTable
$('#tbl_autorizar_pagos').on('init.dt', function () {
  setTimeout(aplicarEstilosResponsive, 100);
});

// Aplica después de cada draw
$('#tbl_autorizar_pagos').on('draw.dt', function () {
  setTimeout(aplicarEstilosResponsive, 100);
});

// Aplica también cuando se expande/cierra una fila responsive
$('#tbl_autorizar_pagos').on('responsive-display.dt', function () {
  setTimeout(aplicarEstilosResponsive, 100);
});



$("#uploadForm").submit(function (e) {
  e.preventDefault();

  var fileInput = $("#pdfFile")[0].files[0]; // Asegurar que hay un archivo seleccionado
  if (!fileInput) {
    alert("Por favor, selecciona un archivo.");
    return;
  }

  // Modificar el nombre del archivo: quitar espacios y reemplazar con "_"
  var nuevoNombre = fileInput.name.replace(/\s+/g, "_");

  // Crear FormData y añadir el archivo con el nuevo nombre
  var formData = new FormData(this);
  formData.delete("pdfFile"); // Eliminar la entrada original
  formData.append("pdfFile", fileInput, nuevoNombre); // Agregar el archivo con el nuevo nombre

  $.ajax({
    url: `${urls}finanzas/subir_pdf`, // Ruta del controlador en CodeIgniter 4
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      var pdfUrl = response.pdfUrl; // Asumiendo que la respuesta contiene la URL del PDF subido

      $("#pdfObject").attr("height", "600");
      $("#pdfObject").attr("data", pdfUrl);
      $("#signBtn").show(); // Mostrar el botón de firma
    },
    error: function () {
      alert("Hubo un error al subir el archivo.");
    },
  });
});

$("#signBtn2").click(function () {
  var pdfUrl = $("#pdfObject").attr("data");
  var id = $("#id_solicitud").val();

  var dataFirma = new FormData();
  dataFirma.append("pdfPath", pdfUrl);
  dataFirma.append("id_request", id);

  console.log("pdf:", pdfUrl);

  $.ajax({
    url: `${urls}finanzas/autoriza_pago_talento`, // Ruta del controlador en CodeIgniter 4
    type: "POST",
    data: dataFirma,
    contentType: false, // Evita que jQuery establezca un tipo de contenido incorrecto
    processData: false, // Evita que jQuery intente procesar FormData
    dataType: "json", // Asegura que la respuesta se maneje como JSON
    success: function (response) {
      var signedPdfUrl = response.signedPdfUrl;
      $("#pdfObject").attr("data", signedPdfUrl); // Mostrar el PDF firmado
      $("#signBtn").prop("disabled", true); // Deshabilitar en otros casos
    },
    error: function () {
      // alert("Hubo un error al firmar el archivo");
    },
  });
});

$("#signBtnANT").click(function () {
  const modalEl = document.getElementById("autorizarSolicitudModal");
  const id = $("#id_solicitud").val();
  const pdfUrl = $("#pdfObject").attr("data");

  // 1) Desactivar interacciones en el modal de Bootstrap
  modalEl.inert = true;

  // 2) Mostrar loader mientras pedimos o reutilizamos el código
  Swal.fire({
    title: "Procesando solicitud…",
    text: "Por favor espera",
    allowOutsideClick: false,
    didOpen: () => Swal.showLoading(),
  });

  // 3) Llamada al backend para crear o reutilizar el código
  $.post(`/finanzas/crear_codigo/${id}`)
    .done((resp) => {
      Swal.close();

      if (resp.signed_up) {
        Swal.fire({
          icon: "info",
          title: "Documento ya firmado",
          html: `El documento ya ha sido firmado por el usuario <b>${resp.user_name}</b>.`,
          showCancelButton: true,
          cancelButtonText: "Cerrar",
        }).then(() => (modalEl.inert = false));
        return;
      }

      // Si el código ya existía y sigue vigente
      if (resp.reused) {
        Swal.fire({
          icon: "info",
          title: "Código ya generado",
          html: `Ya existe un código válido para el folio <b>#${resp.requestId}</b>.<br>
                 Revisa tu correo e ingresa ese mismo código.`,
        }).then(() => openCodePrompt(resp.requestId));
      } else {
        // Se generó uno nuevo
        Swal.fire({
          icon: "success",
          title: "Código enviado",
          html: `Se ha enviado un nuevo código al correo para el folio <b>#${resp.requestId}</b>.`,
          timer: 2000,
          showConfirmButton: false,
        }).then(() => openCodePrompt(resp.requestId));
      }
    })
    .fail(() => {
      Swal.fire(
        "Error",
        "No se pudo generar el código. Intenta de nuevo.",
        "error"
      ).then(() => (modalEl.inert = false));
    });

  // Función que abre el prompt para ingresar el código
  function openCodePrompt(requestId) {
    Swal.fire({
      title: `Folio #${requestId}`,
      text: "Ingresa el código que recibiste por correo",
      input: "text",
      inputPlaceholder: "Código de validación",
      showCancelButton: true,
      confirmButtonText: "Verificar",
      cancelButtonText: "Cancelar",
      allowOutsideClick: () => !Swal.isLoading(),
      didOpen: () => {
        Swal.getInput().focus();
      },
      preConfirm: (code) => {
        code = code.trim();
        if (!code) {
          Swal.showValidationMessage("El código es obligatorio");
          return false;
        }
        // Llamada AJAX para verificar el código y firmar
        return $.post("/finanzas/autoriza_pago_talento", {
          id_request: requestId,
          code: code,
          pdfPath: pdfUrl,
        })
          .then((res) => {
            if (res.status !== "ok") {
              throw new Error(res.message || "Código inválido");
            }
            var signedPdfUrl = res.signedPdfUrl;
            $("#pdfObject").attr("data", signedPdfUrl); // Mostrar el PDF firmado
            $("#signBtn").prop("disabled", true); // Deshabilitar en otros casos
            return res;
          })
          .catch((err) => {
            Swal.showValidationMessage(err.message);
          });
      },
      willClose: () => {
        // Restaurar el foco en el modal
        modalEl.inert = false;
      },
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire("¡Éxito!", "PDF firmado correctamente.", "success");
        // Aquí puedes actualizar UI, ocultar botón o recargar

        tbl_request.ajax.reload(null, false);
      }
    });
  }
});

$("#signBtn").click(function () {
  // 2) Mostrar loader mientras pedimos o reutilizamos el código
  Swal.fire({
    title: "Procesando solicitud…",
    text: "Por favor espera",
    allowOutsideClick: false,
    didOpen: () => Swal.showLoading(),
  });

  var pdfUrl = $("#pdfObject").attr("data");
  var id = $("#id_solicitud").val();

  var dataFirma = new FormData();
  dataFirma.append("pdfPath", pdfUrl);
  dataFirma.append("id_request", id);

  console.log("pdf:", pdfUrl);

  $.ajax({
    url: `${urls}finanzas/autoriza_pago_talento`, // Ruta del controlador en CodeIgniter 4
    type: "POST",
    data: dataFirma,
    contentType: false, // Evita que jQuery establezca un tipo de contenido incorrecto
    processData: false, // Evita que jQuery intente procesar FormData
    dataType: "json", // Asegura que la respuesta se maneje como JSON
    success: function (response) {
      Swal.close();
      var signedPdfUrl = response.signedPdfUrl;
      $("#pdfObject").attr("data", signedPdfUrl); // Mostrar el PDF firmado
      $("#signBtn").prop("disabled", true); // Deshabilitar en otros casos
    },
    error: function () {
      Swal.close();
      // alert("Hubo un error al firmar el archivo");
    },
  });
});

$("#btn-signed").click(function () {
  var pdfUrl = $("#pdfObject").attr("data");
  var id = $("#id_solicitud").val();

  var dataFirma = new FormData();
  dataFirma.append("pdfPath", pdfUrl);
  dataFirma.append("id_request", id);

  // Inicializar la barra de progreso
  $("#progressBar").css("width", "0%");
  $("#progressBar").attr("aria-valuenow", 0);
  // El modal ya está abierto, no es necesario abrirlo aquí
  $("#progressContainer").show(); // Mostrar la barra de progreso

  // Simular progresión mientras se hace la solicitud AJAX
  var progress = 0;

  var interval = setInterval(function () {
    if (progress < 90) {
      progress += 10;
      $("#progressBar").css("width", progress + "%");
      $("#progressBar").attr("aria-valuenow", progress);
    }
  }, 500);

  $.ajax({
    url: `${urls}finanzas/pagar_solicitud_talento`, // Ruta del controlador en CodeIgniter 4
    type: "POST",
    data: dataFirma,
    contentType: false, // Evita que jQuery establezca un tipo de contenido incorrecto
    processData: false, // Evita que jQuery intente procesar FormData
    dataType: "json", // Asegura que la respuesta se maneje como JSON
    success: function (response) {
      // Detener la animación de la barra de progreso
      clearInterval(interval);

      console.log("response: ", response);

      // Completar la barra de progreso
      $("#progressBar").css("width", "100%");
      $("#progressBar").attr("aria-valuenow", 100);

      // Ocultar la barra de progreso
      $("#progressContainer").hide();
      $("#progressBar").empty();
      tbl_request.ajax.reload(null, false);

      if (response.status) {
        // Si la respuesta tiene status true, muestra un mensaje de éxito
        //alert(response.message);  // Muestra el mensaje que devuelve el servidor
        $("#successMessage").show();
        $("#successText").text(response.message);
      } else {
        // Si la respuesta tiene status false, muestra un mensaje de error
        $("#successMessage").show();
        $("#successText").text(response.message);
        //alert(response.message);  // Muestra el mensaje de error que devuelve el servidor
      }
    },
    error: function () {
      // Completar la barra de progreso
      $("#progressBar").css("width", "100%");
      $("#progressBar").attr("aria-valuenow", 100);
      tbl_request.ajax.reload(null, false);
      // Ocultar la barra de progreso
      $("#progressContainer").hide();
      // Ocultar la barra de progreso
      $("#progressContainer").empty();

      /*   // Mostrar el mensaje de éxito
      $("#successMessage").show();
      $("#successText").text(response.message); */

      //  alert("Hubo un error al firmar el archivo");
    },
  });
});

$("#tipo_nomina").on("change", function () {
  if ($("#tipo_nomina").val() == "Quincenal") {
    CargarQuincenas();
  }

  if ($("#tipo_nomina").val() == "Semanal") {
    CargarSemanas();
  }
});

$(document).ready(function () {
  $(".form-control").each(function () {
    actualizarEstado($(this));
  });

  $(document).on("blur", ".form-control", function () {
    actualizarEstado($(this));
  });

  $(document).on("focus", ".form-control", function () {
    $(this).parent(".form-group").addClass("fill");
  });

  $(document).on("change", ".form-control", function () {
    actualizarEstado($(this)); // Para detectar cambios en el select
  });

  function actualizarEstado(f) {
    if (!f || f.length === 0) return; // Evita errores si f no existe

    var g = (f.attr("placeholder") || "").length; // Evita error si placeholder no existe

    if (f.is("select")) {
      // Si el select tiene un valor válido, agregar la clase "fill"
      if (f.val()) {
        f.parent(".form-group").addClass("fill");
      } else {
        f.parent(".form-group").removeClass("fill");
      }
    } else {
      // Para los inputs normales
      if (f.val().length > 0 || g > 0) {
        f.parent(".form-group").addClass("fill");
      } else {
        f.parent(".form-group").removeClass("fill");
      }
    }
  }
});

$("#uploadForm").submit(function (e) {
  e.preventDefault();

  var empresa = document.getElementById("empresa").value.trim();
  var concepto = document.getElementById("concepto").value.trim();
  var mes_solicitud = document.getElementById("mes_solicitud").value.trim();
  var tipo_nomina = document.getElementById("tipo_nomina").value.trim();
  var periodo = document.getElementById("periodo").value.trim();
  var fecha = document.getElementById("fecha").value.trim();
  var monto = document.getElementById("monto").value.trim();
  var observaciones = document.getElementById("observaciones").value.trim();

  var error_empresa = empresa.length === 0 ? "El campo es requerido" : "";
  document.getElementById("error_empresa").textContent = error_empresa;

  var error_concepto = concepto.length === 0 ? "El campo es requerido" : "";
  document.getElementById("error_concepto").textContent = error_concepto;

  var error_mes = mes_solicitud.length === 0 ? "El campo es requerido" : "";
  document.getElementById("error_mes").textContent = error_mes;

  var error_tipo = tipo_nomina.length === 0 ? "El campo es requerido" : "";
  document.getElementById("error_tipo").textContent = error_tipo;

  var error_periodo = periodo.length === 0 ? "El campo es requerido" : "";
  document.getElementById("error_periodo").textContent = error_periodo;

  var error_fecha = fecha.length === 0 ? "El campo es requerido" : "";
  document.getElementById("error_fecha").textContent = error_fecha;

  var error_monto = monto.length === 0 ? "El campo es requerido" : "";
  document.getElementById("error_monto").textContent = error_monto;

  if (
    error_empresa ||
    error_concepto ||
    error_mes ||
    error_tipo ||
    error_periodo ||
    error_fecha ||
    error_monto
  ) {
    console.log("Error en la validación");
    return false;
  }

  // ✅ Crear FormData para enviar archivos y demás datos
  var formData = new FormData(document.getElementById("uploadForm"));

  Swal.fire({
    title: "Generando Solicitud...",
    allowOutsideClick: false,
    showConfirmButton: false,
    willOpen: () => {
      Swal.showLoading();
    },
  });

  $.ajax({
    url: `${urls}finanzas/generar_solicitud_pago`,
    type: "POST",
    dataType: "json",
    data: formData, // ✅ Enviar FormData en lugar de serialize()
    contentType: false, // ✅ Necesario para enviar archivos
    processData: false, // ✅ Necesario para evitar que jQuery procese FormData
    success: function (response) {
      Swal.close();
      if (response.status === "success") {
        var miModal = new bootstrap.Modal(
          document.getElementById("solicitudModal")
        );
        miModal.hide();
        Swal.fire({
          icon: "success",
          title: "¡Solicitud generada con éxito!",
          html: `<p style="font-size: 25px;"><b>Id: ${response.id}</b></p>`,
        });

        // ✅ Quitar la clase "fill" después de la respuesta
        $(".form-group").removeClass("fill");

        $("#uploadForm")[0].reset(); // ✅ Limpiar el formulario
        tbl_request.ajax.reload(null, false);
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió mal. Contactar con el Administrador",
        });
      }
    },
    error: function () {
      Swal.close();
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Hubo un problema en la solicitud",
      });
    },
  });
});

function abrirSolicitudModal() {
  var miModal = new bootstrap.Modal(document.getElementById("solicitudModal"));
  miModal.show();
}

function downloadFile(ruta) {
  let link = document.createElement("a");
  link.href = ruta;
  link.setAttribute("download", "");
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}

function openPdfModal(ruta, id_request, status) {
  document.getElementById("id_solicitud").value = id_request;

  // Asegurar que el botón se habilite si el status es 1
  if (parseInt(status) === 1) {
    $("#signBtn").prop("disabled", false); // Habilitar si es 1
    $("#btn-signed").prop("disabled", false); // Deshabilitar en otros casos
  } else if (parseInt(status) === 2) {
    $("#signBtn").prop("disabled", false); // Habilitar si es 1
    $("#btn-signed").prop("disabled", false); // Deshabilitar en otros casos
  } else if (parseInt(status) === 3) {
    $("#signBtn").prop("disabled", false); // Deshabilitar en otros casos
    $("#btn-signed").prop("disabled", false); // Deshabilitar en otros casos
  }

  /* // Asegurar que el botón se habilite si el status es 1
if (parseInt(status) === 3) {
  $("#signBtn").prop("disabled", true);  // Deshabilitar en otros casos
  $("#btn-signed").prop("disabled", true);  // Deshabilitar en otros casos
} else {
  $("#signBtn").prop("disabled", false); // Habilitar si es 1
  $("#btn-signed").prop("disabled", false); // Habilitar si es 1
} */

  // modalBody.innerHTML = `<iframe src="${ruta}" width="100%" height="700px"></iframe>`;
  var pdfContainer = $("#pdfContainer"); // Contenedor del <object>

  // Eliminar el <object> actual
  $("#pdfObject").remove();

  // Crear un nuevo <object> con el nuevo PDF
  var nuevoObject = `<object id="pdfObject" data="${ruta}" type="application/pdf" width="100%" height="600px"></object>`;

  // Agregarlo nuevamente al contenedor
  pdfContainer.html(nuevoObject);
  // document.getElementById("pdfObject").setAttribute("data", ruta);
  $("#autorizarSolicitudModal").modal("show");
}

function handleDeletePaymentRequest(id_request) {
  Swal.fire({
    title: `Deseas Rechazar la Solicitud con Folio: ${id_request} ?`,
    text: `Rechazar solicitud!`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Rechazar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.value) {
      let dataForm = new FormData();
      dataForm.append("id_request", id_request);

      Swal.fire({
        title: "Rechazando Solicitud...",
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
          Swal.showLoading();
        },
      });

      $.ajax({
        data: dataForm, //datos que se envian a traves de ajax
        url: `${urls}finanzas/rechazar_solicitud_talento`, //archivo que recibe la peticion
        type: "post", //método de envio
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentType
        success: function (response) {
          //una vez que el archivo recibe el request lo procesa y lo devuelve
          //console.log(response);
          Swal.close();
          /*codigo que borra todos los campos del form newProvider*/
          if (response) {
            tbl_request.ajax.reload(null, false);
          } else {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Algo salió Mal! Contactar con el Administrador",
            });
          }
        },
      }).fail(function (jqXHR, textStatus, errorThrown) {
        if (jqXHR.status === 0) {
          Swal.close();
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Fallo de conexión: ​​Verifique la red.",
          });
          $("#guardar_ticket").prop("disabled", false);
        } else if (jqXHR.status == 404) {
          Swal.close();
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "No se encontró la página solicitada [404]",
          });
          $("#guardar_ticket").prop("disabled", false);
        } else if (jqXHR.status == 500) {
          Swal.close();
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Internal Server Error [500]",
          });
          $("#guardar_ticket").prop("disabled", false);
        } else if (textStatus === "parsererror") {
          Swal.close();
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Error de análisis JSON solicitado.",
          });
          $("#guardar_ticket").prop("disabled", false);
        } else if (textStatus === "timeout") {
          Swal.close();
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Time out error.",
          });
          $("#guardar_ticket").prop("disabled", false);
        } else if (textStatus === "abort") {
          Swal.close();
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Ajax request aborted.",
          });

          $("#guardar_ticket").prop("disabled", false);
        } else {
          alert("Uncaught Error: " + jqXHR.responseText);
          Swal.close();
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: `Uncaught Error: ${jqXHR.responseText}`,
          });
          $("#guardar_ticket").prop("disabled", false);
        }
      });
    }
  });
}

function downloadZip(id_request) {
  let timerInterval = Swal.fire({
    //se le asigna un nombre al swal
    title: `<i class="fas fa-file-download" style="margin-right: 10px;"></i>¡Descargando Archivos!`,
    html: "Espere unos Segundos.",
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading(); //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });

  // Hacer la solicitud AJAX
  $.ajax({
    url: `${urls}finanzas/descargar_solicitudes_talento/${id_request}`,
    method: "GET",
    xhrFields: {
      responseType: "blob", // Indicar que esperamos un archivo binario
    },
    success: function (data) {
      // Crear un enlace temporal para descargar el archivo
      const link = document.createElement("a");
      link.href = window.URL.createObjectURL(data);
      link.download = "solicitud_pago_" + id_request + ".zip";
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      Swal.close(timerInterval);
    },
    error: function (xhr, status, error) {
      alert("Error al descargar la carpeta: " + xhr.responseText);
    },
  });
}
