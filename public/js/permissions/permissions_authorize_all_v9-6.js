/**
 * ARCHIVO MODULO PERMISSIONS
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */

// const colorPermiss = { 1: '#2B43C6', 2: '#7DCD67', 3: '#F2A100', 4: '#CB6BD3', 5: '#3FC3EE', null: '#BDBDBD' };
const colorStatus = {
  Rechazada: "danger",
  Autorizada: "success",
  Cancelada: "cancel",
  Pendiente: "warning",
};

const estado = { 1: "PENDIENTE", 2: "AUTORIZADA", 3: "CANCELADA" };
const usado = { 1: "DISPONIBLE", 2: "USADO", 3: "DEUDA", 4: "NUEVO" };
const color_estado = { 1: "warning", 2: "success", 3: "danger" };
const color_usado = { 1: "53F3F3", 2: "7FF59A", 3: "FEAF39", 4: "D3D3D3" };
const tipo_pago = {
  1: "LLEGAR ANTES",
  2: "QUEDARSE DESPUES",
  3: "TURNO COMPLETO",
};

const inputVacaciones = flatpickr("#vacaciones_dias_disfrutar", {
  locale: "es",
  mode: "multiple",
  dateFormat: "Y-m-d",
  // Configuración de flatpickr con las fechas mínima y máxim
  onChange: function (selectedDates, dateStr, instance) {
    selectedDays = selectedDates.length;
    $("#count_array").val(selectedDays);
  },
});

const inputRegreso = flatpickr("#vacaciones_regresar_actividades", {
  locale: "es",
  dateFormat: "d/m/Y",
  // Configuración de flatpickr con las fechas mínima y máxim
});

$(document).ready(function () {
  pintarHorarios();

  /* tabla para Permisos */
  tbl_permissions = $("#tabla_autorizar_todos_permisos")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: urls + "permisos/entrada-salida",
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
            columns: [0, 1, 2, 3, 4, 5, 6],
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
          data: "id_es",
          title: "FOLIO",
          className: "text-center",
        },
        {
          data: "nombre_solicitante",
          title: "USUARIO",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            let fecha_salida = moment(data["fecha_salida"]).format(
              "YYYY-MM-DD"
            );
            const hrSalida = data["hora_salida"];
            return $.trim(data["fecha_salida"]) === "0000-00-00"
              ? "---"
              : ` <div class="mr-auto">${fecha_salida} - ${hrSalida} </div> `;
          },
          title: "SALIDA",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            let fecha_entrada = moment(data["fecha_entrada"]).format(
              "YYYY-MM-DD"
            );
            const hrEntrada = data["hora_entrada"];
            return $.trim(data["fecha_entrada"]) === "0000-00-00"
              ? "---"
              : ` <div class="mr-auto"> ${fecha_entrada}- ${hrEntrada} </div> `;
          },
          title: "ENTRADA",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            return $.trim(data["inasistencia_del"]) == "0000-00-00"
              ? "---"
              : ` <div class="mr-auto">Del: ${data["inasistencia_del"]} </br> Al: ${data["inasistencia_al"]} </div> `;
          },
          title: "INASISTENCIA",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            return `<span class="badge" style="color:#fff;background-color:${data["colorPermiss"]};">${data["tipo_permiso"]}</span>`;
          },
          title: "TIPO PERMISO",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            return `<span class="badge badge-${colorStatus[data["estatus"]]}">${
              data["estatus"]
            }</span>`;
          },
          title: "ESTATUS",
          className: "text-center",
        },
        {
          data: "authoriza",
          title: "AUTORIZADOR",
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
            return ` <div class="pull-right mr-auto">
            <div class="btn-group" role="group">
            <button id="btnGroupDropPermisos" type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-user-check"></i>
            </button>
              <div class="dropdown-menu" aria-labelledby="btnGroupDropPermisos">
                <a class="dropdown-item" style="cursor:pointer;" onClick="handleAuthorize(${
                  data["id_es"]
                })">Autorizar</a>
                <a class="dropdown-item" style="cursor:pointer;" onClick="handleEdit(${
                  data["id_es"]
                })">Editar</a>
              </div>
            </div>
          
          <a href="javascript:void(0);" class="btn btn-outline-info btn-sm" data-toggle="modal" data-target="#pdfModal" data-url="${urls}permisos/ver-permisos/${$.md5(
              key + data["id_es"])}">
            <i class="fas fa-eye"></i>
          </a>


            <button type="button" class="btn btn-outline-danger btn-sm "  onClick=handleDeletePermissions(${
              data["id_es"]
            })>
             <i class="fas fa-trash-alt"></i>
            </button>
            </div> `;
          },
        },
        /*  {
           <a href="${urls}permisos/ver-permisos/${$.md5(key + data["id_es"])}" target="_blank" class="btn btn-outline-info btn-sm">
              <i class="fas fa-eye"></i>
            </a> 
        targets: [0],
        visible: false,
        searchable: false,
      }, */
      ],

      order: [[0, "DESC"]],

      createdRow: (row, data) => {
        $(row).attr("id", "permissions_" + data.id_es);
      },
    })
    .DataTable();
  $("#tabla_autorizar_todos_permisos thead").addClass("thead-dark text-center");

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

  /*tabla para permisos de vacaciones */
  tbl_vacations = $("#tabla_autorizar_todos_vacaciones")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: urls + "permisos/vacaciones-todos",
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
          title: "Vacaciones",
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6],
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
          data: "id_vcns",
          title: "FOLIO",
          className: "text-center",
        },
        {
          data: "fecha_registro",
          title: "CREACIÓN",
          className: "text-center",
        },
        {
          data: "nombre_solicitante",
          title: "USUARIO",
        },
        {
          data: "num_dias_a_disfrutar",
          title: "DIAS",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            let fecha_salida = moment(data["dias_a_disfrutar_del"]).format(
              "DD-MM-YYYY"
            );
            return $.trim(data["dias_a_disfrutar_del"]) === "0000-00-00"
              ? "---"
              : ` <div class="mr-auto"> ${fecha_salida} </div> `;
          },
          title: "DEL",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            let fecha_entrada = moment(data["dias_a_disfrutar_al"]).format(
              "DD-MM-YYYY"
            );
            return $.trim(data["dias_a_disfrutar_al"]) === "0000-00-00"
              ? "---"
              : ` <div class="mr-auto"> ${fecha_entrada} </div> `;
          },
          title: "AL",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            action =
              data["id_vcns"] > 8694
                ? `onclick="verFechas(${data["id_vcns"]})"`
                : "disabled";
            color = data["id_vcns"] > 8694 ? `outline-primary` : "secondary";
            return `<button class=" btn btn-${color}" ${action}>
            <i class="fas fa-calendar-day"></i>
            </button>
            `;
          },
          title: "DIAS",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            let fecha_entrada = moment(data["regreso"]).format("DD-MM-YYYY");
            return $.trim(data["regreso"]) === "0000-00-00"
              ? "---"
              : ` <div class="mr-auto"> ${fecha_entrada} </div> `;
          },
          title: "REGRESA",
          className: "text-center",
        },

        {
          data: null,
          render: function (data, type, full, meta) {
            switch (data["estatus"]) {
              case "Rechazada":
                return `<span class="badge badge-danger">${data["estatus"]}</span>`;
                break;
              case "Autorizada":
                return `<span class="badge badge-success">${data["estatus"]}</span>`;
                break;
              case "Cancelada":
                return `<span class="badge" style="color:#fff;background-color:#f76a77;">${data["estatus"]}</span>`;
                break;
              default:
                return `<span class="badge badge-warning">${data["estatus"]}</span>`;
                break;
            }
          },
          title: "ESTATUS",
          className: "text-center",
        },
        {
          data: "authoriza",
          title: "AUTORIZADOR",
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
          targets: 10,
          render: function (data, type, full, meta) {
            var modal =
              data["id_vcns"] < 8695 ? "handleVacation" : "handleVacationNew";
            var estado2 =
              data["active_status"] == 1
                ? `
              <div class="btn-group" role="group">
            <button id="btnGroupDropPermisos" type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user-check"></i>
          </button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDropPermisos">
              <a class="dropdown-item" style="cursor:pointer;" onClick="handleAuthorizeVacation(${data["id_vcns"]})">Autorizar</a>
              <a class="dropdown-item" style="cursor:pointer;" onClick="${modal}(${data["id_vcns"]})">Editar</a>
            </div>
          </div>`
                : `
            <button type="button" class="btn btn-outline-secondary btn-sm " >
                  <i class="fas fa-user-check"></i>
            </button>`;
            return ` <div class="pull-right mr-auto">
            ${estado2}
          
               <a href="javascript:void(0);" class="btn btn-outline-info btn-sm" data-toggle="modal" data-target="#pdfVacacionesModal" data-url="${urls}permisos/vacaciones/${$.md5(
                key + data["id_vcns"]
              )}">
            <i class="fas fa-eye"></i>
          </a>
            <button type="button" class="btn btn-outline-danger btn-sm "  onClick=handleDeleteVacations(${
              data["id_vcns"]
            })>
              <i class="fas fa-trash-alt"></i>
            </button>
          </div> `;
          },
        },
        /* {
        targets: [0],
        visible: false,
        searchable: false,
      }, */
      ],

      order: [[0, "DESC"]],

      createdRow: (row, data) => {
        $(row).attr("id", "vacation_" + data.id_vcns);
      },
    })
    .DataTable();
  $("#tabla_autorizar_todos_vacaciones thead").addClass(
    "thead-dark text-center"
  );

  $("#pdfVacacionesModal").on("show.bs.modal", function (event) {
    $(this).find("#carga_pdf_vacaciones").attr("src", ""); // Limpiar el iframe
    var button = $(event.relatedTarget); // Botón que activó el modal
    var url = button.data("url"); // Extrae la URL del atributo data-url
    var modal = $(this);

    if (url) {
      modal.find("#carga_pdf_vacaciones").attr("src", url); // Inserta la URL en el iframe
    } else {
      console.error("URL no encontrada o inválida.");
    }
  });



  /* tabla Pago de Tiempo */
  tbl_time_pay = $("#tbl_pago_tiempo")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}permisos/datos_pago_tiempo_todos`,
        dataSrc: "",
      },
      lengthChange: true,
      ordering: true,
      responsive: true,
      autoWidth: true,
      rowId: "staffId",
      // dom: "lBfrtip",
      buttons: [
        /* {
        extend: "excelHtml5",
        title: "Permisos",
        exportOptions: {
          columns: [1, 2, 3, 4, 5, 6, 0, 7],
        },
      }, */
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
          data: "id_item",
          title: "FOLIO",
          className: "text-center",
        },
        {
          data: "nombre",
          title: "USUARIO",
          className: "text-center",
        },
        {
          data: "depto",
          title: "DEPARTAMENTO",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            return tipo_pago[data["type_pay"]];
          },
          title: "TIPO",
          className: "text-center",
        },
        {
          data: "day_to_pay",
          title: "DIA",
          className: "text-center",
        },
        {
          data: "time_pay",
          title: "TIEMPO A PAGAR",
          className: "text-center",
        },
        {
          data: "check_clock",
          title: "HORA DE CHECADA",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            return `<span class="badge" style="background-color:#${
              color_usado[data["estado"]]
            };">${usado[data["estado"]]}</span>`;
          },
          title: "ESTADO PAGO",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            return `<span class="badge badge-${
              color_estado[data["status_autorize"]]
            }">${estado[data["status_autorize"]]}</span>`;
          },
          title: "ESTATUS",
          className: "text-center",
        },
        {
          data: "authorize",
          title: "AUTORIZADOR",
          className: "text-center",
        },
        {
          data: null,
          title: "ACCIONES",
          className: "text-center",
        },
      ],
      destroy: "true",
      columnDefs: [
        {
          targets: 10,
          render: function (data, type, full, meta) {
            return `<div class=" mr-auto">
            <button type="button" class="btn btn-outline-info btn-sm" title="Editar Pago Tiempo" onclick="editItem(${data["id_item"]})">
              <i class="fas fa-edit"></i>
            </button>  
            <button type="button" class="btn btn-outline-primary btn-sm" title="Autorizar Pago Tiempo" onclick="statusChange(${data["id_request"]},${data["id_item"]})">
              <i class="fas fa-clipboard-check"></i>
            </button>
            <button type="button" class="btn btn-outline-danger btn-sm" title="Eliminar Pago Tiempo" onclick="deleteChange(${data["id_item"]})">
              <i class="fas fa-trash-alt"></i>
            </button>
            </div> `;
          },
        },
        // {
        //  targets: [0],
        //  visible: false,
        //  searchable: false,
        //  },
      ],
      order: [[0, "DESC"]],
      createdRow: (row, data) => {
        $(row).attr("id", "permissions_" + data.id_es);
      },
    })
    .DataTable();
  $("#tbl_pago_tiempo thead").addClass("thead-dark text-center");
});

function handleAuthorize(id_folio) {
  let data = new FormData();

  data.append("id_folio", id_folio);

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}permisos/editar_permiso`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    async: true,
    dataType: "json",
    success: function (resp) {
      //console.log(resp);
      if (resp != "error") {
        resp.forEach(function (permiso, index) {
          $("#permiso_salida").val();
          $("#permiso_entrada").val();
          $("#folio").val(permiso.id_es);
          $("#usuario").val(permiso.nombre_solicitante);
          $("#observaciones").val(permiso.observaciones);
          if (
            permiso.hora_entrada == "00:00:00" &&
            permiso.hora_salida == "00:00:00"
          ) {
            $("#permiso_salida").val("----");
            $("#permiso_entrada").val("----");
          }
          if (permiso.hora_salida != "00:00:00") {
            $("#permiso_entrada").val("---");
            $("#permiso_salida").val(
              `${permiso.fecha_salida} - ${permiso.hora_salida}`
            );
            $("#permiso_inasistencia").val("----");
          }

          if (permiso.hora_entrada != "00:00:00") {
            $("#permiso_salida").val("---");
            $("#permiso_entrada").val(
              `${permiso.fecha_entrada} - ${permiso.hora_entrada}`
            );
            $("#permiso_inasistencia").val("----");
          }

          if (
            permiso.inasistencia_del != "0000-00-00" &&
            permiso.inasistencia_al != "0000-00-00"
          ) {
            $("#permiso_inasistencia").val(
              `del:  ${permiso.inasistencia_del}  al:  ${permiso.inasistencia_al}`
            );
          }
        });

        $("#permisosModal").modal("show");
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

function handleEdit(a) {
  console.log("edit");
  let e = new FormData();
  e.append("id_folio", a),
    $.ajax({
      data: e,
      url: urls + "permisos/editar_permiso",
      type: "post",
      processData: !1,
      contentType: !1,
      async: !0,
      dataType: "json",
      success: function (a) {
        console.log(a);
        if (a != "error") {
          a.forEach(function (a, e) {
            hora_salida = a.hora_salida != "00:00:00" ? a.hora_salida : "";
            hora_entrada = a.hora_entrada != "00:00:00" ? a.hora_entrada : "";
            $("#editar_folio").val(a.id_es);
            $("#editar_usuario").val(a.nombre_solicitante);
            $("#editar_observaciones").val(a.observaciones);
            $("#editar_permiso_salida").val(a.fecha_salida);
            $("#editar_permiso_salida_h").val(hora_salida);
            $("#editar_permiso_entrada").val(a.fecha_entrada);
            $("#editar_permiso_entrada_h").val(hora_entrada);
            $("#editar_inasistencia_del").val(a.inasistencia_del);
            $("#editar_inasistencia_al").val(a.inasistencia_al);
          }),
            $("#permisosEditarModal").modal("show");
        } else {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Algo salió Mal! Contactar con el Administrador",
          }),
            console.log("Mal Revisa");
        }
      },
    }).fail(function (a, e, t) {
      0 === a.status
        ? (Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Fallo de conexión: ​​Verifique la red.",
          }),
          $("#guardar_ticket").prop("disabled", !1))
        : 404 == a.status
        ? (Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "No se encontró la página solicitada [404]",
          }),
          $("#guardar_ticket").prop("disabled", !1))
        : 500 == a.status
        ? (Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Internal Server Error [500]",
          }),
          $("#guardar_ticket").prop("disabled", !1))
        : "parsererror" === e
        ? (Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Error de análisis JSON solicitado.",
          }),
          $("#guardar_ticket").prop("disabled", !1))
        : "timeout" === e
        ? (Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Time out error.",
          }),
          $("#guardar_ticket").prop("disabled", !1))
        : "abort" === e
        ? (Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Ajax request aborted.",
          }),
          $("#guardar_ticket").prop("disabled", !1))
        : (alert("Uncaught Error: " + a.responseText),
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: `Uncaught Error: ${a.responseText}`,
          }),
          $("#guardar_ticket").prop("disabled", !1));
    });
}

/*Generar ticket it */

$("#autorizar_permisos").submit(function (event) {
  event.preventDefault();
  $("#autoriza_permiso").prop("disabled", true);

  let data = new FormData();
  let autorizacion = $("#autorizacion").val();
  data.append("id_folio", $("#folio").val());
  data.append("autorizacion", autorizacion);
  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: urls + "permisos/autorizacion", //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    dataType: "json",
    success: function (response) {
      $("#autoriza_permiso").prop("disabled", false);
      //una vez que el archivo recibe el request lo procesa y lo devuelve
      console.log(response);
      $("#permisosModal").modal("toggle");
      /*codigo que borra todos los campos del form newProvider*/
      if (response == true) {
        autorizacion === "Autorizada"
          ? Swal.fire("!El permiso ha sido Autorizado!", "", "success")
          : Swal.fire("!El permiso a sido Rechazado!", "", "success");
        resetTablas();
        $("#autoriza_permiso").prop("disabled", false);
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
});

$("#editar_permisos").submit(function (e) {
  e.preventDefault();
  $("#editar_permiso").prop("disabled", true);
  let a = new FormData($("#editar_permisos")[0]);
  $.ajax({
    data: a, //datos que se envian a traves de ajax
    url: `${urls}permisos/guardar_editar`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    async: true,
    dataType: "json",
    success: function (resp) {
      $("#editar_permiso").prop("disabled", false);
      if (resp == true) {
        Swal.fire("!El Permiso ha sido Actualizado!", "", "success");
        resetTablas();
        $("#permisosEditarModal").modal("toggle");
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
    $("#editar_permiso").prop("disabled", false);
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
});

function handleAuthorizeVacation(id_folio) {
  $("#div_btn").empty();
  $("#div_modal_a_cargo").hide();
  let data = new FormData();
  data.append("id_folio", id_folio);
  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}permisos/editar_permiso_vacations/3`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    async: true,
    dataType: "json",
    success: function (resp) {
      console.log(resp);
      if (resp != "error") {
        $("#del_vacaciones").val(resp.dias_a_disfrutar_del);
        $("#al_vacaciones").val(resp.dias_a_disfrutar_al);
        $("#folio_vacaciones").val(resp.id_vcns);
        $("#usuario_vacaciones").val(resp.nombre_solicitante);
        $("#regresa").val(resp.regreso);
        $("#dias").val(resp.num_dias_a_disfrutar);
        $("#num_nomina").val(resp.num_nomina);
        if (parseInt(resp.id_a_cargo) != 0) {
          $("#div_modal_a_cargo").show();
          $("#modal_a_cargo").val(resp.a_cargo);
        }
        action =
          id_folio > 8694 ? `onclick="verFechas(${resp.id_vcns})"` : "disabled";
        color = id_folio > 8694 ? `outline-primary` : "secondary";
        $("#div_btn").append(`
          <button type="button" class="btn btn-${color}" ${action} style="margin-top: 1rem;">
            <i class="fas fa-calendar-day" style="margin-right: 10px;"></i>Ver dias de Vacaciones
          </button>`);

        $("#vacacionesModal").modal("show");
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

function handleVacation(id_folio) {
  console.log("vacaciones editar");
  let data = new FormData();
  data.append("id_folio", id_folio);
  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}permisos/editar_permiso_vacations/1`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    async: true,
    dataType: "json",
    success: function (resp) {
      console.log(resp);
      if (resp != "error") {
        resp.forEach(function (permiso, index) {
          $("#editar_vacaciones_del").val(permiso.dias_a_disfrutar_del);
          $("#editar_vacaciones_al").val(permiso.dias_a_disfrutar_al);
          $("#editar_folio_vcns").val(permiso.id_vcns);
          $("#editar_usuario_vcns").val(permiso.nombre_solicitante);
          $("#editar_regresando").val(permiso.regreso);
          $("#editar_catidad").val(permiso.num_dias_a_disfrutar);
        });
        $("#editarVacacionesModal").modal("show");
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

function handleVacationNew(id_folio) {
  console.log("vacaciones editar");
  let data = new FormData();
  data.append("id_folio", id_folio);
  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}permisos/editar_permiso_vacations/2`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    async: true,
    dataType: "json",
    success: function (resp) {
      if (resp != "error") {
        $("#editar_vcns_new").val(resp.id_vcns);
        $("#id_user_new").val(resp.id_user);
        $("#id_depto_new").val(resp.id_depto);
        $("#editar_usuario_vcns_new").val(resp.nombre_solicitante);
        $("#editar_catidad_new").val(resp.num_dias_a_disfrutar);
        var fechas_array = resp.concatenado.split(",");
        $("#id_items_new").val(resp.items);
        inputVacaciones.setDate(fechas_array);
        inputRegreso.setDate(resp.regreso);
        $("#editarVacacionesNewModal").modal("show");
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

$("#editar_vacaciones").submit(function (e) {
  e.preventDefault();
  $("#actualiza_vacaciones").prop("disabled", true);
  let a = new FormData($("#editar_vacaciones")[0]);
  $.ajax({
    data: a, //datos que se envian a traves de ajax
    url: `${urls}permisos/guardar_editar_vacaciones`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    async: true,
    dataType: "json",
    success: function (resp) {
      $("#actualiza_vacaciones").prop("disabled", false);
      if (resp == true) {
        Swal.fire(
          "!El permiso de Vacaciones ha sido Actualizado!",
          "",
          "success"
        );
        resetTablas();
        $("#editarVacacionesModal").modal("toggle");
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
    $("#actualiza_vacaciones").prop("disabled", false);
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
});

$("#autorizar_vacaciones").submit(function (event) {
  event.preventDefault();
  $("#autoriza_vacaciones").prop("disabled", true);

  let data = new FormData();
  let dias = $("#dias").val();
  let num_nomina = $("#num_nomina").val();
  let autorizacion = $("#autorizacion_vacaciones").val();
  console.log(num_nomina);
  console.log(dias);
  data.append("id_folio", $("#folio_vacaciones").val());
  data.append("autorizacion", autorizacion);
  data.append("dias", dias);
  data.append("num_nomina", num_nomina);
  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: urls + "permisos/autorizacion_vacaciones", //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    success: function (response) {
      //una vez que el archivo recibe el request lo procesa y lo devuelve
      console.log(response);
      $("#vacacionesModal").modal("toggle");
      /*codigo que borra todos los campos del form newProvider*/
      if (response != "error") {
        autorizacion === "Autorizada"
          ? Swal.fire("!El permiso ha sido Autorizado!", "", "success")
          : Swal.fire("!El permiso a sido Rechazado!", "", "success");
        resetTablas();
        $("#autoriza_vacaciones").prop("disabled", false);
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
});

function handleDeletePermissions(id_folio) {
  Swal.fire({
    title: `Deseas Eliminar el Permiso con Folio: ${id_folio} ?`,
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
        url: `${urls}permisos/eliminar_permisos`, //archivo que recibe la peticion
        type: "post", //método de envio
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentType
        success: function (response) {
          //una vez que el archivo recibe el request lo procesa y lo devuelve
          console.log(response);

          /*codigo que borra todos los campos del form newProvider*/
          if (response) {
            resetTablas();
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

function handleDeleteVacations(id_folio) {
  Swal.fire({
    title: `Deseas Eliminar el Permiso con Folio: ${id_folio} ?`,
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
        url: `${urls}permisos/eliminar_vacaciones`, //archivo que recibe la peticion
        type: "post", //método de envio
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentType
        success: function (response) {
          //una vez que el archivo recibe el request lo procesa y lo devuelve
          console.log(response);

          /*codigo que borra todos los campos del form newProvider*/
          if (response) {
            resetTablas();
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

function testAnim(e) {
  $(".modal .vacaciones").attr("class", `modal-dialog modal-xl ${e} animated`);
}

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

$("#vacacionesModal").on("show.bs.modal", function (e) {
  $("html, body").css("overflow", "hidden");
  var anim = "bounceInRight";
  testAnim(anim);
});

$("#vacacionesModal").on("hide.bs.modal", function (e) {
  $("html, body").css("overflow", "scroll");
  var anim = "slideOutLeft";
  testAnim(anim);
});

function testAnimPermisos(e) {
  $(".modal .autoriza-permisos").attr(
    "class",
    `modal-dialog modal-xl ${e} animated`
  );
}

$("#permisosModal").on("show.bs.modal", function (e) {
  var anim = "bounceInRight";
  testAnimPermisos(anim);
});

$("#permisosModal").on("hide.bs.modal", function (e) {
  var anim = "slideOutLeft";
  testAnimPermisos(anim);
});

$("#editar_vacaciones_new").submit(function (e) {
  e.preventDefault();
  const data = new FormData($("#editar_vacaciones_new")[0]);
  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}permisos/actualizar_dias_new`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    async: true,
    dataType: "json",
    success: function (save) {
      if (save === true) {
        resetTablas();

        Swal.fire({
          icon: "success",
          title: "Exito",
          text: "Actualización de datos Exitosa",
        });
        $("#editarVacacionesNewModal").modal("hide");
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
});

function statusChange(id_rquest, item) {
  Swal.fire({
    title:
      '<i class="far fa-clock" style="margin-right: 10px;"></i>¿Confirmar Pago de Tiempo?',
    showDenyButton: true,
    showCancelButton: true,
    confirmButtonText:
      '<i class="fas fa-check" style="margin-right: 10px;"></i>Confirmar',
    confirmButtonColor: "#28A745",
    denyButtonText: `<i class="fas fa-times" style="margin-right: 10px;"></i>Rechazar`,
  }).then((result) => {
    if (result.isConfirmed) {
      estadoContrato(2, id_rquest, item, 1);
    } else if (result.isDenied) {
      estadoContrato(3, id_rquest, item, 1);
    }
  });
}

function deleteChange(item) {
  Swal.fire({
    title:
      '<i class="fas fa-trash-alt" style="margin-right: 10px;"></i>¿Eliminar Pago de Tiempo?',
    confirmButtonText:
      '<i class="fas fa-check" style="margin-right: 10px;"></i>Eliminar',
    confirmButtonColor: "#28A745",
    showCancelButton: true,
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      let timerInterval = Swal.fire({
        //se le asigna un nombre al swal
        title:
          '<i class="far fa-save" style="margin-right: 10px;"></i>¡Eliminando Registro!',
        html: "Espere unos Segundos.",
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading(); //remplaza boton "OK" por animacion de "circulo Cargando"
        },
      });
      var statusContract = new FormData();
      statusContract.append("id_item", item);
      console.log(statusContract);
      $.ajax({
        type: "post",
        url: `${urls}permisos/eliminar_pago_tiempo`,
        data: statusContract,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (save) {
          console.log(save);
          Swal.close(timerInterval);
          if (save.hasOwnProperty("xdebug_message")) {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Algo salió Mal! Contactar con el Administrador",
            });
            console.log(save.xdebug_message);
          } else if (save === true) {
            resetTablas();
            Swal.fire({
              icon: "success",
              title: "¡Eliminación Exitosa!",
              text: "Se eliminó correctamente",
            });
          } else {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Algo salió Mal! Contactar con el Administrador",
            });
          }
        },
      });
    }
  });
}

function estadoContrato(status, id, item, active) {
  let timerInterval = Swal.fire({
    //se le asigna un nombre al swal
    title:
      '<i class="far fa-save" style="margin-right: 10px;"></i>¡Guardando Cambios!',
    html: "Espere unos Segundos.",
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading(); //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });
  var statusContract = new FormData();
  statusContract.append("status_autorize", status);
  statusContract.append("id_contract", id);
  statusContract.append("id_item", item);
  console.log(statusContract);
  $.ajax({
    type: "post",
    url: `${urls}permisos/actualizar_pago_tiempo`,
    data: statusContract,
    cache: false,
    dataType: "json",
    contentType: false,
    processData: false,
    success: function (save) {
      console.log(save);
      Swal.close(timerInterval);
      if (save.hasOwnProperty("xdebug_message")) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
        console.log(save.xdebug_message);
      } else if (save === true) {
        resetTablas();
        Swal.fire({
          icon: "success",
          title: "¡Cambio Exitoso!",
          text: "Se registró el cambio exitosamente",
        });
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

function editItem(id_item) {
  $("#error_id_item").text("");
  $("#error_turno").text("");
  $("#error_tipo_permiso").text("");
  $("#error_dia_salida").text("");
  $("#error_input_horas").text("");
  $("#error_input_minutos").text("");
  const data = new FormData();
  data.append("id_item", id_item);

  $.ajax({
    data: data,
    url: `${urls}permisos/datos_item_pago_tiempo`,
    type: "post",
    processData: false,
    contentType: false,
    async: true,
    dataType: "json",
    success: function (resp) {
      console.log(resp);
      if (resp != false) {
        $("#id_item").val(resp.id_item);
        $("#turno").val(resp.id_turn);
        $("#tipo_permiso").val(resp.type_pay);
        $("#dia_salida").val(resp.day_to_pay);
        $("#input_horas").val(resp.hour_pay);
        $("#input_minutos").val(resp.min_pay);
        $("#datosPagoTiempoModal").modal("show");
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

function turnos(select) {
  document.getElementById("div_horario").innerHTML = "";
  document.getElementById("tipo_permiso_opc").style.display = "none";

  var lv_in = document.getElementById("L-V_entrada");
  var lv_out = document.getElementById("L-V_salida");
  var s_in = document.getElementById("S_entrada");
  var s_out = document.getElementById("S_salida");

  lv_in.value = "";
  lv_out.value = "";
  s_in.value = "";
  s_out.value = "";

  const element_error = document.getElementById("error_" + select.id);
  if (select.value.length == 0) {
    element_error.textContent = "Campo Requerido";
    select.classList.add("has-error");
    return false;
  }
  element_error.textContent = "";
  select.classList.remove("has-error");
  const data = new FormData();
  data.append("id_turn", select.value);
  $.ajax({
    data: data,
    url: `${urls}permisos/horarios`,
    type: "post",
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (turn) {
      console.log(turn);
      if (turn != false && turn != null) {
        element_error.textContent = "";
        select.classList.remove("has-error");
        sabado =
          turn.hour_in_saturday == "00:00:00"
            ? "<b>Sabado:</b> Sin Horario"
            : ` <b>Sabado:</b> ${turn.hour_in_saturday} - ${turn.hour_out_saturday}`;
        $("#div_horario").append(
          `<b>Lunes a Viernes:</b> ${turn.hour_in} - ${turn.hour_out} <br> ${sabado}`
        );
        if (select.value == 9) {
          document.getElementById("tipo_permiso_opc").style.display = "block";
        }
        lv_in.value = turn.hour_in;
        lv_out.value = turn.hour_out;
        s_in.value = turn.hour_in_saturday;
        s_out.value = turn.hour_out_saturday;
      } else {
        element_error.textContent = "Campo Requerido";
        select.classList.add("has-error");
      }
    },
  });
}

function limpiarError(campo) {
  if (campo.value.length > 0) {
    campo.classList.remove("has-error");
    document.getElementById("error_" + campo.id).textContent = "";
  }

  if (campo.id == "input_horas" || campo.id == "input_minutos") {
    campo = document.getElementById("input_horas");
    campo.classList.remove("has-error");
    document.getElementById("error_" + campo.id).textContent = "";

    campo = document.getElementById("input_minutos");
    campo.classList.remove("has-error");
    document.getElementById("error_" + campo.id).textContent = "";
  }
}

function turnoCompleto() {
  setTimeout(function () {
    var turno = document.getElementById("turno").value;
    var tipo = document.getElementById("tipo_permiso").value;
    var dia = document.getElementById("dia_salida");
    var horas = document.getElementById("input_horas");
    var minutos = document.getElementById("input_minutos");
    if (tipo == 3) {
      horas.readOnly = true;
      minutos.readOnly = true;
      if (turno != "" && dia.value != "") {
        var input_horas = document.getElementById("input_horas");
        var input_minutos = document.getElementById("input_minutos");
        var error_dia = document.getElementById("error_dia_salida");
        error_dia.innerText = "";
        dia.classList.remove("has-error");
        const date = new Date(dia.value);
        const dayOfWeek = date.getDay(); // 5 ->Sabado, 6 -> Domingo, 0 -> Lunes ....
        if (dayOfWeek === 6) {
          error_dia.innerText = "Dia no Valido";
          dia.classList.add("has-error");
          horas.value = 0;
          minutos.value = 0;
        } else if (dayOfWeek === 5) {
          var S_in = document.getElementById("S_entrada").value;
          var S_out = document.getElementById("S_salida").value;

          if (S_in == "00:00:00") {
            error_dia.innerText = "Dia sin Horario";
            dia.classList.add("has-error");
            horas.value = 0;
            minutos.value = 0;
            tiempoTotal();
            return false;
          }
          if (turno == 9) {
            // Caso de 3er turno Sabado, Manual
            horas.value = 7;
            minutos.value = 30;
          } else {
            const horaInicio = new Date("2000-01-01T" + S_in);
            const horaFin = new Date("2000-01-01T" + S_out);

            const diferenciaMilisegundos = horaFin - horaInicio;
            const minutosTotales = Math.floor(
              diferenciaMilisegundos / 1000 / 60
            );

            const R_horas = Math.floor(minutosTotales / 60);
            const R_minutos = minutosTotales % 60;

            horas.value = R_horas;
            minutos.value = R_minutos;
          }
          input_horas.classList.remove("has-error");
          input_minutos.classList.remove("has-error");
          document.getElementById("error_input_horas").textContent = "";
          document.getElementById("error_input_minutos").textContent = "";
        } else {
          var LV_in = document.getElementById("L-V_entrada").value;
          var LV_out = document.getElementById("L-V_salida").value;
          if (turno == 9) {
            // Caso de 3er turno, Manual
            horas.value = 8;
            minutos.value = 0;
          } else {
            const horaInicio = new Date("2000-01-01T" + LV_in);
            const horaFin = new Date("2000-01-01T" + LV_out);

            const diferenciaMilisegundos = horaFin - horaInicio;
            const minutosTotales = Math.floor(
              diferenciaMilisegundos / 1000 / 60
            );

            const R_horas = Math.floor(minutosTotales / 60);
            const R_minutos = minutosTotales % 60;

            horas.value = R_horas;
            minutos.value = R_minutos;
          }
          input_horas.classList.remove("has-error");
          input_minutos.classList.remove("has-error");
          document.getElementById("error_input_horas").textContent = "";
          document.getElementById("error_input_minutos").textContent = "";
        }
        tiempoTotal();
      }
    }
  }, 200);
}

function pintarHorarios() {
  $.ajax({
    url: `${urls}permisos/lista_horarios`,
    type: "post",
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (arrayTurn) {
      if (arrayTurn != false && arrayTurn != null) {
        arrayTurn.forEach((t) => {
          $("#turno").append(`<option value="${t.id}">${t.name_turn}</option>`);
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "No se pudo cargar los Horarios",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
  });
}

$("#form_editar_pago_tiempo").submit(function (e) {
  e.preventDefault();

  var errores = 0;
  var turno = document.getElementById("turno");
  if (turno.value.length == 0) {
    turno.classList.add("has-error");
    document.getElementById("error_turno").textContent = "Campo Requerido";
    errores += 1;
  } else {
    turno.classList.remove("has-error");
    document.getElementById("error_turno").textContent = "";
  }

  var tipo_permiso = document.getElementById("tipo_permiso");
  if (tipo_permiso.value.length == 0) {
    tipo_permiso.classList.add("has-error");
    document.getElementById("error_tipo_permiso").textContent =
      "Campo Requerido";
    errores += 1;
  } else {
    tipo_permiso.classList.remove("has-error");
    document.getElementById("error_tipo_permiso").textContent = "";
  }

  var dia_salida = document.getElementById("dia_salida");
  if (dia_salida.value.length == 0) {
    dia_salida.classList.add("has-error");
    document.getElementById("error_dia_salida").textContent = "Campo Requerido";
    errores += 1;
  } else if (
    dia_salida.value < $("#hoy").val() ||
    dia_salida.value > $("#15dias").val()
  ) {
    dia_salida.classList.add("has-error");
    document.getElementById("error_dia_salida").textContent =
      "Día fuera de los Parámetros";
    errores += 1;
  } else {
    const date = new Date(dia_salida.value);
    const dayOfWeek = date.getDay(); // 5 ->Sabado, 6 -> Domingo, 0 -> Lunes ....
    if (dayOfWeek === 6) {
      dia_salida.classList.add("has-error");
      document.getElementById("error_dia_salida").textContent = "Dia no Valido";
      errores += 1;
    } else {
      dia_salida.classList.remove("has-error");
      document.getElementById("error_dia_salida").textContent = "";
    }
  }

  var input_horas = document.getElementById("input_horas");
  var input_minutos = document.getElementById("input_minutos");
  if (input_horas.value == 0 && input_minutos.value == 0) {
    input_horas.classList.add("has-error");
    input_minutos.classList.add("has-error");
    document.getElementById("error_input_horas").textContent =
      "Campo Requerido";
    document.getElementById("error_input_minutos").textContent =
      "Campo Requerido";
    errores += 1;
  } else {
    input_horas.classList.remove("has-error");
    input_minutos.classList.remove("has-error");
    document.getElementById("error_input_horas").textContent = "";
    document.getElementById("error_input_minutos").textContent = "";
  }

  if (errores > 0) {
    return false;
  }
  let timerInterval = Swal.fire({
    //se le asigna un nombre al swal
    title: "Generando Permiso de Vacaciones!",
    html: "Espere unos Segundos.",
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading(); //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });
  document.getElementById("btn_editar_pago_tiempo").disabled = true;
  var datos = new FormData($("#form_editar_pago_tiempo")[0]);
  $.ajax({
    data: datos,
    url: urls + "permisos/editar_pago_tiempo", //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    dataType: "json",
    success: function (save) {
      Swal.close(timerInterval);
      document.getElementById("btn_editar_pago_tiempo").disabled = false;
      if (save.hasOwnProperty("xdebug_message")) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
        console.log(save.xdebug_message);
      } else if (save != false && save != null) {
        document.getElementById("form_editar_pago_tiempo").reset();
        Swal.fire({
          icon: "success",
          title: "Exito",
          text: "¡Se registró el Pago de Tiempo exitosamente!.",
        });
        resetTablas();
        $("#datosPagoTiempoModal").modal("hide");
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
  }).fail(function (jqXHR, textStatus, errorThrown) {
    document.getElementById("btn_tiempo").disabled = false;
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
});

function resetTablas() {
  tbl_vacations.ajax.reload(null, false);
  tbl_permissions.ajax.reload(null, false);
  tbl_time_pay.ajax.reload(null, false);
}
