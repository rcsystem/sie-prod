/**
 * ARCHIVO MODULO PACKER
 * AUTOR: HORUS SAMAEL RIVAS PEDRAZA
 * horus.riv.ped@gmail.com
 * CEL:5624392632
 */
var error_estado = "";
var error_guia = "";
var error_coment = "";

$(document).ready(function () {
  tbl_paqueteria = $("#tbl_autorizar")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}paqueteria/todas_solicitudes`,
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
          title: "Solicitudes de Paqueteria",
          exportOptions: {
            columns: [0, 1, 2, 3, 4],
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
          render: function (data, type, row, meta) {
            let sender_company = data["sender_name"].toUpperCase();
            return sender_company;
          },
          title: "USUARIO",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, row, meta) {
            let sending_company = data["sending_company"].toUpperCase();
            return sending_company;
          },
          title: "EMPRESA REMITENTE",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, row, meta) {
            let recipient_company = data["recipient_company"].toUpperCase();
            return recipient_company;
          },
          title: "DESTINO",
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
            clase = (data["pdf_guie"] != null) ? "danger" : "secondary";
            change = (data["pdf_guie"] != null) ? `onclick=Guia(${data["id_request"]},'${data["pdf_guie"]}')` : "";
            return ` <div class="mr-auto">
              <button type="button" class="btn btn-primary btn-sm" title="Editar Suministro" onclick="Edit(${data["id_request"]},${data["shipping_type"]})">
                  <i class="far fa-edit"></i>
              </button>
               <button type="button" id="btn_excel" class="btn btn-success btn-sm" onclick="Excel(${data["id_request"]})">
                 <i class="fas fa-file-excel"></i>
               </button>
               <button type="button" id="btn_dowload" class="btn btn-${clase} btn-sm" ${change} >
             <i class="fas fa-file-download"></i>&nbsp;&nbsp;Guía
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
        $(row).attr("id", "request_" + data.id_request);
      },
    })
    .DataTable();
  $("#tbl_autorizar thead").addClass("thead-dark text-center");
});

function Guia(id_request, urlUbiv) {
  $("#btn_dowload").prop("disabled", true);
  let timerDowload = Swal.fire({ //se le asigna un nombre al swal
    allowOutsideClick: false,
    title: '¡Descargando!',
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });
  const downloadInstance = document.createElement('a');
  downloadInstance.href = `${urls}${urlUbiv}`;
  downloadInstance.download = `Guia_Paqueteria_${id_request}.pdf`;
  downloadInstance.click();
  Swal.close(timerDowload);
  $("#btn_dowload").prop("disabled", false);
}

function Excel($id_request_fun) {
  let id_request = $id_request_fun;

  $("#btn_excel").prop("disabled", true);
  let timer = Swal.fire({ //se le asigna un nombre al swal
    allowOutsideClick: false,
    title: '¡Generando Excel!',
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });
  var nomArchivo = `Solicitud_de_envio_${id_request}.xlsx`;
  var param = JSON.stringify({
    id_request: id_request
  });
  var pathservicehost = `${urls}paqueteria/excel`;
  var xhr = new XMLHttpRequest();
  xhr.open("POST", pathservicehost, true);
  xhr.responseType = "blob";
  //Send the proper header information along with the request
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onload = function (e) {
    Swal.close(timer);
    if (xhr.readyState === 4 && xhr.status === 200) {
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
      $("#btn_excel").prop("disabled", false);
      //link.click();
    } else {
      $("#btn_excel").prop("disabled", false);

      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No es posible acceder al archivo, probablemente no existe. Comunicarse con el Administrador",
      });
    }
  };
  xhr.send("data=" + param);

}

function Edit(id_request, type) {
  $("#error_estado").text("");
  $("#estado").removeClass("has-error");
  $("#lbl_guia").removeClass('has-error');
  $("#error_guia").text("");
  $("#error_coment").text("");
  $("#coment").removeClass("has-error");
  let usuario = $(`#request_${id_request} td`)[2].innerHTML;
  let origen = $(`#request_${id_request} td`)[3].innerHTML;
  let destino = $(`#request_${id_request} td`)[4].innerHTML;
  $("#folio").val("");
  $("#usuario").val("");
  $("#empresa_R").val("");
  $("#empresa_D").val("");
  $("#tipo_").val("");
  $("#estado").val("");
  $("#guia").val("");
  if (type == 1) { var tipo = "DIA SIGUIENTE"; }
  else if (type == 2) { var tipo = "TERRESTRE"; }
  error_estado = "";
  $("#error_estado").text(error_estado);
  $("#estado").removeClass("has-error");
  $("#folio").val(id_request);
  $("#usuario").val(usuario);
  $("#empresa_R").val(origen);
  $("#empresa_D").val(destino);
  $("#tipo_").val(tipo);

  $("#paqueteria_Modal").modal("show");
}
function validar() {
  if ($.trim($("#estado").val()).length > 0) {
    $("#error_estado").text("");
    $("#estado").removeClass("has-error");
  }
  if ($("#guia").val().length > 0) {
    $("#lbl_guia").empty();
    $("#lbl_guia").append(`${document.getElementById('guia').files[0].name}`);
    $("#lbl_guia").removeClass('has-error');
    $("#lbl_guia").attr("style", "color:#474D54;");
    $("#error_guia").text("");
  }
  if ($.trim($("#coment").val()).length > 0) {
    $("#error_coment").text("");
    $("#coment").removeClass("has-error");
  }
}


$("#form_paqueteria").submit(function (event) {
  event.preventDefault();

  if ($.trim($("#estado").val()).length == 0) {
    error_estado = "El campo es requerido";
    $("#error_estado").text(error_estado);
    $("#estado").addClass("has-error");
  } else {
    error_estado = "";
    $("#error_estado").text(error_estado);
    $("#estado").removeClass("has-error");
  }
  if ($("#estado").val() == 2) {
    if ($("#guia").val().length == 0) {
      error_guia = "El campo es requerido";
      $("#error_guia").text(error_guia);
      $("#lbl_guia").addClass("has-error");
    } else {
      error_guia = "";
      $("#error_guia").text(error_guia);
      $("#lbl_guia").removeClass("has-error");
    }
  } else { error_guia = ""; }
  if ($.trim($("#coment").val()).length == 0) {
    error_coment = "El campo es requerido";
    $("#error_coment").text(error_coment);
    $("#coment").addClass("has-error");
  } else {
    error_coment = "";
    $("#error_coment").text(error_coment);
    $("#coment").removeClass("has-error");
  }

  if (error_estado != "" || error_guia != "" || error_coment != "") {
    return false;
  }

  $("btn_form_paquetiria").prop("disabled", true);
  let data = new FormData($('#form_paqueteria')[0]);

  $.ajax({
    data: data,
    url: `${urls}paqueteria/autorizar`,
    type: "post",
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (response) {
      if (response == true) {
        setTimeout(function () {
          tbl_paqueteria.ajax.reload(null, false);
        }, 100);
        $("btn_form_paquetiria").prop("disabled", false);
        $("#paqueteria_Modal").modal("toggle");
        Swal.fire("!Los datos se han Actualizado!", "", "success");
      }
      else {
        $("btn_form_paquetiria").prop("disabled", false);
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
      $("#btn_form_paquetiria").prop("disabled", false);
    } else if (jqXHR.status == 404) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No se encontró la página solicitada [404]",
      });
      $("#btn_form_paquetiria").prop("disabled", false);
    } else if (jqXHR.status == 500) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Internal Server Error [500]",
      });
      $("#btn_form_paquetiria").prop("disabled", false);
    } else if (textStatus === "parsererror") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Error de análisis JSON solicitado.",
      });
      $("#btn_form_paquetiria").prop("disabled", false);
    } else if (textStatus === "timeout") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Time out error.",
      });
      $("#btn_form_paquetiria").prop("disabled", false);
    } else if (textStatus === "abort") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ajax request aborted.",
      });

      $("#btn_form_paquetiria").prop("disabled", false);
    } else {
      alert("Uncaught Error: " + jqXHR.responseText);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: `Uncaught Error: ${jqXHR.responseText}`,
      });
      $("#btn_form_paquetiria").prop("disabled", false);
    }
  });
});
