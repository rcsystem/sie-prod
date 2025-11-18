$(document).ready(function () {
  pintarDatosRequest();
  if ($("#type").val() == 2) {
    $(".my-col-it").attr('class', 'col-md-3');
    $("#div_monto_grado").hide();
  }
  datosFolio();


});
var idUser = document.getElementById('iduser').value;

function pintarDatosRequest() {
  let dataForm = new FormData();
  dataForm.append("folio", $("#folio").val());
  dataForm.append("type", $("#type").val());
  $("#h_folio").text();
  $("#h2_solicitado").text();
  $("#h2_estado_cuenta").text();
  $("#h2_comprobado").text();
  $("#h2_descuento").text();
  $("#h2_grado").text();
  $("#icon_grade").text();
  $.ajax({
    data: dataForm,
    url: `${urls}viajes/datos_solicitud_cartas_cabeza`,
    type: "post",
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (resp) {
      if (resp) {
        $("#h_folio").text(`Folio: ${resp.folio}`);
        $("#h2_solicitado").text(resp.solicitado);
        $("#h2_estado_cuenta").text(resp.cuenta);
        $("#h2_comprobado").text(resp.comprobado);
        $("#h2_descuento").text(resp.descuento);
        $("#h2_grado").text(resp.monto_diario);
        $("#icon_grade").text(resp.icon_grado);
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
        console.log("Mal Revisa");
      }
    },
  });
}

function datosFolio() {
  const data = new FormData();
  data.append('folio', $("#folio").val());
  data.append('type', $("#type").val());

  $.ajax({
    data: data,
    url: `${urls}viajes/datos_folio_tipo`,
    type: "post",
    cache: false,
    dataType: "json",
    contentType: false,
    processData: false,
    success: function (data) {
      if (data != false) {
        let cont = 1;
        let forms = '';
        let ncrs = '';
        data.datos.forEach(function (elemento, index) {

          
          if (idUser == 224 || idUser == 185 || idUser == 404 || idUser == 631 || idUser == 314 || idUser == 1 || idUser == 253) {

            ncrs = `<div class="col-md-12" style="margin-right:.5rem;margin-bottom:1rem">
                      <div class="row">
                        <div class"col-md-4">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" codigo="${cont}" name="Cnr_${cont}" id="cnr_sin_${cont}" value="1" checked>
                            <label class="form-check-label" for="cnr_${cont}">Sin CNR</label>
                          </div>

                          <div class="form-check form-check-inline">
                            <input class="form-check-input" codigo="${cont}" type="radio" name="Cnr_${cont}" id="cnr_con_${cont}" value="2">
                            <label class="form-check-label" for="cnr_${cont}">Con NCR</label>
                          </div>
                        </div>
                        <div id="inputOculto_${cont}"  class=" col-md-5">
                          <div class="form-check form-check-inline input-group-sm">
                            <input class="form-control" type="text" name="number_cnr_${cont}" id="number_cnr_${cont}" value=""
                              placeholder="Numero de NCR" />
                            <div id="error_ncr_${cont}" class="text-danger"></div>

                          </div>
                           <div class="form-check form-check-inline input-group-sm">
                            <input min="0" max="999999999" class="form-control" type="number" name="number_caso_${cont}" id="number_caso_${cont}" oninput="validateInputLength(this)" value=""
                              placeholder="Numero de Caso" />
                            <div id="error_caso_${cont}" class="text-danger"></div>

                          </div>

                        </div>
                      </div>
                    </div>`

          }
         

          if (elemento.rule_code == 'EX' || elemento.rule_code == 'E' || elemento.rule_code == 'V') {
            forms = `<div id="tipo_facura_${cont}" class="col-md-10">
            
              <div class="row">
                <div class="mr-2">
                  <label id="up_file" class="up_file" for="upload_${cont}">
                    <input type="file" id="upload_${cont}" name="upload_${cont}" data-id="${cont}" onchange="fileEfectivo(this)"
                      accept=".pdf" class="btn" />
                    Elige PDF
                  </label>
                </div>
            
                
                <div id="error_user_files_${cont}" class="text-danger"></div>
                <div class="form-row col-md-10">
                  <div class="input-group-sm col-md-3">
                    <input type="text" id="proveedor_${cont}" name="proveedor_${cont}" class="form-control" value=""
                      placeholder="proveedor">
                    <div id="error_prove_${cont}" class="text-danger"></div>
                  </div>
                  <div class="input-group-sm col-md-3">
                    <input type="number" id="cantidad_${cont}" name="cantidad_${cont}" class="form-control" value=""
                      placeholder="cantidad">
                    <div id="error_cantidad_${cont}" class="text-danger" ></div>
                  </div>
                  <div class="input-group-sm col-md-3">
                    <input type="date" id="fecha_${cont}" name="fecha_${cont}" class="form-control">
                    <div id="error_fecha_${cont}" class="text-danger" ></div>
                  </div>
                </div>
              </div>
              <div id="files_selected_${cont}" class="files">
                <ul id="lista_facturas_${cont}" class="horizontal-list"></ul>
              </div>
            </div>`;
          } else {
            forms = ` <div class="col-md-6">
            
              <div class="row">
              <div class="input-group-sm  col-md-4"> 
              <input type="text" id="visita_cliente_${cont}" name="visita_cliente_${cont}" class="form-control" placeholder="Cliente a Visitar">
              <div id="error_cliente_${cont}" class="text-danger" ></div>
              </div>
                <div id="tipo_archivo_${cont}">
                  <label id="up_file" class="up_file" for="upload_${cont}">
                    <input type="file" 
                            id="upload_${cont}" 
                            name="upload_${cont}" 
                            data-id="${cont}"
                            onchange="fileFacturas(this)"
                            accept=".pdf, .xml" 
                            multiple 
                            class="btn"/>
                    Elige PDF & XML
                  </label>
                </div>
               
                <div id="files_selected_${cont}" class="files">
    
                  <ul id="lista_facturas_${cont}" class="horizontal-list"></ul>
                </div>
    
                <div id="error_user_files_${cont}" class="text-danger"></div>
    
    
              </div>
    
            </div>`;
          }





          const cardHtml = `<div id="card_${cont}" class="shadow-lg card ${(elemento.rule_code == 'EF') ? 'bg-green' : 'bg-dark'} text-white col-md-12">
          <div class="card-body">
        
            <div class="d-flex justify-content-between align-items-center">
            <input type="hidden" id="lugares_${cont}" name="lugares_${cont}"  value="${elemento["lugar"]}">
            <input type="hidden" id="cantidades_${cont}" name="cantidades_${cont}"  value="${elemento.amount} ${elemento.divisa}">
            <input type="hidden" id="fechas_${cont}" name="fechas_${cont}"  value="${elemento.fecha}">
              <div class="col-md-8">
                <h5 class="card-title">| ${elemento["lugar"]} |</h5>
                <h5 class="card-title"> a comprobar: $${elemento.amount} ${elemento.divisa} </h5>
                <h5 class="card-title">|  ${elemento.fecha}</h5>
              </div>
              <span class="card-subtitle mb-2 badge badge-${elemento.estado_color}">${elemento.estado_txt}</span>
            </div>
            <hr style="background-color: #ffffff;">
            <div id="error_${cont}" class="col-md-12"></div>
            ${ncrs}
            <div class="col-md-12">
              <form id="factura_item_${cont}" method="post" enctype="multipart/form-data">
                <input type="hidden" id="id_item_${cont}" name="id_item_${cont}" value="${elemento.id_item}">
                <input type="hidden" id="comprobar_monto_${cont}" name="comprobar_monto_${cont}" value="${elemento.amount}">
                <input type="hidden" id="politics_status_${cont}" name="politics_status_${cont}"
                  value="${elemento.politics_status}">
                  
              
        
                <div class="row">
                ${(elemento.politics_status != 3 || elemento.rule_code == 'EX' || elemento.rule_code == 'E' || elemento.rule_code == 'V')
              ? `<div class="input-group-sm col-md-2">
                      <select name="tipo_gasto_${cont}" id="tipo_gasto_${cont}" class="form-control" data-id="${cont}"
                        onchange="facExtras(this)">
                        <option value="">Seleccionar...</option>
          
                      </select>
                      <div id="error_tipo_gasto_${cont}" class="text-danger"></div>
                    </div>`
              : ''
            }

            

              
                  
                  <div id="extras_${cont}"></div>
                 
                  ${(elemento.politics_status != 3) ? forms : ''}
                  <div id="btn_submit_${cont}"></div>
                  </div>
                  <div id="nota_credito_${cont}"></div>
        
              </form>
            </div>
            <!-- <div id="datos_totales_${cont}" class="files col-md-6">
                    <h2 id="totales_${cont}"></h2>
        
                </div> -->
            <div>
              <hr style="background-color: #ffffff;">
              <div id="resultado_${cont}" class=" table-responsive col-md-12"></div>
        
            </div>
          </div>
        </div>`;

          $("#formato_nuevo").append(cardHtml);

          $(`#inputOculto_${cont}`).hide();

          const selectElement = $(`#tipo_gasto_${cont}`);

          data.category.forEach(function (category, index) {
            const option = $('<option>', {
              value: category.id_category,
              text: category.category,
            });
            selectElement.append(option);
          });

          cont++;
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      handleError(jqXHR);
    },
  });
}

function handleError(jqXHR) {
  let errorMessage = "Ocurrió un error en el servidor! Contactar con el Administrador";

  switch (jqXHR.status) {
    case 0:
      errorMessage = "Fallo de conexión: Verifique la red.";
      break;
    case 404:
      errorMessage = "No se encontró la página solicitada [404]";
      break;
    case 500:
      errorMessage = "Internal Server Error [500]";
      break;
    case "parsererror":
      errorMessage = "Error de análisis JSON solicitado.";
      break;
    case "timeout":
      errorMessage = "Time out error.";
      break;
    case "abort":
      errorMessage = "Ajax request aborted.";
      break;
  }

  Swal.fire({
    icon: "error",
    title: "Oops...",
    text: errorMessage,
  });
}


function escuchar() {
  /* Ponemos evento blur a la escucha sobre id nombre en id cliente. */
  $("#form-validacion").on("blur", "#folio", function () {
    /* Obtenemos el valor del campo */
    //const folio = this.value;
    const folio = document.getElementById('folio').value;
    console.log(folio);
    const tipo_gasto = document.getElementById('tipo_gasto').value;
    if (tipo_gasto == "") {
      Swal.fire(`!No se ha seleccionado el tipo de gasto a comprobrar!`, "", "warning");
      return
    }
    const fileInput = document.getElementById('userfile');
    //fileInput.disabled = false;
    /* Si la longitud del valor es mayor a 2 caracteres.. */
    if (folio.length >= 0) {
      /* Hacemos la consulta ajax */
      var consulta = $.ajax({
        type: "POST",
        async: true,
        url: `${urls}viajes/buscar-datos`,
        data: { folio: folio, tipo_gasto: tipo_gasto },
        dataType: "JSON",
      });

      /* En caso de que se haya retornado bien.. */
      consulta.done(function (resp) {
        console.log("que pasa ", resp);
        if (resp === 3) {
          // Obtener el elemento del input de tipo file
          // Bloquear el input de tipo file
          // fileInput.disabled = true;
          Swal.fire(`!El viaje no esta autorizado para realizar este proceso!`, "", "error")
          return
        }
        // console.log(resp);
        $("#datos_requisicion").html('');
        if (tipo_gasto == 2) {
          if (resp != false) {
            document.getElementById('total_gastos').value = resp.total_amount;
            let total_amount = new Intl.NumberFormat('es-MX').format(resp.total_amount);
            $("#datos_requisicion").html(`<div class="form-group col-md-6">
                                              <h3>Motivo: ${resp.reasons}</h3>
                                          </div>
                                          <div class="form-group col-md-3">
                                          <h3>Fecha Inicio:</h3>
                                              <h2> ${moment(resp.start_date).format('DD/MM/YYYY')}</h2>
                                          </div>
                                          <div class="form-group col-md-3">
                                          <h3>Fecha Termino:</h3>
                                              <h2>${moment(resp.end_date).format('DD/MM/YYYY')}</h2>
                                          </div>
                                          <div class="form-group col-md-3">
                                          <h3>Total a Comprobar:</h3>
                                              <h2> $${total_amount}</h2>
                                          </div> 
                                          <div id="result_total" class="form-group col-md-3 text-success">
                                          
                                          </div>`);

            return false;
          } else {
            $.each(resp, function (index, data) {
              if (data.name !== undefined) {
                $(`#usuario_extra_${cont_}`).val(data.name + " " + data.surname);
              }
              if (data.job !== undefined) {
                $(`#puesto_${cont_}`).val(data.job);
              }
              if (data.departament !== undefined) {
                $(`#depto_${cont_}`).val(data.departament);
              }

              return true;
            });
          }

        } else {
          if (resp != false) {
            document.getElementById('total_gastos').value = resp.total_travel;
            let total_travel = new Intl.NumberFormat('es-MX').format(resp.total_travel);
            $("#datos_requisicion").html(`<div class="form-group col-md-3">
                                          <h3>Fecha Inicio:</h3>
                                              <h2> ${moment(resp.start_of_trip).format('DD/MM/YYYY')}</h2>
                                          </div>
                                          <div class="form-group col-md-3">
                                          <h3>Fecha Termino:</h3>
                                              <h2>${moment(resp.return_trip).format('DD/MM/YYYY')}</h2>
                                          </div>
                                          <div class="form-group col-md-3">
                                          <h3>Total a Comprobar:</h3>
                                              <h2> $${total_travel}</h2>
                                          </div>
                                          <div id="result_total" class="form-group col-md-3 text-success">
                                          
                                          </div>`);

            return false;
          } else {
            $.each(resp, function (index, data) {
              if (data.name !== undefined) {
                $(`#usuario_extra_${cont_}`).val(data.name + " " + data.surname);
              }
              if (data.job !== undefined) {
                $(`#puesto_${cont_}`).val(data.job);
              }
              if (data.departament !== undefined) {
                $(`#depto_${cont_}`).val(data.departament);
              }

              return true;
            });
          }

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

$("#form-validacion").on("submit", function (e) {
  e.preventDefault();

  const tipo_gasto = document.getElementById('tipo_gasto');
  const folio = document.getElementById('folio');
  const tipo = document.getElementById('type');
  const btn_ejecutar = document.getElementById('btn-ejecutar');
  const user_files = document.getElementById('upload');
  const total_gastos = document.getElementById('total_gastos');
  const files_data = document.querySelector('#upload').files.length;


  //console.log("datos: ",  this);
  const error_tipo_gasto = (tipo_gasto.value.length == 0) ? "El campo es requerido" : '';
  console.log("tipo", error_tipo_gasto);
  document.getElementById("error_tipo_gasto").innerHTML = error_tipo_gasto;
  (error_tipo_gasto.length > 0) ? tipo_gasto.classList.add("has-error") : tipo_gasto.classList.remove("has-error");


  //const error_user_files = (user_files.value.length == 0) ? "El campo es requerido" : '';
  const error_user_files = (files_data < 1) ? "El campo es requerido" : '';

  //console.log("tipo_123", files_data);
  document.getElementById("error_user_files").innerHTML = error_user_files;
  (error_user_files.length > 0) ? user_files.classList.add("has-error") : user_files.classList.remove("has-error");



  if (error_tipo_gasto != '' || error_user_files != '') {
    console.log("estoy aqui241");
    return false;
  }




  btn_ejecutar.disabled = true;


  let data = new FormData($("#form-validacion")[0]);


  $.ajax({
    data: data,
    url: `${urls}viajes/registrar-comprobantes`,
    type: "POST",
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    dataType: "json",
    success: function (resp) {
      console.log("RESULTADO: ", resp.total_facturas);
      btn_ejecutar.disabled = false;
      total_facturas = new Intl.NumberFormat('es-MX').format(resp.total_facturas);
      if (Object.entries(resp).length != 0) {
        document.getElementById("result_total").innerHTML += '';
        document.getElementById("result_total").innerHTML += `<h3>Total Comprobado:</h3>
                                                              <h2> $${total_facturas}</h2>`;
        document.getElementById('form-validacion').reset();
        document.getElementById("fileOutput").innerHTML = '';

        Swal.fire(`!Se han Registrado las Facturas Correctamente la cantidad Total es: ${total_facturas} !`, "", "success");


      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
    error: function () {
      btn_ejecutar.disabled = false;
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
      btn_ejecutar.disabled = false;

    } else if (jqXHR.status == 404) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No se encontró la página solicitada [404]",
      });
      btn_ejecutar.disabled = false;
    } else if (jqXHR.status == 500) {

      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Internal Server Error [500]",
      });
      btn_ejecutar.disabled = false;
    } else if (textStatus === 'parsererror') {

      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Error de análisis JSON solicitado.",
      });
      btn_ejecutar.disabled = false;
    } else if (textStatus === 'timeout') {

      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Time out error.",
      });
      btn_ejecutar.disabled = false;
    } else if (textStatus === 'abort') {

      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ajax request aborted.",
      });

      btn_ejecutar.disabled = false;
    } else {

      alert('Uncaught Error: ' + jqXHR.responseText);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: `Uncaught Error: ${jqXHR.responseText}`,
      });
      btn_ejecutar.disabled = false;
    }
  });
});


function enviarFormulario(miId) {
  const tipo_gasto = document.getElementById(`tipo_gasto_${miId}`);
  const folio = document.getElementById('folio').value; // Agregamos .value para obtener el valor
  const tipo = document.getElementById('type').value; // Agregamos .value para obtener el valor
  const politics_status = document.getElementById(`politics_status_${miId}`);
  const id_item = document.getElementById(`id_item_${miId}`);
  const comprobar_monto = document.getElementById(`comprobar_monto_${miId}`);
  const total_acumulado = document.getElementById(`total_acumulado`);

  const visitar_estado = document.getElementById(`estado_visitar`);
  const dias_visita = document.getElementById(`dias_visita`);

  const lugares = document.getElementById(`lugares_${miId}`);
  const cantidades = document.getElementById(`cantidades_${miId}`);
  const fechas = document.getElementById(`fechas_${miId}`);


  var ncr = document.getElementById(`cnr_con_${miId}`);
  var number_ncr = document.getElementById(`number_cnr_${miId}`);
  var number_caso = document.getElementById(`number_caso_${miId}`);
  
  if (number_ncr !== null) {
    if (ncr.checked) {

      const error_ncr = (number_ncr.value.length === 0) ? "El campo es requerido" : '';
      document.getElementById(`error_ncr_${miId}`).innerHTML = error_ncr;
      (error_ncr.length > 0) ? number_ncr.classList.add("has-error") : number_ncr.classList.remove("has-error");

      

      if (error_ncr !== '') {

        return false;
      }

    }
  }

  if (number_caso !== null) {
    
    if (ncr.checked) {
      const error_caso = (number_caso.value.length === 0) ? "El campo es requerido" : '';
      document.getElementById(`error_caso_${miId}`).innerHTML = error_caso;
      (error_caso.length > 0) ? number_caso.classList.add("has-error") : number_caso.classList.remove("has-error");

      if ( error_caso !== '' ) {

        return false;
      }
    }
    
  }



  const visita_cliente = document.getElementById(`visita_cliente_${miId}`);
  const btn_ejecutar = document.getElementById(`btn_comprobar_${miId}`);
  const files = document.getElementById(`upload_${miId}`);
  const files_data = document.querySelector(`#upload_${miId}`).files.length;
  let nota_credito = false;
  const extras = document.getElementById(`extras_${miId}`).hasChildNodes();

  const notas = document.getElementById(`upload_nota_${miId}`);

  const propinas = document.getElementById(`propinas_${miId}`);

  const iva = document.getElementById(`iva_${miId}`);
  const iva_monto = document.getElementById(`iva_monto_${miId}`);

  let rutas = '';

  // const total_gastos = document.getElementById('total_gastos');

  console.log("files_data: ", files_data);
  const error_tipo_gasto = (tipo_gasto.value.length === 0) ? "El campo es requerido" : '';
  console.log("tipo", error_tipo_gasto);
  document.getElementById(`error_tipo_gasto_${miId}`).innerHTML = error_tipo_gasto;
  (error_tipo_gasto.length > 0) ? tipo_gasto.classList.add("has-error") : tipo_gasto.classList.remove("has-error");

  console.log("tipo_123", files_data);
  const error_user_files = (files_data < 1) ? "El campo es requerido" : '';
  document.getElementById(`error_user_files_${miId}`).innerHTML = error_user_files;
  (error_user_files.length > 0) ? files.classList.add("has-error") : files.classList.remove("has-error");


  const error_estado = (visitar_estado.value.length === 0) ? "El campo es requerido" : '';
  document.getElementById(`error_estado`).innerHTML = error_estado;
  (error_estado.length > 0) ? visitar_estado.classList.add("has-error") : visitar_estado.classList.remove("has-error");


  const error_dias_visita = (dias_visita.value.length === 0) ? "El campo es requerido" : '';
  document.getElementById(`error_dias_visita`).innerHTML = error_dias_visita;
  (error_dias_visita.length > 0) ? dias_visita.classList.add("has-error") : dias_visita.classList.remove("has-error");



  const error_cliente = (visita_cliente.value.length === 0) ? "El campo es requerido" : '';
  document.getElementById(`error_cliente_${miId}`).innerHTML = error_cliente;
  (error_cliente.length > 0) ? visita_cliente.classList.add("has-error") : visita_cliente.classList.remove("has-error");


  if (extras) {
    console.log("propinas estan aqui");
    if (tipo_gasto.value == 19) {

      if ($(`#upload_nota_${miId}`).length) {

        const notas_data = document.querySelector(`#upload_nota_${miId}`).files.length;

        var checkbox = document.getElementById(`nota_${miId}`);
        if (checkbox.checked) {

          const error_user_notas = (notas_data < 1) ? "El campo es requerido" : '';
          document.getElementById(`error_user_nota_${miId}`).innerHTML = error_user_notas;
          //(error_user_notas.length > 0) ? notas.classList.add("has-error") : notas.classList.remove("has-error");

          if (error_user_notas !== '') {

            return false;
          }
          nota_credito = true;

        }
      }

    }

    if (tipo_gasto.value == 18) {

      var checkbox = document.getElementById(`propina_${miId}`);
      if (checkbox.checked) {

        const error_propina = (propinas.value === '') ? "El campo es requerido" : '';
        document.getElementById(`error_propinas_${miId}`).innerHTML = error_propina;
        (error_propina.length > 0) ? propinas.classList.add("has-error") : propinas.classList.remove("has-error");

        if (error_propina !== '') {

          return false;
        }
        // nota_credito = true;

      }

    }

    if (tipo_gasto.value == 17) {


      console.log("propinas estan aqui OXXO");

      const error_iva = (iva.value === '') ? "El campo es requerido" : '';
      document.getElementById(`error_iva_${miId}`).innerHTML = error_iva;
      (error_iva.length > 0) ? iva.classList.add("has-error") : iva.classList.remove("has-error");

      const error_iva_monto = (iva_monto.value === '') ? "El campo es requerido" : '';
      document.getElementById(`error_iva_monto_${miId}`).innerHTML = error_iva_monto;
      (error_iva_monto.length > 0) ? iva_monto.classList.add("has-error") : iva_monto.classList.remove("has-error");

      if (error_iva !== '' || error_iva_monto !== '') {

        return false;
      }


    }

    if (tipo_gasto.value == 16) {


      console.log("propinas estan aqui");


      var checkbox = document.getElementById(`propina_${miId}`);
      if (checkbox.checked) {

        const error_propina = (propinas.value === '') ? "El campo es requerido" : '';
        document.getElementById(`error_propinas_${miId}`).innerHTML = error_propina;
        (error_propina.length > 0) ? propinas.classList.add("has-error") : propinas.classList.remove("has-error");

        if (error_propina !== '') {

          return false;
        }
        // nota_credito = true;

      }


    }



  }


  if (error_tipo_gasto !== '' || error_user_files !== '' || error_estado !== '' || error_dias_visita !== '' || error_cliente !== '') {
    console.log("estoy aqui2413");
    return false;
  }

  btn_ejecutar.disabled = true;

  var formData = new FormData();
  var files_list = files.files;

  for (var i = 0; i < files_list.length; i++) {
    formData.append('upload[]', files_list[i]);
  }

  formData.append('lugares', lugares.value);
  formData.append('cantidades', cantidades.value);
  formData.append('fechas', fechas.value);

  formData.append('type', tipo); // Usamos la variable 'tipo' en lugar de 'tipo.value()'
  formData.append('folio', folio); // Usamos la variable 'folio' en lugar de 'folio.value()'
  formData.append('id_item', id_item.value);
  formData.append('cont', miId);
  formData.append('tipo_gasto', tipo_gasto.value);
  formData.append('politics_status', politics_status.value);
  formData.append('comprobar_monto', comprobar_monto.value);
  formData.append('total_acumulado', total_acumulado.value);
  formData.append('visitar_estado', visitar_estado.value);
  formData.append('dias_visita', dias_visita.value);
  formData.append('nota_credito', nota_credito);
  formData.append('visita_cliente', visita_cliente.value);

  if (tipo_gasto.value == 18) {
    //var checkbox = document.getElementById(`propina_${miId}`);
    if (checkbox.checked) {
      formData.append('propinas', propinas.value);
    }
  }

  if (tipo_gasto.value == 17) {
    //var checkbox = document.getElementById(`propina_${miId}`);

    formData.append('iva', iva.value);
    formData.append('iva_monto', iva_monto.value);

  }


  if (tipo_gasto.value == 16) {
    //var checkbox = document.getElementById(`propina_${miId}`);
    if (checkbox.checked) {
      formData.append('propinas', propinas.value);
    }
  }

  if (number_ncr !== null) {

    if (ncr.checked) {

      formData.append('number_ncr', number_ncr.value);
      formData.append('number_caso', number_caso.value);

    }
  }


  if ($(`#upload_nota_${miId}`).length) {
    if (checkbox.checked) {

      var notas_list = notas.files;

      for (var i = 0; i < notas_list.length; i++) {
        formData.append('notas[]', notas_list[i]);
      }
    }
    rutas = `${urls}viajes/registrar-comprobantes-notas`;
  } else {
    rutas = `${urls}viajes/registrar-comprobantes`
  }

  $.ajax({
    data: formData,
    url: rutas,
    type: "POST",
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (resp) {
      console.log(resp);
      if (resp === true) {
        btn_ejecutar.disabled = false;
        $(`#card_${miId}`).fadeOut(3000, function () {
          // Una vez que la animación haya terminado, elimina la tarjeta del DOM
          $(this).remove();
        });

      } else {
        $(`#error_${miId}`).append(`<div id="alert_${miId}" class="alert alert-warning alert-dismissible fade show" role="alert">
         <b> ${resp}.</b>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>`)

        setTimeout(function () {
          $(`#alert_${miId}`).fadeOut(120, function () {
            $(this).remove(); // Elimina el elemento del DOM después de la animación de desvanecimiento
          });
        }, 3000); // 3000 milisegundos (3 segundos)

        btn_ejecutar.disabled = false;
        /*  Swal.fire({
           icon: "error",
           title: "Oops...",
           text: `${resp}`,
         }); */

      }


    },
    error: function (jqXHR, textStatus, errorThrown) {
      btn_ejecutar.disabled = false;
      handleError(jqXHR);
    },
  });
}

function enviarFormularioGastos(miId) {
  const tipo_gasto = document.getElementById(`tipo_gasto_${miId}`);
  const folio = document.getElementById('folio').value; // Agregamos .value para obtener el valor
  const tipo = document.getElementById('type').value; // Agregamos .value para obtener el valor
  const politics_status = document.getElementById(`politics_status_${miId}`);
  const id_item = document.getElementById(`id_item_${miId}`);
  const comprobar_monto = document.getElementById(`comprobar_monto_${miId}`);
  const total_acumulado = document.getElementById(`total_acumulado`);

  const total_taxi = document.getElementById(`taxi_cantidad_${miId}`);

  const lugares = document.getElementById(`lugares_${miId}`);
  const cantidades = document.getElementById(`cantidades_${miId}`);
  const fechas = document.getElementById(`fechas_${miId}`);

  /*  const visitar_estado = document.getElementById(`estado_visitar`);
   const dias_visita = document.getElementById(`dias_visita`); */

  var ncr = document.getElementById(`cnr_con_${miId}`);
  var number_ncr = document.getElementById(`number_cnr_${miId}`);
  var number_caso = document.getElementById(`number_caso_${miId}`);

  if (number_ncr !== null) {
    if (ncr.checked) {

      const error_ncr = (number_ncr.value.length === 0) ? "El campo es requerido" : '';
      document.getElementById(`error_ncr_${miId}`).innerHTML = error_ncr;
      (error_ncr.length > 0) ? number_ncr.classList.add("has-error") : number_ncr.classList.remove("has-error");

      if (error_ncr !== '') {

        return false;
      }

    }
  }

  if (number_caso !== null) {
    if (ncr.checked) {

      const error_caso = (number_caso.value.length === 0) ? "El campo es requerido" : '';
      document.getElementById(`error_caso_${miId}`).innerHTML = error_caso;
      (error_caso.length > 0) ? number_caso.classList.add("has-error") : number_caso.classList.remove("has-error");

      if (error_caso !== '') {

        return false;
      }

    }
  }


  const visita_cliente = document.getElementById(`visita_cliente_${miId}`);
  const btn_ejecutar = document.getElementById(`btn_comprobar_${miId}`);
  const files = document.getElementById(`upload_${miId}`);
  const files_data = document.querySelector(`#upload_${miId}`).files.length;
  let nota_credito = false;
  const extras = document.getElementById(`extras_${miId}`).hasChildNodes();

  const notas = document.getElementById(`upload_nota_${miId}`);

  const propinas = document.getElementById(`propinas_${miId}`);

  const iva = document.getElementById(`iva_${miId}`);
  const iva_monto = document.getElementById(`iva_monto_${miId}`);

  let rutas = '';

  // const total_gastos = document.getElementById('total_gastos');

  console.log("files_data: ", files_data);
  const error_tipo_gasto = (tipo_gasto.value.length === 0) ? "El campo es requerido" : '';
  console.log("tipo", error_tipo_gasto);
  document.getElementById(`error_tipo_gasto_${miId}`).innerHTML = error_tipo_gasto;
  (error_tipo_gasto.length > 0) ? tipo_gasto.classList.add("has-error") : tipo_gasto.classList.remove("has-error");

  console.log("tipo_123", files_data);
  const error_user_files = (files_data < 1) ? "El campo es requerido" : '';
  document.getElementById(`error_user_files_${miId}`).innerHTML = error_user_files;
  (error_user_files.length > 0) ? files.classList.add("has-error") : files.classList.remove("has-error");


  /*  const error_estado = (visitar_estado.value.length === 0) ? "El campo es requerido" : '';
   document.getElementById(`error_estado`).innerHTML = error_estado;
   (error_estado.length > 0) ? visitar_estado.classList.add("has-error") : visitar_estado.classList.remove("has-error");
 
 
   const error_dias_visita = (dias_visita.value.length === 0) ? "El campo es requerido" : '';
   document.getElementById(`error_dias_visita`).innerHTML = error_dias_visita;
   (error_dias_visita.length > 0) ? dias_visita.classList.add("has-error") : dias_visita.classList.remove("has-error"); */



  const error_cliente = (visita_cliente.value.length === 0) ? "El campo es requerido" : '';
  document.getElementById(`error_cliente_${miId}`).innerHTML = error_cliente;
  (error_cliente.length > 0) ? visita_cliente.classList.add("has-error") : visita_cliente.classList.remove("has-error");


  if (extras) {
    console.log("propinas estan aqui");
    if (tipo_gasto.value == 19) {

      if ($(`#upload_nota_${miId}`).length) {

        const notas_data = document.querySelector(`#upload_nota_${miId}`).files.length;

        var checkbox = document.getElementById(`nota_${miId}`);
        if (checkbox.checked) {

          const error_user_notas = (notas_data < 1) ? "El campo es requerido" : '';
          document.getElementById(`error_user_nota_${miId}`).innerHTML = error_user_notas;
          //(error_user_notas.length > 0) ? notas.classList.add("has-error") : notas.classList.remove("has-error");

          if (error_user_notas !== '') {

            return false;
          }
          nota_credito = true;

        }
      }

    }

    if (tipo_gasto.value == 18) {

      var checkbox = document.getElementById(`propina_${miId}`);
      if (checkbox.checked) {

        const error_propina = (propinas.value === '') ? "El campo es requerido" : '';
        document.getElementById(`error_propinas_${miId}`).innerHTML = error_propina;
        (error_propina.length > 0) ? propinas.classList.add("has-error") : propinas.classList.remove("has-error");

        if (error_propina !== '') {

          return false;
        }
        // nota_credito = true;

      }

    }
    /* if (tipo_gasto.value == 1) {

    console.log("TAXI estan aqui");
    const error_taxi = (total_taxi.value === '') ? "El campo es requerido" : '';
    document.getElementById(`error_taxi_${miId}`).innerHTML = error_taxi;
    (error_taxi.length > 0) ? total_taxi.classList.add("has-error") : total_taxi.classList.remove("has-error");
    
    if (error_taxi !== '') {

      return false;
    }

    } */

    if (tipo_gasto.value == 8 || tipo_gasto.value == 12) {


      console.log("propinas estan aqui OXXO");

      const error_iva = (iva.value === '') ? "El campo es requerido" : '';
      document.getElementById(`error_iva_${miId}`).innerHTML = error_iva;
      (error_iva.length > 0) ? iva.classList.add("has-error") : iva.classList.remove("has-error");

      const error_iva_monto = (iva_monto.value === '') ? "El campo es requerido" : '';
      document.getElementById(`error_iva_monto_${miId}`).innerHTML = error_iva_monto;
      (error_iva_monto.length > 0) ? iva_monto.classList.add("has-error") : iva_monto.classList.remove("has-error");

      if (error_iva !== '' || error_iva_monto !== '') {

        return false;
      }


    }

    if (tipo_gasto.value == 6) {


      console.log("propinas estan aqui");


      var checkbox = document.getElementById(`propina_${miId}`);
      if (checkbox.checked) {

        const error_propina = (propinas.value === '') ? "El campo es requerido" : '';
        document.getElementById(`error_propinas_${miId}`).innerHTML = error_propina;
        (error_propina.length > 0) ? propinas.classList.add("has-error") : propinas.classList.remove("has-error");

        if (error_propina !== '') {

          return false;
        }
        // nota_credito = true;

      }


    }



  }



  btn_ejecutar.disabled = true;

  var formData = new FormData();
  var files_list = files.files;

  for (var i = 0; i < files_list.length; i++) {
    formData.append('upload[]', files_list[i]);
  }

  formData.append('lugares', lugares.value);
  formData.append('cantidades', cantidades.value);
  formData.append('fechas', fechas.value);

  formData.append('type', tipo); // Usamos la variable 'tipo' en lugar de 'tipo.value()'
  formData.append('folio', folio); // Usamos la variable 'folio' en lugar de 'folio.value()'
  formData.append('id_item', id_item.value);
  formData.append('cont', miId);
  formData.append('tipo_gasto', tipo_gasto.value);
  formData.append('politics_status', politics_status.value);
  formData.append('comprobar_monto', comprobar_monto.value);
  formData.append('total_acumulado', total_acumulado.value);

  formData.append('nota_credito', nota_credito);
  formData.append('visita_cliente', visita_cliente.value);

  if (tipo_gasto.value == 8 || tipo_gasto.value == 12) {
    formData.append('iva', iva.value);
    formData.append('iva_monto', iva_monto.value);

  }

  if (tipo_gasto.value == 6) {
    //var checkbox = document.getElementById(`propina_${miId}`);
    if (checkbox.checked) {
      formData.append('propinas', propinas.value);
    }
  }

  if (number_ncr !== null) {
    if (ncr.checked) {

      formData.append('number_ncr', number_ncr.value);
      formData.append('number_caso', number_caso.value);

    }
  }

  rutas = `${urls}viajes/registrar-gastos`

  $.ajax({
    data: formData,
    url: rutas,
    type: "POST",
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (resp) {
      pintarDatosRequest()
      console.log(resp);
      if (resp === true) {
        btn_ejecutar.disabled = false;
        $(`#card_${miId}`).fadeOut(4000, function () {
          // Una vez que la animación haya terminado, elimina la tarjeta del DOM
          $(this).remove();
        });

      } else {
        $(`#error_${miId}`).append(`<div id="alert_${miId}" class="alert alert-warning alert-dismissible fade show" role="alert">
         <b> ${resp}.</b>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>`)

        setTimeout(function () {
          $(`#alert_${miId}`).fadeOut(400, function () {
            $(this).remove(); // Elimina el elemento del DOM después de la animación de desvanecimiento
          });
        }, 6000); // 3000 milisegundos (3 segundos)

        btn_ejecutar.disabled = false;
        /*  Swal.fire({
           icon: "error",
           title: "Oops...",
           text: `${resp}`,
         }); */

      }


    },
    error: function (jqXHR, textStatus, errorThrown) {
      btn_ejecutar.disabled = false;
      handleError(jqXHR);
    },
  });
}

function enviarFormEfEx(miId) {
  const tipo_gasto = document.getElementById(`tipo_gasto_${miId}`);
  const folio = document.getElementById('folio').value; // Agregamos .value para obtener el valor
  const tipo = document.getElementById('type').value; // Agregamos .value para obtener el valor
  const politics_status = document.getElementById(`politics_status_${miId}`);
  const id_item = document.getElementById(`id_item_${miId}`);

  const btn_ejecutar = document.getElementById(`btn_comprobar_${miId}`);
  const files = document.getElementById(`upload_${miId}`);
  const files_data = document.querySelector(`#upload_${miId}`).files.length;

  const comprobar_monto = document.getElementById(`cantidad_${miId}`);
  const proveedor = document.getElementById(`visita_cliente_${miId}`);
  const proveedor2 = document.getElementById(`proveedor_${miId}`);
  const fecha_gasto = document.getElementById(`fecha_${miId}`);
  let error_prove='';

  //console.log("tipo proveedor1: ", proveedor.value);
  //console.log("tipo proveedor2: ", proveedor2.value);

  let proveedorValue = "";

if (proveedor && proveedor.value.length > 0) {
    proveedorValue = proveedor.value;
     error_prove = (proveedorValue.length === 0) ? "El campo es requerido" : '';
    console.log("tipo Aqui", error_prove);
    
    document.getElementById(`error_cliente_${miId}`).innerHTML = error_prove;


} else if (proveedor2 && proveedor2.value.length > 0) {
    proveedorValue = proveedor2.value;

     error_prove = (proveedorValue.length === 0) ? "El campo es requerido" : '';
    console.log("tipo Aqui", error_prove);
    
    document.getElementById(`error_prove_${miId}`).innerHTML = error_prove;

}



if (proveedorValue.length > 0) {
    if (proveedor) proveedor.classList.remove("has-error");
    if (proveedor2) proveedor2.classList.remove("has-error");
} else {
    if (proveedor) proveedor.classList.add("has-error");
    if (proveedor2) proveedor2.classList.add("has-error");
}


  const lugares = document.getElementById(`lugares_${miId}`);
  const cantidades = document.getElementById(`cantidades_${miId}`);
  const fechas = document.getElementById(`fechas_${miId}`);

  // const total_gastos = document.getElementById('total_gastos');

  console.log("files_data: ", files_data);
  const error_tipo_gasto = (tipo_gasto.value.length === 0) ? "El campo es requerido" : '';
  console.log("tipo", error_tipo_gasto);
  document.getElementById(`error_tipo_gasto_${miId}`).innerHTML = error_tipo_gasto;
  (error_tipo_gasto.length > 0) ? tipo_gasto.classList.add("has-error") : tipo_gasto.classList.remove("has-error");

  const error_cantidad = (comprobar_monto.value.length === 0) ? "El campo es requerido" : '';
  console.log("tipo", error_cantidad);
  document.getElementById(`error_cantidad_${miId}`).innerHTML = error_cantidad;
  (error_cantidad.length > 0) ? comprobar_monto.classList.add("has-error") : comprobar_monto.classList.remove("has-error");

   

  const error_fecha = (fecha_gasto.value.length === 0) ? "El campo es requerido" : '';
  console.log("tipo", error_fecha);
  document.getElementById(`error_fecha_${miId}`).innerHTML = error_fecha;
  (error_fecha.length > 0) ? fecha_gasto.classList.add("has-error") : fecha_gasto.classList.remove("has-error");


  const error_user_files = (files_data < 1) ? "El campo es requerido" : '';
  console.log("tipo_123", files_data);
  document.getElementById(`error_user_files_${miId}`).innerHTML = error_user_files;
  (error_user_files.length > 0) ? files.classList.add("has-error") : files.classList.remove("has-error");

  if (error_tipo_gasto !== '' || error_user_files !== '' || error_cantidad !== '' || error_prove !== '' || error_fecha !== '') {
    console.log("estoy aqui241");
    return false;
  }

  btn_ejecutar.disabled = true;

  var formData = new FormData();
  var files_list = files.files;

  for (var i = 0; i < files_list.length; i++) {
    formData.append('upload[]', files_list[i]);
  }

  formData.append('type', tipo); // Usamos la variable 'tipo' en lugar de 'tipo.value()'
  formData.append('folio', folio); // Usamos la variable 'folio' en lugar de 'folio.value()'
  formData.append('id_item', id_item.value);
  formData.append('cont', miId);
  formData.append('tipo_gasto', tipo_gasto.value);
  formData.append('politics_status', politics_status.value);
  formData.append('cantidad', comprobar_monto.value);
  //formData.append('proveedor', proveedor.value);
  formData.append('proveedor', proveedor?.value || proveedor2?.value);
  formData.append('fecha', fecha_gasto.value);

  formData.append('lugares', lugares.value);
  formData.append('cantidades', cantidades.value);
  formData.append('fechas', fechas.value);


  $.ajax({
    data: formData,
    url: `${urls}viajes/registrar-comprobantes_efEx`,
    type: "POST",
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (resp) {
      console.log(resp);
      if (resp === true) {
        btn_ejecutar.disabled = false;
        $(`#card_${miId}`).fadeOut(4000, function () {
          // Una vez que la animación haya terminado, elimina la tarjeta del DOM
          $(this).remove();
        });

      } else {
        $(`#error_${miId}`).append(`<div id="alert_${miId}" class="alert alert-warning alert-dismissible fade show" role="alert">
         <b> ${resp}.</b>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>`)

        setTimeout(function () {
          $(`#alert_${miId}`).fadeOut(400, function () {
            $(this).remove(); // Elimina el elemento del DOM después de la animación de desvanecimiento
          });
        }, 6000); // 3000 milisegundos (3 segundos)

        btn_ejecutar.disabled = false;
        /*  Swal.fire({
           icon: "error",
           title: "Oops...",
           text: `${resp}`,
         }); */

      }


    },
    error: function (jqXHR, textStatus, errorThrown) {
      handleError(jqXHR);
    },
  });
}


function enviarFormEfExGastos(miId) {
  const tipo_gasto = document.getElementById(`tipo_gasto_${miId}`);
  const folio = document.getElementById('folio').value; // Agregamos .value para obtener el valor
  const tipo = document.getElementById('type').value; // Agregamos .value para obtener el valor
  const politics_status = document.getElementById(`politics_status_${miId}`);
  const id_item = document.getElementById(`id_item_${miId}`);

  const btn_ejecutar = document.getElementById(`btn_comprobar_${miId}`);
  const files = document.getElementById(`upload_${miId}`);
  const files_data = document.querySelector(`#upload_${miId}`).files.length;

  const comprobar_monto = document.getElementById(`cantidad_${miId}`);
  const proveedor = document.getElementById(`proveedor_${miId}`);
  const fecha_gasto = document.getElementById(`fecha_${miId}`);

  const lugares = document.getElementById(`lugares_${miId}`);
  const cantidades = document.getElementById(`cantidades_${miId}`);
  const fechas = document.getElementById(`fechas_${miId}`);

  // const total_gastos = document.getElementById('total_gastos');

  console.log("files_data: ", files_data);
  const error_tipo_gasto = (tipo_gasto.value.length === 0) ? "El campo es requerido" : '';
  console.log("tipo", error_tipo_gasto);
  document.getElementById(`error_tipo_gasto_${miId}`).innerHTML = error_tipo_gasto;
  (error_tipo_gasto.length > 0) ? tipo_gasto.classList.add("has-error") : tipo_gasto.classList.remove("has-error");

  const error_cantidad = (comprobar_monto.value.length === 0) ? "El campo es requerido" : '';
  console.log("tipo", error_cantidad);
  document.getElementById(`error_cantidad_${miId}`).innerHTML = error_cantidad;
  (error_cantidad.length > 0) ? comprobar_monto.classList.add("has-error") : comprobar_monto.classList.remove("has-error");



  const error_fecha = (fecha_gasto.value.length === 0) ? "El campo es requerido" : '';
  console.log("tipo", error_fecha);
  document.getElementById(`error_fecha_${miId}`).innerHTML = error_fecha;
  (error_fecha.length > 0) ? fecha_gasto.classList.add("has-error") : fecha_gasto.classList.remove("has-error");


  const error_user_files = (files_data < 1) ? "El campo es requerido" : '';
  console.log("tipo_123", files_data);
  document.getElementById(`error_user_files_${miId}`).innerHTML = error_user_files;
  (error_user_files.length > 0) ? files.classList.add("has-error") : files.classList.remove("has-error");

  //|| error_fecha !== ''

  if (error_tipo_gasto !== '' || error_user_files !== '' || error_cantidad !== '') {
    console.log("estoy aqui241");
    return false;
  }

  btn_ejecutar.disabled = true;

  var formData = new FormData();
  var files_list = files.files;

  for (var i = 0; i < files_list.length; i++) {
    formData.append('upload[]', files_list[i]);
  }

  formData.append('type', tipo); // Usamos la variable 'tipo' en lugar de 'tipo.value()'
  formData.append('folio', folio); // Usamos la variable 'folio' en lugar de 'folio.value()'
  formData.append('id_item', id_item.value);
  formData.append('cont', miId);
  formData.append('tipo_gasto', tipo_gasto.value);
  formData.append('politics_status', politics_status.value);
  formData.append('cantidad', comprobar_monto.value);
  formData.append('fecha', fecha_gasto.value);

  formData.append('lugares', lugares.value);
  formData.append('cantidades', cantidades.value);
  formData.append('fechas', fechas.value);




  $.ajax({
    data: formData,
    url: `${urls}viajes/registrar-comprobantes_efExGastos`,
    type: "POST",
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (resp) {
      console.log(resp);
      if (resp === true) {
        btn_ejecutar.disabled = false;
        $(`#card_${miId}`).fadeOut(4000, function () {
          // Una vez que la animación haya terminado, elimina la tarjeta del DOM
          $(this).remove();
        });

      } else {
        $(`#error_${miId}`).append(`<div id="alert_${miId}" class="alert alert-warning alert-dismissible fade show" role="alert">
         <b> ${resp}.</b>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>`)

        setTimeout(function () {
          $(`#alert_${miId}`).fadeOut(400, function () {
            $(this).remove(); // Elimina el elemento del DOM después de la animación de desvanecimiento
          });
        }, 6000); // 3000 milisegundos (3 segundos)

        btn_ejecutar.disabled = false;
        /*  Swal.fire({
           icon: "error",
           title: "Oops...",
           text: `${resp}`,
         }); */

      }


    },
    error: function (jqXHR, textStatus, errorThrown) {
      handleError(jqXHR);
    },
  });
}



$('#to_upload').on('drop', function (e) {
  e.preventDefault();
  // $(this).removeClass('file_drag_over');
  const tipo_gasto = document.getElementById('tipo_gasto');
  const folio = document.getElementById('folio');
  const btn_ejecutar = document.getElementById('btn-ejecutar');
  const user_files = document.getElementById('userfile');
  const total_gastos = document.getElementById('total_gastos');
  const files_data = document.querySelector('#fileOutput').childNodes.length;


  const error_tipo_gasto = (tipo_gasto.value.length == 0) ? "El campo es requerido" : '';
  console.log("tipo", error_tipo_gasto);
  document.getElementById("error_tipo_gasto").innerHTML = error_tipo_gasto;
  (error_tipo_gasto.length > 0) ? tipo_gasto.classList.add("has-error") : tipo_gasto.classList.remove("has-error");

  const error_folio = (folio.value.length == 0) ? "El campo es requerido" : '';
  console.log("tipo", error_folio);
  document.getElementById("error_folio").innerHTML = error_folio;
  (error_folio.length > 0) ? folio.classList.add("has-error") : folio.classList.remove("has-error");


  if (error_tipo_gasto != '' || error_folio != '') {
    console.log("estoy aqui_241");
    document.getElementById("fileOutput").innerHTML = '';
    return false;
  }


  var formData = new FormData();
  var files_list = e.originalEvent.dataTransfer.files;

  for (var i = 0; i < files_list.length; i++) {
    formData.append('userfile[]', files_list[i]);
  }

  formData.append('tipo_gasto', tipo_gasto.value);
  formData.append('folio', folio.value);
  console.log("folio: ", folio.value);
  console.log("tipo gasto: ", tipo_gasto.value);
  $.ajax({
    data: formData,
    url: `${urls}viajes/registrar-comprobantes`,
    type: "POST",
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    dataType: "json",
    success: function (resp) {
      console.log("RESULTADO: ", resp.total_facturas);
      btn_ejecutar.disabled = false;
      total_facturas = new Intl.NumberFormat('es-MX').format(resp.total_facturas);
      if (Object.entries(resp).length != 0) {
        document.getElementById("result_total").innerHTML += '';
        document.getElementById("result_total").innerHTML += `<h3>Total Comprobado:</h3>
                                                              <h2> $${total_facturas}</h2>`;
        document.getElementById('form-validacion').reset();
        document.getElementById("fileOutput").innerHTML = '';

        Swal.fire(`!Se han Registrado las Facturas Correctamente la cantidad Total es: ${total_facturas} !`, "", "success");


      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
    error: function () {
      btn_ejecutar.disabled = false;
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
      btn_ejecutar.disabled = false;

    } else if (jqXHR.status == 404) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No se encontró la página solicitada [404]",
      });
      btn_ejecutar.disabled = false;
    } else if (jqXHR.status == 500) {

      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Internal Server Error [500]",
      });
      btn_ejecutar.disabled = false;
    } else if (textStatus === 'parsererror') {

      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Error de análisis JSON solicitado.",
      });
      btn_ejecutar.disabled = false;
    } else if (textStatus === 'timeout') {

      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Time out error.",
      });
      btn_ejecutar.disabled = false;
    } else if (textStatus === 'abort') {

      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ajax request aborted.",
      });

      btn_ejecutar.disabled = false;
    } else {

      alert('Uncaught Error: ' + jqXHR.responseText);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: `Uncaught Error: ${jqXHR.responseText}`,
      });
      btn_ejecutar.disabled = false;
    }
  });
});


// no react or anything
let state = {};

// state management
function updateState(newState) {
  state = { ...state, ...newState };

  console.log(state);
}

// event handlers
/* $("#upload").change(function(e) {
  let files = document.getElementsByTagName("input")[0].files;
  let filesArr = Array.from(files);
  updateState({ files: files, filesArr: filesArr });

  renderFileList();
}); */

/*  $("#upload").change(function (e) {
  var miIds = e.getAttribute("data-id");
  fileFacturas(e,miIds)

}

); */

function Taxi(miId) {

  var checkbox = document.getElementById(`taxi_${miId}`);
  //var div = document.getElementById(`nota_credito_${miId}`).hasChildNodes();
  if (checkbox.checked) {

    console.log("El checkbox está activo (marcado TAXI).");
    $(`#nota_credito_${miId}`).append(`<div class="col-md-4">
     <div class="row">
     <div class="input-group-sm col-md-6">
       
           <input type="number" 
                   id="cantidad_${miId}" 
                   name="cantidad_${miId}"                  
                   class="form-control"
                   placeholder="Total"/>
                   <div id="error_cantidad_${miId}" class="text-danger"></div>
        
     </div>
     <div class="input-group-sm col-md-6">
       
           <input type="date" 
                   id="fecha_${miId}" 
                   name="fecha_${miId}"                  
                   class="form-control"
                   placeholder="Fecha"/>
                   <div id="error_fecha_${miId}" class="text-danger"></div>
        
     </div>
     </div>
 
   </div>`);
    $(`#tipo_archivo_${miId}`).empty();
    $(`#tipo_archivo_${miId}`).append(`
    <div class="mr-2">
      <label id="up_file" class="up_file" for="upload_${miId}">
        <input type="file" id="upload_${miId}" name="upload_${miId}" data-id="${miId}" onchange="fileEfectivo(this)"
          accept=".pdf" class="btn" />
        Elige PDF
      </label>
    </div>`);


  } else {
    $(`#nota_credito_${miId}`).empty();
    $(`#tipo_archivo_${miId}`).empty();
    $(`#tipo_archivo_${miId}`).append(`
    <div class="mr-2">
    <label id="up_file" class="up_file" for="upload_${miId}">
    <input type="file" 
            id="upload_${miId}" 
            name="upload_${miId}" 
            data-id="${miId}"
            onchange="fileFacturas(this)"
            accept=".pdf, .xml" 
            multiple 
            class="btn"/>
    Elige PDF & XML
  </label>
    </div>`);
  }
}

function Propinas(miId) {

  var checkbox = document.getElementById(`propina_${miId}`);
  var div = document.getElementById(`nota_credito_${miId}`).hasChildNodes();
  if (checkbox.checked && !(div)) {

    console.log("El checkbox está activo (marcado).");

    $(`#nota_credito_${miId}`).append(`<div class="col-md-5">
                                          <div class="row">
                                            <div class="input-group-sm col-md-5">
                                            <select name="propinas_${miId}" id="propinas_${miId}" class="form-control">
                                              <option value="">Monto Propina...</option>
                                              <option value="10">10%</option>
                                              <option value="15">15%</option>
                                            </select>
                                            <div id="error_propinas_${miId}" class="text-danger"></div>
                                            </div>
                                          </div>
                                        </div>`);

  } else {
    console.log("El checkbox no está activo (desmarcado).");
    $(`#nota_credito_${miId}`).empty();
  }
}


function miNota(miId) {

  var checkbox = document.getElementById(`nota_${miId}`);
  var div = document.getElementById(`nota_credito_${miId}`).hasChildNodes();
  if (checkbox.checked && !(div)) {

    console.log("El checkbox está activo (marcado).");

    $(`#nota_credito_${miId}`).append(`<div class="col-md-5">
    <div class="row">
      <div>
        <label id="up_file" class="up_file" for="upload_nota_${miId}">
          <input type="file" 
                  id="upload_nota_${miId}" 
                  name="upload_nota_${miId}" 
                  data-id="${miId}"
                  onchange="fileNotas(this)"
                  accept=".pdf, .xml" 
                  multiple 
                  class="btn"/>
          Elige NOTA & XML
        </label>
      </div>
      <div id="nota_selected_${miId}" class="files">

        <ul id="lista_nota_${miId}" class="horizontal-list"></ul>
      </div>

      <div id="error_user_nota_${miId}" class="text-danger"></div>


    </div>

  </div>`);

  } else {
    console.log("El checkbox no está activo (desmarcado).");
    $(`#nota_credito_${miId}`).empty();
  }
}


function facExtras(e) {

  var miId = e.getAttribute("data-id");
  console.log("facExtra", miId);

  if ($("#type").val() == 1) {
    $(`#extras_${miId}`).empty();
    $(`#extras_${miId}`).removeClass("col-md-2");
    $(`#extras_${miId}`).removeClass("col-md-4");
    $(`#nota_credito_${miId}`)
    var select = parseInt(document.getElementById(`tipo_gasto_${miId}`).value);
    console.log("select", select);
    if (select === 19) {
      $(`#extras_${miId}`).addClass("col-md-2");
      $(`#extras_${miId}`).append(`<div class="form-check form-switch">
    <label for="nota_${miId}">
    <input class="sr-only" type="checkbox" id="nota_${miId}" name="nota_${miId}" onchange="miNota(${miId})" />
    <div class="slider"></div>
    <span class="label">Nota de Credito</span>
  </label>
</div>`)
      $(`#tipo_archivo_${miId}`).empty();
      $(`#tipo_archivo_${miId}`).append(`
    <label id="up_file" class="up_file" for="upload_${miId}">
      <input type="file" 
              id="upload_${miId}" 
              name="upload_${miId}" 
              data-id="${miId}"
              onchange="fileFacturas(this)"
              accept=".pdf, .xml" 
              multiple 
              class="btn"/>
      Elige PDF & XML
    </label>`)
    } else if (select === 21) {
      $(`#extras_${miId}`).addClass("col-md-4");
      $(`#extras_${miId}`).append(` <div class="row">
    <div class="input-group-sm col-md-6">
    <input type ="number" class="form-control" name="cantidad_${miId}" id="cantidad_${miId}" placeholder="TOTAL" />
    <div id="error_cantidad_${miId}" class="text-danger"></div>
    </div>
    <div class="input-group-sm col-md-6">
    <input type ="date" class="form-control" name="fecha_${miId}" id="fecha_${miId}" placeholder="Fecha" />
    <div id="error_fecha_${miId}" class="text-danger"></div>
    </div></div>`)
      $(`#tipo_archivo_${miId}`).empty();
      $(`#tipo_archivo_${miId}`).append(`
    <div class="mr-2">
      <label id="up_file" class="up_file" for="upload_${miId}">
        <input type="file" id="upload_${miId}" name="upload_${miId}" data-id="${miId}" onchange="fileEfectivo(this)"
          accept=".pdf" class="btn" />
        Elige PDF
      </label>
    </div>`);
    } else if (select === 18) {
      $(`#extras_${miId}`).addClass("col-md-2");
      $(`#extras_${miId}`).append(`<div class="form-check form-switch">
    <label for="propina_${miId}">
    <input class="sr-only" type="checkbox" id="propina_${miId}" name="propina_${miId}" onchange="Propinas(${miId})" />
    <div class="slider"></div>
    <span class="label">Propina</span>
  </label>
  </div>`)
      $(`#tipo_archivo_${miId}`).empty();
      $(`#tipo_archivo_${miId}`).append(`
    <label id="up_file" class="up_file" for="upload_${miId}">
      <input type="file" 
              id="upload_${miId}" 
              name="upload_${miId}" 
              data-id="${miId}"
              onchange="fileFacturas(this)"
              accept=".pdf, .xml" 
              multiple 
              class="btn"/>
      Elige PDF & XML
    </label>`)
    } else if (select === 17) {
      $(`#extras_${miId}`).addClass("col-md-4");
      $(`#extras_${miId}`).append(` <div class="row">
    <div class="input-group-sm col-md-6">
    <select name="iva_${miId}" id="iva_${miId}" class="form-control">
      <option value="">IVA...</option>
      <option value="16">16%</option>
      <option value="8">8%</option>
      <option value="0">0%</option>
    </select>
    <div id="error_iva_${miId}" class="text-danger"></div>
    </div>
    <div class="input-group-sm col-md-6">
    <input type ="number" class="form-control" name="iva_monto_${miId}" id="iva_monto_${miId}" placeholder="Colocar Monto de IVA..." />
    <div id="error_iva_monto_${miId}" class="text-danger"></div>
    </div></div>`)
      $(`#tipo_archivo_${miId}`).empty();
      $(`#tipo_archivo_${miId}`).append(`
    <label id="up_file" class="up_file" for="upload_${miId}">
      <input type="file" 
              id="upload_${miId}" 
              name="upload_${miId}" 
              data-id="${miId}"
              onchange="fileFacturas(this)"
              accept=".pdf, .xml" 
              multiple 
              class="btn"/>
      Elige PDF & XML
    </label>`)
    } else if (select === 16) {
      $(`#extras_${miId}`).addClass("col-md-2");
      $(`#extras_${miId}`).append(`<div class="form-check form-switch">
    <label for="propina_${miId}">
    <input class="sr-only" type="checkbox" id="propina_${miId}" name="propina_${miId}" onchange="Propinas(${miId})" />
    <div class="slider"></div>
    <span class="label">Propina</span>
  </label>

  </div>`)
      $(`#tipo_archivo_${miId}`).empty();
      $(`#tipo_archivo_${miId}`).append(`
    <label id="up_file" class="up_file" for="upload_${miId}">
      <input type="file" 
              id="upload_${miId}" 
              name="upload_${miId}" 
              data-id="${miId}"
              onchange="fileFacturas(this)"
              accept=".pdf, .xml" 
              multiple 
              class="btn"/>
      Elige PDF & XML
    </label>`)
    } else {
      $(`#extras_${miId}`).empty();
      $(`#extras_${miId}`).removeClass("col-md-2");
      $(`#extras_${miId}`).removeClass("col-md-4");


    }

  } else {

    $(`#extras_${miId}`).empty();
    $(`#extras_${miId}`).removeClass("col-md-2");
    $(`#extras_${miId}`).removeClass("col-md-4");
    $(`#nota_credito_${miId}`)
    var select = parseInt(document.getElementById(`tipo_gasto_${miId}`).value);
    console.log("select", select);
    if (select === 19) {
      $(`#extras_${miId}`).addClass("col-md-2");
      $(`#extras_${miId}`).append(`<div class="form-check form-switch">
    <label for="nota_${miId}">
    <input class="sr-only" type="checkbox" id="nota_${miId}" name="nota_${miId}" onchange="miNota(${miId})" />
    <div class="slider"></div>
    <span class="label">Nota de Credito</span>
  </label>
</div>`)
    } else if (select === 22) {
      $(`#extras_${miId}`).addClass("col-md-4");
      $(`#extras_${miId}`).append(` <div class="row">
    <div class="input-group-sm col-md-6">
    <input type ="number" class="form-control" name="cantidad_${miId}" id="cantidad_${miId}" placeholder="TOTAL" />
    <div id="error_cantidad_${miId}" class="text-danger"></div>
    </div>
    <div class="input-group-sm col-md-6">
    <input type ="date" class="form-control" name="fecha_${miId}" id="fecha_${miId}" placeholder="Fecha" />
    <div id="error_fecha_${miId}" class="text-danger"></div>
    </div></div>`)
      $(`#tipo_archivo_${miId}`).empty();
      $(`#tipo_archivo_${miId}`).append(`
    <div class="mr-2">
      <label id="up_file" class="up_file" for="upload_${miId}">
        <input type="file" id="upload_${miId}" name="upload_${miId}" data-id="${miId}" onchange="fileEfectivo(this)"
          accept=".pdf" class="btn" />
        Elige PDF
      </label>
    </div>`);
    } else if (select === 18) {
      $(`#extras_${miId}`).addClass("col-md-2");
      $(`#extras_${miId}`).append(`<div class="form-check form-switch">
    <label for="propina_${miId}">
    <input class="sr-only" type="checkbox" id="propina_${miId}" name="propina_${miId}" onchange="Propinas(${miId})" />
    <div class="slider"></div>
    <span class="label">Propina</span>
  </label>

  </div>`)
    } else if (select === 1) {
      $(`#extras_${miId}`).addClass("col-md-2");
      $(`#extras_${miId}`).append(`<div class="form-check form-switch">
    <label for="taxi_${miId}">
    <input class="sr-only" type="checkbox" id="taxi_${miId}" name="taxi_${miId}" onchange="Taxi(${miId})" />
    <div class="slider"></div>
    <span class="label">Vale Azul</span>
  </label>

  </div>`)
    } else if (select === 8 || select === 12) {
      $(`#extras_${miId}`).addClass("col-md-4");
      $(`#extras_${miId}`).append(` <div class="row">
    <div class="input-group-sm col-md-6">
    <select name="iva_${miId}" id="iva_${miId}" class="form-control">
      <option value="">IVA...</option>
      <option value="16">16%</option>
      <option value="8">8%</option>
      <option value="0">0%</option>
    </select>
    <div id="error_iva_${miId}" class="text-danger"></div>
    </div>
    <div class="input-group-sm col-md-6">
    <input type ="number" class="form-control" name="iva_monto_${miId}" id="iva_monto_${miId}" placeholder="Colocar Monto de IVA..."/>
    <div id="error_iva_monto_${miId}" class="text-danger"></div>
    </div></div>`)
    } else if (select === 16 || select === 6) {
      $(`#extras_${miId}`).addClass("col-md-2");
      $(`#extras_${miId}`).append(`<div class="form-check form-switch">
    <label for="propina_${miId}">
    <input class="sr-only" type="checkbox" id="propina_${miId}" name="propina_${miId}" onchange="Propinas(${miId})" />
    <div class="slider"></div>
    <span class="label">Propina</span>
  </label>

  </div>`)
    } else {
      $(`#extras_${miId}`).empty();
      $(`#extras_${miId}`).removeClass("col-md-2");
      $(`#extras_${miId}`).removeClass("col-md-4");
      $(`#tipo_archivo_${miId}`).empty();
      $(`#tipo_archivo_${miId}`).append(`
    <label id="up_file" class="up_file" for="upload_${miId}">
      <input type="file" 
              id="upload_${miId}" 
              name="upload_${miId}" 
              data-id="${miId}"
              onchange="fileFacturas(this)"
              accept=".pdf, .xml" 
              multiple 
              class="btn"/>
      Elige PDF & XML
    </label>`)
    }
  }


}

function fileFacturas(e) {
  var miId = e.getAttribute("data-id");
  //console.log("miId1",e); 
  // Obtén una referencia al elemento <input> por su ID
  var input = document.getElementById(`upload_${miId}`);



  let files = input.files;

  if (files.length > 2) {

    $(`#error_${miId}`).append(`<div id="alert_${miId}" class="alert alert-warning alert-dismissible fade show" role="alert">
         <b> Por favor, seleccione solo hasta 2 archivos.</b>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>`)

    setTimeout(function () {
      $(`#alert_${miId}`).fadeOut(400, function () {
        $(this).remove(); // Elimina el elemento del DOM después de la animación de desvanecimiento
      });
    }, 3500); // 3000 milisegundos (3 segundos)

    btn_ejecutar.disabled = false;
    this.value = ''; // Limpia la selección de archivos
    return
  }


  var xmlSelected = false;
  var pdfSelected = false;

  for (var i = 0; i < files.length; i++) {
    var file = files[i];
    if (file.name.endsWith('.xml')) {
      xmlSelected = true;
    } else if (file.name.endsWith('.pdf')) {
      pdfSelected = true;
    }
  }

  if (!(xmlSelected && pdfSelected)) {

    $(`#error_${miId}`).append(`<div id="alert_${miId}" class="alert alert-warning alert-dismissible fade show" role="alert">
       <b>Por favor, seleccione un archivo XML y un archivo PDF.</b>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>`)
    this.value = ''; // Limpia la selección de archivos
    setTimeout(function () {
      $(`#alert_${miId}`).fadeOut(400, function () {
        $(this).remove(); // Elimina el elemento del DOM después de la animación de desvanecimiento
      });
    }, 3500); // 3000 milisegundos (3 segundos)
    return
  }


  let filesArr = Array.from(files);
  let Subtotal = 0;
  let Iva = 0;
  let Total = 0;
  let subtotal_acumulado = 0;
  let iva_acumulado = 0;
  let total_acumulado = 0;
  let count = 0;
  updateState({ files: files, filesArr: filesArr });

  renderFileList(miId, 1);

  // Obtén la lista de archivos seleccionados
  //var files = this.files;

  var resultado = $(`#resultado_${miId}`);
  resultado.empty();
  console.log("miId", miId);
  resultado.append(` <table class="table table-lg table-hover table-striped table-dark tabla-pequena">
    <thead>
        <tr>
            <th class="text-center">CFDI</th>
            <th class="text-center">Serie & Folio</th>
            <th class="text-center">Razón Social</th>
            <th class="text-center">RFC</th>
            <th class="text-center">Fecha Factura</th>
            <th class="text-center">Subtotal</th>
            <th class="text-center">IVA</th>
            <th class="text-center">Total</th>
        </tr>
    </thead>
    <tbody id="datos_${miId}">
        
        
    </tbody>
  </table>`);

  var res_total = $(`#datos_totales`);
  res_total.empty();

  res_total.append(` <table class="table table-striped table-dark tabla-pequena">
    <thead>
        <tr>
            <th class="text-center">SUBTOTAL</th>
            <th class="text-center">IVA</th>
            <th class="text-center">TOTAL</th>
        </tr>
    </thead>
    <tbody id="totales_datos">
        
        
    </tbody>
  </table>`);

  let archivosProcesados = 0;
  let archivosXML = 0; // Variable para contar archivos XML
  let archivosXML2 = 0;
  // Itera a través de cada archivo seleccionado
  for (var i = 0; i < files.length; i++) {
    var file = files[i];

    // Solo itera sobre los archivos XML
    if (file.type === "text/xml") {
      archivosXML++
    }
  }



  for (var i = 0; i < files.length; i++) {
    var file = files[i];

    var reader = new FileReader();

    reader.onload = (function (file, index) {

      return function (e) {
        console.log("index: ", index);

        var contenido = e.target.result;
        var factura_nombre = file.name;
        var nombre_fac = factura_nombre.replace(/\s+/g, '');
        // Detecta si el archivo es XML o PDF
        if (file.type === "text/xml") {
          archivosProcesados++;
          // Incrementa el contador de archivos XML
          // Si es XML, parsea el contenido XML
          var parser = new DOMParser();
          var xmlDoc = parser.parseFromString(contenido, "text/xml");
          console.log("estoy aqui 2");
          // Procesa los datos XML y muéstralos en #resultado
          mostrarDatos(xmlDoc, miId);


          var formData = new FormData();
          // var files_list = e.originalEvent.dataTransfer.files;

          // for (var i = 0; i < files_list.length; i++) {
          formData.append('userfile[]', file);
          //  }

          $.ajax({
            data: formData,
            url: `${urls}viajes/revisar-comprobantes`,
            type: "POST",
            processData: false, // dile a jQuery que no procese los datos
            contentType: false, // dile a jQuery que no establezca contentType
            dataType: "json",
            success: function (resp) {
              /* console.log("RESULTADO: ", resp.total_facturas);
              total_facturas = new Intl.NumberFormat('es-MX').format(resp.total_facturas); */
              archivosXML2++
              if (Object.entries(resp).length != 0) {
                var monto_sub_total = $("#subtotal_acumulado").val();
                var monto_iva = $("#iva_acumulado").val();
                var monto_total = $("#total_acumulado").val();
                console.log("monto_sub_total", monto_sub_total);
                let Nombre = resp.nombre_proveedor;
                let RFC = resp.rfc;
                // Parsea la fecha usando Moment.js
                //const Fecha = moment(resp.fecha_factura).format('DD/MM/YYYY HH:mm:ss');
                const Fecha = moment(resp.fecha_factura).format('DD/MM/YYYY');

                Subtotal = parseFloat(resp.sub_total);

                console.log("Subtotal:", Subtotal);
                Iva = parseFloat(resp.iva);

                let validar_iva = Iva / 0.16;
                let validar_iva2 = Subtotal + validar_iva;
                console.log("iva2", validar_iva);
                Total = resp.total;

                console.log("total", validar_iva);
                let cfdi = resp.version_cfdi;
                let Folio = resp.folio;
                subtotal_acumulado = Subtotal
                iva_acumulado = Iva
                total_acumulado = Total




                const table = $(`<tr id="fact_${nombre_fac}">
                                              <td class="text-center">${cfdi}</td>
                                              <td class="text-center">${Folio}</td>
                                              <td class="text-center">${Nombre}</td>
                                              <td class="text-center">${RFC}</td>
                                              <td class="text-center">${Fecha}</td>
                                              <td class="text-center">$${Subtotal.toLocaleString()}</td>
                                              <td class="text-center">$${Iva.toLocaleString()}</td>
                                              <td class="text-center">$${Total.toLocaleString()}</td>
                                            </tr>`);
                $(`#datos_${miId}`).append(table);

                // Aplica el efecto fadeIn al nuevo elemento
                table.hide().fadeIn();

                // Swal.fire(`!Se han Registrado las Facturas Correctamente la cantidad Total es: ${total_facturas} !`, "", "success");

                $('#subtotal_acumulado').val(subtotal_acumulado.toFixed(2))
                $('#iva_acumulado').val(iva_acumulado.toFixed(2))
                $('#total_acumulado').val(total_acumulado.toFixed(2))

                // Convierte el array en una cadena JSON
                var miArrayJSON = JSON.stringify(resp);
                var facturas = "facturas" + index;
                // Almacena la cadena JSON en sessionStorage
                sessionStorage.setItem(facturas, miArrayJSON);

                console.log("var1", archivosXML);
                console.log("var2", archivosXML2);
                if (archivosXML2 === archivosXML) {
                  let sub = parseFloat($("#subtotal_acumulado").val())
                  let iva = parseFloat($("#iva_acumulado").val())
                  let total = parseFloat($("#total_acumulado").val())
                  const nuevoTable = $(`<tr>
                                          <td id="suber" class="text-center"><h4>$${sub.toLocaleString()}</h4></td>
                                          <td class="text-center"><h4>$${iva.toLocaleString()}</h4></td>
                                          <td class="text-center"><h4>$${total.toLocaleString()}</h4></td>
                                        </tr>`);
                  $(`#totales_datos`).append(nuevoTable);

                  // Aplica el efecto fadeIn al nuevo elemento
                  nuevoTable.hide().fadeIn();

                }

              } else {
                Swal.fire({
                  icon: "error",
                  title: "Oops...",
                  text: "Algo salió Mal! Contactar con el Administrador",
                });
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

        }

      }


    })(file, i);

    reader.readAsDataURL(file);


  }


}

function fileNotas(e) {
  var miId = e.getAttribute("data-id");
  //console.log("miId1",e); 
  // Obtén una referencia al elemento <input> por su ID
  var input = document.getElementById(`upload_nota_${miId}`);



  let files = input.files;

  if (files.length > 2) {

    $(`#error_${miId}`).append(`<div id="alert_${miId}" class="alert alert-warning alert-dismissible fade show" role="alert">
         <b> Por favor, seleccione solo hasta 2 archivos.</b>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>`)

    setTimeout(function () {
      $(`#alert_${miId}`).fadeOut(150, function () {
        $(this).remove(); // Elimina el elemento del DOM después de la animación de desvanecimiento
      });
    }, 3000); // 3000 milisegundos (3 segundos)

    btn_ejecutar.disabled = false;
    this.value = ''; // Limpia la selección de archivos
    return
  }


  var xmlSelected = false;
  var pdfSelected = false;

  for (var i = 0; i < files.length; i++) {
    var file = files[i];
    if (file.name.endsWith('.xml')) {
      xmlSelected = true;
    } else if (file.name.endsWith('.pdf')) {
      pdfSelected = true;
    }
  }

  if (!(xmlSelected && pdfSelected)) {

    $(`#error_${miId}`).append(`<div id="alert_${miId}" class="alert alert-warning alert-dismissible fade show" role="alert">
       <b>Por favor, seleccione un archivo XML y un archivo PDF.</b>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>`)
    this.value = ''; // Limpia la selección de archivos
    setTimeout(function () {
      $(`#alert_${miId}`).fadeOut(400, function () {
        $(this).remove(); // Elimina el elemento del DOM después de la animación de desvanecimiento
      });
    }, 4000); // 3000 milisegundos (3 segundos)
    return
  }


  let filesArr = Array.from(files);
  let Subtotal = 0;
  let Iva = 0;
  let Total = 0;
  let subtotal_acumulado = 0;
  let iva_acumulado = 0;
  let total_acumulado = 0;
  let count = 0;
  updateState({ files: files, filesArr: filesArr });

  renderFileListN(miId);


  let archivosProcesados = 0;
  let archivosXML = 0; // Variable para contar archivos XML
  let archivosXML2 = 0;
  // Itera a través de cada archivo seleccionado
  for (var i = 0; i < files.length; i++) {
    var file = files[i];

    // Solo itera sobre los archivos XML
    if (file.type === "text/xml") {
      archivosXML++
    }
  }



  for (var i = 0; i < files.length; i++) {
    var file = files[i];

    var reader = new FileReader();

    reader.onload = (function (file, index) {

      return function (e) {
        console.log("index: ", index);

        var contenido = e.target.result;
        var factura_nombre = file.name;
        var nombre_fac = factura_nombre.replace(/\s+/g, '');
        // Detecta si el archivo es XML o PDF
        if (file.type === "text/xml") {
          archivosProcesados++;
          // Incrementa el contador de archivos XML
          // Si es XML, parsea el contenido XML
          var parser = new DOMParser();
          var xmlDoc = parser.parseFromString(contenido, "text/xml");
          console.log("estoy aqui 2");
          // Procesa los datos XML y muéstralos en #resultado
          mostrarDatos(xmlDoc, miId);


          var formData = new FormData();
          // var files_list = e.originalEvent.dataTransfer.files;

          // for (var i = 0; i < files_list.length; i++) {
          formData.append('userfile[]', file);
          //  }

          $.ajax({
            data: formData,
            url: `${urls}viajes/revisar-comprobantes`,
            type: "POST",
            processData: false, // dile a jQuery que no procese los datos
            contentType: false, // dile a jQuery que no establezca contentType
            dataType: "json",
            success: function (resp) {
              /* console.log("RESULTADO: ", resp.total_facturas);
              total_facturas = new Intl.NumberFormat('es-MX').format(resp.total_facturas); */
              archivosXML2++
              if (Object.entries(resp).length != 0) {
                var monto_sub_total = $("#subtotal_acumulado").val();
                var monto_iva = $("#iva_acumulado").val();
                var monto_total = $("#total_acumulado").val();
                console.log("monto_sub_total", monto_sub_total);
                let Nombre = resp.nombre_proveedor;
                let RFC = resp.rfc;
                // Parsea la fecha usando Moment.js
                //const Fecha = moment(resp.fecha_factura).format('DD/MM/YYYY HH:mm:ss');
                const Fecha = moment(resp.fecha_factura).format('DD/MM/YYYY');

                Subtotal = parseFloat(resp.sub_total);

                console.log("Subtotal:", Subtotal);
                Iva = parseFloat(resp.iva);

                let validar_iva = Iva / 0.16;
                let validar_iva2 = Subtotal + validar_iva;
                console.log("iva2", validar_iva);
                Total = resp.total;

                console.log("total", validar_iva);
                let cfdi = resp.version_cfdi;
                let Folio = resp.folio;
                subtotal_acumulado = Subtotal
                iva_acumulado = Iva
                total_acumulado = Total




                const table = $(`<tr id="fact_${nombre_fac}">
                                              <td class="text-center">${cfdi}</td>
                                              <td class="text-center">${Folio}</td>
                                              <td class="text-center">${Nombre}</td>
                                              <td class="text-center">${RFC}</td>
                                              <td class="text-center">${Fecha}</td>
                                              <td class="text-center">$${Subtotal.toLocaleString()}</td>
                                              <td class="text-center">$${Iva.toLocaleString()}</td>
                                              <td class="text-center">$${Total.toLocaleString()}</td>
                                            </tr>`);
                $(`#datos_${miId}`).append(table);

                // Aplica el efecto fadeIn al nuevo elemento
                table.hide().fadeIn();

                // Swal.fire(`!Se han Registrado las Facturas Correctamente la cantidad Total es: ${total_facturas} !`, "", "success");

                $('#subtotal_acumulado').val(subtotal_acumulado.toFixed(2))
                $('#iva_acumulado').val(iva_acumulado.toFixed(2))
                $('#total_acumulado').val(total_acumulado.toFixed(2))

                // Convierte el array en una cadena JSON
                var miArrayJSON = JSON.stringify(resp);
                var facturas = "facturas" + index;
                // Almacena la cadena JSON en sessionStorage
                sessionStorage.setItem(facturas, miArrayJSON);

                console.log("var1", archivosXML);
                console.log("var2", archivosXML2);
                if (archivosXML2 === archivosXML) {
                  let sub = parseFloat($("#subtotal_acumulado").val())
                  let iva = parseFloat($("#iva_acumulado").val())
                  let total = parseFloat($("#total_acumulado").val())
                  const nuevoTable = $(`<tr>
                                          <td id="suber" class="text-center"><h4>$${sub.toLocaleString()}</h4></td>
                                          <td class="text-center"><h4>$${iva.toLocaleString()}</h4></td>
                                          <td class="text-center"><h4>$${total.toLocaleString()}</h4></td>
                                        </tr>`);
                  $(`#totales_datos`).append(nuevoTable);

                  // Aplica el efecto fadeIn al nuevo elemento
                  nuevoTable.hide().fadeIn();

                }

              } else {
                Swal.fire({
                  icon: "error",
                  title: "Oops...",
                  text: "Algo salió Mal! Contactar con el Administrador",
                });
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

        }

      }


    })(file, i);

    reader.readAsDataURL(file);


  }


}

function fileEfectivo(e) {
  var miId = e.getAttribute("data-id");
  //console.log("miId1",e); 
  // Obtén una referencia al elemento <input> por su ID
  var input = document.getElementById(`upload_${miId}`);

  let files = input.files;
  let filesArr = Array.from(files);
  let Subtotal = 0;
  let Iva = 0;
  let Total = 0;
  let subtotal_acumulado = 0;
  let iva_acumulado = 0;
  let total_acumulado = 0;
  let count = 0;
  updateState({ files: files, filesArr: filesArr });

  renderFileList(miId, 2);


  console.log("miId", miId);


  var res_total = $(`#datos_totales`);
  res_total.empty();

  res_total.append(` <table class="table table-striped table-dark tabla-pequena">
    <thead>
        <tr>
            <th class="text-center">SUBTOTAL</th>
            <th class="text-center">IVA</th>
            <th class="text-center">TOTAL</th>
        </tr>
    </thead>
    <tbody id="totales_datos">
        
        
    </tbody>
  </table>`);




}

function mostrarDatos(xmlDoc, miId) {
  // Itera a través de los elementos del XML y muestra los datos
  console.log(xmlDoc);
  $(xmlDoc).find("Comprobante").each(function () {
    var Nombre = $(this).find("Nombre").text();
    var RFC = $(this).find("Rfc").text();

    $(`#resultado_${miId}`).append("<p>Razon Social: " + Nombre + ", RFC: " + RFC + "</p>");
  });
}

$(".files").on("click", "li > i", function (e) {
  let key = $(this).parent().attr("key");
  console.log("keys:", key);
  //let curArr = state.filesArr;
  let curArr = state.filesArr.slice(); // Copiamos el arreglo para evitar modificar el original
  curArr.splice(key, 1); // Elimina el archivo del arreglo
  updateState({ filesArr: curArr }); // Actualiza el estado
  // Llama a fileFacturas después de eliminar el archivo
  renderFileList();
  fileFacturas({ target: { files: curArr } }); // Simula un evento con los archivos restantes

  // Selecciona el elemento de lista que deseas eliminar
  let listItemToRemove = $(this).parent();

  // Elimina el elemento de lista del DOM
  listItemToRemove.remove();
  // Obtén el valor del atributo data-id del elemento li
  let id = $(this).parent().data("id");
  console.log("id", id);
  // Actualiza la lista de archivos en el input file
  let inputFile = $(`#upload_${id}`);
  let filesArr = inputFile[0].files;
  filesArr = Array.from(filesArr);
  filesArr.splice(key, 1);
  inputFile[0].files = new FileList(filesArr, { type: '/*' });

  // Llama a la función para procesar los archivos actualizados
  fileFacturas(filesArr);



});


// render functions
function renderFileList(miId = 0, tipoForm) {
  let fileMap = state.filesArr.map((file, index) => {
    let suffix = "bytes";
    let size = file.size;
    if (size >= 1024 && size < 1024000) {
      suffix = "KB";
      size = Math.round(size / 1024 * 100) / 100;
    } else if (size >= 1024000) {
      suffix = "MB";
      size = Math.round(size / 1024000 * 100) / 100;
    }
    //<i class="fas fa-trash-alt"></i>
    return `<li key="${index}" data-id="${miId}">${file.name} <span class="file-size">${size} ${suffix}</span>`;
  });
  $(`ul#lista_facturas_${miId}`).html(fileMap);
  $(`#btn_submit_${miId}`).empty();
  if ($("#type").val() == 1) {
    let comprobar = (tipoForm == 1) ? `onclick="enviarFormulario(${miId})"` : `onclick="enviarFormEfEx(${miId})"`;
    $(`#btn_submit_${miId}`).append(`<button type="button" id="btn_comprobar_${miId}" class="btn btn-light" style="border-radius:1.25rem" ${comprobar} >Comprobar gasto</button>`);
  } else {
    let comprobar = (tipoForm == 1) ? `onclick="enviarFormularioGastos(${miId})"` : `onclick="enviarFormEfExGastos(${miId})"`;
    $(`#btn_submit_${miId}`).append(`<button type="button" id="btn_comprobar_${miId}" class="btn btn-light" style="border-radius:1.25rem" ${comprobar} >Comprobar gasto</button>`);
  }


}

function renderFileListN(miId = 0) {
  let fileMap = state.filesArr.map((file, index) => {
    let suffix = "bytes";
    let size = file.size;
    if (size >= 1024 && size < 1024000) {
      suffix = "KB";
      size = Math.round(size / 1024 * 100) / 100;
    } else if (size >= 1024000) {
      suffix = "MB";
      size = Math.round(size / 1024000 * 100) / 100;
    }
    //<i class="fas fa-trash-alt"></i>
    return `<li key="${index}" data-id="${miId}">${file.name} <span class="file-size">${size} ${suffix}</span>`;
  });
  $(`ul#lista_nota_${miId}`).html(fileMap);


}


$(document).on('change', '.form-check-input', function () {
  var codigo = $(this).attr('codigo');
  console.log("this: ", codigo);
  // Verificar si el radio button seleccionado es el que activa el input
  if ($(this).val() === "2") {
    // Mostrar el input si se selecciona la opción 1
    $(`#inputOculto_${codigo}`).show();
  } else {
    // Ocultar el input si se selecciona otra opción
    $(`#inputOculto_${codigo}`).hide();
  }
});

function validateInputLength(input) {
  // Asegurarse de que el valor sea mayor o igual a 0
  if (input.value < 0) {
      input.value = 0;
  }

  // Limitar a 9 dígitos
  if (input.value.length > 9) {
      input.value = input.value.slice(0, 9);
  }
}











