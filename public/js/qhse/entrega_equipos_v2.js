/**
 * ARCHIVO MODULO QHSE
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */

$(document).ready(function () {
  listaEpp(1);
});

var cont = 1;
var arrayItems = [];

var arrayInventory = [];

// Cuando hacemos click en el boton de retirar
function retirarItem(item) {
  var i = arrayItems.indexOf(item);
  arrayItems.splice(i, 1);
  sessionStorage.setItem("arrayItems", JSON.stringify(arrayItems));

  $(`#extra_${item}`).remove();
  if (cont > 0) {
    --cont;
  }
  return false;
}

// El formulario que queremos replicar
//var formUser = $("#tiempo_extra").clone(true, true).html();
var formUser = $("#tiempo_extra").clone();
//$("#origen").clone(true).appendTo("#destino");

// El encargado de agregar más formularios
$("#btn-agregar-item").click(function () {
  if (arrayItems.length < 25) {
    cont++;

    // Verificar si `cont` ya está en `arrayItems`, en cuyo caso incrementamos `cont` hasta encontrar un número no usado.
    while (arrayItems.includes(cont)) {
      cont++;
    }

    // Clonación de campo...
    arrayItems.push(cont);
    // Se guarda en sessionStorage después de JSON stringificarlo
    sessionStorage.setItem("arrayItems", JSON.stringify(arrayItems));

    // Clonar el formulario
    var clonedForm = formUser.clone();
    clonedForm.attr("id", `form_${cont}`);
    
    // Reemplazar IDs y atributos relevantes en el formulario clonado
    clonedForm.find("#epp_1").attr("onchange", `inventary(this)`).attr("id", `epp_${cont}`).attr("list", `browsers_${cont}`);
    clonedForm.find("#browsers_1").attr("id", `browsers_${cont}`);
    clonedForm.find("#error_epp_1").attr("id", `error_epp_${cont}`);
    clonedForm.find("#suggestions_1").attr("id", `suggestions_${cont}`);
    
    clonedForm.find("#puesto_1").attr("id", `puesto_${cont}`);
    clonedForm.find("#depto_1").attr("id", `depto_${cont}`);
    clonedForm.find("#btn_eliminar_1").attr("id", `btn_eliminar_${cont}`);
    clonedForm.find("#inventario_1").attr("id", `inventario_${cont}`);
    clonedForm.find("#id_product_1").attr("id", `id_product_${cont}`);
    clonedForm.find("#medida_1").attr("id", `medida_${cont}`);
    clonedForm.find("#cantidad_1").attr("id", `cantidad_${cont}`);
    clonedForm.find("#error_cantidad_1").attr("id", `error_cantidad_${cont}`);
    clonedForm.find("#extra_1").attr("id", `extra_${cont}`);

    // Cambiar el texto del usuario
    clonedForm.find("#extra_usuario").text(`Usuario ${cont}`);

    // Agregar el botón de eliminar al formulario clonado
    clonedForm.find(`#btn_eliminar_${cont}`).append(
      `<div class="item-duplica card-tools" style="margin-top: 2rem;">
          <button type="button" class="btn btn-danger btn-retirar-item" onclick="retirarItem(${cont})">
              <i class="fas fa-times"></i>
          </button>
      </div>`
    );

    // Agregar el formulario clonado al DOM
    $("#item-duplica").prepend(clonedForm);
    clonedForm.addClass("animate-show");

    // Hacer focus en el primer input del formulario clonado
    clonedForm.find(`#epp_${cont}`).focus();

    // Aplicar clases adicionales y cargar plugins
    $("#extra_" + cont).addClass("extras");

    listaEpp(cont);

  } else {
    /* Mostrar error */
    $("#resultado").html(
      `<div class="alert alert-warning alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <strong>NO SE PERMITEN MÁS DE 25 ITEMS EN LA SOLICITUD...</strong>
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

function validarUsuario() {
  if ($("#epp_num_nomina").val().length > 0) {
    $("#error_num_nomina").text("");
    $("#epp_num_nomina").removeClass("has-error");
  }
}

function validarClon(clon) {
  if ($(`#epp_${clon}`).val().length > 0) {
    $(`#error_epp_${clon}`).text("campo vacio");
    $(`#epp_${clon}`).addClass("has-error");
  } else {
    $(`#error_epp_${clon}`).text("");
    $(`#epp_${clon}`).removeClass("has-error");
  }
}

function escuchar(cont_) {
  /* Ponemos evento blur a la escucha sobre id nombre en id cliente. */
  $("#content-form").on("blur", `#epp_num_nomina`, function () {
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
              $(`#id_user`).val(data.id_user);
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

$("#equipos").submit(function (e) {
  e.preventDefault();

  var id_user = document.getElementById("id_user").value.trim();
  var num_nomina = document.getElementById("epp_num_nomina").value.trim();
  var epp_depto = document.getElementById("epp_depto").value.trim();
  var epp_puesto = document.getElementById("epp_puesto").value.trim();
  var epp_1 = document.getElementById("epp_1").value.trim();
  var cantidad_1 = document.getElementById("cantidad_1").value.trim();

  var entrega = document.getElementById("entrega_equipo").value.trim();

  var error_num_nomina = num_nomina.length === 0 ? "El campo es requerido" : "";

  document.getElementById("error_num_nomina").textContent = error_num_nomina;
  document
    .getElementById("epp_num_nomina")
    .classList.toggle("has-error", num_nomina.length === 0);

  var error_epp_depto = epp_depto.length === 0 ? "El campo es requerido" : "";

  document.getElementById("error_epp_depto").textContent = error_epp_depto;
  document
    .getElementById("epp_depto")
    .classList.toggle("has-error", epp_depto.length === 0);

  var error_epp_puesto = epp_puesto.length === 0 ? "El campo es requerido" : "";

  document.getElementById("error_epp_puesto").textContent = error_epp_puesto;
  document
    .getElementById("epp_puesto")
    .classList.toggle("has-error", epp_puesto.length === 0);

  var error_epp_1 = epp_1.length === 0 ? "El campo es requerido" : "";

  document.getElementById("error_epp_1").textContent = error_epp_1;
  document
    .getElementById("epp_1")
    .classList.toggle("has-error", epp_1.length === 0);

  var error_cantidad_1 = cantidad_1.length === 0 ? "El campo es requerido" : "";

  document.getElementById("error_cantidad_1").textContent = error_cantidad_1;
  document
    .getElementById("cantidad_1")
    .classList.toggle("has-error", cantidad_1.length === 0);

  var error = "";
  var error2 = "";
  if (arrayItems.length > 0) {
    arrayItems.forEach((item) => {
      var value = document.getElementById(`epp_${item}`).value.trim();
      error = value.length === 0 ? "El campo es requerido" : "";

      document.getElementById(`error_epp_${item}`).textContent = error;
      document
        .getElementById(`epp_${item}`)
        .classList.toggle("has-error", value.length === 0);

      var cantidad = document.getElementById(`cantidad_${item}`).value.trim();
      error2 = cantidad.length === 0 ? "El campo es requerido" : "";

      document.getElementById(`error_cantidad_${item}`).textContent = error2;
      document
        .getElementById(`cantidad_${item}`)
        .classList.toggle("has-error", cantidad.length === 0);
    });
  }

  if (
    error_num_nomina != "" ||
    error_epp_depto != "" ||
    error_epp_puesto != "" ||
    error_epp_1 != "" ||
    error_cantidad_1 != "" ||
    error != "" ||
    error2 != ""
  ) {
    console.log("estoy aqui");
    return false;
  }

  var dataString = $("#equipos").serialize();
  //alert('Datos serializados: '+dataString);
  Swal.fire({
    title: "Generando Vale...",
    allowOutsideClick: false,
    showConfirmButton: false, // Esto oculta el botón "OK"
    willOpen: () => {
      Swal.showLoading();
    },
  });
  $.ajax({
    url: `${urls}qhse/entrega_epp`,
    type: "POST",
    async: true,
    dataType: "json",
    data: dataString,
    success: function (save) {
      Swal.close();
      //console.log(resp);
      if (save != false) {
        /* elimina todos los form-items duplicados */
        $("#item-duplica").slideUp("slow", function () {
          $(".extras").remove();
        });

        if ($.isNumeric(save)) {
          Swal.fire({
            icon: "success",
            title: "¡Vale Generado Exitosamente!",
            html: `<p style="font-size: 25px;">Clave de Seguridad: <b>${save}</b></p>`,
          });
        } else {
          Swal.fire({
            icon: "success",
            title: "¡Vale Generado Exitosamente!",
            html: `<p style="font-size: 25px;"><b>${save}</b></p>`,
          });
        }

        // Asegúrate de que el campo #especificar exista
        if ($("#especificar").length) {
          $("#especificar").val("");
        }

        $("#epp_1").val("");
        $("#entrega_equipo").val("0");
        $("#inventario_1").val("");
        $("#cantidad_1").val("");
        $("#epp_num_nomina").val("");
        $("#epp_usuario").val("");
        $("#epp_depto").val("");
        $("#epp_puesto").val("");
        $("#id_user").val("");
        $("#codigo_1").val("");
        

        // Limpia todos los radios
        $('input[type="radio"]').prop("checked", false);

        // Restablece el valor predeterminado
        $('input[type="radio"][data-default="true"]').prop("checked", true);

        $("#entrega_equipo").prop("checked", false);

        cont = 1;
      } else {
        Swal.close();
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
  });
});

function listaEpp(num) {
  let cont = num > 1 ? num : 1;
  $.ajax({
    url: `${urls}qhse/listado_epp`,
    type: "post",
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (data) {
      // console.log(data);
      data.forEach(function (elemento) {
        var opcion = $("<option>", {
          text: `${elemento.description_product}`,
          value: `${elemento.description_product}`,
        });
        $(`#browsers_${cont}`).append(opcion);
      });
    },
  });
}

function inventary1(item) {
  console.log(item.value);
  let items = item.value;

  var data = new FormData();
  data.append("product", items);

  $.ajax({
    data: data,
    url: `${urls}qhse/listado_epp_nombre`,
    type: "post",
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (data) {
      // console.log(data);
      data.forEach(function (elemento) {
        $(`#inventario_${cont}`).val(
          `${elemento.stock_product} ${elemento.unit_of_measurement}`
        );
        $(`#inventario_${cont}`).parent(".form-group").addClass("fill");
        console.log(`${elemento.stock_product}`);
        $(`#medida_${cont}`).val(elemento.unit_of_measurement);
        $(`#medida_${cont}`).parent(".form-group").addClass("fill");
        console.log(`medida ${elemento.unit_of_measurement}`);
        $(`#id_product_${cont}`).val(elemento.id_product);
        console.log(`id product ${elemento.id_product}`);
      });
    },
  });
}

function inventary_code(item) {
  console.log(item.value);
  let items = item.value;

  var data = new FormData();
  data.append("codigo", items);

  $.ajax({
    data: data,
    url: `${urls}qhse/list_store_articles`,
    type: "post",
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (data) {
      // console.log(data);
      data.forEach(function (elemento) {
        $(`#epp_${cont}`).val(`${elemento.description}`);

        $(`#medida_${cont}`).val(elemento.unit_of_measurement);
      });
    },
  });
}

/* $(document).ready(function() {
  // Apply autocomplete to all inputs with the class 'autocomplete-input'
  $(".autocomplete-input").autocomplete({
      source: function(request, response) {
          $.ajax({
            data: {
              description: request.term
            },
              url: `${urls}qhse/list_store_desc_articles`, // Ruta a tu archivo PHP
              type: "post",
             
              dataType: "json",
              success: function(data) {
                response(data.suggestions);
              }
          });
      },
      minLength:1,
      select: function(event, ui) {
        // Cuando se selecciona un item, se actualiza el código en el input correspondiente
        let code = ui.item.code;
        console.log("codigo",code);
        $('.code-input').val(code);
    }

    
        
  });
}); */

$(document).ready(function () {
  // Asigna el evento 'keyup' a los campos con la clase 'autocomplete-input'
  $(document).on("keyup", ".autocomplete-input", function () {
      var query = $(this).val();
      var currentInput = $(this); // Guarda la referencia al campo actual
      
      if (query.length > 2) {
          $.ajax({
              url: `${urls}qhse/list_store_desc_articles`,
              method: "POST",
              data: { description: query },
              success: function (data) {
                  // Genera las sugerencias
                  var suggestions = data.map(function(item) {
                      return `<div class="suggestion-item" data-code="${item.code}">${item.description}</div>`;
                  }).join("");
                  
                  // Muestra las sugerencias en el contenedor correspondiente
                  currentInput.siblings(".autocomplete-suggestions").html(suggestions).show();
              },
          });
      } else {
          // Oculta las sugerencias si la longitud es menor a 3 caracteres
          currentInput.siblings(".autocomplete-suggestions").hide();
      }
  });
});

             // Manejar la selección de una sugerencia
             $(document).on("click", ".suggestion-item", function() {
              var selectedDescription = $(this).text();
              var selectedCode = $(this).data("code");
      
              // Encuentra el campo correspondiente al hacer clic en una sugerencia
              var suggestionsContainer = $(this).closest(".autocomplete-suggestions");
              var inputField = suggestionsContainer.siblings(".autocomplete-input");
              var codeField = suggestionsContainer.closest(".form-row").find(".code-input");
      
              // Completa los campos correspondientes
              inputField.val(selectedDescription);
              codeField.val(selectedCode).trigger('blur');
      
              // Oculta las sugerencias
              suggestionsContainer.hide();
          });


$(".form-control").each(function () {
  a($(this));
});

$(document).on("blur", ".form-control", function () {
  a($(this));
});

$(document).on("focus", ".form-control", function () {
  $(this).parent(".form-group").addClass("fill");
});

function a(f) {
  var g = 0;
  try {
    g = f.attr("placeholder").length;
  } catch (d) {
    g = 0;
  }
  if (f.val().length > 0 || g > 0) {
    f.parent(".form-group").addClass("fill");
  } else {
    f.parent(".form-group").removeClass("fill");
  }
}

$(document).ready(function () {
  $("#entrega_equipo").change(function () {
    $(this).val($(this).is(":checked") ? "1" : "0");
  });
});
