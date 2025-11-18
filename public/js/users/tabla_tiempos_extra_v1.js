/**
 * ARCHIVO MODULO QHSE
 * AUTOR:HORUS SAMAEL RIVAS PEDRAZA
 * EMAIL:horus.riv.ped@GMAIL.COM
 * CEL:5565429649
 */
$(document).ready(function () {
  tbl_overtime = $("#tabla_tiempos_extras")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: urls + "qhse/tiempos_extras_autorizado",
        dataSrc: "",
      },
      lengthChange: true,
      ordering: true,
      responsive: true,
      autoWidth: false,
      rowId: "staffId",
      dom: "lBfrtip",
      buttons: [
        /*
       {
         extend: "excelHtml5",
         title: "Permisos tiempos extra",
         exportOptions: {
           columns: [0, 1, 2, 3, 4, 5, 6],
         },
       },
       {
         extend: 'pdfHtml5',
         title: 'Listado de Proveedores',
         exportOptions: {
           columns: [1, 2, 3, 4, 5, 6, 7]
         }
       },
       */
      ],
      language: {
        url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
      },
      columns: [
        {
          data: "id",
          title: "FOLIO",
          className: "text-center",
        },
        {
          data: "payroll_number",
          title: "NOMINA",
          className: "text-center",
        },
        {
          data: "name",
          title: "USUARIO RESPONSABLE",
          className: "text-center",
        },
        /* {
          data: "departament",
          title: "DEPARTAMENTO",
          className: "text-center",
        }, */
        {
          data: null,
          render: function (data, type, full, meta) {
            fecha = new Date(data["day_you_visit"]);
            if (fecha.getMonth() + 1 < 10) { mes = `0${fecha.getMonth() + 1}`; }
            else { mes = fecha.getMonth() + 1; }
            if ((fecha.getDate() + 1) < 10) { dia = `0${(fecha.getDate() + 1)}`; }
            else { dia = (fecha.getDate() + 1); }
            $descripcion = `${dia}/${mes}/${fecha.getFullYear()}`;
            return $descripcion;
          },
          title: "DIA DE TIEMPO OBSCURO",
          className: "text-center",
        },
        {
          data: "time_of_entry",
          title: "HORA DE LLEGADA",
          className: "text-center",
        },
        {
          data: "departure_time",
          title: "HORA DE SALIDA",
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
            console.log(data["id"]);
            return ` <div class="pull-right mr-auto">
            <button type="button" class="btn btn-primary btn-sm " title="Ver Usuarios"  onClick="Edit(${data["id"]})">
            <i class="fas fa-users"></i>
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
      destroy: "true",
      order: [[0, "DESC"]],

      createdRow: (row, data) => {
        $(row).attr("id", "info_" + data.id);
      },
    })
    .DataTable();
  $("#tabla_tiempos_extras thead").addClass("thead-dark text-center");
});

function Edit(id_) {
  let fecha = $(`#info_${id_} td`)[3].innerHTML;
  let hora_i = $(`#info_${id_} td`)[4].innerHTML;
  let hora_f = $(`#info_${id_} td`)[5].innerHTML;
  $("#modat_title").empty();
  $("#modal_body").empty();
  $("#modat_title").append(`
  <i class="fas fa-users" style="margin-right: 0.5rem;"></i> DIA: ${fecha},  HORARIO: ${hora_i} | ${hora_f}`);
  let id = new FormData();
  id.append('id_',id_);
  $.ajax({
    processing: true,
    data: id,
    method: "post",
    url: urls + "qhse/tiempos_extras_all_user",
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (usuarios) {
      console.log(usuarios);
      if (usuarios) {
        usuarios.forEach(data => {
          $("#modal_body").append(`
          <div class="form-row">
          <div class="form-group col-md-2">
            <label>Nomina:</label>
            <input type="text" class="form-control" value="${data.payroll_number}" readonly>
          </div>
          <div class="form-group col-md-4">
            <label>Nombre:</label>
            <input type="text" class="form-control" value="${data.user}" readonly>
          </div>
          <div class="form-group col-md-3">
            <label>Puesto:</label>
            <input type="text" class="form-control" value="${data.job}" readonly>
          </div>
          <div class="form-group col-md-3">
            <label>Departamento:</label>
            <input type="text" class="form-control" value="${data.depto}" readonly>
          </div>
        </div>
          `);

        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    }
  });

  $("#user_Modal").modal("show");
}