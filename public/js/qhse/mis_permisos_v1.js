/**
 * ARCHIVO MODULO QHSE
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
$(document).ready(function () {
  tbl_provedores = $("#tabla_permiso_proveedores")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: urls + "qhse/mis_permisos_proveedores",
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
            return `<div class=" mr-auto">
                        <a href="${urls}qhse/ver-permiso/${$.md5(
              key + data["id"]
            )}" title="Ver Permiso" target="_blank" class="btn btn-info btn-sm">
                         <i class="fas fa-eye"></i>
                        </a>
                        </div> `;
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

  tbl_tiempos = $("#tabla_tiempo_extra")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: urls + "qhse/mis_tiempo_extra",
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
          data: "day_you_visit",
          title: "DIA DE VISITA",
          className: "text-center",
        },
        {
          data: "time_of_entry",
          title: "ENTRADA",
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
          targets: 6,
          render: function (data, type, full, meta) {
            return `<div class=" mr-auto">
                        <a href="${urls}qhse/ver-tiempo-extra/${$.md5(
              key + data["id"]
            )}" title="Ver Permiso" target="_blank" class="btn btn-info btn-sm">
                         <i class="fas fa-eye"></i>
                        </a>
                        </div> `;
          },
        },
        /*           {
             targets: [3],
             className:"text-center"
           },  */
      ],
 
      order: [[0, "DESC"]],
 
      createdRow: (row, data) => {
        $(row).attr("id", "overtime_" + data.id);
      },
    })
    .DataTable();
  $("#tabla_tiempo_extra thead").addClass("thead-dark text-center");
 });
 