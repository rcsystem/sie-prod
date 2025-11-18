/**
 * ARCHIVO MODULO ADMINISTRATOR
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */

$(document).ready(function () {

  tbl_users_temp = $("#tabla_usuarios_temp").dataTable({
    responsive: true, "lengthChange": false, "autoWidth": false,
    buttons: [
      /* {
        extend: 'excelHtml5',
        title: 'Listado Personal Eventual',
        exportOptions: {
          columns: [1, 2, 3, 4]
        }
      }, */
      /* {
        extend:'pdfHtml5',
        title:'Listado de Urs',
        exportOptions:{
          columns:[1,2,3,4]
        }
      } */
    ],
    processing: true,
    ajax: {
      method: "post",
      url: `${urls}usuarios/contratos_planta`,
      dataSrc: "",
    },
    lengthChange: true,
    ordering: true,
    autoWidth: false,
    rowId: "staffId",
    dom: "lBfrtip",
    language: {
      url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
    },
    columns: [
      {
        data: "payroll_number",
        title: "NUM NOMINA",
        className: "text-center"
      },

      {
        data: "nombre",
        title: "USUARIO",
        className: "text-center"
      },
      {
        data: "departament",
        title: "DEPARTAMENTO",
        className: "text-center"
      },
      {
        data: "job",
        title: "PUESTO",
        className: "text-center"
      },
      {
        data: "fecha_limite",
        title: "FIN CONTRATO TEMPORAL",
        className: "text-center"
      },
      {
        data: null,
          render: function (data, type, full, meta) {
            const color = {0: "bg-warning", 1: "bg-success", 2 : 'bg-danger'};
            const texto = {0: "PENDIENTE", 1: "AUTORIZAR", 2 : 'RECHAZADO'};
            return `<span class="badge ${color[data['direct_authorization']]}">${texto[data['direct_authorization']]}</span>`;
          },
          title: "ESTADO",
          className: "text-center",
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
        targets: 6,
        render: function (data, type, full, meta) {
          var color = (data["direct_authorization"] == 0) ? 'outline-success' : 'secondary';
          var change = (data["direct_authorization"] == 0) ? `onClick="Authorize(${data["id_contract"]})"` : '';
          return ` <div class="pull-right mr-auto">
              <a href="${urls}usuarios/ver-contrato/${$.md5(key + data["id_contract"])}" target="_blank" class="btn btn-outline-info btn-sm">
              <i class="fas fa-eye"></i>
              </a>
              <button class="btn btn-${color} btn-sm" ${change} >
              <i class="fas fa-file-signature"></i>
              </button>
            </div> `;
        },
      },
      /*  {
         targets: [0],
         visible: false,
         searchable: false,
       }, */
    ],

    order: [[0, "DESC"]],
    createdRow: (row, data) => {
      $(row).attr("id", "usuario_" + data.id_contract);
    },
  }).DataTable();
  $("#tabla_usuarios_temp thead").addClass("thead-dark text-center");

});

function Authorize(id_contract) {
  Swal.fire({
    title: '<i class="fas fa-file-signature" style="margin-right: 10px;"></i>¿Confirmar Contrato?',
    showDenyButton: true,
    showCancelButton: true,
    confirmButtonText: '<i class="fas fa-check" style="margin-right: 10px;"></i>Confirmar',
    confirmButtonColor: "#28A745",
    denyButtonText: `<i class="fas fa-times" style="margin-right: 10px;"></i>Rechazar`,
  }).then((result) => {
    if (result.isConfirmed) {
      estadoContrato(1, id_contract)
    } else if (result.isDenied) {
      estadoContrato(2, id_contract)
    }
  })
}

function estadoContrato(status, id) {
  let timerInterval = Swal.fire({ //se le asigna un nombre al swal
    title: '<i class="fas fa-envelope-open-text" style="margin-right: 10px;"></i>¡Notificando a Recursos Humanos!',
    html: 'Espere unos Segundos.',
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });
  var statusContract = new FormData();
  statusContract.append('direct_authorization', status)
  statusContract.append('id_contract', id)
  console.log(statusContract);
  $.ajax({
    type: "post",
    url: `${urls}usuarios/actualizar_contratos_planta`,
    data: statusContract,
    cache: false,
    dataType: "json",
    contentType: false,
    processData: false,
    success: function (save) {
      Swal.close(timerInterval);
      if (save === true) {
        tbl_users_temp.ajax.reload(null, false);
        Swal.fire({
          icon: 'success',
          title: "¡Notificancion Exitosa!",
          text: 'Se registró el cambio en el contrato',
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