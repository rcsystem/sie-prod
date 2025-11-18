$(document).ready(function () {
  tbl_viaticos = initTablaLiberation();
  $("#tabla_liberation thead").addClass("thead-dark text-center");
});

const ADEUDO_ITEMS = new Set([
  "Casco",
  "Zapatos de seguridad",
  "Uniforme",
  "Lentes de seguridad",
  "Marbete",
  "Computadora",
  "Alan Landa",
  "Lap-Top",
  "Celular",
  "Credencial",
  "Auto",
  "Viáticos",
  "Préstamos",
  "Walworth Store",
  "Material Promocional",
  "Equipo del departamento",
  "Equipo de medición",
  "Herramientas",
  "Préstamo",
]);

function initTablaLiberation() {
  return $("#tabla_liberation").DataTable({
    processing: true,
    ajax: {
      method: "POST",
      url: `${urls}liberacion/todos_las_solicitudes_del_departamento`,
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
      { data: "folio", title: "Folio", className: "text-center" },
      {
        data: "user_name",
        title: "Nombre del solicitante",
        className: "text-center",
      },
      { data: "nomina", title: "Nomina", className: "text-center" },
      { data: "department_name", title: "Liberar", className: "text-center" },

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
        render: function (data) {
          return `
            <div class="text-center">
                <button type="button" class="btn btn-outline-info btn-sm" title="Editar" onclick="abrirModal(${
                  data.request_id
                }, '${data.department_name}')">
                    <i class="fas fa-edit"></i>
                </button> 

                 <a href="javascript:void(0);" class="btn btn-outline-info btn-sm" style="margin-right: 5px;"
                    data-toggle="modal" 
                    data-target="#pdfModal" 
                    data-folio ="${data.folios}"
                    data-url="${urls}liberacion/ver-solicitud/${$.md5(
            key + data.folios
          )}">
                      <i class="fas fa-eye"></i>
                  </a>
            </div>
          `;
        },
        title: "Acciones",
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

function abrirModal(folio, department_name) {
  $("#editarLiberation").modal("show");
  // 👇 guarda el request y el área activa para refrescar la fila correcta
  window.ctxLiberation = { requestId: folio, deptName: department_name };

  limpiarItems();
  cargarItems(folio, department_name);
}

function limpiarItems() {
  $("#items_container").empty();
}

function cargarItems(folio, department_id) {
  $.ajax({
    method: "POST",
    url: `${urls}liberacion/getItemsByRequestAndDepartment`,
    data: { request_id: folio, department_name: department_id },
    success: function (response) {
      if (response.error) {
        mostrarError(response.error);
        return;
      }

      // Mostrar info de la solicitud (opcional)
      mostrarInfoSolicitud(response.request_info);

      // Verificar si hay items
      if (!response.items || response.items.length === 0) {
        mostrarMensaje("No hay items para este departamento.");
        return;
      }

      // Renderizar items
      renderizarItems(response.items, folio);
      activarCheckboxListener();
    },
    error: function () {
      mostrarError("Error al cargar items.");
    },
  });
}

function mostrarError(mensaje) {
  $("#items_container").html(
    `<div class="alert alert-danger">${mensaje}</div>`
  );
}

function mostrarMensaje(mensaje) {
  $("#items_container").html(`<p>${mensaje}</p>`);
}

/* function mostrarInfoSolicitudANT(request_info) {
  // Generamos el HTML con la información de la solicitud
  let html = `
    <div id="info_solicitud" class="mb-3 p-3 border rounded bg-light">
      <p><strong>No Nomina:</strong> ${request_info.payroll_number}</p>
      <p><strong>Solicitante:</strong> ${request_info.name || ""} ${
    request_info.surname || ""
  } ${request_info.second_surname || ""} </p>
      <p><strong>Estado de la solicitud:</strong> ${
        request_info.request_status
      }</p>
      <p><strong>Email:</strong> ${request_info.user_email || ""}</p>
    </div>
  `;

  // Insertamos encima de los items
  $("#items_container").html(html);
} */

function mostrarInfoSolicitud(request_info) {
  let html = `
    <div id="info_solicitud" class="mb-3 p-3 border rounded bg-white">
      <div class="row g-2">
        <div class="col-6 col-md-2">
          <div class="label">No Nómina</div>
          <div class="value">${request_info.payroll_number ?? ""}</div>
        </div>
        <div class="col-12 col-md-5">
          <div class="label">Solicitante</div>
          <div class="value">
            ${request_info.name ?? ""} ${request_info.surname ?? ""} ${
    request_info.second_surname ?? ""
  }
          </div>
        </div>
        <div class="col-6 col-md-2">
          <div class="label">Estado</div>
          <div class="value">${request_info.request_status ?? ""}</div>
        </div>
        <div class="col-6 col-md-2">
          <div class="label">Tipo de Nomina</div>
          <div class="value">${request_info.payroll_type ?? ""}</div>
        </div>
      </div>
    </div>
  `;
  $("#items_container").html(html);
}

function renderizarItems(items,folio) {
  /* <button class="btn btn-sm btn-outline-success me-2" id="btnFirmarTodos">
          <i class="fas fa-check"></i> Firmar todos
        </button>
        <button class="btn btn-sm btn-outline-secondary" id="btnQuitarTodos">
          <i class="fas fa-times"></i> Quitar firmas
        </button> */
  let html = `
    <div class="d-flex justify-content-between align-items-center mb-2 items-header-actions">
      <h6 class="m-0">Ítems del departamento</h6>
      <div>
        
      </div>
    </div>
    <table class="table table-sm table-liberation">
      <thead class="table-light">
        <tr>
          <th style="width:45%">Ítem</th>
          <th style="width:20%">Adeudo</th>
          <th style="width:15%">Estado</th>
          <th style="width:20%" class="text-center">Acción</th>
        </tr>
      </thead>
      <tbody>
  `;

  items.forEach((item) => {
    const entregado = Number(item.signed) === 1; // firmado
    const adeudoVal = Number(item.owed_amount) || 0;
    const showInput = ADEUDO_ITEMS.has(String(item.item_name).trim());
    const disableSwitch = showInput && adeudoVal > 0;

    html += `
  <tr data-id="${item.request_item_id}" data-has-adeudo="${showInput ? 1 : 0}">
    <td class="item-name">${item.item_name}</td>
    <td class="adeudo-cell">
      ${
        showInput
          ? `
        <div class="input-group input-group-sm" style="max-width:160px;">
          <span class="input-group-text" style="padding:.1rem .75rem;">$</span>
          <input type="number" step="0.01" min="0" class="form-control adeudo-input" value="${adeudoVal.toFixed(
            2
          )}">
        </div>
      `
          : `<em class="text-muted">N/A</em>`
      }
    </td>
    <td class="estado-cell"></td> <!-- badge se setea después -->
    <td class="text-center">
      <div class="form-check form-switch d-inline-block">
        <input class="form-check-input signed-switch" type="checkbox"
               ${entregado ? "checked" : ""} ${
      disableSwitch ? "disabled" : ""
    }/>
      </div>
    </td>
  </tr>
`;
  });

  html += `</tbody></table>`;
  // 🔹 Bloque de comentarios enriquecidos
  html += `
  <div id="bloque_comentarios" class="mt-3 p-3 border rounded bg-white">
    <div class="form-check mb-2">
      <input class="form-check-input" type="checkbox" id="chk_agregar_comentario">
      <label class="form-check-label" for="chk_agregar_comentario">
        Agregar comentario 
      </label>
    </div>

    <div id="editor_contenedor" class="border rounded" style="display:none;">
      <div id="editor_toolbar">
        <span class="ql-formats">
          <button class="ql-bold"></button>
          <button class="ql-underline"></button>
          <button class="ql-italic"></button>
        </span>
        <span class="ql-formats">
          <button class="ql-list" value="ordered"></button>
          <button class="ql-list" value="bullet"></button>
        </span>
        <span class="ql-formats">
          <button class="ql-link"></button>
          <button class="ql-clean"></button>
        </span>
       
      </div>
      <div id="editor_comentario" style="height:150px;background:#fff;"></div>
    </div>

    <div class="text-end mt-2">
      <button type="button" class="btn btn-primary btn-sm" id="btn_enviar_comentario" disabled>
        <i class="fas fa-paper-plane"></i> Enviar comentario
      </button>
    </div>

    <div id="comentario_feedback" class="mt-2"></div>
  </div>
`;

  $("#items_container").append(html);

  // Setear badges iniciales (tri-estado)
  $("#items_container tr[data-id]").each(function () {
    const $row = $(this);
    const signed = $row.find(".signed-switch").is(":checked");
    const input = $row.find(".adeudo-input")[0];
    const adeudo = input ? Number(input.value) || 0 : 0;
    setBadgeState($row, { signed, adeudo });
  });

  activarCheckboxListener();
  activarAdeudoListener();
  activarComentarioUIQuill(); // 🔹 NUEVO
}

let editorQuill = null;

function activarComentarioUIQuill() {
  const $check = $('#chk_agregar_comentario');
  const $btn   = $('#btn_enviar_comentario');

  $check.off('change').on('change', function () {
    const activo = $(this).is(':checked');
    $('#editor_contenedor').toggle(activo);
    $btn.prop('disabled', !activo);

    // ← evita re-crear Quill si ya existe
    if (activo && !editorQuill) {
      initQuillEditor();
    }
    if (!activo && editorQuill) {
      editorQuill.setContents([]); // limpia contenido
    }
  });

  $btn.off('click').on('click', function () {
    if (!$('#chk_agregar_comentario').is(':checked')) return;
    if (!editorQuill) return;

    // 🔹 Contenido como HTML (lo que guardarás)
    const html = editorQuill.root.innerHTML.trim();
    // También podrías guardar el Delta:
    // const delta = editorQuill.getContents();

    // Evitar guardar vacío (Quill pone <p><br></p>)
    const vacio = html === '<p><br></p>' || html === '';
    if (vacio) {
      mostrarFeedbackComentario('warning', 'Escribe tu Comentario.');
      return;
    }

    const folio = window.ctxLiberation?.requestId;
    const area  = window.ctxLiberation?.deptName;

    $.ajax({
      method: 'POST',
      url: `${urls}liberacion/guardarComentario`,
      data: {
        request_id: folio,
        department_name: area,
        comentario_html: html
      },
      beforeSend: function(){
        $btn.prop('disabled', true);
      },
      success: function (res) {
        mostrarFeedbackComentario('success', 'Comentario guardado correctamente.');
        // Limpia editor
        editorQuill.setContents([]);
        $('#chk_agregar_comentario').prop('checked', false).trigger('change');

        if (res && res.dept_status) {
          actualizarFilaTablaDepartamento(res.dept_status, res.request_status);
        }
        $('#tabla_liberation').DataTable().ajax.reload(null, false);
      },
      error: function () {
        mostrarFeedbackComentario('danger', 'Error al guardar el comentario.');
        $btn.prop('disabled', false);
      }
    });
  });
}

function initQuillEditor() {
  editorQuill = new Quill('#editor_comentario', {
    theme: 'snow',
    modules: {
      toolbar: {
        container: '#editor_toolbar',
        handlers: {
          image: funcionSubirImagenQuill // 🔹 handler personalizado
        }
      }
    }
  });
}

// 🔹 Handler para subir imagen: abre file input, sube y coloca URL en el editor
function funcionSubirImagenQuill() {
  const input = document.createElement('input');
  input.setAttribute('type', 'file');
  input.setAttribute('accept', 'image/*');
  input.click();

  input.onchange = () => {
    const archivo = input.files[0];
    if (!archivo) return;

    const formData = new FormData();
    formData.append('imagen', archivo);

    // Puedes incluir metadata opcional (folio/área) si deseas
    const folio = window.ctxLiberation?.requestId;
    const area  = window.ctxLiberation?.deptName;
    formData.append('request_id', folio || '');
    formData.append('department_name', area || '');

    $.ajax({
      url: `${urls}liberacion/subirImagenComentario`,
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function (res) {
        if (res && res.url) {
          const rango = editorQuill.getSelection(true);
          editorQuill.insertEmbed(rango.index, 'image', res.url, 'user');
          editorQuill.setSelection(rango.index + 1, 0);
        } else {
          mostrarFeedbackComentario('danger', 'No se recibió URL de la imagen.');
        }
      },
      error: function () {
        mostrarFeedbackComentario('danger', 'Error al subir la imagen.');
      }
    });
  };
}

function mostrarFeedbackComentario(tipo, mensaje) {
  $('#comentario_feedback').html(`
    <div class="alert alert-${tipo} py-1 px-2 mb-0">${mensaje}</div>
  `);
}

$('#editarLiberation').on('hidden.bs.modal', function () {
  // 1) destruir editor si existe
  try {
    if (editorQuill) {
      // Quill no tiene destroy oficial, pero puedes quitar eventos y vaciar el contenedor
      $('#editor_comentario').off();
      editorQuill = null;
    }
  } catch (e) { /* noop */ }

  // 2) limpiar contenido del modal
  $('#items_container').empty();

  // 3) quitar cualquier feedback pendiente
  $('#comentario_feedback').empty();
});


function activarAdeudoListener() {
  $(".adeudo-input")
    .off("change blur")
    .on("change blur", function () {
      const $row = $(this).closest("tr");
      const id = $row.data("id");
      let val = parseFloat(this.value);
      if (isNaN(val) || val < 0) val = 0;

      $.ajax({
        method: "POST",
        url: `${urls}liberacion/updateItemSigned`,
        data: { request_item_id: id, owed_amount: val },
        success: function () {
          const $switch = $row.find(".signed-switch");

          // Solo filas con input de adeudo (N/A no pasan por aquí)
          if (val > 0) {
            // Deshabilitar y, si estaba marcado, desmarcar y persistir signed=0
            if ($switch.is(":checked")) {
              $switch.prop("checked", false);
              $.post(`${urls}liberacion/updateItemSigned`, {
                request_item_id: id,
                signed: 0,
              });
            }
            $("#tabla_liberation").DataTable().ajax.reload(null, false);

            $switch.prop("disabled", true);
          } else {
            // Rehabilitar cuando monto = 0
            $switch.prop("disabled", false);
          }

          pintarBadge($row, $switch.is(":checked"));
        },
        error: function () {
          mostrarError("Error al actualizar adeudo.");
        },
      });
    });
}

function actualizarAdeudo(request_item_id, owed_amount, onOk, onErr) {
  $.ajax({
    method: "POST",
    url: `${urls}liberacion/updateItemSigned`,
    data: { request_item_id, owed_amount }, // sin 'signed'
    success: function (res) {
      // refresca tu tabla si aplica
      if (res && res.success) onOk && onOk();
      else
        onErr && onErr(res && res.error ? res.error : "Error al actualizar.");
      // 🔥 refresca la fila en la tabla principal
      if (res && res.dept_status) {
        actualizarFilaTablaDepartamento(res.dept_status, res.request_status);
      }
      $("#tabla_liberation").DataTable().ajax.reload(null, false);
    },
    error: function () {
      onErr && onErr("Error en la petición al actualizar adeudo.");
    },
  });
}

async function toggleTodos(on) {
  const $switches = $(".signed-switch:not(:disabled)");
  for (const el of $switches.toArray()) {
    const $sw = $(el);
    const $row = $sw.closest("tr");
    const id = $row.data("id");
    if ($sw.prop("checked") !== on) {
      $sw.prop("checked", on);
      try {
        const res = await $.post(`${urls}liberacion/updateItemSigned`, {
          request_item_id: id,
          signed: on ? 1 : 0,
        });
        pintarBadge($row, on);
        if (res && res.dept_status) {
          actualizarFilaTablaDepartamento(res.dept_status, res.request_status);
        }
        $("#tabla_liberation").DataTable().ajax.reload(null, false);
      } catch (e) {
        console.error(e);
      }
    }
  }
}

function activarCheckboxListener() {
  $(".signed-switch")
    .off("change")
    .on("change", function () {
      const $row = $(this).closest("tr");
      const id = $row.data("id");
      const on = $(this).is(":checked");

      $.ajax({
        method: "POST",
        url: `${urls}liberacion/updateItemSigned`,
        data: { request_item_id: id, signed: on ? 1 : 0 },
        success: function (res) {
          // badge de la fila (modal)
          pintarBadge($row, on);

          // 🔥 refresca la fila en la tabla (usa lo que regresa el backend)
          if (res && res.dept_status) {
            actualizarFilaTablaDepartamento(
              res.dept_status,
              res.request_status
            );
          } else {
            // fallback: si al menos hay un cambio, asumimos “EN PROGRESO”
            actualizarFilaTablaDepartamento("EN PROGRESO");
          }
          $("#tabla_liberation").DataTable().ajax.reload(null, false);
        },
        error: function () {
          mostrarError("Error al actualizar firma.");
        },
      });
    });
}

function pintarBadge($row, entregado) {
  $row
    .find(".estado-cell")
    .html(
      entregado
        ? '<span class="badge badge-firmado">Firmado</span>'
        : '<span class="badge badge-pendiente">Pendiente</span>'
    );
}

function actualizarSignedANT(request_item_id, signed) {
  $.ajax({
    method: "POST",
    url: `${urls}liberacion/updateItemSigned`,
    data: { request_item_id, signed },
    success: function (res) {
      tbl_viaticos.ajax.reload(null, false);
      if (res.error) {
        alert("Error al actualizar: " + res.error);
      }
      $("#tabla_liberation").DataTable().ajax.reload(null, false);
    },
    error: function () {
      alert("Error en la petición al actualizar el estado firmado.");
    },
  });
}
function actualizarSigned(request_item_id, signed, onOk, onErr) {
  $.ajax({
    method: "POST",
    url: `${urls}liberacion/updateItemSigned`,
    data: { request_item_id, signed },
    success: function (res) {
      tbl_viaticos.ajax.reload(null, false); // actualiza listado
      if (res && res.success) {
        onOk && onOk();
      } else {
        onErr && onErr(res && res.error ? res.error : "Error al actualizar.");
      }
      $("#tabla_liberation").DataTable().ajax.reload(null, false);
    },
    error: function () {
      onErr && onErr("Error en la petición al actualizar el estado firmado.");
    },
  });
}

function badgeEstado(entregado) {
  return entregado
    ? '<span class="badge badge-firmado">Firmado</span>'
    : '<span class="badge badge-pendiente">Pendiente</span>';
}

function pintarBadge($row, entregado) {
  $row.find(".estado-cell").html(badgeEstado(!!entregado));
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

function activarCheckboxListener() {
  $(".signed-switch")
    .off("change")
    .on("change", function () {
      const $row = $(this).closest("tr");
      const id = $row.data("id");
      const on = $(this).is(":checked");

      $.ajax({
        method: "POST",
        url: `${urls}liberacion/updateItemSigned`,
        data: { request_item_id: id, signed: on ? 1 : 0 },
        success: function (res) {
          // pinta el badge del renglón de ítems
          pintarBadge($row, on);

          // 🔥 refresca la fila de la tabla (usa dept_status de la respuesta)
          if (res && res.dept_status) {
            actualizarFilaTablaDepartamento(
              res.dept_status,
              res.request_status
            );
          }
          $("#tabla_liberation").DataTable().ajax.reload(null, false);
        },
        error: function () {
          mostrarError("Error al actualizar firma.");
        },
      });
    });
}
function activarAdeudoListener() {
  $(".adeudo-input")
    .off("change blur")
    .on("change blur", function () {
      const $row = $(this).closest("tr");
      const id = $row.data("id");
      let val = parseFloat(this.value);
      if (isNaN(val) || val < 0) val = 0;

      $.ajax({
        method: "POST",
        url: `${urls}liberacion/updateItemSigned`,
        data: { request_item_id: id, owed_amount: val },
        success: function (res) {
          const $switch = $row.find(".signed-switch");

          if (val > 0) {
            // si hay adeudo, el check no debe quedar activo
            if ($switch.is(":checked")) {
              $switch.prop("checked", false);
              $.post(`${urls}liberacion/updateItemSigned`, {
                request_item_id: id,
                signed: 0,
              });
            }
            $("#tabla_liberation").DataTable().ajax.reload(null, false);

            $switch.prop("disabled", true);
          } else {
            $switch.prop("disabled", false);
          }

          // badge del renglón del modal: “Firmado” si (check) o (adeudo>0)
          const entregado = $switch.is(":checked") || val > 0;
          pintarBadge($row, entregado);

          // 🔥 refresca la fila de la tabla
          if (res && res.dept_status) {
            actualizarFilaTablaDepartamento(
              res.dept_status,
              res.request_status
            );
          } else {
            // si pusiste un monto > 0, mínimo debe quedar EN PROGRESO
            actualizarFilaTablaDepartamento(
              val > 0 ? "EN PROGRESO" : undefined
            );
          }
        },
        error: function () {
          mostrarError("Error al actualizar adeudo.");
        },
      });
    });
}

function actualizarFilaTablaDepartamentoANT(newDeptStatus, newReqStatus) {
  if (!window.ctxLiberation || !tbl_viaticos) return;

  // Tus filas tienen id="liberation_<folio>"
  const sel = "#liberation_" + window.ctxLiberation.requestId;
  const row = tbl_viaticos.row(sel);
  if (!row || !row.node()) return;

  const data = row.data();
  // Esta tabla (de departamento) muestra el estado del área:
  data.request_status = newDeptStatus || data.request_status;

  row.data(data).invalidate(); // re-render
  $(row.node()).addClass("table-success");
  setTimeout(() => $(row.node()).removeClass("table-success"), 600);
}

function actualizarFilaTablaDepartamento(newDeptStatus, newReqStatus) {
  if (!window.ctxLiberation || !window.tbl_viaticos) return;

  const rowSelector = "#liberation_" + window.ctxLiberation.requestId; // id="liberation_<folio>"
  const row = tbl_viaticos.row(rowSelector);
  if (!row || !row.node()) return;

  const data = row.data();
  data.request_status = newDeptStatus || data.request_status; // pinta EN PROGRESO/FIRMADO/etc

  row.data(data).invalidate(); // re-render
  $(row.node()).addClass("table-success");
  setTimeout(() => $(row.node()).removeClass("table-success"), 700);
}

function badgeHtml({ signed, adeudo }) {
  if (signed) {
    return '<span class="badge badge-firmado">Firmado</span>';
  }
  if (adeudo > 0) {
    return '<span class="badge badge-adeudo">Adeudo</span>';
  }
  return '<span class="badge badge-pendiente">Pendiente</span>';
}

function setBadgeState($row, state) {
  $row.find(".estado-cell").html(badgeHtml(state));
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
