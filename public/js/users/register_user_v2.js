/**
 * ARCHIVO MODULO ADMINISTRATOR
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
const asignation = document.getElementById("asignacion");

$(document).ready(() => {
  asignation.style.display = "none";

  $(".js-example-basic-multiple").select2();
  $("#rol_usuario").select2();
  $("#puesto").select2();
  $("#autoriza").select2();
  $("#director").select2();
  $("#area_operative").select2();
  $("#clace_cost").select2();
  $("#depto").select2();
});

//Me creo una funcion para al cambiar el select me llene un campo de texto con ese valor en este caso centro de costo dependiendo el area operativa
$("#tipo_usuario").on("change", function () {
  $("#puesto").empty();

  let tipo_usuario = $("#tipo_usuario").val();
  var puestos = $("#puesto");
  let data = new FormData();
  data.append("tipo_usuario", tipo_usuario);

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: urls + "usuarios/tipo_usuario", //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentTypeasignacion
    dataType: "json",
    success: function (resp) {
      //console.log(resp);
      // Limpiamos el select
      puestos.find("option").remove();
      $("#puesto").append('<option value="">Seleccionar...</option>');
      $.each(resp, function (id, value) {
        $("#puesto").append(
          '<option value="' + value.id + '">' + value.job + "</option>"
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
});

//Me creo una funcion para al cambiar el select me llene un campo de texto con ese valor en este caso centro de costo dependiendo el area operativa
$("#contrato").on("change", function () {
  let contrato = $("#contrato").val();
  // console.log(contrato);
  $('#fecha_contrato').removeClass('col-md-4');
  $("#fecha_contrato").empty();
  if (contrato != 1) {
    $('#fecha_contrato').addClass('col-md-4');
    $("#fecha_contrato").append(`<div >
      <label for="termino_contrato">Termino de Contrato</label>
      <input type="date" id="termino_contrato" name="termino_contrato" class="form-control rounded-0" value="" required>
    </div>`);
    return;
  }

});
function validaNumericos(event) {
  return event.charCode >= 48 && event.charCode <= 57 ? true : false;
}

$("#registrar_usuario").submit(function (event) {
  event.preventDefault();
  $("#btn_registro").prop("disabled", true);
  let timerInterval = Swal.fire({ //se le asigna un nombre al swal
    title: '<i class="fas fa-user-plus" style="margin-right: 10px;"></i>Registrando Usuario!',
    html: 'Espere unos Segundos.',
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });
  var formData = $("#registrar_usuario").serialize();
  $.ajax({
    method: "get",
    data: formData, //datos que se envian a traves de ajax
    url: urls + "usuarios/registrar_usuario", //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    //dataType: "json",
    success: function (response) {
      Swal.close(timerInterval);
      $("#btn_registro").prop("disabled", false);

      if (response != "error") {
        $("#nombre").val("");
        $("#empresa").val("");
        $("#ape_paterno").val("");
        $("#ape_materno").val("");
        $("#correo").val("");
        $("#password").val("");
        $("#num_empleado").val("");
        $("#fecha_ingreso").val("");
        $("#depto").val("");
        $("#grado").val("");
        $("#curp").val("");
        $("#nss").val("");
        $("#select2-depto-container").empty();
        $("#rol_usuario").val("");
        $("#tipo_usuario").val("");
        $("#puesto").val("");
        $("#autorizar").val("");
        $("#autoriza").val("");
        $("#director").val("");
        $("#contrato").val("");
        
        $("#asigna_depto_gerente").val(null).trigger("change");
        $('#fecha_contrato').removeClass('col-md-4');
        $("#fecha_contrato").empty();

        $(".js-example-basic-multiple").val("").change();
        $("#rol_usuario").val("").change();
        $("#puesto").val("").change();
        $("#autoriza").val("").change();
        $("#director").val("").change();
        $("#area_operative").val("").change();
        $("#clace_cost").val("").change();
        $("#depto").val("").change();
        Swal.fire("!El Usuario se a registrado correctamente!", "", "success");
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
    error: function (jqXHR, status, error) {
      $("#btn_registro").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Algo salió Mal! Contactar con el Administrador",
      });
      console.log(
        "Mal Revisa entro en el estatus: " +
        status +
        " error" +
        error +
        " jqXHR" +
        jqXHR
      );
    },
  });
});

//Me creo una funcion para al cambiar el select me llene un campo de texto con ese valor en este caso centro de costo dependiendo el area operativa
const rol = document.querySelector("#rol_usuario");
rol.addEventListener("change", () => {
  let tipo_usuario = document.getElementById("rol_usuario").value;
  (tipo_usuario == 3) ? asignation.style.display = "block" : asignation.style.display = "none";
});



document.querySelector('#usuarios_excel').addEventListener('change', function(e){
  // Obtener el nombre del archivo seleccionado
  var fileName = e.target.files[0].name;
  // Actualizar el label con el nombre del archivo
  e.target.nextElementSibling.innerHTML = fileName;
});


$("#registrar_usuarios_excel").submit(function (e) {
  e.preventDefault();
  $("#btn_registro_excel").prop("disabled", true);
  let data = new FormData($('#registrar_usuarios_excel')[0]);
  console.log(data);
  $.ajax({
    data: data,
    url: `${urls}usuarios/iterar_registrar_usuario`,
    type: "post",
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (response) {
      console.log(response);
      if (response == true) {
        setTimeout(function () {
          tbl_paqueteria.ajax.reload(null, false);
        }, 100);
        $("#btn_registro_excel").prop("disabled", false);
        $("#usuarios_excel").val("");
        Swal.fire("!Los datos del archivo Excel se han Registrado!", "", "success");
      } else if (response != true && response != false) {
        setTimeout(function () {
          tbl_paqueteria.ajax.reload(null, false);
        }, 100);
        $("#btn_registro_excel").prop("disabled", false);
        $("#usuarios_excel").val("");
        Swal.fire(`El Proceso se detubo en la fila ${response} `, "Revisa Excel", "error");
      }
      else {
        $("#btn_registro_excel").prop("disabled", false);
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
  }).fail(function (jqXHR, textStatus, errorThrown) {
    if (jqXHR.status === 0) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Fallo de conexión: ​​Verifique la red.",
      });
      $("#btn_registro_excel").prop("disabled", false);
    } else if (jqXHR.status == 404) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No se encontró la página solicitada [404]",
      });
      $("#btn_registro_excel").prop("disabled", false);
    } else if (jqXHR.status == 500) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Internal Server Error [500]",
      });
      $("#btn_registro_excel").prop("disabled", false);
    } else if (textStatus === "parsererror") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Error de análisis JSON solicitado.",
      });
      $("#btn_registro_excel").prop("disabled", false);
    } else if (textStatus === "timeout") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Time out error.",
      });
      $("#btn_registro_excel").prop("disabled", false);
    } else if (textStatus === "abort") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ajax request aborted.",
      });

      $("#btn_registro_excel").prop("disabled", false);
    } else {
      alert("Uncaught Error: " + jqXHR.responseText);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: `Uncaught Error: ${jqXHR.responseText}`,
      });
      $("#btn_registro_excel").prop("disabled", false);
    }
  });
});



