/**
 * ARCHIVO MODULO QHSE
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

  $("#extra_" + item).remove();
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
   // $("#num_nomina_extra_1").attr("onclick", `escuchar(${cont})`);
    $("#num_nomina_extra_1").attr("onchange", `validarClon(${cont});escuchar(${cont})`);
    $("#num_nomina_extra_1").attr("id", `num_nomina_extra_${cont}`);
    $("#error_num_nomina_extra_1").attr("id", `error_num_nomina_extra_${cont}`);
    $("#usuario_extra_1").attr("id", `usuario_extra_${cont}`);
    
    $("#puesto_1").attr("id", `puesto_${cont}`);
    $("#depto_1").attr("id", `depto_${cont}`);
    $("#btn_eliminar_1").attr("id", `btn_eliminar_${cont}`);
    $("#extra_usuario").empty();
    $("#extra_usuario").append(`Usuario ${cont}`);
    
    
    // Agregamos un boton para retirar el formulario
    $("#btn_eliminar_" + cont).append(
      `<div class="item-duplica card-tools" style="margin-top: 2rem;">
                                            
      <button type="button" class="btn btn-danger btn-retirar-item" onclick="retirarItem(${cont})">
          <i class="fas fa-times"></i>
      </button>
  </div>`
    );

    // Hacemos focus en el primer input del formulario
    $(`#num_nomina_extra_${cont}`).focus();
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
  if ($("#num_nomina_extra_" + clon).val().length > 0) {
    $("#error_num_nomina_extra_"+clon).text("");
    $("#num_nomina_extra_"+clon).removeClass("has-error");
  }
}

function escuchar(cont_) {
    /* Ponemos evento blur a la escucha sobre id nombre en id cliente. */
    $("#content-form").on("blur", `#num_nomina_extra_${cont_}`, function () {
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
                $(`#usuario_extra_${cont_}`).val(data.name + " " + data.surname);
              }
              if (data.job !== undefined) {
                $(`#puesto_${cont_}`).val(data.job);
              }
              if (data.departament !== undefined) {
                $(`#depto_${cont_}`).val(data.departament);
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

    var fecha_extra = $("#fecha_extra").val();
    var hora_entrada = $("#hora_entrada").val();
    var hora_salida = $("#hora_salida").val();

    if ($.trim(fecha_extra).length == 0) {
      var error_fecha_extra = "El campo es requerido";
      $("#error_fecha_extra").text(error_fecha_extra);
      $("#fecha_extra").addClass("has-error");
    } else {
      error_fecha_extra = "";
      $("#error_fecha_extra").text(error_fecha_extra);
      $("#fecha_extra").removeClass("has-error");
    }

    if ($.trim(hora_entrada).length == 0) {
      var error_hora_entrada = "El campo es requerido";
      $("#error_hora_entrada").text(error_hora_entrada);
      $("#hora_entrada").addClass("has-error");
    } else {
      error_hora_entrada = "";
      $("#error_hora_entrada").text(error_hora_entrada);
      $("#hora_entrada").removeClass("has-error");
    }

    if ($.trim(hora_salida).length == 0) {
      var error_hora_salida = "El campo es requerido";
      $("#error_hora_salida").text(error_hora_salida);
      $("#hora_salida").addClass("has-error");
    } else {
      error_hora_salida = "";
      $("#error_hora_salida").text(error_hora_salida);
      $("#hora_salida").removeClass("has-error");
    }
    if ($.trim($("#num_nomina_extra_1").val()).length == 0) {
      var error_num_nomina_extra_1 = "El campo es requerido";
      $("#error_num_nomina_extra_1").text(error_num_nomina_extra_1);
      $("#num_nomina_extra_1").addClass("has-error");
    } else {
      error_num_nomina_extra_1 = "";
      $("#error_num_nomina_extra_1").text(error_num_nomina_extra_1);
      $("#num_nomina_extra_1").removeClass("has-error");
    }
    var error_extra_num_nomina_clon ="";
    arrayItems.forEach(item => {
      if ($.trim($("#num_nomina_extra_"+item).val()).length == 0) {
        error_num_nomina_extra_clon = "El campo es requerido";
        $("#error_num_nomina_extra_"+item).text(error_num_nomina_extra_clon);
        $("#num_nomina_extra_"+item).addClass("has-error");
      } else {
        error_num_nomina_extra_clon = "";
        $("#error_num_nomina_extra_"+item).text(error_num_nomina_extra_clon);
        $("#num_nomina_extra_"+item).removeClass("has-error");
      }
    });
 
    if(error_num_nomina_extra_1 != "" || error_extra_num_nomina_clon != "" ||error_fecha_extra != ""||error_hora_entrada !=""||error_hora_salida!=""){
      return false;
    }
    var dataString = $("#horas_extras").serialize();
    //alert('Datos serializados: '+dataString);
    $.ajax({
      url: `${urls}qhse/tiempo_extra`,
      type: "POST",
      async: true,
      dataType: "json",
      data: dataString,
      success: function (resp) {
        //console.log(resp);
        if (resp == "ok") {
          /* elimina todos los form-items duplicados */
          $("#item-duplica").slideUp("slow", function () {
            $(".extras").remove();
          });
  
          Swal.fire("!Sea Registrado el permiso!", "", "success");
          $("#fecha_extra").val("");
          $("#hora_entrada").val("");
          $("#hora_salida").val("");
          $("#num_nomina_extra_1").val("");
          $("#usuario_extra_1").val("");
          $("#puesto_1").val("");
            
          cont = 1;
        } else {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Algo salió Mal! Contactar con el Administrador",
          });
        }
      },
    });
  });
    
