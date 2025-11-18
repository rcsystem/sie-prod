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

$("#signBtn").click(function () {

  var pdfUrl = $("#pdfObject").attr("data");
  var id = $("#id_solicitud").val();

  var dataFirma = new FormData();
  dataFirma.append("pdfPath", pdfUrl);
  dataFirma.append("id_request", id);

  console.log("pdf:", pdfUrl);
  
  $.ajax({
    url: `${urls}finanzas/firmar_pdf`, // Ruta del controlador en CodeIgniter 4
    type: "POST",
    data: dataFirma,
    contentType: false,  // Evita que jQuery establezca un tipo de contenido incorrecto
    processData: false,  // Evita que jQuery intente procesar FormData
    dataType: "json",    // Asegura que la respuesta se maneje como JSON
    success: function (response) {
      var signedPdfUrl = response.signedPdfUrl;
      $("#pdfObject").attr("data", signedPdfUrl); // Mostrar el PDF firmado
    },
    error: function () {
      alert("Hubo un error al firmar el archivo");
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
        var miModal = new bootstrap.Modal(document.getElementById("solicitudModal"));
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

$(document).ready(function () {
  tbl_request = $("#tbl_pagar_solicitudes")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}finanzas/tbl_pagar_solicitudes`,
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
          render: function(data, type, row) {
            // Agregar comas a la cantidad
            if (type === 'display' || type === 'filter') {
              return "$"+parseFloat(data).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
          }
            return data;
        }
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
          data: null,
          title: "Acciones",
          className: "text-center",
          render: function (data) {
           // console.log(data);
        
            let itemsArray = data["items"] ? data["items"].split(", ") : [];
            let rutasArray = data["rutas"] ? data["rutas"].split(", ") : [];
            let tiposArray = data["tipo"] ? data["tipo"].split(", ") : [];
        
            let itemsLinks = itemsArray.map((item, index) => {
              let tipo = tiposArray[index] ? tiposArray[index].trim() : "";
              let ruta = rutasArray[index] ? rutasArray[index].trim() : "#";
              
              // Asignar icono según el tipo de archivo
              let icono = '<i class="fas fa-file"></i>'; // Icono por defecto
              let action = "";
        
              if (tipo === "1") { // PDF
                icono = '<i class="fas fa-file-pdf text-danger"></i>';
                action = `onclick="openPdfModal('${urls}public/${ruta}',${data["id_request"]})"`;
              } else if (tipo === "2") { // Excel
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
              }
              else if (tipo === "5") {
                // SUA
                icono = '<i class="fas fa-file text-secondary"></i>';
                action = `onclick="downloadFile('${urls}public/${ruta}')"`;
              }if (tipo === "6") {
                  // PDF
                  icono = '<i class="fas fa-file-pdf text-danger"></i>';
                  action = `onclick="openPdfModal('${urls}public/${ruta}',${data["id_request"]},${data["firm_status_file_1"]})"`;
                }
        
              return `
                <a class="dropdown-item" style="cursor:pointer;" ${action}>
                  ${icono} ${item.trim()}
                </a>
              `;
            }).join("");
        
            return `
              <div class="float-left mr-auto">
                <div class="btn-group" role="group">
                  <button id="btnGroupDropPermisos" type="button" class="btn btn-outline-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-file-archive"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-left">
                    ${itemsLinks || '<a class="dropdown-item text-muted">No hay archivos</a>'}
                  </div>
                </div>
                 <button type="button" class="btn btn-outline-primary btn-sm" onclick="downloadZip(${data["id_request"]} )">
                  <i class="fas fa-download"></i> 
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="uploadPaymentCommitment(${data["id_request"]})">
                  <i class="fas fa-upload"></i>
                </button>
              </div>
            `;
          },
        }
        
        
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

function openPdfModal(ruta,id_request) {
 
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
 $('#id_request').val(id_request);
  $('#comprobanteModal').modal('show');


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
  var error_comprobante = comprobante.files.length === 0 ? "⚠️ Debes seleccionar un archivo." : "";
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
      }
  });
});
