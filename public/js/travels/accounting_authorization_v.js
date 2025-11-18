/**
 * ARCHIVO MODULO VIAJES
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EDITOR:HORUS RIVAS PEDRAZA
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */

$(document).ready(function () {
    tbl_amount_state = $("#tabla_estado_cuenta").dataTable({
        processing: true,
        ajax: {
            method: "post",
            url: `${urls}viajes/lista_comprobaciones_pendientes`,
            dataSrc: "",
        },
        lengthChange: true,
        ordering: true,
        responsive: true,
        autoWidth: true,
        rowId: "staffId",
        dom: "lfrtip",
        buttons: [
            {
                extend: "excelHtml5",
                title: "Permisos",
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6],
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
                data: "id_item",
                title: "FOLIO",
                className: "text-center",
            },
            {
                data: "tipo_request",
                title: "TIPO",
                className: "text-center",
            },
            {
                data: "user_name",
                title: "USUARIO",
                className: "text-center",
            },
            {
                data: "lugar",
                title: "LUGAR | MOTIVO",
                className: "text-center",
            },
            {
                data: "fecha",
                title: "FECHA TRANSACCIÓN",
                className: "text-center",
            },
            {
                data: null,
                render: function (data, type, full, meta) {
                    return `${data["amount"]} ${data["divisa"]}`;
                },
                title: "MONTO",
                className: "text-center",
            },
            {
                data: null,
                render: function (data, type, full, meta) {
                    return `<h5><span class="badge badge-pill badge-${data["conta_color"]}">${data["conta_txt"]}</span></h5>`;
                },
                title: "CONTABILIDAD",
                className: "text-center",
            },
            {
                data: null,
                render: function (data, type, full, meta) {
                  const color_espera = (data["accounting_authorization"] == 1) ? 'outline-primary' : 'secondary';
                  const click_espera = (data["accounting_authorization"] == 1) ? `onClick=autorizeOrCancel(${data["id_item"]})` : '';
                    return ` <div class="pull-right mr-auto">
                        <button type="button" class="btn btn-outline-info btn-sm" tittle="Descargar Archivo PDF" onclick="dowloadPdf('${data["pdf_travel_routes"]}')">
                            <i class="far fa-file-pdf"></i>
                        </button>
                        <button type="button" class="btn btn-${color_espera} btn-sm" ${click_espera}>
                        <i class="fas fa-file-signature"></i>
                        </button>
                    </div> `;
                },
                title: "ACCIONES",
                className: "text-center",
            },
        ],
        destroy: "true",

        order: [[0, "DESC"]],

        createdRow: (row, data) => {
            $(row).attr("id", "item_" + data.id_item);
        },
    }).DataTable();
    $("#tabla_estado_cuenta thead").addClass("thead-dark text-center");
});

function dowloadPdf(ubicacion) {

    console.log(`${urls}${ubicacion}`);
    const btn = document.getElementById('btn_dowload_format');
    btn.disabled = true;
    const cargando = Swal.fire({ //se le asigna un nombre al swal
        allowOutsideClick: false,
        title: `DESCARGANDO <i class="fas fa-qrcode"></i>`,
        html: 'Espere unos Segundos.',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });
    const downloadOneDocument = document.createElement('a');
    downloadOneDocument.href = `${urls}${ubicacion}`;
    downloadOneDocument.download = 'FormatoSubir_EstadoCuenta';
    // downloadOneDocument.target = "";
    /*  var clicEvent = new MouseEvent("click", {
         view: window,
         bubbles: true,
         cancelable: true,
     }); */
    // downloadOneDocument.dispatchEvent(clicEvent);
    downloadOneDocument.click();
    btn.disabled = false;
    Swal.close(cargando);
}

function autorizeOrCancel(id_item) {
    Swal.fire({
      title: '<i class="fas fa-money-check-alt" style="margin-right: 10px;"></i> Comprobación Tardía',
      showDenyButton: true,
      showCancelButton: true,
      confirmButtonText: '<i class="fas fa-check" style="margin-right: 10px;"></i>Aceptar',
      confirmButtonColor: "#28A745",
      denyButtonText: `<i class="fas fa-times" style="margin-right: 10px;"></i>Rechazar`,
    }).then((result) => {
      if (result.isConfirmed) {
        estadoComprobacion(3, id_item)
      } else if (result.isDenied) {
        estadoComprobacion(4, id_item)
      }
    })
  }
  
  function estadoComprobacion(status, id) {
    let timerInterval = Swal.fire({ //se le asigna un nombre al swal
      title: '¡Guardando Cambios!',
      html: 'Espere unos Segundos.',
      timerProgressBar: true,
      didOpen: () => {
        Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
      },
    });
    const statusAmountStatus = new FormData();
    statusAmountStatus.append('status', status)
    statusAmountStatus.append('id_acount', id)
    
    $.ajax({
      type: "post",
      url: `${urls}viajes/actualizar_comprobacion_tardia`,
      data: statusAmountStatus,
      cache: false,
      dataType: "json",
      contentType: false,
      processData: false,
      success: function (save) {
        Swal.close(timerInterval);
        if (save === true) {
          tbl_amount_state.ajax.reload(null, false);
          Swal.fire({
            icon: 'success',
            title: "Cambio Exitoso!",
            text: 'Se registró el cambio en la Comprovacion',
          });
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