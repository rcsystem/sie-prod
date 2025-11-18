/**
 * ARCHIVO MODULO CARS
 * AUTOR: HORUS SAMAEL RIVAS PEDRAZA
 * horus.riv.ped@gmail.com
 * CEL:5624392632
 */

$(document).ready(function () {
  tbl_requisitions = $("#tabla_solicitudes")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}autos/todas-aprobadas`,
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
          title: "Solicitud de Vehiculo",
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5],
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
            return ` <div class="mr-auto">
             <button type="button" class="btn btn-primary btn-sm" title="Editar Solicitud" onclick="Edit(${data["id_request"]},${data["type_trip"]})">
                 <i class="far fa-edit"></i>
             </button>
                        <a href="${urls}autos/ver-solicitudes/${$.md5(key + data["id_request"])}" title="Ver Solicitud" target="_blank" class="btn btn-info btn-sm">
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
  $("#tabla_solicitudes thead").addClass("thead-dark text-center");
});

function Edit(id_request, type_trip) {
  let usuario = $(`#request_${id_request} td`)[2].innerHTML;
  let viaje = $(`#request_${id_request} td`)[3].innerHTML;
  $("#estado").removeClass("has-error");
  $("#auto").removeClass("has-error");
  $("#observacion").removeClass("has-error");
  $("#error_estado").text("");
  $("#error_auto").text("");
  $("#error_observacion").text("");
  $("#folio").val("");
  $("#usuario").val("");
  $("#modelo").val("");
  $("#viaje").val("");
  $("#tipo").val("");
  $("#datos_viaje").empty();
  $("#folio").val(id_request);
  $("#usuario").val(usuario);
  $("#viaje").val(viaje);
  $("#tipo").val(type_trip);

  $("#vehiculo_Modal").modal("show");
}

function placas() {
  $("#placasDiv").empty();
  if ($.trim($("#auto").val()).length > 0) {
    $("#error_auto").text("");
    $("#auto").removeClass("has-error");
    let data = new FormData();
    data.append('id_car', $("#auto").val());
    $.ajax({
      data: data,
      url: `${urls}autos/datos_autos_id`,
      type: "post",
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (dataCar) {
        if (dataCar != false) {
          $("#placasDiv").append(`
  <b>Placas </b><input class="form-control" style="margin-top:5px;" type="text" id="placasCar" value="${dataCar.placa}" disabled>
  `);
        } else {

        }
      }
    });

  }
}

function validar() {
  if ($.trim($("#estado").val()).length > 0) {
    $("#error_estado").text("");
    $("#estado").removeClass("has-error");

  }
  if ($.trim($("#observacion").val()).length > 0) {
    $("#error_observacion").text("");
    $("#observacion").removeClass("has-error");
  }
}

$("#tipo_reportes").on("change", function (e) {
  e.preventDefault();
  $("#parametro").empty();
  $("#parametro").attr('style')
  if ($("#tipo_reportes").val() == 2) {
    $("#parametro").append(`
    <label for="vehiculo">Vehiculo</label>
    <select id="vehiculo" class="form-control rounded-0" required>
      <option value="">Selecciona una Opcion...</option>
    </select>`);
    $.ajax({
      url: `${urls}autos/informacion`,
      type: "post",
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (data) {
        console.log(data);
        if (data != false) {
          data.forEach(car => {
            $("#vehiculo").append(`<option value="${car.id_car}">${car.model} - ${car.placa}</option>`);
          });
        }
        else {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Algo salió Mal! Contactar con el Administrador",
          });
        }
      }
    }).fail(function (jqXHR, textStatus, errorThrown) {
      if (jqXHR.status === 0) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Fallo de conexión: ​​Verifique la red.",
        });
      } else if (jqXHR.status == 404) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "No se encontró la página solicitada [404]",
        });
      } else if (jqXHR.status == 500) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Internal Server Error [500]",
        });
      } else if (textStatus === "parsererror") {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Error de análisis JSON solicitado.",
        });
      } else if (textStatus === "timeout") {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Time out error.",
        });
      } else if (textStatus === "abort") {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Ajax request aborted.",
        });

      } else {
        alert("Uncaught Error: " + jqXHR.responseText);
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: `Uncaught Error: ${jqXHR.responseText}`,
        });
      }
    });
  }
})

$("#form_reportes").submit(function (e) {
  e.preventDefault();
  if ($("#fecha_inicial").val() > $("#fecha_final").val()) {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Error en las Fechas",
    });
    return false;
  }
  $("#btn_reportes").prop("disabled", true);
  let timerInterval = Swal.fire({ //se le asigna un nombre al swal
    allowOutsideClick: false,
    title: '¡Generando Reporte!',
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });
  let fecha_inicio = $("#fecha_inicial").val();
  let fecha_fin = $("#fecha_final").val();
  let categoria = $("#tipo_reportes").val();
  let id_car = (categoria == 2) ? $("#vehiculo").val() : "";
  var nomArchivo = `Reporte_Vehiculo_${fecha_inicio}_${fecha_fin}.xlsx`;
  var param = JSON.stringify({
    star_date: fecha_inicio,
    end_date: fecha_fin,
    type: categoria,
    id_car: id_car,
  });
  var pathservicehost = `${urls}/autos/reporte_vehiculos`;

  var xhr = new XMLHttpRequest();
  xhr.open("POST", pathservicehost, true);
  xhr.responseType = "blob";

  //Send the proper header information along with the request
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onload = function (e) {
    Swal.close(timerInterval);
    $("#btn_reportes").prop("disabled", false);
    if (xhr.readyState === 4 && xhr.status === 200) {
      $("#fecha_inicial").val("");
      $("#fecha_final").val("");
      $("#tipo_reportes").val(1);
      $("#parametro").empty();
      var contenidoEnBlob = xhr.response;
      var link = document.createElement("a");
      link.href = (window.URL || window.webkitURL).createObjectURL(
        contenidoEnBlob
      );
      link.download = nomArchivo;
      var clicEvent = new MouseEvent("click", {
        view: window,
        bubbles: true,
        cancelable: true,
      });
      //Simulamos un clic del usuario
      //no es necesario agregar el link al DOM.
      link.dispatchEvent(clicEvent);
      //link.click();
    } else {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No es posible acceder al archivo, probablemente no existe.",
      });
    }
  };
  xhr.send("data=" + param);
})

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
  if ($.trim($("#estado").val()) == 4) {
    if ($.trim($("#auto").val()).length == 0) {
      var error_auto = "El campo es requerido";
      $("#error_auto").text(error_auto);
      $("#auto").addClass("has-error");
    } else {
      error_auto = "";
      $("#error_auto").text(error_auto);
      $("#auto").removeClass("has-error");
    }
  } else {
    error_auto = "";
  }

  if ($.trim($("#observacion").val()).length == 0) {
    error_observacion = "El campo es requerido";
    $("#error_observacion").text(error_observacion);
    $("#observacion").addClass("has-error");
  } else {
    error_observacion = "";
    $("#error_observacion").text(error_observacion);
    $("#observacion").removeClass("has-error");
  }

  if (error_estado != "" || error_auto != "" || error_observacion != "") {
    return false;
  }

  $("autorizar_cars").prop("disabled", true);
  let data = new FormData();

  data.append("id_folio", $("#folio").val());
  data.append("estado", $("#estado").val());
  data.append("tipo", $("#tipo").val());
  data.append("observacion", $("#observacion").val());
  data.append("id_cars", $("#auto").val());
  $.ajax({
    data: data,
    url: `${urls}autos/autorisar`,
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
