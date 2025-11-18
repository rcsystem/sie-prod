/**
 * ARCHIVO MODULO PAPELERIA
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
          console.log(`respuesta: ${resp}`);
          // Limpiamos el select
          
          $.each(resp, function (id, value) {
            $("#articulo_" + cont_).val(`${value.description}`);
          });
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
  
    //alert('Datos serializados: '+dataString);
    $.ajax({
      url: `${urls}almacen/materia_prima`,
      type: "POST",
      async: true,
      dataType: "json",
      data: dataString,
      success: function (resp) {
        //console.log(resp);
        if (resp === true) {
          /* elimina todos los form-items duplicados */
          $("#item-duplica").slideUp("slow", function () {
            $(".extras").remove();
          });
          cont = 1;
          $("#create-account-button").prop("disabled", false);
          $("#destinatario").val("");
          $("#codigo_1").val("");
          $("#articulo_1").val("");
          $("#cantidad_1").val("");
          $("#peso_1").val("");
          $("#observacion_1").val("");
          $("#transferir").val("");
         
          Swal.fire("!Sea Registrado la Solicitud!", "", "success");
          
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



  