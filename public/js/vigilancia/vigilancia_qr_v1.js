var tbl_usuarios_estacionamientos;
$(document).ready(function () {
  tbl_usuarios_estacionamientos = $("#tbl_usuarios_estacionamientos")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}vigilancia/tbl_usuarios_estacionamientos`,
        dataSrc: "data",
      },
      lengthChange: true,
      //ordering: true,
      //responsive: false,
      // fixedHeader: false, // Mantiene los encabezados fijos
      scrollY: "600px",
      scrollX: true, // Activa el desplazamiento horizontal
      scrollCollapse: true,
      paging: false,
      fixedColumns: {
        left: 3,
      },
      autoWidth: true,
      rowId: "id_",
      dom: "lBfrtip", // Esto coloca los botones encima de la tabla
      bInfo: false,
      language: {
        url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
      },
      columns: [
        {
          data: "id_usuario",
          title: "Folio",
          className: "text-center",
          type: "num", // Forzar tipo numérico
          render: function (data, type, row) {
            return parseInt(data); // Convertir a número
          },
        },
        { data: "nombre_usuario", title: "Usuario", className: "text-center" },
        {
          data: "departamento",
          title: "Departamento",
          className: "text-center",
        },
        { data: "marbete", title: "Marbete", className: "text-center" },
        { data: "modelo", title: "Modelo", className: "text-center" },
        { data: "color", title: "Color", className: "text-center" },
        { data: "tipo", title: "Tipo", className: "text-center" },
        { data: "placa", title: "Placa", className: "text-center" },

        {
          data: null,
          title: "Tipo usuario",
          className: "text-center",
          render: function (data, type, row) {
            if (data["id_rol"] == 2) {
              return `<span class="badge badge-info">Sindicalizado</span>`;
            } else {
              return '<span class="badge badge-primary">No sindicalizado</span>';
            }
          },
        },
        {
          data: null,
          render: function (data) {
            return `<div class="mr-auto">
          <button type="button" class="btn btn-outline-black btn-sm btn-download" title="Descargar Qr" data-qr="${data["nombre_usuario"]}" data-imagen="${data["ruta_imagen_qr"]}">
              <i class="fas fa-qrcode"></i>
            </button>            
            <button type="button" class="btn btn-outline-danger btn-sm" title="Desactivar Activo" onclick="deleteChange(${data["id_usuario"]},'${data["codigo"]}')">
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
        $(row).attr("id", "activos_" + data.id_usuario);
      },
    })
    .DataTable();

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
  /*************************************
   * inventario inactivo               *
   * ***********************************/

  /*  // Si DataTable ya está inicializado, lo destruimos
    if ($.fn.DataTable.isDataTable("#tbl_inventario_inactivo")) {
      $("#tbl_inventario_inactivo").DataTable().destroy();
      $("#tbl_inventario_inactivo").empty();
    } */

  tbl_inactivos = $("#tbl_estacionamiento_inactivo").DataTable({
    processing: true,
    ajax: {
      method: "POST",
      url: `${urls}vigilancia/usuario_inactivo`,
      dataSrc: function (json) {
        console.log("Datos recibidos:", json); // Verifica si llegan datos
        if (json.status === "error") {
          console.warn(json.message); // Muestra el mensaje de error en la consola
          return []; // Devuelve un array vacío para que DataTables no falle
        }
        return json.data || [];
      },
      error: function (xhr, error, thrown) {
        $("#tbl_inventario_inactivo").DataTable().clear().draw();
        console.error("Error en la solicitud AJAX:", error, xhr.responseText);
      },
    },
    language: {
      url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
      emptyTable: "No hay datos disponibles.",
    },
    lengthChange: true,
    ordering: true,
    responsive: false,
    autoWidth: true,
    fixedHeader: true, // Mantiene los encabezados fijos
    scrollX: true, // Activa el desplazamiento horizontal
    rowId: "staffId",
    columns: [
      { data: "id_activo", title: "Id" },
      { data: "codigo", title: "Código" },
      { data: "descripcion", title: "Descripción" },
      { data: "marca", title: "Marca" },
      { data: "capacidad", title: "Capacidad" },
      { data: "modelo", title: "Modelo" },
      { data: "serie", title: "Serie" },
      { data: "ubicacion", title: "Ubicación" },
      { data: "area", title: "Área" },
      { data: "fecha", title: "Fecha" },
      { data: "proveedor", title: "Proveedor" },
      {
        data: "factura",
        title: "Factura",
        render: function (data, type, row) {
          if (!data || !row.ruta_factura)
            return '<span class="text-muted">No disponible</span>';
          let facturaLimpia = data.trim();
          return `<a href="${row.ruta_factura}" download="${facturaLimpia}" title="Descargar factura">${facturaLimpia}</a>`;
        },
      },
      { data: "revisado", title: "Revisado" },
      {
        data: null,
        title: "ACCIONES",
        render: function (data) {
          return `
                  <button class="btn btn-sm btn-outline-black btn-download" title="Descargar QR" 
                      data-qr="${data.factura}" data-imagen="${data.imagen_qr}">
                      <i class="fas fa-qrcode"></i>
                  </button>
                  <button class="btn btn-sm btn-outline-success" title="Activar Item" 
                      onclick="activeChange(${data.id_activo}, '${data.codigo}')">
                      <i class="fas fa-power-off"></i>
                  </button>`;
        },
      },
    ],
    order: [[0, "desc"]], // Ordenar por la columna oculta `id_activo`
    createdRow: (row, data) => {
      $(row).attr("id", "inactivos_" + data.id_activo);
    },
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

function abrirSolicitudModal() {
  $("#AltaUsuarioModal").modal("show");
}

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
