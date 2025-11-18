/*
 * ARCHIVO MODULO RECORRIDOS HSE
 * AUTOR:Horus Samael Rivas Pedraza
 * EMAIL:horus.riv.ped@gmail.com
 */
var msj_correo_obligatorio = false;

$(document).ready(function () {
    $("#id_departamento").select2();
    $("#id_departamento_recorrido").select2();
});

$("#form_registro_recorrido").submit(function (e) {
    e.preventDefault();
    const btn = document.getElementById('btn_registro_recorrido');
    const departamento_recorido = document.getElementById('id_departamento_recorrido');
    var errors = 0;
    // 13
    if (departamento_recorido.value.length == 0) {
        errors++;
        document.getElementById('error_' + departamento_recorido.id).innerText = 'Campo Requerido';
    } else {
        document.getElementById('error_' + departamento_recorido.id).innerText = '';
    }

    for (let i = 14; i > 0; i--) {
        const radioBtn = document.getElementById('valor_campo_' + i);
        if (radioBtn.value.length == 0) {
            errors++;
            document.getElementById('error_campo_' + i).innerText = 'Campo Requerido';
            radioBtn.focus();
        } else {
            document.getElementById('error_campo_' + i).innerText = '';
        }
    }
    if (errors > 0) { return; }

    const form = document.getElementById('form_registro_recorrido');
    var suma = 0;
    for (var i = 0; i < form.elements.length; i++) {
        var element = form.elements[i];
        if (element.type === 'radio' && element.checked) {
            suma += parseFloat(element.value);
        }
    }
    btn.disabled = true;
    const timerInterval = Swal.fire({
        allowOutsideClick: false,
        title: '¡Generando Reporte y Notificando al Jefe!',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });

    const data_form = new FormData($("#form_registro_recorrido")[0]);
    data_form.append('calificacion', suma);
    $.ajax({
        type: "post",
        url: `${urls}recorridos-HSE/registrar_recorido`,
        data: data_form,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (save) {
            console.log(save);
            btn.disabled = false;
            Swal.close(timerInterval);
            if (save.hasOwnProperty('xdebug_message')) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal en registrar_recorido! Contactar con el Administrador",
                });
                console.log(save.xdebug_message);
            } else if (save === true) {
                $("#form_registro_recorrido")[0].reset();
                $(".btn-opcion").removeClass("active");
                $(".imagePreview").attr("style", "display:none");
                $(".btn_tomar_foto").empty();
                $(".btn_tomar_foto").append(`<i class="fas fa-camera"  style="margin-right: 10px;"></i>TOMAR FOTO`);
                document.getElementById('card_registro_recorrido').click();
                $('#id_departamento_recorrido').val('').trigger('change');
                Swal.fire({
                    icon: 'success',
                    title: "¡Registro Exitoso!",
                    text: 'Se registró y notificó el recorrido.',
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
    });
});

$("#form_reporte_infraccion").submit(function (e) {
    e.preventDefault();
    const btn = document.getElementById('btn_reporte_infraccion');
    const tipo_reporte = document.getElementById('tipo_reporte');
    var errors = 0;
    if (tipo_reporte.value.length == 0) {
        errors++;
        $("#error_tipo_reporte").text('Campo Requerido');
    } else {
        $("#error_tipo_reporte").text('');
        console.log(tipo_reporte.value);
        if (tipo_reporte.value == 1) {
            const campo = document.getElementById('valor_gravedad');
            if (campo.value.length == 0) {
                errors++;
                $("#error_" + campo.id).text('Campo Requerido');
            } else {
                $("#error_" + campo.id).text('');
            }
            const id_usuario = document.getElementById('id_usuario');
            if (id_usuario.value.length == 0) {
                errors++;
                $("#error_nomina").text('Campo Requerido');
            } else {
                $("#error_nomina").text('');
            }
        }
        if (tipo_reporte.value == 2) {
            const id_departamento = document.getElementById('id_departamento');
            if (id_departamento.value.length == 0) {
                errors++;

                $("#error_" + id_departamento.id).text('Campo Requerido');
            } else {
                $("#error_" + id_departamento.id).text('');
            }
        }

        const tipo_incidencia = document.getElementById('tipo_incidencia');
        if (tipo_incidencia.value.length == 0) {
            errors++;
            $("#error_" + tipo_incidencia.id).text('Campo Requerido');
        } else {
            $("#error_" + tipo_incidencia.id).text('');
        }

        const foto_incidencia = document.getElementById('foto_incidencia');
        if (foto_incidencia.value.length == 0) {
            errors++;
            // $("#error_" + foto_incidencia.id).text('Campo Requerido');
            Swal.fire({
                title: `Falta Evidencia <i class="far fa-image" style="margin-left: 5px;"></i>`,
            });
        }
    }

    if (errors > 0) {
        return;
    }
    btn.disabled = true;
    const timerInterval = Swal.fire({
        // allowOutsideClick: false,
        title: '¡Generando Incidencia y Notificando al Jefe!',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });

    const data_form = new FormData($("#form_reporte_infraccion")[0]);
    $.ajax({
        type: "post",
        url: `${urls}recorridos-HSE/registrar_incidencia`,
        data: data_form,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (save) {
            console.log(save);
            btn.disabled = false;
            Swal.close(timerInterval);
            if (save.hasOwnProperty('xdebug_message')) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal en registrar_recorido! Contactar con el Administrador",
                });
                console.log(save.xdebug_message);
            } else if (save === true) {
                msj_correo_obligatorio = false;
                $("#form_reporte_infraccion")[0].reset();
                $(".btn-opcion").removeClass("active");
                $(".imagePreview").attr("style", "display:none");
                $(".btn_tomar_foto").empty();
                $(".btn_tomar_foto").append(`<i class="fas fa-camera"  style="margin-right: 10px;"></i>TOMAR FOTO`);
                $("#div_usuario").hide();
                $("#div_departamento").hide();
                $('#id_departamento').val('').trigger('change');
                $("#div_reincidencias").hide();
                $("#div_reincidencias").empty();
                $("#div_msj_rh").hide();
                document.getElementById('card_reporte_infraccion').click();
                Swal.fire({
                    icon: 'success',
                    title: "¡Registro Exitoso!",
                    text: 'Se registró y notificó la incidencia.',
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
    });
});


function mostrarImagenPrevia1(campo) {
    // preventDefault();
    const input = campo;
    const preview = document.getElementById('previa_' + input.id);
    $('#btn_' + input.id).empty();

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            $('#btn_' + input.id).append(`<i class="fas fa-camera"  style="margin-right: 10px;"></i>RETOMAR FOTO`);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function limpiarError(campo) {
    document.getElementById(campo.id).classList.remove('has-error');
    document.getElementById('error_' + campo.id).textContent = '';
}

function actualizarValor(checkbox) {
    const valorInput = document.getElementById("opc_" + checkbox.id);
    if (checkbox.checked) {
        valorInput.value = 1;
        $("#lbl_" + checkbox.id).text("SI");
    } else {
        valorInput.value = 0;
        $("#lbl_" + checkbox.id).text("NO");
    }
}

$("#id_departamento").on('change', function () {
    $("#otro_depto").val('');
    $("#div_otro").hide();
    if ($("#id_departamento").val().length > 0) {
        if ($("#id_departamento").val() == 0) {
            $("#div_otro").show();
        }
    }
})


function tipoReporte(val) {
    $("#div_otro").hide();
    $("#div_usuario").hide();
    $("#div_departamento").hide();
    $("#btn_reporte_infraccion").hide();

    $("#nomina").val('');
    $("#otro_depto").val('');
    $("#id_usuario").val('');
    $("#nombre_usuario").val('');
    $("#valor_gravedad").val('');
    $(".btn-opcion-sub").removeClass('active');
    // $("#gravedad").removeClass('active');
    $("#id_departamento").val('').trigger("change");

    $("#tipo_reporte").val(val);
    $("#error_tipo_reporte").text('');

    if (val == 1) {
        $("#div_usuario").show();
    } else {
        $("#div_departamento").show();
        $("#btn_reporte_infraccion").show();
    }
    pintarListaReportes(val);
}

function pintarNombre(nomina) {
    nomina.classList.remove('has-error');
    document.getElementById('error_' + nomina.id).textContent = '';
    $("#id_usuario").val('');
    $("#nombre_usuario").val('');
    if (nomina.value.length == 0) {
        return;
    }
    const data = new FormData();
    data.append('payroll_number', nomina.value);
    $.ajax({
        data: data,
        url: `${urls}sistemas/datos_usuario_actualizado`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (respUser) {
            // console.log(respUser);
            if (respUser) {
                $("#id_usuario").val(respUser.id_user);
                $("#nombre_usuario").val(respUser.nombre_completo);
                rastrearIncidencias(respUser.id_user);
            } else {
                nomina.classList.add('has-error');
                document.getElementById('error_' + nomina.id).textContent = 'Nomina no Encontrada';
            }
        }
    });
}

function pintarListaReportes(tipo) {
    $("#tipo_incidencia").empty();
    const data = new FormData();
    data.append('type_category', tipo);
    $.ajax({
        data: data,
        url: `${urls}recorridos-HSE/buscar_lista_categoria`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (respData) {
            if (respData) {
                $("#tipo_incidencia").append(`<option value="">Seleccionar Opción...</option>`);
                respData.forEach(dato => {
                    $("#tipo_incidencia").append(`<option value="${dato.id_category}">${dato.txt_category}</option>`);
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal al buscar_lista_categoria! Contactar con el Administrador del Sistema",
                });
            }
        }
    });
}

function valorRadioBtn(campo, opcion) {
    document.getElementById('valor_' + campo.id).value = opcion;
    $("#error_" + campo.id).text('');
}

function gravedadNivel(campo, opcion) {
    document.getElementById('valor_' + campo.id).value = opcion;
    $("#error_" + campo.id).text('');
    if (msj_correo_obligatorio == false) {
        $("#div_msj_rh").hide();
        $("#correo_rh").val();
        $("#correo_rh").removeClass('has-error');
        $("#error_correo_rh").text('');
        if (opcion == 3) {
            $("#div_msj_rh").show();
        }

    }
}

function rastrearIncidencias(respUser) {
    console.log('rastrearincidencias');
    console.log(respUser);
    const user = respUser;
    msj_correo_obligatorio = false;
    $("#div_reincidencias").hide();
    $("#div_reincidencias").empty();
    $("#btn_reporte_infraccion").hide();
    if (user.length > 0) {
        const data = new FormData();
        data.append('id_user', user);
        $.ajax({
            data: data,
            url: `${urls}recorridos-HSE/todas_insidencias_usuario`,
            type: "post",
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (respData) {
                console.log(respData.length);
                if (respData.length > 0) {
                    $("#div_reincidencias").show();
                    i = 0
                    respData.forEach(dato => {
                        $("#div_reincidencias").append(`<div class="col-md-3 space-up" style="border: 2px dotted ${dato.color};background: #E9E9E9;">
                            <label style="font-size: 15px;">${dato.fecha} a las ${dato.hora} por ${dato.responsable}</label>
                        </div>`);
                        i++;
                    });
                    if (i >= 3) {
                        msj_correo_obligatorio = true;
                        $("#div_msj_rh").show();
                    }
                }
            }
        });
    }
    $("#btn_reporte_infraccion").show();
}



// Función para detectar si es un dispositivo móvil
function isMobile() {
    return /Mobi|Android/i.test(navigator.userAgent);
}

// Función para manejar la toma de foto
function tomarFoto() {
    const inputFile = document.getElementById('foto_incidencia');
    if (isMobile()) {
        // Si es un dispositivo móvil, abre la cámara
        inputFile.setAttribute('capture','camera');
    } else {
        // Si es un PC, permite seleccionar un archivo desde el explorador
        inputFile.removeAttribute('capture');
    }
    inputFile.click(); // Simula el clic en el input file
}

// Función para mostrar la imagen previa
function mostrarImagenPrevia(input) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imgPreview = document.getElementById('previa_foto_incidencia');
            imgPreview.src = e.target.result;
            imgPreview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
} 

/* 
$(document).ready(function(){
    // Al hacer clic en el botón, simulamos un clic en el input file
    $('#btn_abrir_galeria').on('click', function(){
        $('#input_galeria').click();
    });

    // Cuando se selecciona un archivo (imagen) en la galería
    $('#input_galeria').on('change', function(){
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Mostrar la imagen seleccionada en la vista previa
                $('#imagen_previa').attr('src', e.target.result).show();
            };
            reader.readAsDataURL(file); // Lee el archivo como una URL de datos
        }
    });
});  */


/* $(document).ready(function() {
    $('#btnAbrirGaleria').on('click', function() {
        $('#galeriaInput').click(); // Simula el clic en el input de tipo file
    });
});

 */

