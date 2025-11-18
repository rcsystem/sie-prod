/**
 * ARCHIVO MODULO CARS
 * AUTOR: HORUS SAMAEL RIVAS PEDRAZA
 * horus.riv.ped@gmail.com
 * CEL:5624392632
 */

$(document).ready(function () {
  tbl_requisitions = $("#tabla_autorizar")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}autos/todas-solicitudes`,
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
          title: "Solicitud de Vehiculo",
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
          data: "id_request",
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
            switch (data["type_trip"]) {
              case '1':
                viaje = "VIAJE CORTO";
                break;
              case '2':
                viaje = "VIAJE PROLONGADO";
                break;
              default:
                viaje = "Error";
                break;
            }
            return viaje;
          },
          title: "TIPO DE VIAJE",
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
                return `<span class="badge badge-info">Autorizada</span>`;
                break;
              case "3":
                return `<span class="badge badge-danger">Rechazada</span>`;
                break;
              case "4":
                return `<span class="badge badge-success">Asignado</span>`;
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
          targets: 5,
          render: function (data, type, full, meta) {
            clase = (data["status"] == 1) ? "primary" : "secondary";
            onclic = (data["status"] == 1) ? `onclick=Edit(${data["id_request"]},${data["type_trip"]})` : "";
            return ` <div class="mr-auto">
             <button type="button" class="btn btn-${clase} btn-sm" title="Editar Solicitud" ${onclic} >
                 <i class="far fa-edit"></i>
             </button>
            <a href="${urls}autos/ver-solicitudes/${$.md5(key + data["id_request"])}" target="_blank" title="Ver Solicitud" class="btn btn-info btn-sm">
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
        $(row).attr("id", "request_" + data.id_request);
      },
    })
    .DataTable();
  $("#tabla_autorizar thead").addClass("thead-dark text-center");
});

function Edit(id_request, type_trip) {
  //console.log("Hola Mundo Entrada" + id_product);
  let usuario = $(`#request_${id_request} td`)[2].innerHTML;
  let viaje = $(`#request_${id_request} td`)[3].innerHTML;
 
  $("#folio").val("");
  $("#usuario").val("");
  $("#viaje").val("");
  $("#tipo").val("");
  $("#datos_viaje").empty();
  error_estado = "";
  $("#error_estado").text(error_estado);
  $("#estado").removeClass("has-error");

  $("#folio").val(id_request);
  $("#usuario").val(usuario);
  // $("#modelo").val(modelo);
  $("#viaje").val(viaje);
  $("#tipo").val(type_trip);

  $("#vehiculo_Modal").modal("show");
}



$("#solicitud_de_vehiculo").submit(function (event) {
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

  $("autorizar_cars").prop("disabled", true);
  let data = new FormData();

  data.append("id_folio", $("#folio").val());
  data.append("estado", $("#estado").val());
  data.append("tipo", $("#tipo").val());

  $.ajax({
    data: data,
    url: `${urls}autos/autorisar_jefe`,
    type: "post",
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (response) {
      console.log(response);
      if (response == true) {
        setTimeout(function () {
          tbl_requisitions.ajax.reload(null, false);
        }, 100);
        $("autorizar_cars").prop("disabled", false);
        $("#vehiculo_Modal").modal("toggle");
        Swal.fire("!Los datos se han Actualizado!", "", "success");
      }
      else if (response != false && response != true) {
        $("autorizar_cars").prop("disabled", false);
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: ` El permiso a Autorizar tiene conflicto con el permiso FOLIO: ${response}`,
        });
      }
      else {
        $("autorizar_cars").prop("disabled", false);
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
      $("#autorizar_cars").prop("disabled", false);
    } else if (jqXHR.status == 404) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No se encontró la página solicitada [404]",
      });
      $("#autorizar_cars").prop("disabled", false);
    } else if (jqXHR.status == 500) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Internal Server Error [500]",
      });
      $("#autorizar_cars").prop("disabled", false);
    } else if (textStatus === "parsererror") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Error de análisis JSON solicitado.",
      });
      $("#autorizar_cars").prop("disabled", false);
    } else if (textStatus === "timeout") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Time out error.",
      });
      $("#autorizar_cars").prop("disabled", false);
    } else if (textStatus === "abort") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ajax request aborted.",
      });

      $("#autorizar_cars").prop("disabled", false);
    } else {
      alert("Uncaught Error: " + jqXHR.responseText);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: `Uncaught Error: ${jqXHR.responseText}`,
      });
      $("#autorizar_cars").prop("disabled", false);
    }
  });
});
