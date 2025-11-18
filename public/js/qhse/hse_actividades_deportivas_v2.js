/**
 * ARCHIVO MODULO QHSE
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
$(document).ready(function () {
  let data_menu = new FormData();
  data_menu.append("id_menu", 3);

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

  // Validaciones previas...
  var tel_contacto = $("#tel_contacto").val().trim();
  var motivo = $("#motivo").val().trim();

  let error_contacto = tel_contacto === "" ? "El campo es requerido" : "";
  let error_motivo = motivo === "" ? "El campo es requerido" : "";

  $("#error_contacto").text(error_contacto);
  $("#tel_contacto").toggleClass("has-error", tel_contacto === "");

  $("#error_motivo").text(error_motivo);
  $("#motivo").toggleClass("has-error", motivo === "");

  let selectedValues = $("input.opt-check:checked");

  if (error_contacto || error_motivo || selectedValues.length === 0) {
    if (selectedValues.length === 0) {
      Swal.fire({
        icon: "warning",
        title: "Selecciona al menos una actividad",
      });
    }
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

 /*  // Construir objeto de datos estructurado
  let formData = {
    tipo_evento: $("#tipo_evento").val(),
    num_nomina: $("#num_nomina").val(),
    usuario: $("#usuario").val(),
    departamento: $("#departamento").val(),
    puesto: $("#puesto").val(),
    tel_contacto: tel_contacto,
    motivo: motivo,
    actividad: [],
    fechas_actividad: []
  };

  // Recorrer checkboxes seleccionados y emparejar con sus fechas
  selectedValues.each(function() {
    let grupo = $(this).closest(".form-group");
    formData.actividad.push($(this).val());
    formData.fechas_actividad.push(grupo.find("input[type='hidden'].original").val());
  }); */

  Swal.fire({
    title: "Registrando Solicitud...",
    allowOutsideClick: false,
    showConfirmButton: false,
    willOpen: () => Swal.showLoading(),
  });

  // Enviar como FormData tradicional (compatible con tu backend actual)
  $.ajax({
    url: `${urls}qhse/solicitud_evento`,
    type: "POST",
    dataType: "json",
    data: dataString, // Enviamos el objeto directamente (jQuery lo convierte a FormData)
    success: function (response) {
      Swal.close();
      if (response && response.success) {
        Swal.fire({
          icon: "success",
          title: "¡Solicitud Generada Exitosamente!",
        }).then(() => {
          // Limpiar formulario
          $("#solicitud_eventos")[0].reset();
          // Restaurar checkboxes
          $("input.opt-check").prop('checked', false);

              // Limpia todos los radios
        $('input[type="radio"]').prop("checked", false);

        // Restablece el valor predeterminado
        $('input[type="radio"][data-default="true"]').prop("checked", true);

        $('input[type="checkbox"]').prop("checked", false);

        $("#solicitud_eventos")[0].reset();
        // Limpiar inputs dinámicos (como los hidden de fechas)
        $("input[name='fechas_actividad[]']").remove();
        $("#personas-container").empty();


        });
      } else {
        let errorMsg = response && response.error ? response.error : "Algo salió mal. Contacta al administrador.";
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: errorMsg,
        });
      }
    },
    error: function(xhr, status, error) {
      Swal.close();
      Swal.fire({
        icon: "error",
        title: "Error de conexión",
        text: "No se pudo conectar con el servidor. Intenta nuevamente.",
      });
    }
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
