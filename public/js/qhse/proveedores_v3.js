
/**
 * ARCHIVO MODULO QHSE
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
var cont = 1;
var cont_seguro = 0;
var cont_auto = 0;
var cont_estadia = 0;
var arrayItems = [];
$(document).ready(function () {
  $(".select2bs4").select2({
    placeholder: "Selecciona una Opción",
  });
  $("input[name='estadia'][value='0']").prop("checked", true);
  $("#cont_estadia").removeClass("col-md-6");
  $("#cont_estadia").empty();
  $("#cont_estadia").addClass("row col-md-4");
  $("#cont_estadia").append(`
                  <div class="form-group col-md-12">
                  <label for="motivo_visita">*Día de visita</label>
                  <input type="date" class="form-control rounded-0 calendar-visita" id="dia_visita" name="dia_visita" value="" onchange="validar()">
                  <div id="error_dia" class="text-danger"></div>
                </div>
      `).show("slow");

  //codigo :
  flatpickr("#dia_visita", {
    locale: "es",
    // mode: "range",
    dateFormat: "d/m/Y",
    minDate: "today", // minimo dia de hoy


  });
});

// Cuando hacemos click en el boton de retirar
function retirarItem(item) {
  var i = arrayItems.indexOf(item);
  arrayItems.splice(i, 1);
  sessionStorage.setItem('arrayItems', JSON.stringify(arrayItems));

  $("#extra_" + item).remove();
  if (cont > 0) {
    --cont;
  }
  return false;
};

$("#epp_si").on("click", function () {
  $("#error_epp").text('');
  $("#epp_si").val("1");
  $("#epp_no").val("0");
});

$("#epp_no").on("click", function () {
  $("#error_epp").text('');
  $("#epp_no").val("2");
  $("#epp_si").val("0");
});

$("#trabajos_si").on("click", function () {
  cont_seguro++;
  $("#error_trabajos").text('');

  if (cont_seguro == 1) {

    $("#seguro_trabajo").addClass("form-group col-md-6");

    $("#seguro_trabajo").append(`
      <label for="motivo_visita">IMSS</label>
      <div class="custom-file">
        <input type="file" name="file_seguro_visita" id="file_seguro_visita" class="custom-file-input" aria-describedby="inputGroupFileAddon01" onchange="limpiarArchivo(this)">
        <label id="lbl_file_seguro_visita" class="custom-file-label" for="file_seguro_visita">cedula de terminación de cuotas o alta</label>
      </div>
      <p><b>No olvidar que para casos en los cuales la visita relizara trabajos al interior de las instalaciones, 
      se convierte en un <i>contratista</i>,por lo cual deberá traer 
      comprobante del ultimo pago del seguro social (cedula de terminación de cuotas o alta) 
      para poder ingresar y cumplir con PHSE-04 PROCEDIMIENTO DE SEGURIDAD PARA VISITANTES, 
      CONTRATISTAS Y PROVEEDORES.</b> </p>
      `).show("slow");
  }

});

$("#trabajos_no").on("click", function () {
  cont_seguro = 0;
  $("#error_trabajos").text('');

  $("#seguro_trabajo").removeClass("form-group col-md-6");
  $("#seguro_trabajo").empty();


});

$("#estadia_si").on("click", function () {
  cont_estadia++;

  if (cont_estadia == 1) {

    $("#cont_estadia").removeClass("row");
    $("#cont_estadia").empty();


    $("#cont_estadia").append(`
   
                  <label for="visita">*Inicio de la Estadia</label>
                  <input type="date" class="form-control rounded-0 calendar-visita" id="dias_visitas" name="dias_visitas" value="" onchange="validar()">
                  <div id="error_visitas" class="text-danger"></div>
                
                
      `).show("slow");

    //codigo :
    flatpickr("#dias_visitas", {
      locale: "es",
      mode: "range",
      dateFormat: "d/m/Y",
      minDate: "today", // minimo dia de hoy
      onChange: function (selectedDates, dateStr, instance) {
        // Cuando cambia la selección de fechas
        if (selectedDates.length === 2) {
          // Si se han seleccionado dos fechas
          var fechaInicial = selectedDates[0].toLocaleDateString(); //nota, la fecha sale con formato d/m/Y
          var fechaFinal = selectedDates[1].toLocaleDateString();  //nota, la fecha sale con formato d/m/Y
          var fechaSeleccionada = fechaInicial + "," + fechaFinal;
          instance.input.value = fechaSeleccionada;
        }
      },

    });

  }
  $("#error_estadia").text('');
});

$("#estadia_no").on("click", function () {
  cont_estadia = 0;
  $("#error_estadia").text('');

  $("#cont_estadia").removeClass("col-md-6");
  $("#cont_estadia").empty();
  $("#cont_estadia").addClass("row col-md-4");
  $("#cont_estadia").append(`
                  <div class="form-group col-md-12">
                  <label for="motivo_visita">*Día de visita</label>
                  <input type="date" class="form-control rounded-0 calendar-visita" id="dia_visita" name="dia_visita" value="" onchange="validar()">
                  <div id="error_dia" class="text-danger"></div>
                </div>
      `).show("slow");

  //codigo :
  flatpickr("#dia_visita", {
    locale: "es",
    // mode: "range",
    dateFormat: "d/m/Y",
    minDate: "today", // minimo dia de hoy


  });

});

function limpiarArchivo(input) {
  if ($("#" + input.id).val().length > 0) {
    $("#lbl_" + input.id).empty();
    $("#lbl_" + input.id).append(`${document.getElementById(input.id).files[0].name}`);
    $("#lbl_" + input.id).attr('style', 'color:#343a40!important;');
    $("#lbl_" + input.id).removeClass('has-error');
  }
}

$("#auto_si").on("click", function () {
  cont_auto++;
  $("#error_auto").text('');
  if (cont_auto == 1) {

    $("#seguro_auto").addClass("form-group col-md-6");

    $("#seguro_auto").append(`
      <label for="motivo_visita">Poliza de Seguro</label>
      <p><b>No olvidar que para casos en los cuales ingresaran a las instalaciones con vehículo, deberán contar con la <i>póliza vigente</i>, correspondiente al vehiculo que ingresara.</b></p>
      `).show("slow");

    $("#datos_auto").addClass("col-md-12");
    $("#datos_auto").append(`
      <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="modelo">Modelo</label>
                    <input type="text" class="form-control rounded-0" id="modelo" name="modelo" value="" />
                    <div id="error_modelo" class="text-danger"></div>
                  </div>
                  <div class="form-group col-md-4">
                    <label for="color">Color</label>
                    <input type="text" class="form-control rounded-0" id="color" name="color" value="" />
                    <div id="error_color" class="text-danger"></div>
                  </div>
                  <div class="form-group col-md-4">
                    <label for="placas">Placas</label>
                    <input type="text" class="form-control rounded-0" id="placas" name="placas" value="" />
                    <div id="error_placas" class="text-danger"></div>
                  </div>
               </div>
      `).show("slow");
  }



});

$("#auto_no").on("click", function () {
  cont_auto = 0;
  $("#error_auto").text('');
  $("#seguro_auto").removeClass("form-group col-md-6");
  $("#seguro_auto").empty();
  $("#datos_auto").removeClass("col-md-12");
  $("#datos_auto").empty();


});

// El formulario que queremos replicar
var formUser = $("#tiempo_extra").clone(true, true).html();

// El encargado de agregar más formularios 
$("#btn-agregar-item").click(function () {
  if (arrayItems.length <= 10) {
    if (arrayItems.length == 0) {
      cont++;
    } else {
      cont++;
      arrayItems.forEach(item => {
        if (item === cont) {
          cont++;
        }
      });
    }
    // clonacion de campo.....
    arrayItems.push(cont);
    // Se guarda en localStorage despues de JSON stringificarlo 
    sessionStorage.setItem('arrayItems', JSON.stringify(arrayItems));


    // Agregamos el formulario
    $("#item-duplica").prepend(formUser).show("slow");
    $("#extra_1").attr("id", "extra_" + cont);
    //Editamos el valor del input con data de la sugerencia pulsada
    $("#num_nomina_extra_1").attr("onclick", "escuchar(" + cont + ")");
    $("#num_nomina_extra_1").attr("id", "num_nomina_extra_" + cont);
    $("#usuario_extra_1").attr("id", "usuario_extra_" + cont);
    $("#visitante_1").attr("onchange", "validarClon(" + cont + ")");
    $("#nacionalidad_1").attr("onchange", "validarClon(" + cont + ")");
    $("#visitante_1").attr("id", "visitante_" + cont);
    $("#nacionalidad_1").attr("id", "nacionalidad_" + cont);
    $("#error_visitante_1").attr("id", "error_visitante_" + cont);
    $("#error_nacio_1").attr("id", "error_nacio_" + cont);

    $("#puesto_1").attr("id", "puesto_" + cont);
    $("#btn_eliminar_1").attr("id", "btn_eliminar_" + cont);
    $("#extra_usuario").empty();
    $("#extra_usuario").append("Usuario " + cont);


    // Agregamos un boton para retirar el formulario
    $("#btn_eliminar_" + cont).append(
      `<div class="item-duplica card-tools" style="margin-top: 2rem;">
                                            
      <button type="button" class="btn btn-danger btn-retirar-item" onclick="retirarItem(${cont})">
          <i class="fas fa-times"></i>
      </button>
      </div>`);

    // Hacemos focus en el primer input del formulario
    $("#num_nomina_extra_" + cont).focus();
    //$("#alumnos#header-car:first .card-bodyinput:first").focus();
    $("#extra_" + cont).addClass("extras");
    // Volvemos a cargar todo los plugins que teníamos, dentro de esta función esta el del datepicker assets/js/ini.js
  } else {
    /* Mostrar error */
    $("#resultado").html(
      `<div class="alert alert-warning alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <strong>NO SE PERMITEN MAS DE 10 ITEMS EN LA SOLICITUD...</strong>
        </div>
          <span></span>`
    );
    setTimeout(function () {
      $(".alert")
        .fadeTo(1000, 0)
        .slideUp(800, function () {
          $(this).remove();
        });
    }, 3000);
    return false;
  }
});

function limpiarError(input) {
  console.log('hola campo: ', input.id);
  if ($("#" + input.id).val().length > 0) {
    $("#error_" + input.id).text("");
    $("#" + input.id).removeClass("has-error");
  }
}

function validarClon(clon) {
  if ($("#visitante_" + clon).val().length > 0) {
    $("#error_visitante_" + clon).text("");
    $("#visitante_" + clon).removeClass("has-error");
  }
  if ($("#nacionalidad_" + clon).val().length > 0) {
    $("#error_nacio_" + clon).text("");
    $("#nacionalidad_" + clon).removeClass("has-error");
  }
}

/* $("#permisos_visitas").submit(function (event) {
  event.preventDefault();
  let proveedor = $("#proveedor").val();
  let num_personas = $("#num_personas").val();
  let persona_visita = $("#visita").val();
  let depto = $("#depto").val();
  let motivo = $("#motivo_visita").val();
  let dia_visita = $("#dia_visita").val();
  let hora_visita = $("#hora_entrada").val();
  let imss = $("#imss").val();
  let poliza = $("#poliza").val();
  let visitante = $("#visitante_1").val();
  let nacionalidad = $("#nacionalidad_1").val();

  if ($.trim(proveedor).length == 0) {
    var error_provee = "El campo es requerido";
    $("#error_proveedor").text(error_provee);
    $("#proveedor").addClass("has-error");
  } else {
    error_provee = "";
    $("#error_proveedor").text(error_provee);
    $("#proveedor").removeClass("has-error");
  }

  if ($.trim(num_personas).length == 0) {
    var error_num = "El campo es requerido";
    $("#error_num_personas").text(error_num);
    $("#num_personas").addClass("has-error");
  } else {
    error_num = "";
    $("#error_num_personas").text(error_num);
    $("#num_personas").removeClass("has-error");
  }

  if ($.trim(persona_visita).length == 0) {
    var error_persona_visita = "El campo es requerido";
    $("#error_visita").text(error_persona_visita);
    $("#visita").addClass("has-error");
  } else {
    error_persona_visita = "";
    $("#error_visita").text(error_persona_visita);
    $("#visita").removeClass("has-error");
  }

  if ($.trim(depto).length == 0) {
    var error_depto = "El campo es requerido";
    $("#error_depto").text(error_depto);
    $("#depto").addClass("has-error");
  } else {
    error_depto = "";
    $("#error_depto").text(error_depto);
    $("#depto").removeClass("has-error");
  }

  if ($.trim(motivo).length == 0) {
    var error_motivo = "El campo es requerido";
    $("#error_motivo").text(error_motivo);
    $("#motivo_visita").addClass("has-error");
  } else {
    error_motivo = "";
    $("#error_motivo").text(error_motivo);
    $("#motivo_visita").removeClass("has-error");
  }

  if ($.trim(dia_visita).length == 0) {
    var error_dia_visita = "El campo es requerido";
    $("#error_dia").text(error_dia_visita);
    $("#dia_visita").addClass("has-error");
  } else {
    error_dia_visita = "";
    $("#error_dia").text(error_dia_visita);
    $("#dia_visita").removeClass("has-error");
  }

  if ($.trim(hora_visita).length == 0) {
    var error_hora_visita = "El campo es requerido";
    $("#error_hora").text(error_hora_visita);
    $("#hora_entrada").addClass("has-error");
  } else {
    error_hora_visita = "";
    $("#error_hora").text(error_hora_visita);
    $("#hora_entrada").removeClass("has-error");
  }


  if ($('input[name="epp"]').is(':checked')) {

    error_epp = "";
    $("#error_epp").text(error_epp);
    $("#epp").removeClass("has-error");
  } else {
    var error_epp = "El campo es requerido";
    $("#error_epp").text(error_epp);
    $("#epp").addClass("has-error");
  }

  if ($('input[name="trabajos"]').is(':checked')) {

    error_trabajos = "";
    $("#error_trabajos").text(error_trabajos);
    $("#trabajos").removeClass("has-error");

  } else {
    var error_trabajos = "El campo es requerido";
    $("#error_trabajos").text(error_trabajos);
    $("#trabajos").addClass("has-error");
  }

  if ($('input[name="auto"]').is(':checked')) {
    let valor = $('input[name="auto"]:checked').val();
    error_auto = "";
    $("#error_auto").text(error_auto);
    $("#auto").removeClass("has-error");
    if (valor == 1) {
      let modelo = $("#modelo").val();
      let color = $("#color").val();
      let placas = $("#placas").val();

      if ($.trim(modelo).length == 0) {
        var error_modelo = "El campo es requerido";
        $("#error_modelo").text(error_modelo);
        $("#modelo").addClass("has-error");
        $("#guardar_permiso").prop("disabled", false);

      } else {
        error_modelo = "";
        $("#error_modelo").text(error_modelo);
        $("#modelo").removeClass("has-error");
      }

      if ($.trim(color).length == 0) {
        var error_color = "El campo es requerido";
        $("#error_color").text(error_color);
        $("#color").addClass("has-error");
        $("#guardar_permiso").prop("disabled", false);

      } else {
        error_color = "";
        $("#error_color").text(error_color);
        $("#color").removeClass("has-error");
      }

      if ($.trim(placas).length == 0) {
        var error_placas = "El campo es requerido";
        $("#error_placas").text(error_placas);
        $("#placas").addClass("has-error");
        $("#guardar_permiso").prop("disabled", false);

      } else {
        error_placas = "";
        $("#error_placas").text(error_placas);
        $("#placas").removeClass("has-error");
      }

      if (
        error_modelo != "" ||
        error_color != "" ||
        error_placas != ""
      ) {
        $("#guardar_permiso").prop("disabled", false);
        return false;
      }
    }



  } else {
    var error_auto = "El campo es requerido";
    $("#error_auto").text(error_auto);
    $("#auto").addClass("has-error");
  }

  if ($.trim(visitante).length == 0) {
    var error_visitante = "El campo es requerido";
    $("#error_visitante_1").text(error_visitante);
    $("#visitante_1").addClass("has-error");
  } else {
    error_visitante = "";
    $("#error_visitante_1").text(error_visitante);
    $("#visitante_1").removeClass("has-error");
  }

  if ($.trim(nacionalidad).length == 0) {
    var error_nacio = "El campo es requerido";
    $("#error_nacio_1").text(error_nacio);
    $("#nacionalidad_1").addClass("has-error");
  } else {
    error_nacio = "";
    $("#error_nacio_1").text(error_nacio);
    $("#nacionalidad_1").removeClass("has-error");
  }
  var error_visitante_clon = "";
  var error_nacio_clon = "";
  arrayItems.forEach(item => {
    if ($.trim($("#visitante_" + item).val()).length == 0) {
      var error_visitante_clon = "El campo es requerido";
      $("#error_visitante_" + item).text(error_visitante_clon);
      $("#visitante_" + item).addClass("has-error");
    } else {
      error_visitante_clon = "";
      $("#error_visitante_" + item).text(error_visitante_clon);
      $("#visitante_" + item).removeClass("has-error");
    }
    if ($.trim($("#nacionalidad_" + item).val()).length == 0) {
      var error_nacio_clon = "El campo es requerido";
      $("#error_nacio_" + item).text(error_nacio_clon);
      $("#nacionalidad_" + item).addClass("has-error");
    } else {
      error_nacio_clon = "";
      $("#error_nacio_" + item).text(error_nacio_clon);
      $("#nacionalidad_" + item).removeClass("has-error");
    }
  });

  if (
    error_num != "" ||
    error_persona_visita != "" ||
    error_depto != "" ||
    error_motivo != "" ||
    error_dia_visita != "" ||
    error_hora_visita != "" ||
    error_epp != "" ||
    error_trabajos != "" ||
    error_auto != "" ||
    error_visitante != "" ||
    error_nacio != "" ||
    error_visitante_clon != "" ||
    error_nacio_clon != ""
  ) {
    return false;
  }
  $("#guardar_permiso").prop("disabled", true);
  // var dataString = $("#permisos_visitas").serialize();
  //FormData es necesario para el envio de archivo,
  //y de la siguiente manera capturamos todos los elementos del formulario
  var dataString = new FormData($(this)[0]);

  $.ajax({
    data: dataString, //datos que se envian a traves de ajax
    url: `${urls}qhse/generar-permiso`, //archivo que recibe la peticion
    type: "post", //método de envio
    contentType: false, //importante enviar este parametro en false
    processData: false, //importante enviar este parametro en false

    success: function (response) {
    $("#guardar_permiso").prop("disabled", false);
      //una vez que el archivo recibe el request lo procesa y lo devuelve
      // console.log(response);
      //codigo que borra todos los campos del form Tickets
      if (response != "error") {
        $('#permisos_visitas').trigger("reset");
        document.getElementById('seguro_trabajo').innerHTML = "";
        document.getElementById('seguro_auto').innerHTML = "";
        document.getElementById('item-duplica').innerHTML = "";
        document.getElementById('select2-depto-container').innerHTML = "";

        var element = document.getElementById("seguro_trabajo");
        element.classList.remove("form-group");
        element.classList.remove("col-md-6");
        cont_seguro = 0;
        cont_auto = 0;
        $("#datos_auto").removeClass("col-md-12");
        $("#datos_auto").empty();

        $(".btn-primary").removeClass("active");

        Swal.fire("!Se ha Registrado el permiso!", "", "success");
      } else {
        $(".btn-primary").removeClass("active");
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
    error: function (jqXHR, status, error) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Algo salió Mal! Contactar con el Administrador",
      });//console.log("Mal Revisa entro en el error: " + error); 
    },
  });
}); */

$("#permisos_visitas").submit(function (event) {
  event.preventDefault();
  let proveedor = $("#proveedor").val();
  let num_personas = $("#num_personas").val();
  let persona_visita = $("#visita").val();
  let depto = $("#depto").val();
  let motivo = $("#motivo_visita").val();
  let dia_visita = $("#dia_visita").val();
  let hora_visita = $("#hora_entrada").val();
  let imss = $("#imss").val();
  let poliza = $("#poliza").val();
  let visitante = $("#visitante_1").val();
  let nacionalidad = $("#nacionalidad_1").val();
  var error_visitas = "";
  var error_dia_visita = "";


  var estadiaChecked = $('input[name="estadia"]').is(':checked');
  var error_estadia = estadiaChecked ? "" : "El campo es requerido";

  $("#error_estadia").text(error_estadia);
  $("#estadia").toggleClass("has-error", !estadiaChecked);

  var estadiaChecked2 = $('input[name="estadia"]').val();
  var dias_visitas = $('#dias_visitas').val();
  console.log("quepedo: ", dias_visitas);
  console.log("quepedo2: ", estadiaChecked2);
  if (estadiaChecked2 === '1') {
    if (dias_visitas === undefined) {

    } else {
      if ($.trim(dias_visitas).length === 0) {
        error_visitas = "El campo es requerido";
        $("#error_visitas").text(error_visitas);
        $("#dias_visitas").addClass("has-error");

      } else {
        error_visitas = "";
        $("#error_visitas").text(error_visitas);
        $("#dias_visitas").removeClass("has-error");

      }
    }
  } else {

    if ($.trim(dia_visita).length == 0) {
      error_dia_visita = "El campo es requerido";
      $("#error_dia").text(error_dia_visita);
      $("#dia_visita").addClass("has-error");
    } else {
      error_dia_visita = "";
      $("#error_dia").text(error_dia_visita);
      $("#dia_visita").removeClass("has-error");
    }
  }
  //fin del trabajo

  if ($.trim(proveedor).length == 0) {
    var error_provee = "El campo es requerido";
    $("#error_proveedor").text(error_provee);
    $("#proveedor").addClass("has-error");
  } else {
    error_provee = "";
    $("#error_proveedor").text(error_provee);
    $("#proveedor").removeClass("has-error");
  }

  if ($.trim(num_personas).length == 0) {
    var error_num = "El campo es requerido";
    $("#error_num_personas").text(error_num);
    $("#num_personas").addClass("has-error");
  } else {
    error_num = "";
    $("#error_num_personas").text(error_num);
    $("#num_personas").removeClass("has-error");
  }

  if ($.trim(persona_visita).length == 0) {
    var error_persona_visita = "El campo es requerido";
    $("#error_visita").text(error_persona_visita);
    $("#visita").addClass("has-error");
  } else {
    error_persona_visita = "";
    $("#error_visita").text(error_persona_visita);
    $("#visita").removeClass("has-error");
  }

  if ($.trim(depto).length == 0) {
    var error_depto = "El campo es requerido";
    $("#error_depto").text(error_depto);
    $("#depto").addClass("has-error");
  } else {
    error_depto = "";
    $("#error_depto").text(error_depto);
    $("#depto").removeClass("has-error");
  }

  if ($.trim(motivo).length == 0) {
    var error_motivo = "El campo es requerido";
    $("#error_motivo_visita").text(error_motivo);
    $("#motivo_visita").addClass("has-error");
  } else {
    error_motivo = "";
    $("#error_motivo_visita").text(error_motivo);
    $("#motivo_visita").removeClass("has-error");
  }

  if (cont_seguro == 1) {
    if ($("#file_seguro_visita").val().length == 0) {
      error_seguro_file = "El campo es requerido";
      $("#lbl_file_seguro_visita").addClass("has-error");

    } else {
      error_seguro_file = '';
      $("#lbl_file_seguro_visita").removeClass("has-error");
    }

  } else {
    error_seguro_file = '';
  }

  if ($.trim(hora_visita).length == 0) {
    var error_hora_visita = "El campo es requerido";
    $("#error_hora").text(error_hora_visita);
    $("#hora_entrada").addClass("has-error");
  } else {
    error_hora_visita = "";
    $("#error_hora").text(error_hora_visita);
    $("#hora_entrada").removeClass("has-error");
  }

  if ($('input[name="epp"]').is(':checked')) {
    error_epp = "";
    $("#error_epp").text(error_epp);
    $("#epp").removeClass("has-error");
  } else {
    var error_epp = "El campo es requerido";
    $("#error_epp").text(error_epp);
    $("#epp").addClass("has-error");
  }

  if ($('input[name="trabajos"]').is(':checked')) {

    error_trabajos = "";
    $("#error_trabajos").text(error_trabajos);
    $("#trabajos").removeClass("has-error");

  } else {
    var error_trabajos = "El campo es requerido";
    $("#error_trabajos").text(error_trabajos);
    $("#trabajos").addClass("has-error");
  }

  if ($('input[name="auto"]').is(':checked')) {
    let valor = $('input[name="auto"]:checked').val();
    error_auto = "";
    $("#error_auto").text(error_auto);
    $("#auto").removeClass("has-error");
    if (valor == 1) {
      let modelo = $("#modelo").val();
      let color = $("#color").val();
      let placas = $("#placas").val();

      if ($.trim(modelo).length == 0) {
        var error_modelo = "El campo es requerido";
        $("#error_modelo").text(error_modelo);
        $("#modelo").addClass("has-error");
        $("#guardar_permiso").prop("disabled", false);

      } else {
        error_modelo = "";
        $("#error_modelo").text(error_modelo);
        $("#modelo").removeClass("has-error");
      }

      if ($.trim(color).length == 0) {
        var error_color = "El campo es requerido";
        $("#error_color").text(error_color);
        $("#color").addClass("has-error");
        $("#guardar_permiso").prop("disabled", false);

      } else {
        error_color = "";
        $("#error_color").text(error_color);
        $("#color").removeClass("has-error");
      }

      if ($.trim(placas).length == 0) {
        var error_placas = "El campo es requerido";
        $("#error_placas").text(error_placas);
        $("#placas").addClass("has-error");
        $("#guardar_permiso").prop("disabled", false);

      } else {
        error_placas = "";
        $("#error_placas").text(error_placas);
        $("#placas").removeClass("has-error");
      }

      if (
        error_modelo != "" ||
        error_color != "" ||
        error_placas != ""
      ) {
        $("#guardar_permiso").prop("disabled", false);
        return false;
      }
    }



  } else {
    var error_auto = "El campo es requerido";
    $("#error_auto").text(error_auto);
    $("#auto").addClass("has-error");
  }

  if ($.trim(visitante).length == 0) {
    var error_visitante = "El campo es requerido";
    $("#error_visitante_1").text(error_visitante);
    $("#visitante_1").addClass("has-error");
  } else {
    error_visitante = "";
    $("#error_visitante_1").text(error_visitante);
    $("#visitante_1").removeClass("has-error");
  }

  if ($.trim(nacionalidad).length == 0) {
    var error_nacio = "El campo es requerido";
    $("#error_nacio_1").text(error_nacio);
    $("#nacionalidad_1").addClass("has-error");
  } else {
    error_nacio = "";
    $("#error_nacio_1").text(error_nacio);
    $("#nacionalidad_1").removeClass("has-error");
  }
  var error_visitante_clon = "";
  var error_nacio_clon = "";
  arrayItems.forEach(item => {
    if ($.trim($("#visitante_" + item).val()).length == 0) {
      var error_visitante_clon = "El campo es requerido";
      $("#error_visitante_" + item).text(error_visitante_clon);
      $("#visitante_" + item).addClass("has-error");
    } else {
      error_visitante_clon = "";
      $("#error_visitante_" + item).text(error_visitante_clon);
      $("#visitante_" + item).removeClass("has-error");
    }
    if ($.trim($("#nacionalidad_" + item).val()).length == 0) {
      var error_nacio_clon = "El campo es requerido";
      $("#error_nacio_" + item).text(error_nacio_clon);
      $("#nacionalidad_" + item).addClass("has-error");
    } else {
      error_nacio_clon = "";
      $("#error_nacio_" + item).text(error_nacio_clon);
      $("#nacionalidad_" + item).removeClass("has-error");
    }
  });

  if (
    error_num != "" ||
    error_persona_visita != "" ||
    error_depto != "" ||
    error_motivo != "" ||
    error_dia_visita != "" ||
    error_hora_visita != "" ||
    error_epp != "" ||
    error_trabajos != "" ||
    error_auto != "" ||
    error_visitante != "" ||
    error_nacio != "" ||
    error_visitante_clon != "" ||
    error_nacio_clon != "" ||
    error_estadia != "" ||
    error_visitas != "" ||
    error_seguro_file != ""
  ) {
    // console.log("error_num: ", error_num);
    // console.log("error_persona_visita: ", error_persona_visita);
    // console.log("error_depto: ", error_depto);
    // console.log("error_motivo: ", error_motivo);
    // console.log("error_dia_visita: ", error_dia_visita);
    // console.log("error_hora_visita: ", error_hora_visita);
    // console.log("error_epp: ", error_epp);
    // console.log("error_trabajos: ", error_trabajos);
    // console.log("error_auto: ", error_auto);
    // console.log("error_visitante: ", error_visitante);
    // console.log("error_nacio: ", error_nacio);
    // console.log("error_visitante_clon: ", error_visitante_clon);
    // console.log("error_nacio_clon: ", error_nacio_clon);
    // console.log("error_estadia: ", error_estadia);
    // console.log("error_visitas: ", error_visitas);
    // console.log("estoy aqui");
    return false;
  }
  $("#guardar_permiso").prop("disabled", true);
  let timerInterval = Swal.fire({ //se le asigna un nombre al swal
    title: '<i class="fas fa-envelope-open-text" style="margin-right: 10px;"></i>¡Notificando a Servicios Generales!',
    html: 'Espere unos Segundos.',
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });
  // var dataString = $("#permisos_visitas").serialize();
  //FormData es necesario para el envio de archivo,
  //y de la siguiente manera capturamos todos los elementos del formulario
  var dataString = new FormData($(this)[0]);

  $.ajax({
    data: dataString, //datos que se envian a traves de ajax
    url: `${urls}qhse/generar_permiso`, //archivo que recibe la peticion
    type: "post", //método de envio
    contentType: false, //importante enviar este parametro en false
    processData: false, //importante enviar este parametro en false
    dataType: "json",
    success: function (response) {
      Swal.close(timerInterval);
      $("#guardar_permiso").prop("disabled", false);
      //una vez que el archivo recibe el request lo procesa y lo devuelve
      // console.log(response);
      /*codigo que borra todos los campos del form Tickets*/
      if (response == true) {
        $('#permisos_visitas').trigger("reset");
        document.getElementById('seguro_trabajo').innerHTML = "";
        document.getElementById('seguro_auto').innerHTML = "";
        document.getElementById('item-duplica').innerHTML = "";
        document.getElementById('select2-depto-container').innerHTML = "";
        var element = document.getElementById("seguro_trabajo");
        element.classList.remove("form-group");
        element.classList.remove("col-md-6");
        cont_seguro = 0;
        cont_auto = 0;
        $("#datos_auto").removeClass("col-md-12");
        $("#datos_auto").empty();
        $(".btn-primary").removeClass("active");
        Swal.fire("!Se ha Registrado el permiso!", "", "success");
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
    error: function (jqXHR, status, error) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Algo salió Mal! Contactar con el Administrador",
      });/* 
      console.log("Mal Revisa entro en el error: " + error); */
    },
  });
});

