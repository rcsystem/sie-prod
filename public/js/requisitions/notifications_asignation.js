/**
 * ARCHIVO MODULO SISTEMAS
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
var urls = "https://sie.grupowalworth.com/";
function asignation(id_user) {
  if ($(`#user_${id_user}`).prop("checked")) {
    $(`#user_${id_user}`).removeClass("usuario");
    $("input.usuario").attr("disabled", true);

    $("#usuario_seleccionado").val(id_user);
    let data = new FormData();

    data.append("id_user", id_user);
    $.ajax({
      data: data, //datos que se envian a traves de ajax
      url: `${urls}requisiciones/areas_asignadas`,
      type: "post", //método de envio
      processData: false, // dile a jQuery que no procese los datos
      contentType: false, // dile a jQuery que no establezca contentType
      dataType: "json",
      success: function (resp) {
        // console.log(resp);
        if (resp != "error") {
          $.each(resp, function (key, val) {
            $(`#area_${val.area_operativa}`).prop("checked", true);
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "No tiene ninguna Area Asignada",
          });
        }
      },
      error: function () {
        $("#create-account-button").prop("disabled", false);
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
        $("#create-account-button").prop("disabled", false);
      } else if (jqXHR.status == 404) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "No se encontró la página solicitada [404]",
        });
        $("#create-account-button").prop("disabled", false);
      } else if (jqXHR.status == 500) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Internal Server Error [500]",
        });
        $("#create-account-button").prop("disabled", false);
      } else if (textStatus === "parsererror") {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Error de análisis JSON solicitado.",
        });
        $("#create-account-button").prop("disabled", false);
      } else if (textStatus === "timeout") {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Time out error.",
        });
        $("#create-account-button").prop("disabled", false);
      } else if (textStatus === "abort") {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Ajax request aborted.",
        });

        $("#create-account-button").prop("disabled", false);
      } else {
        alert("Uncaught Error: " + jqXHR.responseText);
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: `Uncaught Error: ${jqXHR.responseText}`,
        });
        $("#create-account-button").prop("disabled", false);
      }
    });
  } else {
    $(`#user_${id_user}`).addClass("usuario");
    $("input.usuario").attr("disabled", false);
    $(".areas").prop("checked", false);
    $("#usuario_seleccionado").val("");
    //console.log(false);
  }
}

$("#asignar_areas").on("submit", function (e) {
  e.preventDefault();

  //$("#create-account-button").prop("disabled", true);

  if (Math.abs($("#tabla_gerentes .form-check-input:checked").length) == 0) {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "No se ha Seleccionado un Usuario",
    });
    return;
  }

  if (Math.abs($("#tabla_areas .form-check-input:checked").length) == 0) {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "No se ha Seleccionado ninguna Area",
    });
    return;
  }

  let id_user = $("#usuario_seleccionado").val();
  let data = new FormData();

  data.append("id_user", id_user);

  array_area = [];
       $("#tabla_areas .form-check-input:checked").each(function(){
           
        array_area.push($(this).val());
        
        console.log(array_area);
       }); 
       data.append("areas",array_area);

   $.ajax({
      url: `${urls}requisiciones/asignar_areas`,
      data: data, //datos que se envian a traves de ajax
      type: "post", //método de envio
      processData: false, // dile a jQuery que no procese los datos
      contentType: false, // dile a jQuery que no establezca contentType
      dataType: "json",
      success: function (resp) {
        console.log(resp);
         if (resp === true) {
          // elimina todos los form-items duplicados 
          Swal.fire("!Se han registrado las Areas con Exito!", "", "success");
          
        } else {
          
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Algo salió Mal! Contactar con el Administrador",
          });
          console.log("Mal Revisa");
        } 
      },
      error: function () {
        $("#create-account-button").prop("disabled", false);
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Ocurrio un error en el servidor! Contactar con el Administrador",
        });
      },
    }).fail( function( jqXHR, textStatus, errorThrown ) {
  
      if (jqXHR.status === 0) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Fallo de conexión: ​​Verifique la red.",
        });
          $("#create-account-button").prop("disabled", false);
    
      } else if (jqXHR.status == 404) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "No se encontró la página solicitada [404]",
        });
      $("#create-account-button").prop("disabled", false);
      } else if (jqXHR.status == 500) {
    
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Internal Server Error [500]",
        });
      $("#create-account-button").prop("disabled", false);
      } else if (textStatus === 'parsererror') {
    
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Error de análisis JSON solicitado.",
        });
      $("#create-account-button").prop("disabled", false);
      } else if (textStatus === 'timeout') {
    
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Time out error.",
        });
      $("#create-account-button").prop("disabled", false);
      } else if (textStatus === 'abort') {
    
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Ajax request aborted.",
        });
    
      $("#create-account-button").prop("disabled", false);
      } else {
    
        alert('Uncaught Error: ' + jqXHR.responseText);
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: `Uncaught Error: ${jqXHR.responseText}`,
        });
      $("#create-account-button").prop("disabled", false);
      }
    }); 
});
