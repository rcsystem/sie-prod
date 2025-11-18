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

$(document).ready(function () {
  tbl_request = $("#tbl_solicitudes_pagadas")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}finanzas/tbl_solicitudes_pagadas`,
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
          data: "realizada",
          title: "Realizada",
          className: "text-center",
          render: function (data, type, row) {
            const checked = data === "1" ? "checked" : "";
            return `<input type="checkbox" class="chk-realizada" data-id="${row.id_request}" ${checked}>`;
          },
        },
        {
          data: "id_request",
          title: "Folio",
          className: "text-center",
        },
        {
          data: "id_epicor",
          title: "Id Epicor",
          className: "text-center",
        },
        {
          data: "company",
          title: "Empresa",
          className: "text-center",
        },

        {
          data: "application_concept",
          title: "Concepto",
          className: "text-center",
        },
        {
          data: "period",
          title: "Periodo",
          className: "text-center",
        },
        {
          data: "amount",
          title: "Cantidad",
          className: "text-center",
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
            if (data["status_request"] != "") {
              switch (data["status_request"]) {
                case "1":
                  return `<span class="badge">Pendiente</span>`;
                  break;
                case "2":
                  return `<span class="badge">Autorizada</span>`;
                  break;
                case "3":
                  return `<span class="badge">Aprobada</span>`;
                  break;
                case "4":
                  return `<span class="badge">Pagada</span>`;
                  break;
                case "5":
                  return `<span class="badge">Rechazada</span>`;
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
          className: "text-center",
          render: function (data, type, row) {
            if (!data) return "";
            // Si el formato es "YYYY-MM-DD HH:mm:ss", separamos la fecha
            return data.split(" ")[0];
          },
        },

        {
          data: null,
          title: "Acciones",
          className: "text-center",
          render: function (data) {
            // console.log(data);

            let itemsArray = data["items"] ? data["items"].split(", ") : [];
            let rutasArray = data["rutas"] ? data["rutas"].split(", ") : [];
            let tiposArray = data["tipo"] ? data["tipo"].split(", ") : [];

            let itemsLinks = itemsArray
              .map((item, index) => {
                let tipo = tiposArray[index] ? tiposArray[index].trim() : "";
                let ruta = rutasArray[index] ? rutasArray[index].trim() : "#";

                // Asignar icono según el tipo de archivo
                let icono = '<i class="fas fa-file"></i>'; // Icono por defecto
                let action = "";

                if (tipo === "1") {
                  // PDF
                  icono = '<i class="fas fa-file-pdf text-danger"></i>';
                  action = `onclick="openPdfModal('${urls}public/${ruta}',${data["id_request"]})"`;
                } else if (tipo === "2") {
                  // Excel
                  icono = '<i class="fas fa-file-excel text-success"></i>';
                  action = `onclick="downloadFile('${urls}public/${ruta}')"`;
                } else if (tipo === "3") {
                  // TXT
                  icono = '<i class="fas fa-file-alt text-dark"></i>';
                  action = `onclick="downloadFile('${urls}public/${ruta}')"`;
                } else if (tipo === "4") {
                  // SUA
                  icono = '<i class="fas fa-file text-secondary"></i>';
                  action = `onclick="downloadFile('${urls}public/${ruta}')"`;
                } else if (tipo === "5") {
                  // SUA
                  icono = '<i class="fas fa-file text-secondary"></i>';
                  action = `onclick="downloadFile('${urls}public/${ruta}')"`;
                } else if (tipo === "6") {
                  // SUA
                  icono = '<i class="fas fa-file text-secondary"></i>';
                  action = `onclick="downloadFile('${urls}public/${ruta}')"`;
                }

                return `
                <a class="dropdown-item" style="cursor:pointer;" ${action}>
                  ${icono} ${item.trim()}
                </a>
              `;
              })
              .join("");

            return `
              <div class="float-left mr-auto">
                <div class="btn-group" role="group">
                  <button id="btnGroupDropPermisos" type="button" class="btn btn-outline-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-file-archive"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-left">
                    ${
                      itemsLinks ||
                      '<a class="dropdown-item text-muted">No hay archivos</a>'
                    }
                  </div>
                </div>
                 <button type="button" class="btn btn-outline-primary btn-sm" onclick="downloadZip(${
                   data["id_request"]
                 } )">
                  <i class="fas fa-download"></i> 
                </button>
                <button type="button" class="btn btn-outline-primary btn-naranja btn-sm" onclick="updateIdEpicor(${
                  data["id_request"]
                } )">
                  <i class="fas fa-sync-alt"></i>
 
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

      order: [[1, "DESC"]],

      createdRow: (row, data) => {
        $(row).attr("id", "solicitud_" + data.id_request);

        // Pinta la celda de estatus con colores según el estado
        let statusCell = $(row).find("td:eq(7)"); // Índice de la columna "Estatus"
        switch (data.status_request) {
          case "1":
            statusCell.css("background-color", "#ffcc00"); // Amarillo
            break;
          case "2":
            statusCell.css("background-color", "#99ccff"); // Azul
            break;
          case "3":
            statusCell.css("background-color", "#99ff99"); // Verde
            $(row).find(".btn-outline-secondary").prop("disabled", true); // Deshabilita botón de subir comprobante
            break;
          case "4":
            statusCell.css("background-color", "#00bc8c"); // Verde oscuro
            break;
          case "5":
            statusCell.css("background-color", "#ff6666"); // Rojo fuerte
            break;
        }
      },
    })
    .DataTable();
  $("#tbl_pago_adm thead").addClass("text-center");
});

function updateIdEpicor(id_request) {
  // Abrir el modal al hacer clic en el botón
  $("#id_requests").val(id_request);
  $("#epicorModal").modal("show");
}

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

function openPdfModal(ruta, id_request) {
  document.getElementById("id_solicitud").value = id_request;
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

function uploadPaymentCommitment(id_request) {
  // Abrir el modal al hacer clic en el botón
  $("#id_request").val(id_request);
  $("#comprobanteModal").modal("show");
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
    url: `${urls}finanzas/descargar_solicitudes/${id_request}`,
    method: "GET",
    xhrFields: {
      responseType: "blob", // Indicar que esperamos un archivo binario
    },
    success: function (data) {
      // Crear un enlace temporal para descargar el archivo
      const link = document.createElement("a");
      link.href = window.URL.createObjectURL(data);
      link.download = "solicitud_" + id_request + ".zip";
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

$("#subirComprobante").submit(function (e) {
  e.preventDefault();
  $(".btn-guardar").prop("disabled", true).text("Subiendo...");

  let comprobante = document.getElementById("comprobante");

  // CORREGIDO: Verificamos si se seleccionó un archivo
  var error_comprobante =
    comprobante.files.length === 0 ? "⚠️ Debes seleccionar un archivo." : "";
  document.getElementById("error_comprobante").textContent = error_comprobante;

  if (error_comprobante) {
    console.log("Error en la validación");
    $(".btn-guardar").prop("disabled", false).text("Subir comprobante"); // Habilitar el botón de nuevo
    return false; // Detener la ejecución si falta el archivo
  }

  // Crear objeto FormData para enviar archivos
  var formData = new FormData();
  formData.append("comprobante", $("#comprobante")[0].files[0]); // Archivo
  formData.append("comentario", $("#comentario").val()); // Comentario
  formData.append("id_request", $("#id_request").val()); // id_request

  $.ajax({
    url: `${urls}finanzas/subir_comprobante`, // Ruta en CodeIgniter
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    dataType: "json",
    beforeSend: function () {
      $(".btn-guardar").prop("disabled", true).text("Subiendo...");
    },
    success: function (response) {
      $(".btn-guardar").prop("disabled", false).text("Subir comprobante");

      if (response.success) {
        tbl_request.ajax.reload(null, false);
        alert("Comprobante subido correctamente.");
        $("#subirComprobante")[0].reset(); // Limpiar formulario
      } else {
        alert("Error: " + response.error);
      }
    },
    error: function () {
      $(".btn-guardar").prop("disabled", false).text("Subir comprobante");
      alert("Hubo un error en la subida.");
    },
  });
});

$("#tbl_solicitudes_pagadas").on("change", ".chk-realizada", function () {
  const id = $(this).data("id");
  const realizada = $(this).is(":checked") ? 1 : 0;

  $.ajax({
    url: `${urls}finanzas/marcar_realizada`,
    method: "POST",
    data: {
      id_request: id,
      realizada: realizada,
    },
    success: function (resp) {
      console.log("Actualizado correctamente");
    },
    error: function () {
      alert("Error al actualizar el estado");
    },
  });
});

$("#actualizarIdEpicor").submit(function (e) {
  e.preventDefault();
  $("#btn_actualizar_id_epicor").prop("disabled", true).text("Actualizando...");

  let id_requests = document.getElementById("id_requests");
  let id_epicor = document.getElementById("id_epicor");

  // CORREGIDO: Verificamos si se seleccionó un archivo
  var error_id_epicor =
    id_epicor.value.trim() === "" ? "⚠️ Debes ingresar un ID Epicor." : "";
  document.getElementById("error_id_epicor").textContent = error_id_epicor;

  if (error_id_epicor) {
    console.log("Error en la validación");
    $("#btn_actualizar_id_epicor")
      .prop("disabled", false)
      .text("Actualizar Id Epicor"); // Habilitar el botón de nuevo
    return false; // Detener la ejecución si falta el archivo
  }

  // Crear objeto FormData para enviar archivos
  var formData = new FormData();
  formData.append("id_requests", id_requests.value); // id_requests
  formData.append("id_epicor", id_epicor.value); // id_ep

  $.ajax({
    url: `${urls}finanzas/actualizar_id_epicor`, // Ruta en CodeIgniter
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    dataType: "json",
    beforeSend: function () {
      $("#btn_actualizar_id_epicor")
        .prop("disabled", true)
        .text("Actualizando...");
    },
    success: function (response) {
      $("#btn_actualizar_id_epicor")
        .prop("disabled", false)
        .text("Actualizar Id Epicor");

      if (response.success) {
        tbl_request.ajax.reload(null, false);
        alert("ID Epicor actualizado correctamente.");
        $("#actualizarIdEpicor")[0].reset(); // Limpiar formulario
      } else {
        alert("Error: " + response.error);
      }
    },
    error: function () {
      $("#btn_actualizar_id_epicor")
        .prop("disabled", false)
        .text("Actualizar Id Epicor");
      alert("Hubo un error en la subida.");
    },
  });
});
