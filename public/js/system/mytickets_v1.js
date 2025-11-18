/**
 * ARCHIVO MODULO SISTEMAS
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EDITOR: HORUS SAMAEL RIVAS PEDRAZA
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
 $(document).ready(function () {
   tbl_activitys = $("#tabla_tickets_usuarios")
     .dataTable({
       processing: true,
       ajax: {
         method: "post",
         url: urls + "sistemas/mis_tickets",
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
           data: "id_ticket",
           title: "FOLIO",
           className: "text-center"
         },
         {
           data: null,
           render: function (data, type, full, meta) {
             var objFechaCreacion = new Date(data["created_at"]);
             var dia = (objFechaCreacion.getDate()).toString().padStart(2, "0");
             var mes = (objFechaCreacion.getMonth() + 1).toString().padStart(2, "0");
             var anio = objFechaCreacion.getFullYear();
             var hora = (objFechaCreacion.getHours()).toString().padStart(2, "0");
             var minutos = (objFechaCreacion.getMinutes()).toString().padStart(2, "0");
             // Devuelve: '1/2/2011':
             let fecha_creacion = dia + "-" + mes + "-" + anio;
             let hora_creacion = hora + ":" + minutos;
             return $.trim(data["created_at"]) === "0000-00-00" ? "---" : `${fecha_creacion} ${hora_creacion} `;
           },
           title: "CREACIÓN",
           className: "text-center"
         },
         {
           data: null,
           render: function (data, type, full, meta) {
             return data["name"] + " " + data["surname"];
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
                        <a href="${urls}sistemas/ver-actividades/${$.md5(key + data["id_ticket"])}" title="Ver Ticket" target="_blank" class="btn btn-info btn-sm">
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
         $(row).attr("id", "activity_" + data.id_ticket);
       },
     })
     .DataTable();
   $('#tabla_tickets_usuarios thead').addClass('thead-dark text-center');
 
   /*se carga informacion de la talba actidades IT*/
   tbl_activitys = $("#tabla_tickets_actividades")
     .dataTable({
       processing: true,
       ajax: {
         method: "post",
         url: urls + "sistemas/mis_actividades",
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
           title: "Mis Actividades",
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
           title: "FOLIO",
           className: "text-center"
         },
         {
           data: null,
           render: function (data, type, full, meta) {
             var objFechaCreacion = new Date(data["created_at"]);
             var dia = (objFechaCreacion.getDate()).toString().padStart(2, "0");
             var mes = (objFechaCreacion.getMonth() + 1).toString().padStart(2, "0");
             var anio = objFechaCreacion.getFullYear();
             var hora = (objFechaCreacion.getHours()).toString().padStart(2, "0");
             var minutos = (objFechaCreacion.getMinutes()).toString().padStart(2, "0");
             // Devuelve: '1/2/2011':
             let fecha_creacion = dia + "-" + mes + "-" + anio;
             let hora_creacion = hora + ":" + minutos;
             return $.trim(data["created_at"]) === "0000-00-00" ? "---" : `${fecha_creacion} ${hora_creacion} `;
           },
           title: "CREACIÓN",
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
           targets: 5,
           render: function (data, type, full, meta) {
             return ` <div class=" mr-auto">
              <button type="button" class="btn btn-primary btn-sm"
              onClick=ActualizarActivity(${data["id_activity"]})>
              <i class="far fa-edit"></i>
              </button>
              
              <a href="${urls}sistemas/ver-actividad/${$.md5(key + data["id_activity"])}" title="Ver Actividad" target="_blank" class="btn btn-info btn-sm">
              <i class="fas fa-eye"></i>
              </a>
              
              <button class="btn btn-danger btn-sm"
              onClick=BorrarActivity(${data["id_activity"]}) >
              <i class="fas fa-trash-alt"></i>
              </button>
             </div>`;
           },
         },
         /*           {
                    targets: [3],
                    className:"text-center"
                  },  */
       ],
 
       order: [[0, "DESC"]],
 
       createdRow: (row, data) => {
         $(row).attr("id", "activitys_" + data.id_activity);
       },
     })
     .DataTable();
   $('#tabla_tickets_actividades thead').addClass('thead-dark text-center');
 });
 
 
 function BorrarActivity(id) {
   console.log(id);
   Swal.fire({
     title: `Deseas Eliminar el Ticket con Folio: ${id} ?`,
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
       dataForm.append("id", id);
 
       $.ajax({
         data: dataForm,
         url: `${urls}sistemas/borrar_activity`,
         type: "post",
         processData: false,
         contentType: false,
         dataType: 'json',
         success: function (response) {
           if (response) {
             tbl_activitys.ajax.reload(null, false);
 
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
 
 var error_actividad = "";
 function ActualizarActivity(id_activity) {
   //console.log("Hola Mundo Entrada" + id_product);
   let usuario = $(`#activitys_${id_activity} td`)[2].innerHTML;
   let actividad = $(`#activitys_${id_activity} td`)[4].innerHTML;
  /*  let viaje = $(`#request_${id_activity} td`)[3].innerHTML;
   let modelo = $(`#request_${id_activity} td`)[1].innerHTML; */
   $("#folio").val("");
   $("#usuario").val("");
   $("#actividad").val("");
   error_actividad = "";
   $("#error_actividad").text(error_actividad);
   $("#actividad").removeClass("has-error");
   $("#folio").val(id_activity);
   $("#usuario").val(usuario);
   $("#actividad").val(actividad);
   $("#actividad_Modal").modal("show");
 }
 
 function valida(){
   if($("#actividad").val().length > 0){
     error_actividad = "";
   $("#error_actividad").text(error_actividad);
   $("#actividad").removeClass("has-error");
   }
 }
 
 $("#actualizar_ticket").on("submit", function (e) {
   e.preventDefault();
   
   if ($("#actividad").val().length < 4) {
     error_actividad = "Campo Requerido ";
   $("#error_actividad").text(error_actividad);
   $("#actividad").addlass("has-error");
   }
   
   if (
     error_actividad != ""
   ) {
     return false;
   }
 
   $("#btn_actualizar_ticket").prop("disabled", true);
   
   var formData1 = new FormData();
   formData1.append("id", $("#folio").val());
   formData1.append("actividad", $("#actividad").val());
   
 
   $.ajax({
     type: "post",
     url: `${urls}sistemas/actualizar_activity`,
     cache: false,
     data: formData1,
     dataType: "json",
     contentType: false,
     processData: false,
 
     success: function (response) {
       if (response != "error") {
         setTimeout(function () {
           tbl_activitys.ajax.reload(null, false);
         }, 100);
         $('#actividad_Modal').modal('toggle');
         Swal.fire("!El Ticket se a Actualizado correctamente!", "", "success");
         $("#btn_actualizar_ticket").prop("disabled", false);
       } else {
         Swal.fire({
           icon: "error",
           title: "Oops...",
           text: "Algo salió Mal! Contactar con el Administrador",
         });
         $("#btn_actualizar_ticket").prop("disabled", false);
       }
     },
   }).fail(function (jqXHR, textStatus, errorThrown) {
     if (jqXHR.status === 0) {
       Swal.fire({
         icon: "error",
         title: "Oops...",
         text: "Fallo de conexión: ​​Verifique la red.",
       });
       $("#btn_actualizar_ticket").prop("disabled", false);
     } else if (jqXHR.status == 404) {
       Swal.fire({
         icon: "error",
         title: "Oops...",
         text: "No se encontró la página solicitada [404]",
       });
       $("#btn_actualizar_ticket").prop("disabled", false);
     } else if (jqXHR.status == 500) {
       Swal.fire({
         icon: "error",
         title: "Oops...",
         text: "Internal Server Error [500]",
       });
       $("#btn_actualizar_ticket").prop("disabled", false);
     } else if (textStatus === "parsererror") {
       Swal.fire({
         icon: "error",
         title: "Oops...",
         text: "Error de análisis JSON solicitado.",
       });
       $("#btn_actualizar_ticket").prop("disabled", false);
     } else if (textStatus === "timeout") {
       Swal.fire({
         icon: "error",
         title: "Oops...",
         text: "Time out error.",
       });
       $("#btn_actualizar_ticket").prop("disabled", false);
     } else if (textStatus === "abort") {
       Swal.fire({
         icon: "error",
         title: "Oops...",
         text: "Ajax request aborted.",
       });
 
       $("#btn_actualizar_ticket").prop("disabled", false);
     } else {
       alert("Uncaught Error: " + jqXHR.responseText);
       Swal.fire({
         icon: "error",
         title: "Oops...",
         text: `Uncaught Error: ${jqXHR.responseText}`,
       });
       $("#btn_actualizar_ticket").prop("disabled", false);
     }
   });
 });