/**
 * ARCHIVO MODULO VALIJAS SOLICITUDES
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
 $(document).ready(function () {
    tbl_requisitions = $("#tabla_valija_solicitudes")
      .dataTable({
        processing: true,
        ajax: {
          method: "post",
          url: `${urls}valija/solicitudes_usuario`,
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
            title: "Solicitudes",
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
            data: "id_valija",
            title: "FOLIO",
            className: "text-center"
          },
          {
            data: "created_at",
            title: "FECHA CREACIÓN",
            className: "text-center"
          },
          {
            data: "user_name",
            title: "USUARIO",
          },
          {
            data: "departament",
            title: "DEPARTAMENTO",
          },
          {
            data: "origin",
            title: "ORIGEN",
            className: "text-center"
          },
          {
            data: "destination",
            title: "DESTINO",
            className: "text-center"
          },
          
         
         {
           data: null,
           render: function (data, type, full, meta) {
             switch (data["priority"]) {

                case "BAJA":
                 return `<span class="badge badge-info">${data["priority"]}</span>`;
                 break;
              
               case "NORMAL":
                 return `<span class="badge badge-success">${data["priority"]}</span>`;
                 break;

                 case "INMEDIATA":
                 return `<span class="badge badge-danger">${data["priority"]}</span>`;
                 break;
 
               default:
                 return `<span class="badge badge-warning">ERROR</span>`;
                 break;
             }
           },
           title: "PRIORIDAD",
           className: "text-center"
         },
         {
            data: null,
            render: function (data, type, full, meta) {
              switch (data["status"]) {
 
                 case "1":
                  return `<span class="badge badge-warning">Pendiente</span>`;
                  break;
               
                case "2":
                  return `<span class="badge badge-success">Agendado</span>`;
                  break;
 
                  case "3":
                  return `<span class="badge badge-danger">Cancelado</span>`;
                  break;
  
                default:
                  return `<span class="badge badge-warning">ERROR</span>`;
                  break;
              }
            },
            title: "PRIORIDAD",
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
            targets: 8,
            render: function (data, type, full, meta) {
              return ` <div class="mr-auto">
                        <a href="${urls}valija/ver-solicitudes/${$.md5(key + data["id_valija"])}" target="_blank" class="btn btn-info btn-sm">
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
  
        createdRow: (row, data) => {
          $(row).attr("id", "request_" + data.id_valija);
        },
      })
      .DataTable();
      $('#tabla_valija_solicitudes thead').addClass('thead-dark text-center');
    });