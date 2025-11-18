/**
 * ARCHIVO MODULO PAPELERIA
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
$(document).ready(function () {
  tbl_transfers = $("#tabla_autorizar_transferencias")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}almacen/listado_transferencias`,
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
          title: "Listado de Material",
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
          data: "id_vouchers",
          title: "FOLIO",
          className: "text-center",
        },

        {
          data: null,
          render: function (data, type, full, meta) {
            return data["name"] + " " + data["surname"];
          },
          title: "USUARIO",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            if (data["addressee"] != "") {
              switch (data["addressee"]) {
                case "1":
                  return "NAVE 1";
                  break;
                case "2":
                  return "NAVE 4";
                  break;
                  case "3":
                  return "NAVE 3";
                  break;
                  case "4":
                  return "VILLAHERMOSA";
                  break;
                  case "5":
                  return "CENTURY";
                  break;

                default:
                  return "Error";
                  break;
              }
            } else {
              return "----";
            }
          },
          title: "ORIGEN",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            if (data["departures"] != "") {
              switch (data["departures"]) {
                case "1":
                  return "NAVE 1";
                  break;
                case "2":
                  return "NAVE 4";
                  break;
                  case "3":
                  return "NAVE 3";
                  break;
                  case "4":
                  return "VILLAHERMOSA";
                  break;
                  case "5":
                  return "CENTURY";
                  break;

                default:
                  return "Error";
                  break;
              }
            } else {
              return "----";
            }
          },
          title: "DESTINO",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            switch (data["estatus"]) {
              case "1":
                return `<span class="badge badge-warning">Pendiente</span>`;
                break;
              case "2":
                return `<span class="badge badge-success">Autorizada</span>`;
                break;
              case "3":
                return `<span class="badge badge-danger">Rechazada</span>`;
                break;

              default:
                return `<span class="badge badge-warning">Error</span>`;
                break;
            }
          },
          title: "ESTATUS",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            var objFechaCreacion = new Date(data["created_at"]);
            var dia = objFechaCreacion.getDate().toString().padStart(2, "0");
            var mes = (objFechaCreacion.getMonth() + 1)
              .toString()
              .padStart(2, "0");
            var anio = objFechaCreacion.getFullYear();
            var hora = objFechaCreacion.getHours().toString().padStart(2, "0");
            var minutos = objFechaCreacion
              .getMinutes()
              .toString()
              .padStart(2, "0");
            // Devuelve: '1/2/2011':
            let fecha_creacion = dia + "-" + mes + "-" + anio;
            let hora_creacion = hora + ":" + minutos;
            return $.trim(data["created_at"]) === "0000-00-00"
              ? "---"
              : `${fecha_creacion} ${hora_creacion} `;
          },

          title: "FECHA DE CREACION",
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
            let buttons =
              data["estatus"] == 1 ? "btn-success" : "btn-secondary";

            let autorizar =
              data["estatus"] == 1
                ? `<div class="dropdown-menu">
            <a style="cursor:pointer;" class="dropdown-item" onclick="Authorize(${data["id_vouchers"]})">Autorizar</a>
            <div class="dropdown-divider"></div>
            <a style="cursor:pointer;" class="dropdown-item" onclick="toRefuse(${data["id_vouchers"]})">Rechazar</a>
          </div>`
                : "";
            let option =
              data["estatus"] == 1
                ? `<button type="button" class="btn btn-sm ${buttons} dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-expanded="false">`
                : "";
            return ` <div class="pull-right mr-auto">
             <div class=" mr-auto">          

            <div class="btn-group">
              <button type="button" class="btn btn-sm ${buttons}"><i class="far fa-edit"></i></button>
              ${option}
                <span class="sr-only">Toggle Dropdown</span>
              </button>
              ${autorizar}
            </div>
              
           <a href="${urls}almacen/ver-transferencia/${$.md5(
              key + data["id_vouchers"]
            )}" title="Ver Transferencia" target="_blank" class="btn btn-info btn-sm">
                 <i class="fas fa-eye"></i>
           </a>                 
           </div>     </div> `;
          },
        },
        /* {
             targets: [0],
             visible: false,
             searchable: false,
           },  */
      ],

      order: [[0, "DESC"]],

      createdRow: (row, data) => {
        $(row).attr("id", "material_" + data.id_vouchers);
      },
    })
    .DataTable();
  $("#tabla_autorizar_transferencias thead").addClass("thead-dark text-center");
});

function toRefuse(id_folio) {
  Swal.fire({
    title: `Rechazar el translado del Folio: ${id_folio} ?`,
    text: `Una vez Rechazado no podras Cambiar Estatus!`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Rechazar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.value) {
      let dataForm = new FormData();
      dataForm.append("id_folio", id_folio);

      $.ajax({
        data: dataForm, //datos que se envian a traves de ajax
        url: `${urls}almacen/rechazar_transferencia`, //archivo que recibe la peticion
        type: "post", //método de envio
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentType
        success: function (response) {
          //una vez que el archivo recibe el request lo procesa y lo devuelve
          console.log(response);

          /*codigo que borra todos los campos del form newProvider*/
          if (response) {
            tbl_transfers.ajax.reload(null, false);
            Swal.fire("!Rechazado Correctamente!", "", "success");
          } else {
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
          $("#guardar_ticket").prop("disabled", false);
        } else if (jqXHR.status == 404) {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "No se encontró la página solicitada [404]",
          });
          $("#guardar_ticket").prop("disabled", false);
        } else if (jqXHR.status == 500) {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Internal Server Error [500]",
          });
          $("#guardar_ticket").prop("disabled", false);
        } else if (textStatus === "parsererror") {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Error de análisis JSON solicitado.",
          });
          $("#guardar_ticket").prop("disabled", false);
        } else if (textStatus === "timeout") {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Time out error.",
          });
          $("#guardar_ticket").prop("disabled", false);
        } else if (textStatus === "abort") {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Ajax request aborted.",
          });

          $("#guardar_ticket").prop("disabled", false);
        } else {
          alert("Uncaught Error: " + jqXHR.responseText);
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: `Uncaught Error: ${jqXHR.responseText}`,
          });
          $("#guardar_ticket").prop("disabled", false);
        }
      });
    }
  });
}

function Authorize(id_folio) {
  Swal.fire({
    title: `Autorizar el translado del Folio: ${id_folio} ?`,
    text: `Una vez Autorizado no podras Cancelar!`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Transferir",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.value) {
      let dataForm = new FormData();
      dataForm.append("id_folio", id_folio);

      $.ajax({
        data: dataForm, //datos que se envian a traves de ajax
        url: `${urls}almacen/autorizar_transferencia`, //archivo que recibe la peticion
        type: "post", //método de envio
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentType
        success: function (response) {
          //una vez que el archivo recibe el request lo procesa y lo devuelve
          console.log(response);

          /*codigo que borra todos los campos del form newProvider*/
          if (response) {
            tbl_transfers.ajax.reload(null, false);
            Swal.fire("!Autorizado correctamente!", "", "success");
          } else {
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
          $("#guardar_ticket").prop("disabled", false);
        } else if (jqXHR.status == 404) {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "No se encontró la página solicitada [404]",
          });
          $("#guardar_ticket").prop("disabled", false);
        } else if (jqXHR.status == 500) {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Internal Server Error [500]",
          });
          $("#guardar_ticket").prop("disabled", false);
        } else if (textStatus === "parsererror") {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Error de análisis JSON solicitado.",
          });
          $("#guardar_ticket").prop("disabled", false);
        } else if (textStatus === "timeout") {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Time out error.",
          });
          $("#guardar_ticket").prop("disabled", false);
        } else if (textStatus === "abort") {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Ajax request aborted.",
          });

          $("#guardar_ticket").prop("disabled", false);
        } else {
          alert("Uncaught Error: " + jqXHR.responseText);
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: `Uncaught Error: ${jqXHR.responseText}`,
          });
          $("#guardar_ticket").prop("disabled", false);
        }
      });
    }
  });
}
