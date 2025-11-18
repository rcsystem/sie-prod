/**
 * ARCHIVO MODULO DASHBOARD VIGILANCIA
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
var urls = "https://sie.grupowalworth.com/";
var key =
  "5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677";
$(document).ready(function () {
  tbl_permissions = $("#tabla_permisos")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}permisos/permisos_autorizados_villa`,
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
          width: "15%",
          data: "nombre_solicitante",
          title: "USUARIO",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            var objFechaSalida = new Date(data["fecha_salida"]);
            var dia = (objFechaSalida.getDate() + 1)
              .toString()
              .padStart(2, "0");
            var mes = (objFechaSalida.getMonth() + 1)
              .toString()
              .padStart(2, "0");
              if(dia == 32){dia="01"; var mes = (objFechaSalida.getMonth() + 2)
              .toString()
              .padStart(2, "0"); }
            var anio = objFechaSalida.getFullYear();
            // Devuelve: '1/2/2011':
            let fecha_salida = dia + "-" + mes + "-" + anio;

            const hrSalida = data["hora_salida"];
            return $.trim(data["fecha_salida"]) === "0000-00-00"
              ? "---"
              : ` <div class="mr-auto"> ${fecha_salida} <br> ${hrSalida} </div> `;
          },
          title: "SALIDA",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            var objFechaEntrada = new Date(data["fecha_entrada"]);
            var dia = (objFechaEntrada.getDate() + 1)
              .toString()
              .padStart(2, "0");
            var mes = (objFechaEntrada.getMonth() + 1)
              .toString()
              .padStart(2, "0");
              if(dia == 32){dia="01"; var mes = (objFechaEntrada.getMonth() + 2)
              .toString()
              .padStart(2, "0"); }
            var anio = objFechaEntrada.getFullYear();
            // Devuelve: '1/2/2011':
            let fecha_entrada = dia + "-" + mes + "-" + anio;
            const hrEntrada = data["hora_entrada"];
            return $.trim(data["fecha_entrada"]) === "0000-00-00"
              ? "---"
              : ` <div class="mr-auto"> ${fecha_entrada}<br> ${hrEntrada} </div> `;
          },
          title: "ENTRADA",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            var objFechaInicio = new Date(data["inasistencia_del"]);
            var dia = objFechaInicio.getDate().toString().padStart(2, "0");
            var mes = (objFechaInicio.getMonth() + 1)
              .toString()
              .padStart(2, "0");
              
            var anio = objFechaInicio.getFullYear();
            var objFechaFin = new Date(data["inasistencia_al"]);
            var dia_fin = objFechaFin.getDate().toString().padStart(2, "0");
            var mes_fin = (objFechaFin.getMonth() + 1)
              .toString()
              .padStart(2, "0");
              if(dia == 32){dia="01"; var mes = (objFechaFin.getMonth() + 2)
              .toString()
              .padStart(2, "0"); }
            var anio_fin = objFechaFin.getFullYear();
            // Devuelve: '1/2/2011':
            let inasistencia_del = dia + "-" + mes + "-" + anio;
            let inasistencia_al = dia_fin + "-" + mes_fin + "-" + anio_fin;
            return $.trim(data["inasistencia_del"]) == "0000-00-00"
              ? "---"
              : ` <div class="mr-auto">Del: ${inasistencia_del} </br> Al: ${inasistencia_al} </div> `;
          },
          title: "AUSENCIA",
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
          title: "ESTATUS",
          className: "text-center",
        },
        {
          data: null,
          title: "VER",
          className: "text-center",
        },
      ],
      destroy: "true",
      columnDefs: [
        {
          targets: 6,
          render: function (data, type, full, meta) {
            return ` <div class="pull-right mr-auto">
                        <a href="${urls}permisos/ver-permisos/${$.md5(
              key + data["id_es"]
            )}" target="_blank" class="btn btn-info btn-sm">
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
        $(row).attr("id", "permissions_" + data.id_es);
      },
    })
    .DataTable();

  $("#tabla_permisos thead").addClass("thead-dark text-center");

  tbl_permissions = $("#tabla_proveedores_visitas")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}qhse/proveedores_visitas`,
        dataSrc: "",
      },
      lengthChange: true,
      ordering: true,
      responsive: true,
      autoWidth: true,
      lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
      rowId: "staffId",
    /*   dom: "lBfrtip",
      buttons: [
        {
          extend: "excelHtml5",
          title: "Visitas Proveedores",
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6],
          },
        },
         {
              extend:'pdfHtml5',
              title:'Listado de Proveedores',
              exportOptions:{
                columns:[1,2,3,4,5,6,7]
              }
            }      
           ], */
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
          data: "person_you_visit",
          title: "USUARIO",
        },

        {
          data: "suppliers",
          title: "PROVEEDOR",
        },

        {
          data: null,
          render: function (data, type, full, meta) {
            var objFechaSalida = new Date(data["day_you_visit"]);
            var dia = (objFechaSalida.getDate() + 1)
              .toString()
              .padStart(2, "0");
            var mes = (objFechaSalida.getMonth() + 1)
              .toString()
              .padStart(2, "0");
            var anio = objFechaSalida.getFullYear();
            // Devuelve: '1/2/2011':
            let fecha_salida = dia + "-" + mes + "-" + anio;

            const hrSalida = data["time_of_entry"];
            return $.trim(data["fecha_salida"]) === "0000-00-00"
              ? "---"
              : ` <div class="mr-auto"> ${fecha_salida} <br> ${hrSalida} </div> `;
          },
          title: "ENTRADA",
          className: "text-center",
        },

        {
          data: null,
          render: function (data, type, full, meta) {
            switch (data["authorize"]) {
              case "3":
                return `<span class="badge badge-danger">Rechazada</span>`;
                break;
              case "2":
                return `<span class="badge badge-success">Autorizada</span>`;
                break;
              case "1":
                return `<span class="badge badge-warning">Pendiente</span>`;
                break;

              default:
                return `<span class="badge badge-default">Error</span>`;
                break;
            }
          },
          title: "ESTATUS",
          className: "text-center",
        },
        
        {
          data: "departament_you_visit",
          title: "DEPTO",
        },
        {
          data: null,
          title: "VER",
          className: "text-center",
        },
      ],
      destroy: "true",
      columnDefs: [
        {
          targets: 6,
          render: function (data, type, full, meta) {
            return ` <div class="pull-right mr-auto">
                        <a href="${urls}qhse/ver-permiso/${$.md5(
              key + data["id"]
            )}" target="_blank" class="btn btn-info btn-sm">
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
        $(row).attr("id", "proveedor_" + data.id);
      },
    })
    .DataTable();

  $("#tabla_proveedores_visitas thead").addClass("thead-dark text-center");

  tbl_over_time = $("#tabla_tiempo_extra")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}qhse/tiempos_extra`,
        dataSrc: "",
      },
      lengthChange: true,
      ordering: true,
      responsive: true,
      autoWidth: true,
      lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
      rowId: "staffId",
      /*dom: "lBfrtip",
       buttons: [
        {
          extend: "excelHtml5",
          title: "Tiempo Extra",
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6],
          },
        },
        {
            extend:'pdfHtml5',
            title:'Listado de Proveedores',
            exportOptions:{
              columns:[1,2,3,4,5,6,7]
            }
          } 
      ], */
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
        },

        {
          data: null,
          render: function (data, type, full, meta) {
            var objFechaSalida = new Date(data["day_you_visit"]);
            var dia = (objFechaSalida.getDate() + 1)
              .toString()
              .padStart(2, "0");
            var mes = (objFechaSalida.getMonth() + 1)
              .toString()
              .padStart(2, "0");
            var anio = objFechaSalida.getFullYear();
            // Devuelve: '1/2/2011':
            let fecha_salida = dia + "-" + mes + "-" + anio;

            const hrSalida = data["time_of_entry"];
            return $.trim(data["day_you_visit"]) === "0000-00-00"
              ? "---"
              : ` <div class="mr-auto"> ${fecha_salida} <br> ${hrSalida} </div> `;
          },
          title: "ENTRADA",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            var objFechaSalida = new Date(data["departure_time"]);

            const hrSalida = data["departure_time"];
            return $.trim(hrSalida) === "00:00:00"
              ? "---"
              : ` <div class="mr-auto">${hrSalida} </div> `;
          },
          title: "SALIDA",
          className: "text-center",
        },
       
        {
          data: null,
          render: function (data, type, full, meta) {
            switch (data["authorize"]) {
              case "3":
                return `<span class="badge badge-danger">Rechazada</span>`;
                break;
              case "2":
                return `<span class="badge badge-success">Autorizada</span>`;
                break;
              case "1":
                return `<span class="badge badge-warning">Pendiente</span>`;
                break;

              default:
                return `<span class="badge badge-default">Error</span>`;
                break;
            }
          },
          title: "ESTATUS",
          className: "text-center",
        },
        {
          data: "departament",
          title: "DEPTO",
          className: "text-center",
        },
        {
          data: null,
          title: "VER",
          className: "text-center",
        },
      ],
      destroy: "true",
      columnDefs: [
        {
          targets: 6,
          render: function (data, type, full, meta) {
            return ` <div class="pull-right mr-auto">
                      <a href="${urls}qhse/ver-tiempo-extra/${$.md5(
              key + data["id"]
            )}" target="_blank" class="btn btn-info btn-sm">
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
        $(row).attr("id", "overtime_" + data.id);
      },
    })
    .DataTable();

  $("#tabla_tiempo_extra thead").addClass("thead-dark text-center");
});
