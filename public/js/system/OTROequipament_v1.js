/**
 * ARCHIVO MODULO SYSTEMA / EQUIPOS
 * AUTOR:HORUS SAMAEL RIVAS PEDRAZA
 * horus.riv.ped@gmail.com
 * CEL:56 2439 2632
 */
/* remplazar required 
onchange="limpiarErrores(this)"
 */

var array_items = [1]
var items = 1

$(document).ready(function () {
    $("#id_user").select2();
});

document.getElementById("form_asignar_equipo").addEventListener("submit", function (event) {
    event.preventDefault();
    var errors = 0;
    const form = this;
    const btn = document.getElementById("btn_asignar_equipo");
    const nomina = document.getElementById('num_nomina');

    if ($('#id_user').val().length == 0) {
        console.log('pintar error;');
        errors++;
    }

    if (nomina.value.length == 0) {
        nomina.classList.add("has-error");
        errors++;
    } else {
        nomina.classList.remove("has-error");
    }
    if (errors != 0) { return false; }
    btn.disabled = true;
    let timerInterval = Swal.fire({
        title: 'Generando Permiso!',
        html: 'Espere unos Segundos.',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });
    const datos = new FormData($("#" + form.id)[0]);
    $.ajax({
        data: datos,
        url: urls + "sistemas/equipos-asignar-usuario",
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (response) {
            Swal.close(timerInterval);
            btn.disabled = false;
            if (response.hasOwnProperty('xdebug_message')) {
                Swal.fire({
                    icon: "error",
                    title: "Error de Exception",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
                console.log(response.xdebug_message);
            } else if (response === true) {
                form.reset();
                $("#div_clones").empty();
                Swal.fire({
                    icon: "success",
                    title: "!Registro completado!",
                    text: "Se ha Registrado con exito",
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

function seleccionarTipo(item) {
    $("#div_campos_equipos_" + item).empty();
    var tipo = document.getElementById('tipo_equipo_' + item).value;
    if (tipo == 1 || tipo == 2) {
        $("#div_campos_equipos_" + item).append(`
        <div class="col-md-3">
            <label for="equipo_usuario_${item}">Usuario:</label>
            <input type="text" name="equipo_usuario_[]" id="equipo_usuario_${item}" class="form-control" required>
            <div class="text-danger" id="error_equipo_usuario_${item}"></div>
        </div>
        <div class="col-md-3">
            <label for="equipo_pw_${item}">Contraseña:</label>
            <input type="text" name="equipo_pw_[]" id="equipo_pw_${item}" class="form-control" required>
            <div class="text-danger" id="error_equipo_pw_${item}"></div>
        </div>
        <div class="col-md-3">
            <label for="equipo_ip_${item}">IP:</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">192.1.</span>
                </div>
                <input type="number" max="15"  name="equipo_ip_g_[]" id="equipo_ip_g_${item}" class="form-control" required>
                <div class="input-group-prepend">
                    <span class="input-group-text">.</span>
                </div>
                <input type="number" max="255"  name="equipo_ip_n_[]" id="equipo_ip_n_${item}" class="form-control" required>
            </div>
            <div class="text-danger" id="error_equipo_ip_${item}"></div>
        </div>
        <div class="col-md-3">
            <label for="equipo_model_${item}">Modelo:</label>
            <input type="text" name="equipo_model_[]" id="equipo_model_${item}" class="form-control" required>
            <div class="text-danger" id="error_equipo_model_${item}"></div>
        </div>
        <div class="col-md-3">
            <label for="equipo_marca_${item}">Marca:</label>
            <input type="text" name="equipo_marca_[]" id="equipo_marca_${item}" class="form-control" required>
            <div class="text-danger" id="error_equipo_marca_${item}"></div>
        </div>`);

    } else if (tipo == 3) {
        $("#div_campos_equipos_" + item).append(`
        <input type="hidden" name="equipo_usuario_[]">
        <input type="hidden" name="equipo_pw_[]">
        <input type="hidden" name="equipo_ip_g_[]">
        <input type="hidden" name="equipo_ip_n_[]">
        <div class="col-md-3">
            <label for="equipo_model_${item}">Modelo:</label>
            <input type="text" name="equipo_model_[]" id="equipo_model_${item}" class="form-control" required>
            <div class="text-danger" id="error_equipo_model_${item}"></div>
        </div>
        <div class="col-md-3">
            <label for="equipo_marca_${item}">Marca:</label>
            <input type="text" name="equipo_marca_[]" id="equipo_marca_${item}" class="form-control" required>
            <div class="text-danger" id="error_equipo_marca_${item}"></div>
        </div>`);

    }
}

document.getElementById("btn_agregar_item").addEventListener("click", function () {
    if (array_items.length < 6) {
        items++;
        array_items.forEach(item => {
            if (item === items) {
                items++;
            }
        });
        array_items.push(items);
        $("#div_clones").append(`
        <div class="form-row" id="div_clon_${items}">
            <div class="col-md-3" style="margin-bottom: 5px;">
                <label for="tipo_equipo_${items}">Tipo de Equipo:</label>
                <select name="tipo_equipo_[]" id="tipo_equipo_${items}" class="form-control" onchange="seleccionarTipo(${items})" required>
                    <option value="">Opciones...</option>
                    <option value="1">Laptop</option>
                    <option value="2">Desktop</option>
                    <option value="3">Tablet</option>
                </select>
                <div class="text-danger" id="error_tipo_equipo_${items}"></div>
            </div>
            <div id="div_campos_equipos_${items}" class="row"></div>
        </div>`);
        if (array_items.length % 2 === 0) {
            document.getElementById("div_clon_" + items).style.backgroundColor = "#F3F3F3";
        }
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

document.getElementById('num_nomina').addEventListener("change", function () {
    const depto = document.getElementById('depto');
    const id_depto = document.getElementById('id_depto');
    const user = $("#id_user");
    const payroll = this;

    depto.value = '';
    user.val('').trigger('change');
    id_depto.value = '';

    payroll.classList.remove("has-error");

    if (payroll.value.length > 0) {
        var data = new FormData();
        data.append('ID', payroll.value);
        $.ajax({
            data: data,
            url: `${urls}sistemas/datos_usuario`,
            type: "post",
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (respUser) {
                if (respUser) {
                    depto.value = respUser.departamento;
                    user.val(respUser.id_user).trigger('change');
                    id_depto.value = respUser.id_departament;
                    depto.classList.remove("has-error");
                    user.removeClass("has-error");
                } else {
                    payroll.classList.add("has-error");
                    // $("#error_ID_").text(_nomina_);
                }
            }
        });
    }
});

$("#id_user").on('change', function () {
    const depto = document.getElementById('depto');
    const id_depto = document.getElementById('id_depto');
    const user = this;
    const payroll = document.getElementById('num_nomina');;

    depto.value = '';
    // payroll.value = '';
    id_depto.value = '';

    user.classList.remove("has-error");
    if (user.value.length > 0) {
        var data = new FormData();
        data.append('ID_U', user.value);
        $.ajax({
            data: data,
            url: `${urls}sistemas/datos_usuario`,
            type: "post",
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (respUser) {
                if (respUser !== false) {
                    depto.value = respUser.departamento;
                    payroll.value = respUser.nomina;
                    id_depto.value = respUser.id_departament;

                    depto.classList.remove("has-error");
                    payroll.classList.remove("has-error");
                } else {
                    user.classList.add("has-error");
                    // $("#error_ID_").text(_nomina_);
                }
            }
        });
    }
});