/**
 * ARCHIVO MODULO VIAJES
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EDITOR:HORUS RIVAS PEDRAZA
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */

// cache global recomendado
const cacheUsuarios = window.cacheUsuarios || new Map();
window.cacheUsuarios = cacheUsuarios;

$(document).ready(function () {
  tbl_viaticos = initTablaLiberation();
  $("#tabla_liberation thead").addClass("thead-dark text-center");
  initLiberationForm(urls);
});

function initTablaLiberation() {
  return $("#tabla_liberation").DataTable({
    processing: true,
    ajax: {
      method: "post",
      url: `${urls}liberacion/todas_las_solicitudes`,
      dataSrc: "",
    },
    lengthChange: true,
    ordering: true,
    responsive: true,
    autoWidth: false,
    rowId: "folio",
    dom: "lfrtip",
    buttons: [],
    language: {
      url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
    },
    columns: [
      { data: "folio", title: "FOLIO", className: "text-center" },
      { data: "user_name", title: "USUARIO", className: "text-center" },
      { data: "nomina", title: "NOMINA", className: "text-center" },
      {
        data: "request_status",
        title: "Estado",
        className: "text-center",
        render: function (data) {
          return statusBadge(data);
        },
      },
      {
        data: null,
        render: function (data, type, full, meta) {
          return `
              <div class="d-inline-flex gap-2">
              
                  <a href="javascript:void(0);" class="btn btn-outline-info btn-sm" style="margin-right: 5px;"
                    data-toggle="modal" 
                    data-target="#pdfModal" 
                     data-folio ="${data.folio}"
                    data-url="${urls}liberacion/ver-solicitud/${$.md5(
            key + data["folio"]
          )}">
                      <i class="fas fa-eye"></i>
                  </a>
                  <button type="button" class="btn btn-outline-success btn-sm" style="margin-right: 5px;" title="Telefóno" onclick="actualizarSolicitud(${
                data.folio
              })" >
                      <i class="fas fa-mobile-alt"></i>
                  </button>
                  <button type="button" class="btn btn-outline-danger btn-sm" title="Desactivar" onclick="desactivarSolicitud(${
                    data.folio
                  })" >
                      <i class="fas fa-trash-alt"></i>
                  </button>
              </div>
          `;
        },
        title: "ACCIONES",
        className: "text-center",
      },
    ],
    destroy: true,
    order: [[0, "DESC"]],
    createdRow: (row, data) => {
      $(row).attr("id", "liberation_" + data.folio);
    },
  });
}

function actualizarSolicitud(idSolicitud) {
  
  // $("#modalCreateRequestLiberation").modal("show");

 // let idSolicitud = $(this).data('id');

    Swal.fire({
        title: 'Actualizar Teléfono',
        input: 'text',
        inputLabel: 'Nuevo número de teléfono',
        inputPlaceholder: 'Ej: 5551234567',
        showCancelButton: true,
        confirmButtonText: 'Actualizar',
        cancelButtonText: 'Cancelar',
        inputValidator: (value) => {
            if (!value) {
                return 'El teléfono no puede estar vacío';
            }
            if (!/^[0-9]{10}$/.test(value)) {
                return 'El teléfono debe tener 10 dígitos';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            let nuevoTelefono = result.value;

            $.ajax({
                url: `${urls}liberacion/actualizarTelefono`,
                type: "POST",
                data: {
                    id_solicitud: idSolicitud,
                    telefono: nuevoTelefono
                },
                dataType: "json",
                success: function (resp) {
                    if (resp.ok) {
                        Swal.fire('✅ Listo', 'Teléfono actualizado correctamente', 'success');
                        // refrescar tabla o campo
                        $('#telefono_' + idSolicitud).text(nuevoTelefono);
                    } else {
                        Swal.fire('⚠️ Error', resp.mensaje || 'No se pudo actualizar', 'error');
                    }
                },
                error: function () {
                    Swal.fire('❌ Error', 'Hubo un problema con el servidor', 'error');
                }
            });
        }
    });
}

function abrirSolicitudModal() {
  $("#modalCreateRequestLiberation").modal("show");
}

function initLiberationForm(urls) {
  let today = new Date().toISOString().split("T")[0];
  $("#date").val(today);

  // Cargar compañías
  $.ajax({
    url: `${urls}liberacion/companies_list`,
    method: "POST",
    success: function (companies) {
      $("#empresa_id")
        .empty()
        .append('<option value="">Seleccione una empresa</option>');
      companies.forEach((company) => {
        $("#empresa_id").append(
          `<option value="${company.name_company}">${company.name_company}</option>`
        );
      });
    },
  });

  // Al cambiar compañía, cargar usuarios
  $("#empresa_id2")
    .off("change")
    .on("change", function () {
      let companyId = $(this).val();
      $("#user_name")
        .empty()
        .append('<option value="">Seleccione un usuario</option>')
        .prop("disabled", true);
      $("#payroll_number")
        .empty()
        .append('<option value="">Seleccione una nómina</option>')
        .prop("disabled", true);

      if (companyId) {
        $.ajax({
          url: `${urls}liberacion/users_by_company/${companyId}`,
          method: "GET",
          success: function (users) {
            $("#user_name").data("all-users", users);

            users.forEach((user) => {
              // Agregar opción al select de nombre
              $("#user_name").append(
                `<option value="${user.id_user}" 
                       data-payroll="${user.payroll_number}" 
                       data-department="${user.department_name}" 
                       data-department_id="${user.department_id}"
                       data-manager="${user.direct_manager || ""}" 
                       data-tel="${user.tel || ""}" 
                       data-equip="${user.equip_asigned || ""}">
                 ${user.name}
               </option>`
              );

              // Agregar opción al select de nómina
              $("#payroll_number").append(
                `<option value="${user.payroll_number}" 
                       data-userid="${user.id_user}">
                 ${user.payroll_number}
               </option>`
              );
            });
            $("#user_name").prop("disabled", false);
            $("#payroll_number").prop("disabled", false);
          },
        });
      }

      limpiarCampos();
    });

  // Cuando cambie el nombre
  $("#user_name")
    .off("change")
    .on("change", function () {
      let selectedOption = $(this).find("option:selected");
      if (selectedOption.val()) {
        $("#payroll_number").val(selectedOption.data("payroll"));
        $("#department").val(selectedOption.data("department"));
        $("#department_id").val(selectedOption.data("department_id"));
        $("#direct_manager").val(selectedOption.data("manager"));
        $("#tel").val(selectedOption.data("tel"));
        $("#equip_asigned").val(selectedOption.data("equip"));

        let userId = selectedOption.val();
        let users = $("#user_name").data("all-users");
        let user = users.find((u) => u.id_user == userId);

        let equipDiv = $("#equip_info");
        equipDiv.empty();
        if (user && user.equip_info && user.equip_info.length > 0) {
          user.equip_info.forEach((e) => {
            equipDiv.append(
              `<div class="mb-1">
                    <strong>Modelo:</strong> ${e.model || e.modelo} <br>
                    <strong>Marca:</strong> ${e.marca} <br>
                    <strong>No. Serial:</strong> ${e.no_serial || ""}
                </div><hr>`
            );
          });
        } else {
          equipDiv.append("<small>No hay equipos asignados</small>");
        }
      } else {
        limpiarCampos();
        $("#equip_info")
          .empty()
          .append("<small>No hay equipos asignados</small>");
      }
    });

  // Cuando cambie la nómina
  $("#payroll_number")
    .off("change")
    .on("change", function () {
      let payroll = $(this).val();
      let matchOption = $(`#user_name option[data-payroll="${payroll}"]`);
      if (matchOption.length) {
        $("#user_name").val(matchOption.val()).trigger("change");
      }
    });

  function limpiarCampos() {
    $("#payroll_number").val("");
    $("#department_id").val("");
    $("#department").val("");
    $("#direct_manager").val("");
    $("#tel").val("");
    $("#equip_asigned").val("");
  }
}

$("#formCreateRequestLiberation").on("submit", function (e) {
  e.preventDefault();

  const formData = $(this).serialize();
  console.log(formData);
  $.ajax({
    type: "POST",
    url: `${urls}liberacion/crear_solicitud`,
    data: formData,
    dataType: "json",
    success: function (response) {
      if (response.success) {
        $("#modalCreateRequestLiberation").modal("hide");
        $("#formCreateRequestLiberation")[0].reset();
        tbl_viaticos.ajax.reload(null, false);
        Swal.fire("¡Éxito!", "Ítem agregado correctamente", "success");
      } else {
        Swal.fire(
          "Error",
          response.message || "Hubo un problema al guardar.",
          "error"
        );
      }
    },
    error: function () {
      Swal.fire("Error", "No se pudo conectar con el servidor.", "error");
    },
  });
});

function desactivarSolicitud(request_id) {
  if (
    !confirm(
      "¿Seguro que deseas desactivar esta solicitud y todos sus registros relacionados?"
    )
  )
    return;

  $.ajax({
    method: "POST",
    url: `${urls}liberacion/desactivar_solicitud`,
    data: { request_id },
    success: function (res) {
      if (res.success) {
        tbl_viaticos.ajax.reload(); // Recarga tabla
        Swal.fire("¡Éxito!", "se completo la acción correctamente", "success");
      } else {
        Swal.fire(
          "Error",
          res.error || "Hubo un problema al guardar.",
          "error"
        );
      }
    },
    error: function () {
      alert("Error en la petición para desactivar la solicitud.");
    },
  });
}

$("#form_solicitudes_liberation").on("submit", function (e) {
  e.preventDefault();
  var error = 0;
  const btn = document.getElementById("btn_solicitudes_liberation");
  const archivo = document.getElementById("archivo");
  if (archivo.value.length == 0) {
    error++;
    $("#lbl_" + archivo.id).addClass("has-error");
    $("#error_" + archivo.id).text("Archivo requerido");
  } else if (archivo.value.split(".").pop() != "xlsx") {
    error++;
    $("#lbl_" + archivo.id).addClass("has-error");
    $("#error_" + archivo.id).text("Archivo .xlsx necesario");
  } else {
    $("#lbl_" + archivo.id).removeClass("has-error");
    $("#error_" + archivo.id).text("");
  }
  if (error != 0) {
    return false;
  }
  const timerInterval = Swal.fire({
    iconHtml: '<i class="fas fa-file-upload"></i>',
    title: "Subiendo Datos!",
    html: "Espere unos Segundos.",
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading();
    },
  });
  btn.disabled = true;
  const data = new FormData($("#form_solicitudes_liberation")[0]);
  $.ajax({
    data: data,
    url: `${urls}liberacion/subir_solicitudes_liberacion_masivo`,
    type: "POST",
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (response) {
      btn.disabled = false;
      Swal.close(timerInterval);

      if (
        response.successCount > 0 &&
        (!response.errors || response.errors.length === 0)
      ) {
        tbl_viaticos.ajax.reload(null, false);
        Swal.fire(
          "¡Éxito!",
          `Se procesaron ${response.successCount} solicitudes correctamente.`,
          "success"
        );
      } else if (response.successCount > 0 && response.errors.length > 0) {
        Swal.fire(
          "Parcialmente exitoso",
          `Se procesaron ${response.successCount} solicitudes, pero hubo ${response.errors.length} errores.`,
          "warning"
        );
        console.log("Errores:", response.errors);
      } else {
        Swal.fire("Error", "No se pudo procesar ninguna solicitud.", "error");
        console.log("Errores:", response.errors);
      }
    },
  }).fail(function () {
    btn.disabled = false;
    Swal.fire("Error", "No se pudo conectar con el servidor.", "error");
  });
});

function validarFile(campo) {
  const input = campo;
  if (input.value.length > 0) {
    $("#lbl_" + input.id).empty();
    $("#lbl_" + input.id).append(
      `${document.getElementById(input.id).files[0].name}`
    );
    $("#lbl_" + input.id).attr("style", "color:#343a40!important;");
    $("#lbl_" + input.id).removeClass("has-error");
    $("#error_" + input.id).text("");
  }
}

$("#pdfModal").on("show.bs.modal", function (event) {
  $(this).find("#carga_pdf").attr("src", "");
  var button = $(event.relatedTarget);
  var url = button.data("url");
  var modal = $(this);
  if (url) {
    modal.find("#carga_pdf").attr("src", url);
  } else {
    console.error("URL no encontrada o inválida.");
  }
});
$("#pdfModal").on("hidden.bs.modal", function () {
  $(this).find("#carga_pdf").attr("src", "");
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

  $("#periodo").empty();
  var option = document.createElement("option");
  option.text = "Seleccione periodo";
  option.value = "";
  option.disabled = true;
  option.selected = true;

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

  option.text = "Seleccione periodo";
  option.value = "";
  option.disabled = true;
  option.selected = true;

  $("#periodo").append(option);

  for (a = 1; a <= 5; a++) {
    var option = document.createElement("option");

    option.text = a + " Semana";

    option.value = a + " Semana";

    $("#periodo").append(option);
  }
}

function download() {
  const btn = document.getElementById("btn_dowload_format");
  btn.disabled = true;
  const cargando = Swal.fire({
    //se le asigna un nombre al swal
    allowOutsideClick: false,
    title: `DESCARGANDO <i class="fas fa-qrcode"></i>`,
    html: "Espere unos Segundos.",
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading(); //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });
  const downloadOneDocument = document.createElement("a");
  downloadOneDocument.href = `${urls}/public/doc/liberacion/FormatoSubir_SolicitudesLiberacionMasivo.xlsx`;
  downloadOneDocument.download = "FormatoSubir_SolicitudesLiberacionMasivo";
  downloadOneDocument.click();
  btn.disabled = false;
  Swal.close(cargando);
}

function statusBadge(s) {
  const map = {
    FIRMADO: "badge-success",
    "EN PROGRESO": "badge-info",
    PENDIENTE: "badge-warning",
  };
  const cls = map[s] || "badge-secondary";
  return `<span style="padding: 10px;" class="badge badge-status ${cls}">${s}</span>`;
}

// expone una función para actualizar UNA fila sin recargar toda la tabla
function updateSolicitudesLiberacionRow(requestId, newStatus) {
  if (!dtSL) return;
  const row = dtSL.row("#" + requestId);
  if (row.length) {
    const data = row.data();
    data.request_status = newStatus;
    row.data(data).invalidate(); // refresca celdas
    // resaltar visualmente la actualización (opcional)
    $(row.node()).addClass("table-success");
    setTimeout(() => $(row.node()).removeClass("table-success"), 700);
  } else {
    // si no está en la página actual (serverSide o paginación), recarga sin resetear
    dtSL.ajax.reload(null, false);
  }
}

/******************* Autocompletar y autollenar: nómina, nombre, departamento *****************/

// Inicialización perezosa (útil si los inputs viven en un modal)
let autocompletadoInicializado = false;

//const cacheUsuarios = new Map(); // clave "nombre|nomina" -> objeto

function inicializarAutocompletadoUsuario() {
  if (autocompletadoInicializado) return;

  const $campoIdUsuario = document.getElementById("id_user_name");
  const $campoUsuario = document.getElementById("modal_user_name");
  const $campoNomina = document.getElementById("modal_payroll_number");
  const $campoDepto = document.getElementById("modal_department");
  const $campoJefeDirecto = document.getElementById("modal_direct_manager");
  const $campoEquipoAsignado = document.getElementById("modal_equip_asigned");

  // Si aún no existen los elementos (p.ej. modal no montado), salimos
  if (!$campoIdUsuario || !$campoUsuario || !$campoNomina || !$campoDepto)
    return;

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

  let temporizador;
  let textoUltimo = ""; // para descartar respuestas viejas

  // Helper para armar query string
  const parametrosURL = (obj) => new URLSearchParams(obj).toString();

  function llenarCamposUsuario(u) {
    console.log("datos: ", u);

    // if (!u) return;
    $campoIdUsuario.value = u.id_user ?? "";
    $campoUsuario.value = u.full_name ?? "";
    $campoNomina.value = u.payroll_number ?? "";
    $campoDepto.value = u.department ?? "";
    $campoJefeDirecto.value = u.direct_manager ?? "";
    $campoEquipoAsignado.value = u.equip_asigned ?? "";
  }

  function limpiarSiVacios() {
    if (!$campoUsuario.value.trim() && !$campoNomina.value.trim()) {
      $campoDepto.value = "";
      $campoIdUsuario.value = "";
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
        `${urls}liberacion/encontrar?` + parametrosURL({ num_nomina: nomina })
      );
      const { ok, data } = await resp.json();
      if (!ok || !Array.isArray(data) || !data.length) {
        $campoIdUsuario.value = "";
        $campoUsuario.value = "";
        $campoDepto.value = "";
        $campoJefeDirecto.value = "";
        $campoEquipoAsignado.value = "";
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
        `${urls}liberacion/encontrar?` + parametrosURL({ usuario: texto })
      );
      const { ok, data } = await resp.json();

      // si el usuario siguió escribiendo, descartar esta respuesta
      if (texto !== textoUltimo) return;
      if (!ok || !Array.isArray(data)) return;

      $lista.innerHTML = "";
      //cacheUsuarios.clear();

      if (!data.length) return;

      const opciones = data
        .map((u) => {
          const clave = `${u.full_name} | ${u.payroll_number}`;
          cacheUsuarios.set(clave, u); // ✅ No borra lo anterior
          return `<option value="${clave}" data-clave="${clave}" label="${u.department}"></option>`;
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
    const texto = $campoUsuario.value.trim();
    if (!texto) {
      limpiarSiVacios();
      return;
    }

    const lista = obtenerListaUsuarios();
    const opcion = Array.from(lista.options).find(
      (o) => o.value.trim() === texto
    );

    const clave = opcion?.dataset?.clave;

    // 🚀 Si el usuario seleccionó una opción válida del datalist
    if (clave && cacheUsuarios.has(clave)) {
      const usuario = cacheUsuarios.get(clave);
      console.log("✅ Usuario encontrado desde cache:", clave, usuario);
      llenarCamposUsuario(usuario);
      let nombreLimpio = usuario.full_name;
      if (texto.includes("|")) {
        nombreLimpio = texto.split("|")[0].trim();
      }

      // Limpia visualmente el input dejando solo el nombre
      $campoUsuario.value = nombreLimpio;
    } else {
      // 🛑 Si el usuario escribió a mano o no coincide exactamente con el datalist
      let nombreLimpio = texto;
      if (texto.includes("|")) {
        nombreLimpio = texto.split("|")[0].trim();
      }

      console.log("🔍 Buscando por nombre limpio:", nombreLimpio);

      fetch(
        `${urls}liberacion/encontrar?` +
          new URLSearchParams({ usuario: nombreLimpio })
      )
        .then((res) => res.json())
        .then(({ ok, data }) => {
          if (ok && data && data.length) {
            llenarCamposUsuario(data[0]);
            $campoUsuario.value = data[0].full_name;
          }
        })
        .catch(console.error);
    }
  });

  autocompletadoInicializado = true;
}

// 1) Intento cuando el DOM está listo
document.addEventListener("DOMContentLoaded", inicializarAutocompletadoUsuario);

$("#modalCreateRequestLiberation").on("shown.bs.modal", function () {
  inicializarAutocompletadoUsuario(); // ← asegura inicialización al abrir el modal
});

$("#user_name")
  .off("change")
  .on("change", function () {
    let selectedOption = $(this).find("option:selected");
    if (selectedOption.val()) {
      $("#payroll_number").val(selectedOption.data("payroll"));
      $("#department").val(selectedOption.data("department"));
      $("#department_id").val(selectedOption.data("department_id"));
      $("#direct_manager").val(selectedOption.data("manager"));
      $("#tel").val(selectedOption.data("tel"));
      $("#equip_asigned").val(selectedOption.data("equip"));

      let userId = selectedOption.val();
      let users = $("#user_name").data("all-users");
      let user = users.find((u) => u.id_user == userId);

      let equipDiv = $("#equip_info");
      equipDiv.empty();
      if (user && user.equip_info && user.equip_info.length > 0) {
        user.equip_info.forEach((e) => {
          equipDiv.append(
            `<div class="mb-1">
                    <strong>Modelo:</strong> ${e.model || e.modelo} <br>
                    <strong>Marca:</strong> ${e.marca} <br>
                    <strong>No. Serial:</strong> ${e.no_serial || ""}
                </div><hr>`
          );
        });
      } else {
        equipDiv.append("<small>No hay equipos asignados</small>");
      }
    } else {
      limpiarCampos();
      $("#equip_info")
        .empty()
        .append("<small>No hay equipos asignados</small>");
    }
  });

(function wireBuscarPorNomina() {
  const $m = $("#modalCreateRequestLiberation");
  let t;

  $m.find("#payroll_number")
    .off("input change")
    .on("input", function () {
      clearTimeout(t);
      const nomina = this.value.trim();
      if (!nomina) return;
      t = setTimeout(() => fetchNomina(nomina), 250);
    })
    .on("change", function () {
      const nomina = this.value.trim();
      if (!nomina) return;
      fetchNomina(nomina);
    });

  async function fetchNomina(nomina) {
    try {
      const qs = new URLSearchParams({ num_nomina: nomina }).toString();
      const resp = await fetch(`${urls}liberacion/encontrar?` + qs);
      const { ok, data } = await resp.json();
      if (ok && Array.isArray(data) && data.length) {
        pintarUsuarioEnModal(data[0]);
      }
    } catch (e) {
      console.error(e);
    }
  }
})();

(function wireBuscarPorNombre() {
  const $m = $("#modalCreateRequestLiberation");
  const $campoUsuario = $m.find("#user_name");

  // change cuando el usuario elige una opción del datalist o escribe y confirma
  $campoUsuario.off("change").on("change", async function () {
    let texto = this.value.trim();
    if (!texto) return;

    // Si viene "NOMBRE | 123", separar nombre limpio y clave completa
    let nombreLimpio = texto;
    let clave = null;
    if (texto.includes("|")) {
      const [n, p] = texto.split("|").map((s) => s.trim());
      nombreLimpio = n;
      clave = `${n} | ${p}`;
    }

    // Intentar datalist exacto (si existe)
    const lista = document.getElementById("lista-usuarios");
    const opcion = Array.from(lista?.options ?? []).find(
      (o) => o.value.trim() === texto
    );
    const claveLista = opcion?.dataset?.clave ?? clave;

    // Si lo tenemos en cache, pintar directo
    if (claveLista && cacheUsuarios.has(claveLista)) {
      const u = cacheUsuarios.get(claveLista);
      pintarUsuarioEnModal(u);
      // Deja solo el nombre en el input
      this.value = u.full_name ?? nombreLimpio;
      return;
    }

    // Si no está en cache, buscamos por nombre limpio
    try {
      const qs = new URLSearchParams({ usuario: nombreLimpio }).toString();
      const resp = await fetch(`${urls}liberacion/encontrar?` + qs);
      const { ok, data } = await resp.json();
      if (ok && Array.isArray(data) && data.length) {
        const u = data[0];
        // guardar en cache con la misma clave que pintas en datalist
        const k = `${u.full_name} | ${u.payroll_number}`;
        cacheUsuarios.set(k, u);
        pintarUsuarioEnModal(u);
        this.value = u.full_name ?? nombreLimpio;
      }
    } catch (e) {
      console.error(e);
    }
  });
})();
