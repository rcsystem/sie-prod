/**
 * ARCHIVO MODULO QHSE
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
$(document).ready(function () {
  tbl_suppliers = $("#tabla_permiso_proveedores")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: urls + "qhse/permisos_proveedores_all",
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
          title: "Permisos proveedores",
          exportOptions: {
            columns: [0, 1, 2, 3, 4,5,6],
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
          data: "id",
          title: "FOLIO",
          className: "text-center",
        },
        {
          data: "name",
          title: "USUARIO",
          className: "text-center",
        },
        {
          data: "departament",
          title: "DEPARTAMENTO",
          className: "text-center",
        },

        {
          data: "suppliers",
          title: "PROVEEDOR",
          className: "text-center",
        },
        {
          data: "day_you_visit",
          title: "DIA DE VISITA",
          className: "text-center",
        },
        {
          data: "time_of_entry",
          title: "HORA DE LLEGADA",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            switch (data["authorize"]) {
              case "1":
                return `<span class="badge badge-warning">Pendiente</span>`;
                break;
              case "2":
                return `<span class="badge badge-success">Autorizada</span>`;
                break;

              case "3":
                return `<span class="badge badge-danger">Rechazada</span>`;
                break;
            }
          },
          title: "ESTATUS",
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
          targets: 7,
          render: function (data, type, full, meta) {
            if (data["authorize"] != 2) {
              return `<div class=" mr-auto">
              <button type="button" class="btn btn-primary btn-sm" title="Autorizar Requisiciones"  onClick=handleChange(${
                data["id"]
              })>
                    <i class="fas fa-user-check"></i>
              </button>
                  <a href="${urls}qhse/ver-permiso/${$.md5(
                key + data["id"]
              )}" title="Ver Permiso" target="_blank" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i>
                  </a>
                </div> `;
            } else {
              return ` <div class=" mr-auto">
                        <button type="button" class="btn btn-secondary btn-sm" title="Autorizar Requisiciones">
                        <i class="fas fa-user-check"></i>
                        </button>    
                        <a href="${urls}qhse/ver-permiso/${$.md5(
                key + data["id"]
              )}" title="Ver Permiso" target="_blank" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div> `;
            }
          },
        },
        /*           {
            targets: [3],
            className:"text-center"
          },  */
      ],

      order: [[0, "DESC"]],

      createdRow: (row, data) => {
        $(row).attr("id", "suppliers_" + data.id);
      },
    })
    .DataTable();
  $("#tabla_permiso_proveedores thead").addClass("thead-dark text-center");

  tbl_suppliers_stay = $("#tabla_permiso_estadias")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: urls + "qhse/permisos_proveedores_estadias_all",
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
          title: "Permisos Estadias",
          exportOptions: {
            columns: [0, 1, 2, 3, 4,5,6],
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
          data: "id",
          title: "FOLIO",
          className: "text-center",
        },
        {
          data: "name",
          title: "USUARIO",
          className: "text-center",
        },
        {
          data: "departament",
          title: "DEPARTAMENTO",
          className: "text-center",
        },

        {
          data: "suppliers",
          title: "PROVEEDOR",
          className: "text-center",
        },
        {
          data: "start_date_of_stay",
          title: "INICIO ESTADIA",
          className: "text-center",
        },
        {
          data: "end_date_of_stay",
          title: "FIN ESTADIA",
          className: "text-center",
        },
        {
          data: "time_of_entry",
          title: "LLEGADA (tentativa)",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            switch (data["authorize"]) {
              case "1":
                return `<span class="badge badge-warning">Pendiente</span>`;
                break;
              case "2":
                return `<span class="badge badge-success">Autorizada</span>`;
                break;

              case "3":
                return `<span class="badge badge-danger">Rechazada</span>`;
                break;
            }
          },
          title: "ESTATUS",
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
          targets: 8,
          render: function (data, type, full, meta) {
            if (data["authorize"] != 2) {
              return `<div class=" mr-auto">
              <button type="button" class="btn btn-primary btn-sm" title="Autorizar Requisiciones"  onClick=handleChangeStay(${
                data["id"]
              })>
                    <i class="fas fa-user-check"></i>
              </button>
                  <a href="${urls}qhse/ver-permiso/${$.md5(
                key + data["id"]
              )}" title="Ver Permiso" target="_blank" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i>
                  </a>
                </div> `;
            } else {
              return ` <div class=" mr-auto">
                        <button type="button" class="btn btn-secondary btn-sm" title="Autorizar Requisiciones">
                        <i class="fas fa-user-check"></i>
                        </button>    
                        <a href="${urls}qhse/ver-permiso/${$.md5(
                key + data["id"]
              )}" title="Ver Permiso" target="_blank" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div> `;
            }
          },
        },
        /*           {
            targets: [3],
            className:"text-center"
          },  */
      ],

      order: [[0, "DESC"]],

      createdRow: (row, data) => {
        $(row).attr("id", "suppliers_" + data.id);
      },
    })
    .DataTable();
  $("#tabla_permiso_estadias thead").addClass("thead-dark text-center");

  tbl_overtime = $("#tabla_tiempos_extras")
  .dataTable({
    processing: true,
    ajax: {
      method: "post",
      url: urls + "qhse/tiempos_extras_all",
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
        title: "Permisos tiempos extra",
        exportOptions: {
          columns: [0, 1, 2, 3, 4,5,6],
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
        data: "id",
        title: "FOLIO",
        className: "text-center",
      },
      {
         data: "payroll_number",
         title: "Numero nomina",
         className: "text-center",
       },
      {
        data: "name",
        title: "USUARIO",
        className: "text-center",
      },
      {
        data: "departament",
        title: "DEPARTAMENTO",
        className: "text-center",
      },
      {
        data: "day_you_visit",
        title: "DIA DE VISITA",
        className: "text-center",
      },
      {
        data: "time_of_entry",
        title: "HORA DE LLEGADA",
        className: "text-center",
      },
      {
         data: "departure_time",
         title: "HORA DE SALIDA",
         className: "text-center",
       },
      {
        data: null,
        render: function (data, type, full, meta) {
          switch (data["authorize"]) {
            case "1":
              return `<span class="badge badge-warning">Pendiente</span>`;
              break;
            case "2":
              return `<span class="badge badge-success">Autorizada</span>`;
              break;

            case "3":
              return `<span class="badge badge-danger">Rechazada</span>`;
              break;
          }
        },
        title: "ESTATUS",
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
        targets: 8,
        render: function (data, type, full, meta) {
          if (data["authorize"] != 2) {
            return `<div class=" mr-auto">
            <button type="button" class="btn btn-primary btn-sm" title="Autorizar Requisiciones"  onClick=Change(${
              data["id"]
            })>
                  <i class="fas fa-user-check"></i>
            </button>
                <a href="${urls}qhse/ver-tiempo-extra/${$.md5(key + data["id"])}" title="Ver Permiso" target="_blank" class="btn btn-info btn-sm">
                      <i class="fas fa-eye"></i>
                </a>
              </div> `;
          } else {
            return ` <div class=" mr-auto">
                      <button type="button" class="btn btn-secondary btn-sm" title="Autorizar Requisiciones">
                      <i class="fas fa-user-check"></i>
                      </button>    
                      <a href="${urls}qhse/ver-tiempo-extra/${$.md5(key + data["id"])}" title="Ver Permiso" target="_blank" class="btn btn-info btn-sm">
                          <i class="fas fa-eye"></i>
                      </a>
                  </div> `;
          }
        },
      },
      /*           {
          targets: [3],
          className:"text-center"
        },  */
    ],

    order: [[0, "DESC"]],

    createdRow: (row, data) => {
      $(row).attr("id", "suppliers_" + data.id);
    },
  })
  .DataTable();
$("#tabla_tiempos_extras thead").addClass("thead-dark text-center");

});

function handleChange(id_folio) {
  let data = new FormData();

  data.append("id_folio", id_folio);

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: urls + "qhse/permiso_detalles", //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    async: true,
    dataType: "json",
    success: function (resp) {
      //console.log(resp);
      if (resp != "error") {
        $("#id_folio").val(id_folio);
        resp.forEach(function (data, index) {
          $("#proveedor").val(data.suppliers);
          $("#dia_visita").val(data.day_you_visit);
          $("#hora_llegada").val(data.time_of_entry);
          $("#persona_visita").val(data.person_you_visit);
          $("#departamento").val(data.departament_you_visit);
        });
        $("#autorizarModal").modal("show");
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
    error: function (jqXHR, status, error) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Algo salió Mal! Contactar con el Administrador",
      });
      /* console.log("Mal Revisa entro en el error: "+ error); */
    },
  });
}

$("#autorizar_permiso").submit(function (event) {
  event.preventDefault();
  $("#autorizar_permisos").prop("disabled", true);

  let data = new FormData();

  data.append("id_folio", $("#id_folio").val());
  data.append("autorizacion", $("#autorizacion").val());

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: urls + "qhse/autorizar_permiso", //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    success: function (response) {
      //una vez que el archivo recibe el request lo procesa y lo devuelve
      //console.log(response);
      /*codigo que borra todos los campos del form newProvider*/
      $("#autorizar_permisos").prop("disabled", false);
      if (response != "error") {
        setTimeout(function () {
          tbl_suppliers.ajax.reload(null, false);
        }, 100);
        $("#autorizarModal").modal("toggle");
        Swal.fire("!Los datos se han Actualizado!", "", "success");
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
    error: function (jqXHR, status, error) {
      $("#autorizar_permisos").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Algo salió Mal! Contactar con el Administrador",
      });
      /* console.log("Mal Revisa entro en el error: "+ error); */
    },
  });
});

function Change(id_folio) {
  let data = new FormData();

  data.append("id_folio", id_folio);

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: urls + "qhse/extra_detalles", //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    async: true,
    dataType: "json",
    success: function (resp) {
      //console.log(resp);
      if (resp != "error") {
        $("#id_folio_extra").val(id_folio);
        resp.forEach(function (data, index) {
          $("#usuario_extra").val(data.name);
          $("#depto_extra").val(data.departament);
          $("#dia_extra").val(data.day_you_visit);
          $("#hora_llegada_extra").val(data.time_of_entry);
          $("#hora_salida_extra").val(data.departure_time);
          
          
        });
        $("#autorizarTiemposModal").modal("show");
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
    error: function (jqXHR, status, error) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Algo salió Mal! Contactar con el Administrador",
      });
    },
  });
}

$("#autorizar_tiempo_extra").submit(function (event) {
  event.preventDefault();
  $("#autorizar_extra").prop("disabled", true);

  let data = new FormData();

  data.append("id_folio", $("#id_folio_extra").val());
  data.append("autorizacion", $("#autorizacion_extra").val());

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: urls + "qhse/autorizar_tiempo_extra", //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    success: function (response) {
      //una vez que el archivo recibe el request lo procesa y lo devuelve
      //console.log(response);
      /*codigo que borra todos los campos del form newProvider*/
      if (response != "error") {
        setTimeout(function () {
          tbl_overtime.ajax.reload(null, false);
        }, 100);
        $("#autorizar_extra").prop("disabled", false);
        $("#autorizarTiemposModal").modal("toggle");
        Swal.fire("!Los datos se han Actualizado!", "", "success");
      } else {
        $("#autorizar_extra").prop("disabled", false);
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
    error: function (jqXHR, status, error) {
      $("#autorizar_extra").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Algo salió Mal! Contactar con el Administrador",
      });
    },
  });
});

function handleChangeStay(id_folio) {
  let data = new FormData();

  data.append("id_folio", id_folio);

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: urls + "qhse/permiso_detalles", //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    async: true,
    dataType: "json",
    success: function (resp) {
      //console.log(resp);
      if (resp != "error") {
        $("#id_folio_estadia").val(id_folio);
        resp.forEach(function (data, index) {
          $("#estadia_proveedor").val(data.suppliers);
          $("#inicio_estadia").val(data.start_date_of_stay);
          $("#fin_estadia").val(data.end_date_of_stay);
          $("#estadia_hora_llegada").val(data.time_of_entry);
          $("#estadia_persona_visita").val(data.person_you_visit);
          $("#estadia_departamento").val(data.departament_you_visit);
        });
        $("#autorizarEstadiasModal").modal("show");
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
    error: function (jqXHR, status, error) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Algo salió Mal! Contactar con el Administrador",
      });
      /* console.log("Mal Revisa entro en el error: "+ error); */
    },
  });
}

$("#autorizar_estadias").submit(function (event) {
  event.preventDefault();
  $("#autorizar_estadia").prop("disabled", true);

  let data = new FormData();

  data.append("id_folio", $("#id_folio_estadia").val());
  data.append("autorizacion", $("#estadia_autorizacion").val());

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: urls + "qhse/autorizar_permiso", //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    success: function (response) {
      //una vez que el archivo recibe el request lo procesa y lo devuelve
      //console.log(response);
      /*codigo que borra todos los campos del form newProvider*/
      $("#autorizar_estadia").prop("disabled", false);
      if (response != "error") {
        setTimeout(function () {
          tbl_suppliers_stay.ajax.reload(null, false);
        }, 100);
        $("#autorizarEstadiasModal").modal("toggle");
        Swal.fire("!Los datos se han Actualizado!", "", "success");
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
    error: function (jqXHR, status, error) {
      $("#autorizar_estadia").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Algo salió Mal! Contactar con el Administrador",
      });
      /* console.log("Mal Revisa entro en el error: "+ error); */
    },
  });
});
