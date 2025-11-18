/**
 * ARCHIVO MODULO CARS
 * AUTOR: HORUS SAMAEL RIVAS PEDRAZA
 * horus.riv.ped@gmail.com
 * CEL:5624392632
 */


var vehiculo = [];
var error_vehiculo = "";
var error_tipo_viaje = "";
var error_motivo = "";
var error_fecha_inicio = "";
var error_horario_fecha_inicio = "";
var error_fecha_fin = "";
var error_horario_fecha_fin = "";
var error_fecha = "";
var error_horario_inicio = "";
var error_horario_fin = "";
var d = new Date();
var month = d.getMonth() + 1;
var day = d.getDate();
var fecha_hoy = `${d.getFullYear()}-${month < 10 ? '0' : ''}${month}-${(day < 10 ? '0' : '') + day}`;
var hoy = new Date();
var hora = hoy.getHours();
var minutos = hoy.getMinutes();
var hora_hoy = `${hora < 10 ? '0' : ''}${hora}:${minutos < 10 ? '0' : ''}${minutos}`;

$("#solicitud_vehiculo").on("submit", function (e) {
  e.preventDefault();
  $("#btn_solicitud_vehiculo").prop("disabled", true);
  if ($("#tipo_viaje").val().length == 0
    && $("#motivo").val().length == 0
  ) {
    Swal.fire({
      icon: "error",
      title: "!ERROR¡",
      text: "Llena el formulario",
    });
    $("#btn_solicitud_vehiculo").prop("disabled", false);
    return false;
  }

  if ($("#motivo").val().length == 0) {
    error_motivo = "Campo Requerido";
    $("#motivo").addClass('has-error');
    $("#error_motivo").text(error_motivo);
  } else if ($("#motivo").val().length > 0 && $("#motivo").val().length < 4) {
    error_motivo = "Escribe un motivo Valido";
    $("#motivo").addClass('has-error');
    $("#error_motivo").text(error_motivo);
  } else if (isNaN($.trim($("#motivo").val())) == false) {
    error_motivo = "No se permiten solo números";
    $('#error_motivo').text(error_motivo);
    $('#motivo').addClass('has-error');
  }
  if ($("#tipo_viaje").val().length == 0) {
    error_tipo_viaje = "Campo Requerido";
    $("#tipo_viaje").addClass('has-error');
    $("#error_tipo_viaje").text(error_tipo_viaje);
  }
  if ($("#tipo_viaje").val() == 1) {
    error_fecha_inicio = "";
    error_horario_fecha_inicio ="";
    error_fecha_fin = "";
    error_horario_fecha_fin = "";
    if ($("#fecha").val().length == 0) {
      error_fecha = "Campo Requerido";
      $("#fecha").addClass('has-error');
      $("#error_fecha").text(error_fecha);
    } else if (fecha_hoy > $("#fecha").val()) {
      error_fecha = "La Fecha no puede ser menor a Hoy.";
      $("#fecha").addClass('has-error');
      $("#error_fecha").text(error_fecha);
    }
    if ($("#horario_inicio").val().length == 0) {
      error_horario_inicio = "Campo Requerido";
      $("#horario_inicio").addClass('has-error');
      $("#error_horario_inicio").text(error_horario_inicio);
    } else if (fecha_hoy == $("#fecha").val()) {
      if (hora_hoy >= $("#horario_inicio").val()) {
        error_horario_inicio = "La Hora de Incio debe ser mayor a la actual";
        $("#horario_inicio").addClass('has-error');
        $("#error_horario_inicio").text(error_horario_inicio);
      }
    }
    if ($("#horario_fin").val().length == 0) {
      error_horario_fin = "Campo Requerido";
      $("#horario_fin").addClass('has-error');
      $("#error_horario_fin").text(error_horario_fin);
    } else if ($("#horario_inicio").val() >= $("#horario_fin").val()) {
      error_horario_fin = "La Hora Final debe ser mayor a la Hora de Inicio";
      $("#horario_fin").addClass('has-error');
      $("#error_horario_fin").text(error_horario_fin);
    }
  } else if ($("#tipo_viaje").val() == 2) {
    error_fecha = "";
    error_horario_inicio = "";
    error_horario_fin = "";
    if ($("#fecha_inicio").val().length == 0) {
      error_fecha_inicio = "Campo Requerido";
      $("#fecha_inicio").addClass('has-error');
      $("#error_fecha_inicio").text(error_fecha_inicio);
    } else if (fecha_hoy > $("#fecha_inicio").val()) {
      error_fecha_inicio = " La Fecha de Inicio no puede ser menor a Hoy.";
      $("#fecha_inicio").addClass('has-error');
      $("#error_fecha_inicio").text(error_fecha_inicio);
    } else if (fecha_hoy == $("#fecha_inicio").val()) {
      if (hora_hoy >= $("#horario_fecha_inicio").val()) {
        error_horario_fecha_inicio = "La Hora de Incio debe ser mayor a la actual";
        $("#horario_fecha_inicio").addClass('has-error');
        $("#error_horario_fecha_inicio").text(error_horario_fecha_inicio);
      }
    }
    if ($("#fecha_fin").val().length == 0) {
      error_fecha_fin = "Campo Requerido";
      $("#fecha_fin").addClass('has-error');
      $("#error_fecha_fin").text(error_fecha_fin);
    } else if ($("#fecha_inicio").val() >= $("#fecha_fin").val()) {
      error_fecha_fin = "La Fecha Final debe ser mayo a la Fecha de Inico.";
      $("#fecha_fin").addClass('has-error');
      $("#error_fecha_fin").text(error_fecha_fin);
    }
    if ($("#horario_fecha_inicio").val().length == 0) {
      error_horario_fecha_inicio = "Campo Requerido";
      $("#horario_fecha_inicio").addClass('has-error');
      $("#error_horario_fecha_inicio").text(error_horario_fecha_inicio);
    }
    if ($("#horario_fecha_fin").val().length == 0) {
      error_horario_fecha_fin = "Campo Requerido";
      $("#horario_fecha_fin").addClass('has-error');
      $("#error_horario_fecha_fin").text(error_horario_fecha_fin);
    }
  }
  if (
    error_tipo_viaje != "" ||
    error_fecha_inicio != "" ||
    error_fecha_fin != "" ||
    error_fecha != "" ||
    error_horario_inicio != "" ||
    error_horario_fecha_inicio != "" ||
    error_horario_fin != "" ||
    error_horario_fecha_fin != "" ||
    error_motivo != ""
  ) {
    $("#btn_solicitud_vehiculo").prop("disabled", false);
    return false;
  }

  let data = new FormData();
  data.append("type_trip", $("#tipo_viaje").val());
  if ($("#tipo_viaje").val() == 1) {
    data.append("date", $("#fecha").val());
    data.append("star_time", $("#horario_inicio").val());
    data.append("end_time", $("#horario_fin").val());
  } else if ($("#tipo_viaje").val() == 2) {
    data.append("star_date", $("#fecha_inicio").val());
    data.append("star_datetime", $("#horario_fecha_inicio").val());
    data.append("end_date", $("#fecha_fin").val());
    data.append("end_datetime", $("#horario_fecha_fin").val());
  }
  data.append("id_car", sessionStorage.vehiculo);
  data.append("motive", $("#motivo").val());

  $.ajax({
    data: data,
    url: `${urls}autos/solicitar`,
    type: "POST",
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    dataType: "json",
    success: function (resp) {
      if(resp != true && resp != false){
        console.log(resp.error);
        if(resp.error == 1 || resp.error == 2 || resp.error == 3){
          /* var d1 =resp.dato;
          var hours = d1.getHours();
          var m1 ="am";
          if(hours>12){hours= hours-12; m1 = "pm";}
          var minuts = d1.getMinutes();
          var hora1 = `${(hours < 10 ? '0' : '') + hours}:${minuts < 10 ? '0' : ''}${minuts}  ${m1}`;
          var d2 =resp.dato2;
          var hours2 = d2.getHours();
          var m2 ="am";
          if(hours2>12){hours2 = hours2-12; m2 = "pm";}
          var minuts2 = d2.getMinutes();
          var hora2 = `${(hours2 < 10 ? '0' : '') + hours2}:${minuts2 < 10 ? '0' : ''}${minuts2}  ${m2}`;
          console.log(hora1,hora2); */
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: `El Vehiculo Solicitado estra en uso de
             ${resp.dato} a ${resp.dato2}`,
          });
          $("#btn_solicitud_vehiculo").prop("disabled", false);
        }
        if(resp.error == 4){
          var d1 = new Date(resp.dato);
          var month = d1.getMonth()+1;
          var day = d1.getDate() + 1;
          var fecha1 = `${(day < 10 ? '0' : '') + day}/${month < 10 ? '0' : ''}${month}/${d1.getFullYear()}`;
          var d2 = new Date(resp.dato2);
          var month2 = d2.getMonth()+1;
          var day2 = d2.getDate() +1;
          var fecha2 = `${(day2 < 10 ? '0' : '') + day2}/${month2 < 10 ? '0' : ''}${month2}/${d2.getFullYear()}`;
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: `El Vehiculo Solicitado estra en uso del dia
            ${fecha1} al ${fecha2}`,
          });
          $("#btn_solicitud_vehiculo").prop("disabled", false);
        }
        if(resp.error == 5){
          var d1 = new Date(resp.dato2);
          var month = d1.getMonth()+1;
          var day = d1.getDate() +1;
          var fecha1 = `${(day < 10 ? '0' : '') + day}/${month < 10 ? '0' : ''}${month}/${d1.getFullYear()}`;
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: `El Vehiculo Solicitado estra en uso el dia
            ${fecha1} apartir de las ${resp.dato}`,
          });
          $("#btn_solicitud_vehiculo").prop("disabled", false);
        }
        if(resp.error == 6){
          var d1 = new Date(resp.dato2);
          var month = d1.getMonth()+1;
          var day = d1.getDate() +1;
          var fecha1 = `${(day < 10 ? '0' : '') + day}/${month < 10 ? '0' : ''}${month}/${d1.getFullYear()}`;
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: `El Vehiculo Solicitado esta solicitado el dia
            ${fecha1} apartir de las ${resp.dato}`,
          });
          $("#btn_solicitud_vehiculo").prop("disabled", false);
        }    
        if(resp.error == 7){
          var d2 = new Date(resp.dato2);
          var month2 = d2.getMonth()+1;
          var day2 = d2.getDate()+1;
          var fecha2 = `${(day2 < 10 ? '0' : '') + day2}/${month2 < 10 ? '0' : ''}${month2}/${d2.getFullYear()}`;
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: `El Vehiculo Solicitado estra disponible el dia
            ${fecha2} despues de las ${resp.dato}`,
          });
          $("#btn_solicitud_vehiculo").prop("disabled", false);
        }
        if(resp.error == 8){
          var d1 = new Date(resp.dato);
          var month = d1.getMonth()+1;
          var day = d1.getDate()+1;
          var fecha1 = `${(day < 10 ? '0' : '') + day}/${month < 10 ? '0' : ''}${month}/${d1.getFullYear()}`;
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: `El Vehiculo Solicitado estra en uso el dia
            ${fecha1}`,
          });
          $("#btn_solicitud_vehiculo").prop("disabled", false);
        }
        if(resp.error == 9){
          var d1 = new Date(resp.dato3);
          var month = d1.getMonth()+1;
          var day = d1.getDate() +1;
          var fecha1 = `${(day < 10 ? '0' : '') + day}/${month < 10 ? '0' : ''}${month}/${d1.getFullYear()}`;
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: `El Vehiculo Solicitado estra en uso el dia
            ${fecha1} de las ${resp.dato} a las ${resp.dato2}`,
          });
          $("#btn_solicitud_vehiculo").prop("disabled", false);
        }     
        if(resp.error == 11){
          var d1 = new Date(resp.dato2);
          var month = d1.getMonth()+1;
          var day = d1.getDate() +1;
          var fecha1 = `${(day < 10 ? '0' : '') + day}/${month < 10 ? '0' : ''}${month}/${d1.getFullYear()}`;
           Swal.fire({
            icon: "error",
            title: "Oops...",
            text: `El Vehiculo Solicitado ya esta solicitado el dia
            ${fecha1} a las  ${resp.dato}`,
          });
          $("#btn_solicitud_vehiculo").prop("disabled", false);
        }
        if(resp.error == 12 || resp.error == 13 || resp.error == 14){
          var d1 = new Date(resp.dato);
          var month = d1.getMonth()+1;
          var day = d1.getDate() +1;
          var fecha1 = `${(day < 10 ? '0' : '') + day}/${month < 10 ? '0' : ''}${month}/${d1.getFullYear()}`;
          var d2 = new Date(resp.dato2);
          var month2 = d2.getMonth()+1;
          var day2 = d2.getDate() +1;
          var fecha2 = `${(day2 < 10 ? '0' : '') + day2}/${month2 < 10 ? '0' : ''}${month2}/${d2.getFullYear()}`;
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: `El Vehiculo Solicitado estra en uso del dia
             ${fecha1} al ${fecha2}`,
          });
          $("#btn_solicitud_vehiculo").prop("disabled", false);
        }
        if(resp.error == 15){
          var d1 = new Date(resp.dato);
          var month = d1.getMonth()+1;
          var day = d1.getDate() +1;
          var fecha1 = `${(day < 10 ? '0' : '') + day}/${month < 10 ? '0' : ''}${month}/${d1.getFullYear()}`;
         Swal.fire({
            icon: "error",
            title: "Oops...",
            text: `El Vehiculo Solicitado estra en uso de las
             ${fecha1} hasta las ${resp.dato2}`,
          });
          $("#btn_solicitud_vehiculo").prop("disabled", false);
        }
        if(resp.error == 16){
          var d1 = new Date(resp.dato);
          var month = d1.getMonth()+1;
          var day = d1.getDate()+1;
          var fecha1 = `${(day < 10 ? '0' : '') + day}/${month < 10 ? '0' : ''}${month}/${d1.getFullYear()}`;
         Swal.fire({
            icon: "error",
            title: "Oops...",
            text: `El Vehiculo Solicitado ya esta solicitado el dia
             ${fecha1} a las ${resp.dato2}`,
          });
          $("#btn_solicitud_vehiculo").prop("disabled", false);
        }      
      }else if (resp) {
        $(".cards").removeClass("active");
        $("#tipo_viaje").val("");
        $("#motivo").val("");
        error_tipo_viaje = "";
        error_motivo = "";
        $("#opciones").empty();
        $("#opciones2").empty();
        $("#opciones3").empty();
        $("#btn_solicitud_vehiculo").prop("disabled", false);
        Swal.fire("!Sea Registrado la Solicitud!", "", "success");
      } else {
        $("#btn_solicitud_vehiculo").prop("disabled", false);
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
    error: function () {
      $("#btn_solicitud_vehiculo").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ocurrio un error en el servidor! Contactar con el Administrador",
      });
    },
  }).fail(function (jqXHR, textStatus, errorThrown) {

    if (jqXHR.status === 0) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Fallo de conexión: ​​Verifique la red.",
      });
      $("#btn_solicitud_vehiculo").prop("disabled", false);

    } else if (jqXHR.status == 404) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No se encontró la página solicitada [404]",
      });
      $("#btn_solicitud_vehiculo").prop("disabled", false);
    } else if (jqXHR.status == 500) {

      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Internal Server Error [500]",
      });
      $("#btn_solicitud_vehiculo").prop("disabled", false);
    } else if (textStatus === 'parsererror') {

      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Error de análisis JSON solicitado.",
      });
      $("#btn_solicitud_vehiculo").prop("disabled", false);
    } else if (textStatus === 'timeout') {

      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Time out error.",
      });
      $("#btn_solicitud_vehiculo").prop("disabled", false);
    } else if (textStatus === 'abort') {

      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ajax request aborted.",
      });

      $("#btn_solicitud_vehiculo").prop("disabled", false);
    } else {

      alert('Uncaught Error: ' + jqXHR.responseText);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: `Uncaught Error: ${jqXHR.responseText}`,
      });
      $("#btn_solicitud_vehiculo").prop("disabled", false);
    }
  });
});

function viaje() {
  $("#opciones").empty();
  $("#opciones2").empty();
  $("#opciones3").empty();
  $("#opciones4").empty();
  if ($("#tipo_viaje").val() != 0) {
    $("#tipo_viaje").removeClass('has-error');
    $("#error_tipo_viaje").text('');
  }
  if ($("#tipo_viaje").val() == 1) {
    $("#opciones").removeClass('col-md-2');
    $("#opciones2").removeClass('col-md-2');
    $("#opciones3").removeClass('col-md-2');
    $("#opciones4").removeClass('col-md-2');
    $("#opciones").addClass('col-md-4');
    $("#opciones2").addClass('col-md-4');
    $("#opciones3").addClass('col-md-4');
    $("#opciones4").addClass('col-md-4');
    $("#opciones").append(`
    <div class="form-group">
      <label for="feccha">Fecha</label>
        <input type="date" class="form-control rounded-0" id="fecha" name="fecha" value="" data-provide="datepicker" onchange="validar()">
        <div id="error_fecha" class="text-danger"></div>
      </div>
      `).show("slow");
    $("#opciones2").append(`
      <div class="form-group">
        <label for="horario_inicio">Hora de Inicio</label>
        <input type="time" class="form-control rounded-0" id="horario_inicio" name="horario_inicio" onchange="validar()">
        <div id="error_horario_inicio" class="text-danger"></div>
      </div>
      `).show("slow");
    $("#opciones3").append(`
      <div class="form-group">
        <label for="horario_fin">Hora de Final</label>
        <input type="time" class="form-control rounded-0" id="horario_fin" name="horario_fin" onchange="validar()">
        <div id="error_horario_fin" class="text-danger"></div>
    </div>
  `).show("slow");
  }
  if ($("#tipo_viaje").val() == 2) {
  $("#opciones").removeClass('col-md-4');
  $("#opciones2").removeClass('col-md-4');
  $("#opciones3").removeClass('col-md-4');
  $("#opciones4").removeClass('col-md-4');
  $("#opciones").addClass('col-md-3');
  $("#opciones2").addClass('col-md-3');
  $("#opciones3").addClass('col-md-3');
  $("#opciones4").addClass('col-md-3');
    $("#opciones").append(`
    <div class="form-group">
      <label for="fecha_inicio">Fecha de incio</label>
        <input type="date" class="form-control rounded-0" id="fecha_inicio" name="fecha_inicio" value="" onchange="validar()">
        <div id="error_fecha_inicio" class="text-danger"></div>
      </div>
    </div>
    `).show("slow");
    $("#opciones2").append(`
    <div class="form-group">
        <label for="horario_fecha_inicio">Hora</label>
        <input type="time" class="form-control rounded-0" id="horario_fecha_inicio" name="horario_fecha_inicio" onchange="validar()">
        <div id="error_horario_fecha_inicio" class="text-danger"></div>
      </div>
    `).show("slow");
    $("#opciones3").append(`
    <div class="form-group">
      <label for="fecha_fin">Fecha Final</label>
        <input type="date" class="form-control rounded-0" id="fecha_fin" name="fecha_fin" value="" onchange="validar()">
        <div id="error_fecha_fin" class="text-danger"></div>
      </div>
    </div>
    `).show("slow");
    $("#opciones4").append(`
    <div class="form-group">
    <label for="horario_fecha_fin">Hora</label>
    <input type="time" class="form-control rounded-0" id="horario_fecha_fin" name="horario_fecha_fin" onchange="validar()">
    <div id="error_horario_fecha_fin" class="text-danger"></div>
  </div>
    `).show("slow");
  }
}
function validar() {
  if ($("#motivo").val().length > 0) {
    error_motivo = "";
    $("#motivo").removeClass('has-error');
    $("#error_motivo").text(error_motivo);
  }
  if ($("#tipo_viaje").val() == 1) {
    if ($("#fecha").val().length > 0) {
      error_fecha = "";
      $("#fecha").removeClass('has-error');
      $("#error_fecha").text(error_fecha);
    }
    if ($("#horario_inicio").val().length > 0) {
      error_horario_inicio = "";
      $("#horario_inicio").removeClass('has-error');
      $("#error_horario_inicio").text(error_horario_inicio);
    }
    if ($("#horario_fin").val().length > 0) {
      error_horario_fin = "";
      $("#horario_fin").removeClass('has-error');
      $("#error_horario_fin").text(error_horario_fin);
    }
  } else if ($("#tipo_viaje").val() == 2) {
    if ($("#fecha_inicio").val().length > 0) {
      error_fecha_inicio = "";
      $("#fecha_inicio").removeClass('has-error');
      $("#error_fecha_inicio").text(error_fecha_inicio);
    }
    if ($("#horario_fecha_inicio").val().length > 0) {
      error_horario_fecha_inicio = "";
      $("#horario_fecha_inicio").removeClass('has-error');
      $("#error_horario_fecha_inicio").text(error_horario_fecha_inicio);
    }
    if ($("#fecha_fin").val().length > 0) {
      error_fecha_fin = "";
      $("#fecha_fin").removeClass('has-error');
      $("#error_fecha_fin").text(error_fecha_fin);
    }
    if ($("#horario_fecha_fin").val().length > 0) {
      error_horario_fecha_fin = "";
      $("#horario_fecha_fin").removeClass('has-error');
      $("#error_horario_fecha_fin").text(error_horario_fecha_fin);
    }
  }
  
}

/* $(document).ready(function () {
  var cat_menu = "0";
  $.ajax({
    type: "post",
    url: `${urls}autos/todos_vehiculos`,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (resp) {
      resp.forEach((key, value) => {
        cat_menu++;
        $("#vehiculos").append(`
           <div id="${key.id_car}-card" class="cards card col-md-4"  style="margin-bottom: 1rem;">
             <div class="card-body" role="button">
               <div style="width: 100%;" class="mb-3">
                 <h5 class="card-title">
                  <input type="hidden" id="${key.id_car}">
                   <label id="modelo">${key.model}<br></label>
                 </h5>
               </div>
               <div class="text-center mb-3">
                 <img id="imagen" class="img-fluid mx-auto d-block rounded" style=" width:95%; height:180px;" src="${key.imagen}"/>
               </div>
                <hr>
                <label id="placas">${key.placa.toUpperCase()}<br></label>
               <hr>
             </div>
           </div>
         `).show("slow");
        if (cat_menu == 3) {
          $("#vehiculos").append(`
           <div class="w-100"></div>
           `);
          cat_menu = 0;
        }
      });
      carro();
    }
  });
}); */

/* function carro() {
  $(".cards").click(function () {//Clicking the card
    var inputElement = $(this).find(' input[type=hidden]').attr('id');
    vehiculo = [];
    sessionStorage.setItem('vehiculo', JSON.stringify(vehiculo));
    removeActive();
    makeActive(inputElement);
    clickRadio(inputElement);
    error_vehiculo = "";
    $("#error_vehiculo").text(error_vehiculo);
  });
}; */
function clickRadio(inputElement) {
  vehiculo.push(inputElement);
  sessionStorage.setItem('vehiculo', vehiculo);
}
function removeActive() {
  $(".cards").removeClass("active");
}
function makeActive(element) {
  $(`#${element}-card`).addClass("active");
}