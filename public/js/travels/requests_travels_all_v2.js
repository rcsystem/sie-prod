/**
 * ARCHIVO MODULO VIAJES
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EDITOR:HORUS RIVAS PEDRAZA
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */

$(document).ready(function () {
  tbl_viaticos = $("#tabla_viaticos").dataTable({
    processing: true,
    ajax: {
      method: "post",
      url: `${urls}viajes/todos_viaticos`,
      dataSrc: "",
    },
    lengthChange: true,
    ordering: true,
    responsive: true,
    autoWidth: false,
    rowId: "staffId",
    dom: "lfrtip",
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
        data: "folio",
        title: "FOLIO",
        className: "text-center"
      },
      {
        data: "creacion",
        title: "FECHA CREACIÓN",
        className: "text-center"
      },
      {
        data: "grado",
        title: "GRADO",
        className: "text-center"
      },
      {
        data: "user_name",
        title: "USUARIO",
        className: "text-center"
      },
      {
        data: "nomina",
        title: "NOMINA",
        className: "text-center"
      },
      {
        data: "fechas",
        title: "INICIO -- FINAL",
        className: "text-center"
      },
      {
        data: "tipo_viaje",
        title: "TIPO",
        className: "text-center"
      },
      {
        data: "avion",
        title: "AVION",
        className: "text-center"
      },
      {
        data: "destino",
        title: "DESTINO",
        className: "text-center"
      },
      {
        data: null,
        render: function (data) {
          var status = '';
          if (data["txt"] == null) {
            status = '<span class="badge badge-warning">Error</span>';
          } else {
            status = `<span class="badge" style="background-color: ${data["color"]}; color:#FFF;">${data["txt"]}</span>`;
          }
          return status;
        },
        title: "ESTADO",
        className: "text-center"
      },
      {
        data: null,
        render: function (data) {
          var status = '';
          if (data["txt"] == null) {
            status = '<span class="badge badge-warning">Error</span>';
          } else {
            status = `<span class="badge badge-${data["verification_color"]}" >${data["verification_txt"]}</span>`;
          }
          return status;
        },
        title: "COMPROBACION",
        className: "text-center"
      },
      {
        data: null,
        render: function (data, type, full, meta) {
          let zip = (data["verification_status"] > 1)
            ? ` <a class="dropdown-item" style="cursor:pointer;" onClick="zipDownload(${data["folio"]},1)">Comprobantes</a>`
            : ``;
          // let excel = (data["verification_status"] == 3)
          let excel = (data["verification_status"] > 1)
            ? ` <a class="dropdown-item" style="cursor:pointer;" id="xlsx_${data["folio"]}_1" onClick="excelDowload(${data["folio"]},1)">Reporte</a>`
            : ``;
          let cerrar = (data["request_status"] > 1)
            ? `<a class="dropdown-item" style="cursor:pointer;" onClick="handleAutorizeTravel(${data["folio"]},3)">Cerrar</a>`
            : ``;

          const miniMenu = (data["verification_status"] == 1) ? `<button type="button" class="btn btn-secondary btn-sm">
              <i class="fas fa-file-alt"></i>
            </button>` :
            `<button id="btnGroupDropPermisos" type="button" class="btn btn-outline-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-file-alt"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDropPermisos">
            ${zip}
            ${excel}
          </div>`;

          const estados_cuenta = (data["request_status"] == 2 || data["request_status"] == 4) ? `<a href="${urls}viajes/ver-estados-cuenta-por-folio/1/${$.md5(key + data["folio"])}" target="_blank" class="btn btn-outline-info btn-sm">
              <i class="fas fa-credit-card"></i>
            </a>`
            : `<a class="btn btn-secondary btn-sm">
              <i class="fas fa-credit-card"></i>
          </a>`;

          return ` <div class="mr-auto">
            <div class="btn-group">    
              <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-user-check"></i>
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" style="cursor:pointer;" onClick="handleAutorizeTravel(${data["folio"]})">Autorizacion</a>
                <a class="dropdown-item" style="cursor:pointer;" onClick="handleAutorizeTravel(${data["folio"]},2)">Cancelacion</a>
                ${cerrar}
              </div> 
            </div>   
            ${estados_cuenta}
            <div class="btn-group">
              ${miniMenu}
            </div>
            <button type="button" class="btn btn-outline-danger btn-sm " onclick="handleDeleteTravel(${data["folio"]})">
              <i class="fas fa-trash-alt"></i>
            </button>
          </div> `;
        },
        title: "ACCIONES",
        className: "text-center"
      },
    ],
    destroy: "true",
    /*  columnDefs: [
       {
         targets: 6,
        
       },
 
     ], */

    order: [[0, "DESC"]],

    createdRow: (row, data) => {
      $(row).attr("id", "travel_" + data.folio);
    },
  }).DataTable();
  $('#tabla_viaticos thead').addClass('thead-dark text-center');

  tbl_gastos = $("#tabla_gastos").dataTable({
    processing: true,
    ajax: {
      method: "post",
      url: `${urls}viajes/todos_gastos`,
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
        data: "folio",
        title: "FOLIO",
        className: "text-center"
      },
      {
        data: "creado",
        title: "FECHA CREACION",
        className: "text-center"
      },
      {
        data: "user_name",
        title: "USUARIO",
        className: "text-center"
      },
      {
        data: "nomina",
        title: "NOMINA",
        className: "text-center"
      },
      {
        data: "fechas",
        title: "INICIO -- FINAL",
        className: "text-center"
      },
      {
        data: "total",
        title: "MONTO SOLICITADO",
        className: "text-center"
      },

      {
        data: null,
        render: function (data) {
          var status = '';
          if (data["txt"] == null) {
            status = '<span class="badge badge-warning">Error</span>';
          } else {
            status = `<span class="badge" style="background-color: ${data["color"]}; color:#FFF;">${data["txt"]}</span>`;
          }
          return status;
        },
        title: "ESTADO",
        className: "text-center"
      },
      {
        data: null,
        render: function (data) {
          var status = '';
          if (data["txt"] == null) {
            status = '<span class="badge badge-warning">Error</span>';
          } else {
            status = `<span class="badge badge-${data["verification_color"]}" >${data["verification_txt"]}</span>`;
          }
          return status;
        },
        title: "COMPROBACION",
        className: "text-center"
      },
      {
        data: null,
        render: function (data, type, full, meta) {
          console.log(data["folio"], " -- ", data["verification_status"]);
          /* let zip = (data["verification_status"] > 1)
            ? ` `
            : ``;
          // let excel = (data["verification_status"] == 3)
          let excel = (data["verification_status"] > 1)
            ? ` `
            : ``; */
          let cerrar = (data["request_status"] > 1)
            ? `<a class="dropdown-item" style="cursor:pointer;" onClick="handleAutorizeExpenses(${data["folio"]},3)">Cerrar</a>`
            : ``;

          const miniMenu = (data["verification_status"] == 1) ? `<button type="button" class="btn btn-secondary btn-sm">
              <i class="fas fa-file-alt"></i>
            </button>` :
            `<button id="btnGroupDropPermisos" type="button" class="btn btn-outline-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-file-alt"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDropPermisos">
              <a class="dropdown-item" style="cursor:pointer;" onClick="zipDownload(${data["folio"]},2)">Comprobantes</a>
              <a class="dropdown-item" style="cursor:pointer;" id="xlsx_${data["folio"]}_1" onClick="excelDowload(${data["folio"]},2)">Reporte</a>
          </div>`;

          const estados_cuenta = (data["request_status"] == 2 || data["request_status"] == 4) ? `<a href="${urls}viajes/ver-estados-cuenta-por-folio/2/${$.md5(key + data["folio"])}" target="_blank" class="btn btn-outline-info btn-sm">
              <i class="fas fa-credit-card"></i>
            </a>`
            : `<a class="btn btn-secondary btn-sm">
              <i class="fas fa-credit-card"></i>
          </a>`;

          return ` <div class="mr-auto">
            <div class="btn-group">    
              <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-user-check"></i>
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" style="cursor:pointer;" onClick="handleAutorizeExpenses(${data["folio"]})">Autorizacion</a>
                <a class="dropdown-item" style="cursor:pointer;" onClick="handleAutorizeExpenses(${data["folio"]},2)">Cancelacion</a>
                ${cerrar}
              </div> 
            </div>   
            ${estados_cuenta}
            <div class="btn-group">
              ${miniMenu}
            </div>
            <button type="button" class="btn btn-outline-danger btn-sm " onclick="handleDeleteExpenses(${data["folio"]})">
              <i class="fas fa-trash-alt"></i>
            </button>
          </div> `;
        },
        title: "ACCIONES",
        className: "text-center"
      },
    ],
    destroy: "true",
    /*  columnDefs: [
       {
         targets: 6,
        54202
       },
 
     ], */
    order: [[0, "DESC"]],

    createdRow: (row, data) => {
      $(row).attr("id", "expenses_" + data.folio);
    },
  }).DataTable();
  $('#tabla_gastos thead').addClass('thead-dark text-center');
});

$("#form_estado_cuenta").submit(function (e) {
  e.preventDefault();
  var error = 0;
  const btn = document.getElementById('btn_estado_cuenta');
  const archivo = document.getElementById('archivo');
  if (archivo.value.length == 0) {
    error++;
    $("#lbl_" + archivo.id).addClass('has-error');
    $("#error_" + archivo.id).text('Archivo requerido');
  } else if (archivo.value.split(".").pop() != "xlsx") {
    error++;
    $("#lbl_" + archivo.id).addClass('has-error');
    $("#error_" + archivo.id).text('Archivo .xlsx necesario');
  } else {
    $("#lbl_" + archivo.id).removeClass('has-error');
    $("#error_" + archivo.id).text('');
  }

  if (error != 0) {
    return false
  }
  const timerInterval = Swal.fire({
    iconHtml: '<i class="fas fa-file-upload"></i>',
    title: 'Subiendo Datos!',
    html: 'Espere unos Segundos.',
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });
  btn.disabled = true;
  const data = new FormData($("#form_estado_cuenta")[0]);
  $.ajax({
    data: data,
    url: `${urls}viajes/subir_estado_cuenta_masivo`,
    type: "POST",
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (save) {
      btn.disabled = false;
      Swal.close(timerInterval);
      if (save.hasOwnProperty('xdebug_message')) {
        Swal.fire({
          icon: "error",
          title: "Oops, Exception...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
        console.log('Mensaje de xdebug:', response.xdebug_message);
      } else if (save == true) {
        $("#lbl_archivo").empty();
        $("#lbl_archivo").append('Seleccionar Excel');
        document.getElementById('form_estado_cuenta').reset();
        console.log('mandasivo notify');
        NotifyMassive();
        // tbl_amount_state.ajax.reload(null, false);
      } else if (!isNaN(save)) {
        $("#lbl_archivo").empty();
        $("#lbl_archivo").append('Seleccionar Excel');
        document.getElementById('form_estado_cuenta').reset();
        Swal.fire({
          icon: "info",
          title: `Datos interrumpidos en Fila ${save}`,
          text: `Se guardaron los datos hasta la fila ${save - 1}. Revisa y Edita el Archivo`,
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Ocurrio un error en el servidor! Contactar con el Administrador",
        });
      }
    }, error: function () {
      btn.disabled = false;
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ocurrio un error en el servidor! Contactar con el Administrador",
      });
    },
  }).fail(function (jqXHR, textStatus, errorThrown) {
    btn.disabled = false;
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
    } else if (textStatus === 'parsererror') {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Error de análisis JSON solicitado.",
      });
    } else if (textStatus === 'timeout') {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Time out error.",
      });
    } else if (textStatus === 'abort') {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ajax request aborted.",
      });
    } else {
      alert('Uncaught Error: ' + jqXHR.responseText);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: `Uncaught Error: ${jqXHR.responseText}`,
      });
    }
  })
})

function NotifyMassive() {
  const timerInterval = Swal.fire({
    iconHtml: '<i class="fas fa-envelope-open-text"></i>',
    title: 'Éxito de Carga Masiva',
    html: "Notificando a los todos los usuarios <br> sus Comprobaciones pendientes.",
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });
  $.ajax({
    url: `${urls}viajes/notificar_estado_cuenta_masivo`,
    type: "POST",
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (save) {
      Swal.close(timerInterval);
      if (save.hasOwnProperty('xdebug_message')) {
        Swal.fire({
          icon: "error",
          title: "Oops, Exception...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
        console.log('Mensaje de xdebug:', response.xdebug_message);
      } else if (save == true) {
        Swal.fire({
          icon: "success",
          title: "!Proceso Exitoso¡",
          text: "Carga y Notificación Masiva Completada.",
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Ocurrio un error en el servidor! Contactar con el Administrador",
        });
      }
    }, error: function () {
      btn.disabled = false;
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ocurrio un error en el servidor! Contactar con el Administrador",
      });
    },
  }).fail(function (jqXHR, textStatus, errorThrown) {
    btn.disabled = false;
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
    } else if (textStatus === 'parsererror') {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Error de análisis JSON solicitado.",
      });
    } else if (textStatus === 'timeout') {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Time out error.",
      });
    } else if (textStatus === 'abort') {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ajax request aborted.",
      });
    } else {
      alert('Uncaught Error: ' + jqXHR.responseText);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: `Uncaught Error: ${jqXHR.responseText}`,
      });
    }
  })
}


function zipDownload(folio, tipo_gasto) {
  //e.preventDefault();
  console.log("folio", folio);
  console.log("tipo_gasto", tipo);
  let timerInterval = Swal.fire({ //se le asigna un nombre al swal
    title: 'Descargando Zip!',
    html: 'Espere unos Segundos.',
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });
  var tipo = (tipo_gasto == 1) ? "Viatico" : "Gasto";
  var nomArchivo = `${tipo}_Folio_${folio}.zip`;
  var param = JSON.stringify({
    folio: folio,
    tipo_gasto: tipo_gasto
  });
  var pathservicehost = `${urls}viajes/descargar_gasto_zip`;
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
      //$("#generarReporte").prop("disabled", false);
      Swal.close(timerInterval); // cierra el swal en ejecucion (nombre del swal)
      //link.click();
    } else {
      //$("#generarReporte").prop("disabled", false);

      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No es posible acceder al archivo, probablemente no existe. Comunicarse con el Administrador",
      });
    }
  };
  xhr.send(`data=${param}`);

}

function excelDowload(folio, tipo) {
  $(`#xlsx_${folio}_${tipo}`).prop("disabled", true);
  let timerInterval = Swal.fire({ //se le asigna un nombre al swal
    title: '<i class="fas fa-file-excel" style="margin-right:5px"></i> Generando Reporte en Excel!',
    html: 'Espere unos Segundos.',
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });
  let reportes = (tipo == 1) ? "Viaticos" : "Gastos";
  var nomArchivo = `reporte_${reportes}_Folio_${folio}.xlsx`;
  var param = JSON.stringify({
    request: folio,
    type: tipo
  });
  var pathservicehost = `${urls}/viajes/reporte_folio_tipo`;
  var xhr = new XMLHttpRequest();
  xhr.open("POST", pathservicehost, true);
  xhr.responseType = "blob";
  //Send the proper header information along with the request
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onload = function (e) {
    $(`#xlsx_${folio}_${tipo}`).prop("disabled", false);
    Swal.close(timerInterval); // cierra el swal en ejecucion (nombre del swal)
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
      // $("#form_reportes_por_direccion")[0].reset();
      // $("#permisos_div").empty();
      // $("#parametro").empty();
      //Simulamos un clic del usuario
      //no es necesario agregar el link al DOM.
      link.dispatchEvent(clicEvent);
      //link.click();
    } else {
      Swal.fire({
        icon: "info",
        title: "Oops...",
        text: "No es posible acceder al archivo, probablemente no existe.",
      });
    }
  };
  xhr.send("data=" + param);

}

function handleDeleteExpenses(folio) {
  Swal.fire({
    title: `Eliminar Solicitud de Gastos : ${folio}`,
    text: "Eliminar Solicitud de Gastos?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Eliminar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.value) {
      const timerInterval = Swal.fire({ //se le asigna un nombre al swal
        title: '<i class="fas fas fa-edit" style="margin-right: 10px;"></i>¡Actualizando el Registro!',
        html: 'Espere unos Segundos.',
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
      });
      let dataForm = new FormData();
      dataForm.append("folio", folio);
      $.ajax({
        data: dataForm, //datos que se envian a traves de ajax
        url: `${urls}viajes/eliminar_gastos`,//archivo que recibe la peticion
        type: "post", //método de envio
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentType
        dataType: "json",
        success: function (resp) {
          console.log(resp);
          Swal.close(timerInterval);
          if (resp) {
            tbl_gastos.ajax.reload(null, false);
            Swal.fire("!Eliminado correctamente!", "", "success");
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
  });
}

function handleDeleteTravel(folio) {
  Swal.fire({
    title: `Eliminar Solicitud de Viaticos: ${folio}`,
    text: "Deseas Eliminar esta Solicitud de Viaticos!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Eliminar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.value) {
      const timerInterval = Swal.fire({ //se le asigna un nombre al swal
        title: '<i class="fas fas fa-edit" style="margin-right: 10px;"></i>¡Actualizando el Registro!',
        html: 'Espere unos Segundos.',
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
      });
      let dataForm = new FormData();
      dataForm.append("folio", folio);

      $.ajax({
        data: dataForm, //datos que se envian a traves de ajax
        url: `${urls}viajes/eliminar_viaticos`,//archivo que recibe la peticion
        type: "post", //método de envio
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentType
        dataType: "json",
        success: function (resp) {
          Swal.close(timerInterval);
          console.log(resp);
          if (resp) {
            tbl_viaticos.ajax.reload(null, false);
            //console.log("Hola Mundo Delete" + row);
            document.getElementById("travel_" + folio).style.display = "none";
            $("tr.child").remove();
            Swal.fire("!Eliminado correctamente!", "", "success");
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
  });
}

function handleAutorizeExpenses(id_folio, opc = null) {
  Swal.fire({
    title: '<i class="fas fa-file-signature" style="margin-right: 10px;"></i>¿Autorizar Solicitud de Viatico?',
    showDenyButton: true,
    // showCancelButton: true,
    showCloseButton: true,
    confirmButtonText: '<i class="fas fa-check" style="margin-right: 10px;"></i>Confirmar',
    confirmButtonColor: "#28A745",
    denyButtonText: `<i class="fas fa-times" style="margin-right: 10px;"></i>Rechazar`,
    cancelButtonColor: "#B70923",
    cancelButtonText: `<i class="fas fa-ban" style="margin-right: 10px;"></i>Cancelar`,
  }).then((result) => {
    if (result.isConfirmed) {
      actualizarRequest(2, id_folio, 2)
    } else if (result.isDenied) {
      actualizarRequest(3, id_folio, 2)
    }
  })
  if (opc == 2) {
    Swal.fire({
      title: 'Motivo de Cancelación',
      input: 'text',
      inputAttributes: {
        autocapitalize: 'off',
        placeholder: 'Ingrese el motivo de la cancelación',
      },
      allowOutsideClick: false,
      showCancelButton: true,
      confirmButtonColor: "#B70923",
      confirmButtonText: '<i class="fas fa-ban" style="margin-right: 10px;"></i>Confirmar Cancelacion',
      cancelButtonText: `Cancelar`,
      inputValidator: (value) => {
        if (!value || value.length < 15) {
          return 'El motivo debe tener al menos 15 caracteres.';
        }
      },
    }).then((result) => {
      if (result.isConfirmed) {
        const motivo = result.value;
        actualizarRequest(0, id_folio, 2, motivo)
      }
    });
  }
  if (opc == 3) {
    Swal.fire({
      title: '<i class="fas fa-file-signature" style="margin-right: 10px;"></i>¿Autorizar Solicitud de Viatico?',
      showDenyButton: true,
      // showCancelButton: true,
      showCloseButton: true,
      confirmButtonText: '<i class="far fa-file-alt" style="margin-right: 10px;"></i>Cerrar',
      confirmButtonColor: "#28A745",
      denyButtonText: `Cancelar`,
    }).then((result) => {
      if (result.isConfirmed) {
        actualizarRequest(4, id_folio, 2)
      }
    })
  }
}

function handleAutorizeTravel(id_folio, opc = null) {
  Swal.fire({
    title: '<i class="fas fa-file-signature" style="margin-right: 10px;"></i>¿Autorizar Solicitud de Viatico?',
    showDenyButton: true,
    // showCancelButton: true,
    showCloseButton: true,
    confirmButtonText: '<i class="fas fa-check" style="margin-right: 10px;"></i>Confirmar',
    confirmButtonColor: "#28A745",
    denyButtonText: `<i class="fas fa-times" style="margin-right: 10px;"></i>Rechazar`,
    cancelButtonColor: "#B70923",
    cancelButtonText: `<i class="fas fa-ban" style="margin-right: 10px;"></i>Cancelar`,
  }).then((result) => {
    if (result.isConfirmed) {
      actualizarRequest(2, id_folio, 1)
    } else if (result.isDenied) {
      actualizarRequest(3, id_folio, 1)
    } /* else if (result.dismiss === Swal.DismissReason.cancel) {
      Swal.fire({
        title: 'Motivo de Cancelación',
        input: 'text',
        inputAttributes: {
          autocapitalize: 'off',
          placeholder: 'Ingrese el motivo de la cancelación',
        },
        allowOutsideClick: false,
        showCancelButton: true,
        confirmButtonColor: "#B70923",
        confirmButtonText: '<i class="fas fa-ban" style="margin-right: 10px;"></i>Confirmar Cancelacion',
        cancelButtonText: `Atrás`,
        inputValidator: (value) => {
          if (!value || value.length < 15) {
            return 'El motivo debe tener al menos 15 caracteres.';
          }
        },
      }).then((result) => {
        if (result.isConfirmed) {
          const motivo = result.value;
          actualizarRequest(0, id_folio, 1, motivo)
        } else if (result.dismiss === Swal.DismissReason.cancel) {
          handleAutorizeExpenses(id_folio);
        }
      });
    } */
  })
  if (opc == 2) {
    Swal.fire({
      title: 'Motivo de Cancelación',
      input: 'text',
      inputAttributes: {
        autocapitalize: 'off',
        placeholder: 'Ingrese el motivo de la cancelación',
      },
      allowOutsideClick: false,
      showCancelButton: true,
      confirmButtonColor: "#B70923",
      confirmButtonText: '<i class="fas fa-ban" style="margin-right: 10px;"></i>Confirmar Cancelacion',
      cancelButtonText: `Cancelar`,
      inputValidator: (value) => {
        if (!value || value.length < 15) {
          return 'El motivo debe tener al menos 15 caracteres.';
        }
      },
    }).then((result) => {
      if (result.isConfirmed) {
        const motivo = result.value;
        actualizarRequest(0, id_folio, 1, motivo)
      }
    });
  }
  if (opc == 3) {
    Swal.fire({
      title: '<i class="fas fa-file-signature" style="margin-right: 10px;"></i>¿Autorizar Solicitud de Viatico?',
      showDenyButton: true,
      // showCancelButton: true,
      showCloseButton: true,
      confirmButtonText: '<i class="far fa-file-alt" style="margin-right: 10px;"></i>Cerrar',
      confirmButtonColor: "#28A745",
      denyButtonText: `Cancelar`,
    }).then((result) => {
      if (result.isConfirmed) {
        actualizarRequest(4, id_folio, 1)
      }
    })
  }
}

function actualizarRequest(status, id, type, motive = null) {
  const timerInterval = Swal.fire({ //se le asigna un nombre al swal
    title: '<i class="fas fas fa-edit" style="margin-right: 10px;"></i>¡Actualizando el Registro!',
    html: 'Espere unos Segundos.',
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });
  const route = (type == 1) ? 'autorizar_viaticos' : 'autorizar_gasto';
  const dataToUpdate = new FormData();
  dataToUpdate.append('status', status);
  dataToUpdate.append('id_folio', id);
  dataToUpdate.append('motivo', motive);

  $.ajax({
    type: "post",
    url: `${urls}viajes/${route}`,
    data: dataToUpdate,
    cache: false,
    dataType: "json",
    contentType: false,
    processData: false,
    success: function (save) {
      Swal.close(timerInterval);
      if (save === true) {
        if (type == 1) {
          tbl_viaticos.ajax.reload(null, false);
        } else {
          tbl_gastos.ajax.reload(null, false);
        }
        Swal.fire({
          icon: 'success',
          title: "¡Actualizacion Exitosa!",
          text: 'Se registró el cambio en la Solicitud',
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

/* 
function testAnim(e) {
  $('.modal .gastos').attr('class', `modal-dialog modal-xl ${e} animated`);
};

$('#autorizarGastoModal').on('show.bs.modal', function (e) {
  $("html, body").css("overflow", "hidden");
  var anim = "bounceInRight";
  testAnim(anim);
})
$('#autorizarGastoModal').on('hide.bs.modal', function (e) {
  $("html, body").css("overflow", "scroll");
  var anim = "slideOutLeft";
  testAnim(anim);
})

function testAnimPermisos(e) {
  $('.modal .viaticos').attr('class', `modal-dialog modal-xl ${e} animated`);
};

$('#autorizarViaticoModal').on('show.bs.modal', function (e) {
  var anim = "bounceInRight";
  testAnimPermisos(anim);
})
$('#autorizarViaticoModal').on('hide.bs.modal', function (e) {
  var anim = "slideOutLeft";
  testAnimPermisos(anim);
});
 */

function download() {
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
  downloadOneDocument.href = `${urls}/public/doc/politicas/FormatoEstadoCuentaMasivo.xlsx`;
  downloadOneDocument.download = 'FormatoSubir_EstadoCuentaMasivo';
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

function validarFile(campo) {
  const input = campo;
  if (input.value.length > 0) {
    $("#lbl_" + input.id).empty();
    $("#lbl_" + input.id).append(`${document.getElementById(input.id).files[0].name}`);
    $("#lbl_" + input.id).attr('style', 'color:#343a40!important;');
    $("#lbl_" + input.id).removeClass('has-error');
    $("#error_" + input.id).text('');
  }
}

$("#btn_actualizar_cuentas").on("click", function () {
  // Función a ejecutar cuando se hace clic en el botón
  // alert("¡Haz hecho clic en el botón!");

  // Realiza la solicitud AJAX
  

  $.ajax({

   // data: dataForm, //datos que se envian a traves de ajax
    url: `${urls}viajes/actualizar_cuentas`,//archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    dataType: "json",
    beforeSend: function() {
      // Código a ejecutar antes de enviar la solicitud
      $("#result_cuentas").show();
      $("#result_cuentas").html(`<div class="alert alert-primary alert-dismissible fade show" role="alert">
      <strong>Actualizando Datos!</strong> 
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>`);
    },
    success: function (data) {
      // Maneja la respuesta exitosa aquí
      if (data) {
        
        $("#result_cuentas").html(`<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Cuentas Actualizadas!</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>`);
      }
    },
    error: function () {
      // Maneja el error aquí
      $("#result_cuentas").html('<p>Error al cargar los datos.</p>');
    },
    complete: function () {
      // Oculta el indicador de carga después de que la solicitud se haya completado
     // $("#result_cuentas").empty();
      $("#result_cuentas").fadeOut(4000, function() {
        // Una vez que se ha completado el desvanecimiento, limpiar el contenido del div
        $("#result_cuentas").empty().hide();
      });
    }
  });
});