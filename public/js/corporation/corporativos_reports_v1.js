/**
 * ARCHIVO MODULO PAPELERIA
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EDITOR: HORUS SAMAEL RIVAS PEDRAZA
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */

const colorPermiss = { 1: '#2B43C6', 2: '#7DCD67', 3: '#F2A100', 4: '#CB6BD3', 5: '#3FC3EE', null: '#BDBDBD' };
const colorStatus = { "Rechazada": 'danger', "Autorizada": 'success', "Cancelada": 'cancel', "Pendiente": 'warning' }

const servicios = {
  1: 'Servicios',
  2: 'Tickets_IT_Epicor',
  3: 'Papeleria',
  4: 'Valija',
  5: 'Paqueteria',
  6: 'Vehículos',
  7: 'Cafeteria',
  8: 'Reporte Indicadores',
};

$("#form_servicio_reporte").submit(function (e) {
  e.preventDefault();
  const btn = document.getElementById('btn_servicio_reporte');
  const finFecha = document.getElementById('servicio_fecha_fin');
  const iniFecha = document.getElementById('servicio_fecha_ini');
  const tipoReporte = document.getElementById('servicio_tipo_reporte');
  var error_fecha = '';
  var error_fecha_ini = '';
  var error_tipo = '';

  if (tipoReporte.value.length == 0) {
    error_tipo = 'Campo Requerido';
    tipoReporte.classList.add('has-error');
    document.getElementById("error_" + tipoReporte.id).textContent = error_tipo;
  } else {
    error_tipo = '';
    tipoReporte.classList.remove('has-error')
    document.getElementById("error_" + tipoReporte.id).textContent = error_tipo;
  }

  if (iniFecha.value.length == 0) {
    error_fecha_ini = 'Campo Requerido';
    iniFecha.classList.add('has-error');
    document.getElementById("error_" + iniFecha.id).textContent = error_fecha_ini;
  } else {
    error_fecha_ini = '';
    iniFecha.classList.remove('has-error')
    document.getElementById("error_" + iniFecha.id).textContent = error_fecha_ini;
  }

  if (finFecha.value.length == 0) {
    error_fecha = 'Campo Requerido';
    finFecha.classList.add('has-error');
    document.getElementById("error_" + finFecha.id).textContent = error_fecha;
  } else if (finFecha.value < iniFecha.value) {
    error_fecha = 'Fecha Final debe ser mayor a Fecha Inicial';
    finFecha.classList.add('has-error')
    document.getElementById("error_" + finFecha.id).textContent = error_fecha;
  } else {
    error_fecha = '';
    finFecha.classList.remove('has-error')
    document.getElementById("error_" + finFecha.id).textContent = error_fecha;
  }

  if (error_fecha != '' || error_tipo != '' || error_fecha_ini != '') {
    return false;
  }

  btn.disabled = true;

  var fecha_inicio = iniFecha.value;
  var fecha_fin = finFecha.value;
  var reporte = tipoReporte.value;
  var nomArchivo = `Reporte_${servicios[tipoReporte.value]}_${fecha_inicio}_${fecha_fin}.xlsx`;


  if (tipoReporte.value == 8) {

    var timerInterval = Swal.fire({ //se le asigna un nombre al swal
      icon: 'error',
      iconHtml: '<i class="far fa-file-pdf nav-icon" style="color:red;font-size: 90px;margin: inherit;"></i>',
      title: 'Generando ' + servicios[tipoReporte.value] + ' !',
      html: 'Espere unos Segundos.',
      timerProgressBar: true,
      didOpen: () => {
        Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
      },
    });


    var data = new FormData();
   
    data.append('start_date', fecha_inicio);
    data.append('end_date', fecha_fin);

    // Realizar solicitud Ajax
    $.ajax({
      url: `${urls}corporativo/reporte-indicadores`,
      data: data,
      type: "post",
      cache: false,
      dataType: "json",
      contentType: false,
      processData: false,
      success: function(data) {
        btn.disabled = false;
        
        var link = document.createElement('a');
        link.href = data.pdfPath;
        link.download = `reportes_corporativo_${fecha_inicio}_${fecha_fin}.pdf`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        Swal.close(timerInterval); // cierra el swal en ejecucion (nombre del swal)
    },
      error: function () {
        btn.disabled = false;
        Swal.close(timerInterval); // cierra el swal en ejecucion (nombre del swal)
        // Manejar errores de la solicitud Ajax
        alert('Error de conexión');
      }
    });

  } else {

    let timerInterval = Swal.fire({ //se le asigna un nombre al swal
      icon: 'success',
      iconHtml: '<i class="far fa-file-excel nav-icon" style="font-size: 50px;margin: inherit;"></i>',
      title: 'Generando Reporte de ' + servicios[tipoReporte.value] + ' !',
      html: 'Espere unos Segundos.',
      timerProgressBar: true,
      didOpen: () => {
        Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
      },
    });


    var end_point = (tipoReporte.value == 1) ? 'generar_reportes_todos_servicios' : 'generar_reportes_servicios';

    var param = JSON.stringify({
      tipoReporte: reporte,
      fechaInicio: fecha_inicio,
      fechaFin: fecha_fin,
    });
    var pathservicehost = `${urls}corporativo/${end_point}`;
    var xhr = new XMLHttpRequest();
    xhr.open("POST", pathservicehost, true);
    xhr.responseType = "blob";
    //Send the proper header information along with the request
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function (e) {
      Swal.close(timerInterval); // cierra el swal en ejecucion (nombre del swal)
      btn.disabled = false;
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
        //link.click();
      } else {

        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "No es posible acceder al archivo, probablemente no existe. Comunicarse con el Administrador",
        });
      }





    };
    xhr.send("data=" + param);


  }

})

$("#formReporte").submit(function (e) {
  e.preventDefault();
  const finFecha = document.getElementById('fecha_fin');
  const iniFecha = document.getElementById('fecha_ini');
  var error_fecha = '';

  if (finFecha.value.length == 0) {
    error_fecha = 'Campo Requerido';
    finFecha.classList.add('has-error');
    document.getElementById("error_" + finFecha.id).textContent = error_fecha;
  } else if (finFecha.value < iniFecha.value) {
    error_fecha = 'Fecha Final debe ser mayor a Fecha Inicial';
    finFecha.classList.add('has-error')
    document.getElementById("error_" + finFecha.id).textContent = error_fecha;
  } else {
    error_fecha = '';
    finFecha.classList.remove('has-error')
    document.getElementById("error_" + finFecha.id).textContent = error_fecha;
  }

  if (error_fecha != '') {
    return false;
  }

  $("#generarReporte").prop("disabled", true);

  var fecha_inicio = $("#fecha_ini").val();
  var fecha_fin = $("#fecha_fin").val();
  var reporte = $("#tipo_reporte").val();
  var permiso = (reporte == 1) ? $("#cat_permiso").val() : "";
  if (reporte == 1) {
    tipoReporte = "Salidar_Entradas";
  } if (reporte == 2) {
    tipoReporte = "Vacaciones";
  }
  let timerInterval = Swal.fire({ //se le asigna un nombre al swal
    title: 'Generando Reporte en Excel!',
    html: 'Espere unos Segundos.',
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });

  var nomArchivo = `Reporte_${tipoReporte}_${fecha_inicio}_${fecha_fin}.xlsx`;
  var param = JSON.stringify({
    tipoReporte: reporte,
    fechaInicio: fecha_inicio,
    fechaFin: fecha_fin,
    permissions: permiso
  });
  var pathservicehost = `${urls}corporativo/generar_reportes`;
  var xhr = new XMLHttpRequest();
  xhr.open("POST", pathservicehost, true);
  xhr.responseType = "blob";
  //Send the proper header information along with the request
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onload = function (e) {
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
      $("#generarReporte").prop("disabled", false);
      Swal.close(timerInterval); // cierra el swal en ejecucion (nombre del swal)
      //link.click();
    } else {
      $("#generarReporte").prop("disabled", false);

      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No es posible acceder al archivo, probablemente no existe. Comunicarse con el Administrador",
      });
    }
  };
  xhr.send("data=" + param);

})

$("#tipo_reporte").on('change', function () {
  $("#cat_permiso_div").empty();
  $("#cat_permiso_div").attr('class', "");
  if ($("#tipo_reporte").val() == 1) {
    $("#cat_permiso_div").attr('class', "form-group col-md-6");
    $("#cat_permiso_div").append(`<label for="cat_permiso">Tipo de Permiso</label>
    <select class="form-control" id="cat_permiso" required>
      <option value="">Seleccionar</option>
      <option value="LABORAL">Laboral</option>
      <option value="PERSONAL">Personal</option>
      <option value="SERVICIO MEDICO">Servicio Medico</option>
      <option value="1">Todos</option>
    </select>`);
  }
})


$("#form_data_table").submit(function (e) {
  e.preventDefault();
  const finFecha = document.getElementById('fecha_fin_b');
  const iniFecha = document.getElementById('fecha_inicio_b');
  var error_fecha_b = '';

  if (finFecha.value.length == 0) {
    error_fecha_b = 'Campo Requerido';
    finFecha.classList.add('has-error');
    document.getElementById("error_" + finFecha.id).textContent = error_fecha_b;
  } else if (finFecha.value < iniFecha.value) {
    error_fecha_b = 'Fecha Final debe ser mayor a Fecha Inicial';
    finFecha.classList.add('has-error')
    document.getElementById("error_" + finFecha.id).textContent = error_fecha_b;
  } else {
    error_fecha_b = '';
    finFecha.classList.remove('has-error')
    document.getElementById("error_" + finFecha.id).textContent = error_fecha_b;
  }

  if (error_fecha_b != '') {
    return false;
  }


  $("#btm_data_table").prop("disabled", true);
  $("#table_div").empty();
  $("#table_div").append(`<table id="tabla_anteriores" class="table table-striped table-bordered nowrap" role="grid" aria-describedby="vacaciones_info" style="width:100%" ref=""></table>`)

  var inicio = $("#fecha_inicio_b").val();
  var fin = $("#fecha_fin_b").val();
  var busqueda = $("#tipo_busqueda").val();
  var opcion = "";
  if (busqueda != 1) {
    opcion = $("#opcion").val();
  }

  if ($("#tipo_anterior").val() == 1) {
    tbl_permissions = $("#tabla_anteriores").dataTable({
      // processing: true,
      ajax: {
        data: { "star_date": inicio, "end_date": fin, "serch": busqueda, "option": opcion },
        method: "post",
        url: `${urls}corporativo/entrada_salida_fecha`,
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
          data: "id_es",
          title: "FOLIO",
          className: "text-center",
        }, // 0
        {
          data: "nombre_solicitante",
          title: "USUARIO",
        }, // 1
        {
          data: null,
          render: function (data, type, full, meta) {
            let fecha_salida = moment(data["fecha_salida"]).format('YYYY-MM-DD');
            const hrSalida = data["hora_salida"];
            return $.trim(data["fecha_salida"]) === "0000-00-00"
              ? "---"
              : ` <div class="mr-auto">${fecha_salida} - ${hrSalida} </div> `;
          },
          title: "SALIDA",
          className: "text-center",
        }, // 2
        {
          data: null,
          render: function (data, type, full, meta) {

            let fecha_entrada = moment(data["fecha_entrada"]).format('YYYY-MM-DD');
            const hrEntrada = data["hora_entrada"];
            return $.trim(data["fecha_entrada"]) === "0000-00-00"
              ? "---"
              : ` <div class="mr-auto"> ${fecha_entrada}- ${hrEntrada} </div> `;
          },
          title: "ENTRADA",
          className: "text-center",
        }, // 3
        {
          data: null,
          render: function (data, type, full, meta) {
            return $.trim(data["inasistencia_del"]) == "0000-00-00"
              ? "---"
              : ` <div class="mr-auto">Del: ${data["inasistencia_del"]} </br> Al: ${data["inasistencia_al"]} </div> `;
          },
          title: "INASISTENCIA",
          className: "text-center",
        }, // 4
        {
          data: null,
          render: function (data, type, full, meta) {
            return `<span class="badge" style="color:#fff;background-color:${colorPermiss[data["id_tipo_permiso"]]};">${data["tipo_permiso"]}</span>`;
          },
          title: "TIPO PERMISO",
          className: "text-center",
        }, // 5
        {
          data: null,
          render: function (data, type, full, meta) {
            return `<span class="badge badge-${colorStatus[data["estatus"]]}">${data["estatus"]}</span>`;
          },
          title: "ESTATUS",
          className: "text-center",
        }, // 6
        {
          data: null,
          title: "Acciones",
          className: "text-center",
        }, // 7
      ],
      // destroy: "true",
      columnDefs: [
        {
          targets: 7,
          render: function (data, type, full, meta) {
            return ` <div class="pull-right mr-auto">
            <a href="${urls}permisos/ver-permisos/${$.md5(key + data["id_es"])}" target="_blank" class="btn btn-outline-info btn-sm">
              <i class="fas fa-eye"></i>
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
      order: [[0, "DESC"]],
      createdRow: (row, data) => {
        $(row).attr("id", "permissions_" + data.id_es);
      },
    }).DataTable();
    $("#tabla_anteriores thead").addClass("thead-dark text-center");

    /*tabla para permisos de vacaciones */
  } else if ($("#tipo_anterior").val() == 2) {
    tbl_vacations = $("#tabla_anteriores").dataTable({
      processing: true,
      ajax: {
        data: { "star_date": inicio, "end_date": fin, "serch": busqueda, "option": opcion },
        method: "post",
        url: urls + "corporativo/vacaciones_todos_fecha",
        dataSrc: "",
      },
      lengthChange: true,
      ordering: true,
      responsive: true,
      autoWidth: true,
      rowId: "staffId",
      // dom: "lBfrtip",
      dom: "lfrtip",
      buttons: [
        /* {
          extend: "excelHtml5",
          title: "Vacaciones",
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6],
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
      anguage: {
        url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
      },
      columns: [
        {
          data: "id_vcns",
          title: "FOLIO",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            let fecha_creacion = moment(data["fecha_registro"]).format('YYYY-MM-DD');
            return $.trim(data["fecha_creacion"]) === "0000-00-00"
              ? "---"
              : ` <div class="mr-auto">${fecha_creacion} </div> `;
          },
          title: "CREACIÓN",
          className: "text-center",
        },
        {
          data: "nombre_solicitante",
          title: "USUARIO",
        },
        {
          data: "num_dias_a_disfrutar",
          title: "DIAS",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {

            let fecha_salida = moment(data["dias_a_disfrutar_del"]).format('DD-MM-YYYY');
            return $.trim(data["dias_a_disfrutar_del"]) === "0000-00-00"
              ? "---"
              : ` <div class="mr-auto"> ${fecha_salida} </div> `;
          },
          title: "DEL",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {

            let fecha_entrada = moment(data["dias_a_disfrutar_al"]).format('DD-MM-YYYY');
            return $.trim(data["dias_a_disfrutar_al"]) === "0000-00-00"
              ? "---"
              : ` <div class="mr-auto"> ${fecha_entrada} </div> `;
          },
          title: "AL",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            action = (data["id_vcns"] > 8694) ? `onclick="verFechas(${data["id_vcns"]})"` : 'disabled'
            color = (data["id_vcns"] > 8694) ? `outline-primary` : 'secondary'
            return `<button class=" btn btn-${color}" ${action}>
              <i class="fas fa-calendar-day"></i>
              </button>
              `;
          },
          title: "DIAS",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            let fecha_entrada = moment(data["regreso"]).format('DD-MM-YYYY');
            return $.trim(data["regreso"]) === "0000-00-00"
              ? "---"
              : ` <div class="mr-auto"> ${fecha_entrada} </div> `;
          },
          title: "REGRESA",
          className: "text-center",
        },

        {
          data: null,
          render: function (data, type, full, meta) {
            switch (data["estatus"]) {
              case "Rechazada":
                return `<span class="badge badge-danger">${data["estatus"]}</span>`;
                break;
              case "Autorizada":
                return `<span class="badge badge-success">${data["estatus"]}</span>`;
                break;
              case "Cancelada":
                return `<span class="badge" style="color:#fff;background-color:#f76a77;">${data["estatus"]}</span>`;
                break;
              default:
                return `<span class="badge badge-warning">${data["estatus"]}</span>`;
                break;
            }
          },
          title: "ESTATUS",
          className: "text-center",
        },
        {
          data: null,
          title: "Acciones",
          className: "text-center",
        },
      ],
      destroy: "true",
      columnDefs: [
        {
          targets: 9,
          render: function (data, type, full, meta) {
            return ` <div class="pull-right mr-auto">
              <a href="${urls}permisos/vacaciones/${$.md5(key + data["id_vcns"])}" target="_blank" class="btn btn-outline-info btn-sm">
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
        $(row).attr("id", "vacation_" + data.id_vcns);
      },
    }).DataTable();
    $("#tabla_anteriores thead").addClass("thead-dark text-center");
  }
  $("#btm_data_table").prop("disabled", false);
});

$("#tipo_busqueda").on("change", function (e) {
  e.preventDefault();
  $("#opcion_div").empty();
  if ($("#tipo_busqueda").val() == 2) {
    $("#opcion_div").append(`
      <label>Numero de Nomina</label>
      <input type="number" id="opcion" class="form-control" min="1" required>`);
  }
  if ($("#tipo_busqueda").val() == 3) {
    $("#opcion_div").append(`
      <label for="ingenieria">Departamento:</label>
          <select name="opcion" id="opcion" class="form-control select2-hidden-accessible" style="width: 100%; height: calc(2.25rem + 2px);" required>
          </select>`);
    $("#opcion").select2();
  } $.ajax({
    url: `${urls}permisos/departamentos`,
    type: "post",
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (resp) {
      $("#opcion").append('<option value="">Seleccionar...</option>');
      resp.forEach(key => {
        $("#opcion").append(`<option value="${key.id_depto}">${key.departament}</option>`);
      });
    }
  });

});

function limpiarError(campo) {
  document.getElementById(campo.id).classList.remove('has-error')
  document.getElementById("error_" + campo.id).textContent = '';
}

function verFechas(id_folio) {
  $("#div_dias").empty();
  let data = new FormData();
  data.append("id_folio", id_folio);
  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}permisos/dias_vacaciones`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    async: true,
    dataType: "json",
    success: function (resp) {
      if (resp) {
        var i = 0
        resp.forEach(r => {
          var styl = (i == 0) ? '' : 'margin-top: 10px;';
          $("#div_dias").append(`<div class="row" style="${styl}">
            <input type="date" class="form-control" style="text-align: center;" value="${r.date_vacation}" readonly>
          </div>`);
          i++;
        });
        $("#fechasVacacionesModal").modal("show");
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

