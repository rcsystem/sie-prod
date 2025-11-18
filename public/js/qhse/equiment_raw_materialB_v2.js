/**
 * ARCHIVO MODULO AlMACEN
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */


$("#form_items").submit(function (e) {
  e.preventDefault();
   // Cerrar modal de Bootstrap
   $('#itemModal').modal('hide');
  Swal.fire({
    title: 'CLAVE DE SEGURIDAD',
    input: 'text',
    inputAttributes: {
      autocapitalize: 'off'
    },
    showCancelButton: true,
    confirmButtonText: 'Continuar',
    cancelButtonText: 'Cancelar',
    showLoaderOnConfirm: true,
    preConfirm: (nombre) => {
      if (!nombre) {
        Swal.showValidationMessage('Campo Obligatorio');
      }
      // Aquí puedes hacer algo con el nombre ingresado, como enviarlo a tu servidor o realizar alguna acción en JavaScript
    },
    allowOutsideClick: () => !Swal.isLoading()
  }).then((result) => {
    if (result.isConfirmed) {
      console.log(result.value);
      const timerInterval = Swal.fire({ //se le asigna un nombre al swal
        allowOutsideClick: false,
        title: '¡Guardando Entrega de EPP!',
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
      });
      const data_asig = new FormData($('#form_items')[0]);
      data_asig.append('clave',result.value)
      $.ajax({
        data: data_asig,
        url: `${urls}qhse/confirmar_entrega_epp`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (save) {

          Swal.close(timerInterval);

          if (save.hasOwnProperty('xdebug_message')) {
            Swal.fire({
              icon: "error",
              title: "Oops, Exception...",
              text: "Algo salió Mal en procesos_equipo! Contactar con el Administrador",
            });
            console.log('Mensaje de xdebug:', save.xdebug_message);
          } else if (save == 'errorClave') {
            Swal.fire({
              icon: "info",
              title: "Clave incorrecta",
              // text: '',
            });
          } else if (save == false) {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Algo salió Mal! Contactar con el Administrador del Sistema",
            });
          } else {
            $(`#vale_${save}`).fadeOut("slow");
            $("#form_items").empty();
            $("#num_nomina").val('');
            $("#id_user").val('');
            $("#nombre").val('');
            $("#code_nomina").empty();
            
            Swal.fire({
              icon: "success",
              title: "¡Exito!",
              text: "Se ha Registrado Correctamente",
            });
          }
        }
      }).fail(function (jqXHR, textStatus, errorThrown) {
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
      })
    }
  });

})

function datosUsusario(campo) {
  $("#num_nomina").removeClass('has-error');
  $("#error_num_nomina").text('');
  $("#id_user").val('');
  $("#nombre").val('');
  const data = new FormData();
  data.append("payroll_number", campo.value);
  $.ajax({
    data: data,
    type: "post",
    url: `${urls}sistemas/datos_usuario_actualizado`,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (resp) {
      if (resp != false) {
        $("#id_user").val(resp.id_user);
        $("#nombre").val(resp.nombre_completo);
      }
      else {
        $("#num_nomina").val('');
        $("#num_nomina").addClass('has-error');
        $("#error_num_nomina").text('Dato No Encontrado');
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

function actualizarValor(checkbox) {
  if (checkbox.checked) {
    checkbox.value = 1;
    $("#lbl_" + checkbox.id).text("Entregado");
    $("#cant_" + checkbox.id).val(1).attr('readonly', false);
  } else {
    checkbox.value = 0;
    $("#lbl_" + checkbox.id).text("No Entregado");
    $("#cant_" + checkbox.id).val(0).attr('readonly', true);
  }
}

$("#btn_buscar_vale").on('click', function () {
  //$("#form_items").empty();
  var cont_item = 1;
  const id_user = document.getElementById('id_user').value;
  if (id_user.length == 0) {
      $("#num_nomina").addClass('has-error');
      $("#error_num_nomina").text('Campo Requerido');
      return;
  }

  const data = new FormData();
  data.append('id_user', id_user);

  $.ajax({
      data: data,
      type: "post",
      url: `${urls}qhse/lista_datos_vales`,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (response) {
          if (response.hasOwnProperty('xdebug_message')) {
              Swal.fire({
                  icon: "error",
                  title: "Oops, Exception...",
                  text: "Algo salió Mal en lista_datos_vales_by_usuario! Contactar con el Administrador",
              });
              console.log('Mensaje de xdebug:', response.xdebug_message);
          } else if (response !== false) {
              console.log(response);
              let groupedItems = response.groupedItems;
              let htmlContent = '';

              $.each(groupedItems, function (requestId, items) {
                  htmlContent += `
                      <div id="vale_${requestId}" class="card mb-3">
                          <div class="card-header">
                              <h5 class="mb-0">Numero de Vale: ${requestId}</h5>
                          </div>
                          <div class="card-body">
                              <div class="list-group">
                  `;

                  items.forEach(function (item) {
                      htmlContent += `
                          <div class="list-group-item" style="cursor:pointer;" data-request-id="${requestId}">
                             <p class="mb-1">Articulo: ${item.product}</p>
                             <h6 class="mb-1">Cantidad: ${item.quantity}</h6>
                          </div>
                      `;
                  });

                  htmlContent += `
                              </div>
                          </div>
                      </div>
                  `;
              });

              // Insertamos el contenido HTML en el contenedor
              $('#vouchers_list').html(htmlContent);

              // Asignamos el evento click a los elementos creados dinámicamente
              $('#vouchers_list').on('click', '.list-group-item', function () {
                  let requestId = $(this).data('request-id');
                  let items = groupedItems[requestId];
                  $("#code_nomina").empty();
                  $("#itemModalLabel").empty();

                 
                  let modalContent = '';
                  let payrollnumber_image  = '';
                  items.forEach(function (item) {
                    payrollnumber_image = item.payrollnumber_image;
                     /*  modalContent += `
                          <div class="modal-item">
                              <h6>Item ID: ${item.item_id}</h6>
                              <p>Item Name: ${item.product}</p>
                              <small>Item Code: ${item.item_code}</small>
                          </div>
                          <hr/>
                      `; */
                      var clas = (cont_item % 2 == 0) ? 'item-color' : '';
                       modalContent +=`
                       <div class="row item ${clas}" id="row_${cont_item}">
                        <div class="col-md-2">
                          <input type="hidden" name="id_request" value="${requestId}">
                          <input type="hidden" name="id_item_[]" value="${item.id_request_item}">
                          <div class="form-check" style="padding-top: 15px;">
                            <input class="form-check-input" style="width: 30px;height: 30px;" type="checkbox" id="entrega_${cont_item}" onclick="actualizarValor(this)" value="0">
                            <label class="form-check-label type-h4" for="miCheckbox" id="lbl_entrega_${cont_item}" style="margin-left: 20px;font-size: 24px;">No Entregado</label>
                          </div>
                        </div>
                        <div class="col-md-7">
                          <label>EQUIPO</label>
                          <h4 style="border-bottom: 2px dashed black;" id="h4_equipo">${item.product}</h4>
                          <div style="margin-bottom:5rem"> <img src="${item.barcode_image}" style="height:40px;">    </div>
                          <div class="col-md-12">
                          <div class="row">
                          <div class="col-md-6"> <label>Code EQPS <img src="${item.barcode_eqps}" style="width:192px;height:38px;"></label>  </div>
                          <div class="col-md-6 text-right"> <label>Code UNIF <img src="${item.barcode_unif}" style="width:192px;height:38px;"></label>  </div>  
            
                          </div>
                          </div>  
                          
                        </div>
                        <div class="col-md-1">
                          <label>SOLICITADO</label>
                          <h4 style="border-bottom: 2px dashed black;" id="piezas">${item.quantity} ${item.unit}</h4>
                        </div>
                        <div class="col-md-1">
                          <label>ENTREGADO</label>
                          <div class="input-group-prepend">
                            <input type="number" class="form-control" name="cant_entrega_[]" id="cant_entrega_${cont_item}" min="0" max="${item.quantity}" value="0" readonly>
                            <span class="input-group-text">${item.unit}</span>
                          </div>
                        </div>
                      </div>`;
                      cont_item++;
                    



                  });
                    // Insertamos el contenido en el cuerpo del modal
                    $('#form_items').html(modalContent);

                  $("#form_items").append(`<div class="row">
                    <div class="col-md-12">
                      <label>Comentario</label>
                      <textarea name="comentario" class="form-controls" cols="10" rows="2"></textarea>          
                    </div>
                    <div class="col-md-12" style="margin-top:15px">
                      <button type="submit" id="btn_items" class="btn btn-outline-guardar btn-lg btn-block"><i class="far fa-save" style="margin-right:5px;"></i>GUARDAR</button>                   
                    </div>
                  </div>`);

                

                  $("#code_nomina").append(`<label>Num.Nomina: <img src="${payrollnumber_image}" style="height:40px;"></label>`);
                  $("#itemModalLabel").text(`Numero de Vale: ${requestId}`);
                  // Mostramos el modal
                  $('#itemModal').modal('show');
              });

          } else {
              Swal.fire({
                  icon: "info",
                  title: "Sin Datos",
                  text: "No se encontraron vales EPP de este usuario.",
              });
          }
      },
      error: function () {
          Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Ocurrió un error en el servidor! Contactar con el Administrador",
          });
      },
  });
});


 function voucherItems(id_request) {
  $("#form_items").empty();
  var cont_item = 1;
  

  const data = new FormData();
  data.append('id_request', id_request)
  $.ajax({
    data: data,
    type: "post",
    url: `${urls}qhse/lista_item_vales`,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (resp) {
      if (resp.hasOwnProperty('xdebug_message')) {

        Swal.fire({
          icon: "error",
          title: "Oops, Exception...",
          text: "Algo salió Mal en lista_datos_vales_by_usuario! Contactar con el Administrador",
        });
        console.log('Mensaje de xdebug:', resp.xdebug_message);
      } if (resp != false) {

        $("#especificacion").val(resp[0].specify);

        $("#form_items").append(`<div class="row">
          <div class="col-md-12">
            
            <input type="hidden" name="id_request" value="${id_request}">      
          </div>
        </div>`);
        $("#code_nomina").append(`<img src="${resp[0].payrollnumber_image}">`);
        resp.forEach(item => {
          var clas = (cont_item % 2 == 0) ? 'item-color' : '';
          $("#form_items").append(`<div class="row item ${clas}" id="row_${cont_item}">
            <div class="col-md-2">
              <input type="hidden" name="id_item_[]" value="${item.id_request_item}">
              <div class="form-check" style="padding-top: 15px;">
                <input class="form-check-input" style="width: 30px;height: 30px;" type="checkbox" id="entrega_${cont_item}" onclick="actualizarValor(this)" value="0">
                <label class="form-check-label type-h4" for="miCheckbox" id="lbl_entrega_${cont_item}" style="margin-left: 20px;font-size: 24px;">No Entregado</label>
              </div>
            </div>
            <div class="col-md-7">
              <label>EQUIPO</label>
              <h4 style="border-bottom: 2px dashed black;" id="h4_equipo">${item.product}</h4>
              <div style="margin-bottom:3.5rem"> <img src="${item.barcode_image}" style="height:35px;">    </div>
              <div class="col-md-12">
              <div class="row">
              <div class="col-md-6"> <label>Code EQPS <img src="${item.barcode_eqps}" style="width:192px;height:35px;"></label>  </div>
              <div class="col-md-6 text-right"> <label>Code UNIF <img src="${item.barcode_unif}" style="width:192px;height:35px;"></label>  </div>  

              </div>
              </div>  
              
            </div>
            <div class="col-md-1">
              <label>SOLICITADO</label>
              <h4 style="border-bottom: 2px dashed black;" id="piezas">${item.quantity} ${item.unit}</h4>
            </div>
            <div class="col-md-1">
              <label>ENTREGADO</label>
              <div class="input-group-prepend">
                <input type="number" class="form-control" name="cant_entrega_[]" id="cant_entrega_${cont_item}" min="0" max="${item.quantity}" value="0" readonly>
                <span class="input-group-text">${item.unit}</span>
              </div>
            </div>
          </div>`);
          cont_item++;
        });
        $("#form_items").append(`<div class="row">
          <div class="col-md-12">
            <label>Comentario</label>
            <textarea name="comentario" class="form-controls" cols="10" rows="4"></textarea>          
          </div>
          <div class="col-md-12" style="margin-top:15px">
            <button type="submit" id="btn_items" class="btn btn-outline-guardar btn-lg btn-block"><i class="far fa-save" style="margin-right:5px;"></i>GUARDAR</button>                   
          </div>
        </div>`);
      }
      else {
        Swal.fire({
          icon: "info",
          title: "Sin Datos",
          text: "No se encontraron vales EPP de este usuario.",
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




function handleAuthorized(id_product) {

  let data = new FormData();
  data.append("id_product", id_product);

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    type: "post", //método de envio
    url: `${urls}qhse/autorizar_epp`, //archivo que recibe la peticion
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    dataType: "json",
    success: function (resp) {

      if (resp) {
        setTimeout(function () {
          tbl_inventary.ajax.reload(null, false);
          $("#parametros").prop("disabled", false);
          $("#inventarioModal").modal("toggle");
          Swal.fire({
            icon: "success",
            title: "",
            text: "!Los datos se han Actualizado!",
          });
        }, 100);
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
