$(document).ready(function () {
  $('[data-toggle="tooltip"]').tooltip(); // Inicializar tooltips
  tbl_request = $("#tbl_solicitudes_msi")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}logistica/solicitudes_logistica`,
        dataSrc: "data",
      },
      lengthChange: true,
      ordering: true,
      responsive: true,
      autoWidth: false,
      rowId: "id_solicitud", // ✔️ usa el campo correcto
      dom: "lBfrtip",
      buttons: [
        {
          extend: "excelHtml5",
          title: "Solicitudes de Movimiento de Inventario",
          exportOptions: { columns: [0, 1, 2, 3, 4, 5] },
        },
      ],
      language: {
        url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
      },
      columns: [
        { data: "id_solicitud", title: "Folio", className: "text-center" },
        { data: "concepto", title: "Concepto", className: "text-center" },

        {
          data: null,
          title: "Estatus",
          className: "text-center",
          render: function (data) {
            const est = data["estatus_solicitud"] || "";
            if (!est) return "----";
            switch (est) {
              case "GERMAN VELAZQUEZ":
                return `<span class="badge badge-warning">${est}</span>`;
              case "ABRAHAM GERARDO SERNAS":
                return `<span class="badge badge-info">${est}</span>`;
              case "ANIBAL MOLINA":
                return `<span class="badge badge-info">${est}</span>`;
              case "GUSTAVO ANGELES":
                return `<span class="badge badge-info">${est}</span>`;
              case "FRANCISCO ENRICO PEREZ":
                return `<span class="badge badge-info">${est}</span>`;
              case "APROBADO":
                return `<span class="badge badge-success">${est}</span>`;
              default:
                return `<span class="badge badge-info">Error</span>`;
            }
          },
        },

        {
          data: null,
          title: "Acciones",
          className: "text-center",
          render: function (data) {
            const id = data["id_solicitud"];

            const aprovadorActual = data["estatus_solicitud"];
            // Si tu API ya devuelve ruta relativa, úsala. Si no, se calcula dentro de abrirModalPdf.
            const rutaRel = data["ruta_archivo"] || "";

            // OJO: escapamos comillas simples en la ruta para no romper el onclick
            const rutaEsc = String(rutaRel).replace(/'/g, "\\'");

            return `
            <div class="btn-group" role="group">
              <button type="button" class="btn btn-outline-primary btn-sm"
                      data-toggle="tooltip" title="Ver PDF"
                      onclick="abrirModalPdf(${id}, '${rutaEsc}','${aprovadorActual}')">
                <i class="fas fa-eye"></i>
              </button>
                           <button type="button" class="btn btn-outline-danger btn-sm"
                      data-toggle="tooltip" title="Eliminar (soft)"
                      onclick="cambiarEstatusArchivo(${id})">
                <i class="fas fa-trash-alt"></i>
              </button>
            </div>
          `;
          },
        },
      ],
      destroy: "true",
      order: [[0, "DESC"]],
      createdRow: (row, data) => {
        $(row).attr("id", "solicitud_" + data.id_solicitud);
        // Si viene un flag de eliminado, lo marcamos visualmente
        if (Number(data.estatus_archivo) === 0) {
          $(row).addClass("table-danger text-muted");
        }
      },
    })
    .DataTable();

  $("#tbl_solicitudes_msi thead").addClass("text-center");
});

function actualizarTelefono(id) {
  $.ajax({
    url: `${urls}logistica/actualizar_solicitud`,
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    beforeSend: function () {
      $("#btnGuardarSolicitud").prop("disabled", true).text("Guardando...");
    },
    success: function (res) {
      if (res.ok) {
        tbl_request.ajax.reload(null, false);
        Swal.fire({
          icon: "success",
          title: "Éxito",
          text: "Solicitud registrada con ID: " + res.id,
        }).then(() => {
          $("#formSolicitud")[0].reset();
          // Si está dentro de modal → ciérralo
          // $('#modalSolicitud').modal('hide');
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: res.msg,
        });
      }
    },
    error: function () {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Ocurrió un error al guardar la solicitud.",
      });
    },
    complete: function () {
      $("#btnGuardarSolicitud").prop("disabled", false).text("Guardar");
      $("#btnGuardarSolicitud").prop("disabled", false).text("Guardar");
    },
  });
}

$(document).ready(function () {
  $(document).on("focus", ".form-control", function () {
    $(this).parent(".form-group").addClass("fill");
  });

  $.ajax({
    url: `${urls}logistica/conceptos`,
    type: "GET",
    dataType: "json",
    success: function (respuesta) {
      let $select = $("#conceptos");
      $select
        .empty()
        .append('<option value="">Seleccione un concepto</option>');

      $.each(respuesta, function (i, dep) {
        $select.append(
          '<option value="' +
            dep.nombre_concepto +
            '">' +
            dep.nombre_concepto +
            "</option>"
        );
      });
    },
    error: function (xhr, status, error) {
      console.error("Error al cargar conceptos:", error);
    },
  });
});

function abrirSolicitudModal() {
  var miModal = new bootstrap.Modal(document.getElementById("solicitudModal"));
  miModal.show();
}

$(document).ready(function () {
  $("#formSolicitud").submit(function (e) {
    e.preventDefault();

    let concepto = $("#conceptos").val();
    let archivo = $("#pdfFile")[0].files[0];
    let aprobador_actual = usuario_actual;

    // 🔸 Validaciones
    if (concepto === "") {
      Swal.fire({
        icon: "warning",
        title: "Atención",
        text: "Debes ingresar un concepto.",
      });
      return;
    }

    if (!archivo) {
      Swal.fire({
        icon: "warning",
        title: "Atención",
        text: "Debes seleccionar un archivo PDF.",
      });
      return;
    }

    if (archivo.type !== "application/pdf") {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "El archivo debe ser un PDF válido.",
      });
      return;
    }

    // 🔸 Preparar FormData
    let formData = new FormData();
    formData.append("concepto", concepto);
    formData.append("archivo_pdf", archivo);
    formData.append("aprobador_actual", aprobador_actual);

    // 🔸 Bloquear botón antes de enviar
    $("#btnGuardarSolicitud").prop("disabled", true).text("Enviando...");

    // 🔸 Enviar por AJAX
    $.ajax({
      url: `${urls}logistica/guardar_solicitud`,
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      beforeSend: function () {
        $("#btnGuardarSolicitud").prop("disabled", true).text("Guardando...");
      },
      success: function (res) {
        if (res.ok) {
          tbl_request.ajax.reload(null, false);
          Swal.fire({
            icon: "success",
            title: "Éxito",
            text: "Solicitud registrada con ID: " + res.id,
          }).then(() => {
            $("#formSolicitud")[0].reset();
            // Si está dentro de modal → ciérralo
            // $('#modalSolicitud').modal('hide');
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: res.msg,
          });
        }
      },
      error: function () {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Ocurrió un error al guardar la solicitud.",
        });
      },
      complete: function () {
        $("#btnGuardarSolicitud").prop("disabled", false).text("Guardar");
        $("#btnGuardarSolicitud").prop("disabled", false).text("Guardar");
      },
    });
  });
});

(function () {
  // Orden de aprobadores
  const ORDEN = [
    "German Velazquez",
    "Abraham Sernas",
    "Anibal Molina",
    "Gustavo Angeles",
    "Enrico Perez",
  ];

  // Abrir modal y preparar botones
  function abrirModalPdf(id_solicitud, ruta_relativa, aprobador_actual) {
    const urlPdf = `${urls}public/${ruta_relativa}`;

    $("#folioPdf").text("#" + id_solicitud);
    $("#lblAprobadorActual").text(aprobador_actual || "—");
    $("#smi_id_actual").val(id_solicitud);
    $("#smi_aprobador_actual").val(aprobador_actual || "");

    // Por default bloqueo ambos
    $("#btnFirmar").prop("disabled", true);
    $("#btnEnviarSiguiente").prop("disabled", true);

    // Solo habilito "Firmar" si el usuario actual coincide con el aprobador actual
    if (aprobador_actual && usuario_actual === aprobador_actual) {
      $("#btnFirmar").prop("disabled", false);
    }

    $("#visorPdfSolicitud").attr("src", urlPdf + `?t=${Date.now()}`);

    var miModal = new bootstrap.Modal(
      document.getElementById("modalPdfSolicitud")
    );
    miModal.show();
  }
  window.abrirModalPdf = abrirModalPdf;

  // Firmar documento (aprobador actual)
  $("#btnFirmar").on("click", function () {
    const id = $("#smi_id_actual").val();
    const aprobador = $("#smi_aprobador_actual").val();

    if (!id || !aprobador) {
      Swal.fire({
        icon: "warning",
        title: "Atención",
        text: "No hay información del aprobador actual.",
      });
      return;
    }

    Swal.fire({
      title: "Firmando...",
      didOpen: () => Swal.showLoading(),
      allowOutsideClick: false,
    });

    $.ajax({
      url: `${urls}logistica/solicitudes/firmar`,
      type: "POST",
      dataType: "json",
      data: { id_solicitud: id, aprobador_actual: aprobador },
      success: function (res) {
        if (res.ok) {
          tbl_request.ajax.reload(null, false);
          Swal.fire({
            icon: "success",
            title: "Firmado",
            text: res.msg || "Firma aplicada correctamente",
          });
          // Habilitar "Enviar al siguiente"
          $("#btnEnviarSiguiente").prop("disabled", false);
          // refrescar iframe para ver firma
          const src = $("#visorPdfSolicitud").attr("src").split("?")[0];
          $("#visorPdfSolicitud").attr("src", src + `?t=${Date.now()}`);
        } else {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: res.msg || "No se pudo firmar.",
          });
        }
      },
      error: function () {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Error de comunicación con el servidor.",
        });
      },
    });
  });

  // Enviar al siguiente aprobador
  $("#btnEnviarSiguiente").on("click", function () {
    const id = $("#smi_id_actual").val();
    const aprobador = $("#smi_aprobador_actual").val();

    Swal.fire({
      icon: "question",
      title: "¿Enviar al siguiente?",
      text: "Se notificará al siguiente aprobador.",
      showCancelButton: true,
      confirmButtonText: "Sí, enviar",
      cancelButtonText: "Cancelar",
    }).then(function (r) {
      if (!r.isConfirmed) return;

      $.ajax({
        url: `${urls}logistica/solicitudes/avanzar`,
        type: "POST",
        dataType: "json",
        data: { id_solicitud: id, aprobador_actual: aprobador },
        success: function (res) {
          if (res.ok) {
            Swal.fire({
              icon: "success",
              title: "Enviado",
              text: res.msg || "Se notificó al siguiente aprobador.",
            });
            // refrescar DataTable sin perder paginación
            tbl_request.ajax.reload(null, false);
            // actualizar label de aprobador (por si sigue abierto el modal)
            if (res.aprobador_siguiente) {
              $("#lblAprobadorActual").text(res.aprobador_siguiente);
              $("#smi_aprobador_actual").val(res.aprobador_siguiente);
              $("#btnEnviarSiguiente").prop("disabled", true);
            } else {
              // flujo concluido
              $("#lblAprobadorActual").text("FINALIZADO");
              $("#btnEnviarSiguiente").prop("disabled", true);
              $("#btnFirmar").prop("disabled", true);
            }
          } else {
            Swal.fire({
              icon: "error",
              title: "Error",
              text: res.msg || "No fue posible avanzar.",
            });
          }
        },
        error: function () {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "Error de comunicación con el servidor.",
          });
        },
      });
    });
  });
})();
