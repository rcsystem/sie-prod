/**
 * ARCHIVO MODULO FACTURAS
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
$(document).ready(function () {});

$("#consulta-cfdi-form").submit(function (e) {
  e.preventDefault();

  let data = new FormData($("#consulta-cfdi-form")[0]);
  

  $.ajax({
    data: data,
    type: "POST",
    url: `${urls}admin/datos_factura`,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (resp) {

        console.log("hola: ",resp);

        $("#estatus_cfdi").append(` <div class="alert alert-${resp.clase} alert-dismissible fade show" role="alert">
  <strong>${resp.resultado}</strong> 
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>` );

          $("#tabla_result").append(`
            <table style="border-collapse: collapse; width: 100%; border: 1px solid rgb(204, 204, 204);"><thead><tr><th style="border: 1px solid rgb(204, 204, 204); padding: 8px;">Datos del Comprobante</th><th style="border: 1px solid rgb(204, 204, 204); padding: 8px;"></th></tr></thead><tbody><tr><td style="border: 1px solid rgb(204, 204, 204); padding: 8px;">Cadena Original SAT</td><td style="border: 1px solid rgb(204, 204, 204); padding: 8px;">
                <div>
                    <span>||1.1|6769229B-24D5-54E4-9EAD-01037DB4255C|2024-08</span>
                    <span style="display: none;">-24T13:47:07|CVD110412TF6|AE8P92huzVwGKFXqAAkKdqXmFv9bvHREZiL/9JTpqVkAjkFIgnVq0/PMHdCM4AdzQR14oIIs9Q4iWJkrXb/Yzb9cd4QoCtdW/7V5P8so2jUS1jEBPcLfkoYg+1PGnNswCwMYJ548WvsH7k7XJgrYaw7AgiCEpjTaxCoDuXv8hYhgZ59BubJ21AXiuC5znVYnnmmn2GNSfdonUFnvj0XW2cke9Afie5X0i/SzbqQstGz5T9o/xZSWKJtlndLxA5B6Ei9MZq/GmqyLYOozp6/ibZEPia9fYRnHEky+5YlbnF4HvBShfMLt3hC7qZfohO/Eq/s/kZLCst9foxIAhD4clQ==|00001000000707310321||</span>
                </div>
                <button style="background-color: #ff6900; font-size: 12px; padding: 4px 8px; border: none; border-radius: 3px; color: white;" onclick="toggleText(this)">Mostrar Más</button>
            </td></tr><tr><td style="border: 1px solid rgb(204, 204, 204); padding: 8px;">Cadena Original Comprobante</td><td style="border: 1px solid rgb(204, 204, 204); padding: 8px;">
                <div>
                    <span>||4.0|ZEEANM-I|892|2024-08-24T13:47:02|04|00001000</span>
                    <span style="display: none;">000504986732|Pago en una sola exhibición|301.67|MXN|349.94|I|01|PUE|54743|GAVK950601UY6|KARINA GALICIA VAZQUEZ|625|IVA760914GV5|INDUSTRIAL DE VALVULAS|54610|601|G03|78111808|1|E48|Unidad de servicio|Tarifa|301.67|301.67|02|301.67|002|Tasa|0.160000|48.27|301.67|002|Tasa|0.160000|48.27|48.27||</span>
                </div>
                <button style="background-color: #ff6900; font-size: 12px; padding: 4px 8px; border: none; border-radius: 3px; color: white;" onclick="toggleText(this)">Mostrar Más</button>
            </td></tr><tr><td style="border: 1px solid rgb(204, 204, 204); padding: 8px;">UUID</td><td style="border: 1px solid rgb(204, 204, 204); padding: 8px;">6769229B-24D5-54E4-9EAD-01037DB4255C</td></tr><tr><td style="border: 1px solid rgb(204, 204, 204); padding: 8px;">Estatus SAT</td><td style="border: 1px solid rgb(204, 204, 204); padding: 8px;">Vigente</td></tr><tr><td style="border: 1px solid rgb(204, 204, 204); padding: 8px;">Codigo Estatus SAT</td><td style="border: 1px solid rgb(204, 204, 204); padding: 8px;">S - Comprobante obtenido satisfactoriamente.</td></tr><tr><td style="border: 1px solid rgb(204, 204, 204); padding: 8px;">Es Cancelable</td><td style="border: 1px solid rgb(204, 204, 204); padding: 8px;">Cancelable sin aceptación</td></tr><tr><td style="border: 1px solid rgb(204, 204, 204); padding: 8px;">Estatus cancelacion</td><td style="border: 1px solid rgb(204, 204, 204); padding: 8px;"></td></tr></tbody></table>
            
            
            `);
        


    },
  });
});

function fileFacturas(e) {
  var miId = e.getAttribute("data-id");
   
  // Obtén una referencia al elemento <input> por su ID
  var input = document.getElementById(`upload`);



  let files = input.files;

  if (files.length > 1) {

    $(`#estatus_cfdi`).append(`<div id="alert_fac" class="alert alert-warning alert-dismissible fade show" role="alert">
         <b> Por favor, seleccione solo hasta 1 archivos.</b>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>`)

    setTimeout(function () {
      $(`#estatus_cfdi`).fadeOut(400, function () {
        $(this).remove(); // Elimina el elemento del DOM después de la animación de desvanecimiento
      });
    }, 3500); // 3000 milisegundos (3 segundos)

    btn_ejecutar.disabled = false;
    this.value = ''; // Limpia la selección de archivos
    return
  }


  var xmlSelected = false;
 

  for (var i = 0; i < files.length; i++) {
    var file = files[i];
    if (file.name.endsWith('.xml')) {
      xmlSelected = true;
    } 
  }

  if (!(xmlSelected)) {

    $(`#estatus_cfdi`).append(`<div id="alert_fac" class="alert alert-warning alert-dismissible fade show" role="alert">
       <b>Por favor, seleccione un archivo XML</b>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>`)
    this.value = ''; // Limpia la selección de archivos
    setTimeout(function () {
      $(`#estatus_cfdi`).fadeOut(400, function () {
        $(this).remove(); // Elimina el elemento del DOM después de la animación de desvanecimiento
      });
    }, 3500); // 3000 milisegundos (3 segundos)
    return
  }





}
