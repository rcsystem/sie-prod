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
  var concepto = document.getElementById("concepto").value.trim();
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

  var error_concepto = concepto.length === 0 ? "El campo es requerido" : "";
  document.getElementById("error_concepto").textContent = error_concepto;

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
});

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

function abrirSolicitudModal() {
  var miModal = new bootstrap.Modal(document.getElementById("solicitudModal"));
  miModal.show();
}

document.getElementById("concepto").addEventListener("change", function () {
  const fileInputContainer = document.getElementById("fileInputContainer");
  const fileInputContainerSUA = document.getElementById(
    "fileInputContainerSua"
  );
  const fileInputContainerTXT = document.getElementById(
    "fileInputContainerTxt"
  );
  const fileInputContainerPDF = document.getElementById(
    "fileInputContainerPdf"
  );
  const fileInputContainerXML = document.getElementById(
    "fileInputContainerXml"
  );
  const concepto = document.getElementById("concepto").value;
  console.log("Concepto: ", concepto);

  // Limpiar los contenedores antes de agregar nuevos inputs
  fileInputContainer.innerHTML = "";
  fileInputContainerSUA.innerHTML = "";
  fileInputContainerTXT.innerHTML = "";
  fileInputContainerPDF.innerHTML = "";
  fileInputContainerXML.innerHTML = "";

  const conceptoSeleccionado = this.value;
  const empresaSeleccionada = document.getElementById("empresa").value; // Obtener empresa seleccionada

  // Definir qué inputs deben mostrarse para cada concepto
  const inputsPorConcepto = {
    "NOMINA SEMANAL": ["TXT"],
    "NOMINA QUINCENAL": ["TXT"],
    "PENSION ALIMENTICIA": ["EXCEL"],
    "CUOTA SINDICAL SEMANAL": [],
    "CUOTA SINDICAL EVENTOS SOCIALES": [],
    "AYUDA SINDICAL EVENTOS ESPECIALES": [],
    FONACOT: [],
    "FONDO DE AHORRO SEMANAL": [],
    //"FONDO DE AHORRO FINIQUITOS": ["EXCEL"],
    "FONDO DE AHORRO FINIQUITOS": [],
    "CAJA DE AHORROS SINDICALIZADOS": [],
    "VALES DESPENSA": [],
    "PAGO CUOTAS IMSS": ["SUA"],
    "CAJA DE AHORROS EMPLEADOS": [],
    "DEVOLUCIÓN CAJA DE AHORROS FINIQUITOS": ["EXCEL"],
    "PRESTAMOS NOMINOM": [],
    "OPTICA OCULAR": [],
    "PAGO DE FINIQUITOS": ["EXCEL"],
    PAGARÉS: ["EXCEL"],
    "AYUDA POR DEFUNCIÓN": [],
    "APOYO POR DEFUNCIÓN": [],
    "AYUDA SINDICAL VARIOS": [],
    "TARJETAS VALES DE DESPENSA": [],
    "CAJA DE AHORRO EMPLEADOS": [],
    "DEVOLUCION FONDO DE AHORRO (BAJA)": ["EXCEL"],
    "REPARTO DE UTILIDADES": ["EXCEL", "TXT"],
    "PAGO DE ARBITRAJES": ["PDF"],
    "PAGO NOMINA ESQUEMA": ["PDF", "XML", "EXCEL"],
    "PAGO BECAS": ["PDF"],
    "PAGO UNIFORMES FUTBOL": ["PDF"],
    "AYUDA DECEMBRINA": ["PDF"],
    "REPARTO DE UTILIDADES BAJAS": ["EXCEL"],
    PAYNEFITS: ["PDF"],
  };

  // Obtener los inputs necesarios según el concepto seleccionado
  let inputs = inputsPorConcepto[conceptoSeleccionado] || [];

  // Si la empresa seleccionada es "Walworth", añadir "EXCEL" al array de inputs

  if (
    concepto == "NOMINA QUINCENAL" &&
    empresaSeleccionada === "Grupo Walworth S.A. de C.V." &&
    !inputs.includes("EXCEL")
  ) {
    inputs.push("EXCEL");
  }

  if (
    concepto == "NOMINA QUINCENAL" &&
    empresaSeleccionada === "Industrial de Valvulas S.A de C.V." &&
    !inputs.includes("EXCEL")
  ) {
    inputs.push("EXCEL");
  }

  // Generar los inputs de archivo según la lista obtenida
  inputs.forEach((tipo) => {
    const label = document.createElement("label");
    label.textContent = `Subir archivo ${tipo}:`;
    label.htmlFor = `archivo-${tipo}`;

    const inputFile = document.createElement("input");
    inputFile.type = "file";
    inputFile.id = `archivo-${tipo}`;
    inputFile.name = `archivo-${tipo}`;

    if (tipo === "EXCEL") {
      const inputFiles = document.createElement("input");
      inputFiles.type = "file";
      inputFiles.id = `xlsFile`;
      inputFiles.name = `xlsFile`;
      inputFiles.accept = ".xlsx, .xls, .xlsm";
      fileInputContainer.appendChild(label);
      fileInputContainer.appendChild(inputFiles);
      fileInputContainer.classList.add("font-solicitud", "col-md-4");
    } else if (tipo === "SUA") {
      fileInputContainerSUA.appendChild(label);
      fileInputContainerSUA.appendChild(inputFile);
      fileInputContainerSUA.classList.add("font-solicitud", "col-md-4");
    } else if (tipo === "TXT") {
      fileInputContainerTXT.appendChild(label);
      fileInputContainerTXT.appendChild(inputFile);
      fileInputContainerTXT.classList.add("font-solicitud", "col-md-4");
    } else if (tipo === "PDF") {
      const inputFilePdf = document.createElement("input");
      inputFilePdf.type = "file";
      inputFilePdf.id = `pdfsFile`;
      inputFilePdf.name = `pdfsFile`;
      inputFilePdf.accept = ".pdf";
      fileInputContainerPDF.appendChild(label);
      fileInputContainerPDF.appendChild(inputFilePdf);
      fileInputContainerPDF.classList.add("font-solicitud", "col-md-4");
    } else if (tipo === "XML") {
      const inputFileXml = document.createElement("input");
      inputFileXml.type = "file";
      inputFileXml.id = `xmlFile`;
      inputFileXml.name = `xmlFile`;
      inputFileXml.accept = ".xml";
      fileInputContainerXML.appendChild(label);
      fileInputContainerXML.appendChild(inputFileXml);
      fileInputContainerXML.classList.add("font-solicitud", "col-md-4");
    }
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

function openPdfModal(ruta) {
  let modalBody = document.getElementById("pdfModalBody");
  modalBody.innerHTML = `<iframe src="${ruta}" width="100%" height="700px"></iframe>`;
  $("#aprobarSolicitudModal").modal("show");
}

function handleDeletePaymentRequest(id_request) {
  Swal.fire({
    title: `Deseas Eliminar la Solicitud con Folio: ${id_request} ?`,
    text: `Una vez Eliminado no podras Recuperarlo!`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Eliminar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.value) {
      let dataForm = new FormData();
      dataForm.append("id_request", id_request);

      $.ajax({
        data: dataForm, //datos que se envian a traves de ajax
        url: `${urls}finanzas/eliminar_solicitud`, //archivo que recibe la peticion
        type: "post", //método de envio
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentType
        success: function (response) {
          //una vez que el archivo recibe el request lo procesa y lo devuelve
          console.log(response);

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
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Fallo de conexión: ​​Verifique la red.",
          });
          $("#guardar_ticket").prop("disabled", false);
        } else if (jqXHR.status == 404) {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "No se encontró la página solicitada [404]",
          });
          $("#guardar_ticket").prop("disabled", false);
        } else if (jqXHR.status == 500) {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Internal Server Error [500]",
          });
          $("#guardar_ticket").prop("disabled", false);
        } else if (textStatus === "parsererror") {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Error de análisis JSON solicitado.",
          });
          $("#guardar_ticket").prop("disabled", false);
        } else if (textStatus === "timeout") {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Time out error.",
          });
          $("#guardar_ticket").prop("disabled", false);
        } else if (textStatus === "abort") {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Ajax request aborted.",
          });

          $("#guardar_ticket").prop("disabled", false);
        } else {
          alert("Uncaught Error: " + jqXHR.responseText);
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

function generarSolicitud() {
  const container = document.getElementById("extraFieldsContainer");
  const checked = document.getElementById("generar_solicitud").checked;

  if (checked) {
    // Si está marcado, inyectamos el HTML
    container.innerHTML = `
      <div class="row">
        <div class="col-md-4">
          <div class="btn-group-toggle text-left" data-toggle="buttons">
            <label class="floating-label">Tipo de pago</label>
            <label class="btn btn-success">
              <input type="radio" name="tipo_pago" id="cheque" value="cheque"> Cheque
            </label>
            <label class="btn btn-success">
              <input type="radio" name="tipo_pago" id="transferencia" value="transferencia"> Transferencia
            </label>
          </div>
          <div id="error_tipo" class="text-danger"></div>
        </div>
        <div class="col-md-4">
          <div class="form-group mb-5">
            <label class="floating-label" for="nombre_empresa">Expedir a nombre de:</label>
            <input type="text" class="form-control escribe" id="nombre_empresa" name="nombre_empresa">
            <div id="error_empresa" class="text-danger"></div>
          </div>
        </div>
       <div class="col-md-4">
                      <div class="form-group mb-5">
                        <label class="floating-label" for="banco">Banco:</label>
                        <select name="banco" id="banco" class="form-control">
                          <option value="" disabled="" selected=""></option>
                          <option value="BBVA">BBVA</option>
                          <option value="BANAMEX">BANAMEX</option>
                          <option value="CITIBANK">CITIBANK</option>
                          <option value="SANTANDER">SANTANDER</option>
                          <option value="HSBC">HSBC</option>
                          <option value="BAJÍO">BAJÍO</option>
                          <option value="IXE">IXE</option>
                          <option value="INBURSA">INBURSA</option>
                          <option value="AFIRME">AFIRME</option>
                          <option value="AZTECA">AZTECA</option>
                          <option value="AUTOFIN">AUTOFIN</option>
                          <option value="BANCO MULTIVA">BANCO MULTIVA</option>
                          <option value="BANCO FAMSA">BANCO FAMSA</option>
                          <option value="BANCOPPEL">BANCOPPEL</option>
                          <option value="AMERICAN EXPRESS">AMERICAN EXPRESS</option>
                          <option value="BANORTE">BANORTE</option>

                        </select>
                        <div id="error_banco" class="text-danger"></div>
                      </div>
                    </div>
        <div class="col-md-4">
          <div class="form-group mb-5">
            <label class="floating-label" for="cuenta">Cuenta:</label>
            <input type="text" id="cuenta" name="cuenta" class="form-control escribe">
            <div id="error_cuenta" class="text-danger"></div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group mb-5">
            <label class="floating-label" for="clabe">Clabe:</label>
            <input type="text" id="clabe" name="clabe" class="form-control escribe">
            <div id="error_clabe" class="text-danger"></div>
          </div>
        </div>
         <div class="col-md-4">
          <div class="form-group mb-5">
            <label class="floating-label" for="clabe">Cantidad Letra:</label>
            <input type="text" id="cantidad_letra" name="cantidad_letra" class="form-control escribe">
            <div id="error_cantidad_letra" class="text-danger"></div>
          </div>
        </div>
      </div>
    `;

    // Al inyectarse el HTML, ahora asignamos el listener al input monto

    $("#monto").on("input", function () {
      let valor = $(this).val().replace(/,/g, ""); // Eliminar comas antes de convertir
      let letras = numeroALetras(valor);
      $("#cantidad_letra").val(letras);
    });
  } else {
    // Si está desmarcado, limpiamos el contenedor
    container.innerHTML = "";
  }
}

function numeroALetras(num) {
  const unidades = [
    "",
    "UNO",
    "DOS",
    "TRES",
    "CUATRO",
    "CINCO",
    "SEIS",
    "SIETE",
    "OCHO",
    "NUEVE",
  ];
  const especiales = [
    "DIEZ",
    "ONCE",
    "DOCE",
    "TRECE",
    "CATORCE",
    "QUINCE",
    "DIECISÉIS",
    "DIECISIETE",
    "DIECIOCHO",
    "DIECINUEVE",
  ];
  const decenas = [
    "",
    "DIEZ",
    "VEINTE",
    "TREINTA",
    "CUARENTA",
    "CINCUENTA",
    "SESENTA",
    "SETENTA",
    "OCHENTA",
    "NOVENTA",
  ];
  const centenas = [
    "",
    "CIENTO",
    "DOSCIENTOS",
    "TRESCIENTOS",
    "CUATROCIENTOS",
    "QUINIENTOS",
    "SEISCIENTOS",
    "SETECIENTOS",
    "OCHOCIENTOS",
    "NOVECIENTOS",
  ];

  function convertir(n) {
    if (n < 10) return unidades[n];
    else if (n < 20) return especiales[n - 10];
    else if (n < 100)
      return (
        decenas[Math.floor(n / 10)] + (n % 10 ? " Y " + unidades[n % 10] : "")
      );
    else if (n < 1000)
      return (
        centenas[Math.floor(n / 100)] +
        (n % 100 ? " " + convertir(n % 100) : "")
      );
    else if (n < 1000000)
      return (
        convertir(Math.floor(n / 1000)) +
        " MIL " +
        (n % 1000 ? convertir(n % 1000) : "")
      );
    else return "Número demasiado grande";
  }

  num = parseFloat(num);
  if (isNaN(num) || num < 0) return "Número inválido";

  let parteEntera = Math.floor(num);
  let parteDecimal = Math.round((num - parteEntera) * 100);

  let texto = convertir(parteEntera);
  if (parteDecimal > 0) {
    texto += " PESOS CON " + convertir(parteDecimal) + " CENTAVOS";
  } else {
    texto += " PESOS";
  }

  return texto.charAt(0).toUpperCase() + texto.slice(1); // Primera letra mayúscula
}
