/**
 * ARCHIVO MODULO PAPELERIA
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
$(document).ready(function () {
  tbl_transfers = $("#tabla_transferencias")
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
          render: function (data, type, full, meta) {
           
            if (data["type_transfer"] !=null) {
              return `<span class="badge badge-info">${data["type_transfer"]}</span>`;
            } else {
              return "----";
            }
          },
          title: "TIPO",
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
          title: "ACCIONES",
          className: "text-center",
        },
      ],
      destroy: "true",
      columnDefs: [
        {
          targets: 5,
          render: function (data, type, full, meta) {
            return ` <div class="pull-right mr-auto">
             <div class=" mr-auto">
             <a href="${urls}almacen/ver-transferencia/${$.md5(
              key + data["id_vouchers"]
            )}" title="Ver Transferencia" target="_blank" class="btn btn-danger btn-sm">
                   
                   <i class="fas fa-file-pdf"></i> PDF
             </a>
             <a href="${urls}almacen/generar_reportes/${
              data["id_vouchers"]
            }" title="Descargar Excel" target="_blank" class="btn btn-success btn-sm">
                      <i class="fas fa-file-excel"></i> Excel
                      </a>
           </div>                     
                     </div> `;
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
        $(row).attr("id", "material_" + data.id_mp);
      },
    })
    .DataTable();
  $("#tabla_transferencias thead").addClass("thead-dark text-center");
});

/* Ponemos evento blur a la escucha sobre id nombre en id cliente. */
$("#buscador").on("click", "#btnBuscarCodigo", function () {
  /* Obtenemos el valor del campo */
  let codigo = $("#buscar_codigo").val();
  console.log(`mi_valor: ${codigo}`);

  let startDate = new Date($("#desde").val());
  let endDate = new Date($("#hasta").val());

  if (startDate > endDate) {
    $("#error_fechas")
      .append(`<div class="alert alert-warning alert-dismissible" role="alert" >
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">×</span>
    </button>
    <strong>Error en el Rango de Fechas...</strong>
  </div>`);
    setTimeout(function () {
      $(".alert")
        .fadeTo(1000, 0)
        .slideUp(800, function () {
          $(this).remove();
        });
    }, 3000);
    return false;
  }

  let data = new FormData();
  let event1 = new Date(startDate);
  let event2 = new Date(endDate);

  let desde1 = JSON.stringify(event1);
  let hasta1 = JSON.stringify(event2);
  let desde = desde1.slice(1, 11);
  let hasta = hasta1.slice(1, 11);
  data.append("codigo", codigo);
  data.append("desde", desde);
  data.append("hasta", hasta);

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}almacen/buscar_codigo`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    dataType: "json",
    success: function (resp) {
      console.log(resp);

      $("#resultado_busqueda").empty();

      // Limpiamos el select
      $.each(resp, function (id, value) {
        let envio = "";
        switch (value.departures) {
          case "1":
            envio = "Nave_1";
            break;
          case "2":
            envio = "Nave_4";
            break;
          case "3":
            envio = "Nave_3";
            break;
          case "4":
            envio = "VillaHermosa";
            break;
          case "5":
            envio = "Century";
            break;

          default:
            envio = "Error";
            break;
        }
        let usuario_genera = `${value.name} ${value.surname}`;
        $("#resultado_busqueda").append(
          `
              <tr>
                 <td>${value.id_vouchers}</td>
                 <td>${value.created_at}</td>
                 <td>${value.code}</td>
                 <td>${value.amount}</td>
                 <td>${value.weight}</td>
                 <td>${envio}</td>
                 <td>${usuario_genera}</td>
                 <td><div class="pull-right mr-auto">
                    <div class=" mr-auto">
                      <a href="${urls}almacen/ver-transferencia/${$.md5(
            key + value.id_vouchers
          )}" title="Ver Transferencia" target="_blank" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                      </a>                      
                    </div>                      
                </td>
              </tr>`
        );
      });
    },
    error: function () {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ocurrio un error en el servidor! Contactar con el Administrador",
      });
    },
  });
});

document.getElementById("btnExportar").addEventListener("click", function () {
  let table2excel = new Table2Excel();
  table2excel.export(document.querySelectorAll("#tabla"));
});
