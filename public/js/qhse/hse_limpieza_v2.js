/**
 * ARCHIVO MODULO QHSE
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
$(document).ready(function () {
  let data_menu = new FormData();
  data_menu.append("id_menu", 2);

  $.ajax({
    url: `${urls}qhse/ver_listado_menus`, // Cambia esto por la URL real si es necesario
    method: "POST",
    data: data_menu,
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentTypeasignacion
    dataType: "json",
    success: function (resp) {
      // Recorremos el array de actividades
      $.each(resp, function (id, item) {
        console.log("menus: ", item.menus);

        // Crear un checkbox con su input hidden de fecha
        var checkbox = `
         <div class="form-group">
           <label>
             <input type="checkbox" class="opt-check" name="actividad[]" value="${item.menus}" data-fecha="${item.event_date}">
             ${item.menus}
           </label>
         </div>
       `;

        // Añadir el checkbox al contenedor
        $("#actividades-container").append(checkbox);
      });
    },
    error: function () {
      // Manejo de errores si la llamada AJAX falla
      alert("Hubo un problema al cargar las actividades.");
    },
  });
});

function validarUsuario() {
  if ($("#epp_num_nomina").val().length > 0) {
    $("#error_num_nomina").text("");
    $("#epp_num_nomina").removeClass("has-error");
  }
}

let contadorPersonas = 0;

document.getElementById("add-persona").addEventListener("click", function () {
  if (contadorPersonas < 5) {
    contadorPersonas++;

    const container = document.getElementById("personas-container");

    const row = document.createElement("div");
    row.classList.add("form-row", "mb-3", "animate-show");

    row.innerHTML = `
      <div class="form-group col-md-6">
        <label for="persona_nombre_${contadorPersonas}">Nombre</label>
        <input type="text" name="personas[]" id="persona_nombre_${contadorPersonas}" class="form-control" required>
      </div>
      <div class="form-group col-md-4">
        <label for="persona_talla_${contadorPersonas}">Talla</label>
        <input type="text" name="tallas[]" id="persona_talla_${contadorPersonas}" class="form-control" required>
      </div>
      <div class="form-group col-md-2 d-flex align-items-end">
        <button type="button" class="btn btn-danger btn-block remove-persona">
          <i class="fas fa-trash-alt"></i>
        </button>
      </div>
    `;

    container.appendChild(row);

    // Eliminar persona y disminuir el contador
    row.querySelector(".remove-persona").addEventListener("click", function () {
      row.remove();
      contadorPersonas--;
    });
  }
});

$("#solicitud_eventos").submit(function (e) {
  e.preventDefault();

  var tel_contacto = document.getElementById("tel_contacto").value.trim();
  var motivo = document.getElementById("motivo").value.trim();
  // var epp_depto = document.getElementById("epp_depto").value.trim();

  var error_contacto = tel_contacto.length === 0 ? "El campo es requerido" : "";

  document.getElementById("error_contacto").textContent = error_contacto;
  document
    .getElementById("tel_contacto")
    .classList.toggle("has-error", tel_contacto.length === 0);

  var error_motivo = motivo.length === 0 ? "El campo es requerido" : "";

  document.getElementById("error_motivo").textContent = error_motivo;
  document
    .getElementById("motivo")
    .classList.toggle("has-error", motivo.length === 0);

  let opt = $(".opt-check").val();

  console.log(opt);

  var selectedValues = [];
  $("input.opt-check:checked").each(function () {
    selectedValues.push($(this).val());
  });
  console.log(selectedValues);
  if (
    error_contacto != "" ||
    error_motivo != "" ||
    selectedValues.length == 0
  ) {
    console.log("estoy aqui");
    return false;
  }

  // Para cada checkbox marcado
  $("input.opt-check:checked").each(function () {
    //const actividad = $(this).val();
    const fecha = $(this).data("fecha"); // Asegúrate que el input tiene un data-fecha="..."

    // Agrega solo la fecha correspondiente
    $("<input>")
      .attr({
        type: "hidden",
        name: "fechas_actividad[]",
        value: fecha,
      })
      .appendTo("#actividades-container");
  });

  var dataString = $("#solicitud_eventos").serialize();
  //alert('Datos serializados: '+dataString);
  Swal.fire({
    title: "Registrando Solicitud...",
    allowOutsideClick: false,
    showConfirmButton: false, // Esto oculta el botón "OK"
    willOpen: () => {
      Swal.showLoading();
    },
  });
  $.ajax({
    url: `${urls}qhse/solicitud_evento`,
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
            title: "¡Solicitud Generado Exitosamente!",
            html: ``,
          });
        } else {
          Swal.fire({
            icon: "success",
            title: "¡Solicitud Generado Exitosamente!",
            html: ``,
          });
        }

        // Asegúrate de que el campo #especificar exista
        if ($("#especificar").length) {
          $("#especificar").val("");
        }

        $("#tel_contacto").val("");
        $("#motivo").val("");

        // Limpia todos los radios
        $('input[type="radio"]').prop("checked", false);

        // Restablece el valor predeterminado
        $('input[type="radio"][data-default="true"]').prop("checked", true);

        $('input[type="checkbox"]').prop("checked", false);

        $("#solicitud_eventos")[0].reset();
        // Limpiar inputs dinámicos (como los hidden de fechas)
        $("input[name='fechas_actividad[]']").remove();
        $("#personas-container").empty();
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
