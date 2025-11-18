/**
 * ARCHIVO MODULO CAFETERIA
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */

$(document).ready(function () {
  tbl_requisitions = $("#mis_solicitudes_cafeteria")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}cafeteria/mis-solicitudes`,
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
          data: null,
          render: function (data, type, full, meta) {
            switch (data["meeting_room"]) {
              case '1':
                $sala = "Sala de Consejo";
                break;
              case '2':
                $sala = "Sala de Operaciones";
                break;
              case '3':
                $sala = "Sala de Ingenieria";
                break;
              case '4':
                $sala = "Sala James Walworth";
                break;
              case '5':
                $sala = "Sala de Logistica";
                break;
              case '6':
                $sala = "Sala de Ventas";
                break;
              case '7':
                $sala = "Sala de Calidad";
                break;
              case '8':
                $sala = "Mezzanine (Nave 3)";
                break;

              default:
                $sala = "Error no se Selecciono Sala";
                break;
            }
            return $sala;
          },

          title: "USUARIO",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            return data["date"] == "0000-00-00"
              ? "---"
              : ` <div class="mr-auto">
                      ${data["date"]} |
                      ${data["horario"]}
                    </div> `;
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
                  return `<span class="badge badge-info">Autoriza Talento</span>`;
                  break;
                  case "7":
                return `<span class="badge badge-primary">Rechaza Talento</span>`;
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
          targets: 5,
          render: function (data, type, full, meta) {
            return ` <div class="mr-auto">
              <a href="${urls}cafeteria/ver-solicitudes/${$.md5(key + data["id_coffee"])}" target="_blank" class="btn btn-info btn-sm">
                <i class="fas fa-eye"></i>
              </a>
              <button type="button" class="btn btn-danger btn-sm" title="Cancelar Solicitud" onclick="Cancel(${data["id_coffee"]},'${data["date"]}','${data["horario"]}')">
                <i class="fas fa-times"></i>
              </button>
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
        $(row).attr("id", "request_" + data.id_coffee);
      },
    })
    .DataTable();
  $("#mis_solicitudes_cafeteria thead").addClass("thead-dark text-center");
});
var error_razon = "";

function Cancel(id, fecha,hora) {
  // let fecha = $(`#request_${id} td`)[3].innerHTML;
  let sala = $(`#request_${id} td`)[2].innerHTML;
  $("#folio").val("");
  $("#fecha").val("");
  $("#sala").val("");
  $("#razon").val("");
  error_razon = "";

  $("#fecha").val(fecha+" | "+hora);
  $("#sala").val(sala);
  $("#folio").val(id);

  $("#cancelarModal").modal("show");
}

$("#cancelar").submit(function (e) {
  e.preventDefault();
  if ($("#razon").val().length == 0) {
    error_razon = "Campo Requerido";
    $("#razon").addClass('has-error');
    $("#error_rezon").text(error_razon);
    return false;
  } else {
    error_razon = "";
    $("#razon").removeClass('has-error');
    $("#error_rezon").text(error_razon);    
  }

  $("#btn_cancelar").prop("disabled",true);
  let data = new FormData();

  data.append("folio", $("#folio").val());
  data.append("razon", $("#razon").val());

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}cafeteria/cancelar`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    success: function (response) {
      //una vez que el archivo recibe el request lo procesa y lo devuelve
      console.log(response);
      /*codigo que borra todos los campos del form newProvider*/

      if (response) {
        setTimeout(function () {
          tbl_requisitions.ajax.reload(null, false);
        }, 100);
        $("#btn_cancelar").prop("disabled", false);
        $("#cancelarModal").modal("toggle");
        Swal.fire("!Solicitud Cancelada con Exito!", "", "success");
      } else {
        $("#btn_cancelar").prop("disabled", false);
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
  });



})
