/**
 * ARCHIVO MODULO NUEVOS TICKETS
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
/*Generar ticket it */

$("#generar_ticket_it").submit(function (event) {
  event.preventDefault();
  $("#guardar_ticket_it").prop("disabled", true);

  let data = new FormData();
  
  data.append("usuario_it", $("#usuario_it").val());
  data.append("actividad_it", $("#actividad_it").val());
  data.append("fecha_actividad", $("#fecha_actividad").val());
  let complejidad = $("#complejo_it input[type='radio']:checked").val();
  data.append("complejidad_it", complejidad);

  let home = $("#home_office_it input[type='radio']:checked").val();
  data.append("homeoffice_it", home);
  //data.append("fecha_ticket", fecha_ticket);
  
  console.log(home);
  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: urls + "sistemas/crear-ticket-it", //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    success: function (response) {
      //una vez que el archivo recibe el request lo procesa y lo devuelve
      //console.log(response);
      /*codigo que borra todos los campos del form Activiadades*/
      if (response != "error") {
        
        $("#actividad_it").val("");
        $("#guardar_ticket_it").prop("disabled", false);
        $(".radio_it").prop("checked", false);
        $(".btn-primary").removeClass("active");
        $("#fecha_actividad").val("");
        Swal.fire("!Sea Registrado la Actividad!", "", "success");
      } else {
        $("#guardar_ticket_it").prop("disabled", false);
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
    error: function (jqXHR, status, error) {
      $("#guardar_ticket_it").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Algo salió Mal! Contactar con el Administrador",
      });
      console.log("Mal Revisa entro en el error: "+ error);
    },
  }).fail( function( jqXHR, textStatus, errorThrown ) {

    if (jqXHR.status === 0) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Fallo de conexión: ​​Verifique la red.",
      });
         $("#guardar_ticket_it").prop("disabled", false);
  
    } else if (jqXHR.status == 404) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No se encontró la página solicitada [404]",
      });
     $("#guardar_ticket_it").prop("disabled", false);
    } else if (jqXHR.status == 500) {
  
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Internal Server Error [500]",
      });
     $("#guardar_ticket_it").prop("disabled", false);
    } else if (textStatus === 'parsererror') {
  
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Error de análisis JSON solicitado.",
      });
     $("#guardar_ticket_it").prop("disabled", false);
    } else if (textStatus === 'timeout') {
  
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Time out error.",
      });
     $("#guardar_ticket_it").prop("disabled", false);
    } else if (textStatus === 'abort') {
  
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ajax request aborted.",
      });
  
     $("#guardar_ticket_it").prop("disabled", false);
    } else {
  
      alert('Uncaught Error: ' + jqXHR.responseText);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: `Uncaught Error: ${jqXHR.responseText}`,
      });
     $("#guardar_ticket_it").prop("disabled", false);
    }
  });
});
