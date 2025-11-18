/**
 * ARCHIVO MODULO PAQUETERIA
 * AUTOR: HORUS SAMAEL RIVAS PEDRAZA
 * horus.riv.ped@gmail.com
 * CEL:5624392632
 */
$(document).ready(function () {
  tbl_paqueteria = $("#tbl_mis_solicitudes")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}paqueteria/mis_solicitudes`,
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
          title: "Mis Solicitudes de Paqueteria",
          exportOptions: {
            columns: [0, 1, 2, 3],
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
          title: "ESTATUS",
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
            clase = (data["pdf_guie"] != null) ? "danger" : "secondary";
            change = (data["pdf_guie"] != null) ? `onClick=Guia(${data["id_request"]},'${data["pdf_guie"]}')` : "";
            return ` <div class="mr-auto">
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
    })
    .DataTable();
  $("#tbl_mis_solicitudes thead").addClass("thead-dark text-center");
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
  downloadInstance.download = `Guia_Paqueteria_${id_request}`;
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
