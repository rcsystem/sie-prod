/**
 * ARCHIVO MODULO PERMISOS
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */

const estado = { 1: 'PENDIENTE', 2: 'AUTORIZADA', 3: 'CANCELADA' };
const usado = { 1: 'DISPONIBLE', 2: 'USADO', 3: 'DEUDA', 4: 'DESHABILITADO' };
const color_estado = { 1: 'warning', 2: 'success', 3: 'danger' };
const color_usado = { 1: '53F3F3', 2: '7FF59A', 3: 'FEAF39', 4: 'D3D3D3'};

$(document).ready(function () {
  tbl_requisitions = $("#tabla_usuario_permisos").dataTable({
    processing: true,
    ajax: {
      method: "post",
      url: `${urls}permisos/por_usuario`,
      dataSrc: "",
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
        title: "Requisiciones",
        exportOptions: {
          columns: [0, 1, 2, 3, 4],
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
        title: "Folio",
        className: "text-center"
      },
      {
        data: "fecha_creacion",
        title: "FECHA CREACIÓN",
        className: "text-center"
      },
      {
        data: "nombre_solicitante",
        title: "USUARIO",
      },
      {
        data: "departamento",
        title: "DEPARTAMENTO",
      },
      {
        data: null,
        render: function (data, type, full, meta) {
          return (data["fecha_salida"] == "0000-00-00") ? "---" :
            ` <div class="mr-auto">
                      ${data["fecha_salida"]} </br>
                      ${data["hora_salida"]}
                    </div> `;
        },
        title: "SALIDA",
        className: "text-center"
      },
      {
        data: null,
        render: function (data, type, full, meta) {
          return (data["fecha_entrada"] == "0000-00-00") ? "---" :
            ` <div class="mr-auto">
                      ${data["fecha_entrada"]} </br>
                      ${data["hora_entrada"]}
                    </div> `;
        },
        title: "ENTRADA",
        className: "text-center"
      },
      {
        data: null,
        render: function (data, type, full, meta) {
          return (data["inasistencia_del"] == "0000-00-00") ? "---" :
            ` <div class="mr-auto">
                      ${data["inasistencia_del"]} </br>
                      ${data["inasistencia_al"]}
                    </div> `;
        },
        title: "INASISTENCIA",
        className: "text-center"
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
          switch (data["estatus"]) {
            case "Rechazada":
              return `<span class="badge badge-danger">${data["estatus"]}</span>`;
              break;
            case "Autorizada":
              return `<span class="badge badge-success">${data["estatus"]}</span>`;
              break;

            default:
              return `<span class="badge badge-warning">${data["estatus"]}</span>`;
              break;
          }
        },
        title: "Estatus",
        className: "text-center"
      },
      {
        data: null,
        title: "VER",
        className: "text-center"
      },
    ],
    destroy: "true",
    columnDefs: [
      {
        targets: 9,
        render: function (data, type, full, meta) {
          return ` <div class="mr-auto">
                       <a href="${urls}permisos/ver-permisos/${$.md5(key + data["id_es"])}" target="_blank" class="btn btn-info btn-sm">
                             <i class="fas fa-eye"></i>
                       </a>
                     </div> `;
        },
      },
      {
        targets: [0],
        visible: false,
        searchable: false,
      },
    ],

    order: [[0, "DESC"]],

    createdRow: (row, data) => {
      $(row).attr("id", "request_" + data.id_es);
    },
  }).DataTable();
  $('#tabla_usuario_permisos thead').addClass('thead-dark text-center');

  /*SE LLENA LA TABLA DE PERMISOS DE VACACIONES  */
  tbl_vacations = $("#tabla_usuario_vacaciones").dataTable({
    processing: true,
    ajax: {
      method: "post",
      url: urls + "permisos/mis-vacaciones",
      dataSrc: "",
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
        title: "Días de Vacaciones",
        exportOptions: {
          columns: [0, 1, 2, 3, 4],
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
        title: "Folio",
        className: "text-center"
      },
      {
        data: null,
        render: function (data, type, full, meta) {
          var objFechaCreacion = new Date(data["fecha_registro"]);
          var dia = (objFechaCreacion.getDate()).toString().padStart(2, "0");
          var mes = (objFechaCreacion.getMonth() + 1).toString().padStart(2, "0");
          var anio = objFechaCreacion.getFullYear();
          var hora = objFechaCreacion.getHours();
          var minutos = objFechaCreacion.getMinutes();
          // Devuelve: '1/2/2011':
          let fecha_creacion = dia + "-" + mes + "-" + anio;
          let hora_creacion = hora + ":" + minutos;
          return $.trim(data["fecha_registro"]) === "0000-00-00 00:00:00" ? "---" : ` <div class="mr-auto"> ${fecha_creacion} ${hora_creacion} </div> `;
        },
        title: "CREACIÓN",
        className: "text-center"
      },
      {
        data: "nombre_solicitante",
        title: "USUARIO",
      },
      {
        data: "num_dias_a_disfrutar",
        title: "DÍAS A DISFRUTAR",
        className: "text-center"
      },
      {
        data: null,
        render: function (data, type, full, meta) {
          return (data["dias_a_disfrutar_del"] == "0000-00-00") ? "" :
            ` <div class="mr-auto">
                      ${data["dias_a_disfrutar_del"]}
                    </div> `;
        },
        title: "DEL",
        className: "text-center"
      },
      {
        data: null,
        render: function (data, type, full, meta) {
          return (data["dias_a_disfrutar_al"] == "0000-00-00") ? "---" :
            ` <div class="mr-auto">
                      ${data["dias_a_disfrutar_al"]}
                    </div> `;
        },
        title: "AL",
        className: "text-center"
      },
      {
        data: null,
        render: function (data, type, full, meta) {
          action = (data["id_vcns"] > 8694) ? `onclick="verFechas(${data["id_vcns"]})"` : 'disabled'
          color = (data["id_vcns"] > 8694) ? `outline-primary` : 'secondary'
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
          return (data["regreso"] == "0000-00-00") ? "---" :
            ` <div class="mr-auto">
                      ${data["regreso"]}
                    </div> `;
        },
        title: "REGRESA",
        className: "text-center"
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

            default:
              return `<span class="badge badge-warning">${data["estatus"]}</span>`;
              break;
          }
        },
        title: "ESTATUS",
        className: "text-center"
      },

      {
        data: null,
        title: "VER",
        className: "text-center"
      },
    ],
    destroy: "true",
    columnDefs: [
      {
        targets: 9,
        render: function (data, type, full, meta) {
          return ` <div class="mr-auto">
                       <a href="${urls}permisos/vacaciones/${$.md5(key + data["id_vcns"])}" target="_blank" class="btn btn-info btn-sm">
                             <i class="fas fa-eye"></i>
                       </a>
                     </div> `;
        },
      },
      {
        targets: [0],
        visible: false,
        searchable: false,
      },
    ],

    order: [[0, "DESC"]],

    createdRow: (row, data) => {
      $(row).attr("id", "vacaciones_" + data.id_vcns);
    },
  }).DataTable();
  $('#tabla_usuario_vacaciones thead').addClass('thead-dark text-center');

  tbl_time_pay = $("#tbl_pago_tiempo").dataTable({
    processing: true,
    ajax: {
      method: "post",
      url: `${urls}permisos/mis_pago_tiempo`,
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
        data: null,
        render: function (data, type, full, meta) {
          return `<span class="badge" style="background-color:#${color_usado[data["estado"]]};">${usado[data["estado"]]}</span>`;
        },
        title: "ESTADO PAGO",
        className: "text-center",
      },
      {
        data: null,
        render: function (data, type, full, meta) {
          return `<span class="badge badge-${color_estado[data["status_autorize"]]}">${estado[data["status_autorize"]]}</span>`;
        },
        title: "ESTATUS",
        className: "text-center",
      },
    ],
    destroy: "true",
    order: [[0, "DESC"]],
    createdRow: (row, data) => {
      $(row).attr("id", "permissions_" + data.id_es);
    },
  }).DataTable();
  $("#tbl_pago_tiempo thead").addClass("thead-dark text-center");

});

$("#actualizar_requisicion").submit(function (event) {
  event.preventDefault();
  $("#actualiza_requisicion").prop('disabled', true);

  let data = new FormData();

  data.append("id_folio", $("#id_folio").val());
  data.append("tipo_personal", $("#tipo_personal").val());
  data.append("puesto_solicitado", $("#puesto_solicitado").val());
  data.append("personas_requeridas", $("#personas_requeridas").val());

  data.append("salario_inicial", $("#salario_inicial").val());
  data.append("salario_final", $("#salario_final").val());
  data.append("horario_inicial", $("#horario_inicial").val());
  data.append("horario_final", $("#horario_final").val());


  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: urls + "requisiciones/actualizar_requisicion", //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    success: function (response) {
      //una vez que el archivo recibe el request lo procesa y lo devuelve
      //console.log(response);
      /*codigo que borra todos los campos del form newProvider*/
      if (response != "error") {
        setTimeout(function () {
          tbl_requisitions.ajax.reload(null, false);
        }, 100);
        $("#actualiza_requisicion").prop('disabled', false);
        $("#editarModal").modal("toggle");
        Swal.fire("!Los datos se han Actualizado!", "", "success");

      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }

    },
  });
});

function handleEdit(id_folio) {
  //console.log("Hola Mundo Edit" + id_supplies);
  let data = new FormData();

  data.append("id_folio", id_folio);


  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: urls + "requisiciones/editar_requisicion", //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    async: true,
    dataType: "json",
    success: function (resp) {
      //console.log(resp.tipo_de_personal);
      //console.log(resp);
      if (resp != "error") {
        $("#id_folio").val(id_folio);
        $("#tipo_personal").val(resp.tipo_de_personal);
        $("#puesto_solicitado").val(resp.puesto_solicitado);
        $("#personas_requeridas").val(resp.personas_requeridas);
        $("#horario_inicial").val(resp.horario_inicial);
        $("#horario_final").val(resp.horario_final);
        $("#salario_inicial").val(resp.salario_inicial);
        $("#salario_final").val(resp.salario_final);

        $("#editarModal").modal("show");
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
        console.log("Mal Revisa");
      }
    },
  });
}

function validaNumericos(event) {
  return event.charCode >= 48 && event.charCode <= 57 ? true : false;
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
        var i = 0
        resp.forEach(r => {
          var styl = (i == 0) ? '' : 'margin-top: 10px;';
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