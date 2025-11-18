/**
 * ARCHIVO MODULO COFFEE
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */

$(document).ready(function () {
  tbl_requisitions = $("#tabla_autorizar_james")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}cafeteria/solicitudes_james`,
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
          title: "Solicitud de Cafetería",
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5],
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
          data: "id_coffee",
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
          data: null,
          render: function (data) {
            //console.log(data["meeting_room"]);
            switch (data["meeting_room"]) {
             
              case '4':
                sala = "Sala James Walworth";
                break;
              

              default:
                sala = "Error";
                break;
            }
            return sala;
          },
          title: "SALA",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            return data["date"] == "0000-00-00"
              ? "---"
              : `${data["date"]} | ${data["horario"]}`;
          },
          title: "FECHA & HORARIO",
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
                return `<span class="badge badge-success">Autorizada</span>`;
                break;
              case "3":
                return `<span class="badge badge-danger">Rechazada</span>`;
                break;
              case "4":
                  return `<span class="badge badge-danger">Eliminada</span>`;
                  break;
              case "5":
                return `<span class="badge badge-warning">Pendiente</span>`;
                break;
              case "6":
                return `<span class="badge badge-success">Autorizada</span>`;
                break;
                case "7":
                return `<span class="badge badge-danger">Rechazada</span>`;
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
          title: "ACCIONES",
          className: "text-center",
        },
      ],
      destroy: "true",
      columnDefs: [
        {
          targets: 6,
          render: function (data, type, full, meta) {
            if (data["status"] != 4) { 
              return ` <div class="mr-auto">
              <button type="button" class="btn btn-primary btn-sm" title="Editar Suministro" onclick="Edit(${data["id_coffee"]})">
              <i class="far fa-edit"></i>
              </button>
              <a href="${urls}cafeteria/ver-solicitudes/${$.md5(key + data["id_coffee"])}" target="_blank" class="btn btn-info btn-sm">
              <i class="fas fa-eye"></i>
              </a>
              </div> `;
            }else{
              return "";
            }
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
        $(row).attr("id", "request_" + data.id_coffee);
      },
    })
    .DataTable();
  $("#tabla_autorizar_james thead").addClass("thead-dark text-center");
});

function Edit(id_coffee) {
  //console.log("Hola Mundo Entrada" + id_product);
  let usuario = $(`#request_${id_coffee} td`)[2].innerHTML;

  let fecha = $(`#request_${id_coffee} td`)[4].innerHTML;
  let sala = $(`#request_${id_coffee} td`)[3].innerHTML;
  //let fecha = $(`#request_${id_coffee} td`)[1].innerHTML;
  $("#estado").val("");
  $("#entrega").val("");

  $("#folio").val(id_coffee);
  $("#usuario").val(usuario);
  $("#fecha").val(fecha);
  $("#sala").val(sala);

  error_entrega = "";
  $("#error_entrega").text(error_entrega);
  $("#entrega").removeClass("has-error");
  error_estado = "";
  $("#error_estado").text(error_estado);
  $("#estado").removeClass("has-error");
  $("#obs").val("");
  $("#jamesModal").modal("show");
}



$("#respuesta_coffee").submit(function (event) {
  event.preventDefault();

  if ($.trim($("#estado").val()).length == 0) {
    var error_estado = "El campo es requerido";
    $("#error_estado").text(error_estado);
    $("#estado").addClass("has-error");
  } else {
    error_estado = "";
    $("#error_estado").text(error_estado);
    $("#estado").removeClass("has-error");
  }


  if (error_estado != "") {
    return false;
  }

  $("#res_coffee").prop("disabled", true);
  let data = new FormData();

  data.append("id_folio", $("#folio").val());
  data.append("estado", $("#estado").val());

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}cafeteria/request_estado`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    success: function (response) {
      //una vez que el archivo recibe el request lo procesa y lo devuelve
      console.log(response);
      /*codigo que borra todos los campos del form newProvider*/

      if (response != "error") {
        setTimeout(function () {
          tbl_requisitions.ajax.reload(null, false);
        }, 100);
        $("#res_coffee").prop("disabled", false);
        $("#jamesModal").modal("toggle");
        Swal.fire("!Los datos se han Actualizado!", "", "success");
      } else {
        $("#res_coffee").prop("disabled", false);
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
  }).fail(function (jqXHR, textStatus, errorThrown) {
    if (jqXHR.status === 0) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Fallo de conexión: ​​Verifique la red.",
      });
      $("#res_coffee").prop("disabled", false);
    } else if (jqXHR.status == 404) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No se encontró la página solicitada [404]",
      });
      $("#res_coffee").prop("disabled", false);
    } else if (jqXHR.status == 500) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Internal Server Error [500]",
      });
      $("#res_coffee").prop("disabled", false);
    } else if (textStatus === "parsererror") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Error de análisis JSON solicitado.",
      });
      $("#res_coffee").prop("disabled", false);
    } else if (textStatus === "timeout") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Time out error.",
      });
      $("#res_coffee").prop("disabled", false);
    } else if (textStatus === "abort") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ajax request aborted.",
      });

      $("#res_coffee").prop("disabled", false);
    } else {
      alert("Uncaught Error: " + jqXHR.responseText);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: `Uncaught Error: ${jqXHR.responseText}`,
      });
      $("#res_coffee").prop("disabled", false);
    }
  });
});

