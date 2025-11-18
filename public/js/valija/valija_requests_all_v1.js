/**
 * ARCHIVO MODULO VALIJAS SOLICITUDES
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
$(document).ready(function () {
  tbl_requisitions = $("#tabla_valija_todas_solicitudes")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}valija/todas_solicitudes`,
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
          title: "Solicitudes",
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 8],
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
          data: "id_valija",
          title: "FOLIO",
          className: "text-center"
        },
        {
          data: "created_at",
          title: "FECHA CREACIÓN",
          className: "text-center"
        },
        {
          data: "user_name",
          title: "USUARIO",
        },
        {
          data: "departament",
          title: "DEPARTAMENTO",
        },
        {
          data: "origin",
          title: "ORIGEN",
          className: "text-center"
        },
        {
          data: "destination",
          title: "DESTINO",
          className: "text-center"
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            switch (data["priority"]) {

              case "BAJA":
                return `<span class="badge badge-info">${data["priority"]}</span>`;
                break;

              case "NORMAL":
                return `<span class="badge badge-success">${data["priority"]}</span>`;
                break;

              case "INMEDIATA":
                return `<span class="badge badge-danger">${data["priority"]}</span>`;
                break;

              default:
                return `<span class="badge badge-warning">ERROR</span>`;
                break;
            }
          },
          title: "PRIORIDAD",
          className: "text-center"
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            switch (data["status"]) {

              case "1":
                return `<span class="badge badge-warning">Pendiente</span>`;
                break;

              case "2":
                return `<span class="badge badge-success">Concluido</span>`;
                break;

              case "3":
                return `<span class="badge badge-danger">Cancelado</span>`;
                break;

              default:
                return `<span class="badge badge-warning">ERROR</span>`;
                break;
            }
          },
          title: "ESTATUS",
          className: "text-center"
        },
        {
          data: "observation",
          title: "MOTIVO",
          className: "text-center"
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
          targets: 9,
          render: function (data, type, full, meta) {
            return ` <div class="mr-auto">
                  <button type="button" class="btn btn-primary btn-sm" title="Autorizar Permiso" onClick=handleChange(${data["id_valija"]})>
                    <i class="fas fa-user-check"></i>
                  </button>
                  <a href="${urls}valija/ver-solicitudes/${$.md5(key + data["id_valija"])}" target="_blank" class="btn btn-danger btn-sm">
                  <i class="fas fa-file-pdf"></i>
                  </a>
              </div> `;
          },
        },
        {
          targets: [8],
          visible: false,
          searchable: false,
        },
      ],

      order: [[0, "DESC"]],

      createdRow: (row, data) => {
        $(row).attr("id", "request_" + data.id_valija);
      },
    })
    .DataTable();
  $('#tabla_valija_todas_solicitudes thead').addClass('thead-dark text-center');
});

function handleChange(id_valija) {
  let data = new FormData();

  data.append("id_valija", id_valija);

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}valija/editar-valija`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    async: true,
    dataType: "json",
    success: function (resp) {
      console.log(resp);
      if (resp != false) {
        resp.forEach(function (valija, index) {
          $("#autorizarValijaModal").modal("show");
          $("#usuario_valija").val(valija.user_name);

          if (valija.origin == "OTRO") {
            $("#destino_valija").val(valija.another_origin);
          } else {
            $("#origen_valija").val(valija.origin);
          }

          if (valija.destination == "OTRO") {
            $("#destino_valija").val(valija.another_destination);
          } else {
            $("#destino_valija").val(valija.destination);
          }

          $("#observacion").val(valija.observation);
        });
        $("#id_valija").val(id_valija);
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
        console.log("Mal Revisa");
      }
    },
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

$("#autorizar_valija").submit(function (e) {
  e.preventDefault();
  let data = new FormData();

  let estatus = $("#estatus_valija").val();
  data.append("id_valija", $("#id_valija").val());
  data.append("estatus", estatus);

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}valija/autorizar-valija`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    success: function (response) {
      //una vez que el archivo recibe el request lo procesa y lo devuelve
      console.log(response);
      $("#autorizarValijaModal").modal("toggle");
      /*codigo que borra todos los campos del form newProvider*/
      if (response != false) {
        Swal.fire("!La Solicitud ha sido Actualizado !", "", "success");
        tbl_requisitions.ajax.reload(null, false);
      } else {
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
      console.log("Mal Revisa entro en el error: " + error);
    },
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
});

function validar() {
  if ($("#fecha_inicial").val().length > 0) {
    $("#error_fecha_inicial").text("");
    $("#fecha_inicial").removeClass('has-error');
  }
  if ($("#fecha_final").val().length > 0) {
    $("#error_fecha_final").text("");
    $("#fecha_final").removeClass('has-error');
  }
}

$("#formReportes").on("submit", function (e) {
  e.preventDefault();
  if ($("#fecha_inicial").val().length == 0) {
    error_fecha_inicial = "Fecha Inicial Requerida";
    $("#error_fecha_inicial").text(error_fecha_inicial);
    $("#fecha_inicial").addClass('has-error');
  } else {
    error_fecha_inicial = "";
    $("#error_fecha_inicial").text(error_fecha_inicial);
    $("#fecha_inicial").removeClass('has-error');
  }

  if ($("#fecha_final").val().length == 0) {
    error_fecha_final = "Fecha Final Requerida";
    $("#error_fecha_final").text(error_fecha_final);
    $("#fecha_final").addClass('has-error');
  } else if ($("#fecha_final").val() < $("#fecha_inicial").val()) {
    error_fecha_final = "Fecha Final Incorrecta";
    $("#error_fecha_final").text(error_fecha_final);
    $("#fecha_final").addClass('has-error');
  } else {
    error_fecha_final = "";
    $("#error_fecha_final").text(error_fecha_final);
    $("#fecha_final").removeClass('has-error');
  }

  if (error_fecha_inicial != "" || error_fecha_final != "") { return false; }
  $("#generar_reporte").prop("disabled", true);

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
  var nomArchivo = `Reporte_Valija_${fecha_inicio}_${fecha_fin}.xlsx`;
  var param = JSON.stringify({
    fecha_inicio: fecha_inicio,
    fecha_fin: fecha_fin,
  });
  var pathservicehost = `${urls}/valija/genera_reportes`;

  var xhr = new XMLHttpRequest();
  xhr.open("POST", pathservicehost, true);
  xhr.responseType = "blob";

  //Send the proper header information along with the request
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onload = function (e) {
    Swal.close(timerInterval);
    $("#generar_reporte").prop("disabled", false);
    if (xhr.readyState === 4 && xhr.status === 200) {
      $("#fecha_inicial").val("");
      $("#fecha_final").val("");
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
      alert(" No es posible acceder al archivo, probablemente no existe.");
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No es posible acceder al archivo, probablemente no existe.",
      });
    }
  };
  xhr.send("data=" + param);
});