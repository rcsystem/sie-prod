/*
 * ARCHIVO MODULO PAPELERIA
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EDITOR: HORUS SAMAEL RIVAS PEDRAZA
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */

const colorStatus = { "Rechazada": 'danger', "Autorizada": 'success', "Cancelada": 'cancel', "Pendiente": 'warning' }
const direction = {
  251: 'DIRECCION_COMERCIAL',
  1188: 'DIRECCION_ADMINISTRACION',
  250: 'DIRECCION_FINANZAS',
  374: 'DIRECCION_INGENERIA_R&D',
  262: 'DIRECCION_OPERACIONES',
  252: 'DIRECCION_GENERAL',
  695: 'DOS_BOCAS',
  905: 'LEGAL',
}

const inputVacaciones = flatpickr("#vacaciones_dias_disfrutar", {
  locale: "es",
  mode: "multiple",
  dateFormat: "Y-m-d",
  // Configuración de flatpickr con las fechas mínima y máxim
  onChange: function (selectedDates, dateStr, instance) {
    selectedDays = selectedDates.length;
    $("#count_array").val(selectedDays);
  }
});

const inputRegreso = flatpickr("#vacaciones_regresar_actividades", {
  locale: "es",
  dateFormat: "d/m/Y",
  // Configuración de flatpickr con las fechas mínima y máxim
});

$("#editar_vacaciones_new").submit(function (e) {
  e.preventDefault();
  const data = new FormData($("#editar_vacaciones_new")[0]);
  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}permisos/actualizar_dias_new`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    async: true,
    dataType: "json",
    success: function (save) {
      if (save === true) {
        
        tbl_vacations.ajax.reload(null, false);
        Swal.fire({
          icon: "success",
          title: "Exito",
          text: "Actualización de datos Exitosa",
        });
        $("#editarVacacionesNewModal").modal("hide");
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
        console.log("Mal Revisa");
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
})

$("#formReporte").submit(function (e) {
  e.preventDefault();
  var errors = 0;
  if ($("#tipo_reporte").val().length == 0) {
    errors++;
    $("#tipo_reporte").addClass("has-error");
    $("#error_tipo_reporte").text("Campo Requerido");
  } else {
    $("#tipo_reporte").removeClass("has-error");
    $("#error_tipo_reporte").text("");
  }

  if ($("#fecha_ini").val().length == 0) {
    errors++;
    $("#fecha_ini").addClass("has-error");
    $("#error_fecha_ini").text("Campo Requerido");
  } else {
    $("#fecha_ini").removeClass("has-error");
    $("#error_fecha_ini").text("");
  }

  if ($("#fecha_fin").val().length == 0) {
    errors++;
    $("#fecha_fin").addClass("has-error");
    $("#error_fecha_fin").text("Campo Requerido");
  } else {
    $("#fecha_fin").removeClass("has-error");
    $("#error_fecha_fin").text("");
  }
  if ($("#fecha_fin").val().length > 0 && $("#fecha_ini").val().length > 0) {
    if ($("#fecha_fin").val() < $("#fecha_ini").val()) {
      errors++;
      $("#fecha_fin").addClass("has-error");
      $("#error_fecha_fin").text("Fecha Final debe ser mayor a la Inicial");
    }
  }

  if (errors != 0) { return false }
  $("#generarReporte").prop("disabled", true);

  var fecha_inicio = $("#fecha_ini").val();
  var fecha_fin = $("#fecha_fin").val();
  var reporte = $("#tipo_reporte").val();
  var permiso = (reporte == 1) ? $("#cat_permiso").val() : "";
  const tipoReporte = {
    1: "Salidar_Entradas",
    2: "Vacaciones",
    3: "Pago_Tiempo"
  };
  let timerInterval = Swal.fire({ //se le asigna un nombre al swal
    title: 'Generando Reporte en Excel!',
    html: 'Espere unos Segundos.',
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });

  var nomArchivo = `Reporte_${tipoReporte[reporte]}_${fecha_inicio}_${fecha_fin}.xlsx`;
  var param = JSON.stringify({
    tipoReporte: reporte,
    fechaInicio: fecha_inicio,
    fechaFin: fecha_fin,
    permissions: permiso
  });
  var pathservicehost = `${urls}permisos/generar_reportes`;
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

});

$("#tipo_reporte").on('change', function () {
  $("#tipo_reporte").removeClass("has-error");
  $("#error_tipo_reporte").text("");
  $("#cat_permiso_div").empty();
  $("#cat_permiso_div").attr('class', "");
  if ($("#tipo_reporte").val() == 1) {
    $("#cat_permiso_div").attr('class', "form-group col-md-6");
    $("#cat_permiso_div").append(`<label for="cat_permiso">Tipo de Permiso</label>
    <select class="form-control" id="cat_permiso" required>
      <option value="">Seleccionar</option>
      <option value="1">Laboral</option>
      <option value="2">Personal</option>
      <option value="3">A cuenta de Vacaciones</option>
      <option value="4">Festivos</option>
      <option value="5">Servicio Medico</option>
      <option value="6">Trafico</option>
      <option value="all">Todos</option>
    </select>`);
  }
})

$("#categoria").on("change", () => {
  let categoria = $("#categoria").val();
  $("#parametro").empty();
  if (categoria == 2) {
    $("#parametro").addClass("col-md-6");
    campo = ` <label for="descripcion">Numero de Nomina</label>
                <input type="number" class="form-control rounded-0" id="num_nomina" name="num_nomina" value="" required>`;
    $("#parametro").append(campo);
  } else if (categoria == 1) {
    $("#parametro").addClass("col-md-6");
    campo = `<label>Dirección:</label>
        <select name="depto" id="depto" class="form-control rounded-0" required></select>   `;
    $("#parametro").append(campo);
    $.ajax({
      // data: data, //datos que se envian a traves de ajax
      // url: `${urls}permisos/departamentos`,
      url: `${urls}permisos/direccion`,
      type: "post", //método de envio
      processData: false, // dile a jQuery que no procese los datos
      contentType: false, // dile a jQuery que no establezca contentType
      dataType: "json",
      success: function (resp) {
        if (resp) {
          $("#depto").append('<option value="">Seleccionar...</option>');
          $.each(resp, function (id, value) {
            $("#depto").append(`<option value='${value.id_responsible}'>${value.direction}</option>`);
          });
        }
      },
      error: function () {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Ocurrio un error en el servidor! Contactar con el Administrador",
        });
      },
    });
  }
});

$("#tipo_reportes").on('change', function () {
  $("#permisos_div").empty();
  $("#permisos_div").attr('class', "");
  if ($("#tipo_reportes").val() == 1) {
    $("#permisos_div").attr('class', "form-group col-md-6");
    $("#permisos_div").append(`<label for="tipo_permisos">Tipo de Permiso</label>
    <select class="form-control" id="tipo_permisos" required>
      <option value="">Seleccionar</option>
      <option value="1">Laboral</option>
      <option value="2">Personal</option>
      <option value="3">A cuenta de Vacaciones</option>
      <option value="4">Festivo</option>
      <option value="5">Servicio Medico</option>
      <option value="6">Trafico</option>
      <option value="all">Todos</option>
    </select>`);
  }
})

$("#form_reportes_por_direccion").on("submit", function (e) {
  e.preventDefault();

  const finFecha = document.getElementById('fecha_final');
  const iniFecha = document.getElementById('fecha_inicial');
  var error_fecha_final = '';

  if (finFecha.value.length == 0) {
    error_fecha_final = 'Campo Requerido';
    finFecha.classList.add('has-error');
    document.getElementById("error_" + finFecha.id).textContent = error_fecha_final;
  } else if (finFecha.value < iniFecha.value) {
    error_fecha_final = 'Fecha Final debe ser mayor a Fecha Inicial';
    finFecha.classList.add('has-error')
    document.getElementById("error_" + finFecha.id).textContent = error_fecha_final;
  } else {
    error_fecha_final = '';
    finFecha.classList.remove('has-error')
    document.getElementById("error_" + finFecha.id).textContent = error_fecha_final;
  }

  if (error_fecha_final != '') {
    return false;
  }

  let fecha_inicio = $("#fecha_inicial").val();
  let fecha_fin = $("#fecha_final").val();
  let reporte = $("#tipo_reportes").val();
  let categoria = $("#categoria").val();
  let permisos = (reporte == 1) ? $("#tipo_permisos").val() : "";
  let parametro = 0;
  categoria == 1
    ? (parametro = $("#depto").val())
    : (parametro = $("#num_nomina").val());
  reporte == 1 ? "permisos" : "vacaciones";
  categoria == 1
    ? (name_arch = direction[$("#depto").val()])
    : (name_arch = 'USUARIO_' + $("#num_nomina").val());
  $("#generar_reporte").prop("disabled", true);
  let timerInterval = Swal.fire({ //se le asigna un nombre al swal
    title: 'Generando Reporte en Excel!',
    html: 'Espere unos Segundos.',
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });
  if (fecha_inicio < fecha_fin) {

    let reportes = reporte == 1 ? "permisos" : "vacaciones";

    var nomArchivo = `reporte_${reportes}_de_${name_arch}.xlsx`;
    var param = JSON.stringify({
      tipo_reportes: reporte,
      fecha_inicio: fecha_inicio,
      fecha_fin: fecha_fin,
      categoria: categoria,
      parametro: parametro,
      permissions: permisos
    });
    var pathservicehost = `${urls}/permisos/genera_reportes_director`;
    var xhr = new XMLHttpRequest();
    xhr.open("POST", pathservicehost, true);
    xhr.responseType = "blob";
    //Send the proper header information along with the request
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function (e) {
      $("#generar_reporte").prop("disabled", false);
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
        Swal.close(timerInterval); // cierra el swal en ejecucion (nombre del swal)
        //link.click();
      } else {
        alert(" No es posible acceder al archivo, probablemente no existe.");
      }
    };
    xhr.send("data=" + param);
  } else {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Algo salió Mal! La Fecha Final es Anterior a la Fecha Inicial",
    });
  }
});


$("#formReportesGlobal").on("submit", function (e) {
  e.preventDefault();


  $("#reporte_global").prop("disabled", true);

  let timerInterval = Swal.fire({ //se le asigna un nombre al swal
    title: 'Generando Reporte en Excel!',
    html: 'Espere unos Segundos.',
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });
  let reportes = "global";

  var nomArchivo = `reporte_global.xlsx`;
  var param = JSON.stringify({
    tipo_reporte: reportes
  });
  var pathservicehost = `${urls}/permisos/reporte_global`;
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
      $("#reporte_global").prop("disabled", false);
      Swal.close(timerInterval); // cierra el swal en ejecucion (nombre del swal)
      //link.click();
    } else {
      $("#reporte_global").prop("disabled", false);

      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No es posible acceder al archivo, probablemente no existe. Comunicarse con el Administrador",
      });
    }
  };
  xhr.send("data=" + param);

});

$("#formReportesIndividual").on("submit", function (e) {
  e.preventDefault();
  let num_nomina = $("#num_nomina").val();

  $("#reporte_individual").prop("disabled", true);
  let timerInterval = Swal.fire({ //se le asigna un nombre al swal
    title: 'Generando Reporte en Excel!',
    html: 'Espere unos Segundos.',
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });
  var nomArchivo = `reporte_individual_${num_nomina}.xlsx`;
  var param = JSON.stringify({
    num_nomina: num_nomina
  });
  var pathservicehost = `${urls}/permisos/reporte_individual`;
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
      $("#reporte_individual").prop("disabled", false);
      Swal.close(timerInterval); // cierra el swal en ejecucion (nombre del swal)
      //link.click();
    } else {
      $("#reporte_individual").prop("disabled", false);

      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No es posible acceder al archivo, probablemente no existe. Comunicarse con el Administrador",
      });
    }
  };
  xhr.send("data=" + param);

});

$("#formReportesVacacionesTotal").on("submit", function (e) {
  e.preventDefault();

  $("#reporte_vacaciones").prop("disabled", true);
  let date = new Date();
  let fecha = date.toISOString().split('T')[0];
  let timerInterval = Swal.fire({ //se le asigna un nombre al swal
    title: 'Generando Reporte en Excel!',
    html: 'Espere unos Segundos.',
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });

  var nomArchivo = `reporte_vacaciones_global_${fecha}.xlsx`;
  let reportes = "global";


  var pathservicehost = `${urls}/permisos/reporte_vacaciones_global`;
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
      $("#reporte_vacaciones").prop("disabled", false);
      Swal.close(timerInterval); // cierra el swal en ejecucion (nombre del swal)
      //link.click();
    } else {
      $("#reporte_vacaciones").prop("disabled", false);

      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No es posible acceder al archivo, probablemente no existe. Comunicarse con el Administrador",
      });
    }
  };
  xhr.send();

});

$("#formDatosGeneral").on("submit", function (e) {
  e.preventDefault();


  $("#reporte_datos_general").prop("disabled", true);
  let timerInterval = Swal.fire({ //se le asigna un nombre al swal
    title: 'Generando Reporte en Excel!',
    html: 'Espere unos Segundos.',
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });
  var nomArchivo = `reporte_datos_generales.xlsx`;

  var pathservicehost = `${urls}/permisos/reporte_datos_generales`;
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
      $("#reporte_datos_general").prop("disabled", false);
      Swal.close(timerInterval); // cierra el swal en ejecucion (nombre del swal)
      //link.click();
    } else {
      $("#reporte_datos_general").prop("disabled", false);

      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No es posible acceder al archivo, probablemente no existe. Comunicarse con el Administrador",
      });
    }
  };
  xhr.send();

});

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
        url: `${urls}permisos/entrada_salida_fecha`,
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
        },
        {
          data: "nombre_solicitante",
          title: "USUARIO",
        },
        {
          data: "salida",
          title: "SALIDA",
          className: "text-center",
        },
        {
          data: "entrada",
          title: "ENTRADA",
          className: "text-center",
        },
        {
          data: "inasistencia",
          title: "INASISTENCIA",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            return `<span class="badge" style="color:#fff;background-color:${data["colorPermiss"]};">${data["tipo_permiso"]}</span>`;
          },
          title: "TIPO PERMISO",
          className: "text-center",
        },
        {
          data: "authoriza",
          title: "AUTORIZADOR",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            return `<span class="badge badge-${colorStatus[data["estatus"]]}">${data["estatus"]}</span>`;
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
      // destroy: "true",
      columnDefs: [
        {
          targets: 8,
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
        url: urls + "permisos/vacaciones_todos_fecha",
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
          data: "authoriza",
          title: "AUTORIZADOR",
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
          targets: 10,
          render: function (data, type, full, meta) {

            var modal = (data["id_vcns"] < 8695) ? 'handleVacation' : 'handleVacationNew';
            return ` <div class="pull-right mr-auto">
            <div class="btn-group" role="group">
            <button id="btnGroupDropPermisos" type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user-check"></i>
          </button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDropPermisos">
              <a class="dropdown-item" style="cursor:pointer;" onClick="handleAuthorizeVacation(${data["id_vcns"]})">Autorizar</a>
              <a class="dropdown-item" style="cursor:pointer;" onClick="${modal}(${data["id_vcns"]})">Editar</a>
            </div>
          </div>
            
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
          <select name="opcion" id="opcion" class="form-control rounded-0" required>
          </select>`);
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

})

function handleAuthorizeVacation(id_folio) {
  let data = new FormData();

  data.append("id_folio", id_folio);

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}permisos/editar_permiso_vacations`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    async: true,
    dataType: "json",
    success: function (resp) {
      //console.log(resp);
      if (resp != "error") {
        resp.forEach(function (permiso, index) {
          $("#del_vacaciones").val(permiso.dias_a_disfrutar_del);
          $("#al_vacaciones").val(permiso.dias_a_disfrutar_al);
          $("#folio_vacaciones").val(permiso.id_vcns);
          $("#usuario_vacaciones").val(permiso.nombre_solicitante);
          $("#regresa").val(permiso.regreso);
          $("#dias").val(permiso.num_dias_a_disfrutar);
          $("#num_nomina").val(permiso.num_nomina);
          if (
            permiso.hora_entrada === "00:00:00" &&
            permiso.hora_salida === "00:00:00"
          ) {
            $("#del").val("---");
            $("#al").val("---");
          } else {
            var hr1 = moment(permiso.inasistencia_del, "DD/MM/YYYY").format(
              "DD/MM/YYYY"
            );

            var hr2 = moment(permiso.inasistencia_al, "DD/MM/YYYY").format(
              "DD/MM/YYYY"
            );
            var d = new Date(permiso.inasistencia_del);
            console.log(`rafael: ${d}`);
            console.log("FECHA BD: ", hr1);
            console.log("Año: ", hr2);
            $("#del").val(hr1);
            $("#al").val(hr2);
          }
        });

        $("#vacacionesModal").modal("show");
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
        console.log("Mal Revisa");
      }
    },
  }).fail(function (jqXHR, textStatus, errorThrown) {
    if (jqXHR.status === 0) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Fallo de conexión: ​​Verifique la red.",
      });
      $("#guardar_ticket").prop("disabled", false);
    } else if (jqXHR.status == 404) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No se encontró la página solicitada [404]",
      });
      $("#guardar_ticket").prop("disabled", false);
    } else if (jqXHR.status == 500) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Internal Server Error [500]",
      });
      $("#guardar_ticket").prop("disabled", false);
    } else if (textStatus === "parsererror") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Error de análisis JSON solicitado.",
      });
      $("#guardar_ticket").prop("disabled", false);
    } else if (textStatus === "timeout") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Time out error.",
      });
      $("#guardar_ticket").prop("disabled", false);
    } else if (textStatus === "abort") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ajax request aborted.",
      });

      $("#guardar_ticket").prop("disabled", false);
    } else {
      alert("Uncaught Error: " + jqXHR.responseText);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: `Uncaught Error: ${jqXHR.responseText}`,
      });
      $("#guardar_ticket").prop("disabled", false);
    }
  });
}

function handleEdit(a) {
  console.log("edit");
  let e = new FormData;
  e.append("id_folio", a),
    $.ajax({
      data: e,
      url: urls + "permisos/editar_permiso",
      type: "post",
      processData: !1,
      contentType: !1,
      async: !0,
      dataType: "json",
      success: function (a) {
        console.log(a);
        if (a != "error") {
          a.forEach(function (a, e) {
            hora_salida = (a.hora_salida != '00:00:00') ? a.hora_salida : "";
            hora_entrada = (a.hora_entrada != '00:00:00') ? a.hora_entrada : "";
            $("#editar_folio").val(a.id_es);
            $("#editar_usuario").val(a.nombre_solicitante);
            $("#editar_observaciones").val(a.observaciones);
            $("#editar_permiso_salida").val(a.fecha_salida);
            $("#editar_permiso_salida_h").val(hora_salida);
            $("#editar_permiso_entrada").val(a.fecha_entrada);
            $("#editar_permiso_entrada_h").val(hora_entrada);
            $("#editar_inasistencia_del").val(a.inasistencia_del);
            $("#editar_inasistencia_al").val(a.inasistencia_al);
          }),
            $("#permisosEditarModal").modal("show")
        } else {
          (Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Algo salió Mal! Contactar con el Administrador"
          }),
            console.log("Mal Revisa"))
        }
      }
    }).fail(function (a, e, t) { 0 === a.status ? (Swal.fire({ icon: "error", title: "Oops...", text: "Fallo de conexión: ​​Verifique la red." }), $("#guardar_ticket").prop("disabled", !1)) : 404 == a.status ? (Swal.fire({ icon: "error", title: "Oops...", text: "No se encontró la página solicitada [404]" }), $("#guardar_ticket").prop("disabled", !1)) : 500 == a.status ? (Swal.fire({ icon: "error", title: "Oops...", text: "Internal Server Error [500]" }), $("#guardar_ticket").prop("disabled", !1)) : "parsererror" === e ? (Swal.fire({ icon: "error", title: "Oops...", text: "Error de análisis JSON solicitado." }), $("#guardar_ticket").prop("disabled", !1)) : "timeout" === e ? (Swal.fire({ icon: "error", title: "Oops...", text: "Time out error." }), $("#guardar_ticket").prop("disabled", !1)) : "abort" === e ? (Swal.fire({ icon: "error", title: "Oops...", text: "Ajax request aborted." }), $("#guardar_ticket").prop("disabled", !1)) : (alert("Uncaught Error: " + a.responseText), Swal.fire({ icon: "error", title: "Oops...", text: `Uncaught Error: ${a.responseText}` }), $("#guardar_ticket").prop("disabled", !1)) })
}

function handleVacation(id_folio) {

  console.log("vacaciones editar: " + id_folio);
  let data = new FormData();
  data.append("id_folio", id_folio);
  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}permisos/editar_permiso_vacations`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    async: true,
    dataType: "json",
    success: function (resp) {
      //console.log(resp);
      if (resp != "error") {
        resp.forEach(function (permiso, index) {
          $("#editar_vacaciones_del").val(permiso.dias_a_disfrutar_del);
          $("#editar_vacaciones_al").val(permiso.dias_a_disfrutar_al);
          $("#editar_folio_vcns").val(permiso.id_vcns);
          $("#editar_usuario_vcns").val(permiso.nombre_solicitante);
          $("#editar_regresando").val(permiso.regreso);
          $("#editar_catidad").val(permiso.num_dias_a_disfrutar);
        });
        $("#editarVacacionesModal").modal("show");
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
        console.log("Mal Revisa");
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

function handleAuthorize(id_folio) {
  let data = new FormData();
  console.log("Folio: " + id_folio);
  data.append("id_folio", id_folio);

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}permisos/editar_permiso`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    async: true,
    dataType: "json",
    success: function (resp) {
      //console.log(resp);
      if (resp != "error") {
        resp.forEach(function (permiso, index) {
          $("#permiso_salida").val();
          $("#permiso_entrada").val();
          $("#folio").val(permiso.id_es);
          $("#usuario").val(permiso.nombre_solicitante);
          $("#observaciones").val(permiso.observaciones);
          if (
            permiso.hora_entrada == "00:00:00" &&
            permiso.hora_salida == "00:00:00"
          ) {
            $("#permiso_salida").val("----");
            $("#permiso_entrada").val("----");
          }
          if (permiso.hora_salida != "00:00:00") {
            $("#permiso_entrada").val("---");
            $("#permiso_salida").val(
              `${permiso.fecha_salida} - ${permiso.hora_salida}`
            );
            $("#permiso_inasistencia").val("----");
          }

          if (permiso.hora_entrada != "00:00:00") {
            $("#permiso_salida").val("---");
            $("#permiso_entrada").val(
              `${permiso.fecha_entrada} - ${permiso.hora_entrada}`
            );
            $("#permiso_inasistencia").val("----");
          }

          if (
            permiso.inasistencia_del != "0000-00-00" &&
            permiso.inasistencia_al != "0000-00-00"
          ) {
            $("#permiso_inasistencia").val(
              `del:  ${permiso.inasistencia_del}  al:  ${permiso.inasistencia_al}`
            );
          }
        });

        $("#permisosModal").modal("show");
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
        console.log("Mal Revisa");
      }
    },
  }).fail(function (jqXHR, textStatus, errorThrown) {
    if (jqXHR.status === 0) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Fallo de conexión: ​​Verifique la red.",
      });
      $("#guardar_ticket").prop("disabled", false);
    } else if (jqXHR.status == 404) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No se encontró la página solicitada [404]",
      });
      $("#guardar_ticket").prop("disabled", false);
    } else if (jqXHR.status == 500) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Internal Server Error [500]",
      });
      $("#guardar_ticket").prop("disabled", false);
    } else if (textStatus === "parsererror") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Error de análisis JSON solicitado.",
      });
      $("#guardar_ticket").prop("disabled", false);
    } else if (textStatus === "timeout") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Time out error.",
      });
      $("#guardar_ticket").prop("disabled", false);
    } else if (textStatus === "abort") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ajax request aborted.",
      });

      $("#guardar_ticket").prop("disabled", false);
    } else {
      alert("Uncaught Error: " + jqXHR.responseText);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: `Uncaught Error: ${jqXHR.responseText}`,
      });
      $("#guardar_ticket").prop("disabled", false);
    }
  });
}


$("#autorizar_permisos").submit(function (event) {
  event.preventDefault();
  $("#autoriza_permiso").prop("disabled", true);

  let data = new FormData();
  let autorizacion = $("#autorizacion").val();
  data.append("id_folio", $("#folio").val());
  data.append("autorizacion", autorizacion);
  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: urls + "permisos/autorizacion", //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    success: function (response) {
      //una vez que el archivo recibe el request lo procesa y lo devuelve
      console.log(response);
      $("#permisosModal").modal("toggle");
      /*codigo que borra todos los campos del form newProvider*/
      if (response != "error") {
        autorizacion === "Autorizada"
          ? Swal.fire("!El permiso ha sido Autorizado!", "", "success")
          : Swal.fire("!El permiso a sido Rechazado!", "", "success");
        tbl_permissions.ajax.reload(null, false);
        $("#autoriza_permiso").prop("disabled", false);
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
      $("#guardar_ticket").prop("disabled", false);
    } else if (jqXHR.status == 404) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No se encontró la página solicitada [404]",
      });
      $("#guardar_ticket").prop("disabled", false);
    } else if (jqXHR.status == 500) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Internal Server Error [500]",
      });
      $("#guardar_ticket").prop("disabled", false);
    } else if (textStatus === "parsererror") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Error de análisis JSON solicitado.",
      });
      $("#guardar_ticket").prop("disabled", false);
    } else if (textStatus === "timeout") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Time out error.",
      });
      $("#guardar_ticket").prop("disabled", false);
    } else if (textStatus === "abort") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ajax request aborted.",
      });

      $("#guardar_ticket").prop("disabled", false);
    } else {
      alert("Uncaught Error: " + jqXHR.responseText);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: `Uncaught Error: ${jqXHR.responseText}`,
      });
      $("#guardar_ticket").prop("disabled", false);
    }
  });
});

$("#editar_permisos").submit(function (e) {
  e.preventDefault();
  $("#editar_permiso").prop('disabled', true);
  let a = new FormData($("#editar_permisos")[0]);
  $.ajax({
    data: a, //datos que se envian a traves de ajax
    url: `${urls}permisos/guardar_editar`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    async: true,
    dataType: "json",
    success: function (resp) {
      $("#editar_permiso").prop('disabled', false);
      if (resp == true) {
        Swal.fire("!El Permiso ha sido Actualizado!", "", "success")
        tbl_permissions.ajax.reload(null, false);
        $("#permisosEditarModal").modal("toggle");
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
        console.log("Mal Revisa");
      }
    },
  }).fail(function (jqXHR, textStatus, errorThrown) {
    $("#editar_permiso").prop('disabled', false);
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

$("#autorizar_vacaciones").submit(function (event) {
  event.preventDefault();
  $("#autoriza_vacaciones").prop("disabled", true);

  let data = new FormData();
  let dias = $("#dias").val();
  let num_nomina = $("#num_nomina").val();
  let autorizacion = $("#autorizacion_vacaciones").val();
  console.log(num_nomina);
  console.log(dias);
  data.append("id_folio", $("#folio_vacaciones").val());
  data.append("autorizacion", autorizacion);
  data.append("dias", dias);
  data.append("num_nomina", num_nomina);
  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: urls + "permisos/autorizacion_vacaciones", //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    success: function (response) {
      //una vez que el archivo recibe el request lo procesa y lo devuelve
      console.log(response);
      $("#vacacionesModal").modal("toggle");
      /*codigo que borra todos los campos del form newProvider*/
      if (response != "error") {
        autorizacion === "Autorizada"
          ? Swal.fire("!El permiso ha sido Autorizado!", "", "success")
          : Swal.fire("!El permiso a sido Rechazado!", "", "success");
        tbl_vacations.ajax.reload(null, false);
        $("#autoriza_vacaciones").prop("disabled", false);
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
      $("#guardar_ticket").prop("disabled", false);
    } else if (jqXHR.status == 404) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No se encontró la página solicitada [404]",
      });
      $("#guardar_ticket").prop("disabled", false);
    } else if (jqXHR.status == 500) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Internal Server Error [500]",
      });
      $("#guardar_ticket").prop("disabled", false);
    } else if (textStatus === "parsererror") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Error de análisis JSON solicitado.",
      });
      $("#guardar_ticket").prop("disabled", false);
    } else if (textStatus === "timeout") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Time out error.",
      });
      $("#guardar_ticket").prop("disabled", false);
    } else if (textStatus === "abort") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ajax request aborted.",
      });

      $("#guardar_ticket").prop("disabled", false);
    } else {
      alert("Uncaught Error: " + jqXHR.responseText);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: `Uncaught Error: ${jqXHR.responseText}`,
      });
      $("#guardar_ticket").prop("disabled", false);
    }
  });
});

$("#editar_vacaciones").submit(function (e) {
  e.preventDefault();
  $("#actualiza_vacaciones").prop('disabled', true);
  let a = new FormData($("#editar_vacaciones")[0]);
  $.ajax({
    data: a, //datos que se envian a traves de ajax
    url: `${urls}permisos/guardar_editar_vacaciones`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    async: true,
    dataType: "json",
    success: function (resp) {
      $("#actualiza_vacaciones").prop('disabled', false);
      if (resp == true) {
        Swal.fire("!El permiso de Vacaciones ha sido Actualizado!", "", "success")
        tbl_vacations.ajax.reload(null, false);
        $("#editarVacacionesModal").modal("toggle");
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
        console.log("Mal Revisa");
      }
    },
  }).fail(function (jqXHR, textStatus, errorThrown) {
    $("#actualiza_vacaciones").prop('disabled', false);
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

function limpiarError(campo) {
  if (campo.value.length > 0) {
    campo.classList.remove('has-error');
    document.getElementById("error_" + campo.id).textContent = '';
  }
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


function handleAuthorizeVacation(id_folio) {
  $("#div_btn").empty();
  $("#div_modal_a_cargo").hide();
  let data = new FormData();
  data.append("id_folio", id_folio);
  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}permisos/editar_permiso_vacations/3`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    async: true,
    dataType: "json",
    success: function (resp) {
      console.log(resp);
      if (resp != "error") {
          $("#del_vacaciones").val(resp.dias_a_disfrutar_del);
          $("#al_vacaciones").val(resp.dias_a_disfrutar_al);
          $("#folio_vacaciones").val(resp.id_vcns);
          $("#usuario_vacaciones").val(resp.nombre_solicitante);
          $("#regresa").val(resp.regreso);
          $("#dias").val(resp.num_dias_a_disfrutar);
          $("#num_nomina").val(resp.num_nomina);
          if (parseInt(resp.id_a_cargo) != 0) {
            $("#div_modal_a_cargo").show();
            $("#modal_a_cargo").val(resp.a_cargo);
          }
          action = (id_folio > 8694) ? `onclick="verFechas(${resp.id_vcns})"` : 'disabled'
          color = (id_folio > 8694) ? `outline-primary` : 'secondary'
          $("#div_btn").append(`
          <button type="button" class="btn btn-${color}" ${action} style="margin-top: 1rem;">
            <i class="fas fa-calendar-day" style="margin-right: 10px;"></i>Ver dias de Vacaciones
          </button>`);

        $("#vacacionesModal").modal("show");
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
        console.log("Mal Revisa");
      }
    },
  }).fail(function (jqXHR, textStatus, errorThrown) {
    if (jqXHR.status === 0) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Fallo de conexión: ​​Verifique la red.",
      });
      $("#guardar_ticket").prop("disabled", false);
    } else if (jqXHR.status == 404) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No se encontró la página solicitada [404]",
      });
      $("#guardar_ticket").prop("disabled", false);
    } else if (jqXHR.status == 500) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Internal Server Error [500]",
      });
      $("#guardar_ticket").prop("disabled", false);
    } else if (textStatus === "parsererror") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Error de análisis JSON solicitado.",
      });
      $("#guardar_ticket").prop("disabled", false);
    } else if (textStatus === "timeout") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Time out error.",
      });
      $("#guardar_ticket").prop("disabled", false);
    } else if (textStatus === "abort") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ajax request aborted.",
      });

      $("#guardar_ticket").prop("disabled", false);
    } else {
      alert("Uncaught Error: " + jqXHR.responseText);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: `Uncaught Error: ${jqXHR.responseText}`,
      });
      $("#guardar_ticket").prop("disabled", false);
    }
  });
}

function handleVacation(id_folio) {
  console.log("vacaciones editar");
  let data = new FormData();
  data.append("id_folio", id_folio);
  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}permisos/editar_permiso_vacations/1`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    async: true,
    dataType: "json",
    success: function (resp) {
      console.log(resp);
      if (resp != "error") {
        resp.forEach(function (permiso, index) {
          $("#editar_vacaciones_del").val(permiso.dias_a_disfrutar_del);
          $("#editar_vacaciones_al").val(permiso.dias_a_disfrutar_al);
          $("#editar_folio_vcns").val(permiso.id_vcns);
          $("#editar_usuario_vcns").val(permiso.nombre_solicitante);
          $("#editar_regresando").val(permiso.regreso);
          $("#editar_catidad").val(permiso.num_dias_a_disfrutar);
        });
        $("#editarVacacionesModal").modal("show");
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
        console.log("Mal Revisa");
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

function handleVacationNew(id_folio) {
  console.log("vacaciones editar");
  let data = new FormData();
  data.append("id_folio", id_folio);
  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}permisos/editar_permiso_vacations/2`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    async: true,
    dataType: "json",
    success: function (resp) {
      if (resp != "error") {
        $("#editar_vcns_new").val(resp.id_vcns);
        $("#id_user_new").val(resp.id_user);
        $("#id_depto_new").val(resp.id_depto);
        $("#editar_usuario_vcns_new").val(resp.nombre_solicitante);
        $("#editar_catidad_new").val(resp.num_dias_a_disfrutar);
        var fechas_array = resp.concatenado.split(",");
        $("#id_items_new").val(resp.items);
        inputVacaciones.setDate(fechas_array);
        inputRegreso.setDate(resp.regreso);
        $("#editarVacacionesNewModal").modal("show");
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
        console.log("Mal Revisa");
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