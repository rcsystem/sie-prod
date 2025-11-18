/**
 * ARCHIVO MODULO Gastos & Viatcos
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EDITOR: HORUS SAMAEL RIVAS PEDRAZA
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */

function changeForm(opc) {
  $(".has-error").removeClass("has-error");
  $(".text-danger").text('');
  if (opc == 2) {
    document.getElementById('form_solicitud_viatico').reset();
    $('.btn-option').removeClass("active");
    $('.div-option').empty();
  } else if (opc == 1) {
    document.getElementById('form_gastos').reset();
  }
}

$("#form_solicitud_viatico").submit(function (e) {
  e.preventDefault();
  var errores = 0;
  const mensaje = 'Campo Requerido';
  const btn = document.getElementById('btn_solicitud_viatico');

  const avion = document.getElementById('avion').value;
  const jerarquia = document.getElementById('jerarquia').value;
  const tipo_viaje = document.getElementById('tipo_viaje').value;
  const inicio_viaje = document.getElementById('inicio_viaje').value;
  const origen_viaje = document.getElementById('origen_viaje').value;
  const regreso_viaje = document.getElementById('regreso_viaje').value;
  const destino_viaje = document.getElementById('destino_viaje').value;
  const detalle_viaje = document.getElementById('detalle_viaje').value;

  if (tipo_viaje.length == 0) {
    $("#error_tipo_viaje").text(mensaje);
    errores++;
  } else {
    $("#error_tipo_viaje").text('')
  }

  if (tipo_viaje == 2) {
    if ($("#id_pais").val().length == 0) {
      errores++;
      $("#error_id_pais").text(mensaje);
      $("#id_pais").addClass('has-error');
    } else {
      $("#error_id_pais").text('');
      $("#id_pais").removeClass('has-error');
    }
  }

  if (jerarquia.length == 0) {
    $("#error_jerarquia").text(mensaje);
    errores++;
  } else {
    $("#error_jerarquia").text('')
  }

  if (jerarquia == 2) {
    if ($("#id_level").val().length == 0) {
      errores++;
      $("#error_id_level").text(mensaje);
      $("#id_level").addClass('has-error');
    } else {
      $("#error_id_level").text('');
      $("#id_level").removeClass('has-error');
    }
  }

  if (inicio_viaje.length == 0) {
    $("#inicio_viaje").addClass('has-error');
    $("#error_inicio_viaje").text(mensaje);
    errores++;
  } else {
    $("#inicio_viaje").removeClass('has-error');
    $("#error_inicio_viaje").text('')
  }

  if (regreso_viaje.length == 0) {
    $("#regreso_viaje").addClass('has-error');
    $("#error_regreso_viaje").text(mensaje);
    errores++;
  } else {
    $("#regreso_viaje").removeClass('has-error');
    $("#error_regreso_viaje").text('')
  }

  if (inicio_viaje.length != 0 && regreso_viaje.length != 0) {
    if (regreso_viaje <= inicio_viaje) {
      $("#regreso_viaje").addClass('has-error');
      $("#error_regreso_viaje").text('Fecha debe ser Mayor a la Inicial');
      errores++;
    }
  }

  if (origen_viaje.length == 0) {
    $("#origen_viaje").addClass('has-error');
    $("#error_origen_viaje").text(mensaje);
    errores++;
  } else {
    $("#origen_viaje").removeClass('has-error');
    $("#error_origen_viaje").text('')
  }

  if (destino_viaje.length == 0) {
    $("#destino_viaje").addClass('has-error');
    $("#error_destino_viaje").text(mensaje);
    errores++;
  } else {
    $("#destino_viaje").removeClass('has-error');
    $("#error_destino_viaje").text('')
  }

  if (avion.length == 0) {
    $("#error_avion").text(mensaje);
    errores++;
  } else {
    $("#error_avion").text('')
  }

  if (avion == 1) {
    if ($("#horario_ida").val().length == 0) {
      errores++;
      $("#error_horario_ida").text(mensaje);
      $("#horario_ida").addClass('has-error');
    } else {
      $("#error_horario_ida").text('');
      $("#horario_ida").removeClass('has-error');
    }

    if ($("#horario_regreso").val().length == 0) {
      errores++;
      $("#error_horario_regreso").text(mensaje);
      $("#horario_regreso").addClass('has-error');
    } else {
      $("#error_horario_regreso").text('');
      $("#horario_regreso").removeClass('has-error');
    }
  }

  if (detalle_viaje.length == 0) {
    $("#detalle_viaje").addClass('has-error');
    $("#error_detalle_viaje").text(mensaje);
    errores++;
  } else {
    $("#detalle_viaje").removeClass('has-error');
    $("#error_detalle_viaje").text('')
  }

  if (errores != 0) {
    return false;
  }

  Swal.fire({
    title: 'Términos y Condiciones',
    html: `<label class="custom-checkbox-cell"><input type="checkbox" id="aceptoCheckbox">
    He leído, y Acepto la <a href="${urls}public/doc/politicas/politica_de_viaticos_FGC-01_REV.pdf" target="_blank">política para el control de viáticos, pasajes y gastos a comprobar</a></label>`,
    showCancelButton: true,
    confirmButtonText: 'Continuar',
    cancelButtonText: 'Cancelar',
    allowOutsideClick: false,
    preConfirm: () => {
      const aceptoCheckbox = document.getElementById('aceptoCheckbox');
      if (!aceptoCheckbox.checked) {
        Swal.showValidationMessage('Debes aceptar los términos y condiciones para continuar');
      }
      return aceptoCheckbox.checked;
    }
  }).then((result) => {
    if (result.isConfirmed) {
      let timerInterval = Swal.fire({
        title: 'Generando Solicitud!',
        html: 'Espere unos Segundos.',
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
      });
      btn.disabled = true;
      const data = new FormData($("#form_solicitud_viatico")[0]);

      $.ajax({
        data: data,
        url: `${urls}viajes/viaticos`,
        type: "POST",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (save) {
          btn.disabled = false;
          Swal.close(timerInterval);
          if (save.hasOwnProperty('xdebug_message')) {
            Swal.fire({
              icon: "error",
              title: "Oops, Exception...",
              text: "Algo salió Mal! Contactar con el Administrador",
            });
            console.log('Mensaje de xdebug:', response.xdebug_message);
          } else if (save != false) {
            console.log(save);
            document.getElementById('form_solicitud_viatico').reset();
            $('.btn-option').removeClass("active");
            $('.div-option').empty();
            Swal.fire(`!Sea Registrado la Solicitud con el Folio: ${save}!`, "", "success");
          } else {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Ocurrio un error en el servidor! Contactar con el Administrador",
            });
          }
        },
        error: function () {
          btn.disabled = false;
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Ocurrio un error en el servidor! Contactar con el Administrador",
          });
        },
      }).fail(function (jqXHR, textStatus, errorThrown) {
        btn.disabled = false;
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
          alert('Uncaught Error: ' + jqXHR.responseText);
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: `Uncaught Error: ${jqXHR.responseText}`,
          });
        }
      })
    }
  })
})

$("#form_gastos").submit(function (e) {
  e.preventDefault();
  var errores = 0;
  const mensaje = 'Campo Requerido';
  const btn = document.getElementById('btn_gastos');

  const monto_gasto = document.getElementById('monto_gasto').value;
  const inicio_gastos = document.getElementById('inicio_gastos').value;
  const regreso_gastos = document.getElementById('regreso_gastos').value;
  const motivo_gasto = document.getElementById('motivo_gasto').value;

  if (monto_gasto.length == 0) {
    $("#monto_gasto").addClass('has-error');
    $("#error_monto_gasto").text(mensaje);
    errores++;
  } else {
    $("#monto_gasto").removeClass('has-error');
    $("#error_monto_gasto").text('')
  }

  if (inicio_gastos.length == 0) {
    $("#inicio_gastos").addClass('has-error');
    $("#error_inicio_gastos").text(mensaje);
    errores++;
  } else {
    $("#inicio_gastos").removeClass('has-error');
    $("#error_inicio_gastos").text('')
  }

  if (regreso_gastos.length == 0) {
    $("#regreso_gastos").addClass('has-error');
    $("#error_regreso_gastos").text(mensaje);
    errores++;
  } else {
    $("#regreso_gastos").removeClass('has-error');
    $("#error_regreso_gastos").text('')
  }

  if (inicio_gastos.length != 0 && regreso_gastos.length != 0) {
    if (inicio_gastos >= regreso_gastos) {
      $("#regreso_gastos").addClass('has-error');
      $("#error_regreso_gastos").text('Fecha debe ser Mayor a la Inicial');
      errores++;
    }
  }

  if (motivo_gasto.length == 0) {
    $("#motivo_gasto").addClass('has-error');
    $("#error_motivo_gasto").text(mensaje);
    errores++;
  } else {
    $("#motivo_gasto").removeClass('has-error');
    $("#error_motivo_gasto").text('')
  }

  if (errores != 0) {
    return false;
  }

  Swal.fire({
    title: 'Términos y Condiciones',
    html: `<label class="custom-checkbox-cell"><input type="checkbox" id="aceptoCheckbox">
    He leído, y Acepto la <a href="${urls}public/doc/politicas/politica_de_viaticos_FGC-01_REV.pdf" target="_blank">política para el control de viáticos, pasajes y gastos a comprobar</a></label>`,
    showCancelButton: true,
    confirmButtonText: 'Continuar',
    cancelButtonText: 'Cancelar',
    allowOutsideClick: false,
    preConfirm: () => {
      const aceptoCheckbox = document.getElementById('aceptoCheckbox');
      if (!aceptoCheckbox.checked) {
        Swal.showValidationMessage('Debes aceptar los términos y condiciones para continuar');
      }
      return aceptoCheckbox.checked;
    }
  }).then((result) => {
    if (result.isConfirmed) {
      let timerInterval = Swal.fire({
        title: 'Generando Solicitud!',
        html: 'Espere unos Segundos.',
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
      });
      btn.disabled = true;
      const data = new FormData($("#form_gastos")[0]);

      $.ajax({
        data: data,
        url: `${urls}viajes/registrar_gastos`,
        type: "POST",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (save) {
          btn.disabled = false;
          Swal.close(timerInterval);
          if (save.hasOwnProperty('xdebug_message')) {
            Swal.fire({
              icon: "error",
              title: "Oops, Exception...",
              text: "Algo salió Mal! Contactar con el Administrador",
            });
            console.log('Mensaje de xdebug:', response.xdebug_message);
          } else if (save != false) {
            console.log(save);
            document.getElementById('form_gastos').reset();
            Swal.fire(`!Sea Registrado la Solicitud con el Folio: ${save}!`, "", "success");
          } else {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Ocurrio un error en el servidor! Contactar con el Administrador",
            });
          }
        },
        error: function () {
          btn.disabled = false;
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Ocurrio un error en el servidor! Contactar con el Administrador",
          });
        },
      }).fail(function (jqXHR, textStatus, errorThrown) {
        btn.disabled = false;
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
          alert('Uncaught Error: ' + jqXHR.responseText);
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: `Uncaught Error: ${jqXHR.responseText}`,
          });
        }
      })
    }
  })
})


function limpiarError(campo) {
  if (campo.value.length > 0) {
    campo.classList.remove('has-error');
    document.getElementById("error_" + campo.id).textContent = '';
  }
}

function tipoViaje(tipo) {
  $("#error_tipo_viaje").text('');
  $("#resultado_internacional").empty();
  $("#resultado_internacional").attr('class', "div-option");
  $("#tipo_viaje").val(tipo)
  if (tipo == 2) {
    document.getElementById('calculo_viaticos').innerHTML = '';
    $.ajax({
      url: `${urls}viajes/lista_internacional_grados`,
      type: "post",
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function (resp) {
        if (resp.paises != null) {
          $("#resultado_internacional").attr('class', 'col-md-3 div-option');
          $("#resultado_internacional").append(`<label for="id_pais">Seleccionar Destino:</label>
            <select name="id_pais" id="id_pais" class="form-control" onchange="limpiarError(this),calcularViaticos()">
            <option value="">Opciones...</option></select>
          <div id="error_id_pais" class="text-center text-danger"></div>`);
          resp.paises.forEach(data => {
            $("#id_pais").append(`<option value="${data.id_country}">${data.country}</option>`);
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Algo salió Mal! no se han cargado los viaticos correspondientes Contactar con el Administrador",
          });
          console.log("Mal Revisa");
        }
      },
    })
    $("#resultado_internacional").append(``);
  } else {
    calcularViaticos()
  }
}

function tipoNivel(tipo) {
  $("#error_jerarquia").text('');
  $("#div_otra_jerarquia").empty();
  $("#div_otra_jerarquia").attr('class', "div-option");
  $("#jerarquia").val(tipo)
  if (tipo == 2) {
    document.getElementById('calculo_viaticos').innerHTML = '';
    $.ajax({
      url: `${urls}viajes/lista_internacional_grados`,
      type: "post",
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function (resp) {
        if (resp.grados != null) {
          $("#div_otra_jerarquia").attr('class', 'col-md-3 div-option');
          $("#div_otra_jerarquia").append(`<label for="id_level">Seleccionar Gerarquia:</label>
            <select name="id_level" id="id_level" class="form-control" onchange="limpiarError(this),calcularViaticos()">
            <option value="">Opciones...</option></select>
          <div id="error_id_level" class="text-danger"></div>`);
          resp.grados.forEach(data => {
            $("#id_level").append(`<option value="${data.id_level}">${data.level}    ${data.level_name}</option>`);
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Algo salió Mal! no se han cargado los viaticos correspondientes Contactar con el Administrador",
          });
          console.log("Mal Revisa");
        }
      },
    })
    $("#div_otra_jerarquia").append(``);
  } else {
    calcularViaticos()
  }
}

function calcularViaticos() {
  const div_calculo = document.getElementById('calculo_viaticos');
  const tipo_viaje = document.getElementById('tipo_viaje').value;
  const jerarquia = document.getElementById('jerarquia').value;
  const inicio_viaje = document.getElementById('inicio_viaje').value;
  const regreso_viaje = document.getElementById('regreso_viaje').value;
  div_calculo.innerHTML = '';
  $("#total_viaticos").val('');
  if (tipo_viaje.length > 0 && jerarquia.length > 0
    && inicio_viaje.length > 0 && regreso_viaje.length > 0) {

    error_calculo = 0;
    if (tipo_viaje == 2 && document.getElementById('id_pais').value.length == 0) {
      $("#id_pais").addClass('has-error');
      $("#error_id_pais").text('Campo Requerido Para Calculo');
      error_calculo++;
    }
    if (jerarquia == 2 && document.getElementById('id_level').value.length == 0) {
      $("#id_level").addClass('has-error');
      $("#error_id_level").text('Campo Requerido Para Calculo');
      error_calculo++;
    }
    if (error_calculo != 0) {
      return;
    }
    const id_pais = (tipo_viaje == 1) ? 1 : document.getElementById('id_pais').value;
    const id_level = (jerarquia == 1) ? 0 : document.getElementById('id_level').value;
    const datos_calcular = new FormData();
    datos_calcular.append('id_pais', id_pais);
    datos_calcular.append('id_level', id_level);
    datos_calcular.append('inicio', inicio_viaje);
    datos_calcular.append('fin', regreso_viaje);
    $.ajax({
      data: datos_calcular,
      url: `${urls}viajes/calcular_viaticos`,
      type: "post",
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function (resp) {
        if (resp != null && resp != false) {
          div_calculo.innerHTML = (`<label><b>Total de Viáticos para los ${resp.dias} días:  $${resp.viaticos} ${resp.moneda}</b></label>`)
          $("#total_viaticos").val(resp.viaticos);
          $("#divisa_viaticos").val(resp.moneda);
        } else {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Algo salió Mal! no se han cargado los viaticos correspondientes Contactar con el Administrador",
          });
          console.log("Mal Revisa");
        }
      },
    })
  }
}

function vueloOpc(opc) {
  const div = document.getElementById('resultado_avion_1');
  const div_ = document.getElementById('resultado_avion_2');
  $("#avion").val(opc);
  $("#error_avion").text('');
  div.innerHTML = '';
  div_.innerHTML = '';
  if (opc == 1) {
    div.innerHTML = (`<label for="horario_ida">Horario Preferente Ida</label>
      <input type="time" class="form-control" id="horario_ida" name="horario_ida" onchange="validar()">
    <div id="error_horario_ida" class="text-danger"></div>`);

    div_.innerHTML = (`<label for="horario_regreso">Horario Preferente Regreso</label>
      <input type="time" class="form-control" id="horario_regreso" name="horario_regreso" onchange="validar()">
    <div id="error_regreso" class="text-danger"></div>`);
  }
}