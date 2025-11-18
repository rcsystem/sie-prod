/*
 * ARCHIVO MODULO SERVICIO MEDICO
 * AUTOR:Horus Samael Rivas Pedraza
 * EMAIL:horus.riv.ped@gmail.com
 * CEL: 56 2439 2632
 */
const clas = ['ERROR','A. DE TRABAJO', 'A. DE TRAYECTO', 'C. ADMINISTRATIVA', 'ENF LABORAL', 'ENF. GENERAL', 'EXAMEN DE EGRESO', 'EXAMEN DE INGRESO', 'EXAMEN DE TRABAJO DE RIESGO', 'EXAMEN ESPECIAL', 'EXAMEN PERIÓDICO', 'EXAMEN POST INCAPACIDAD', 'VALORACIÓN A CONTRATISTA', 'OTROS'];
const grado_salud = ['GRADO 0','GRADO I','GRADO II','GRADO III','GRADO IV'];
const motive = ['NO DEFINIDO','ESTRÉS LABORAL','ESTRÉS PERSONAL','EGRONOMÍA','ENFERMEDAD DE TRABAJO',];

$(document).ready(function () {
  tbl_requisitions = $("#tabla_solicitudes")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}medico/todas_solicitudes`,
        dataSrc: "",
      },
      lengthChange: true,
      ordering: true,
      responsive: true,
      autoWidth: false,
      rowId: "staffId",
      dom: "lBfrtip",
      buttons: [
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
          data: "user_name",
          title: "USUARIO",
        },
        {
          data: "motive",
          title: "MOTIVO",
          className: "text-center",
        },
        {
          data: "system",
          title: "SISTEMA",
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
              <a href="${urls}medico/ver-incapacidad-medica/${$.md5(key + data["id_request"])}" title="Ver Informacion de Consulta" target="_blank" class="btn btn-info btn-sm">
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

  tbl_consultas = $("#tabla_consultas")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}medico/todas_consultas_medicas`,
        dataSrc: "",
      },
      lengthChange: true,
      ordering: true,
      responsive: true,
      autoWidth: false,
      rowId: "staffId",
      dom: "lBfrtip",
      buttons: [
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
          className: "text-center",
        },
        {
          data: "type_atention",
          title: "TIPO ATENCION",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            return `${clas[data["id_classification"]]}`;
          },
          title: "CLASIFICACION",
          className: "text-center",
        },
        {
          data: null,
          render: function (data) {
            fecha = (data["next_appointment"] == '0000-00-00') ? '----' : data["next_appointment"];
            return fecha;
          },
          title: "FECHA CITA",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            switch (data["status"]) {
              case "1":
                return `<span class="badge badge-info">PROCESO</span>`;
                break;
              case "2":
                return `<span class="badge badge-success">COMPLETADO</span>`;
                break;

              default:
                return `<span class="badge badge-danger">ERROR</span>`;
                break;
            }
          },
          title: "ESTADO",
          className: "text-center"
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
            return ` <div class="mr-auto">
               <button type="button" class="btn btn-primary btn-sm" title="Finalizar" onclick="Edit(${data["id_request"]})">
                   <i class="far fa-edit"></i>
               </button>
              <!--
              <a href="${urls}medico/ver-consulta/${$.md5(key + data["id_request"])}" title="Ver Informacion de Consulta" target="_blank" class="btn btn-info btn-sm">
                <i class="fas fa-eye"></i>
              </a>
              -->                    
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
        $(row).attr("id", "request_consult_" + data.id_request);
      },
    })
    .DataTable();
  $("#tabla_consultas thead").addClass("thead-dark text-center");

  tbl_examenes = $("#tabla_examenes")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}medico/todas_examenes_medicos`,
        dataSrc: "",
      },
      lengthChange: true,
      ordering: true,
      responsive: true,
      autoWidth: false,
      rowId: "staffId",
      dom: "lBfrtip",
      buttons: [
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
          title: "FECHA ATENCION",
          className: "text-center",
        },
        {
          data: "payroll_number",
          title: "NOMINA",
          className: "text-center",
        },
        {
          data: "name",
          title: "USUARIO",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            return `${grado_salud[data["health"]]}`;
          },
          title: "GRADO DE SALUD",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            return `${motive[data["motive"]]}`;
          },
          title: "MOTIVO COMUN",
          className: "text-center",
        },
        
        /* {
          data: null,
          title: "ACCIONES",
          className: "text-center",
        }, */
      ],
      destroy: "true",
      /* columnDefs: [
        {
          targets: 7,
          render: function (data, type, full, meta) {
            return ` <div class="mr-auto">
               <button type="button" class="btn btn-primary btn-sm" title="Finalizar" onclick="Edit(${data["id_request"]})">
                   <i class="far fa-edit"></i>
               </button>
              <!--
              <a href="${urls}medico/ver-consulta/${$.md5(key + data["id_request"])}" title="Ver Informacion de Consulta" target="_blank" class="btn btn-info btn-sm">
                <i class="fas fa-eye"></i>
              </a>
              -->                    
            </div> `;
          },
        },
        {
          targets: [0],
          visible: false,
          searchable: false,
        },
      ], */

      order: [[0, "DESC"]],

      createdRow: (row, data) => {
        $(row).attr("id", "request_consult_" + data.id_request);
      },
    })
    .DataTable();
  $("#tabla_examenes thead").addClass("thead-dark text-center");
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

function Edit(id_) {
  var id = new FormData();
  id.append("id_request", id_);
  $.ajax({
    data: id,
    url: urls + "medico/datos_consulta_medica",
    type: "post",
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (resp) {
      if (resp != false) {
        $("#id_request").val(id_);
        $("#modal_nombre").val($(`#request_consult_${id_} td`)[2].innerHTML);
        $("#modal_tipo_atencion").val($(`#request_consult_${id_} td`)[3].innerHTML);
        $("#calificacion_accidente").val(resp.calification);
        $("#tipo_incapacidad").val(resp.inability);
        $("#estado").val(resp.estado);
        $("#cerrar_consulta_Modal").modal("show");
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
  });
}

$("#form_cerrar_consulta").on('submit', function (e) {
  e.preventDefault();
  $("#btn_cerrar_consulta").prop("disabled", true);
  const updata = new FormData($("#form_cerrar_consulta")[0]);
  $.ajax({
    data: updata,
    type: "post",
    url: `${urls}medico/actualizar_consulta_medica`,
    processData: false,
    contentType: false,
    dataType: "json",
    cache: false,
    success: function (save) {
    $("#btn_cerrar_consulta").prop("disabled", false);
      if (save) {
        setTimeout(function () {
          tbl_consultas.ajax.reload(null, false);
        }, 100);
        $("#cerrar_consulta_Modal").modal("toggle");
        Swal.fire("!Los datos se han Actualizado!", "", "success");
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    }
  }).fail(function (jqXHR, textStatus, errorThrown) {
    $("#btn_cerrar_consulta").prop("disabled", false);
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