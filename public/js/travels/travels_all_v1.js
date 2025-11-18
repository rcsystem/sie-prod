/**
 * ARCHIVO MODULO VIAJES
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
var arrayAnticipo = [];

$(document).ready(function () {
  tbl_requisitions = $("#tabla_todos_viajes")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}viajes/todos-viajes`,
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
          title: "Requisiciones",
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
          data: "id_travel",
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
          className: "text-center"
        },
        {
          data: "reason_for_travel",
          title: "MOTIVO",
          className: "text-center"
        },
        {
          data: "trip_destination",
          title: "DESTINO",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            switch (data["request_status"]) {
              case '1':
                return `<span class="badge badge-warning">Pendiente</span>`;
                break;
              case '2':
                return `<span class="badge badge-info">Autorizado</span>`;
                break;
              case '3':
                return `<span class="badge badge-success">Aprobado</span>`;
                break;
              case '6':
                return `<span class="badge badge-danger">Rechazada</span>`;
                break;


              default:
                return `<span class="badge badge-warning">Error</span>`;
                break;
            }
          },
          title: "ESTATUS",
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
          targets: 6,
          render: function (data, type, full, meta) {
            clase = (data["request_status"] == 2) ? "primary" : "secondary";
            change = (data["request_status"] == 2) ? `onClick=handleChange(${data["id_travel"]})` : "";
            return ` <div class="mr-auto">
                    <button type="button" class="btn btn-${clase} btn-sm" title="Autorizar Viaje"  ${change}>
                          <i class="fas fa-user-check"></i>
                    </button>
                      <a href="${urls}viajes/ver-solicitud/${$.md5(key + data["id_travel"])}" target="_blank" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                      </a>
                    </div> `;
          },
        },
        /*  
         <button type="button" class="btn btn-primary btn-sm "  onClick=handleEdit(${data["id_folio"]})>
                             <i class="far fa-edit"></i>
                       </button> 
        {
           targets: [0],
           visible: false,
           searchable: false,
         }, */
      ],

      order: [[0, "DESC"]],

      createdRow: (row, data) => {
        $(row).attr("id", "request_" + data.id_travel);
      },
    })
    .DataTable();
  $('#tabla_todos_viajes thead').addClass('thead-dark text-center');
});

function validarAnticipo(item, i) {
  var nuevo_valor = $("#item_" + item).val();
  arrayAnticipo[i] = parseInt(nuevo_valor);
  sessionStorage.setItem('arrayAnticipo', JSON.stringify(arrayAnticipo));
  let total = 0;
  arrayAnticipo.forEach(function (a) { total += a; });
  $("#monto").val(total);
}

function validar() {
  if ($("#aprovacion").val() != "" && $("#coment").val().length > 0) {
    $("#error_coment").text("");
    $("#coment").removeClass('has-error');
  }
  if ($("#presupuesto").val().length > 0) {
    $("#presupuesto").removeClass('has-error');
    $("#error_presupuesto").text("");
  }
}

function opciones() {
  if ($("#aprovacion").val().length > 0) {
    $("#aprovacion").removeClass('has-error');
    $("#error_aprovacion").text("");
    $("#comentarioDiv").empty();
    if ($("#aprovacion").val() == 3) {
      $("#comentarioDiv").append(`<label for="coment">Comentario</label>
      <input type="text" class="form-control" id="coment" name="coment" onchange="validar()">
      <div id="error_coment" class="text-danger"></div>`);
    } else if ($("#aprovacion").val() == 6) {
      $("#comentarioDiv").append(`<label for="coment">Motivo de Rechazo</label>
      <input type="text" class="form-control" id="coment" name="coment" onchange="validar()">
      <div id="error_coment" class="text-danger"></div>`);
    }
    if ($("#coment").val().length > 0) {
      $("#coment").removeClass('has-error');
      $("#error_coment").text("");
    }
  } else {
    $("#comentarioDiv").empty();
  }
}

var advance = 0;
function handleChange(id_travel) {
  $("#anticipoDiv").empty();
  $("#comentarioDiv").empty();
  $("#montoDiv").empty();
  $("#aprovacion").val("");
  let data = new FormData();
  data.append("id_viaje", id_travel);

  $.ajax({
    data: data, 
    url: `${urls}viajes/editar_viaje_all`, 
    type: "post", 
    processData: false, 
    contentType: false, 
    async: true,
    dataType: "json",
    success: function (resp) {
      if (resp != false) {
        resp.request.forEach(function (persona, index) {
          advance = persona.request_advance;
          $("#id_viaje").val(id_travel);
          $("#id_user").val(persona.id_user);
          $("#usuario").val(persona.user_name);
          $("#motivo").val(persona.reason_for_travel);
          $("#origen").val(persona.origin_of_trip);
          $("#destino").val(persona.trip_destination);
          $("#presupuesto").val(persona.estimated_budget);
          $("#observacion").val(persona.observation);
          if (persona.request_advance == 1) {
            $("#montoDiv").append(`<label for="monto" style="margin-top:3px;">Anticipo Solicitado</label>
            <input type="text" class="form-control" id="monto" name="monto" value="${persona.amount}" disabled>
            <input type="hidden" id="monto_org" name="monto_org" value="${persona.amount}">`);
          } 
        });
        if (advance == 1) {
          resp.items.forEach(item => {
            arrayAnticipo.push(parseInt(item.monto));
            sessionStorage.setItem('arrayAnticipo', JSON.stringify(arrayAnticipo));
            let i = arrayAnticipo.indexOf(parseInt(item.monto));
            $("#anticipoDiv").append(`<div class="form-group col-md-3">
            <input type="hidden" name="id_item_[]" value="${item.id_item}">
            <label for="item_${item.id_item}">${item.description.toUpperCase()}</label>
            <input class="form-control" type="number" min="1" id="item_${item.id_item}" name="item_[]" value="${parseInt(item.monto)}" onchange="validarAnticipo(${item.id_item},${i})">
            </div>`);
          });
        }
        swalFirma();
        $("#aprovarViajeModal").modal("show");
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
        console.log("Mal Revisa");
      }
    },
  });
}

$("#aprovar_viaje").submit(function (e) {
  e.preventDefault();
  if ($("#firma_user").val().length == 0) {
    swalFirma();
    return false;
  }

  if ($("#presupuesto").val().length == 0) {
    var error_presupuesto = "Campo Requerido";
    $("#error_presupuesto").text(error_presupuesto);
    $("#presupuesto").addClass('has-error');
  } else {
    var error_presupuesto = "";
    $("#error_presupuesto").text(error_presupuesto);
    $("#presupuesto").removeClass('has-error');
  }

  if ($("#aprovacion").val().length == 0) {
    var error_aprovacion = "Campo Requerido";
    $("#error_aprovacion").text(error_aprovacion);
    $("#aprovacion").addClass('has-error');
  } else {
    var error_aprovacion = "";
    $("#error_aprovacion").text(error_aprovacion);
    $("#aprovacion").removeClass('has-error');
  }
  if ($("#aprovacion").val() != "" && $("#coment").val().length == 0) {
    var error_coment = "Campo Requerido";
    $("#error_coment").text(error_coment);
    $("#coment").addClass('has-error');
  } else {
    var error_coment = "";
    $("#error_coment").text(error_coment);
    $("#coment").removeClass('has-error');
  }

  if (error_aprovacion != "" || error_coment != "" || error_presupuesto != "") { return false }
  $("#btn_aprovar_viaje").prop('disabled', true);
  let data = new FormData($("#aprovar_viaje")[0]);
  if (advance == 1) { data.append("monto", $("#monto").val()); }
  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}viajes/aprovar_viaje`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    success: function (response) {
      console.log(response);
      $("#aprovarViajeModal").modal("toggle");
      $("#observacion_apro").val("");
      $("#aprovacion").val("");
      arrayAnticipo = [];
      $("#btn_aprovar_viaje").prop('disabled', false);
      if (response) {
        Swal.fire(`!La Solicitud ha sido Aprovada!`, "", "success");
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
      $("#btn_aprovar_viaje").prop('disabled', false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Algo salió Mal! Contactar con el Administrador",
      });
      console.log("Mal Revisa entro en el error: " + error);
    },
  });
});

function swalFirma() {
  $.ajax({
    url: `${urls}viajes/firma`,
    type: "POST",
    dataType: "json",
    success: async function (respFir) {
      if (respFir.firma == null || respFir.firma == "") {
        const { value: file } = await Swal.fire({
          allowOutsideClick: false,
          icon: "warning",
          title: 'Regista tu Firma (imagen)',
          text: "Tu Firma No Esta Registrada",
          input: 'file',
          confirmButtonText: 'Guardar',
          inputAttributes: {
            required: true,
            'accept': 'image/*',
            'aria-label': 'Upload your profile picture',
            'id': 'dato'
          },
          validationMessage: 'Campo Requerido',
        });
        if (file) {
          var siezekiloByte = parseInt(file.size / 1024);
          if (siezekiloByte > 1024) {
            Swal.fire({
              allowOutsideClick: false,
              icon: "error",
              title: "Oops...",
              text: "El Tamaño de la Imagen sobre pasa el permitido...",
            }).then((result) => {
              swalFirma();
            });
          }
          const reader = new FileReader();
          reader.onload = (e) => {
            Swal.fire({
              allowOutsideClick: false,
              title: '¿tu firma es Correcta?',
              imageUrl: e.target.result,
              showDenyButton: true,
              confirmButtonText: 'Correcta',
              denyButtonText: `Incorrecta`,
            }).then((result) => {
              if (result.isDenied) {
                swalFirma();
              } else if (result.isConfirmed) {
                let timerInterval = Swal.fire({ //se le asigna un nombre al swal
                  allowOutsideClick: false,
                  title: '¡Guardando!',
                  timerProgressBar: true,
                  didOpen: () => {
                    Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
                  },
                });
                let data = new FormData();
                data.append("firma_", file, File);
                $.ajax({
                  data: data,
                  method: "post",
                  url: `${urls}viajes/saveFirma`,
                  dataType: "json",
                  cache: false,
                  contentType: false,
                  processData: false,
                  success: function (respSave) {
                    Swal.close(timerInterval);
                    if (respSave) {
                      $("#firma_user").val(respSave);
                      Swal.fire({
                        icon: "success",
                        text: "¡Se Guardo Correctamente!",
                      });
                    } else {
                      Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Algo salió Mal! Contactar con el Administrador del Sistema",
                      });
                    }
                  }
                });
              }
            });
          }
          reader.readAsDataURL(file);
        }
      } else {
        $("#firma_user").val(respFir.firma);
      }
    }
  });
}