$("#uploadForm1").submit(function (e) {
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
      console.log(response);

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

  $.ajax({
    url: `${urls}finanzas/firmar_pdf`, // Ruta del controlador en CodeIgniter 4
    type: "POST",
    data: {
      pdfPath: pdfUrl,
    },
    success: function (response) {
      var signedPdfUrl = response.signedPdfUrl;
      $("#pdfObject").attr("data", signedPdfUrl); // Mostrar el PDF firmado
    },
    error: function () {
      alert("Hubo un error al firmar el archivo");
    },
  });
});

$("#btn-signed").click(function () {
  var pdfUrl = $("#pdfObject").attr("data");

  $.ajax({
    url: `${urls}finanzas/firmar_pdf`, // Ruta del controlador en CodeIgniter 4
    type: "POST",
    data: {
      pdfPath: pdfUrl,
    },
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

function CargarQuincenas() {
  var month = new Intl.DateTimeFormat("es-ES", { month: "long" }).format(
    new Date()
  );
  console.log("Mes: ", month);

  $("#periodo").empty();

  var option = document.createElement("option");

  option.text = "";

  option.value = "";

  $("#periodo").append(option);

  var option = document.createElement("option");

  option.text = "15 " + month;

  option.value = "15 " + month;

  $("#periodo").append(option);

  var option = document.createElement("option");

  switch (month) {
    case "febrero":
      valor = "28 " + month;
      break;

    default:
      valor = "30 " + month;
      break;
  }

  option.text = valor;

  option.value = valor;

  $("#periodo").append(option);
}

function CargarSemanas() {
  var fecha = new Date();

  var year = fecha.getFullYear();

  var mes = fecha.getMonth();

  var primerdia = (((new Date(year, mes, 1).getDay() - 1) % 7) + 7) % 7;

  var dias = new Date(year, mes + 1, 0).getDate() - 7 + primerdia;

  var semanas = Math.ceil(dias / 7);

  $("#periodo").empty();

  var option = document.createElement("option");

  option.text = "";

  option.value = "";

  $("#periodo").append(option);

  for (a = 1; a <= 5; a++) {
    var option = document.createElement("option");

    option.text = a + " Semana";

    option.value = a + " Semana";

    $("#periodo").append(option);
  }
}

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
//  var concepto = document.getElementById("concepto").value.trim();
  var mes_solicitud = document.getElementById("mes_solicitud").value.trim();
  var tipo_nomina = document.getElementById("tipo_nomina").value.trim();
  var periodo = document.getElementById("periodo").value.trim();
  var fecha = document.getElementById("fecha").value.trim();
  var monto = document.getElementById("monto").value.trim();
  var observaciones = document.getElementById("observaciones").value.trim();

  // Normalizar el concepto para comparación
  var conceptoNormalizado = concepto
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "")
    .toLowerCase();
  var esArbitraje = conceptoNormalizado === "pago de arbitrajes";

  var error_empresa = empresa.length === 0 ? "El campo es requerido" : "";
  document.getElementById("error_empresa").textContent = error_empresa;

/*   var error_concepto = concepto.length === 0 ? "El campo es requerido" : "";
  document.getElementById("error_concepto").textContent = error_concepto; */

  var error_mes = mes_solicitud.length === 0 ? "El campo es requerido" : "";
  document.getElementById("error_mes").textContent = error_mes;

  /*   var error_tipo = tipo_nomina.length === 0 ? "El campo es requerido" : "";
  document.getElementById("error_tipo").textContent = error_tipo;

  var error_periodo = periodo.length === 0 ? "El campo es requerido" : "";
  document.getElementById("error_periodo").textContent = error_periodo; */

  // Solo validar tipo_nomina y periodo si no es arbitraje
  var error_tipo = "";
  var error_periodo = "";

  if (!esArbitraje) {
    error_tipo = tipo_nomina.length === 0 ? "El campo es requerido" : "";
    error_periodo = periodo.length === 0 ? "El campo es requerido" : "";
  }

  document.getElementById("error_tipo").textContent = error_tipo;
  document.getElementById("error_periodo").textContent = error_periodo;

  var error_fecha = fecha.length === 0 ? "El campo es requerido" : "";
  document.getElementById("error_fecha").textContent = error_fecha;

  var error_monto = monto.length === 0 ? "El campo es requerido" : "";
  document.getElementById("error_monto").textContent = error_monto;

  if (
    error_empresa ||
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
  // var formData = new FormData(document.getElementById("uploadForm"));
  // Crear FormData y añadir el archivo con el nuevo nombre
  var formData = new FormData(this);

  var fileInput = $("#pdfFile")[0].files; // Obtener el archivo

  if (!fileInput) {
    alert("Por favor, selecciona un archivo.");
    return;
  }

  // Modificar el nombre del archivo: quitar espacios y reemplazar con "_"
  // Quita cualquier campo previo pdfFile[] que hubiera
  formData.delete("pdfFile[]");

  // Añade todos los archivos con nuevo nombre
  for (var i = 0; i < fileInput.length; i++) {
    var file = fileInput[i];
    var nuevoNombre = file.name.replace(/\s+/g, "_");
    formData.append("pdfFile[]", file, nuevoNombre);
  }

  console.log(fileInput, formData);

  //formData.set("pdfFile", fileInput, nuevoNombre); // Reemplazar el archivo con el nuevo nombre

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
          html: `<p style="font-size: 25px;"><b>Numero de Solicitud: ${response.id}</b></p>`,
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
  $('[data-toggle="tooltip"]').tooltip(); // Inicializar tooltips
  tbl_request = $("#tbl_pago_adm")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}finanzas/solicitudes`,
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
        /* {
          data: "date_request",
          title: "Fecha",
          className: "text-center",
          render: function (data, type, row){
            let date = new Date(data);
            let opciones = { day: 'numeric', month: 'short', year: 'numeric' };
            return date.toLocaleDateString('es-ES', opciones);
            
          },
        }, */
        {
          data: "date_request",
          title: "Fecha",
          className: "text-center",
          render: function (data, type, row) {
            let [year, month, day] = data.split("-");
            let date = new Date(year, month - 1, day);
            let opciones = { day: "numeric", month: "short", year: "numeric" };
            return date.toLocaleDateString("es-ES", opciones);
          },
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
                  action = `onclick="openPdfModal('${urls}public/${ruta}')"`;
                } else if (tipo === "2") {
                  // Excel
                  icono = '<i class="fas fa-file-excel text-success"></i>';
                  action = `onclick="downloadFile('${urls}public/${ruta}')"`;
                } else if (tipo === "3") {
                  // TXT
                  icono = '<i class="fas fa-file-alt text-light"></i>';
                  action = `onclick="downloadFile('${urls}public/${ruta}')"`;
                } else if (tipo === "4") {
                  // SUA
                  icono = '<i class="fas fa-file text-secondary"></i>';
                  action = `onclick="downloadFile('${urls}public/${ruta}')"`;
                } else if (tipo === "5") {
                  // SUA
                  icono = '<i class="fas fa-file text-secondary"></i>';
                  action = `onclick="downloadFile('${urls}public/${ruta}')"`;
                }
                if (tipo === "6") {
                  // PDF
                  icono = '<i class="fas fa-file-pdf text-danger"></i>';
                  action = `onclick="openPdfModal('${urls}public/${ruta}')"`;
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
                <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Descarga de Archivos" onclick="downloadZip(${
                  data["id_request"]
                } )">
                  <i class="fas fa-download"></i> 
                </button>
                <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Eliminar Solicitud" onclick="handleDeletePaymentRequest(${
                  data["id_request"]
                })">
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
        let statusCell = $(row).find("td:eq(6)"); // Índice de la columna "Estatus"
        switch (data.status_request) {
          case "1":
            statusCell.css("background-color", "#ffcc00");
            break; // Amarillo
          case "2":
            statusCell.css("background-color", "#99ccff");
            break; // Azul
          case "3":
            statusCell.css("background-color", "#99ff99");
            break; // Verde
          case "4":
            statusCell.css("background-color", "#00bc8c");
            break; // Rojo
          case "5":
            statusCell.css("background-color", "#ff6666");
            break; // Rojo fuerte
        }
      },
    })
    .DataTable();
  $("#tbl_pago_adm thead").addClass("text-center");


      // Buscar por número de nómina y autocompletar nombre
  $('#num_nomina').on('change', function() {
    const numNomina = $(this).val();
    if (numNomina) {
      $.ajax({
        url: 'ruta_de_tu_backend.php', // Reemplaza con tu endpoint
        method: 'POST',
        data: { num_nomina: numNomina },
        success: function(response) {
          $('#nombre_usuario').val(response.nombre); // Asigna el nombre
        },
        error: function() {
          alert('Error al buscar el usuario.');
        }
      });
    }
  });

  // Buscar por nombre y autocompletar número de nómina
  $('#nombre_usuario').on('change', function() {
    const nombreUsuario = $(this).val();
    if (nombreUsuario) {
      $.ajax({
        url: 'ruta_de_tu_backend.php', // Reemplaza con tu endpoint
        method: 'POST',
        data: { nombre_usuario: nombreUsuario },
        success: function(response) {
          $('#num_nomina').val(response.num_nomina); // Asigna el número
        },
        error: function() {
          alert('Error al buscar el usuario.');
        }
      });
    }
  });





});



function abrirSolicitudModal() {
  var miModal = new bootstrap.Modal(document.getElementById("solicitudModal"));
  miModal.show();
}



function abrirSolicitudModal() {
  var miModal = new bootstrap.Modal(document.getElementById("solicitudDPModal"));
  miModal.show();
}



