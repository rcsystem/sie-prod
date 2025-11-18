/**
 * ARCHIVO MODULO PERMISSIONS
 * AUTOR:HORUS SAMAEL RIVAS PEDRAZA
 * EMAIL:horus.riv.ped@gmail.com
 * CEL:56 2439 2632
 */
var array_items = [1]
var contar_items = 1
var deuda = false;

$(document).ready(function () {
    validarDeudaPagoTiempo();
    array_items = [1]
    contar_items = 1
    console.log('hoy ->  ', $("#hoy").val(), '+ 15 dias -> ', $("#15dias").val());
    pintarHorarios(1);
});

$("#form_tiempo").submit(function (e) {
    e.preventDefault();
    tiempoTotal();
    errores = validacionSubmit();

    if (deuda) {
        const time_deuda = document.getElementById('tiempo_deuda').value;
        const time_pago = document.getElementById('tiempo_pagado').value;

        console.log(time_deuda > time_pago);
        if (time_deuda > time_pago) {
            errores += errores;
            document.getElementById('tiempo_pagado').classList.add('has-error')
            document.getElementById('error_tiempo_deuda').textContent = 'Tiempo Insuficiente'
        } else {
            document.getElementById('tiempo_pagado').classList.remove('has-error')
            document.getElementById('error_tiempo_deuda').textContent = ''
        }
    }

    if (errores > 0) {
        return false;
    }
    let timerInterval = Swal.fire({ //se le asigna un nombre al swal
        title: 'Generando Pago de Tiempo!',
        html: 'Espere unos Segundos.',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });
    document.getElementById("btn_tiempo").disabled = true;
    var datos = new FormData($("#form_tiempo")[0]);
    datos.append('items', array_items.length);
    $.ajax({
        data: datos,
        url: urls + "permisos/generar_pago_tiempo", //archivo que recibe la peticion
        type: "post", //método de envio
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentType
        dataType: "json",
        success: function (save) {
            Swal.close(timerInterval);
            document.getElementById("btn_tiempo").disabled = false;
            if (save.hasOwnProperty('xdebug_message')) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
                console.log(save.xdebug_message);
            } else if (save != false && save != null) {
                document.getElementById('form_tiempo').reset();
                document.getElementById("div_horario_1").innerHTML = "";
                array_items = [1];
                contar_items = 1
                $(".clon").remove();
                validarDeudaPagoTiempo();
                Swal.fire({
                    icon: "success",
                    title: "Exito",
                    text: "¡Se registró el Pago de Tiempo exitosamente!.",
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
        document.getElementById("btn_tiempo").disabled = false;
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
    });
});

function validacionSubmit() {
    var errores = 0;
    array_items.forEach(item => {
        var turno = document.getElementById('turno_' + item);
        if (turno.value.length == 0) {
            turno.classList.add('has-error');
            document.getElementById('error_turno_' + item).textContent = 'Campo Requerido';
            errores += 1;
        } else {
            turno.classList.remove('has-error');
            document.getElementById('error_turno_' + item).textContent = '';
        }

        var tipo_permiso = document.getElementById('tipo_permiso_' + item);
        if (tipo_permiso.value.length == 0) {
            tipo_permiso.classList.add('has-error');
            document.getElementById('error_tipo_permiso_' + item).textContent = 'Campo Requerido';
            errores += 1;
        } else {
            tipo_permiso.classList.remove('has-error');
            document.getElementById('error_tipo_permiso_' + item).textContent = '';
        }

        var dia_salida = document.getElementById('dia_salida_' + item);
        if (dia_salida.value.length == 0) {
            dia_salida.classList.add('has-error');
            document.getElementById('error_dia_salida_' + item).textContent = 'Campo Requerido';
            errores += 1;
        } else if (dia_salida.value < $("#hoy").val() || dia_salida.value > $("#15dias").val()) {
            dia_salida.classList.add('has-error');
            document.getElementById('error_dia_salida_' + item).textContent = 'Día fuera de los Parámetros';
            errores += 1;
        } else {
            const date = new Date(dia_salida.value);
            const dayOfWeek = date.getDay(); // 5 ->Sabado, 6 -> Domingo, 0 -> Lunes ....
            if (dayOfWeek === 6) {
                dia_salida.classList.add('has-error');
                document.getElementById('error_dia_salida_' + item).textContent = 'Dia no Valido';
                errores += 1;
            } else {
                dia_salida.classList.remove('has-error');
                document.getElementById('error_dia_salida_' + item).textContent = '';
            }
        }

        var input_horas = document.getElementById('input_horas_' + item);
        var input_minutos = document.getElementById('input_minutos_' + item);
        if (input_horas.value == 0 && input_minutos.value == 0) {
            input_horas.classList.add('has-error');
            input_minutos.classList.add('has-error');
            document.getElementById('error_input_horas_' + item).textContent = 'Campo Requerido';
            document.getElementById('error_input_minutos_' + item).textContent = 'Campo Requerido';
            errores += 1;
        } else {
            input_horas.classList.remove('has-error');
            input_minutos.classList.remove('has-error');
            document.getElementById('error_input_horas_' + item).textContent = '';
            document.getElementById('error_input_minutos_' + item).textContent = '';
        }
    });
    return errores;
}

function turnos(select, item) {
    document.getElementById("div_horario_" + item).innerHTML = '';
    document.getElementById('tipo_permiso_' + item + '_opc').style.display = 'none';

    var lv_in = document.getElementById('L-V_entrada_' + item);
    var lv_out = document.getElementById('L-V_salida_' + item);
    var s_in = document.getElementById('S_entrada_' + item);
    var s_out = document.getElementById('S_salida_' + item);

    lv_in.value = '';
    lv_out.value = '';
    s_in.value = '';
    s_out.value = '';

    const element_error = document.getElementById("error_" + select.id);
    if (select.value.length == 0) {
        element_error.textContent = "Campo Requerido";
        select.classList.add('has-error');
        return false;
    }
    element_error.textContent = "";
    select.classList.remove('has-error');
    const data = new FormData();
    data.append('id_turn', select.value);
    $.ajax({
        data: data,
        url: `${urls}permisos/horarios`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (turn) {
            if (turn != false && turn != null) {
                element_error.textContent = "";
                select.classList.remove('has-error');
                sabado = (turn.hour_in_saturday == '00:00:00') ? '<b>Sabado:</b> Sin Horario' : ` <b>Sabado:</b> ${turn.hour_in_saturday} - ${turn.hour_out_saturday}`;
                $("#div_horario_" + item).append(`<b>Lunes a Viernes:</b> ${(turn.hour_in)} - ${turn.hour_out} <br> ${sabado}`);
                if (select.value == 9) {
                    document.getElementById('tipo_permiso_' + item + '_opc').style.display = 'block';
                }
                lv_in.value = turn.hour_in;
                lv_out.value = turn.hour_out;
                s_in.value = turn.hour_in_saturday;
                s_out.value = turn.hour_out_saturday;
            } else {
                element_error.textContent = "Campo Requerido";
                select.classList.add('has-error');
            }
        }
    });
}

function limpiarError(campo) {
    if (campo.value.length > 0) {
        campo.classList.remove('has-error');
        document.getElementById("error_" + campo.id).textContent = '';
    }
}

function tiempoTotal() {
    var horas = 0;
    var minutos = 0;
    array_items.forEach(i => {
        ;
        horas += parseInt(document.getElementById("input_horas_" + i).value);
        minutos += parseInt(document.getElementById("input_minutos_" + i).value);
    });
    if (minutos > 59) {
        horas += Math.floor(minutos / 60);
        minutos %= 60;
    }
    total = (minutos < 10) ? `${horas}:0${minutos}` : `${horas}:${minutos}`;
    document.getElementById('tiempo_pagado').value = total;
    if (deuda) {
        document.getElementById('tiempo_pagado').classList.remove('has-error')
        document.getElementById('error_tiempo_deuda').textContent = ''
    }
}

$("#btn_agregar_item").on('click', function () {
    if (array_items.length < 6) {
        contar_items++;
        array_items.forEach(item => {
            if (item === contar_items) {
                contar_items++;
            }
        });
        array_items.push(contar_items);
        $("#clones_dias").append(`<div class="form-row clon" id="div_dia_${contar_items}" style="padding-top: 8px;">
        <div class="form-group col-md-3">
            <input type="hidden" name="L-V_entrada_[]" id="L-V_entrada_${contar_items}"><input type="hidden" name="L-V_salida_[]" id="L-V_salida_${contar_items}">
            <input type="hidden" name="S_entrada_[]" id="S_entrada_${contar_items}"><input type="hidden" name="S_salida_[]" id="S_salida_${contar_items}">                        
            <label>En el Turno:</label>
            <Select id="turno_${contar_items}" name="turno_[]" class="form-control" onchange="turnos(this,${contar_items}),turnoCompleto(${contar_items})">
                <option value="">Selecciona....</option>
            </Select>
            <div id="div_horario_${contar_items}"></div>
            <div id="error_turno_${contar_items}" class="text-danger"></div>
        </div>
        <div class="col-md-2">
            <label for="tipo_permiso_${contar_items}">Tipo de pago:</label>
            <select name="tipo_permiso_[]" id="tipo_permiso_${contar_items}" class="form-control" onchange="limpiarError(this),turnoCompleto(${contar_items})">
                <option value="">Opciones....</option>
                <option value="1" id="tipo_permiso_${contar_items}_opc" style="display: none;">Llegar Antes</option>
                <option value="2">Quedarse Despues</option>
                <option value="3">Turno Completo</option>
            </select>
            <div id="error_tipo_permiso_${contar_items}" class="text-danger"></div>
        </div>
        <div class="form-group col-md-3">
            <label for="dia_salida_${contar_items}">Día Pago de tiempo:</label>
            <input type="date" class="form-control" id="dia_salida_${contar_items}" name="dia_salida_[]" onchange="limpiarError(this),turnoCompleto(${contar_items})">
            <div id="error_dia_salida_${contar_items}" class="text-danger"></div>
        </div>
        <div class="col-md-3">
            <label>Cantidad de Horas:</label>
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="number" class="form-control" id="input_horas_${contar_items}" name="input_horas_[]" value="0" min="0" max="9" onchange="limpiarError(this),tiempoTotal()">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Hrs</span>
                        </div>
                    </div>
                    <div id="error_input_horas_${contar_items}" class="text-danger"></div>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="number" class="form-control" id="input_minutos_${contar_items}" name="input_minutos_[]" value="0" min="0" max="59" onchange="limpiarError(this),tiempoTotal()">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Min</span>
                        </div>
                    </div>
                    <div id="error_input_minutos_${contar_items}" class="text-danger"></div>
                </div>
            </div>
        </div>
        <div class="col-md-1" style="padding-top:2rem">
            <button type="button" class="btn btn-danger" onclick="retirarItem(${contar_items})"><i class="far fa-trash-alt"></i></button>
        </div>
    </div>`);
        if (array_items.length % 2 === 0) {
            document.getElementById("div_dia_" + contar_items).style.backgroundColor = "#F3F3F3";
        }
        pintarHorarios(contar_items);
    } else {
        $("#error_item").html(
            `<div class="alert alert-warning alert-dismissible" role="alert">
                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
                   </button>
                   <strong>El Sistema solo permite 6 dias por Pago de Tiempo...</strong>
                   </div>
                   <span></span>`
        );
        setTimeout(function () {
            $(".alert")
                .fadeTo(1000, 0)
                .slideUp(800, function () {
                    $(this).remove();
                });
        }, 3000);
    }
    return false;
});

function retirarItem(item) {
    var i = array_items.indexOf(item);
    array_items.splice(i, 1);
    $("#div_dia_" + item).remove();
    contar_items = 1;
    tiempoTotal();
}

function pintarHorarios(item) {
    $.ajax({
        url: `${urls}permisos/lista_horarios`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (arrayTurn) {
            if (arrayTurn != false && arrayTurn != null) {
                arrayTurn.forEach(t => {
                    $("#turno_" + item).append(`<option value="${t.id}">${t.name_turn}</option>`);
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "No se pudo cargar los Horarios",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
            }
        }
    });

}

function turnoCompleto(item) {
    setTimeout(function () {
        var turno = document.getElementById('turno_' + item).value;
        var tipo = document.getElementById('tipo_permiso_' + item).value;
        var dia = document.getElementById('dia_salida_' + item);
        var horas = document.getElementById('input_horas_' + item);
        var minutos = document.getElementById('input_minutos_' + item);
        if (tipo == 3) {
            horas.readOnly = true;
            minutos.readOnly = true;
            if (turno != '' && dia.value != '') {
                var input_horas = document.getElementById('input_horas_' + item);
                var input_minutos = document.getElementById('input_minutos_' + item);
                var error_dia = document.getElementById('error_dia_salida_' + item);
                error_dia.innerText = '';
                dia.classList.remove('has-error');
                const date = new Date(dia.value);
                const dayOfWeek = date.getDay(); // 5 ->Sabado, 6 -> Domingo, 0 -> Lunes ....
                if (dayOfWeek === 6) {
                    error_dia.innerText = 'Dia no Valido';
                    dia.classList.add('has-error');
                    horas.value = 0;
                    minutos.value = 0;
                } else if (dayOfWeek === 5) {
                    var S_in = document.getElementById('S_entrada_' + item).value;
                    var S_out = document.getElementById('S_salida_' + item).value;

                    if (S_in == '00:00:00') {
                        error_dia.innerText = 'Dia sin Horario';
                        dia.classList.add('has-error');
                        horas.value = 0;
                        minutos.value = 0;
                        tiempoTotal()
                        return false;
                    }
                    if (turno == 9) { // Caso de 3er turno Sabado, Manual
                        horas.value = 7;
                        minutos.value = 30;
                    } else {
                        const horaInicio = new Date('2000-01-01T' + S_in);
                        const horaFin = new Date('2000-01-01T' + S_out);

                        const diferenciaMilisegundos = horaFin - horaInicio;
                        const minutosTotales = Math.floor(diferenciaMilisegundos / 1000 / 60);

                        const R_horas = Math.floor(minutosTotales / 60);
                        const R_minutos = minutosTotales % 60;

                        horas.value = R_horas;
                        minutos.value = R_minutos;
                    }
                    input_horas.classList.remove('has-error');
                    input_minutos.classList.remove('has-error');
                    document.getElementById('error_input_horas_' + item).textContent = '';
                    document.getElementById('error_input_minutos_' + item).textContent = '';
                } else {
                    var LV_in = document.getElementById('L-V_entrada_' + item).value;
                    var LV_out = document.getElementById('L-V_salida_' + item).value;
                    if (turno == 9) { // Caso de 3er turno, Manual
                        horas.value = 8;
                        minutos.value = 0;
                    } else {
                        const horaInicio = new Date('2000-01-01T' + LV_in);
                        const horaFin = new Date('2000-01-01T' + LV_out);

                        const diferenciaMilisegundos = horaFin - horaInicio;
                        const minutosTotales = Math.floor(diferenciaMilisegundos / 1000 / 60);

                        const R_horas = Math.floor(minutosTotales / 60);
                        const R_minutos = minutosTotales % 60;

                        horas.value = R_horas;
                        minutos.value = R_minutos;
                    }
                    input_horas.classList.remove('has-error');
                    input_minutos.classList.remove('has-error');
                    document.getElementById('error_input_horas_' + item).textContent = '';
                    document.getElementById('error_input_minutos_' + item).textContent = '';
                }
                tiempoTotal();
            }
        } else {
            horas.readOnly = false;
            minutos.readOnly = false;
            horas.value = 0;
            minutos.value = 0;
        }
    }, 200);
}

function validarDeudaPagoTiempo() {
    $("#div_1").empty();
    $("#div_2").empty();
    $("#div_3").empty();
    console.log('validarDeuda');
    $.ajax({
        url: `${urls}permisos/validar_deuda_tiempo`,
        type: "POST",
        dataType: "json",
        async: false,
        success: function (resp) {
            console.log(resp);
            if (resp) {
                $("#div_1").append(`<label>Tiempo Total:</label>
                <input type="text" class="form-control" name="total_solicitado" id="tiempo_pagado" readonly>
                <div class="text-danger" id="error_tiempo_deuda"></div>`);
                $("#div_2").append(`<label>Tiempo a deber:</label>
                <input type="text" class="form-control" id="tiempo_deuda" value="${resp.tiempo_dueda}" readonly>
                <div class="text-danger" id="error_tiempo_deuda"></div>`);
                $("#div_3").append(`<input type="hidden" name="id_permis" id="id_permis" value="${resp.id_es}">
                <label>Fecha creacion del Permiso</label>
                <input type="date" class="form-control" value="${resp.fecha_creacion}" readonly>`);
            
                deuda = resp.id_es;

                Swal.fire({
                    allowOutsideClick: false,
                    icon: 'warning',
                    title: '¡Alerta!',
                    html: `<p>Se detectó un permiso con falta de pago de tiempo.
                    <br> Genera el pago de tiempo.</p>`,
                    padding: '1em',
                    background: "#FFF",
                    backdrop: `rgba(189, 189, 189, 0.7)
                    no-repeat
                    center 0rem`
                });
                // url("../public/images/survey/logo_2.png")
            }
            else{
                $("#div_1").append(`<input type="hidden" class="form-control" name="total_solicitado" id="tiempo_pagado" readonly>`);           
            }

        }
    });
}


/* 
var btn_add = document.getElementById("btn_agregar_item")
btn_add.addEventListener('click', function() {
    // Tu código aquí
});
*/