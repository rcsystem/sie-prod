/**
 * ARCHIVO MODULO ADMINISTRATOR
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
const firma = $("#firma_user").val();
$(document).ready(function () {

  // InfoContrato();
  // Firma();
});

/* function Firma() {
  if (firma.length == 0) {
    console.log("aqui");
    swalFirma();
    return false;
  }
} */

function InfoContrato() {
  let id_gerente = $("#gerente").val();
  let data = new FormData();

  data.append("id_user", id_gerente);

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}usuarios/info_user`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    async: true,
    dataType: "json",
    success: function (resp) {
      //console.log(resp);
      if (resp) {
        var fecha = new Date();
        fecha.toJSON().slice(0, 10);

        console.log(fecha);

        resp.forEach(function (gerente, index) {
          var nombre = `${gerente.name} ${gerente.surname}`;
          //  console.log(nombre);
          // $(`#departamento`).val(gerente.departament);
          $(`#manager`).val(nombre);


        });


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

function handleEdit(id_user) {
  url = "https://sie.grupowalworth.com/usuarios/";
  window.open(url, '_blank');
  return false;
}

$("#contrato_temp").submit(function (event) {
  event.preventDefault();
  //console.log("aquiEntrar");
  //Firma();

  if (!$('input[name="opcion"]').is(':checked')) {
    /* Mostrar Error */
    $("#error_opcion").html(`<div class="alert alert-warning alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>SE DEBE DE SELECCIONAR UNA OPCIÓN...</strong>
                            </div>`);

    setTimeout(function () {
      $(".alert")
        .fadeTo(1000, 0)
        .slideUp(800, function () {
          $(this).remove();
        });
    }, 3000);
    return false;
  }


  if ($('input[name="opcion"]:checked').val() == 2) {
    if ($("#contrato").val().length == 0) {
      error_opcion2 = "El campo es requerido";
      $("#error_opcion2").text(error_opcion2);
      $("#contrato").addClass("has-error");
      return false;
    } else {
      error_opcion2 = "";
      $("#error_opcion2").text(error_opcion2);
      $("#contrato").removeClass("has-error");
    }
  }

  if ($('input[name="opcion"]:checked').val() == 3) {
    if ($("#causa_baja").val().length == 0) {
      error_baja = "El campo es requerido";
      $("#error_baja").text(error_baja);
      $("#causa_baja").addClass("has-error");
      return false;
    } else {
      error_baja = "";
      $("#error_baja").text(error_baja);
      $("#causa_baja").removeClass("has-error");
    }
  }

  $("#guardar_contrato").prop("disabled", true);
  let timerInterval = Swal.fire({ //se le asigna un nombre al swal
    title: 'Generando Contrato!',
    html: 'Espere unos Segundos.',
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });
  const array_tipo = ['', 'El Contrado de Planta', 'El Contrato', 'La Baja'];
  const contrato_txt = array_tipo[$('input[name="opcion"]:checked').val()];
  var formData = $("#contrato_temp").serialize();
  
  $.ajax({
    type: "post", //método de envio
    data: formData, //datos que se envian a traves de ajax
    url: `${urls}usuarios/registrar_contrato`, //archivo que recibe la peticion
    // processData: false, // dile a jQuery que no procese los datos
    // contentType: false, // dile a jQuery que no establezca contentType
    dataType: "json",
    success: function (response) {
      Swal.close(timerInterval);
      //una vez que el archivo recibe el request lo procesa y lo devuelve
      // console.log(response);
      /*codigo que borra todos los campos del form newProvider*/
      if (response === true) {
        $("#guardar_contrato").prop("disabled", false);
        Swal.fire('Generar Contrato', `!${contrato_txt} se ha generado Correctamente`, 'success').then(() => {
          location.reload();
        });

      } else {
        $("#guardar_contrato").prop("disabled", false);
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
    error: function (jqXHR, status, error) {
      $("#guardar_contrato").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: `Algo salió Mal! Contactar con el Administrador ${error}`,
      });
      console.log(
        `Mal Revisa entro en el estatus: ${status} error: ${error} jqXHR: ${jqXHR}`
      );
    },
  });

});


function swalFirma() {
  $.ajax({
    url: `${urls}viajes/firma`,
    type: "POST",
    dataType: "json",
    success: async function (respFir) {
      console.log(respFir.firma);
      if (respFir.firma == null || respFir.firma == "") {
        const { value: file } = await Swal.fire({
          icon: "warning",
          title: 'Registra tu Firma',
          text: "Tu Firma No Esta Registrada",
          input: 'file',
          confirmButtonText: 'Guardar',
          allowOutsideClick: false,
          inputAttributes: {
            required: true,
            'accept': 'image/*',
            'aria-label': 'Subir Firma',
            'id': 'dato'
          },
          validationMessage: 'Firma Requerida',
        });
        console.log(file.size);
        if (file) {
          var siezekiloByte = parseInt(file.size / 1024);
          console.log(siezekiloByte);
          if (siezekiloByte > 1024) {
            Swal.fire({
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
              title: '¿Tu Firma es Correcta?',
              imageUrl: e.target.result,
              showDenyButton: true,
              confirmButtonText: 'Correcta',
              denyButtonText: `Incorrecta`,
            }).then((result) => {
              console.log("ajax");
              if (result.isDenied) {
                console.log("error de firma");
                swalFirma();
              } else if (result.isConfirmed) {

                let timerInterval = Swal.fire({ //se le asigna un nombre al swal
                  title: '¡Guardando...!',
                  html: 'Espere unos Segundos.',
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
                      console.log(respSave);
                      Swal.fire({
                        icon: "success",
                        text: "¡Se ha Guardado Correctamente!",
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