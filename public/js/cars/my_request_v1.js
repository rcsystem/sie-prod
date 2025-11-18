/**
 * ARCHIVO MODULO CARS
 * AUTOR: HORUS SAMAEL RIVAS PEDRAZA
 * horus.riv.ped@gmail.com
 * CEL:5624392632
 */

$(document).ready(function () {
  tbl_requisitions = $("#mis_solicitudes_automoviles")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}autos/mis-solicitudes`,
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
          title: "Solicitud de Vehiculo",
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5],
          },
        },
      ],
      language: {
        url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
      },
      columns: [
        {
          data: "id_request",
          title: "Folio",
          className: "text-center",
        },
        {
          data: "created_at",
          title: "FECHA CREACIÓN",
          className: "text-center",
        },
        {
          data: "name",
          title: "USUARIO",
        },
        {
          data: "depto",
          title: "DEPARTAMENTO",
        },
        {
          data: null,
          render: function (data, type, row, meta) {
             var model = "";
            if( data["id_cars"] == null){
              var model = "SIN ASIGNAR";
            }else{
              model = data["model"].toUpperCase();
            }
            return model;
          },
          title: "MODELO",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, row, meta) {
             var placa = "";
            if( data["id_cars"] == null){
              var placa = "SIN ASIGNAR";
            }else{
              placa = data["placa"].toUpperCase();
            }
            return placa;
          },
          title: "PLACA",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            switch (data["status"]) {
              case "1":
                return `<span class="badge badge-warning">Pendiente</span>`;
                break;
              case "2":
                return `<span class="badge badge-info">Por Asignar</span>`;
                break;
              case "3":
                return `<span class="badge badge-danger">Rechazada</span>`;
                break;
                case "4":
                return `<span class="badge badge-success">Aprobado</span>`;
                break;

              default:
                return `<span class="badge badge-primary">Error</span>`;
                break;
            }
          },
          title: "Estatus",
          className: "text-center",
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
          targets: 7,
          render: function (data, type, full, meta) {
            return ` <div class="mr-auto">
                       <a href="${urls}autos/ver-solicitudes/${$.md5(key + data["id_request"])}" target="_blank" class="btn btn-info btn-sm">
                             <i class="fas fa-eye"></i>
                       </a>
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
    })
    .DataTable();
  $("#mis_solicitudes_automoviles thead").addClass("thead-dark text-center");
});
