/**
 * ARCHIVO MODULO MATERIA PRIMA
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
 var cont = 1;

 function validaNumericos(event) {
    return event.charCode >= 48 && event.charCode <= 57 ? true : false;
  }
  
  // Cuando hacemos click en el boton de retirar
  $("#item-duplica").on("click", ".btn-retirar-item", function () {
    console.log(cont);
    $(this).closest(".extras").remove();
    --cont;
    return false;
  });
  
  // El formulario que queremos replicar
  var formUser = $("#form_duplica").clone(true, true).html();
 

  // El encargado de agregar más formularios
  $("#btn-agregar-item").click(function () {
    if (cont < 10) {
      cont++;
      //console.log(cont);
  
      // Agregamos el formulario
      $("#item-duplica").prepend(formUser).show("slow");
      $("#item-card_1").attr("id", `item-card_${cont}`);
      //Editamos el valor del input con data de la sugerencia pulsada
      $("#codigo_1").attr("onChange", `escuchar(${cont})`);
      $("#articulo_1").attr("id", `articulo_${cont}`);
      $("#barras_1").attr("id", `barras_${cont}`);
      $("#codigo_1").attr("id", `codigo_${cont}`);
      $("#peso_1").attr("id", `peso_${cont}`);
      $("#cantidad_1").attr("id", `cantidad_${cont}`);
      $("#observacion_1").attr("id", `observacion_${cont}`);
      $("#title-item").text(`Agregar Item ${cont}`);
  
      $("#form_duplica #header-car:first").append(
        `<div class="item-duplica card-tools">
                    <button type="button" class="btn btn-tool btn-retirar-item" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                    </button>
                 </div>`
      );
  
      $("#item-card_" + cont).addClass("extras");
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
  
  function escuchar(cont_) {
   
    
    /* Ponemos evento blur a la escucha sobre id nombre en id cliente. */
    $("#form_duplica").on("blur", `#codigo_${cont_}`, function () {
      /* Obtenemos el valor del campo */
      var valor = this.value;
      console.log(`mi_valor: ${valor}`);
      /*   let a= $(this).val();
       console.log(a); */
  
      let codigo = $("#codigo_" + cont_).val();
      console.log(`codigo: ${codigo}`);
      //var articulo = $("#articulo_" + cont_);
      /* var descripcion2 = $("#articulo_" + cont_).val();
      console.log(`mi_valor: ${descripcion2}`); */
      let data = new FormData();
  
      data.append("id_codigo", codigo);
  
      $.ajax({
        data: data, //datos que se envian a traves de ajax
        url: `${urls}almacen/buscar`, //archivo que recibe la peticion
        type: "POST", //método de envio
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentType
        dataType: "json",
         async: true,
          success: function (resp) {
            
            let respArray = Array.isArray(resp) ? resp : [resp]; // Asegurarse de que respArray siempre sea un array

            if (respArray && Array.isArray(respArray)) {
                // Si resp es un array, procedemos a iterar
                $.each(respArray, function (index, value) {
                    console.log("Descripción del artículo:", value.description);
                    $(`#articulo_${cont_}`).val(value.description);
                  //  $(`#barras_${cont_}`).append(`<img src="${value.code_image}" style="margin-top:1rem;height:40px;"></img>`);


                    
                });
                console.log("condt: ",cont_);
            } else {
                console.error("Respuesta no es un array o está vacía.");
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
    });
  }

  $("#materia_prima").on("submit", function (e) {
    e.preventDefault();
    $("#create-account-button").prop("disabled", true);
  
    
    var dataString = $("#materia_prima").serialize();

    Swal.fire({
      title: "Generando Vale...",
      allowOutsideClick: false,
      showConfirmButton: false, // Esto oculta el botón "OK"
      willOpen: () => {
        Swal.showLoading();
      },
    });
  
    //alert('Datos serializados: '+dataString);
    $.ajax({
      url: `${urls}almacen/materia_prima`,
      type: "POST",
      async: true,
      dataType: "json",
      data: dataString,
      success: function (resp) {
        Swal.close();
        //console.log(resp);
        if ($.isNumeric(resp)) {
          console.log(resp);

          /* elimina todos los form-items duplicados */
          $("#item-duplica").slideUp("slow", function () {
            $(".extras").remove();
          });

          if ($.isNumeric(resp)) {
            Swal.fire({
              icon: "success",
              title: "¡Vale Generado Exitosamente!",
              html: `<p style="font-size: 25px;">Clave de Seguridad: <b>${resp}</b></p>`,
            });
          } else {
            Swal.fire({
              icon: "success",
              title: "¡Vale Generado Exitosamente!",
              html: `<p style="font-size: 25px;"><b>${resp}</b></p>`,
            });
          }
  



          cont = 1;
          $("#create-account-button").prop("disabled", false);
          $("#destinatario").val("");
          $("#codigo_1").val("");
          $("#articulo_1").val("");
          $("#cantidad_1").val("");
          $("#peso_1").val("");
          $("#observacion_1").val("");
          $("#epp_num_nomina").val("");
          $("#epp_usuario").val("");
          $("#epp_depto").val("");
          $("#epp_puesto").val("");
          $("#epp_centro_costo").val("");
          $("#epp_id_user").val("");
          $("#barras_1").empty();
          $("#payrollnumber_image").empty();





         
          
          
        } else {
          $("#create-account-button").prop("disabled", false);
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

/* 
  $(document).ready(function() {
    // Obtener el valor del radio seleccionado
    var selectedValue = $('input[name="options"]:checked').parent().text().trim();
    console.log("Valor seleccionado: " + selectedValue);
}); */

function validarUsuario() {
  if ($("#epp_num_nomina").val().length > 0) {
    $("#error_num_nomina").text("");
    $("#epp_num_nomina").removeClass("has-error");
  }
}


function escucharUsuario(cont_) {
  /* Ponemos evento blur a la escucha sobre id nombre en id cliente. */
  $("#materia_prima").on("blur", `#epp_num_nomina`, function () {
    /* Obtenemos el valor del campo */
    var valor = this.value;
    /* Si la longitud del valor es mayor a 2 caracteres.. */
    if (valor.length >= 0) {
      /* Hacemos la consulta ajax */
      var consulta = $.ajax({
        type: "POST",
        async: true,
        url: `${urls}sistemas/buscar-usuario`,
        data: { num_nomina: valor },
        dataType: "JSON",
      });

      /* En caso de que se haya retornado bien.. */
      consulta.done(function (resp) {
        $("#payrollnumber_image").empty();
        // console.log(resp);
        if (resp == "error") {
          $("#estado")
            .html(`<div class="alert alert-warning alert-dismissible" role="alert">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                          </button>
                          <strong>No se encuentra el Usuario solicitado.</strong>
                      </div>
                          <span></span>`);
          setTimeout(function () {
            $(".alert")
              .fadeTo(1000, 0)
              .slideUp(800, function () {
                $(this).remove();
              });
          }, 3000);
          return false;
        } else {
          $.each(resp, function (index, data) {
            if (data.name !== undefined) {
              $(`#epp_usuario`).val(
                `${data.name} ${data.surname} ${data.second_surname}`
              );
              $(`#epp_usuario`).parent(".form-group").addClass("fill");
            }
            if (data.job !== undefined) {
              $(`#epp_puesto`).val(data.job);
              $(`#epp_puesto`).parent(".form-group").addClass("fill");
            }
            if (data.clave_depto !== undefined) {
              $(`#epp_centro_costo`).val(data.clave_depto);
              $(`#epp_centro_costo`).parent(".form-group").addClass("fill");
            }
            if (data.departament !== undefined) {
              $(`#epp_depto`).val(data.departament);
              $(`#epp_depto`).parent(".form-group").addClass("fill");
            }
            if (data.id_user !== undefined) {
              $(`#epp_id_user`).val(data.id_user);
            }

            if (data.payrollnumber_image !== undefined) {
             // $('#payrollnumber_image').append(`<div style="margin-top:1rem;margin-bottom:1rem;"> <img src="${data.payrollnumber_image}" style="height:40px;">    </div>`);

            }

            return true;
          });
        }
      });

      /* Si la consulta ha fallado.. */
      consulta.fail(function () {
        $(
          "#resultado"
        ).html(`<div class="alert alert-warning alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
          <strong>A ocurrido un Error no se encuentra el Usuario solicitado.</strong>
      </div>
          <span></span>`);
        setTimeout(function () {
          $(".alert")
            .fadeTo(1000, 0)
            .slideUp(800, function () {
              $(this).remove();
            });
        }, 3000);
        return false;
      });
    } else {
      /* Mostrar error */
      $("#resultado")
        .html(`<div class="alert alert-warning alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                    <strong>El codigo debe tener una longitud mayor a 2 caracteres...</strong>
                                  </div>
                                    <span></span>`);
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
}

$('input[name="options"]').on('change', function() {
  if ($('#option3').is(':checked')) {
      $('#herramientas').show();
  } else {
      $('#herramientas').hide();
  }
});





  