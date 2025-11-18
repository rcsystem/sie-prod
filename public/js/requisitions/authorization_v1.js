/**
 * ARCHIVO MODULO REQUISICIONES
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
$(document).ready(function () {
  tbl_requisitions = $("#tabla_autorizar_requisiciones")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: urls + "requisiciones/autorizar",
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
            columns: [0, 1, 2, 3],
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
          data: "id_folio",
          title: "FOLIO",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            var objFechaCreacion= new Date(data["fecha_creacion"]);
            var dia = (objFechaCreacion.getDate()).toString().padStart(2, "0");
            var mes = (objFechaCreacion.getMonth() + 1).toString().padStart(2, "0");
            var anio = objFechaCreacion.getFullYear();
            var hora = objFechaCreacion.getHours();
            var minutos = objFechaCreacion.getMinutes();
             // Devuelve: '1/2/2011':
             let fecha_creacion = dia + "-" + mes + "-" + anio;
             let hora_creacion = hora+":"+minutos;
            return $.trim(data["fecha_creacion"]) === "0000-00-00"
              ? "---"
              : ` <div class="mr-auto">
                      ${fecha_creacion} 
                      ${hora_creacion}
                    </div> `;
          },
          title: "FECHA CREACIÓN",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            return data["name"].toUpperCase() + " " + data["surname"].toUpperCase();
          },
          title: "USUARIO",
          className: "text-center",
        },
        {
          data: "departament",
          title: "DEPARTAMENTO",
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
          title: "ACCIONES",
          className: "text-center",
        },
      ],
      destroy: "true",
      columnDefs: [
        {
          targets: 5,
          render: function (data, type, full, meta) {
            if (data["estatus"] != "Pendiente") {
              return `<div class=" mr-auto">
              
                 <a href="javascript:void(0);" class="btn btn-outline-info btn-sm" data-toggle="modal" data-target="#pdfModal" data-url="${urls}requisiciones/ver-requisicion/${$.md5(
              key + data["id_folio"])}">
            <i class="fas fa-eye"></i>
          </a>
                <button type="button" class="btn btn-secondary btn-sm" title="Autorizar Requisiciones">
                      <i class="fas fa-user-check"></i>
                </button>
                
              </div> `;
            } else {
              return `<div class=" mr-auto">
               <a href="javascript:void(0);" class="btn btn-outline-info btn-sm" data-toggle="modal" data-target="#pdfModal" data-url="${urls}requisiciones/ver-requisicion/${$.md5(
              key + data["id_folio"])}">
            <i class="fas fa-eye"></i>
          </a>
             <button type="button" class="btn btn-primary btn-sm" title="Autorizar Requisiciones"  onClick=handleChange(${data["id_folio"]})>
                   <i class="fas fa-user-check"></i>
             </button>
           
           </div> `;
            }
          },
        },
        /* 
        <a href="${urls}requisiciones/ver-requisicion/${$.md5(
                key + data["id_folio"]
              )}" title="Ver Requisición" target="_blank" class="btn btn-info btn-sm">
                      <i class="fas fa-eye"></i>
                </a>
        {
           targets: [0],
           visible: false,
           searchable: false,
         }, */
      ],

      order: [[0, "DESC"]],

      createdRow: (row, data) => {
        $(row).attr("id", "request_" + data.id_folio);
      },
    })
    .DataTable();
     $('#tabla_autorizar_requisiciones thead').addClass('thead-dark text-center');
});

function handleChange(id_folio) {
  let data = new FormData();

  data.append("id_folio", id_folio);

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: urls + "requisiciones/editar_item", //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    async: true,
    dataType: "json",
    success: function (resp) {
      console.log(resp.name);
      //console.log(resp);
      if (resp != "error") {
        resp.forEach(function (persona, index) {
          $("#usuario").val(persona.name + " " + persona.surname);
          if (persona.personas_requeridas == 1) {
            $("#personas_requeridas").val(
              persona.personas_requeridas + " Persona"
            );
          } else {
            $("#personas_requeridas").val(persona.personas_requeridas + " Personas");
          }
          $("#puesto_solicitado").val(persona.puesto_solicitado);
          $("#motivo").val(persona.motivo_requisicion);
        });
        $("#id_folio").val(id_folio);

        $("#autorizarModal").modal("show");
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

$("#autorizar_requisicion").submit(function (e) {
  e.preventDefault();
  let data = new FormData();
  let estatus = $("#estatus").val();
  data.append("id_folio", $("#id_folio").val());
  data.append("estatus", estatus);

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: urls + "requisiciones/autorizar-item", //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    success: function (response) {
      //una vez que el archivo recibe el request lo procesa y lo devuelve
      console.log(response);
      $("#autorizarModal").modal("toggle");
      /*codigo que borra todos los campos del form newProvider*/
      if(response != "error"){
        Swal.fire("!La Requisición a sido Actualizada !", "", "success");
        tbl_requisitions.ajax.reload(null, false);
      
      }else{
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
      console.log("Mal Revisa entro en el error: "+ error);
    },
  });
});

  $("#pdfModal").on("show.bs.modal", function (event) {
    $(this).find("#carga_pdf").attr("src", ""); // Limpiar el iframe
    var button = $(event.relatedTarget); // Botón que activó el modal
    var url = button.data("url"); // Extrae la URL del atributo data-url
    var modal = $(this);

    if (url) {
      modal.find("#carga_pdf").attr("src", url); // Inserta la URL en el iframe
    } else {
      console.error("URL no encontrada o inválida.");
    }
  });

