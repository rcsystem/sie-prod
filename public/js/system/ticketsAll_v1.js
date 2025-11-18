/**
 * ARCHIVO MODULO SISTEMAS
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
 $(document).ready(function () {
   tbl_activitys_All = $("#tabla_todas_actividades")
     .dataTable({
       processing: true,
       ajax: {
         method: "post",
         url: urls + "sistemas/todos_tickets",
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
           title: "Tickets Usuarios",
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
          data: "id_ticket",
          title: "FOLIO",
          className: "text-center"
        },
         {
           data: null,
           render: function (data, type, full, meta) {
            var objFechaCreacion= new Date(data["created_at"]);
                var dia = (objFechaCreacion.getDate()+1).toString().padStart(2, "0");
                var mes = (objFechaCreacion.getMonth()+1).toString().padStart(2, "0");
               var anio = objFechaCreacion.getFullYear();
               var hora = (objFechaCreacion.getHours()).toString().padStart(2, "0");
            var minutos = (objFechaCreacion.getMinutes()).toString().padStart(2, "0");;
             // Devuelve: '1/2/2011':
             let fecha_creacion = dia + "-" + mes + "-" + anio;
             let hora_creacion = hora+":"+minutos;
            return $.trim(data["created_at"]) === "0000-00-00" ? "---" : `${fecha_creacion} ${hora_creacion} `;
          },
           title: "CREACIÓN",
           className: "text-center"
         },
         {
           data: null,
           render: function (data, type, full, meta) {
            return data["name"]+" "+data["surname"];
           },
           title: " IT",
           className: "text-center"
         },
         {
            data: "user",
            title: "USUARIO",
            className: "text-center"
          },
         {
           data: "departament",
           title: "DEPARTAMENTO",
           className: "text-center"
         },
         {
            data: "activity",
            title: "ACTIVIDAD"
          },
         
         {
           data: null,
           title: "ACCIONES",
           className: "text-center"
         },
       ],
       destroy: "true",
       columnDefs: [
         {
           targets: 6,
           render: function (data, type, full, meta) {
             return ` <div class=" mr-auto">
                       <a href="${urls}sistemas/ver-actividades/${$.md5(key + data["id_ticket"])}" title="Ver Actividad" target="_blank" class="btn btn-info btn-sm">
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
         $(row).attr("id", "activity_" + data.id_folio);
       },
     })
     .DataTable();
     $('#tabla_todas_actividades thead').addClass('thead-dark text-center');

     tbl_activitys = $("#tabla_actividades_it")
     .dataTable({
       processing: true,
       ajax: {
         method: "post",
         url: urls + "sistemas/todos_tickets_it",
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
           title: "Mis Tickets",
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
          data: "id_activity",
          title: " FOLIO",
          className: "text-center"
        },
         {
           data: null,
           render: function (data, type, full, meta) {
            var objFechaCreacion= new Date(data["created_at"]);
                var dia = (objFechaCreacion.getDate()).toString().padStart(2, "0");
                var mes = (objFechaCreacion.getMonth()+1).toString().padStart(2, "0");
               var anio = objFechaCreacion.getFullYear();
               var hora = (objFechaCreacion.getHours()).toString().padStart(2, "0");
            var minutos = (objFechaCreacion.getMinutes()).toString().padStart(2, "0");;
             // Devuelve: '1/2/2011':
             let fecha_creacion = dia + "-" + mes + "-" + anio;
             let hora_creacion = hora+":"+minutos;
            return $.trim(data["created_at"]) === "0000-00-00" ? "---" : `${fecha_creacion} ${hora_creacion} `;
          },
           title: "CREACIÓN",
           className: "text-center"
         },
         {
           data: "user",
           title: " USUARIO",
           className: "text-center"
         },
         {
           data: "departament",
           title: "DEPARTAMENTO",
           className: "text-center"
         },
         {
            data: "activity",
            title: "ACTIVIDAD"
          },
         
         {
           data: null,
           title: "ACCIONES",
           className: "text-center"
         },
       ],
       destroy: "true",
       columnDefs: [
         {
           targets: 5,
           render: function (data, type, full, meta) {
             return ` <div class=" mr-auto">
                       <a href="${urls}sistemas/ver-actividad/${$.md5(key + data["id_activity"])}" title="Ver Actividad" target="_blank" class="btn btn-info btn-sm">
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
         $(row).attr("id", "activity_" + data.id_activity);
       },
     })
     .DataTable();
     $('#tabla_actividades_it thead').addClass('thead-dark text-center');
 });
