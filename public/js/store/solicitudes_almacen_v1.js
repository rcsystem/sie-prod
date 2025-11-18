var tbl_solicitudes_almacen;
const base_url = urls;
$(document).ready(function () {
  tbl_solicitudes_almacen = $("#table_facturas_almacen")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}almacen/tbl_solicitudes_almacen`,
        dataSrc: "",
      },
      lengthChange: true,
      ordering: true,
      responsive: true,
      autoWidth: false,
      rowId: "folio",
      dom: "lfrtip",
      language: {
        url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
      },
      columns: [
        {
          data: "id_request",
          title: "Folio",
          className: "text-center",
          type: "num", // Forzar tipo numérico
          render: function (data, type, row) {
            return parseInt(data); // Convertir a número
          },
        },
        { data: "usuario", title: "Usuario", className: "text-center" },
        {
          data: "departamento",
          title: "Departamento",
          className: "text-center",
        },
        { data: "solicitud", title: "Tipo", className: "text-center" },
        {
          data: null,
          title: "Creación",
          className: "text-center",
          render: function (data, type, row) {
            //el campo de la fecha es un datetime genrea el codigo para mostrar solo la fecha
            let fecha = new Date(data.created_at);
            let dia = fecha.getDate().toString().padStart(2, "0");
            let mes = (fecha.getMonth() + 1).toString().padStart(2, "0");
            let anio = fecha.getFullYear();
            return `${dia}/${mes}/${anio}`;
          },
        },
        {
          data: null,
          title: "Estatus",
          className: "text-center",
          render: function (data, type, row) {
            //estatus con un badge dependiendo del estatus
            let estatus = "";
            switch (data.estatus_activo) {
              case "Pendiente":
                estatus =
                  '<span class="badge badge-warning" style="padding: 10px;">Pendiente</span>';
                break;
              case "Aprobado":
                estatus =
                  '<span class="badge badge-success" style="padding: 10px;">Aprobado</span>';
                break;
              case "Rechazado":
                estatus =
                  '<span class="badge badge-danger" style="padding: 10px;">Rechazado</span>';
                break;
              default:
                estatus =
                  '<span class="badge badge-secondary" style="padding: 10px;">Desconocido</span>';
            }
            return estatus;
          },
        },
        {
          data: null,
          title: "Acciones",
          render: function (data) {
            return `<div class="mr-auto">   
             <button type="button" class="btn btn-outline-danger btn-sm" title="Ver Documentos" data-id="${data["id_request"]}" onclick="abrirFacturasModal('${data["id_request"]}')">
             <i class="fas fa-file-pdf"></i>
            </button>        
            <button type="button" class="btn btn-outline-danger btn-sm" title="Desactivar Activo" onclick="deleteChange('${data["id_request"]}')">
              <i class="fas fa-power-off"></i>
            </button>
            
          </div>`;
          },

          className: "text-center",
        },
      ],
      destroy: "true",
      columnDefs: [
        {
          targets: [1],
          //visible: false,
          searchable: true,
        },
      ],
      order: [[0, "desc"]], // Ordenar por la columna oculta `id_activo`
      buttons: [
        {
          extend: "excelHtml5",
          text: '<i class="far fa-file-excel"></i> Exportar a Excel',
          title: "Inventario", // Título del archivo Excel
          className: "btn btn-success", // Clase para el botón (puedes personalizarlo)
        },
      ],

      createdRow: (row, data) => {
        $(row).attr("id", "solicitud_" + data.id_request);
      },
    })
    .DataTable();
  // agregar clase a th de la initTablaLiberation
  $("#table_facturas_almacen thead").addClass("color-th");

  const inputArchivos = document.getElementById("pdfFiles");
  const lista = document.getElementById("lista");
  const formulario = document.getElementById("formSolicitud");

  // Escuchar el submit del formulario
  formulario.addEventListener("submit", async (e) => {
    e.preventDefault(); // Evita que se recargue la página

    const error_concepto = document.getElementById("error_concepto");
    error_concepto.innerText = "";

    // Validaciones básicas
    const archivos = Array.from(inputArchivos.files);
    const campoConcepto = document.getElementById("concepto");

    if (!campoConcepto.value.trim()) {
      //alert("Debes ingresar un Concepto.");
      error_concepto.innerText = "El concepto es obligatorio.";
      campoConcepto.focus();
      return;
    }

    if (archivos.length === 0) {
      alert("Debes seleccionar al menos un archivo.");
      inputArchivos.focus();
      return;
    }

    Swal.fire({
      title: "Procesando solicitud...",
      allowOutsideClick: false,
      showConfirmButton: false, // Esto oculta el botón "OK"
      willOpen: () => {
        Swal.showLoading();
      },
    });

    // Crear el FormData
    const datos = new FormData(formulario); // toma todos los inputs del form
    archivos.forEach((archivo) => datos.append("files[]", archivo)); // adjunta archivos

    try {
      const respuesta = await fetch(`${urls}almacen/solicitud_factura`, {
        method: "POST",
        body: datos,
      });

      if (!respuesta.ok) throw new Error("Error al subir los archivos.");

      const resultado = await respuesta.json();
      console.log("Respuesta del servidor:", resultado);
      Swal.close();
      Swal.fire({
        icon: "success",
        title: "Solicitud enviada correctamente",
        text: "✅ Tu solicitud fue enviada con éxito.",
        confirmButtonText: "Aceptar",
        confirmButtonColor: "#3085d6",
      });

      $("#previewContainer").empty();
      formulario.reset(); // limpia el formulario
      // 🔄 Recargar la tabla sin refrescar la página
      tbl_solicitudes_almacen.ajax.reload(null, false); // false mantiene la página actual
    } catch (error) {
      console.error("Error en la subida:", error);
      alert("Ocurrió un error al enviar la solicitud.");
    }
  });

  /* 
      $(document).on("click", ".btnFirmar", function () {
      let id = $(this).data("id");
      $("#modalFirmar").modal("show");
      cargarPDFs(id);
    }); */

  $(document).on("click", "#listaPDFs li", function () {
    $("#listaPDFs li").removeClass("active");
    $(this).addClass("active");
    let ruta = $(this).data("ruta");
    $("#visorPDF").attr("src", base_url + ruta);
    $("#visorPDF").data("actual", ruta);
  });

  $("#signBtn").click(function () {
    var pdfUrl = $("#pdfObject").attr("data");
    var id = $("#id_solicitud").val();

    var dataFirma = new FormData();
    dataFirma.append("pdfPath", pdfUrl);
    dataFirma.append("id_request", id);

    console.log("pdf:", pdfUrl);

    $.ajax({
      url: `${urls}finanzas/firmar_talento_pdf`, // Ruta del controlador en CodeIgniter 4
      type: "POST",
      data: dataFirma,
      contentType: false, // Evita que jQuery establezca un tipo de contenido incorrecto
      processData: false, // Evita que jQuery intente procesar FormData
      dataType: "json", // Asegura que la respuesta se maneje como JSON
      success: function (response) {
        var signedPdfUrl = response.signedPdfUrl;
        $("#pdfObject").attr("data", signedPdfUrl); // Mostrar el PDF firmado
        $("#signBtn").prop("disabled", true); // Deshabilitar en otros casos
        tbl_request.ajax.reload(null, false);
      },
      error: function () {
        alert("Hubo un error al firmar el archivo");
      },
    });
  });

  /**********************************FIN NUVO CODIGO ******************************/

  // Evento para el botón de descarga
  $("#tbl_usuarios_estacionamientos").on("click", ".btn-download", function () {
    var imgUrl = $(this).attr("data-imagen");
    var nombreFactura = $(this).attr("data-qr");

    var link = document.createElement("a");
    link.href = urls + "/public/" + imgUrl;
    link.download = `qr_${nombreFactura}.png`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  });

  /*Dar de Alta Activos */

  // Evento click para el botón "Guardar activo"
  // Envío del formulario de Alta de Usuario de Estacionamiento
  $("#altaUsuarioForm").on("submit", function (evento) {
    evento.preventDefault();

    const $formulario = $(this);
    const $btnEnviar = $formulario.find('[type="submit"]');

    // === Obtener valores para validar ===
    const nombreUsuario = $("#nombre_usuario").val().trim();
    const numNomina = $("#num_nomina").val().trim();
    const departamento = $("#departamento").val().trim();
    const marbete = $("#marbete").val() ? $("#marbete").val().trim() : "";
    const modelo = $("#modelo").val() ? $("#modelo").val().trim() : "";
    const color = $("#color").val() ? $("#color").val().trim() : "";
    const tipoVehiculo = $("#tipo").val() || "";
    const idRol = $("#id_rol").val().trim() || ""; // si tu form lo incluye
    const idUsuario = $("#id_user").val().trim() || ""; // si tu form lo incluye

    // === Validaciones mínimas (ajusta a tu necesidad) ===
    // Debe existir al menos nombre o nómina (idealmente ambos)
    if (!numNomina && !nombreUsuario) {
      Swal.fire({
        icon: "warning",
        title: "Faltan datos",
        text: "Captura el número de nómina o el nombre del usuario.",
      });
      return;
    }
    if (!departamento) {
      Swal.fire({
        icon: "warning",
        title: "Departamento vacío",
        text: "Selecciona un usuario válido para llenar el departamento.",
      });
      return;
    }

    if (!tipoVehiculo) {
      Swal.fire({
        icon: "warning",
        title: "Tipo de vehículo",
        text: "Selecciona el tipo de vehículo.",
      });
      return;
    }

    if (!marbete) {
      Swal.fire({
        icon: "warning",
        title: "Falta el marbete",
        text: "El marbete es obligatorio.",
      });
      return;
    }
    if (!modelo) {
      Swal.fire({
        icon: "warning",
        title: "Falta el modelo",
        text: "El modelo es obligatorio.",
      });
      return;
    }
    if (!color) {
      Swal.fire({
        icon: "warning",
        title: "Falta el color",
        text: "El color es obligatorio.",
      });
      return;
    }

    // === Mostrar carga ===
    Swal.fire({
      title: '<i class="far fa-save" style="margin-right: 8px;"></i>Guardando…',
      html: "Espere unos segundos.",
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading(),
    });

    // === Preparar datos ===
    const datos = new FormData(this); // Toma todos los campos del form tal cual
    // Si tienes campos calculados/ocultos adicionales, puedes anexarlos:
    // datos.append("id_user", $("#id_user").val() || "");

    // Deshabilitar botón para evitar doble envío
    $btnEnviar.prop("disabled", true);

    // === Envío AJAX ===
    $.ajax({
      url: `${urls}vigilancia/alta_usuario_estacionamiento`, // <-- ajusta si tu endpoint es otro
      method: "POST",
      data: datos,
      processData: false,
      contentType: false,
    })
      .done(function (respuesta) {
        // Cerrar loading
        Swal.close();

        // Éxito visual
        Swal.fire({
          icon: "success",
          title: "Registro guardado",
          text: "El usuario de estacionamiento se guardó correctamente.",
          timer: 1600,
          showConfirmButton: false,
        });

        // Recargar tabla sin perder paginación
        if (window.tbl_usuarios_estacionamientos) {
          try {
            tbl_usuarios_estacionamientos.ajax.reload(null, false);
          } catch (e) {
            console.warn("No se pudo recargar la tabla:", e);
          }
        }

        // Limpiar y cerrar modal
        $formulario[0].reset();
        $("#lista-usuarios").empty(); // limpiar sugerencias del datalist
        $("#AltaUsuarioModal").modal("hide");
      })
      .fail(function (xhr, estado, error) {
        Swal.close();
        const mensaje =
          xhr && xhr.responseJSON && xhr.responseJSON.message
            ? xhr.responseJSON.message
            : (xhr && xhr.responseText) || "Error al guardar.";
        Swal.fire({
          icon: "error",
          title: "No se pudo guardar",
          text: mensaje,
        });
        console.error("Error AJAX:", estado, error, xhr);
      })
      .always(function () {
        $btnEnviar.prop("disabled", false);
      });
  });
});

function cargarPDFs(idSolicitud) {
  $("#listaPDFs").html("<li class='list-group-item'>Cargando...</li>");
  $("#visorPDF").attr("src", "");

  $.ajax({
    url: `${base_url}almacen/obtener_archivos/${idSolicitud}`,
    type: "GET",
    dataType: "json",
    success: function (archivos) {
      $("#listaPDFs").empty();
      if (archivos.length === 0) {
        $("#listaPDFs").append(
          "<li class='list-group-item'>No hay documentos.</li>"
        );
        return;
      }

      $.each(archivos, function (i, archivo) {
        let item = `
          <li class="list-group-item list-group-item-action" data-ruta="public/${
            archivo.ruta_archivo
          }" style="cursor:pointer;">
            <i class="bi bi-file-earmark-pdf"></i> Documento ${i + 1}
          </li>`;
        $("#listaPDFs").append(item);
      });
    },
    error: function () {
      $("#listaPDFs").html(
        "<li class='list-group-item text-danger'>Error al cargar documentos.</li>"
      );
    },
  });
}

function abrirSolicitudModal() {
  $("#solicitudModal").modal("show");
}

function abrirFacturasModal(id) {
  cargarPDFs(id);
  $("#ModalFacturas").modal("show");
}

$(document).ready(function () {
  let selectedFiles = [];

  // Cuando se seleccionan archivos
  $("#pdfFiles").on("change", function (e) {
    const files = e.target.files;
    selectedFiles = Array.from(files);
    renderPreviews(selectedFiles);
  });

  // Renderizar previsualizaciones
  function renderPreviews(files) {
    $("#previewContainer").empty();

    if (files.length === 0) {
      $("#previewContainer").html(
        '<div class="col-12 text-center text-muted">No hay archivos seleccionados</div>'
      );
      return;
    }

    files.forEach((file, index) => {
      const previewElement = createPreviewElement(file, index);
      $("#previewContainer").append(previewElement);
    });
  }

  // Crear elemento de previsualización
  function createPreviewElement(file, index) {
    const fileType = file.type.split("/")[0];
    const fileExtension = file.name.split(".").pop().toLowerCase();
    const fileSize = formatFileSize(file.size);

    let previewContent = "";

    if (fileType === "image") {
      previewContent = `
                <img src="${URL.createObjectURL(
                  file
                )}" class="preview-image" alt="${file.name}">
            `;
    } else if (fileType === "application" && fileExtension === "pdf") {
      previewContent = `
                <div class="preview-document">
                    <i class="fas fa-file-pdf file-icon" style="color: #dd0000;"></i>
                    <div class="mt-2">PDF Documento</div>
                </div>
            `;
    } else if (fileType === "text") {
      previewContent = `
                <div class="preview-document">
                    <i class="fas fa-file-alt file-icon"></i>
                    <div class="mt-2">Archivo de texto</div>
                </div>
            `;
    } else {
      previewContent = `
                <div class="preview-document">
                    <i class="fas fa-file file-icon"></i>
                    <div class="mt-2">${fileExtension.toUpperCase()} File</div>
                </div>
            `;
    }

    return `
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="preview-item position-relative">
                    <button type="button" class="remove-btn" data-index="${index}">
                        <i class="fas fa-times"></i>
                    </button>
                    ${previewContent}
                    <div class="mt-2">
                        <small class="text-muted d-block">${file.name}</small>
                        <small class="text-muted">${fileSize}</small>
                    </div>
                </div>
            </div>
        `;
  }

  // Formatear tamaño del archivo
  function formatFileSize(bytes) {
    if (bytes === 0) return "0 Bytes";
    const k = 1024;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
  }

  // Eliminar archivo individual
  $(document).on("click", ".remove-btn", function () {
    const index = $(this).data("index");
    selectedFiles.splice(index, 1);

    // Actualizar input file
    updatepdfFiles();
    renderPreviews(selectedFiles);
  });

  // Actualizar input file después de eliminar
  function updatepdfFiles() {
    const dt = new DataTransfer();
    selectedFiles.forEach((file) => dt.items.add(file));
    $("#pdfFiles")[0].files = dt.files;
  }

  // Confirmar selección
  $("#confirmFiles").on("click", function () {
    if (selectedFiles.length === 0) {
      alert("Por favor selecciona al menos un archivo");
      return;
    }

    // Aquí puedes procesar los archivos seleccionados
    console.log("Archivos seleccionados:", selectedFiles);

    // Mostrar resumen
    let fileList = "";
    selectedFiles.forEach((file) => {
      fileList += `• ${file.name} (${formatFileSize(file.size)})\n`;
    });

    alert(`Archivos seleccionados:\n${fileList}`);

    // Cerrar modal
    $("#filePreviewModal").modal("hide");
  });

  // Limpiar al cerrar modal
  $("#filePreviewModal").on("hidden.bs.modal", function () {
    // Opcional: limpiar selección al cerrar
    // selectedFiles = [];
    // $('#pdfFiles').val('');
    // $('#previewContainer').empty();
  });
});

/******************* Autocompletar y autollenar: nómina, nombre, departamento *****************/

// Inicialización perezosa (útil si los inputs viven en un modal)
let autocompletadoInicializado = false;

function inicializarAutocompletadoUsuario() {
  if (autocompletadoInicializado) return;

  const $campoUsuario = document.getElementById("nombre_usuario");
  const $campoNomina = document.getElementById("num_nomina");
  const $campoDepto = document.getElementById("departamento");
  const $tipoUsuario = document.getElementById("tipo_usuario");
  const $tipoEmpleado = document.getElementById("id_rol");
  const $idUsuario = document.getElementById("id_user");

  // Si aún no existen los elementos (p.ej. modal no montado), salimos
  if (!$campoUsuario || !$campoNomina || !$campoDepto || !$tipoUsuario) return;

  // Asegurar datalist presente y vinculado
  function obtenerListaUsuarios() {
    let lista = document.getElementById("lista-usuarios");
    if (!lista) {
      lista = document.createElement("datalist");
      lista.id = "lista-usuarios";
      // insertarlo tras el input de usuario
      $campoUsuario.insertAdjacentElement("afterend", lista);
    }
    // Vincular list al input y desactivar autocomplete nativo que a veces tapa el datalist
    $campoUsuario.setAttribute("list", "lista-usuarios");
    $campoUsuario.setAttribute("autocomplete", "off");
    return lista;
  }

  const $listaUsuarios = obtenerListaUsuarios();
  const cacheUsuarios = new Map(); // clave "nombre|nomina" -> objeto
  let temporizador;
  let textoUltimo = ""; // para descartar respuestas viejas

  // Helper para armar query string
  const parametrosURL = (obj) => new URLSearchParams(obj).toString();

  function llenarCamposUsuario(u) {
    if (!u) return;
    $campoUsuario.value = u.full_name ?? "";
    $campoNomina.value = u.payroll_number ?? "";
    $campoDepto.value = u.departament_name ?? "";
    $tipoUsuario.value = u.id_rol ?? "";
    $tipoEmpleado.value = u.type_of_employee ?? "";
    $idUsuario.value = u.id_user ?? "";
  }

  function limpiarSiVacios() {
    if (!$campoUsuario.value.trim() && !$campoNomina.value.trim()) {
      $campoDepto.value = "";
      $tipoUsuario.value = "";
      $tipoEmpleado.value = "";
      $idUsuario.value = "";
    }
  }

  // --- Buscar por NÓMINA y autollenar ---
  async function buscarPorNomina(nomina) {
    if (!nomina) {
      limpiarSiVacios();
      return;
    }
    try {
      const resp = await fetch(
        `${urls}vigilancia/encontrar?` + parametrosURL({ num_nomina: nomina })
      );
      const { ok, data } = await resp.json();
      if (!ok || !Array.isArray(data) || !data.length) {
        $campoUsuario.value = "";
        $campoDepto.value = "";
        $tipoUsuario.value = "";
        $tipoEmpleado.value = "";
        $idUsuario.value = "";
        return;
      }
      llenarCamposUsuario(data[0]); // nómina suele ser única
    } catch (e) {
      console.error(e);
    }
  }

  // --- Autocomplete por NOMBRE ---
  async function sugerirPorNombre(texto) {
    const $lista = obtenerListaUsuarios(); // garantiza existencia
    if (!texto || texto.length < 1) {
      $lista.innerHTML = "";
      return;
    }

    textoUltimo = texto;
    try {
      const resp = await fetch(
        `${urls}vigilancia/encontrar?` + parametrosURL({ usuario: texto })
      );
      const { ok, data } = await resp.json();

      // si el usuario siguió escribiendo, descartar esta respuesta
      if (texto !== textoUltimo) return;
      if (!ok || !Array.isArray(data)) return;

      $lista.innerHTML = "";
      cacheUsuarios.clear();

      if (!data.length) return;

      const opciones = data
        .map((u) => {
          const clave = `${u.full_name}|${u.payroll_number}`;
          cacheUsuarios.set(clave, u);
          return `<option value="${u.full_name} | ${u.payroll_number}" data-clave="${clave}" label="${u.departament_name}"></option>`;
        })
        .join("");
      $lista.insertAdjacentHTML("beforeend", opciones);

      // “Refresh” suave para navegadores quisquillosos
      const idLista = $lista.id;
      $campoUsuario.setAttribute("list", "");
      requestAnimationFrame(() => $campoUsuario.setAttribute("list", idLista));
    } catch (e) {
      console.error(e);
    }
  }

  // --- Eventos ---
  $campoNomina.addEventListener("input", () => {
    clearTimeout(temporizador);
    temporizador = setTimeout(
      () => buscarPorNomina($campoNomina.value.trim()),
      300
    );
  });
  $campoNomina.addEventListener("change", () =>
    buscarPorNomina($campoNomina.value.trim())
  );

  $campoUsuario.addEventListener("input", () => {
    clearTimeout(temporizador);
    const texto = $campoUsuario.value.trim();
    temporizador = setTimeout(() => sugerirPorNombre(texto), 150);
  });

  $campoUsuario.addEventListener("change", () => {
    const valor = $campoUsuario.value.trim();
    if (!valor) {
      limpiarSiVacios();
      return;
    }

    const opcion = Array.from(obtenerListaUsuarios().options).find(
      (o) => o.value === valor
    );
    if (
      opcion &&
      opcion.dataset.clave &&
      cacheUsuarios.has(opcion.dataset.clave)
    ) {
      const u = cacheUsuarios.get(opcion.dataset.clave);
      llenarCamposUsuario(u);
      // Dejar solo el nombre (sin " | nómina")
      $campoUsuario.value = u.full_name ?? valor.split("|")[0]?.trim() ?? valor;
    } else {
      // Intento de resolución directa por texto
      (async () => {
        try {
          const resp = await fetch(
            `${urls}vigilancia/encontrar?` + parametrosURL({ usuario: valor })
          );
          const { ok, data } = await resp.json();
          if (ok && data && data.length) llenarCamposUsuario(data[0]);
        } catch (e) {}
      })();
    }
  });

  autocompletadoInicializado = true;
}

// 1) Intento cuando el DOM está listo
document.addEventListener("DOMContentLoaded", inicializarAutocompletadoUsuario);

// 2) Si vive en el modal, inicializar cuando se muestre
$(document).on("shown.bs.modal", "#AltaUsuarioModal", function () {
  inicializarAutocompletadoUsuario();
});

// Bootstrap 4 — limpiar formularios al cerrar el modal
$(document).on("hidden.bs.modal", "#AltaUsuarioModal", function () {
  const $modal = $(this);

  // 1) Restablecer todos los formularios dentro del modal
  $modal.find("form").each(function () {
    this.reset();
  });

  // 2) Quitar estados de validación
  $modal.find(".is-valid, .is-invalid").removeClass("is-valid is-invalid");

  // 3) Limpiar inputs de tipo archivo
  $modal.find('input[type="file"]').val("");

  // 4) Limpiar selects
  $modal.find("select").val("");
  // Si usas select2:
  $modal.find("select.select2").val(null).trigger("change");

  // 5) Limpiar el datalist del autocomplete (si lo usas)
  $("#lista-usuarios").empty();

  // 6) Campos específicos (por si están fuera del <form>)
  $("#nombre_usuario, #num_nomina, #departamento").val("");
});

$(".form-control").each(function () {
  a($(this));
});

$(document).on("blur", ".form-control", function () {
  a($(this));
});

$(document).on("focus", ".form-control", function () {
  $(this).parent(".form-group").addClass("fill");
});

function a(f) {
  var g = 0;
  try {
    g = f.attr("placeholder").length;
  } catch (d) {
    g = 0;
  }
  if (f.val().length > 0 || g > 0) {
    f.parent(".form-group").addClass("fill");
  } else {
    f.parent(".form-group").removeClass("fill");
  }
}
