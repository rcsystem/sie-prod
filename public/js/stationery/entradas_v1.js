/**
 * ARCHIVO MODULO ADMINISTRATOR
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
$(document).ready(function () {
    tbl_inventary = $("#tabla_entradas_inventario")
      .dataTable({
        processing: true,
        ajax: {
          method: "post",
          url: `${urls}papeleria/todas_entradas`,
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
            title: "Entradas Papeleria",
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
            data: "id_product",
            title: "ID ITEM",
            className: "text-center",
          },
          {
            data: "code_epicor",
            title: "EPICOR",
            className: "text-center",
          },
  
          {
            data: null,
            render: function (data, type, full, meta) {
              let name = data["product"].toLowerCase();
  
              return name;
            },
  
            title: "PRODUCTOS",
            className: "text-center",
          },
          {
            data: "amount",
            title: "CANTIDAD",
            className: "text-center",
          },
          
          {
            data: "observations",
            title: "DETALLES",
            className: "text-center",
          }, 
           {
            data: "created_at",
            title: "FECHA",
            className: "text-center",
          }, 
         /*  {
            data: null,
            title: "ACCIONES",
            className: "text-center",
          }, */
        ],
        destroy: "true",
       /* columnDefs: [
          {
            targets: 5,
            render: function (data, type, full, meta) {
  
              return ` <div class="pull-right mr-auto">
              <button type="button" class="btn btn-primary btn-sm" title="Editar Suministro"  onClick=Edit(${data["id"]})>
                  <i class="far fa-edit"></i>
              </button>
              <a href="${urls}papeleria/ver-requisicion/${$.md5(
                key + data["id"]
              )}" title="Ver Requisición" target="_blank" class="btn btn-info btn-sm">
              <i class="fas fa-eye"></i>
        </a>
                        
                      </div> `;
            },
          },
            {
              targets: [0],
              visible: false,
              searchable: true,
            },  
        ],*/
  
        order: [[0, "DESC"]],
        select: true,
        createdRow: (row, data) => {
          $(row).attr("id", "request_" + data.id);
        },
      })
      .DataTable();
    $("#tabla_entradas_inventario thead").addClass("thead-dark text-center");
    /*---------------------------------------------------SALIDAS------------------------------------------------------------- */
    tbl_exits = $("#tabla_salidas_inventario")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}papeleria/todas_salidas`,
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
          title: "Salidas Papeleria",
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
          data: "id_product",
          title: "ID ITEM",
          className: "text-center",
        },
        {
          data: "code_epicor",
          title: "EPICOR",
          className: "text-center",
        },

        {
          data: null,
          render: function (data, type, full, meta) {
            let name = data["product"].toLowerCase();

            return name;
          },

          title: "PRODUCTOS",
          className: "text-center",
        },
        {
          data: "amount",
          title: "CANTIDAD",
          className: "text-center",
        },
        
        {
          data: "observations",
          title: "DETALLES",
          className: "text-center",
        }, 
         {
          data: "created_at",
          title: "FECHA",
          className: "text-center",
        }, 
       /*  {
          data: null,
          title: "ACCIONES",
          className: "text-center",
        }, */
      ],
      destroy: "true",
     /* columnDefs: [
        {
          targets: 5,
          render: function (data, type, full, meta) {

            return ` <div class="pull-right mr-auto">
            <button type="button" class="btn btn-primary btn-sm" title="Editar Suministro"  onClick=Edit(${data["id"]})>
                <i class="far fa-edit"></i>
            </button>
            <a href="${urls}papeleria/ver-requisicion/${$.md5(
              key + data["id"]
            )}" title="Ver Requisición" target="_blank" class="btn btn-info btn-sm">
            <i class="fas fa-eye"></i>
      </a>
                      
                    </div> `;
          },
        },
          {
            targets: [0],
            visible: false,
            searchable: true,
          },  
      ],*/

      order: [[0, "DESC"]],
      select: true,
      createdRow: (row, data) => {
        $(row).attr("id", "salidas_" + data.id);
      },
    })
    .DataTable();
  $("#tabla_salidas_inventario thead").addClass("thead-dark text-center");
  });