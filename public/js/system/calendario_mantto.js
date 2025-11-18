var calendar; // 👈 ahora es global

$(document).ready(function () {
  // Configuración del Select2 - Versión corregida
 
 $('#input_usuario_equipo').on('keyup', function () {
    let termino = $(this).val();

    if (termino.length >= 2) {
      $.ajax({
        url: `${urls}/sistemas/usuarios_asignados`,
        method: 'POST',
        data: { search: termino },
        success: function (data) {
          let html = '';
          if (data.length === 0) {
            html = '<a href="#" class="list-group-item list-group-item-action disabled">Sin resultados</a>';
          } else {
            data.forEach(function (item) {
              html += `<a href="#" class="list-group-item list-group-item-action" 
                          data-id_equip="${item.id}" 
                          data-id="${item.id_user}" 
                          data-usuario="${item.text}" 
                          data-equipo="${item.label_equip}" 
                          data-departamento="${item.departamento}">
                          ${item.text}
                       </a>`;
            });
          }
          $('#resultados_busqueda').html(html).show();
        },
        error: function (xhr) {
          console.error("Error en la búsqueda:", xhr.responseText);
        }
      });
    } else {
      $('#resultados_busqueda').hide();
    }
  });

  // Selección de resultado
  $('#resultados_busqueda').on('click', 'a', function (e) {
    e.preventDefault();
    let nombre = $(this).data('usuario');
    let equipo = $(this).data('equipo');
    let id_user = $(this).data('id');
    let id_equip = $(this).data('id_equip');
    let departamento = $(this).data('departamento');

    $('#input_usuario_equipo').val(`${nombre}`);
    $('#hidden_id_user').val(id_user);
    $('#hidden_usuario').val(nombre);
    $('#hidden_equipo').val(equipo);
    $('#hidden_id_equip').val(id_equip);
    $('#hidden_departamento').val(departamento || 'Sin departamento');

    $('#resultados_busqueda').hide();
  });

  // Ocultar resultados si se hace clic fuera
  $(document).on('click', function (e) {
    if (!$(e.target).closest('#input_usuario_equipo, #resultados_busqueda').length) {
      $('#resultados_busqueda').hide();
    }
  });
 
 
  var calendarEl = document.getElementById("calendar");

  calendar = new FullCalendar.Calendar(calendarEl, {
    // locale: FullCalendar.globalLocales.find((loc) => loc.code === "es"),
    locale: "es",
    headerToolbar: {
      left: "prev,next today",
      center: "title",
      right: "dayGridMonth,timeGridWeek,timeGridDay",
    },
    buttonText: {
      today: "hoy",
      month: "mes",
      week: "semana",
      day: "día",
    },

    events: function (fetchInfo, successCallback, failureCallback) {
      $.ajax({
        url: `${urls}/sistemas/datos_mantenimiento`,
        method: "GET",
        data: {
          start: fetchInfo.startStr,
          end: fetchInfo.endStr,
        },
        success: function (data) {
          if (Array.isArray(data)) {
            const eventos = data.map((item) => {
              let color = "#007bff"; // default

              // Ajusta aquí los colores según los valores reales de tu base de datos
              switch (item.status_mantto) {
                case "1":
                  color = "#28a745"; // verde
                  estatus = "Preventivo";
                  break;
                case "2":
                  color = "#c99300"; // amarillo
                  estatus = "Correctivo";
                  break;
                case "3":
                  color = "#005fd3"; // azul
                  estatus = "Correctivo Especializado";
                  break;
                case "4":
                  color = "#dc3545"; // rojo
                  estatus = "Cancelado";
                  break;
              }

              return {
                title: item.pc_user + " - " + item.label_equip,
                tipo: estatus,
                start: item.fecha_mantto,
                backgroundColor: color,
                borderColor: color,
                extendedProps: {
                  id_mantto: item.id_mantto,
                  label_equip: item.label_equip,
                  model: item.model,
                  marca: item.marca,
                  mantto_obsv: item.mantto_obsv,
                  pc_user: item.pc_user,
                  departamento: item.departamento,
                  status_mantto: item.status_mantto,
                  obsv_cancelation: item.obsv_cancelation || "",
                  nombre_tecnico: item.nombre_tecnico || "",
                },
              };
            });
            successCallback(eventos);
          } else {
            failureCallback("Datos recibidos no son válidos");
          }
        },
        error: function () {
          failureCallback("Error al cargar eventos");
        },
      });
    },

    eventClick: function (info) {
      $("#hidden_id_mantto").val(info.event.extendedProps.id_mantto);
      $("#detalle_equipo").text(info.event.extendedProps.label_equip);
      $("#detalle_modelo").text(info.event.extendedProps.model);
      $("#detalle_marca").text(info.event.extendedProps.marca);
      $("#detalle_usuario").text(info.event.extendedProps.pc_user);
      $("#nombre_tecnicos").text(
        info.event.extendedProps.nombre_tecnico || "Sin técnico asignado"
      );
      $("#detalle_observaciones").text(
        info.event.extendedProps.mantto_obsv || "Sin observaciones"
      );
      $("#detalle_fecha").text(info.event.start.toLocaleDateString());

      const tipo = info.event.extendedProps.tipo || "Sin tipo";

      // Limpiar clases anteriores
      $("#detalle_tipo").removeClass(
        "text-success text-warning text-primary text-danger text-secondary"
      );

      // Establecer el texto
      $("#detalle_tipo").text(tipo);

      switch (tipo) {
        case "Preventivo":
          $("#detalle_tipo").addClass("text-success");
          break;
        case "Correctivo":
          $("#detalle_tipo").addClass("text-warning");
          break;
        case "Correctivo Especializado":
          $("#detalle_tipo").addClass("text-primary");
          break;
        case "Cancelado":
          $("#detalle_tipo").addClass("text-danger");
          break;
        default:
          $("#detalle_tipo").addClass("text-secondary"); // Clase por defecto
          break;
      }

      if (info.event.extendedProps.status_mantto === "4") {
        $("#infoCambio").hide();
        $("#infoCancelacion").show();
        $("#observacion_cancelar").text(
          info.event.extendedProps.obsv_cancelation || "Sin observaciones"
        );
      } else {
        $("#infoCambio").show();
        $("#infoCancelacion").hide();
      }

      $("#modalDetalleMantto").modal("show");
    },

    eventDidMount: function (info) {
      info.el.style.cursor = "pointer";
    },
  });

  calendar.render();

  $("#manttoForm").on("submit", function (e) {
    e.preventDefault();
    const data = {
      id_user: $("#hidden_id_user").val(),
      usuario_mantto: $("#hidden_usuario").val(),
      equipo_mantto: $("#hidden_equipo").val(),
      departamento_mantto: $("#hidden_departamento").val(),
      tipo_mantto: $("#tipo_mantto").val(),
      nombre_tecnico: $("#nombre_tecnico").val(),
      id_equipo_mantto: $("#hidden_id_equip").val(),
      fecha_mantto: $("#fecha_mantto").val(),
      observaciones: $("#observaciones").val(),
    };
    $.post("/sistemas/registrar_mantenimiento", data, function (response) {
      let res = typeof response === "string" ? JSON.parse(response) : response;
      if (res.success) {
        alert("Mantenimiento guardado");
        $("#manttoModal").modal("hide");
        calendar.refetchEvents();
        $("#manttoForm")[0].reset(); // Limpiar el formulario
        $("#resultados_busqueda").hide(); // Ocultar resultados de búsqueda
      } else {
        alert("Error al guardar");
      }
    });
  });
});

function Mantto() {
  $("#manttoModal").modal("show");
}

$("#usuario_mantto").on("select2:select", function (e) {
  const selectedId = e.params.data.id_user;
  const selectedUsuario = e.params.data.usuario;
  const selectedEquipo = e.params.data.equipo;
  const selectedDepartamento = e.params.data.departamento;

  console.log("ID seleccionado:", selectedId);
  console.log("Usuario seleccionado:", selectedUsuario);
  console.log("Equipo seleccionado:", selectedEquipo);
  console.log("Departamento seleccionado:", selectedDepartamento);

  // Puedes usarlo como desees, por ejemplo guardarlo en un input oculto:
  $("#hidden_id_user").val(selectedId);
  $("#hidden_usuario").val(selectedUsuario);
  $("#hidden_equipo").val(selectedEquipo);
  $("#hidden_depto").val(selectedDepartamento);
});

$("#cambiarManttoForm").submit(function (e) {
  e.preventDefault();
  const idMantto = $("#hidden_id_mantto").val();
  const data = {
    id_mantto: idMantto,
    tipo_mantto: $("#tipo_mantenimiento").val(),
    fecha_mantto: $("#nueva_fecha").val(),
    nombre_tecnico: $("#nombres_tecnico").val(),
  };

  $.post("/sistemas/cambiar_mantenimiento", data, function (response) {
    let res = typeof response === "string" ? JSON.parse(response) : response;

    console.log("log: " + res.success);
    if (res.success) {
      Swal.fire({
        title: "¡Éxito!",
        text: "El mantenimiento se actualizó correctamente.",
        icon: "success",
        confirmButtonText: "Aceptar",
      });
      $("#modalDetalleMantto").modal("hide");
      calendar.refetchEvents();
    } else {
      Swal.fire({
        title: "Error",
        text: "Error al actualizar",
        icon: "error",
        confirmButtonText: "Aceptar",
      });
    }
  });
});

$("#cancelarManttoForm").submit(function (e) {
  e.preventDefault();
  const idMantto = $("#hidden_id_mantto").val();
  const data = {
    id_mantto: idMantto,
    obsv_mantto: $("#observaciones_cancelar").val(),
  };

  $.post("/sistemas/cancelar_mantenimiento", data, function (response) {
    let res = typeof response === "string" ? JSON.parse(response) : response;

    console.log(res.success);
    if (res.success) {
      Swal.fire({
        title: "¡Éxito!",
        text: "Mantenimiento cancelado",
        icon: "success",
        confirmButtonText: "Aceptar",
      });
      $("#modalDetalleMantto").modal("hide");
      calendar.refetchEvents();
    } else {
      Swal.fire({
        title: "Error",
        text: "Error al cancelar",
        icon: "error",
        confirmButtonText: "Aceptar",
      });
    }
  });
});

function listadoMantto() {
  $("#listadoManttoModal").modal("show");

  tbl_mantto = $("#tbl_mantto")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}sistemas/listado_mantenimientos`,
        dataSrc: "",
      },
      lengthChange: true,
      ordering: true,
      responsive: true,
      autoWidth: false,
      rowId: "staffId",
      dom: "lfrtip",
      buttons: [
        {
          extend: "excelHtml5",
          title: "Historial de Mantenimientos",
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
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
          data: "id_mantto",
          title: "FOLIO",
          className: "text-center sie-size",
        },
        {
          data: "mes",
          title: "MES",
          className: "text-center sie-size",
        },

        {
          data: "numero_inventario",
          title: "ETIQUETA",
          className: "text-center sie-size",
        },
        {
          data: "pc_user",
          title: "USUARIO",
          className: "text-center sie-size",
        },
        {
          data: "departamento",
          title: "DEPARTAMENTO",
          className: "text-center sie-size",
        },
        {
          data: "fecha_programada",
          title: "FECHA",
          className: "text-center sie-size",
        },
        {
          data: "model",
          title: "MODELO",
          className: "text-center sie-size",
        },
        {
          data: "tecnico_asignado",
          title: "TÉCNICO",
          className: "text-center sie-size",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            switch (data.status_mantto) {
              case "1":
                color = "#28a745"; // verde
                estatus = "Preventivo";
                break;
              case "2":
                color = "#c99300"; // amarillo
                estatus = "Correctivo";
                break;
              case "3":
                color = "#005fd3"; // azul
                estatus = "Correctivo Especializado";
                break;
              case "4":
                color = "#dc3545"; // rojo
                estatus = "Cancelado";
                break;
            }

            return ` <div class="mr-auto">
                          <span class="text-center" style="color: ${color}"> <b> ${
              full.status_mantto === "1"
                ? "Preventivo"
                : full.status_mantto === "2"
                ? "Correctivo"
                : full.status_mantto === "3"
                ? "Correctivo Especializado"
                : "Cancelado"
            } </b></span>
                      </div> `;
          },
          title: "ESTATUS",
          className: "text-center sie-size",
        },
        {
          data: null,
          render: function (data, type, full, meta) {

            let ruta = (data.file_name) ? `${data.file_path}` : `${urls}sistemas/ver-mantenimiento/${$.md5(key + data["id_mantto"])}`;

            return ` <div class="d-flex justify-content-center btn-group-space">
                <button class="btn btn-outline-danger btn-sm" data-toggle="modal" data-target="#pdfModal" data-url="${ruta}" title="Ver PDF">
            <i class="fas fa-file-pdf"></i>
          </button>
          <button type="button" class="btn btn-outline-info btn-sm" onclick="subirPdfMantto(${data.id_mantto})" title="Subir Archivo">
            <i class="fas fa-upload"></i>
          </button>
          <button class="btn btn-outline-danger btn-sm" onclick="eliminarMantto(${data.id_mantto})">
              <i class="fas fa-trash"></i>
            </button>
                      </div> `;
          },
          title: "ACCIONES",
          className: "text-center sie-size",
        },
      ],
      destroy: "true",
      /* columnDefs: [
            {
                targets: [0],
                visible: false,
                searchable: false,
            },
        ], */
      order: [[0, "DESC"]],
      createdRow: (row, data) => {
        $(row).attr("id", "request_" + data.id_mantto);
      },
    })
    .DataTable();
  $("#tbl_mantto thead").addClass("thead-dark text-center");
}

$("#pdfModal").on("show.bs.modal", function (event) {
  $(this).find("#carga_pdf").attr("src", ""); // Limpiar el iframe
  var button = $(event.relatedTarget); // Botón que activó el modal
  var url = button.data("url"); // Extrae la URL del atributo data-url
  var modal = $(this);

  if (url) {
    modal.find("#carga_pdf").attr("src", url); // Inserta la URL en el iframe
  } else {
    console.error("URL no encontrada o inválida.");
  }
});

// Abrir el buscador automáticamente si quieres, al mostrar el modal
$("#manttoModal").on("shown.bs.modal", function () {
  $("#usuario_mantto").focus(); // opcional: abrir manualmente con .select2('open')
});

function subirPdfMantto(id_mantto) {
  
 // $("#subirPdfModal").modal("show");

  Swal.fire({
        title: 'Subir Solicitud de Mantenimiento',
        html: `
            <input type="file" id="swalInputPDF" class="swal2-file" accept=".pdf">
            <div class="mt-3">
                <small class="text-muted">Tamaño máximo: 5MB</small>
            </div>
        `,
        focusConfirm: false,
        preConfirm: () => {
            const fileInput = document.getElementById('swalInputPDF');
            if (fileInput.files.length === 0) {
                Swal.showValidationMessage('Debes seleccionar un archivo PDF');
                return false;
            }
            
            const file = fileInput.files[0];
            if (file.size > 5 * 1024 * 1024) { // 5MB
                Swal.showValidationMessage('El archivo excede el tamaño máximo de 5MB');
                return false;
            }
            
            if (file.type !== 'application/pdf') {
                Swal.showValidationMessage('Solo se permiten archivos PDF');
                return false;
            }
            
            return { file: file, id_mantto: id_mantto };
        }
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            subirPDF(result.value.file, result.value.id_mantto);
        }
    });
}



function subirPDF(file, id_mantto) {
    // Cambia los espacios por guiones bajos en el nombre del archivo
    const nuevoNombre = file.name.replace(/\s+/g, '_');
    const nuevoArchivo = new File([file], nuevoNombre, { type: file.type });

    const formData = new FormData();
    formData.append('pdf', nuevoArchivo);
    formData.append('id_mantto', id_mantto);
    
    Swal.fire({
        title: 'Subiendo archivo...',
        html: 'Por favor espera mientras se sube el PDF',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
            
            fetch(`${urls}sistemas/subir_pdf_mantenimiento`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'El PDF se ha subido correctamente',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                       tbl_mantto.ajax.reload();
                    });
                } else {
                    throw new Error(data.message || 'Error al subir el archivo');
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message,
                    confirmButtonText: 'Entendido'
                });
            });
        }
    });
}
function eliminarMantto(id_mantto) {
    Swal.fire({
        title: 'Eliminar Solicitud de Mantenimiento',
        text: "¿Estás seguro de que deseas eliminar esta solicitud?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`${urls}sistemas/eliminar_mantenimiento`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id_mantto: id_mantto })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Eliminado!',
                        text: 'La solicitud de mantenimiento ha sido eliminada.',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        tbl_mantto.ajax.reload();
                    });
                } else {
                    throw new Error(data.message || 'Error al eliminar la solicitud');
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message,
                    confirmButtonText: 'Entendido'
                });
            });
        }
    });
}
