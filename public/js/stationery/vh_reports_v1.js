/**
 * ARCHIVO MODULO PAPELERIA
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
var error_categoria = "0";
var error_fecha_inicial = "0";
var error_fecha_final = "0";
var error_opcion1 = "0";


function validar() {
  if ($("#categoria").val().length > 0) {
    error_categoria = "";
    $("#error_categoria").text(error_categoria);
    $("#categoria").removeClass('has-error');
  }
  if ($("#fecha_inicial").val().length > 0) {
    error_fecha_inicial = "";
    $("#error_fecha_inicial").text(error_fecha_inicial);
    $("#fecha_inicial").removeClass('has-error');
  }
  if ($("#fecha_final").val().length > 0) {
    error_fecha_final = "";
    $("#error_fecha_final").text(error_fecha_final);
    $("#fecha_final").removeClass('has-error');
  }
  if ($("#categoria").val() == 2 && $("#num_nomina").val() != null) {
    error_opcion1 = "";
    $("#error_opcion").text(error_opcion1);
    $("#num_nomina").removeClass('has-error');
  }else if ($("#categoria").val() == 1 && $("#depto").val() != null) {
    error_opcion1 = "";
    $("#error_opcion").text(error_opcion1);
    $("#depto").removeClass('has-error');
  }

    $("#formReportes").on
      ("change", `#num_nomina`, function () {
        let nomina = $(this).val();
        let data = new FormData();
      data.append("payroll_number", nomina);
        $.ajax({
          data: data,
          type: "post",
          url: urls + "papeleria/nominas",
          processData: false,
          contentType: false,
          dataType: "json",
          success: function (resp) {
          //  console.log(resp);
            if (resp=="encontrado") {
              error_funcion = "";
              $(`#error_cantidad_salida`).text("");
              $(`#cantidad_salida`).removeClass("has-error");
            }
            else {
              error_opcion1 = "Numero de Nomina Inexisente";
              $("#error_opcion").text(error_opcion1);
              $("#num_nomina").addClass('has-error');
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

$("#formReportes").on("submit", function (e) {
  e.preventDefault();

  $("#generar_reporte").prop("disabled", true);
  if ($("#categoria").val().length == 0
    && $("#fecha_inicial").val().length == 0
    && $("#fecha_final").val().length == 0) {
    Swal.fire({
      icon: "error",
      title: "!ERROR¡",
      text: "Llena el formulario",
    });
    $("#generar_reporte").prop("disabled", false);
  } else {
    if ($("#categoria").val().length == 0) {
      error_categoria = "Categoria Requerida";
      $("#error_categoria").text(error_categoria);
      $("#categoria").addClass('has-error');
    }
    if ($("#fecha_inicial").val().length == 0) {
      error_fecha_inicial = "Fecha Inicial Requerida";
      $("#error_fecha_inicial").text(error_fecha_inicial);
      $("#fecha_inicial").addClass('has-error');
    }
    if ($("#fecha_final").val().length == 0) {
      error_fecha_final = "Fecha Final Requerida";
      $("#error_fecha_final").text(error_fecha_final);
      $("#fecha_final").addClass('has-error');
    }
    if ($("#categoria").val() == 2 && $("#num_nomina").val().length == 0) {
      error_opcion1 = "Numero de Nomina Requerida";
      $("#error_opcion").text(error_opcion1);
      $("#num_nomina").addClass('has-error');
    } else if ($("#categoria").val() == 1 && $("#depto").val().length == 0) {
      error_opcion1 = "Departamento Requerida";
      $("#error_opcion").text(error_opcion1);
      $("#depto").addClass('has-error');
    }
  }
  if (
    error_categoria != "" ||
    error_fecha_inicial != "" ||
    error_fecha_final != "" ||
    error_opcion1 != ""
  ) {

    $("#generar_reporte").prop("disabled", false);
    return false;
  }

  let fecha_inicio = $("#fecha_inicial").val();
  let fecha_fin = $("#fecha_final").val();

  let categoria = $("#categoria").val();
  let parametro = 0;
  categoria == 1
    ? (parametro = $("#depto").val())
    : (parametro = $("#num_nomina").val());


  if (fecha_inicio < fecha_fin) {
    let reportes = categoria == 1 ? "departamento" : "usuario";

    var nomArchivo = `solicitudes_${reportes}.xlsx`;
    var param = JSON.stringify({
      fecha_inicio: fecha_inicio,
      fecha_fin: fecha_fin,
      categoria: categoria,
      parametro: parametro,
    });
    var pathservicehost = `${urls}/papeleria/genera_reportes`;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", pathservicehost, true);
    xhr.responseType = "blob";

    //Send the proper header information along with the request
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function (e) {
      if (xhr.readyState === 4 && xhr.status === 200) {
        var contenidoEnBlob = xhr.response;
        var link = document.createElement("a");
        link.href = (window.URL || window.webkitURL).createObjectURL(
          contenidoEnBlob
        );
        link.download = nomArchivo;
        var clicEvent = new MouseEvent("click", {
          view: window,
          bubbles: true,
          cancelable: true,
        });
        //Simulamos un clic del usuario
        //no es necesario agregar el link al DOM.
        link.dispatchEvent(clicEvent);
        $("#generar_reporte").prop("disabled", false);
        //link.click();
      } else {
        alert(" No es posible acceder al archivo, probablemente no existe.");
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "No es posible acceder al archivo, probablemente no existe.",
        });
      }
    };
    xhr.send("data=" + param);
  } else {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Algo salió Mal! La Fecha Final es Anterior a la Fecha Inicial",
    });
    $("#generar_reporte").prop("disabled", false);
  }
});

$("#categoria").on("change", () => {
  let categoria = $("#categoria").val();
  if (categoria == 2) {
    $("#parametro").empty();
    $("#parametro").addClass("col-md-3");
    campo = ` <label for="descripcion">Numero de Nomina</label>
                <input type="number" class="form-control rounded-0" id="num_nomina" name="num_nomina" value="" onchange="validar()" >
                <div id="error_opcion" name="error_opcion" class="text-danger"></div>`;
    $("#parametro").append(campo);
  } else if (categoria == 1) {
    $("#parametro").empty();

    $("#parametro").addClass("col-md-3");
    campo = `<label for="ingenieria">Departamento:</label>
        <select name="depto" id="depto" class="form-control rounded-0" onchange="validar()"></select>
        <div id="error_opcion" name="error_opcion" class="text-danger"></div>   `;
    $("#parametro").append(campo);
    $.ajax({
      // data: data, //datos que se envian a traves de ajax
      url: `${urls}permisos/departamentos`, //archivo que recibe la peticion
      type: "post", //método de envio
      processData: false, // dile a jQuery que no procese los datos
      contentType: false, // dile a jQuery que no establezca contentType
      dataType: "json",
      success: function (resp) {
        // console.log(resp);
        // Limpiamos el select
        //puestos.find("option").remove();
        $("#depto").append('<option value="">Seleccionar...</option>');
        $.each(resp, function (id, value) {
          $("#depto").append(
            '<option value="' +
            value.cost_center +
            '">' +
            value.departament +
            "</option>"
          );
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
  }
});
