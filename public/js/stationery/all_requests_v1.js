/**
 * ARCHIVO MODULO ADMINISTRATOR
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
$(document).ready(function () {
  tbl_inventary = $("#tabla_solicitudes")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}papeleria/todas_solicitudes`,
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
          title: "Solicitudes Papeleria",
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
          data: "id_request",
          title: "FOLIO",
          className: "text-center",
        },

        {
          data: null,
          render: function (data, type, full, meta) {
            let name = data["name"].toLowerCase();

            return name;
          },

          title: "USUARIO",
          className: "text-center",
        },
        {
          data: "email",
          title: "EMAIL",
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
            var hora = objFechaCreacion.getHours();
            var minutos = objFechaCreacion.getMinutes();
            // Devuelve: '1/2/2011':
            let fecha_creacion = dia + "-" + mes + "-" + anio;
            let hora_creacion = hora + ":" + minutos;
            return $.trim(data["created_at"]) === "0000-00-00"
              ? "---"
              : `${fecha_creacion} ${hora_creacion}`;
          },
          title: "FECHA",
          className: "text-center",
        },
        {
          data: "departament",
          title: "DEPARTAMENTO",
          className: "text-center",
        },
        {
          data: "cost_center",
          title: "CENTRO COSTO",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            const estatus = ["Error","Pendiente","Autorizado","Completado","Rechazada"];
            const badge = ["warning","warning","info","success","danger"];

            if (data["request_status"]<1 && data["request_status"]>4) {
                return `<span class="badge badge-warning">Error</span>`;
            }

            return `<span class="badge badge-${badge[data["request_status"]]}">${estatus[data["request_status"]]}</span>`;
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
          targets: 7,
          render: function (data, type, full, meta) {

            return ` <div class="pull-right mr-auto">
            <button type="button" class="btn btn-primary btn-sm" title="Editar Suministro"  onClick=Edit(${data["id_request"]})>
                <i class="far fa-edit"></i>
            </button>
            <a href="${urls}papeleria/ver-requisicion/${$.md5(
              key + data["id_request"]
            )}" title="Ver Requisición" target="_blank" class="btn btn-info btn-sm">
            <i class="fas fa-eye"></i>
      </a>
                      
                    </div> `;
          },
        },
        /*  {
            targets: [0],
            visible: false,
            searchable: true,
          },  */
      ],

      order: [[0, "DESC"]],
      select: true,
      createdRow: (row, data) => {
        $(row).attr("id", "request_" + data.id_request);
      },
    })
    .DataTable();
  $("#tabla_solicitudes thead").addClass("thead-dark text-center");
});

function Edit(id_request) {
  
  let usuario = $(`#request_${id_request} td`)[1].innerHTML;
  let email = $(`#request_${id_request} td`)[2].innerHTML;
  let fecha = $(`#request_${id_request} td`)[3].innerHTML;
  let depto = $(`#request_${id_request} td`)[4].innerHTML;
  let centro_costo = $(`#request_${id_request} td`)[5].innerHTML;
  $("#estado").val("");
  $("#entrega").val("");
  $("#email").val(email);
  $("#folio").val(id_request);
  $("#usuario").val(usuario);
  $("#fecha").val(fecha);
  $("#depto").val(depto);
  $("#centro_costo").val(centro_costo);
  error_entrega = "";
  $("#error_entrega").text(error_entrega);
  $("#entrega").removeClass("has-error");
  error_estado = "";
  $("#error_estado").text(error_estado);
  $("#estado").removeClass("has-error");
  $("#obs_entrega").val("");

  let data = new FormData();
  $("#table").empty();
  data.append("id_request", id_request);

  $.ajax({
      data: data, //datos que se envian a traves de ajax
      url: `${urls}papeleria/autorizar_pape`, //archivo que recibe la peticion
      type: "post", //método de envio
      processData: false, // dile a jQuery que no procese los datos
      contentType: false, // dile a jQuery que no establezca contentType
      async: true,
      dataType: "json",
      beforeSend: function () {
          /*
         * Se ejecuta al inicio de la petición
         * */
          $('#loaderGif').show();
      },

      success: function (resp) {
          console.log(resp);
          if (resp != false) {


              for (var arreglo in resp) {
                  //alert(" arreglo2 = " + arreglo);
                  $("#table").append(`<tr style="background:#e9ecef;">
                                       <td colspan="2" style="font-weight:bold;font-size:16px;">${arreglo}</td>
                                      </tr>`);
                  for (var elemento in resp[arreglo]) {

                      element = resp[arreglo][elemento];
                      if (element <= 9 && element >= 0) { e = "0" + element } else { e = element };
                      $("#table").append(`<tr style="background:#fbfbfb">
                                          <td style="font-weight:bold;font-size:16px;">${elemento}</td>
                                          <td style="font-weight:bold;text-align:center;font-size:16px;">${e}</td>
                                      </tr>`);
                  }
              }
              $("#papeleriaModal").modal("show");
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

$("#respuesta_papeleria").submit(function (event) {
  event.preventDefault();

  if ($.trim($("#entrega").val()).length == 0) {
    var error_entrega = "El campo es requerido";
    $("#error_entrega").text(error_entrega);
    $("#entrega").addClass("has-error");
  } else {
    error_entrega = "";
    $("#error_entrega").text(error_entrega);
    $("#entrega").removeClass("has-error");
  }

  if ($.trim($("#estado").val()).length == 0) {
    var error_estado = "El campo es requerido";
    $("#error_estado").text(error_estado);
    $("#estado").addClass("has-error");
  } else {
    error_estado = "";
    $("#error_estado").text(error_estado);
    $("#estado").removeClass("has-error");
  }

  if (error_entrega != "" || error_estado != "") {
    return false;
  }

  $("#res_papeleria").prop("disabled", true);
  let data = new FormData();

  data.append("id_folio", $("#folio").val());
  data.append("fecha_entrega", $("#entrega").val());
  data.append("obs_entrega", $("#obs_entrega").val());
  data.append("email", $("#email").val());
  data.append("estado", $("#estado").val());
  data.append("usuario", $("#usuario").val());

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}papeleria/request_entrega`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    success: function (response) {
      //una vez que el archivo recibe el request lo procesa y lo devuelve
      console.log(response);
      /*codigo que borra todos los campos del form newProvider*/

      if (response != "error") {
        setTimeout(function () {
          tbl_inventary.ajax.reload(null, false);
        }, 100);
        $("#res_papeleria").prop("disabled", false);
        $("#papeleriaModal").modal("toggle");
        Swal.fire("!Los datos se han Actualizado!", "", "success");
      } else {
        $("#res_papeleria").prop("disabled", false);
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
      $("#res_papeleria").prop("disabled", false);
    } else if (jqXHR.status == 404) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No se encontró la página solicitada [404]",
      });
      $("#res_papeleria").prop("disabled", false);
    } else if (jqXHR.status == 500) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Internal Server Error [500]",
      });
      $("#res_papeleria").prop("disabled", false);
    } else if (textStatus === "parsererror") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Error de análisis JSON solicitado.",
      });
      $("#res_papeleria").prop("disabled", false);
    } else if (textStatus === "timeout") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Time out error.",
      });
      $("#res_papeleria").prop("disabled", false);
    } else if (textStatus === "abort") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ajax request aborted.",
      });

      $("#res_papeleria").prop("disabled", false);
    } else {
      alert("Uncaught Error: " + jqXHR.responseText);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: `Uncaught Error: ${jqXHR.responseText}`,
      });
      $("#res_papeleria").prop("disabled", false);
    }
  });
});
