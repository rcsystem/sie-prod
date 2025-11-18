/**
 * ARCHIVO MODULO SUMINISTROS
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
 var cont = 1;
 var arrayItems = [];

// Cuando hacemos click en el boton de retirar
function retirarItem(item) {
  var i = arrayItems.indexOf(item);
  arrayItems.splice(i, 1);
  sessionStorage.setItem('arrayItems', JSON.stringify(arrayItems));

  $(`#extra_${item}`).remove();
  if (cont > 0) {
      --cont;
  }
  return false;
};

  // El formulario que queremos replicar
var formUser = $("#tiempo_extra").clone(true, true).html();
//$("#origen").clone(true).appendTo("#destino");

// El encargado de agregar más formularios 
$("#btn-agregar-item").click(function () {
  // if (cont < 25) {
    if (arrayItems.length <= 23) {
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
    $("#extra_1").attr("id", `extra_${cont}`);


    //Editamos el valor del input con data de la sugerencia pulsada
    $("#num_partida_1").attr("onchange", `escuchar(${cont})`);
    
    $("#num_partida_1").attr("id", `num_partida_${cont}`);
    $("#error_num_partida_1").attr("id", `error_num_partida_${cont}`);
    $("#usuario_extra_1").attr("id", `usuario_extra_${cont}`);
    
    $("#tipo_1").attr("id", `tipo_${cont}`);
    $("#cantidad_1").attr("id", `cantidad_${cont}`);
    $("#diametro_1").attr("id", `diametro_${cont}`);
    $("#clase_1").attr("id", `clase_${cont}`);
    $("#tiempo_1").attr("id", `tiempo_${cont}`);
    $("#desc_1").attr("id", `desc_${cont}`);
    $("#desc_breve_1").attr("id", `desc_breve_${cont}`);
    $("#figura_1").attr("id", `figura_${cont}`);
    $("#btn_eliminar_1").attr("id", `btn_eliminar_${cont}`);
    $("#partida").empty();
    $("#partida").append(`Num partida ${cont}`);
    $("#error_cantidad_1").attr("id", `error_cantidad_${cont}`);
    
    
    
    // Agregamos un boton para retirar el formulario
    $(`#btn_eliminar_${cont}`).append(
      `<div class="item-duplica card-tools" style="margin-top: 2rem;">
                                            
      <button type="button" class="btn btn-danger btn-retirar-item" onclick="retirarItem(${cont})">
          <i class="fas fa-times"></i>
      </button>
  </div>`
    );

    // Hacemos focus en el primer input del formulario
    $(`#num_partida_${cont}`).focus();
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
          <strong>NO SE PERMITEN MAS DE 25 ITEMS EN LA SOLICITUD...</strong>
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

function validarClon(clon) {
  if ($(`#num_partida_${clon}`).val().length > 0) {
    $(`#error_num_partida_${clon}`).text("");
    $(`#num_partida_${clon}`).removeClass("has-error");
  }
}

function escuchar(cont_) {
    /* Ponemos evento blur a la escucha sobre id nombre en id cliente. */
    $("#content-form").on("blur", `#num_partida_${cont_}`, function () {  
      /* Obtenemos el valor del campo */
      var valor = this.value;
      console.log(` valor del campo ${valor}`);
     
        /* Hacemos la consulta ajax */
        var consulta = $.ajax({
          type: "POST",
          async: true,
          url: `${urls}suministros/buscar-partida`,
          data: { num_partida: valor },
          dataType: "JSON",
        });
  
        /* En caso de que se haya retornado bien.. */
        consulta.done(function (resp) {
          console.log(resp);
          if (!resp) {
            $("#estado")
              .html(`<div class="alert alert-warning alert-dismissible" role="alert">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                          </button>
                          <strong>Ha ocurrido un error no se encuentra la Partida solicitada.</strong>
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
              if (data.tipo_partida !== undefined) {
                $(`#tipo_${cont_}`).val(data.tipo_partida);
              }
            
              if (data.diametro_partida !== undefined) {
                $(`#diametro_${cont_}`).val(data.diametro_partida);
              }
              if (data.clase_partida !== undefined) {
                $(`#clase_${cont_}`).val(data.clase_partida);
              }
              if (data.tiempo_entrega !== undefined) {
                $(`#tiempo_${cont_}`).val(data.tiempo_entrega);
              }
              if (data.figura_walworth !== undefined) {
                $(`#figura_${cont_}`).val(data.figura_walworth);
              }
              if (data.desc_partida !== undefined) {
                $(`#desc_${cont_}`).val(data.desc_partida);
              }
              if (data.desc_breve !== undefined) {
                $(`#desc_breve_${cont_}`).val(data.desc_breve);
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
          <strong>Ha ocurrido un error no se encuentra la Partida solicitada.</strong>
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
     
    });
  }

  function validar() {
    if ($("#fecha_extra").val().length > 0) {
      $("#error_fecha_extra").text("");
      $("#fecha_extra").removeClass("has-error");
    }
    if ($("#hora_entrada").val().length > 0) {
      $("#error_hora_entrada").text("");
      $("#hora_entrada").removeClass("has-error");
    }
    if ($("#hora_salida").val().length > 0) {
      $("#error_hora_salida").text("");
      $("#hora_salida").removeClass("has-error");
    }
  }
  
  $("#horas_extras").submit(function (e) {
    e.preventDefault();

    let num_partida = $("#num_partida_1").val();
    let cantidad = $("#cantidad_1").val();
    let orden_compra = $("#orden_compra").val();

    element = $('[name="clase[]"]').length;
    console.log("elementos:"+element);

    if ($.trim(orden_compra).length == 0) {
      var error_orden_compra = "El campo es requerido";
      $("#error_orden_compra").text(error_orden_compra);
      $("#orden_compra").addClass("has-error");
    } else {
      error_orden_compra = "";
      $("#error_orden_compra").text(error_orden_compra);
      $("#orden_compra").removeClass("has-error");
    }

    
    if ($.trim(num_partida).length == 0) {
      var error_num_partida = "El campo es requerido";
      $("#error_num_partida_1").text(error_num_partida);
      $("#num_partida_1").addClass("has-error");
    } else {
      error_num_partida = "";
      $("#error_num_partida_1").text(error_num_partida);
      $("#num_partida_1").removeClass("has-error");
    }

    if ($.trim(cantidad).length == 0) {
      var error_cantidad = "El campo es requerido";
      $("#error_cantidad_1").text(error_cantidad);
      $("#cantidad_1").addClass("has-error");
    } else {
      error_cantidad = "";
      $("#error_cantidad_1").text(error_cantidad);
      $("#cantidad_1").removeClass("has-error");
    }
    var error_codigos_clon = "";
    var error_cantidad_clon = "";

    arrayItems.forEach(item => {
         
      if ($.trim($("#cantidad_"+item).val()).length == 0) {
        var error_cantidad_clon = "El campo es requerido";
        $(`#error_cantidad_${item}`).text(error_cantidad_clon);
        $(`#cantidad_${item}`).addClass("has-error");
      } else {
        error_cantidad_clon = "";
        $(`#error_cantidad_${item}`).text(error_cantidad_clon);
        $(`#cantidad_${item}`).removeClass("has-error");
      }

      if ($.trim($(`#num_partida_${item}`).val()).length == 0) {
        var error_codigos_clon = "El campo es requerido";
        $(`#error_num_partida_${item}`).text(error_codigos_clon);
        $(`#num_partida_${item}`).addClass("has-error");
      } else {
        error_codigos_clon = "";
        $(`#error_num_partida_${item}`).text(error_codigos_clon);
        $(`#num_partida_${item}`).removeClass("has-error");
      }
    });

    if(error_num_partida != "" || error_cantidad != "" || error_orden_compra != "" || error_codigos_clon != "" || error_cantidad_clon != ""){
      console.log(`num_partida_1 : ${error_num_partida}`);
      console.log(`error_cantidad : ${error_cantidad}`);

      console.log(`error_codigos_clon : ${error_codigos_clon}`);
      console.log(`error_cantidad_clon : ${error_cantidad_clon}`);
     
      return false;
    }

  
    var dataString = $("#horas_extras").serialize();
    //alert('Datos serializados: '+dataString);
    $.ajax({
      url: `${urls}suministros/guardar-solicitud`,
      type: "POST",
      async: true,
      dataType: "json",
      data: dataString,
      success: function (resp) {
        //console.log(resp);
        if (resp) {
          /* elimina todos los form-items duplicados */
          $("#item-duplica").slideUp("slow", function () {
            $(".extras").remove();
          });
  
          Swal.fire("!Sea Registrado el permiso!", "", "success");
          document.getElementById('horas_extras').reset();
          cont = 1;
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
  });
    
