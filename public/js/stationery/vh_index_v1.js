/**
 * ARCHIVO MODULO PAPELERIA
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
var cont = 1;
var error_form_categoria = "0";
var error_form_descripcion = "0";
var error_form_cantidad = "0";
var error_funcion = "0";

if ($("#descripcion_" + cont).val().length == 0) {
  $("#cantidad_" + cont).attr("readonly", "readonly");
} else {
  $("#cantidad_" + cont).removeAttr("readonly", "readonly");
}

function validaNumericos(event) {
  return event.charCode >= 48 && event.charCode <= 57 ? true : false;
}

// Cuando hacemos click en el boton de retirar
$("#item-duplica").on("click", ".btn-retirar-item", function () {
  // console.log(cont);
  $(this).closest(".extras").remove();
  --cont;
  return false;
});

// El formulario que queremos replicar
var formUser = $("#form_duplica").clone(true, true).html();
//$("#origen").clone(true).appendTo("#destino");
// El encargado de agregar más formularios
$("#btn-agregar-item").click(function () {

  if ($("#categoria_" + cont).val().length == 0) {
    error_form_categoria = "Categoria Requerida";
    $("#error_categoria_" + cont).text(error_form_categoria);
    $("#categoria_" + cont).addClass('has-error');
  } else {
    error_form_categoria = "";
    $("#error_categoria_" + cont).text(error_form_categoria);
    $("#categoria_" + cont).removeClass('has-error');
  }

  if ($("#descripcion_" + cont).val().length == 0) {
    error_form_descripcion = "Descripcion Requerida";
    $("#error_descripcion_" + cont).text(error_form_descripcion);
    $("#descripcion_" + cont).addClass('has-error');
  } else {
    error_form_descripcion = "";
    $("#error_descripcion_" + cont).text(error_form_descripcion);
    $("#descripcion_" + cont).removeClass('has-error');
  }

  if ($("#cantidad_" + cont).val().length == 0 && error_funcion == "") {
    error_form_cantidad = "Cantidad Requerida";
    $("#error_cantidad_" + cont).text(error_form_cantidad);
    $("#cantidad_" + cont).addClass('has-error');
  } else if (error_funcion != "") {
    error_form_cantidad = "error de funcion";
  }
  else {
    error_form_cantidad = "";
    $("#error_cantidad_" + cont).text(error_form_cantidad);
    $("#cantidad_" + cont).removeClass('has-error');
  }
  if (error_form_categoria != ""
    || error_form_descripcion != ""
    || error_form_cantidad != "") {
  }
  else if (cont < 10) {
    cont++;
    //console.log("nuevo valor cont   ",cont);

    // Agregamos el formulario
    $("#item-duplica").prepend(formUser).show("slow");
    $("#item-card_1").attr("id", `item-card_${cont}`);
    //Editamos el valor del input con data de la sugerencia pulsada
    $("#categoria_1").attr("onChange", `escuchar(${cont})`);
    $("#descripcion_1").attr("onChange", `cambioImagen(${cont})`);
    $("#cantidad_1").attr("onChange", `consultaInventario(${cont})`);
    $("#categoria_1").attr("id", `categoria_${cont}`);
    $("#imagen_1").attr("id", `imagen_${cont}`);
    $("#descripcion_1").attr("id", `descripcion_${cont}`);
    $("#cantidad_1").attr("id", `cantidad_${cont}`);
    $("#title-item").text(`Agregar Item ${cont}`);
    $("#error_categoria_1").attr("id", `error_categoria_${cont}`);
    $("#error_descripcion_1").attr("id", `error_descripcion_${cont}`);
    $("#error_cantidad_1").attr("id", `error_cantidad_${cont}`);

    $("#form_duplica #header-car:first").append(
      `<div class="item-duplica card-tools">
                   <button type="button" class="btn btn-tool btn-retirar-item" data-card-widget="remove">
                   <i class="fas fa-times"></i>
                   </button>
                </div>`
    );

    $("#item-card_" + cont).addClass("extras");
    // Volvemos a cargar todo los plugins que teníamos, dentro de esta función esta el del datepicker assets/js/ini.js
  }
  else {
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
  $("#form_duplica").on("change", `#categoria_${cont_}`, function () {
    /* Obtenemos el valor del campo */
    var valor = this.value;
    // console.log(`mi_valor: ${valor}`);
    /*   let a= $(this).val();
     console.log(a); */

    let categoria = $("#categoria_" + cont_).val();
    //console.log(`categoria: ${categoria}`);
    var descripcion = $("#descripcion_" + cont_);
    var descripcion2 = $("#descripcion_" + cont_).val();
    //console.log(`mi_valor: ${descripcion2}`);
    let data = new FormData();

    data.append("id_categoria", categoria);

    $.ajax({
      data: data, //datos que se envian a traves de ajax
      url: urls + "papeleria/categoria_pape_vh", //archivo que recibe la peticion
      type: "post", //método de envio
      processData: false, // dile a jQuery que no procese los datos
      contentType: false, // dile a jQuery que no establezca contentType
      dataType: "json",
      success: function (resp) {
        // console.log(resp);
        // Limpiamos el select
        descripcion.find("option").remove();
        $("#descripcion_" + cont_).append(
          '<option value="">Seleccionar Opción</option>'
        );
        $.each(resp, function (id, value) {
          $("#descripcion_" + cont_).append(
            `<option value="${value.id_product}">${value.description_product}</option>`
          );
        });
        error_form_categoria = "";
        $("#error_categoria_" + cont_).text(error_form_categoria);
        $("#categoria_" + cont_).removeClass('has-error');
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

function cambioImagen(cont_) {
  $("#form_duplica").on("change", `#descripcion_${cont_}`, function () {
    let id_producto = $(this).val();
    //console.log("este es el id produc \n".id_producto);

    var image = $("#imagen_" + cont_);
    let data = new FormData();

    data.append("id_producto", id_producto);

    $.ajax({
      data: data, //datos que se envian a traves de ajax
      url: urls + "papeleria/imagen_pape_vh", //archivo que recibe la peticion
      type: "post", //método de envio
      processData: false, // dile a jQuery que no procese los datos
      contentType: false, // dile a jQuery que no establezca contentType
      dataType: "json",
      success: function (resp) {
        //console.log(resp);
        // Limpiamos el select
        image.find("src").empty();
        $("#imagen_" + cont_).attr("src", resp.image_product);
        $("#imagen_" + cont_).attr("width", "200px");
        $("#imagen_" + cont_).attr("height", "200px");
        if (resp.stock_product > 0) {
          error_funcion = "";
          $("#cantidad_" + cont_).removeAttr("readonly", "readonly");
          $(`#error_cantidad_${cont_}`).text("");
          $(`#cantidad_${cont_}`).removeClass("has-error");
        }
        else {
          $(`#cantidad_${cont_}`).attr("readonly", "readonly");
          error_funcion = "No hay Existencia del Producto por el momento.";
          $(`#error_cantidad_${cont_}`).text(error_funcion);
          $(`#cantidad_${cont_}`).addClass("has-error");

        }
        error_form_descripcion = "";
        $("#error_descripcion_" + cont_).text(error_form_descripcion);
        $("#descripcion_" + cont_).removeClass('has-error');
        if ($("#cantidad_" + cont_).val().length > 0) {
          $("#cantidad_" + cont_).val("");
        error_form_cantidad = "";
        $("#error_cantidad_" + cont_).text(error_form_cantidad);
        $("#cantidad_" + cont_).removeClass('has-error');
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

function consultaInventario(cont_) {
  if ($("#cantidad_" + cont_).val().length > 0) {
  error_form_cantidad = "";
  $("#error_cantidad_" + cont_).text(error_form_cantidad);
  $("#cantidad_" + cont_).removeClass('has-error');
  }
  /*
  $("#form_duplica").on
    ("change", `#cantidad_${cont_}`, function () {

      let cantidad = $(this).val();
      let id_producto = $("#descripcion_" + cont_).val();

      let data = new FormData();
      data.append("id_producto", id_producto);

      $.ajax({
        data: data, 
        type: "post", 
        url: urls + "papeleria/inventario_solicitud_vh",
        processData: false, 
        contentType: false,
        dataType: "json",
        success: function (resp) {
          // console.log(resp);
          Math.abs(cantidad);
          Math.abs(resp.stock_product);
          var a = cantidad / resp.stock_product;
          console.log(
           "\n este es valor de cont_ \n",cont_,
          "\neste es el id produc \n",$("#descripcion_" + cont_).val(),
          "\n esta es la cantidad solicitada\n",cantidad," int ",!isNaN(cantidad),
          "\n cantidad disponible",resp.stock_product,"int ",!isNaN(resp.stock_product),
          "\n valor de a: ",a );
          if (a <= 1) {
            error_funcion = "";
            $(`#error_cantidad_${cont_}`).text("");
            $(`#cantidad_${cont_}`).removeClass("has-error");
          }
          else {
            error_funcion = "Existencia del Producto por el momento: " + resp.stock_product;
            $(`#error_cantidad_${cont_}`).text(error_funcion);
            $(`#cantidad_${cont_}`).addClass("has-error");

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
    });*/
    
}


$("#solicitud_papeleria").on("submit", function (e) {
  e.preventDefault();
  $("#create-account-button").prop("disabled", true);

  if (cont == 1
    && $("#categoria_" + cont).val().length == 0
    && $("#descripcion_" + cont).val().length == 0
    && $("#cantidad_" + cont).val().length == 0) {

    Swal.fire({
      icon: "error",
      title: "!ERROR¡",
      text: "Llena el formulario antes de generar la solicitud",
    });
  }

  if ($("#categoria_" + cont).val().length == 0) {
    error_form_categoria = "Categoria Requerida";
    $("#error_categoria_" + cont).text(error_form_categoria);
    $("#categoria_" + cont).addClass('has-error');
  } else {
    error_form_categoria = "";
    $("#error_categoria_" + cont).text(error_form_categoria);
    $("#categoria_" + cont).removeClass('has-error');
  }

  if ($("#descripcion_" + cont).val().length == 0) {
    error_form_descripcion = "Descripcion Requerida";
    $("#error_descripcion_" + cont).text(error_form_descripcion);
    $("#descripcion_" + cont).addClass('has-error');
  } else {
    error_form_descripcion = "";
    $("#error_descripcion_" + cont).text(error_form_descripcion);
    $("#descripcion_" + cont).removeClass('has-error');
  }

  if ($("#cantidad_" + cont).val().length == 0 && error_funcion == "") {
    error_form_cantidad = "Cantidad Requerida";
    $("#error_cantidad_" + cont).text(error_form_cantidad);
    $("#cantidad_" + cont).addClass('has-error');
  } else if (error_funcion != "") {
    error_form_cantidad = "error de funcion";
  }
  else {
    error_form_cantidad = "";
    $("#error_cantidad_" + cont).text(error_form_cantidad);
    $("#cantidad_" + cont).removeClass('has-error');
  }
  if (
    error_form_categoria != "" ||
    error_form_descripcion != "" ||
    error_form_cantidad != ""

  ) {
    $("#create-account-button").prop("disabled", false);
    return false;
  }

  var dataString = $("#solicitud_papeleria").serialize();

  // alert('Datos serializados: ' + dataString);
  $.ajax({
    url: `${urls}papeleria/solicitud_papeleria_vh`,
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
        $("#categoria_1").val("");
        $("#descripcion_1").val("");
        $("#cantidad_1").val("");
        $("#imagen_1").attr("src", "");
        $("#imagen_1").attr("width", "");
        $("#imagen_1").attr("height", "");
        Swal.fire("!Sea Registrado la Solicitud!", "", "success");

      } else {
        $("#create-account-button").prop("disabled", false);
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
        //console.log("Mal Revisa");
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
