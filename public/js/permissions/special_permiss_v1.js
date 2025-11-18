/**
 * ARCHIVO MODULO PERMISOS
 * AUTOR: HORUS SAMAEL RIVAS PEDRAZA
 * EMAIL: horus.riv.ped@gmail.com
 * CEL:55 2439 2632
 */
// var selectedDays = 0;
const lbl_status = { 1: "HABILITADO", 2: "DESHABILITADO" };
$(document).ready(function () {
    tbl_permiss_especial = $("#tabla_permisos_especiales").dataTable({
        processing: true,
        ajax: {
            method: "post",
            url: urls + "permisos/fechas_permiso_especial",
            dataSrc: "",
        },
        lengthChange: true,
        ordering: true,
        responsive: true,
        autoWidth: true,
        rowId: "staffId",
        dom: "lfrtip",
        buttons: [
            {
                extend: "excelHtml5",
                title: "Permisos",
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6],
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
                data: "id_day_festive",
                title: "FOLIO",
                className: "text-center",
            },
            {
                data: null,
                title: "ESTADO",
                className: "text-center",
            },
            {
                data: "fecha_permiso",
                title: "DIA",
                className: "text-center",
            },
            {
                data: "tipo",
                title: "MOTIVO",
                className: "text-center",
            },
            {
                data: "tiempo",
                title: "TIEMPO PERMISO",
                className: "text-center",
            },
            {
                data: "hora_in",
                title: "HORA ENTRADA",
                className: "text-center",
            },
            {
                data: null,
                title: "ACCIONES",
                className: "text-center",
            },
        ],
        destroy: "true",
        columnDefs: [
            {
                targets: 1,
                render: function (data, type, full, meta) {
                    setTimeout(function () {
                        if (data["enabled_status"] == 1) {
                            $("#radio_" + data["id_day_festive"]).attr('checked', true);
                        } else {
                            $("#radio_" + data["id_day_festive"]).attr('checked', false);
                        }
                    }, 2);
                    return `<div class="pull-right mr-auto">
                    <div class="row">
                    <div class="col-md-6" style="text-align:end">
                    <label id="lbl_extra_${data["id_day_festive"]}">${lbl_status[data["enabled_status"]]}</label>
                    </div>
                    <div class="col-md-6" style="text-align:initial;padding-top: 1px;">
                    <div class="toggle">
                        <input type="checkbox" id="radio_${data["id_day_festive"]}" onchange="OnOffExtra(${data["id_day_festive"]})">
                        <label></label>
                        </div>
                    </div>
                </div> `;
                },
            },
            {
                targets: 6,
                render: function (data, type, full, meta) {
                    return ` <div class="pull-right mr-auto">    
                        <button type="button" class="btn btn-outline-danger btn-sm "  onClick=handleDeletePermissions(${data["id_day_festive"]})>
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div> `;
                },
            },
            /*  {
              targets: [0],
              visible: false,
              searchable: false,
            }, */
        ],

        order: [[0, "DESC"]],

        createdRow: (row, data) => {
            $(row).attr("id", "permiss_special_" + data.id_day_festive);
        },
    }).DataTable();
    $("#tabla_permisos_especiales thead").addClass("thead-dark text-center");

});

$("#form_alta_permiss").submit(function (e) {
    e.preventDefault();
    var errores = 0;
    const error_mensaje = 'Campo Requerido'
    const btn = document.getElementById('btn_alta_permiss');
    const tipo_permiso = document.getElementById('tipo_permiso');
    const dia_permiso = document.getElementById('dia_permiso');
    const hora_entrada = document.getElementById('hora_entrada');
    const horas = document.getElementById('horas');
    const min = document.getElementById('min');
    const in_permis = parseInt(document.getElementById('in_permis').value);
    const out_permis = parseInt(document.getElementById('out_permis').value);
    const absence_permis = parseInt(document.getElementById('absence_permis').value);
    const motivo = document.getElementById('motivo');
    const obs = document.getElementById('obs');
    document.getElementById("error_" + tipo_permiso.id).textContent = '';

    if (tipo_permiso.value.length == 0) {
        document.getElementById("error_" + tipo_permiso.id).textContent = error_mensaje;
        errores++;
    } else if (tipo_permiso.value == 2) {
        if (dia_permiso.value.length == 0) {
            document.getElementById("error_" + dia_permiso.id).textContent = error_mensaje;
            dia_permiso.classList.add('has-error');
            errores++;
        } else {
            document.getElementById("error_" + dia_permiso.id).textContent = '';
            dia_permiso.classList.remove('has-error');
        }

        if (hora_entrada.value.length == 0) {
            document.getElementById("error_" + hora_entrada.id).textContent = error_mensaje;
            hora_entrada.classList.add('has-error');
            errores++;
        } else {
            document.getElementById("error_" + hora_entrada.id).textContent = '';
            hora_entrada.classList.remove('has-error');
        }
    } else if (tipo_permiso.value == 1) {
        if (dia_permiso.value.length == 0) {
            document.getElementById("error_" + dia_permiso.id).textContent = error_mensaje;
            dia_permiso.classList.add('has-error');
            errores++;
        } else {
            document.getElementById("error_" + dia_permiso.id).textContent = '';
            dia_permiso.classList.remove('has-error');
        }

        if ((min.value.length == 0 || horas.value.length == 0)) {
            if (horas.value.length == 0) {
                horas.classList.add('has-error');
            }
            if (min.value.length == 0) {
                min.classList.add('has-error');
            }
            errores++;
            document.getElementById("error_tiempo_permiso").textContent = error_mensaje;
        } else if (min.value == 0 && horas.value == 0) {
            document.getElementById("error_tiempo_permiso").textContent = 'Llena Correctamente';
            horas.classList.add('has-error');
            min.classList.add('has-error');
            errores++;
        }
        else {
            document.getElementById("error_tiempo_permiso").textContent = '';
            horas.classList.remove('has-error');
            min.classList.remove('has-error');
        }

        const chbx = in_permis + out_permis + absence_permis
        if (chbx == 0) {
            document.getElementById("error_cbx_tipo_permiso").textContent = error_mensaje;
            errores++;
        } else {
            document.getElementById("error_cbx_tipo_permiso").textContent = '';
        }
    }

    if (tipo_permiso.value.length != 0) {
        if (motivo.value.length == 0) {
            document.getElementById("error_" + motivo.id).textContent = error_mensaje;
            motivo.classList.add('has-error');
            errores++;
        } else {
            document.getElementById("error_" + motivo.id).textContent = '';
            motivo.classList.remove('has-error');
        }

        if (obs.value.length == 0) {
            document.getElementById("error_" + obs.id).textContent = error_mensaje;
            obs.classList.add('has-error');
            errores++;
        } else {
            document.getElementById("error_" + obs.id).textContent = '';
            obs.classList.remove('has-error');
        }
    }

    if (errores != 0) {
        console.log('errores');
        return false;
    }

    const timerInterval = Swal.fire({ //se le asigna un nombre al swal
        title: 'Generando Permiso!',
        html: 'Espere unos Segundos.',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });
    btn.disabled = true;
    const data = new FormData($("#form_alta_permiss")[0]);
    console.log(data);
    $.ajax({
        data: data,
        url: `${urls}permisos/registrar_permiso_especial`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (response) {
            btn.disabled = false;
            Swal.close(timerInterval);
            if (response == true) {
                Swal.fire({
                    icon: "success",
                    title: "!Generación Exitosa!",
                    text: "Se ha Generado el Motivo de Permiso Especial",
                });
                tipoPermiso();
                $("#div_dia_permiso").hide();
                $("#form_alta_permiss")[0].reset();
                $(".btn-opcion").removeClass("active focus");
                tbl_permiss_especial.ajax.reload(null, false);
            } else if (response.exception) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Ha ocurrido un error en el servidor! Contactar con el Administrador",
                });
                console.log(response.exception);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
            }
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        $("#radio_" + id_item).prop("disabled", false);
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

function tipoPermiso(type) {
    document.getElementById("error_tipo_permiso").textContent = '';
    $(".chbx-opcion").prop('checked', false);
    $("#motivo").prop('readonly', false);
    $("#obs").prop('readonly', false);
    // $("#in_permis").prop('checked',false);
    $("#obs").val('');
    $("#motivo").val('');
    $(".chbx-opcion").val(0);
    $("#dia_permiso").val('');
    $("#hora_entrada").val('');
    $("#tiempo_permiso").val('');
    $("#horas").val('');
    $("#min").val('');
    $("#tipo_permiso").val(type);

    $("#div_dia_permiso").show();
    $("#div_hora_entrada").hide();
    $("#div_tiempo_permiso").hide();
    $("#div_cbx_tipo_permiso").hide();
    $("#div_motivo").hide();
    $("#div_obs").hide();
    // inputDays.destroy();
    if (type == 2) {
        flatpickr("#dia_permiso", {
            locale: "es",
            dateFormat: "Y-m-d",
            minDate: 'today',
        });
        $("#div_hora_entrada").show();
        $("#div_motivo").show();
        $("#div_obs").show();
        $("#motivo").prop('readonly', true);
        $("#obs").prop('readonly', true);
        $("#motivo").val("Trafico");
        $("#obs").val("Situación de tráfico pesado que está afectando significativamente el tiempo de desplazamiento.");
    }
    if (type == 1) {
        flatpickr("#dia_permiso", {
            locale: "es",
            mode: "range",
            dateFormat: "Y-m-d",
            conjunction: ",",
            minDate: "today", // minimo dia de hoy
        });
        flatpickr("#dia_permiso", {
            locale: "es",
            mode: "multiple",
            dateFormat: "Y-m-d",
            minDate: 'today',
        });
        $("#div_tiempo_permiso").show();
        $("#div_cbx_tipo_permiso").show();
        $("#div_motivo").show();
        $("#div_obs").show();
    }
}

function limpiarError(campo) {
    if (campo.value.length > 0) {
        campo.classList.remove('has-error');
        document.getElementById("error_" + campo.id).textContent = '';
    }
}
function limpiarTiempo(campo) {
    if (campo.value.length > 0) {
        document.getElementById("min").classList.remove('has-error');
        document.getElementById("horas").classList.remove('has-error');
        document.getElementById("error_tiempo_permiso").textContent = '';
    }
}
function limpiarCbx(campo) {
    if (campo.checked) {
        document.getElementById(campo.id).value = 1
    } else {
        document.getElementById(campo.id).value = 0
    }
    $("#error_cbx_tipo_permiso").text('');
}

function OnOffExtra(id_item) {
    const permis = $(`#permiss_special_${id_item} td`)[2].innerHTML;
    $("#radio_" + id_item).prop("disabled", true);
    $("#lbl_extra_" + id_item).empty();
    if ($("#radio_" + id_item).is(':checked')) {
        texto = lbl_status[1]; estado = 1;
    } else {
        texto = lbl_status[2]; estado = 2;
    }
    $("#lbl_extra_" + id_item).append(texto);
    const data = new FormData();
    data.append("id", id_item);
    data.append("status", estado);

    $.ajax({
        data: data,
        url: `${urls}permisos/activar_desactivar_permisos_especiales`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (response) {
            $("#radio_" + id_item).prop("disabled", false);
            if (response) {
                Swal.fire(`!SÉ HA ${texto}!`,
                    `El estado del Permiso Especial del dia: <b>${permis}</b> ha sido actualizado.`,
                    "success");
                tbl_permiss_especial.ajax.reload(null, false);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
            }
        }


    }).fail(function (jqXHR, textStatus, errorThrown) {
        $("#radio_" + id_item).prop("disabled", false);
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
}

function handleDeletePermissions(id_folio) {
    const permis = $(`#permiss_special_${id_folio} td`)[2].innerHTML;
    Swal.fire({
        title: `Deseas Eliminar el Permiso del dia: ${permis} ?`,
        text: `Una vez Eliminado no podras Recuperarlo!`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Eliminar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.value) {
            const dataForm = new FormData();
            dataForm.append("id", id_folio);
            $.ajax({
                data: dataForm,
                url: `${urls}permisos/eliminar_permisos_especiales`,
                type: "post",
                processData: false,
                contentType: false,
                dataType: "json",
                success: function (response) {
                    if (response) {
                        Swal.fire(`!SÉ HA ELIMINADO!`,
                            `El Permiso Especial del dia: ${permis} ha sido eliminado.`,
                            "success");
                        tbl_permiss_especial.ajax.reload(null, false);
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