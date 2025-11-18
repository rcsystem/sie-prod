/*
 * ARCHIVO MODULO ESTACIONAMIENTO
 * AUTOR:Horus Samael Rivas Pedraza
 * EMAIL:horus.riv.ped@gmail.com
 * CEL: 56 2439 2632
 */
var contador = 0;
var contador_registrados = 0;
var arrayItems = [];
const tipo_vehiculo = { 1: "AUTOMOVIL", 2: "MOTOCICLETA", 3: "BICICLETA", 4: "AUTOMOVIL", 5: "AUTOMOVIL", 6: "AUTOMOVIL"};
$(document).ready(function () {
    imprimirRegistros();
});

$("#form_registro").submit(function (e) {
    e.preventDefault();
    $arrayCreate = []
    const btn = document.getElementById("btn_registro");
    console.log(arrayItems.length);
    console.log(contador_registrados);
    if (arrayItems.length == contador_registrados) {
        Swal.fire({
            icon: "warning",
            title: "Advertencia",
            text: "Registrar mínimo 1 vehículo",
        });
        return false;
    }
    contErrors = 0;
    if (arrayItems.length > 0) {
        arrayItems.forEach(item => {
            if (item > contador_registrados) {
                $arrayCreate.push(item);
                console.log('ITEM = ', item);
                if ($("#tipo_vehiculo_" + item).val().length == 0) {
                    contErrors = contErrors + 1;
                    $("#tipo_vehiculo_" + item).addClass('has-error');
                    $("#error_tipo_vehiculo_" + item).text('Campo Requerido');
                } else {
                    $("#tipo_vehiculo_" + item).removeClass('has-error');
                    $("#error_tipo_vehiculo_" + item).text('');
                    if ($("#tipo_vehiculo_" + item).val() == 3) {
                        if ($.trim($("#modelo_" + item).val()).length == 0) {
                            contErrors = contErrors + 1;
                            $("#modelo_" + item).addClass('has-error');
                            $("#error_modelo_" + item).text('Campo Requerido');
                        } else {
                            $("#modelo_" + item).removeClass('has-error');
                            $("#error_modelo_" + item).text('');
                        }

                        if ($.trim($("#color_" + item).val()).length == 0) {
                            contErrors = contErrors + 1;
                            $("#color_" + item).addClass('has-error');
                            $("#error_color_" + item).text('Campo Requerido');
                        } else {
                            $("#color_" + item).removeClass('has-error');
                            $("#error_color_" + item).text('');
                        }
                    } else {
                        if ($.trim($("#modelo_" + item).val()).length == 0) {
                            contErrors = contErrors + 1;
                            $("#modelo_" + item).addClass('has-error');
                            $("#error_modelo_" + item).text('Campo Requerido');
                        } else {
                            $("#modelo_" + item).removeClass('has-error');
                            $("#error_modelo_" + item).text('');
                        }

                        if ($.trim($("#color_" + item).val()).length == 0) {
                            contErrors = contErrors + 1;
                            $("#color_" + item).addClass('has-error');
                            $("#error_color_" + item).text('Campo Requerido');
                        } else {
                            $("#color_" + item).removeClass('has-error');
                            $("#error_color_" + item).text('');
                        }

                        if ($.trim($("#placas_" + item).val()).length == 0) {
                            contErrors = contErrors + 1;
                            $("#placas_" + item).addClass('has-error');
                            $("#error_placas_" + item).text('Campo Requerido');
                        } else {
                            $("#placas_" + item).removeClass('has-error');
                            $("#error_placas_" + item).text('');
                        }

                        if ($.trim($("#vencimiento_" + item).val()).length == 0) {
                            contErrors = contErrors + 1;
                            $("#vencimiento_" + item).addClass('has-error');
                            $("#error_vencimiento_" + item).text('Campo Requerido');
                        } else {
                            $("#vencimiento_" + item).removeClass('has-error');
                            $("#error_vencimiento_" + item).text('');
                        }

                        if ($("#archivo_" + item).val().length == 0) {
                            contErrors = contErrors + 1;
                            $("#lbl_archivo_" + item).addClass('has-error');
                            $("#error_archivo_" + item).text('Campo Requerido');
                        } else if ($("#archivo_" + item)[0].files[0].type !== 'application/pdf') {
                            contErrors = contErrors + 1;
                            $("#lbl_archivo_" + item).addClass('has-error');
                            $("#error_archivo_" + item).text('Archivo no Valido');
                        } else {
                            $("#lbl_archivo_" + item).removeClass('has-error');
                            $("#error_archivo_" + item).text('');
                        }
                    }
                }
            }
        });
    }
    if (contErrors != 0) { return false }
    btn.disabled = true;
    let timerInterval = Swal.fire({ //se le asigna un nombre al swal
        allowOutsideClick: false,
        title: `<i class="fas fa-envelope-open-text"></i> Notificando su registro a HSE`,
        html: 'Espere unos Segundos.',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });
    const datas = new FormData($("#form_registro")[0]);
    datas.append('items', $arrayCreate);
    $.ajax({
        data: datas,
        url: `${urls}estacionamiento/generar_vehiculos_usuario`,
        type: "post",
        dataType: "json",
        processData: false,
        contentType: false,
        cache: false,
        success: function (save) {
            Swal.close(timerInterval);
            btn.disabled = false;
            console.log(save);
            if (save.hasOwnProperty('xdebug_message')) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "¡Algo salió Mal! Contactar con el Administrador",
                });
                console.log(save.xdebug_message);
            }
            else if (save != false && save != null) {
                $("#form_registro")[0].reset();
                $("#items_clon").empty();
                $("#items_existentes").empty();
                contador = 0;
                contador_registrados = 0;
                arrayItems = [];
                imprimirRegistros();
                $("#ext").val(save.ext);
                $("#id_tag").text(save.id);
                Swal.fire({
                    icon: "success",
                    title: "¡Exito!",
                    html: `Registro y Notificación de vehículo(s) exitoso. <br> Su(s) vehículo(s) aparecerá cuando sea aprobado por HSE.`,
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "¡Algo salió Mal! Contactar con el Administrador",
                });
            }
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
            alert('Uncaught Error: ' + jqXHR.saveText);
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: `Uncaught Error: ${jqXHR.responseText}`,
            });
        }
    });
});

$("#form_actualiza_poliza").submit(function (e) {
    e.preventDefault();
    const btn = document.getElementById("btn_actualiza_poliza");
    contErrors = 0;

    if ($.trim($("#vencimiento_modal").val()).length == 0) {
        contErrors = contErrors + 1;
        $("#vencimiento_modal").addClass('has-error');
        $("#error_vencimiento_modal").text('Campo Requerido');
    } else {
        $("#vencimiento_modal").removeClass('has-error');
        $("#error_vencimiento_modal").text('');
    }

    if ($("#archivo_modal").val().length == 0) {
        contErrors = contErrors + 1;
        $("#lbl_archivo_modal").addClass('has-error');
        $("#error_archivo_modal").text('Campo Requerido');
    } else if ($("#archivo_modal")[0].files[0].type !== 'application/pdf') {
        contErrors = contErrors + 1;
        $("#lbl_archivo_modal").addClass('has-error');
        $("#error_archivo_modal").text('Archivo no Valido');
    } else {
        $("#lbl_archivo_modal").removeClass('has-error');
        $("#error_archivo_modal").text('');
    }

    if (contErrors != 0) { return false }
    btn.disabled = true;
    let timerInterval = Swal.fire({ //se le asigna un nombre al swal
        allowOutsideClick: false,
        title: `<i class="fas fa-envelope-open-text"></i> Notificando su actualizacion a HSE`,
        html: 'Espere unos Segundos.',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });
    const datas = new FormData($("#form_actualiza_poliza")[0]);
    $.ajax({
        data: datas,
        url: `${urls}estacionamiento/actualiza_poliza`,
        type: "post",
        dataType: "json",
        processData: false,
        contentType: false,
        cache: false,
        success: function (save) {
            Swal.close(timerInterval);
            btn.disabled = false;
            console.log(save);
            if (save === true) {
                $("#actualizarPolizaModal").modal('hide');
                $("#form_actualiza_poliza")[0].reset();
                imprimirRegistros();
                Swal.fire({
                    icon: "success",
                    title: "¡Exito!",
                    html: `Actualizacion de Poliza y Notificación exitosa.`,
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "¡Algo salió Mal! Contactar con el Administrador",
                });
            }
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
            alert('Uncaught Error: ' + jqXHR.saveText);
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: `Uncaught Error: ${jqXHR.responseText}`,
            });
        }
    });
});

$("#btn_agregar_item").on('click', function () {
    console.log('agregar ->', arrayItems.length);
    if (arrayItems.length < 6) {
        contador++;
        arrayItems.forEach(item => {
            if (item === contador) {
                contador++;
            }
        });
        arrayItems.push(contador);
        sessionStorage.setItem('arrayItems', JSON.stringify(arrayItems));
        $("#items_clon").append(`<div class="row" id="items_clon_${contador}">
        <div class="col-md-2">
            <label>Tipo de Vehículo:</label>
            <select class="form-control" name="tipo_vehiculo_[]" id="tipo_vehiculo_${contador}" onchange="datosShow(${contador})">
                <option value="">Opciones...</option>
                <option value="1">Automóvil</option>
                <option value="2">Motocicleta</option>
                <option value="3">Bicicleta</option>
            </select>
            <div id="error_tipo_vehiculo_${contador}" class="text-danger"></div>
        </div>
        <div class="col-md-9 row" id="div_datos_${contador}"></div>
        <div class="form-group col-md-1" style="text-align:center;">
            <button type="button" class="btn btn-danger " style="margin-top:25px;" onclick="retirarItem(${contador}) ">
                <i class="fas fa-times"></i>
            </button>
        </div>
        </div>`);
        if (contador % 2 === 0) {
            document.getElementById("items_clon_" + contador).style.backgroundColor = "#F3F3F3";
        }
    } else {
        $("#error_item").html(
            `<div class="alert alert-warning alert-dismissible" role="alert">
                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
                   </button>
                   <strong>El Sistema solo permite 6 vehículos por usuario...</strong>
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
    var i = arrayItems.indexOf(item);
    arrayItems.splice(i, 1);
    sessionStorage.setItem('arrayItems', JSON.stringify(arrayItems));
    $("#items_clon_" + item).remove();
    contador = 1;
}

function validarItem(campo) {
    if (campo.value.length > 0) {
        campo.classList.remove('has-error');
        document.getElementById("error_" + campo.id).textContent = '';
    }
}

function validarFile(campo) {
    if (campo.value.length > 0) {
        $("#lbl_" + campo.id).empty();
        $("#lbl_" + campo.id).append(`${document.getElementById(campo.id).files[0].name}`);
        $("#lbl_" + campo.id).attr('style', 'color:#000000;');
        $("#lbl_" + campo.id).removeClass('has-error');
        $("#error_" + campo.id).text('');
    }
}

function datosShow(item) {
    $("#tipo_vehiculo_" + item).removeClass('has-error');
    $("#error_tipo_vehiculo_" + item).text('');
    $("#div_datos_" + item).empty();
    if ($("#tipo_vehiculo_" + item).val() == 3) {
        $("#div_datos_" + item).append(`<div class="col-md-3">
        <label>Marca:</label>
        <input type="text" class="form-control" name="modelo_[]" id="modelo_${item}" onchange="validarItem(this)">
        <div id="error_modelo_${item}" class="text-danger"></div>
    </div>
    <div class="col-md-3">
        <label>Color:</label>
        <input type="text" class="form-control" name="color_[]" id="color_${item}" onchange="validarItem(this)">
        <div id="error_color_${item}" class="text-danger"></div>
    </div>
    <input type="hidden" name="placas_[]">
    <input type="hidden" name="vencimiento_[]">
    <input type="hidden" name="archivo_${item}">`);
    }
    if ($("#tipo_vehiculo_" + item).val() == 1 || $("#tipo_vehiculo_" + item).val() == 2) {
        $("#div_datos_" + item).append(`<div class="col-md-2">
            <label>Modelo:</label>
            <input type="text" class="form-control" name="modelo_[]" id="modelo_${item}" onchange="validarItem(this)">
            <div id="error_modelo_${item}" class="text-danger"></div>
        </div>
        <div class="col-md-2">
            <label>Color:</label>
            <input type="text" class="form-control" name="color_[]" id="color_${item}" onchange="validarItem(this)">
            <div id="error_color_${item}" class="text-danger"></div>
        </div>
        <div class="col-md-2">
            <label>Placas:</label>
            <input type="text" class="form-control" name="placas_[]" id="placas_${item}" onchange="validarItem(this)">
            <div id="error_placas_${item}" class="text-danger"></div>
        </div>
        <div class="col-md-3">
            <label>Vencimiento:</label>
            <input type="date" class="form-control" name="vencimiento_[]" id="vencimiento_${item}" onchange="validarItem(this)">
            <div id="error_vencimiento_${item}" class="text-danger"></div>
        </div>
        <div class="col-md-3">
            <label>Póliza:</label>
            <div class="custom-file">
                <input type="file" class="custom-file-input" accept=".pdf" name="archivo_${item}" id="archivo_${item}" onchange="validarFile(this)">
                <label class="custom-file-label" id="lbl_archivo_${item}" for="archivo_${item}">Selecionar</label>
            </div>
            <div id="error_archivo_${item}" class="text-danger"></div>
        </div>`);
    }
}

function imprimirRegistros() {
    document.getElementById("items_existentes").innerHTML = '';
    $.ajax({
        url: `${urls}estacionamiento/mis_vehiculos`,
        type: "post",
        dataType: "json",
        processData: false,
        contentType: false,
        cache: false,
        success: function (save) {
            console.log(save);
            if (save != false && save != null) {
                save.forEach(key => {
                    contador++;
                    contador_registrados++;
                    arrayItems.push(contador);
                    sessionStorage.setItem('arrayItems', JSON.stringify(arrayItems));
                    if (key.type_vehicle == 3) {
                        $("#items_existentes").append(`<div class="row" id="row_${contador_registrados}" style="padding-top:7px;">
                        <div class="col-md-2">
                            <label>Tipo de Vehículo:</label>
                            <input type="text" class="form-control" value="${tipo_vehiculo[key.type_vehicle]}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label>Modelo:</label>
                            <input type="text" class="form-control" value="${key.model}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label>Color:</label>
                            <input type="text" class="form-control" value="${key.color}" readonly>
                        </div>
                        <div class="col-md-2"></div>
                        <div class="form-group col-md-2" style="text-align:center;">
                            <button type="button" class="btn btn-outline-danger " style="margin-top:31px;" onclick="borrarItem(${key.id_item}) ">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                            <button type="button" class="btn btn-outline-info " style="margin-top:31px;" onclick="download('${key.qr_location}', ${key.id_record},${key.type_vehicle}) ">
                                <i class="fas fa-qrcode"></i>
                            </button>
                        </div>
                    </div>`);
                    } else {
                        $("#items_existentes").append(`<div class="row" id="row_${contador_registrados}" style="padding-top:7px;">
                            <div class="col-md-2">
                                <label>Tipo de Vehículo:</label>
                                <input type="text" class="form-control" value="${tipo_vehiculo[key.type_vehicle]}" readonly>
                            </div>
                            <div class="col-md-2">
                                <label>Modelo:</label>
                                <input type="text" class="form-control" value="${key.model}" readonly>
                            </div>
                            <div class="col-md-2">
                                <label>Color:</label>
                                <input type="text" class="form-control" value="${key.color}" readonly>
                            </div>
                            <div class="col-md-2">
                                <label>Placas:</label>
                                <input type="text" class="form-control" value="${key.placas}" readonly>
                            </div>
                            <div class="col-md-2">
                                <label>Vencimiento de Poliza:</label>
                                <input type="date" class="form-control" value="${key.date_expiration}" readonly>
                            </div>
                            <div class="form-group col-md-2" style="text-align:center;">
                                <button type="button" class="btn btn-outline-danger" title="Eliminar Registro" style="margin-top:31px;" onclick="borrarItem(${key.id_item}) ">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                <button type="button" class="btn btn-outline-primary" title="Actualizar Poliza" style="margin-top:31px;" onclick="actualizarItem(${key.id_item},'${key.model}') ">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-outline-info " style="margin-top:31px;" onclick="download('${key.qr_location}', ${key.id_record},${key.type_vehicle}) ">
                                    <i class="fas fa-qrcode"></i>
                                </button>
                            </div>
                        </div>`);
                    }
                    if (contador_registrados % 2 === 0) {
                        document.getElementById("row_" + contador_registrados).style.backgroundColor = "#F3F3F3";
                    }

                });
                $("#div_btn_descarga").show();
            } else {
                console.log('datos vacios');
            }
        }
    });

}

function borrarItem(id) {
    console.log('borar id -> ', id);
    Swal.fire({
        title: '<i class="fas fa-trash-alt"></i> ¿Eliminar Registro de Vehiculo?',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-check" style="margin-right: 10px;"></i>Confirmar',
        cancelButtonText: '<i class="fas fa-times" style="margin-right: 10px;"></i>Cancelar',
        confirmButtonColor: "#28A745",
    }).then((result) => {
        if (result.isConfirmed) {
            let timerInterval = Swal.fire({ //se le asigna un nombre al swal
                allowOutsideClick: false,
                title: `<i class="fas fa-envelope-open-text"></i> Notificando su cambio a HSE`,
                html: 'Espere unos Segundos.',
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
                },
            });
            var formData = new FormData();
            formData.append("id_item", id);
            $.ajax({
                data: formData,
                url: `${urls}estacionamiento/borrar_vehiculos`,
                type: "post",
                dataType: "json",
                processData: false,
                contentType: false,
                cache: false,
                success: function (delet) {
                    console.log('respuesta borrar', delet);
                    if (delet != false && delet != null) {
                        Swal.close(timerInterval);
                        Swal.fire({
                            icon: "success",
                            title: "¡Exito!",
                            text: "Eliminado Correctamente",
                        });
                        $("#items_clon").empty();
                        imprimirRegistros();
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "¡Algo salió Mal! Contactar con el Administrador",
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
            });
        }
    })
}

function download(location, id, type) {
    console.log('UBICACION:  ',location);
    let cargando = Swal.fire({ //se le asigna un nombre al swal
        allowOutsideClick: false,
        title: `DESCARGANDO <i class="fas fa-qrcode"></i>`,
        html: 'Espere unos Segundos.',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });
    const downloadOneDocument = document.createElement('a');
    downloadOneDocument.href = `${urls}${location}`;
    downloadOneDocument.download = `${id}_${tipo_vehiculo[type]}`;
    // downloadOneDocument.target = "";
    /*  var clicEvent = new MouseEvent("click", {
         view: window,
         bubbles: true,
         cancelable: true,
     }); */
    // downloadOneDocument.dispatchEvent(clicEvent);
    downloadOneDocument.click();
    Swal.close(cargando);

}

function actualizarItem(id, model) {
    document.getElementById("form_actualiza_poliza").reset();
    // $('#lbl_archivo_modal').empty();
    document.getElementById('lbl_archivo_modal').innerHTML = '';
    document.getElementById('archivo_modal').value = '';
    document.getElementById('vencimiento_modal').value = '';
    document.getElementById('error_archivo_modal').textContent = '';
    document.getElementById('error_vencimiento_modal').textContent = '';
    document.getElementById('lbl_archivo_modal').classList.remove('has-error');
    document.getElementById('vencimiento_modal').classList.remove('has-error');


    document.getElementById('id_item').value = id;
    $('#actualizarPolizaModalLabel').empty();
    $('#actualizarPolizaModalLabel').append(`<i class="fas fa-edit" style="margin-right: 10px;" ></i>Actualizar Póliza de ${model}`);
    $("#actualizarPolizaModal").modal('show');
}