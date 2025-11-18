/**
 * ARCHIVO MODULO ADMINISTRATOR
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
$(document).ready(function () {
  console.log(`${urls}papeleria/mis_solicitudes`);
  tbl_inventary = $("#tabla_solicitudes")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}papeleria/mis_solicitudes`,
        dataSrc: "",
      },
      lengthChange: true,
      ordering: true,
      responsive: true,
      autoWidth: false,
      rowId: "staffId",
      dom: "lBfrtip",
      buttons: [
        /* {
          extend: "excelHtml5",
          title: "Solicitudes Papeleria",
          exportOptions: {
            columns: [0, 1, 2, 3, 4],
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
          data: "id_request",
          title: "FOLIO",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            var objFechaCreacion= new Date(data["created_at"]);
            var dia = (objFechaCreacion.getDate()).toString().padStart(2, "0");
            var mes = (objFechaCreacion.getMonth() + 1).toString().padStart(2, "0");
            var anio = objFechaCreacion.getFullYear();
            var hora = objFechaCreacion.getHours();
            var minutos = objFechaCreacion.getMinutes();
             // Devuelve: '1/2/2011':
             let fecha_creacion = dia + "-" + mes + "-" + anio;
             let hora_creacion = hora+":"+minutos;
            return $.trim(data["created_at"]) === "0000-00-00"
              ? "---"
              : ` <div class="mr-auto">
                      ${fecha_creacion} 
                      ${hora_creacion}
                    </div> `;
          },
          title: "FECHA",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            const estatus = ["Error","Pendiente","Autorizado","Completado","Rechazada"];
            const badge = ["warning","warning","info","success","danger"];

            if (data["request_status"]<1 && data["request_status"]>4) {
                return `<span class="badge badge-warning">Error</span>`;
            }

            return `<span class="badge badge-${badge[data["request_status"]]}">${estatus[data["request_status"]]}</span>`;
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
          targets: 3,
          render: function (data, type, full, meta) {
            return ` <div class="pull-right mr-auto">
            <a href="${urls}papeleria/ver-requisicion/${$.md5(key + data["id_request"])}" title="Ver Requisición" target="_blank" class="btn btn-info btn-sm">
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
        $(row).attr("id", "product_" + data.id_product);
      },
    })
    .DataTable();
  $("#tabla_solicitudes thead").addClass("thead-dark text-center");
});