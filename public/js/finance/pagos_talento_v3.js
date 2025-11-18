$(document).ready(function () {
  $(".form-control").each(function () {
    actualizarEstado($(this));
  });

  $(document).on("blur", ".form-control", function () {
    actualizarEstado($(this));
  });

  $(document).on("focus", ".form-control", function () {
    $(this).parent(".form-group").addClass("fill");
  });

  $(document).on("change", ".form-control", function () {
    actualizarEstado($(this)); // Para detectar cambios en el select
  });

  function actualizarEstado(f) {
    if (!f || f.length === 0) return; // Evita errores si f no existe

    var g = (f.attr("placeholder") || "").length; // Evita error si placeholder no existe

    if (f.is("select")) {
      // Si el select tiene un valor válido, agregar la clase "fill"
      if (f.val()) {
        f.parent(".form-group").addClass("fill");
      } else {
        f.parent(".form-group").removeClass("fill");
      }
    } else {
      // Para los inputs normales
      if (f.val().length > 0 || g > 0) {
        f.parent(".form-group").addClass("fill");
      } else {
        f.parent(".form-group").removeClass("fill");
      }
    }
  }
});

$(document).ready(function () {
  $("#cantidad").on("input", function () {
    let valor = $(this).val().replace(/,/g, ""); // Eliminar comas antes de convertir
    let letras = numeroALetras(valor);
    $("#cantidad_letra").val(letras);
  });
});

function numeroALetrasANT(num) {
  const unidades = [
    "",
    "UNO",
    "DOS",
    "TRES",
    "CUATRO",
    "CINCO",
    "SEIS",
    "SIETE",
    "OCHO",
    "NUEVE",
  ];
  const especiales = [
    "DIEZ",
    "ONCE",
    "DOCE",
    "TRECE",
    "CATORCE",
    "QUINCE",
    "DIECISÉIS",
    "DIECISIETE",
    "DIECIOCHO",
    "DIECINUEVE",
  ];
  const decenas = [
    "",
    "DIEZ",
    "VEINTE",
    "TREINTA",
    "CUARENTA",
    "CINCUENTA",
    "SESENTA",
    "SETENTA",
    "OCHENTA",
    "NOVENTA",
  ];
  const centenas = [
    "",
    "CIENTO",
    "DOSCIENTOS",
    "TRESCIENTOS",
    "CUATROCIENTOS",
    "QUINIENTOS",
    "SEISCIENTOS",
    "SETECIENTOS",
    "OCHOCIENTOS",
    "NOVECIENTOS",
  ];

  function convertir(n) {
    if (n < 10) return unidades[n];
    else if (n < 20) return especiales[n - 10];
    else if (n < 100)
      return (
        decenas[Math.floor(n / 10)] + (n % 10 ? " Y " + unidades[n % 10] : "")
      );
    else if (n < 1000)
      return (
        centenas[Math.floor(n / 100)] +
        (n % 100 ? " " + convertir(n % 100) : "")
      );
    else if (n < 1000000)
      return (
        convertir(Math.floor(n / 1000)) +
        " MIL " +
        (n % 1000 ? convertir(n % 1000) : "")
      );
    else return "Número demasiado grande";
  }

  num = parseFloat(num);
  if (isNaN(num) || num < 0) return "Número inválido";

  let parteEntera = Math.floor(num);
  let parteDecimal = Math.round((num - parteEntera) * 100);

  let texto = convertir(parteEntera);
  if (parteDecimal > 0) {
    texto += " PESOS CON " + convertir(parteDecimal) + " CENTAVOS";
  } else {
    texto += " PESOS";
  }

  return texto.charAt(0).toUpperCase() + texto.slice(1); // Primera letra mayúscula
}

function numeroALetras(num) {
  const unidades = [
    "",
    "UNO",
    "DOS",
    "TRES",
    "CUATRO",
    "CINCO",
    "SEIS",
    "SIETE",
    "OCHO",
    "NUEVE",
  ];
  const especiales = [
    "DIEZ",
    "ONCE",
    "DOCE",
    "TRECE",
    "CATORCE",
    "QUINCE",
    "DIECISÉIS",
    "DIECISIETE",
    "DIECIOCHO",
    "DIECINUEVE",
  ];
  const decenas = [
    "",
    "DIEZ",
    "VEINTE",
    "TREINTA",
    "CUARENTA",
    "CINCUENTA",
    "SESENTA",
    "SETENTA",
    "OCHENTA",
    "NOVENTA",
  ];
  const centenas = [
    "",
    "CIENTO",
    "DOSCIENTOS",
    "TRESCIENTOS",
    "CUATROCIENTOS",
    "QUINIENTOS",
    "SEISCIENTOS",
    "SETECIENTOS",
    "OCHOCIENTOS",
    "NOVECIENTOS",
  ];

  function convertir(n) {
    if (n === 0) return "CERO";
    if (n === 100) return "CIEN";
    if (n < 10) return unidades[n];
    if (n < 20) return especiales[n - 10];
    if (n < 100)
      return (
        decenas[Math.floor(n / 10)] + (n % 10 ? " Y " + unidades[n % 10] : "")
      );
    if (n < 1000)
      return (
        centenas[Math.floor(n / 100)] +
        (n % 100 ? " " + convertir(n % 100) : "")
      );
    if (n < 1000000) {
      const miles = Math.floor(n / 1000);
      const resto = n % 1000;
      let milesTexto = miles === 1 ? "MIL" : convertir(miles) + " MIL";
      return milesTexto + (resto ? " " + convertir(resto) : "");
    }
    if (n < 1000000000) {
      const millones = Math.floor(n / 1000000);
      const resto = n % 1000000;
      let millonesTexto =
        millones === 1 ? "UN MILLÓN" : convertir(millones) + " MILLONES";
      return millonesTexto + (resto ? " " + convertir(resto) : "");
    }
    return "NÚMERO DEMASIADO GRANDE";
  }

  num = parseFloat(num);
  if (isNaN(num) || num < 0) return "Número inválido";

  const parteEntera = Math.floor(num);
  const parteDecimal = Math.round((num - parteEntera) * 100);

  let texto = convertir(parteEntera).trim();
  texto += " PESOS";

  if (parteDecimal > 0) {
    texto += " CON " + convertir(parteDecimal).trim() + " CENTAVOS";
  }

  return texto.charAt(0).toUpperCase() + texto.slice(1).toLowerCase();
}

$("#uploadForm").submit(function (e) {
  e.preventDefault();

  // Obtener el valor del radio seleccionado
  var tipo_pago = $("input[name='tipo_pago']:checked").val();
  var nombre_empresa = document.getElementById("nombre_empresa").value.trim();
  var empresas = document.getElementById("empresas").value.trim();
  var banco = document.getElementById("banco").value.trim();
  // var cuenta = document.getElementById("cuenta").value.trim();
  // var clabe = document.getElementById("clabe").value.trim();
  var concepto = document.getElementById("concepto").value.trim();
  var cantidad = document.getElementById("cantidad").value.trim();
  var cantidad_letra = document.getElementById("cantidad_letra").value.trim();

  var error_empresa =
    nombre_empresa.length === 0 ? "El campo es requerido" : "";
  document.getElementById("error_empresa").textContent = error_empresa;

    var error_empresas =
    empresas.length === 0 ? "El campo es requerido" : "";
  document.getElementById("error_empresas").textContent = error_empresas;
console.log("empresas: ",empresas);

  var error_banco = banco.length === 0 ? "El campo es requerido" : "";
  document.getElementById("error_banco").textContent = error_banco;

  /*   var error_cuenta = cuenta.length === 0 ? "El campo es requerido" : "";
  document.getElementById("error_cuenta").textContent = error_cuenta;

  var error_clabe = clabe.length === 0 ? "El campo es requerido" : "";
  document.getElementById("error_clabe").textContent = error_clabe; */

  var error_cantidad = cantidad.length === 0 ? "El campo es requerido" : "";
  document.getElementById("error_cantidad").textContent = error_cantidad;

  var error_letra = cantidad_letra.length === 0 ? "El campo es requerido" : "";
  document.getElementById("error_letra").textContent = error_letra;

  var error_concepto = concepto.length === 0 ? "El campo es requerido" : "";
  document.getElementById("error_concepto").textContent = error_concepto;

  var error_tipo = tipo_pago ? "" : "El campo es requerido";

  var errorElement = (document.getElementById("error_tipo").textContent =
    error_tipo);

  let fileInputOrden = document.getElementById("orden_compra");

  if (fileInputOrden.files.length === 0) {
    //document.getElementById("error_oc").textContent = "El campo es requerido";
    //return;
  } else {
    document.getElementById("error_oc").textContent = "";
  }

  let fileInputFac = document.getElementById("factura");

  if (fileInputFac.files.length === 0) {
    //document.getElementById("error_factura").textContent = "El campo es requerido";
    //return;
  } else {
    document.getElementById("error_factura").textContent = "";
  }

  let fileInputCaratula = document.getElementById("caratula");

  if (fileInputCaratula.files.length === 0) {
    //document.getElementById("error_caratula").textContent = "El campo es requerido";
    //return;
  } else {
    document.getElementById("error_caratula").textContent = "";
  }



  if (
     error_empresas ||
    error_empresa ||
    error_banco ||
    error_cantidad ||
    error_letra ||
    error_concepto ||
    errorElement
  ) {
    console.log("Error en la validación");
    return false;
  }

  // ✅ Crear FormData para enviar archivos y demás datos
  var formData = new FormData(document.getElementById("uploadForm"));

  Swal.fire({
    title: "Generando Solicitud...",
    allowOutsideClick: false,
    showConfirmButton: false,
    willOpen: () => {
      Swal.showLoading();
    },
  });

  $.ajax({
    url: `${urls}finanzas/guardar_solicitud_pago`,
    type: "POST",
    dataType: "json",
    data: formData, // ✅ Enviar FormData en lugar de serialize()
    contentType: false, // ✅ Necesario para enviar archivos
    processData: false, // ✅ Necesario para evitar que jQuery procese FormData
    success: function (response) {
      Swal.close();
      if (response.status === "success") {
        Swal.fire({
          icon: "success",
          title: "¡Solicitud generada con éxito!",
          html: `<p style="font-size: 25px;"><b></b></p>`,
        });

        // ✅ Quitar la clase "fill" después de la respuesta
        $(".form-group").removeClass("fill");

        $("#uploadForm")[0].reset(); // ✅ Limpiar el formulario
        tbl_request.ajax.reload(null, false);
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió mal. Contactar con el Administrador",
        });
      }
    },
    error: function () {
      Swal.close();
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Hubo un problema en la solicitud",
      });
    },
  });
});

function solicitudTalentoModal() {
  var miModal = new bootstrap.Modal(
    document.getElementById("solicitudTalentoModal")
  );
  miModal.show();
}

$(document).ready(function () {
  tbl_request = $("#tbl_pago_talento")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}finanzas/solicitudes_talento`,
        dataSrc: "data",
      },
      lengthChange: true,
      ordering: true,
      responsive: true,
      autoWidth: false,
      rowId: "staffId",
      dom: "lBfrtip",
      buttons: [
        {
          extend: "excelHtml5",
          title: "Solicitudes de Pago",
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5],
          },
        },
        /* {
                 extend:'pdfHtml5',
                 title:'Listado de Proveedores',
                 exportOptions:{
                   columns:[1,2,3,4,5,6,7]
                 }
               } */
      ],
      language: {
        url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
      },
      columns: [
        {
          data: "id_request",
          title: "Folio",
          className: "text-center",
        },
        {
          data: "tipo_pago",
          title: "Pago",
          className: "text-center",
        },
        {
          data: "user_name",
          title: "Usuario",
          className: "text-center fuente-chica",
        },

        {
          data: "nombre_empresa",
          title: "Empresa",
          className: "text-center",
        },
        {
          data: "banco",
          title: "Banco",
          className: "text-center",
        },

        {
          data: "cantidad",
          title: "Cantidad",
          className: "text-center",
          render: function (data, type, row) {
            // Agregar comas a la cantidad
            if (type === "display" || type === "filter") {
              return (
                "$" +
                parseFloat(data).toLocaleString(undefined, {
                  minimumFractionDigits: 2,
                  maximumFractionDigits: 2,
                })
              );
            }
            return data;
          },
        },

        {
          data: null,
          render: function (data, type, full, meta) {
            if (data["request_status"] != "") {
              switch (data["request_status"]) {
                case "1":
                  return `<span class="badge">Pendiente</span>`;
                  break;
                case "2":
                  return `<span class="badge">Aprobada</span>`;
                  break;
                case "3":
                  return `<span class="badge">Autorizada</span>`;
                  break;
                case "4":
                  return `<span class="badge">Pagada</span>`;
                  break;
                case "5":
                  return `<span class="badge">Rechazada</span>`;
                  break;

                default:
                  return `<span class="badge badge">Error</span>`;
                  break;
              }
            } else {
              return "----";
            }
          },
          title: "Estatus",
          className: "text-center",
        },

        {
          data: null,
          title: "Acciones",
          className: "text-center",
          render: function (data) {
            return `
                 <div class="pull-right mr-auto">     
              <button 
                  type="button" 
                  class="btn btn-outline-danger btn-sm" 
                  data-toggle="tooltip" 
                  data-placement="top" 
                  title="Solicitud de Pago" 
                  onclick="openPdfModal('${urls}${data["ruta_pdf"]}',${data["id_request"]},${data["request_status"]},${data["id_user"]})"
                  >
                  <i class="fas fa-file-pdf"></i>
                </button>
                 <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Descarga de Archivos" onclick="downloadZip(${data["id_request"]} )">
                  <i class="fas fa-download"></i> 
                </button>
             
                      <button class="btn btn-sm btn-outline-danger" title="Eliminar Solicitud" 
                          onclick="deleteChange(${data.id_request})">
                          <i class="fas fa-power-off"></i>
                      </button>
                      
                      </div>`;
          },
        },
      ],
      destroy: "true",
      columnDefs: [
        /* {
               targets: [0],
               visible: false,
               searchable: false,
             },  */
      ],

      order: [[0, "DESC"]],

      createdRow: (row, data) => {
        $(row).attr("id", "solicitud_" + data.id_request);

        // Pinta la celda de estatus con colores según el estado
        let statusCell = $(row).find("td:eq(6)"); // Índice de la columna "Estatus"
        switch (data.request_status) {
          case "1":
            statusCell.css("background-color", "#ffcc00");
            break; // Amarillo
          case "2":
            statusCell.css("background-color", "#99ff99");
            break; // Verde
          case "3":
            statusCell.css("background-color", "#99ccff");
            break; // Rojo
          case "4":
            statusCell.css("background-color", "#00bc8c");
            break; // Rojo fuerte
          case "5":
            statusCell.css("background-color", "#ff6666");
            break; // Rojo fuerte
        }
      },
    })
    .DataTable();
  $("#tbl_pago_talento thead").addClass("text-center");
});

function deleteChange(id_request) {
  Swal.fire({
    title: `Deseas Eliminar la Solicitud con Folio: ${id_request} ?`,
    text: `Una vez Eliminado no podras Recuperarlo!`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Eliminar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.value) {
      let dataForm = new FormData();
      dataForm.append("id_request", id_request);

      $.ajax({
        data: dataForm, //datos que se envian a traves de ajax
        url: `${urls}finanzas/eliminar_solicitud_talento`, //archivo que recibe la peticion
        type: "post", //método de envio
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentType
        success: function (response) {
          //una vez que el archivo recibe el request lo procesa y lo devuelve
          console.log(response);

          /*codigo que borra todos los campos del form newProvider*/
          if (response) {
            tbl_request.ajax.reload(null, false);
          } else {
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
          $("#guardar_ticket").prop("disabled", false);
        } else if (jqXHR.status == 404) {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "No se encontró la página solicitada [404]",
          });
          $("#guardar_ticket").prop("disabled", false);
        } else if (jqXHR.status == 500) {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Internal Server Error [500]",
          });
          $("#guardar_ticket").prop("disabled", false);
        } else if (textStatus === "parsererror") {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Error de análisis JSON solicitado.",
          });
          $("#guardar_ticket").prop("disabled", false);
        } else if (textStatus === "timeout") {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Time out error.",
          });
          $("#guardar_ticket").prop("disabled", false);
        } else if (textStatus === "abort") {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Ajax request aborted.",
          });

          $("#guardar_ticket").prop("disabled", false);
        } else {
          alert("Uncaught Error: " + jqXHR.responseText);
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: `Uncaught Error: ${jqXHR.responseText}`,
          });
          $("#guardar_ticket").prop("disabled", false);
        }
      });
    }
  });
}

function openPdfModal(ruta, id_request, status, id_user) {
  $("#progressContainer").hide();
  $("#successMessage").hide();
  console.log("sstatus: ", status);

  // Asegurar que el botón se habilite si el status es 1 && parseInt(id_user) !== 1
  if (parseInt(status) === 3) {
    $("#signBtn").prop("disabled", true); // Deshabilitar en otros casos
    $("#btn-signed").prop("disabled", true); // Deshabilitar en otros casos
  } else {
    $("#signBtn").prop("disabled", false); // Habilitar si es 1
    $("#btn-signed").prop("disabled", false); // Habilitar si es 1
  }

  document.getElementById("id_solicitud").value = id_request;
  // modalBody.innerHTML = `<iframe src="${ruta}" width="100%" height="700px"></iframe>`;
  var pdfContainer = $("#pdfContainer"); // Contenedor del <object>

  // Eliminar el <object> actual
  $("#pdfObject").remove();

  // Crear un nuevo <object> con el nuevo PDF
  var nuevoObject = `<object id="pdfObject" data="${ruta}" type="application/pdf" width="100%" height="600px"></object>`;

  // Agregarlo nuevamente al contenedor
  pdfContainer.html(nuevoObject);
  // document.getElementById("pdfObject").setAttribute("data", ruta);
  $("#aprobarSolicitudModal").modal("show");
}

$("#signBtn").click(function () {
  var pdfUrl = $("#pdfObject").attr("data");
  var id = $("#id_solicitud").val();

  var dataFirma = new FormData();
  dataFirma.append("pdfPath", pdfUrl);
  dataFirma.append("id_request", id);

  console.log("pdf:", pdfUrl);

  $.ajax({
    url: `${urls}finanzas/firmar_talento_pdf`, // Ruta del controlador en CodeIgniter 4
    type: "POST",
    data: dataFirma,
    contentType: false, // Evita que jQuery establezca un tipo de contenido incorrecto
    processData: false, // Evita que jQuery intente procesar FormData
    dataType: "json", // Asegura que la respuesta se maneje como JSON
    success: function (response) {
      var signedPdfUrl = response.signedPdfUrl;
      $("#pdfObject").attr("data", signedPdfUrl); // Mostrar el PDF firmado
      $("#signBtn").prop("disabled", true); // Deshabilitar en otros casos
      tbl_request.ajax.reload(null, false);
    },
    error: function () {
      alert("Hubo un error al firmar el archivo");
    },
  });
});

$("#btn-signed").click(function () {
  var pdfUrl = $("#pdfObject").attr("data");
  var id = $("#id_solicitud").val();

  var dataFirma = new FormData();
  dataFirma.append("pdfPath", pdfUrl);
  dataFirma.append("id_request", id);

  // Inicializar la barra de progreso
  $("#progressBar").css("width", "0%");
  $("#progressBar").attr("aria-valuenow", 0);
  // El modal ya está abierto, no es necesario abrirlo aquí
  $("#progressContainer").show(); // Mostrar la barra de progreso

  // Simular progresión mientras se hace la solicitud AJAX
  var progress = 0;

  var interval = setInterval(function () {
    if (progress < 90) {
      progress += 10;
      $("#progressBar").css("width", progress + "%");
      $("#progressBar").attr("aria-valuenow", progress);
    }
  }, 500);

  $.ajax({
    url: `${urls}finanzas/autorizar_solicitud_talento_pdf`, // Ruta del controlador en CodeIgniter 4
    type: "POST",
    data: dataFirma,
    contentType: false, // Evita que jQuery establezca un tipo de contenido incorrecto
    processData: false, // Evita que jQuery intente procesar FormData
    dataType: "json", // Asegura que la respuesta se maneje como JSON
    success: function (response) {
      // Detener la animación de la barra de progreso
      clearInterval(interval);

      // Completar la barra de progreso
      $("#progressBar").css("width", "100%");
      $("#progressBar").attr("aria-valuenow", 100);

      // Ocultar la barra de progreso
      $("#progressContainer").hide();
      $("#progressBar").empty();
      tbl_request.ajax.reload(null, false);
      /*    // Mostrar el mensaje de éxito
      $("#successMessage").show();
      $("#successText").text(response.message);
 */

      if (response.status) {
        // Si la respuesta tiene status true, muestra un mensaje de éxito
        //alert(response.message);  // Muestra el mensaje que devuelve el servidor
        $("#successMessage").show();
        $("#successText").text(response.message);
      } else {
        // Si la respuesta tiene status false, muestra un mensaje de error
        $("#successMessage").show();
        $("#successText").text(response.message);
        //alert(response.message);  // Muestra el mensaje de error que devuelve el servidor
      }
    },
    error: function () {
      // Completar la barra de progreso
      $("#progressBar").css("width", "100%");
      $("#progressBar").attr("aria-valuenow", 100);
      tbl_request.ajax.reload(null, false);
      // Ocultar la barra de progreso
      $("#progressContainer").hide();
      // Ocultar la barra de progreso
      $("#progressContainer").empty();

      /*   // Mostrar el mensaje de éxito
      $("#successMessage").show();
      $("#successText").text(response.message); */

      alert("Hubo un error al firmar el archivo");
    },
  });
});

function downloadZip(id_request) {
  let timerInterval = Swal.fire({
    //se le asigna un nombre al swal
    title: `<i class="fas fa-file-download" style="margin-right: 10px;"></i>¡Descargando Archivos!`,
    html: "Espere unos Segundos.",
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading(); //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });

  // Hacer la solicitud AJAX
  $.ajax({
    url: `${urls}finanzas/descargar_solicitudes_talento/${id_request}`,
    method: "GET",
    xhrFields: {
      responseType: "blob", // Indicar que esperamos un archivo binario
    },
    success: function (data) {
      // Crear un enlace temporal para descargar el archivo
      const link = document.createElement("a");
      link.href = window.URL.createObjectURL(data);
      link.download = "solicitud_pago_" + id_request + ".zip";
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      Swal.close(timerInterval);
    },
    error: function (xhr, status, error) {
      alert("Error al descargar la carpeta: " + xhr.responseText);
    },
  });
}

// Deshabilitar el pegado en los campos de texto
document.querySelectorAll(".escribe").forEach((input) => {
  input.addEventListener("paste", (e) => e.preventDefault());
});
