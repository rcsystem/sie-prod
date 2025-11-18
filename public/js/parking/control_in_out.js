/*
 * ARCHIVO MODULO ESTACIONAMIENTO
 * AUTOR:Horus Samael Rivas Pedraza
 * EMAIL:horus.riv.ped@gmail.com
 * CEL: 56 2439 2632
 */

// $(document).ready(function () { });
function resetForm(card) {
  if (card == 1) {
    $(".btn-opcion").removeClass("active focus");
    document.getElementById("form_entradas").reset();
    document.getElementById("div_cards").innerHTML = "";
    document.getElementById("div_usuario").innerHTML = "";
  }
  if (card == 2) {
    $(".btn-opcion-salida").removeClass("active focus");
    document.getElementById("form_salidas").reset();
    document.getElementById("div_cards_salidas").innerHTML = "";
    document.getElementById("div_usuario_salidas").innerHTML = "";
  }
}

function tipoVehiculo(tipo) {
  $("#tipo").val(tipo);
  document.getElementById("tipo").classList.remove("has-error");
  document.getElementById("error_tipo").textContent = "";
  /* if (tipo === 3) {
    document.getElementById("lbl_num_espacio").textContent = "Número de Gancho:";
  } else {
    document.getElementById("lbl_num_espacio").textContent = "Número de Cajón:";
  } */
  datosMarbete();
}

/* $("#num_espacio").on('change', function (e) {
  e.preventDefault()
  document.getElementById("num_espacio").classList.remove("has-error");
  document.getElementById("error_num_espacio").textContent = "";
}) */
// 81079
function datosMarbete() {
  var numMarbete = document.getElementById("num_marbete");
  if (numMarbete.value.length > 0) {
    numMarbete.classList.remove("has-error");
    document.getElementById("error_num_marbete").textContent = "";
  }
  $("#div_cards").empty();
  $("#div_usuario").empty();
  $("#item_vehiculo").val('');

  console.log('marbete:   ', $("#num_marbete").val().length);
  console.log('tipo:   ', $("#tipo").val().length);
  if ($("#num_marbete").val().length > 0 && $("#tipo").val().length > 0) {
    var datosMarberte = new FormData();
    datosMarberte.append('tipo', $("#tipo").val());
    datosMarberte.append('marberte', $("#num_marbete").val());
    $.ajax({
      url: `${urls}estacionamiento/datos_marberte`,
      data: datosMarberte,
      type: "post",
      processData: false,
      contentType: false,
      cache: false,
      dataType: "json",
      success: function (data) {
        console.log(data);
        if (data != null && data != false) {
          $("#div_usuario").append(`<label style="padding-top: 10px;margin-bottom: -10px;">${data[0].name}</label><hr>`);
          var i = 1;
          data.forEach(key => {
            $("#div_cards").append(`
            <label id="lbl_${i}" class="btn btn-outline-secondary btn-opcion" style="width: 95%; margin-top: 1rem;">
            <input type="radio" id="radio_${i}" onclick="vehiculoEntrada(${key.id_item})"> 
            <div class="row">
                <div class="col-sm-4">
                    <h6>Modelo: <b>${key.model}</b></h6>
                </div>
                <div class="col-sm-4">
                    <h6>Placas: <b>${key.placas}</b></h6>
                </div>
                <div class="col-sm-4">
                    <h6>Color: <b>${key.color}</b></h6>
                </div>
            </div>
            </div>
            </label> `);
            i++;
          });
          document.getElementById("radio_1").checked = true;
          document.getElementById("lbl_1").classList.add("focus");
          document.getElementById("lbl_1").classList.add("active");
          $("#item_vehiculo").val(data[0].id_item);
        } else {
          Swal.fire({
            icon: "error",
            title: "Datos no exsistentes",
            text: "Numero de marberte o tipo de vehiculo.",
          });
        }
      }
    });
  }
}

function vehiculoEntrada(id_item) {
  $("#item_vehiculo").val('');
  $("#item_vehiculo").val(id_item);
}

$("#form_entradas").submit(function (e) {
  e.preventDefault();

  var tipo = document.getElementById("tipo");
  var numMarbete = document.getElementById("num_marbete");
  // var numEspacio = document.getElementById("num_espacio");
  var id_item = document.getElementById("item_vehiculo");

  var error_tipo, error_tag, /* error_cajon, */ error_item;

  if (tipo.value.length === 0) {
    error_tipo = 'Opción requerida';
    document.getElementById("error_tipo").textContent = error_tipo;
  } else {
    error_tipo = '';
    document.getElementById("error_tipo").textContent = error_tipo;
  }

  if (numMarbete.value.length === 0) {
    error_tag = 'Opción requerida';
    numMarbete.classList.add("has-error");
    document.getElementById("error_num_marbete").textContent = error_tag;
  } else {
    error_tag = '';
    numMarbete.classList.remove("has-error");
    document.getElementById("error_num_marbete").textContent = error_tag;
  }

  /* if (numEspacio.value.length === 0) {
    error_cajon = 'Opción requerida';
    numEspacio.classList.add("has-error");
    document.getElementById("error_num_espacio").textContent = error_cajon;
  } else {
    error_cajon = '';
    numEspacio.classList.remove("has-error");
    document.getElementById("error_num_espacio").textContent = error_cajon;
  } */
  error_item = (id_item.value.length == 0) ? 'item no cargado' : '';

  if (error_tipo != "" || error_tag != "" /* || error_cajon != "" */ || error_item != "") { return false }

  $("#btn_entradas").prop('disabled', true);
  let timerInterval = Swal.fire({ //se le asigna un nombre al swal
    allowOutsideClick: false,
    title: '¡Guardando Registro!',
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });

  const datas = new FormData($("#form_entradas")[0]);
  $.ajax({
    data: datas,
    url: `${urls}estacionamiento/registar_entrada_salida/1`,
    type: "post",
    dataType: "json",
    processData: false,
    contentType: false,
    cache: false,
    success: function (save) {
      Swal.close(timerInterval);
      $("#btn_entradas").prop('disabled', false);
      if (save === true) {
        document.getElementById("form_entradas").reset();
        document.getElementById("tipo").value = '';
        document.getElementById("div_cards").innerHTML = "";
        document.getElementById("div_usuario").innerHTML = "";
        $(".btn-opcion").removeClass("active focus");
        Swal.fire({
          icon: "success",
          title: "Éxito",
          text: "Sé ha registrado correctamente.",
        });
      } else if (save == 'registroExistente') {
        Swal.fire({
          icon: "info",
          title: "Datos ya registrados",
          text: "Este marbete tiene registro duplicado o no tiene registro de salida.",
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
  }).fail(function (jqXHR, textStatus, errorThrown) {
    $("#btn_entradas").prop('disabled', false);
    if (jqXHR.status === 0) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Fallo de conexión: ​​Verifique la red.",
      });
    } else if (jqXHR.status == 404) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No se encontró la página solicitada [404]",
      });
    } else if (jqXHR.status == 500) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Internal Server Error [500]",
      });
    } else if (textStatus === 'parsererror') {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Error de análisis JSON solicitado.",
      });
    } else if (textStatus === 'timeout') {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Time out error.",
      });
    } else if (textStatus === 'abort') {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ajax request aborted.",
      });
    } else {
      alert('Uncaught Error: ' + jqXHR.saveText);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: `Uncaught Error: ${jqXHR.responseText}`,
      });
    }
  });
})

function tipoVehiculoSalida(tipo) {
  console.log('salida->', tipo);
  $("#tipo_salida").val(tipo);
  document.getElementById("tipo_salida").classList.remove("has-error");
  document.getElementById("error_tipo_salida").textContent = "";
  datosMarbeteSalida();
}

function datosMarbeteSalida() {
  console.log('funcion Salida');
  var numMarbete = document.getElementById("num_marbete_salida");
  if (numMarbete.value.length > 0) {
    numMarbete.classList.remove("has-error");
    document.getElementById("error_num_marbete_salida").textContent = "";
  }
  $("#div_usuario_salidas").empty();
  $("#div_cards_salidas").empty();
  $("#item_vehiculo_salida").val('');

  if ($("#num_marbete_salida").val().length > 0 && $("#tipo_salida").val().length > 0) {
    var datosMarberte = new FormData();
    datosMarberte.append('tipo', $("#tipo_salida").val());
    datosMarberte.append('marberte', $("#num_marbete_salida").val());
    $.ajax({
      url: `${urls}estacionamiento/datos_marberte`,
      data: datosMarberte,
      type: "post",
      processData: false,
      contentType: false,
      cache: false,
      dataType: "json",
      success: function (data) {
        console.log(data);
        if (data != null && data != false) {
          $("#div_usuario_salidas").append(`<label style="padding-top: 10px;margin-bottom: -10px;">${data[0].name}</label><hr>`);
          var i = 1;
          data.forEach(key => {
            $("#div_cards_salidas").append(`
            <label id="lbl_salida_${i}" class="btn btn-outline-secondary btn-opcion" style="width: 95%; margin-top: 1rem;">
            <input type="radio" id="radio_salida_${i}" onclick="vehiculoSalida(${key.id_item})"> 
            <div class="row">
                <div class="col-sm-4">
                    <h6>Modelo: <b>${key.model}</b></h6>
                </div>
                <div class="col-sm-4">
                    <h6>Placas: <b>${key.placas}</b></h6>
                </div>
                <div class="col-sm-4">
                    <h6>Color: <b>${key.color}</b></h6>
                </div>
            </div>
            </div>
            </label> `);
            i++;
          });
          document.getElementById("radio_salida_1").checked = true;
          document.getElementById("lbl_salida_1").classList.add("focus");
          document.getElementById("lbl_salida_1").classList.add("active");
          $("#item_vehiculo_salida").val(data[0].id_item);
        } else {
          Swal.fire({
            icon: "error",
            title: "Datos no exsistentes",
            text: "Numero de marberte o tipo de vehiculo.",
          });
        }
      }
    });
  }
}

function vehiculoSalida(id_item) {
  $("#item_vehiculo_salida").val('');
  $("#item_vehiculo_salida ").val(id_item);
}

$("#form_salidas").submit(function (e) {
  e.preventDefault();

  var tipo = document.getElementById("tipo_salida");
  var numMarbete = document.getElementById("num_marbete_salida");
  var id_item_salida = document.getElementById("item_vehiculo_salida");

  var error_tipo, error_tag, error_item_salida;

  if (tipo.value.length === 0) {
    error_tipo = 'Opción requerida';
    document.getElementById("error_tipo_salida").textContent = error_tipo;
  } else {
    error_tipo = '';
    document.getElementById("error_tipo_salida").textContent = error_tipo;
  }

  if (numMarbete.value.length === 0) {
    error_tag = 'Opción requerida';
    numMarbete.classList.add("has-error");
    document.getElementById("error_num_marbete_salida").textContent = error_tag;
  } else {
    error_tag = '';
    numMarbete.classList.remove("has-error");
    document.getElementById("error_num_marbete_salida").textContent = error_tag;
  }

  error_item_salida = (id_item_salida.value.length == 0) ? 'item no cargado' : '';

  if (error_tipo != "" || error_tag != "" || error_item_salida != "") { return false }

  $("#btn_salidas").prop('disabled', true);
  let timerInterval = Swal.fire({ //se le asigna un nombre al swal
    allowOutsideClick: false,
    title: '¡Guardando Registro!',
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });

  const datas = new FormData($("#form_salidas")[0]);
  $.ajax({
    data: datas,
    url: `${urls}estacionamiento/registar_entrada_salida/2`,
    type: "post",
    dataType: "json",
    processData: false,
    contentType: false,
    cache: false,
    success: function (save) {
      Swal.close(timerInterval);
      $("#btn_salidas").prop('disabled', false);
      if (save === true) {
        document.getElementById("form_salidas").reset();
        document.getElementById("tipo_salida").value = '';
        document.getElementById("div_cards_salidas").innerHTML = "";
        document.getElementById("div_usuario_salidas").innerHTML = "";
        $(".btn-opcion-salida").removeClass("active focus");
        Swal.fire({
          icon: "success",
          title: "Éxito",
          text: "Sé ha registrado correctamente.",
        });
      } else if (save == 'noRegistro') {
        Swal.fire({
          icon: "info",
          title: "Datos Incorrectos",
          text: "No se encontró registro de entrada con los datos proporcionados.",
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
  }).fail(function (jqXHR, textStatus, errorThrown) {
    $("#btn_salidas").prop('disabled', false);
    if (jqXHR.status === 0) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Fallo de conexión: ​​Verifique la red.",
      });
    } else if (jqXHR.status == 404) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No se encontró la página solicitada [404]",
      });
    } else if (jqXHR.status == 500) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Internal Server Error [500]",
      });
    } else if (textStatus === 'parsererror') {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Error de análisis JSON solicitado.",
      });
    } else if (textStatus === 'timeout') {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Time out error.",
      });
    } else if (textStatus === 'abort') {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ajax request aborted.",
      });
    } else {
      alert('Uncaught Error: ' + jqXHR.saveText);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: `Uncaught Error: ${jqXHR.responseText}`,
      });
    }
  });
})

/* class QRReader {
  constructor(canvasVideoElement, qrDataContainerElement) {
    this.isCamReady = false; // Una booleana para determinar si la cámara se ha iniciado por primera vez.
    this.isCamOpen = false; // Otra booleana para controlar si la cámara está encendida o apagada.
    this.stream = null; // El flujo de datos extraídos directamente de la cámara.
    this.rafID = null; //  El identificador de iteración de “requestAnimationFrame”. Usaremos esta API HTML5 para ejecutar el código de escaneo de forma recurrente.
    this.camCanvas = canvasVideoElement; // uardamos una referencia al canvas pasado como parámetro.
    this.qrDataContainer = qrDataContainerElement; // También almacenamos la referencia al contenedor donde mostrar el resultado del código encontrado.
    
    // Obtenenos el contexto “2d” del canvas. Será necesario, para pintar cada fotograma de la cámara en el área definida por el canvas.
    this.camCanvasCtx = this.camCanvas.getContext("2d", {
      willReadFrequently: true, 
    });

    // Creamos un elemento video. En una primera instancia, el “stream” obtenido de la cámara se plasmará en un vídeo, para posteriormente re-pintarlo en el canvas. Para evitar que el usuario vea esta etiqueta intermedia, la agregamos con unos estilos adicionales, y el atributo “playsinline”.
    this.video = document.createElement("video");
    this.video.classList.add("video-cam");
    this.video.setAttribute("playsinline", true);
    document.body.appendChild(this.video);
  }
}
export default QRReader;

getIsCamOpen() {
    return this.isCamOpen;
  } */