/**
 * ARCHIVO MODULO HSE VOLUNTARIADOS
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:55-65-42-96-49
 */

$(document).ready(function () {
  tbl_solicitudes = $("#tabla_solicitudes_voluntarias").DataTable({
    processing: true,
    ajax: {
      method: "post",
      url: `${urls}qhse/listado_voluntariado`,
      dataSrc: "",
    },
    lengthChange: true,
    ordering: true,
    responsive: true,
    autoWidth: true,
    rowId: "staffId",
    dom: "lBfrtip",
    buttons: [
      {
        extend: "excelHtml5",
        title: "Voluntariado",
        exportOptions: {
          columns: [0, 1, 2, 3, 4],
        },
      },
    ],
    language: {
      url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
    },
    columns: [
      {
        data: null,
        title: "",
        className: "text-center",
      },
      {
        data: "id_volunteering",
        title: "FOLIO",
        className: "text-center",
      },
      {
        data: "user_name",
        title: "USUARIO",
      },
      {
        data: "tel_user",
        title: "TELEFONO",
        className: "text-center",
      },

      {
        data: "departament",
        title: "DEPTO",
        className: "text-center",
      },

      {
        data: null,
        render: function (data, type, full, meta) {
          let datos = data["activity"]; // Convierte el string a array
          return `<span class="badge badge-info">${datos}</span>`; // Devuelve el primer elemento
        },
        title: "EVENTO",
        className: "text-center",
      },
      {
        data: null,
        render: function (data, type, full, meta) {
          asistencia =
            data["assistance"] == "asistio"
              ? `<span class="badge badge-success">asistió</span>`
              : `<span class="badge badge-secondary">${data["assistance"]}</span>`;

          return asistencia;
        },

        title: "Asistencia",
        className: "text-center",
      },

      {
        data: null,
        render: function (data, type, full, meta) {
          switch (data["tipo_evento"]) {
            case "Acciones Verdes":
              return `<span class="badge badge-success">Acciones Verdes</span>`;
              break;
            case "Actividades Deportivas":
              return `<span class="badge badge-primary">Actividades Deportivas</span>`;
              break;
            case "Voluntariado":
              return `<span class="badge badge-danger">Voluntariado</span>`;
              break;

            default:
              return `<span class="badge badge-warning">Error</span>`;
              break;
          }
        },
        title: "TIPO",
        className: "text-center",
      },
      {
        data: null,
        title: "Acciones",
        className: "text-center",
      },
    ],
    destroy: "true",
    columnDefs: [
      {
        targets: 8,
        render: function (data, type, full, meta) {
          return `<div class=" mr-auto">
                      <button type="button" class="btn btn-outline-primary btn-sm" title="Autorizar Asistencia" onClick=handleChange(${
                        data["id_volunteering"]
                      })>
                          <i class="fas fa-user-check"></i>
                      </button>
                     <a href="javascript:void(0);" class="btn btn-outline-info btn-sm" data-toggle="modal" data-target="#pdfModal" data-url="${urls}qhse/ver-solicitudes/${$.md5(
            key + data["id_volunteering"]
          )}">
            <i class="fas fa-eye"></i>
          </a>
          <button type="button" class="btn btn-outline-danger btn-sm "  onClick=handleDeleteRequest(${
            data["id_volunteering"]
          })>
             <i class="fas fa-trash-alt"></i>
            </button>
                  </div> `;
        },
      },
      {
        targets: 0,
        render: function (data, type, row, meta) {
          if (type === "display") {
            data = `<div class="checkbox"><input type="checkbox" class="dt-checkboxes" style="cursor:pointer;" value="${data["id_volunteering"]}"><label></label></div>`;
          }

          return data;
        },
        checkboxes: {
          selectRow: true,
          selectAllRender:
            '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>',
        },
      },
      /* {
         targets: [0],
         visible: false,
         searchable: false,
       },   */
    ],

    order: [[1, "DESC"]],

    createdRow: (row, data) => {
      $(row).attr("id", "solicitud_" + data.id_volunteering);
    },
  });
  $("#tabla_solicitudes_voluntarias thead").addClass("thead-dark text-center");

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

  // tabla para permisos de vacaciones
  tbl_permission = $("#tabla_solicitudes_permanentes").DataTable({
    processing: true,
    ajax: {
      method: "post",
      url: `${urls}qhse/listado_permanente`,
      dataSrc: "",
    },
    lengthChange: true,
    ordering: true,
    responsive: true,
    autoWidth: true,
    rowId: "staffId",
    dom: "lBfrtip",
    buttons: [
      {
        extend: "excelHtml5",
        title: "Permisos",
        exportOptions: {
          columns: [1, 2, 3, 4, 5, 6, 0, 7],
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
        data: "id_volunteering",
        title: "FOLIO",
        className: "text-center",
      },
      {
        data: "user_name",
        title: "USUARIO",
      },
      {
        data: "tel_user",
        title: "TELEFONO",
        className: "text-center",
      },
      {
        data: "departament",
        title: "DEPTO",
        className: "text-center",
      },

      {
        data: null,
        render: function (data, type, full, meta) {
          switch (data["type_event"]) {
            case "1":
              return `<span class="badge badge-danger">Voluntario</span>`;
              break;
            case "2":
              return `<span class="badge badge-success">Permanente</span>`;
              break;

            default:
              return `<span class="badge badge-warning">Error</span>`;
              break;
          }
        },
        title: "TIPO",
        className: "text-center",
      },
      {
        data: null,
        title: "Acciones",
        className: "text-center",
      },
    ],
    destroy: "true",
    columnDefs: [
      {
        targets: 5,
        render: function (data, type, full, meta) {
          return `<div class=" mr-auto">
                        <button type="button" class="btn btn-outline-primary btn-sm" title="Autorizar Permiso">
                            <i class="fas fa-user-check"></i>
                        </button>
                        <a href="${urls}qhse/ver-permanente/${$.md5(
            key + data["id_volunteering"]
          )}" target="_blank" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div> `;
        },
      },
      /* {
           targets: [0],
           visible: false,
           searchable: false,
         },   */
    ],

    order: [[0, "DESC"]],

    createdRow: (row, data) => {
      $(row).attr("id", "voluntariado_" + data.id_volunteering);
    },
  });
  $("#tabla_solicitudes_permanentes thead").addClass("thead-dark text-center");

  // Evento click para enviar seleccionados
  $("#enviarSeleccionados").on("click", function () {
    let $btn = $(this); // ✅ Aquí defines $btn correctamente
    let idsSeleccionados = [];

    $btn.prop("disabled", true); // Desactiva el botón

    $("#tabla_solicitudes_voluntarias input.dt-checkboxes:checked").each(
      function () {
        idsSeleccionados.push($(this).val());
      }
    );

    // Validar si hay seleccionados
    if (idsSeleccionados.length === 0) {
      alert("Por favor selecciona al menos un voluntario.");
      $btn.prop("disabled", false); // reactiva si no hay nada seleccionado
      return;
    }

    console.log("Logs: ", idsSeleccionados);

    // Enviar vía AJAX
    $.ajax({
      url: `${urls}qhse/validar_solicitudes`,
      method: "POST",
      data: { folios: idsSeleccionados },
      success: function (response) {
        console.log("Respuesta del servidor:", response);
        tbl_permissions.ajax.reload();

        Swal.fire("!Se Registro la Asistencia!", "", "success");

        // alert("Folios enviados correctamente.");
        $btn.prop("disabled", false); // vuelve a activar el botón
      },
      error: function (xhr, status, error) {
        console.error("Error al enviar:", error);
        Swal.fire("Ocurrió un error al enviar los folios.", "", "success");
        tbl_permissions.ajax.reload(null, false);
        $btn.prop("disabled", false); // vuelve a activar el botón incluso si hay error
      },
    });
  });
});

function handleDeleteRequest(id_folio) {
  Swal.fire({
    title: `Deseas Eliminar la Solicitud con Folio: ${id_folio} ?`,
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
      dataForm.append("id_folio", id_folio);

      $.ajax({
        data: dataForm, //datos que se envian a traves de ajax
        url: `${urls}qhse/eliminar_solicitud`, //archivo que recibe la peticion
        type: "post", //método de envio
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentType
        success: function (response) {
          //una vez que el archivo recibe el request lo procesa y lo devuelve
          console.log(response);

          /*codigo que borra todos los campos del form newProvider*/
          if (response) {
            tbl_solicitudes.ajax.reload(null, false);
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

/**
 *
 * tabla de permisos
 */
function handleChange(id_folio) {
  let data = new FormData();
  data.append("id_folio", id_folio);
  $("#estatus").val("");
  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}qhse/asistencia`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    // async: true,
    dataType: "json",
    success: function (resp) {
      //console.log(resp);
      if (resp.success) {
        
        tbl_solicitudes.ajax.reload(null, false);
         
          Swal.fire("!El Permiso ha sido Actualizado!", "", "success");
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
        console.log("Mal Revisa");
      }
    },
  }).fail(function (jqXHR, textStatus, errorThrown) {
    if (jqXHR.status === 0) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Fallo de conexión: ​​Verifique la red.",
      });
    } else if (jqXHR.status == 404) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No se encontró la página solicitada [404]",
      });
    } else if (jqXHR.status == 500) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Internal Server Error [500]",
      });
    } else if (textStatus === "parsererror") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Error de análisis JSON solicitado.",
      });
    } else if (textStatus === "timeout") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Time out error.",
      });
    } else if (textStatus === "abort") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ajax request aborted.",
      });
    } else {
      alert("Uncaught Error: " + jqXHR.responseText);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: `Uncaught Error: ${jqXHR.responseText}`,
      });
    }
  });
}

$("#autorizar_permiso").submit(function (e) {
  e.preventDefault();
  let data = new FormData();
  let estatus = $("#estatus").val();

  $("#actualizar_permiso").prop("disabled", true);
  data.append("id_folio", $("#id_folio").val());
  data.append("estatus", estatus);

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}permisos/autorizar_permiso`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    success: function (response) {
      //console.log(response);
      $("#autorizarModal").modal("toggle");
      tbl_permission.ajax.reload(null, false);
      if (response == "true") {
        $("#actualizar_permiso").prop("disabled", false);
        Swal.fire("!El Permiso ha sido Actualizado!", "", "success");
      } else {
        $("#actualizar_permiso").prop("disabled", false);
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
    error: function (jqXHR, status, error) {
      $("#actualizar_permiso").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Algo salió Mal! Contactar con el Administrador",
      });
      console.log("Mal Revisa entro en el error: " + error);
    },
  }).fail(function (jqXHR, textStatus, errorThrown) {
    if (jqXHR.status === 0) {
      $("#actualizar_permiso").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Fallo de conexión: ​​Verifique la red.",
      });
    } else if (jqXHR.status == 404) {
      $("#actualizar_permiso").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No se encontró la página solicitada [404]",
      });
    } else if (jqXHR.status == 500) {
      $("#actualizar_permiso").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Internal Server Error [500]",
      });
    } else if (textStatus === "parsererror") {
      $("#actualizar_permiso").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Error de análisis JSON solicitado.",
      });
    } else if (textStatus === "timeout") {
      $("#actualizar_permiso").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Time out error.",
      });
    } else if (textStatus === "abort") {
      $("#actualizar_permiso").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ajax request aborted.",
      });
    } else {
      alert("Uncaught Error: " + jqXHR.responseText);
      $("#actualizar_permiso").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: `Uncaught Error: ${jqXHR.responseText}`,
      });
    }
  });
});
/**
 *
 * @param Tabla de Vacaciones
 */

function handleChangeVacation(id_folio) {
  $("#div_modal_a_cargo").hide();
  $("#div_btn").empty();
  let data = new FormData();
  data.append("id_folio", id_folio);

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}permisos/editar_vacaciones`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    async: true,
    dataType: "json",
    success: function (resp) {
      if (resp != false) {
        $("#id_folio_vacaciones").val(id_folio);
        $("#usuario_vacaciones").val(resp.nombre_solicitante);
        $("#vacaciones_del").val(resp.dias_a_disfrutar_del);
        $("#vacaciones_al").val(resp.dias_a_disfrutar_al);
        $("#regresando").val(resp.regreso);
        $("#dias").val(resp.num_dias_a_disfrutar);
        $("#num_nomina").val(resp.num_nomina);
        if (resp.a_cargo.length > 12) {
          $("#div_modal_a_cargo").show();
          $("#modal_a_cargo").val(resp.a_cargo);
        }
        action =
          id_folio > 8694 ? `onclick="verFechas(${id_folio})"` : "disabled";
        color = id_folio > 8694 ? `outline-primary` : "secondary";
        $("#div_btn").append(`
            <button type="button" class="btn btn-${color}" ${action} style="margin-top: 1rem;">
              <i class="fas fa-calendar-day" style="margin-right: 10px;"></i>Ver dias de Vacaciones
            </button>`);
        $("#autorizarVacacionesModal").modal("show");
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
    } else if (jqXHR.status == 404) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No se encontró la página solicitada [404]",
      });
    } else if (jqXHR.status == 500) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Internal Server Error [500]",
      });
    } else if (textStatus === "parsererror") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Error de análisis JSON solicitado.",
      });
    } else if (textStatus === "timeout") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Time out error.",
      });
    } else if (textStatus === "abort") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ajax request aborted.",
      });
    } else {
      alert("Uncaught Error: " + jqXHR.responseText);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: `Uncaught Error: ${jqXHR.responseText}`,
      });
    }
  });
}

$("#autorizar_vacaciones").submit(function (e) {
  e.preventDefault();
  let data = new FormData();
  let estatus = $("#estatus_vacaciones").val();
  let dias = $("#dias").val();
  let num_nomina = $("#num_nomina").val();
  let id_folio = $("#id_folio_vacaciones").val();
  data.append("id_folio", id_folio);
  data.append("estatus", estatus);
  data.append("dias", dias);
  data.append("num_nomina", num_nomina);
  $("#actualizar_vacaciones").prop("disabled", true);
  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}permisos/autoriza-vacaciones`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    success: function (response) {
      //una vez que el archivo recibe el request lo procesa y lo devuelve
      //console.log(response);
      $("#autorizarVacacionesModal").modal("toggle");
      /*codigo que borra todos los campos del form newProvider*/
      if (response != false) {
        Swal.fire(
          "!El Permiso de Vacaciones ha sido Actualizado !",
          "",
          "success"
        );
        $("#actualizar_vacaciones").prop("disabled", false);
        tbl_vacations.ajax.reload(null, false);
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
    error: function (jqXHR, status, error) {
      $("#actualizar_vacaciones").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Algo salió Mal! Contactar con el Administrador",
      });
      console.log("Mal Revisa entro en el error: " + error);
    },
  }).fail(function (jqXHR, textStatus, errorThrown) {
    if (jqXHR.status === 0) {
      $("#actualizar_vacaciones").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Fallo de conexión: ​​Verifique la red.",
      });
    } else if (jqXHR.status == 404) {
      $("#actualizar_vacaciones").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No se encontró la página solicitada [404]",
      });
    } else if (jqXHR.status == 500) {
      $("#actualizar_vacaciones").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Internal Server Error [500]",
      });
    } else if (textStatus === "parsererror") {
      $("#actualizar_vacaciones").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Error de análisis JSON solicitado.",
      });
    } else if (textStatus === "timeout") {
      $("#actualizar_vacaciones").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Time out error.",
      });
    } else if (textStatus === "abort") {
      $("#actualizar_vacaciones").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ajax request aborted.",
      });
    } else {
      $("#actualizar_vacaciones").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: `Uncaught Error: ${jqXHR.responseText}`,
      });
    }
  });
});

function verFechas(id_folio) {
  $("#div_dias").empty();
  let data = new FormData();
  data.append("id_folio", id_folio);
  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}permisos/dias_vacaciones`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    async: true,
    dataType: "json",
    success: function (resp) {
      if (resp) {
        var i = 0;
        resp.forEach((r) => {
          var styl = i == 0 ? "" : "margin-top: 10px;";
          $("#div_dias").append(`<div class="row" style="${styl}">
              <input type="date" class="form-control" style="text-align: center;" value="${r.date_vacation}" readonly>
            </div>`);
          i++;
        });
        $("#fechasVacacionesModal").modal("show");
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
    } else if (jqXHR.status == 404) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No se encontró la página solicitada [404]",
      });
    } else if (jqXHR.status == 500) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Internal Server Error [500]",
      });
    } else if (textStatus === "parsererror") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Error de análisis JSON solicitado.",
      });
    } else if (textStatus === "timeout") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Time out error.",
      });
    } else if (textStatus === "abort") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ajax request aborted.",
      });
    } else {
      alert("Uncaught Error: " + jqXHR.responseText);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: `Uncaught Error: ${jqXHR.responseText}`,
      });
    }
  });
}
