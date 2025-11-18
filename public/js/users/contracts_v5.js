/**
 * ARCHIVO MODULO ADMINISTRATOR
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EDITOR: HORUS SAMAEL RIVAS PEDRAZA
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * EMAIL - EDITOR: horus.riv.pedgmail.com
 * CEL:5565429649
 */

const colors = { 0: 'secondary', 1: 'success', 2: 'info', 3: 'danger', 4: 'warning', 5: 'primary' };
const text = { 0: 'BAJA', 1: 'PLANTA', 2: 'TEMPORAL', 3: 'EXPIRADO', 4: 'PRONTO A EXPIRAR', 5: 'EXPIRA HOY' };
var user_massive_contract = [];
$(document).ready(function () {
  tbl_users_temp = $("#tabla_usuarios_temp").dataTable({
    responsive: true, "lengthChange": false, "autoWidth": false,
    buttons: [
      {
        extend: 'excelHtml5',
        title: 'Listado Personal Eventual',
        exportOptions: {
          columns: [1, 2, 3, 4]
        }
      },
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
      url: `${urls}usuarios/usuarios_contratados`,
      dataSrc: "",
    },
    lengthChange: true,
    ordering: true,
    autoWidth: false,
    rowId: "staffId",
    // dom: "lBfrtip",
    language: {
      url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
    },
    columns: [
      {
        data: null,
        title: "SELECCIÓN",
        className: "text-center",
        render: function (data, type, row, meta) {
          if ((data["option"] == 2 && (data["id_color"] == 4 || data["id_color"] == 5 || data["id_color"] == 3)) || data["id_user_admin"] == 1063 || data["id_user_admin"] == 1) {
            return ` <div class="container-ChBox"><input type="checkbox" id="checkbox_${data["id_user"]}" onclick="array(${data["id_user"]})"></div>`;
          } else {
            return ` <div class="container-ChBox"><input type="checkbox" id="checkbox_${data["id_user"]}" disabled></div>`;

          }
        }
      },
      {
        data: "payroll_number",
        title: "NUM NOMINA",
        className: "text-center"
      },
      {
        data: "date_admission",
        title: "INGRESO",
        className: "text-center"
      },
      {
        data: null,
        render: function (data, type, row) {
          return data["name"] + ' ' + data["surname"];
        },
        title: "USUARIO",
        className: "text-center"
      },
      {
        data: null,
        render: function (data, type, row) {
          return data["job"];
        },
        title: "PUESTO",
        className: "text-center"
      },
      {
        data: "last_contract",
        title: "FECHA TERMINO",
        className: "text-center"
      },
      {
        data: null,
        render: function (data, type, row) {
          return `<span class="badge badge-${colors[data["id_color"]]}">${text[data["id_color"]]}</span>`;

        },
        title: "ESTADO",
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
        targets: 7,
        render: function (data, type, full, meta) {
          return ` <div class="pull-right mr-auto">
            <a href="${urls}usuarios/ver-contratos/${$.md5(key + data["id_user"])}" target="_blank" class="btn btn-info btn-sm">
              <i class="fas fa-file-signature"></i>
            </a>
          </div> `;
        },
      },
      /*  {
         targets: [0],
         visible: false,
         searchable: false,
       }, */
    ],

    // order: [[5, "DESC"]],

    createdRow: (row, data) => {
      $(row).attr("id", "usuario_" + data.id_user);
    },
  }).DataTable();
  $("#tabla_usuarios_temp thead").addClass("thead-dark text-center");
});

function handleDelete(id_user) {
}

function handleEdit(id_user) {
  url = "https://sie.grupowalworth.com/usuarios/";
  window.open(url, '_blank');
  return false;
}

function array(id_user) {
  console.log(id_user);
  var btn = document.getElementById('masivo');
  var checkbox = document.getElementById('checkbox_' + id_user);
  if (checkbox.checked) {
    user_massive_contract.push(id_user);
  } else {
    var index = user_massive_contract.indexOf(id_user);
    if (index !== -1) {
      user_massive_contract.splice(index, 1);
    }
  }
  if (user_massive_contract.length > 1) {
    btn.disabled = false;
    btn.classList.remove('btn-secondary');
    btn.classList.add('btn-outline-success');
  } else {
    btn.disabled = true;
    btn.classList.remove('btn-outline-success');
    btn.classList.add('btn-secondary');
  }
}

document.getElementById("masivo").addEventListener("click", function () {
  $("#tipo_contrato").val('');
  $("#tipo_temporal").val('');
  $("#obs").val('');
  $("#baja").val('');
  $("#div_temporal").hide();
  $("#div_baja").hide();
  $("#configurarContrato").modal('show');
});

document.getElementById("tipo_contrato").addEventListener("change", function () {
  console.log("change star");
  var temporal = document.getElementById("tipo_temporal");
  var baja = document.getElementById("baja");
  temporal.value = '';
  baja.value = '';

  if (this.value == 1) {
    $("#div_temporal").hide();
    $("#div_baja").hide();
    $("#div_obs").show();
    temporal.required = false;
    baja.required = false;
  }
  if (this.value == 2) {
    $("#div_obs").show();
    $("#div_temporal").show();
    $("#div_baja").hide();
    baja.required = false;
    temporal.required = true;
  }
  if (this.value == 3) {
    $("#div_obs").hide();
    $("#div_temporal").hide();
    $("#div_baja").show();
    temporal.required = false;
    baja.required = true;
  }
  console.log("change end");
});

$("#form_contratos_masivos").submit(function (e) {
  e.preventDefault();
  console.log('SUBMITR STAR');
  document.getElementById('btn_contratos_masivos').disabled = true;
  let timerInterval = Swal.fire({ //se le asigna un nombre al swal
    title: '<i class="fas fa-file-upload" style="margin-right: 10px;"></i>¡Generando Contratos!',
    html: 'Espere unos Segundos.',
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });
  var massive_contract = new FormData($("#form_contratos_masivos")[0]);
  massive_contract.append('contador', user_massive_contract.length);
  for (let i = 0; i < user_massive_contract.length; i++) {
    massive_contract.append('ids_contracts_[]', user_massive_contract[i]);
  }
  console.log(massive_contract);
  $.ajax({
    type: "post",
    url: `${urls}usuarios/generar_contratados_masivos`,
    data: massive_contract,
    cache: false,
    dataType: "json",
    contentType: false,
    processData: false,
    success: function (save) {
      console.log(save);
      Swal.close(timerInterval);
      document.getElementById('btn_contratos_masivos').disabled = false;
      if (save.hasOwnProperty('xdebug_message')) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
        console.log(save.xdebug_message);
      } else if (save === true) {
        $("#configurarContrato").modal('hide');
        tbl_users_temp.ajax.reload(null, false);
        user_massive_contract = [];
        const btnMassive = document.getElementById('masivo');
        btnMassive.disabled = true;
        btnMassive.classList.remove('btn-outline-success');
        btnMassive.classList.add('btn-secondary');
        Swal.fire({
          icon: 'success',
          title: "¡Generación Masiva Exitosa!",
          text: 'Se generaron todos los contratos exitosamente',
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
    document.getElementById('btn_contratos_masivos').disabled = false;
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
})