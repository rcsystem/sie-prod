/**
 * ARCHIVO MODULO SYSTEMA / EQUIPOS
 * AUTOR:HORUS SAMAEL RIVAS PEDRAZA
 * horus.riv.ped@gmail.com
 * CEL:56 2439 2632
 */
var i = 0;
var contInv = 0;
var contEquip = 0;
var arrayItemsInv = [];
var arrayItemsEquip = [];

$(document).ready(function () {
    tbl_equipos_asignados = $("#tbl_equipos_asignados").dataTable({
        processing: true,
        ajax: {
            method: "post",
            url: `${urls}sistemas/historial_asinacion`,
            dataSrc: "",
        },
        lengthChange: true,
        ordering: true,
        responsive: true,
        autoWidth: false,
        rowId: "staffId",
        //dom: "lfrtip",
        dom: 'Bfrtip',

        buttons: [
             {
                extend: "excelHtml5",
                title: "Historial de Asignaciones de Equipos",
                exportOptions: {
                    columns: [0, 1, 2, 3,4,5,6,7],
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
                data: "folio",
                title: "FOLIO",
                className: "text-center",
            },
            {
                data: "label_equip",
                title: "ETIQUETA",
                className: "text-center",
            },
            
            {
                data: "serial_number",
                title: "IMEI / SERIE",
                className: "text-center",
            },
            {
                data: "model",
                title: "MODELO",
                className: "text-center",
            },
            {
                data: "user_name",
                title: "USUARIO",
                className: "text-center",
            },
            {
                data: "payroll_number",
                title: "NOMINA",
                className: "text-center",
            },
            {
                data: "assigner_at",
                title: "ASIGNACION",
                className: "text-center",
            },
            {
                data: "collector_at",
                title: "RECOLECCION",
                className: "text-center",
            },
            {
                data: null,
                render: function (data, type, full, meta) {
                    return ` <div class="mr-auto">
                          
                          <a href="${urls}sistemas/pdf-responsiva-asignacion/${$.md5(key + data['folio'])}" target="_blank" title="Responsiva equipo" class="btn btn-danger btn-sm">
                              <i class="fas fa-file-pdf"></i>
                          </a>
                           <button type="button" class="btn btn-success btn-sm" title="Mantenimiento Equipo" onclick="Mantto(${data['id_equip']})" data-toggle="modal" data-target="#manttoModal">
                              <i class="far fa-calendar-alt"></i>
                          </button>
                      </div> `;
                },
                title: "ACCIONES",
                className: "text-center",
            },
        ],
        destroy: "true",
        /* columnDefs: [
            {
                targets: [0],
                visible: false,
                searchable: false,
            },
        ], */
        order: [[0, "DESC"]],
        createdRow: (row, data) => {
            $(row).attr("id", "request_" + data.id_request);
        },
    }).DataTable();
    $("#tbl_equipos_asignados thead").addClass("thead-dark text-center");
});

function Mantto(id_equip) {

     $('#manttoModal').modal('show');
    $("#id_equipo_mantto").val(id_equip);

}

$("#manttoForm").submit(function (e) {
    e.preventDefault();
    const btn = document.getElementById('btn_mantto');
    btn.disabled = true;
    const timerInterval = Swal.fire({ //se le asigna un nombre al swal
        allowOutsideClick: false,
        title: '¡Guardando Registro!',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });

   $.ajax({
       url: `${urls}sistemas/registrar_mantenimiento`,
       type: "post",
       data: $("#manttoForm").serialize(),
       dataType: "json",
       success: function (resp) {
           if (resp.success) {
               Swal.fire({
                   icon: 'success',
                   title: '¡Registro Guardado!',
                   text: resp.message,
                   timer: 2000,
                   showConfirmButton: false,
                   willClose: () => {
                       btn.disabled = false;
                       $("#manttoModal").modal('hide');
                   }
               });
           } else {
               Swal.fire({
                   icon: 'error',
                   title: '¡Error!',
                   text: resp.message,
                   willClose: () => {
                       btn.disabled = false;
                   }
               });
           }
       },
       error: function () {
           Swal.fire({
               icon: 'error',
               title: '¡Error!',
               text: 'No se pudo guardar el registro.',
               willClose: () => {
                   btn.disabled = false;
               }
           });
       }
   });
});



function claenInput(input) {
    if (input.value.length > 0) {
        input.classList.remove('has-error');
        document.getElementById("error_" + input.id).textContent = '';
    }
}

function limpiarClon(input) {
    if (input.value.length > 0) {
        document.getElementById('div_' + input.id).classList.remove('has-error-bg');
        // document.getElementById("error_" + input.id).textContent = '';
    }
}

$("#procesos").submit(function (e) {
    e.preventDefault();
    errores = 0;
    const btn = document.getElementById('btn_procesos');
    const tipo = document.getElementById('tipo_proceso').value;
    if (tipo.length == 0) {
        errores++;
    } else {
        if (tipo == 1) {
            if ($("#id_equipo").val().length == 0) {
                errores++;
                $("#etiqueta").addClass('has-error');
                $("#error_etiqueta").text('Campo Requerido');
            } else {
                $("#etiqueta").removeClass('has-error');
                $("#error_etiqueta").text('');
            }

            if ($("#equipo").val().length == 0) {
                errores++;
                $("#equipo").addClass('has-error');
                $("#error_equipo").text('Campo Requerido');
            } else {
                $("#equipo").removeClass('has-error');
                $("#error_equipo").text('');
            }

            if ($("#id_user").val().length == 0) {
                errores++;
                $("#div_id_user").addClass('has-error-bg');
                $("#error_id_user").text('Campo Requerido');
            } else {
                $("#div_id_user").removeClass('has-error-bg');
                $("#error_id_user").text('');
            }

            if (arrayItemsEquip.length > 0) {
                arrayItemsEquip.forEach(item => {
                    const otro_equipos = document.getElementById('otros_equipos_' + item).value.length;
                    const div_otro_equipos = document.getElementById('div_otros_equipos_' + item);
                    if (otro_equipos == 0) {
                        div_otro_equipos.classList.add('has-error-bg');
                        errores++;
                    } else {
                        div_otro_equipos.classList.remove('has-error-bg');
                    }
                });
            }
            if (arrayItemsInv.length > 0) {
                arrayItemsInv.forEach(item => {
                    const inventario = document.getElementById('inventario_' + item).value.length;
                    const div_inventario = document.getElementById('div_inventario_' + item);
                    if (inventario == 0) {
                        div_inventario.classList.add('has-error-bg');
                        errores++;
                    } else {
                        div_inventario.classList.remove('has-error-bg');
                    }
                });
            }
        }
        if (tipo == 2) {
            if ($("#id_request_ant").val().length == 0) {
                errores++;
                $("#equipo1").addClass('has-error');
                $("#etiqueta1").addClass('has-error');
                $("#error_equipo1").text('Campo Requerido');
                $("#error_etiqueta1").text('Campo Requerido');
            } else {
                $("#error_equipo1").text('');
                $("#error_etiqueta1").text('');
                $("#equipo1").removeClass('has-error');
                $("#etiqueta1").removeClass('has-error');
            }

            if ($("#id_equipo2").val().length == 0) {
                errores++;
                $("#equipo2").addClass('has-error');
                $("#etiqueta2").addClass('has-error');
                $("#error_equipo2").text('Campo Requerido');
                $("#error_etiqueta2").text('Campo Requerido');
            } else {
                $("#error_equipo2").text('');
                $("#error_etiqueta2").text('');
                $("#equipo2").removeClass('has-error');
                $("#etiqueta2").removeClass('has-error');
            }

            if ($("#id_request_ant").val().length > 0 && $("#id_equipo2").val().length > 0) {
                const etiqueta1 = document.getElementById('etiqueta1').value;
                const etiqueta2 = document.getElementById('etiqueta2').value;

                if (etiqueta1.substring(0, 6) != etiqueta2.substring(0, 6)) {
                    errores++;
                    $("#etiqueta1").addClass('has-error');
                    $("#etiqueta2").addClass('has-error');
                    $("#error_etiqueta1").text('Requiere mismo tipo');
                    $("#error_etiqueta2").text('Requiere mismo tipo');
                } else {
                    $("#error_etiqueta1").text('');
                    $("#error_etiqueta2").text('');
                    $("#etiqueta1").removeClass('has-error');
                    $("#etiqueta2").removeClass('has-error');
                }
            }
        }
        if (tipo == 3) {
            if ($("#id_user").val().length == 0) {
                errores++;
                $("#id_user").addClass('has-error');
                $("#error_id_user").text('Campo Requerido');
            } else {
                $("#id_user").removeClass('has-error');
                $("#error_id_user").text('');

                if (i == 0) {
                    Swal.fire({
                        icon: "info",
                        title: "Equipos Inexistentes",
                        text: "No se encontraron equipos del usuario indicado.",
                    });
                } else {
                    var chbk_active = 0;
                    for (f = 1; f < i; f++) {
                        if ($('#chbx_' + f).is(':checked')) { chbk_active++; }
                    }
                    if (chbk_active == 0) {
                        errores++;
                        Swal.fire({
                            icon: "info",
                            title: "Información Incompleta.",
                            text: "Selecciona al menos un equipo que será recolectado.",
                        });
                    }
                }
            }
        }

    }
    if (errores != 0) {
        return;
    }
    btn.disabled = true;
    const timerInterval = Swal.fire({ //se le asigna un nombre al swal
        allowOutsideClick: false,
        title: '¡Guardando Registro!',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });
    const data_asig = new FormData($('#procesos')[0]);
    // data_asig.append('items_equip', arrayItemsEquip);
    // data_asig.append('items_inv', arrayItemsInv);
    $.ajax({
        data: data_asig,
        url: `${urls}sistemas/procesos_equipo`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (save) {
            Swal.close(timerInterval);
            btn.disabled = false;
            if (save.hasOwnProperty('xdebug_message')) {
                Swal.fire({
                    icon: "error",
                    title: "Oops, Exception...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
                console.log('Mensaje de xdebug:', response.xdebug_message);
            }
            if (save != false || save == 'recolectados') {
                setTimeout(function () {
                    tbl_equipos_asignados.ajax.reload(null, false);
                }, 100);
                i = 0
                contInv = 0;
                contEquip = 0;
                arrayItemsInv = [];
                arrayItemsEquip = [];
                document.getElementById('procesos').reset();
                $("#div_obs").hide();
                $("#div_formulario").empty();
                $(".btn-opcion").removeClass('active');
                if (tipo != 3) {
                    Swal.fire({
                        icon: "success",
                        title: "!Registro de Datos Exitoso!",
                        text: '¿Quieres Descargar la Responsiba?',
                        showCancelButton: true,
                        confirmButtonText: 'Ver ResponsiVa',
                        cancelButtonText: 'OK',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirige a la página de equipos cuando se hace clic en el botón "Ir a la página de equipos"
                            window.open(`${urls}sistemas/pdf-responsiva-asignacion/${$.md5(key + save)}`, '_blank');
                        }
                    });
                } else {
                    Swal.fire("¡Registro Exitosamente!", "", "success");
                }
                $("#campo").empty();
                $("#dato_Asig_equipo").empty();
                $("#dato_Asig_usuario").empty();
                $("#equipo_asig").val("");
                $("#coment_asig").val("");
                $("#tipo_asig").val("");
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador del Sistema",
                });
            }
        }
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
    })
});

function proceso(params) {
    $("#coment_asig").val('');
    $("#tipo_proceso").val(params);
    $("#div_items_asignacion").empty()
    $("#div_formulario").empty();
    $("#div_obs").hide();
    if (params == 4) {
        $("#div_formulario").append(`<h4><i class="fas fa-file-upload" style="margin-right: 10px;"></i>Datos Renovacion</h4>
            <input type="hidden" id="id_equipo" name="id_equipo">
            <input type="hidden" name="id_request_ant" id="id_request_ant">              
            <div class="row">
                <div class="form-group col-md-2">
                    <label for="etiqueta1">Etiqueta</label>
                    <input type="text" id="etiqueta1" class="form-control" onchange="datosEquipo(1,1)" list="almacenados">
                    <div id="error_etiqueta1" class="text-danger"></div>
                </div>
                <div class="form-group col-md-2">
                    <label for="equipo1">IMEI / No. Serie</label>
                    <input type="text" id="equipo1" class="form-control" onchange="datosEquipo(2,1)">
                    <div id="error_equipo1" class="text-danger"></div>
                </div>
                <div class="col-md-2">
                    <label>Responsable</label>
                    <input type="text" class="form-control" id="ant_responsable" disabled>
                </div>
                <div class="col-md-2">
                    <label>IP</label>
                    <input type="text" class="form-control" id="ant_ip" disabled>
                </div>
                <div class="col-md-2">
                    <label>Sesión</label>
                    <input type="text" class="form-control" id="ant_secion" disabled>
                </div>
                <div class="col-md-2">
                    <label>Contraseña</label>
                    <input type="text" class="form-control" id="ant_pw" disabled>
                </div>              
            </div>
        </div>`);
    } else if (params == 3) {
        $("#div_formulario").append(`<hr><h4><i class="fas fa-file-upload" style="margin-right: 10px;"></i>Datos Recolección</h4>
            <div class="row">
                <div class="form-group col-md-3">
                    <label for="id_user">Usuario</label>
                    <div id="div_id_user" class="div-error-select2">
                        <select id="id_user" name="id_user" onchange="getDataUserRecolet(this)" class="form-control rounded-0 select2bs4 select2-hidden-accessible" style="width: 100%; height: calc(2.25rem + 2px);">
                            <option value="">Seleccionar...</option>
                        </select>
                    </div>
                    <div id="error_id_user" class="text-danger"></div>
                </div>
                <div class="form-group col-md-9" style="text-align:end;">
                </div>
            </div>
            <div id="div_items_retirar"> </div>
        </div>`);
        $.ajax({
            url: `${urls}sistemas/equipos_lbl_almacenados_y_usuarios`,
            type: "post",
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (respEquip) {
                if (respEquip) {
                    respEquip.usuarios.forEach(users => {
                        $("#id_user").append(`<option value="${users.id_user}">${users.user_name}</option>`);
                    });
                }
            }
        });
        $("#id_user").select2();
        $("#div_obs").show();
    } else if (params == 2) {
        $("#div_formulario").append(`<h4><i class="fas fa-file-upload" style="margin-right: 10px;"></i>Datos Renovacion</h4>
            <input type="hidden" id="id_equipo" name="id_equipo">
            <div class="row">
            <div class="form-group col-md-12" style="text-align:center;" id="div_nombre_user">
            
            </div>
            </div>
            <div class="row">
                <div class="form-group col-md-2">
                    <label for="etiqueta1">Etiqueta Recolectar</label>
                    <input type="text" id="etiqueta1" class="form-control" onchange="datosEquipo(1,1)" list="almacenados">
                    <datalist id="almacenados"></datalist>
                    <div id="error_etiqueta1" class="text-danger"></div>
                </div>
                <div class="form-group col-md-3">
                    <label for="equipo1">IMEI / No. Serie Recolectar</label>
                    <input type="text" id="equipo1" class="form-control" onchange="datosEquipo(2,1)">
                    <div id="error_equipo1" class="text-danger"></div>
                </div>
                <div class="form-group col-md-1">
                    <input type="hidden" id="id_equipo1" name="id_equipo_ant">
                    <input type="hidden" id="id_equipo2" name="id_equipo_new">
                </div>
                <div class="form-group col-md-2">
                    <label for="etiqueta">Etiqueta Nueva</label>
                    <input type="text" id="etiqueta2" class="form-control" onchange="datosEquipo(1,2)" list="almacenados">
                    <datalist id="almacenados"></datalist>
                    <div id="error_etiqueta2" class="text-danger"></div>
                </div>
                <div class="form-group col-md-2">
                    <label for="equipo2">IMEI / No. Serie Nueva</label>
                    <input type="text" id="equipo2" class="form-control" onchange="datosEquipo(2,2)">
                    <div id="error_equipo2" class="text-danger"></div>
                </div>
                <div class="form-group col-md-2">
                    <label for="condicion">Condicion</label>
                    <select name="condicion" id="condicion" class="form-control" onchange="claenInput(this)">
                        <option value="NUEVO">NUEVO</option>
                        <option value="USADO">USADO</option>
                    </select>
                    <div class="text-danger" id="error_condicion"></div>
                </div>
            </div>
            <div class="row">  
            <input type="hidden" name="id_request_ant" id="id_request_ant">              
                <div class="col-md-1">
                    <label>IP</label>
                    <input type="text" class="form-control" id="ant_ip" disabled>
                </div>
                <div class="col-md-2">
                    <label>Sesión</label>
                    <input type="text" class="form-control" id="ant_secion" disabled>
                </div>
                <div class="col-md-2">
                    <label>Contraseña</label>
                    <input type="text" class="form-control" id="ant_pw" disabled>
                </div>  
                <div class="col-md-1" style="text-align: center;">
                    <i class="fas fa-sign-out-alt" style="font-size: 3rem; margin-top: -1rem;"></i>
                </div>          
                <div class="col-md-2" id="div_campo_ip">
                    <label>Definir IP</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">192.1.</span>
                        </div>
                        <input type="number" id="ip_segmento" name="ip_segmento" step="1" min="1" max="20" class="form-control">
                        <span class="input-group-text">.</span>
                        <input type="number" id="ip_id" name="ip_id" step="1" min="1" max="255" class="form-control">
                    </div>
                </div>
                <div class="col-md-2" id="div_campo_sesion_1">
                    <label>Sesión</label>
                    <input type="text" name="sesion_nombre" id="sesion_nombre" class=" form-control">
                </div>
                <div class="col-md-2" id="div_campo_sesion_2">
                    <label>Contraseña</label>
                    <input type="text" name="sesion_pw" id="sesion_pw" class=" form-control">
                </div>
            </div>
        </div>`);
        $("#div_obs").show();
    } else if (params == 1) {
        $("#div_formulario").append(`<hr>
            <h4><i class="fas fa-file-upload" style="margin-right: 10px;"></i>Datos Asignacion</h4>
            <input type="hidden" id="id_equipo" name="id_equipo">
            <div class="row">
                <div class="form-group col-md-3">
                    <label for="etiqueta">Etiqueta</label>
                    <input type="text" id="etiqueta" class="form-control" onchange="datosEquipo(1)" list="almacenados">
                    <datalist id="almacenados"></datalist>
                    <div id="error_etiqueta" class="text-danger"></div>
                </div>
                <div class="form-group col-md-3">
                    <label for="equipo">IMEI / No. Serie</label>
                    <input type="text" id="equipo" class="form-control" onchange="datosEquipo(2)">
                    <div id="error_equipo" class="text-danger"></div>
                </div>
                <div class="form-group col-md-3">
                    <label for="id_user">Usuario</label>
                    <div id="div_id_user" class="div-error-select2">
                        <select id="id_user" name="id_user" onchange="getDataUser()" class="form-control rounded-0 select2bs4 select2-hidden-accessible" style="width: 100%; height: calc(2.25rem + 2px);">
                            <option value="">Seleccionar...</option>
                        </select>
                    </div>
                    <div id="error_id_user" class="text-danger"></div>
                </div>
                <div class="form-group col-md-3">
                    <label for="condicion">Condicion</label>
                    <select name="condicion" id="condicion" class="form-control" onchange="claenInput(this)">
                        <option value="NUEVO">NUEVO</option>
                        <option value="USADO">USADO</option>
                    </select>
                    <div class="text-danger" id="error_condicion"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-1" style="text-align: center;padding-top: 15px;">
                    <label id="lbl_ipChekbox" class="btn btn-outline-secondary btn-opcion">
                        <input type="checkbox" class="custom-control-input" id="ipCheckbox" onclick="ipCheck(this)">
                        <label for="ipCheckbox" id="ipLabel" style="margin-bottom:0px !important;">SIN IP</label>
                    </label>
                </div>
                <div class="col-md-3" id="div_campo_ip" style="display: none;">
                    <label>Definir IP</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">192.1.</span>
                        </div>
                        <input type="number" id="ip_segmento" name="ip_segmento" step="1" min="1" max="20" class="form-control">
                        <span class="input-group-text">.</span>
                        <input type="number" id="ip_id" name="ip_id" step="1" min="1" max="255" class="form-control">
                    </div>
                </div>
                <div class="col-md-2" style="text-align: center;padding-top: 15px;">
                    <label id="lbl_sesionChekbox" class="btn btn-outline-secondary btn-opcion">
                        <input type="checkbox" class="custom-control-input" id="sesionCheckbox" onclick="sessionCheck(this)">
                        <label for="sesionCheckbox" id="sesionLabel" style="margin-bottom:0px !important;">SIN SESIÓN</label>
                    </label>
                </div>
                <div class="col-md-3" id="div_campo_sesion_1" style="display: none;">
                    <label>Sesión</label>
                    <input type="text" name="sesion_nombre" id="sesion_nombre" class=" form-control">
                </div>
                <div class="col-md-3" id="div_campo_sesion_2" style="display: none;">
                    <label>Contraseña</label>
                    <input type="text" name="sesion_pw" id="sesion_pw" class=" form-control">
                </div>
            </div>
            <hr>
            <div class="row">
                <div id="dato_equipo" class="col-md-5"></div>
                <div class="col-md-2" style="text-align: center;">
                    <i class="fas fa-sign-out-alt" style="font-size: 3rem;"></i>
                </div>
                <div id="dato_Asig_usuario" class="col-md-5"></div>
        </div>`);
        $("#div_items_asignacion").append(`<hr>
        <h4><i class="fas fa-file-import" style="margin-right: 10px;"></i>Accesorios</h4>`)
        const data = new FormData();
        data.append('option', 1);
        $.ajax({
            data: data,
            url: `${urls}sistemas/equipos_lbl_almacenados_y_usuarios`,
            type: "post",
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (respEquip) {
                if (respEquip) {
                    respEquip.lbl_equips.forEach(element => {
                        $("#almacenados").append(`<option value="${element.label_equip}">`);
                    });
                    respEquip.usuarios.forEach(users => {
                        $("#id_user").append(`<option value="${users.id_user}">${users.user_name}</option>`);
                    });
                }
            }
        });
        $("#id_user").select2();
        $("#div_obs").show();
    }
}

function datosEquipo(option, item = '') {
    const tipo_proceso = document.getElementById('tipo_proceso').value;
    $("#error_etiqueta" + item).text('');
    $("#etiqueta" + item).removeClass('has-error');
    $("#error_equipo" + item).text('');
    $("#equipo" + item).removeClass('has-error');
    $("#dato_equipo" + item).empty();
    $("#id_equipo" + item).val('');
    if (item == 1) {
        $("#div_nombre_user").empty();
        $("#id_request_ant").val('');
        $("#ant_ip").val('');
        $("#ant_secion").val('');
        $("#ant_pw").val('');
        $("#id_user").val('');
        if (tipo_proceso == 4) {
            $("#id_equipo").val('');
            $("#ant_responsable").val('');
        }
    }
    const no_serial_equip = document.getElementById('equipo' + item);
    const lbl_equip = document.getElementById('etiqueta' + item);

    const campo_opcion = (option == 1) ? 'etiqueta' + item : 'equipo' + item;
    const campo_otro = (option == 1) ? 'equipo' + item : 'etiqueta' + item;
    const data_campo = (option == 1) ? 'label_equip' : 'id_equip';


    if ($("#" + campo_opcion).val().length == 0) {
        $("#" + campo_otro).removeClass('has-error');
        $("#error_" + campo_otro).text("");
        $("#" + campo_otro).val('');
        return false;
    }

    var data = new FormData();
    data.append(data_campo, $("#" + campo_opcion).val());
    // data.append('proccess', params);
    $.ajax({
        data: data,
        url: `${urls}sistemas/datos_equipo_asignacion_recoleccion`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (resp) {
            if (resp.Equip) {
                if (item != 1) {
                    if (resp.Equip.status_equip > 1) {
                        var error = (resp.Equip.status_equip == 2) ? 'Equipo ya esta Asignado' : 'Equipo incorrecto';
                        $("#error_" + campo_opcion).text(error);
                        $("#" + campo_opcion).addClass('has-error');
                        $("#" + campo_otro).removeClass('has-error');
                        $("#error_" + campo_otro).text("");
                        $("#" + campo_otro).val('');
                    } else {
                        if (option == 1 && no_serial_equip.value != resp.Equip.no_serial) {
                            no_serial_equip.value = resp.Equip.no_serial;
                        } else if (option == 2 && lbl_equip.value != resp.Equip.label_equip) {
                            lbl_equip.value = resp.Equip.label_equip
                        }
                        $("#id_equipo" + item).val(resp.Equip.id_equip);
                        $("#dato_equipo").append(`
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>Marca</label>
                                    <p>${resp.Equip.marca}</p>
                                    </div>
                                <div class="form-group col-md-3">
                                    <label for="modelo">Modelo</label>
                                    <p>${resp.Equip.modelo}</p>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="caracteristicas">Caracteristicas</label>
                                    <p>${resp.Equip.features}</p>
                                </div>
                        </div>`);
                    }
                } else {
                    if (resp.Equip.status_equip == 1) {
                        var error = (resp.Equip.status_equip == 1) ? 'Equipo esta Almacenado' : 'Equipo incorrecto';
                        $("#error_" + campo_opcion).text(error);
                        $("#" + campo_opcion).addClass('has-error');
                        $("#" + campo_otro).removeClass('has-error');
                        $("#error_" + campo_otro).text("");
                        $("#" + campo_otro).val('');
                    } else {
                        if (option == 1 && no_serial_equip.value != resp.Equip.no_serial) {
                            no_serial_equip.value = resp.Equip.no_serial;
                        } else if (option == 2 && lbl_equip.value != resp.Equip.label_equip) {
                            lbl_equip.value = resp.Equip.label_equip
                        }
                    }
                    if (tipo_proceso == 2) {
                        $("#div_nombre_user").append(`<h4>${resp.Request.user_name}</h4> <input type="hidden" name="id_user" value="${resp.Request.id_user}">`);
                    } else if (tipo_proceso == 4) {
                        $("#id_equipo").val(resp.Request.id_request);
                        $("#ant_responsable").val(resp.Request.user_name);
                    }
                    
                    $("#id_request_ant").val(resp.Request.id_request);
                    $("#ant_ip").val(resp.Request.ip);
                    $("#ant_secion").val(resp.Request.pc_user);
                    $("#ant_pw").val(resp.Request.pc_pw);

                }
            } else {
                $("#" + campo_opcion).addClass('has-error');
                $("#error_" + campo_opcion).text("Dato no encontrado");
                $("#" + campo_otro).removeClass('has-error');
                $("#error_" + campo_otro).text("");
                $("#" + campo_otro).val('');
            }
        }
    });
}

function getDataUserRecolet(thiss) {
    const campo = thiss;
    $("#div_items_retirar").empty();
    claenInput(campo);
    var data = new FormData();
    data.append('id_user', campo.value);
    // data.append('proccess', params);
    $.ajax({
        data: data,
        url: `${urls}sistemas/lista_equipo_por_usuarios_recoleccion`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (resp) {
            if (resp.equipos.length > 0 || resp.suministros.length > 0 /* || resp.prestamos */) {
                i = 1;
                // onchange="restarDeuda(1)"
                resp.equipos.forEach(item => {
                    bg_color = (i % 2 == 0) ? 'background-color: #E6E6E6;' : '';
                    $("#div_items_retirar").append(`<div class="row" style="padding-bottom:10px;padding-top:10px;${bg_color}">
                        <div class="col-md-1">
                            <input type="checkbox" class="form-control" name="equipo_[]" id="chbx_${i}" value="${item.id_request},${item.id_equip}" style="margin-top: 18px;">
                        </div>
                        <div class="col-md-2">
                            <label>Etiqueta</label>
                            <input type="text" readonly class="form-control" value="${item.label_equip}">
                        </div>
                        <div class="col-md-2">
                            <label>Datos de Equipo</label>
                            <input type="text" readonly class="form-control" value="${item.datos}">
                        </div>
                        <div class="col-md-2">
                            <label>serie / IMEI</label>
                            <input type="text" readonly class="form-control" value="${item.serial_number}">
                        </div>
                        <div class="col-md-2">
                            <label>Costo</label>
                            <input type="text" readonly class="form-control" value="$ ${item.approximate_cost}">
                        </div>
                        <div class="col-md-2">
                            <label>Fecha Entrega</label>
                            <input type="text" readonly class="form-control" value="${item.entrega}">
                        </div>
                    </div>`);
                    i++;
                });

                resp.suministros.forEach(item => {
                    bg_color = (i % 2 == 0) ? 'background-color: #E6E6E6;' : '';
                    $("#div_items_retirar").append(`<div class="row" style="padding-bottom:10px;padding-top:10px;${bg_color}">
                        <div class="col-md-1">
                            <input type="checkbox" class="form-control" name="suministro_[]" id="chbx_${i}" value="${item.id_request},${item.id_product},${item.amount}" style="margin-top: 18px;">
                        </div>
                        <div class="col-md-1">
                            <label>Cantidad</label>
                            <input type="text" readonly class="form-control" value="${item.amount}">
                        </div>
                        <div class="col-md-3">
                            <label>Producto</label>
                            <input type="text" readonly class="form-control" value="${item.product}">
                        </div>
                        <div class="col-md-2">
                            <label>Costo Unitario</label>
                            <input type="text" readonly class="form-control" value="${item.cost_unit}">
                        </div>
                        <div class="col-md-2">
                            <label>Costo Total</label>
                            <input type="text" readonly class="form-control" value="${item.cost_total}">
                        </div>
                        <div class="col-md-2">
                            <label>Fecha Entrega</label>
                            <input type="text" readonly class="form-control" value="${item.fecha}">
                        </div>
                    </div>`);
                    i++;
                });
            } else {
                Swal.fire({
                    icon: "info",
                    title: "Equipos Inexistentes",
                    text: "No se encontraron equipos del usuario indicado.",
                });
            }
        }
    });
}

function datosEquipoRetirar(option) {
    $("#error_etiqueta").text('');
    $("#etiqueta").removeClass('has-error');
    $("#error_equipo").text('');
    $("#equipo").removeClass('has-error');
    $("#dato_equipo").empty();
    $("#id_request").val('');
    $("#user_name").val('');

    const no_serial_equip = document.getElementById('equipo');
    const lbl_equip = document.getElementById('etiqueta');

    const campo_opcion = (option == 1) ? 'etiqueta' : 'equipo';
    const campo_otro = (option == 1) ? 'equipo' : 'etiqueta';
    const data_campo = (option == 1) ? 'label_equip' : 'id_equip';


    if ($("#" + campo_opcion).val().length == 0) {
        $("#" + campo_otro).removeClass('has-error');
        $("#error_" + campo_otro).text("");
        $("#" + campo_otro).val('');
        return false;
    }

    var data = new FormData();
    data.append(data_campo, $("#" + campo_opcion).val());
    // data.append('proccess', params);
    $.ajax({
        data: data,
        url: `${urls}sistemas/datos_equipo_asignacion_recoleccion`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (resp) {
            if (resp.Equip) {
                if (resp.Equip.status_equip != 2) {
                    var error = (resp.Equip.status_equip == 2) ? 'Equipo no Asignado' : 'Equipo incorrecto';
                    $("#error_" + campo_opcion).text(error);
                    $("#" + campo_opcion).addClass('has-error');
                    $("#" + campo_otro).removeClass('has-error');
                    $("#error_" + campo_otro).text("");
                    $("#" + campo_otro).val('');
                } else {
                    if (option == 1 && no_serial_equip.value != resp.Equip.no_serial) {
                        no_serial_equip.value = resp.Equip.no_serial;
                    } else if (option == 2 && lbl_equip.value != resp.Equip.label_equip) {
                        lbl_equip.value = resp.Equip.label_equip
                    }
                    $("#dato_equipo").append(`
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label>Marca</label>
                                <p>${resp.Equip.marca}</p>
                                </div>
                            <div class="form-group col-md-3">
                                <label for="modelo">Modelo</label>
                                <p>${resp.Equip.modelo}</p>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="caracteristicas">Caracteristicas</label>
                                <p>${resp.Equip.features}</p>
                            </div>
                        </div>`);
                    $("#id_request").val(resp.Request.id_request);
                    $("#user_name").val(resp.Request.user_name);
                    // getDataUser(resp.Request.id_user);
                }

            } else {
                $("#" + campo_opcion).addClass('has-error');
                $("#error_" + campo_opcion).text("Dato no encontrado");
                $("#" + campo_otro).removeClass('has-error');
                $("#error_" + campo_otro).text("");
                $("#" + campo_otro).val('');
            }
        }
    });
}

// $("#id_user").on("change", function (e) {
function getDataUser(id_user = null) {
    $("#dato_Asig_usuario").empty();
    if (id_user == null) {
        const campo = document.getElementById('id_user');
        document.getElementById("div_" + campo.id).classList.remove("has-error");
        document.getElementById("error_" + campo.id).textContent = '';
        if (campo.value.length == 0) {
            return false;
        }
        var valor = campo.value;
    } else {
        var valor = id_user;
    }
    var data = new FormData();
    data.append('ID_U', valor);
    $.ajax({
        data: data,
        url: `${urls}sistemas/datos_usuario`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (respUser) {
            if (respUser) {
                // $("#id_user").val(respUser.id_user);
                $("#dato_Asig_usuario").append(`<div class="form-row">
                      <div class="form-group col-md-4">
                       <label>Nomina</label>
                       <p>${respUser.nomina}</p>
                       </div>
                    <div class="form-group col-md-4">
                       <label for="no_serie">Departamento</label>
                       <p>${respUser.departamento}</p>
                    </div>
                    <div class="form-group col-md-4">
                       <label for="tipo">Puesto</label>
                       <p>${respUser.puesto}</p>
                    </div>
                </div>`);
            } else {
                if (id_user == null) {
                    $("#error_id_user").text("Usuario no Encontrada");
                    $("#div_id_user").addClass('has-error-bg');
                }
            }
        }
    })
};

function validarRetirar() {
    if ($("#opcion_reco").val().length > 0) {
        error_opcion_reco = "";
        $("#error_opcion_reco").text(error_opcion_reco);
        $("#opcion_reco").removeClass('has-error');
    }
    if ($("#obs_reco").val().length > 0) {
        error_obs_reco = "";
        $("#error_obs_reco").text(error_obs_reco);
        $("#obs_reco").removeClass('has-error');
    }
}

function ipCheck(campo) {
    const isChecked = $(`#${campo.id}`).is(':checked');
    $("#ip_segmento").val('');
    $("#ip_id").val('');
    if (isChecked) {
        $('#ipLabel').text('CON IP');
        $("#lbl_ipChekbox").addClass('active');
        $("#div_campo_ip").show();
    } else {
        $('#ipLabel').text('SIN IP');
        $("#lbl_ipChekbox").removeClass('active');
        $("#div_campo_ip").hide();
    }
};

function sessionCheck(campo) {
    const isChecked = $(`#${campo.id}`).is(':checked');
    $("#sesion_nombre").val('');
    $("#sesion_pw").val('');
    if (isChecked) {
        $('#sesionLabel').text('CON SESIÓN');
        $("#lbl_sesionChekbox").addClass('active');
        $("#div_campo_sesion_1").show();
        $("#div_campo_sesion_2").show();
    } else {
        $('#sesionLabel').text('SIN SESIÓN');
        $("#lbl_sesionChekbox").removeClass('active');
        $("#div_campo_sesion_1").hide();
        $("#div_campo_sesion_2").hide();
    }
};

function addItems(tipo) {
    if (tipo == 1) { //Inventario
        contInv++;
        arrayItemsInv.forEach(item => {
            if (item === contInv) {
                contInv++;
            }
        });
        $("#div_items_asignacion").append(`<div class="row" style="margin-bottom: 10px;" id="div_inv_clon_${contInv}">
            <div class="col-md-9">
                <input type="hidden" name="tipo_item" value="1">
                <label for="inventario_${contInv}">Inventario:</label>
                <div id="div_inventario_${contInv}" class="div-error-select2">
                <select name="inventario_[]" id="inventario_${contInv}" class="form-control select2-hidden-accessible" style="width: 100%; height: calc(2.25rem + 2px);" onchange="limpiarClon(this)">
                </select>
                </div>
            </div>
            <div class="col-md-3" style="padding-top: 2rem;">
                <button type="button" class="btn btn-danger" onclick="retirarItem(${contInv},1)"> <i class="fas fa-times"></i></button>
            </div>
        </div>`);
        $.ajax({
            url: `${urls}sistemas/datos_productos`,
            type: "post",
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (listResponse) {
                if (listResponse) {
                    $(`#inventario_${contInv}`).append(`<option value="">Inventario...</optino>`);
                    listResponse.forEach(element => {
                        $(`#inventario_${contInv}`).append(`<option value="${element.id_product}">${element.product}</option>`);
                    });
                } else {
                    $(`#inventario_${contInv}`).append(`<option value="">Sin Equipos Disponible</optino>`);
                }
            }
        });
        $("#inventario_" + contInv).select2();
        arrayItemsInv.push(contInv);
    } else {
        contEquip++;
        arrayItemsEquip.forEach(item => {
            if (item === contEquip) {
                contEquip++;
            }
        });
        $("#div_items_asignacion").append(`<div class="row" style="margin-bottom: 10px;" id="div_equip_clon_${contEquip}">
            <div class="col-md-9">
                <input type="hidden" name="tipo_item" value="2">
                <label for="otros_equipos_${contEquip}">Otro Equipo:</label>
                <div id="div_otros_equipos_${contEquip}" class="div-error-select2">
                    <select name="otros_equipos_[]" id="otros_equipos_${contEquip}" class="form-control select2-hidden-accessible" style="width: 100%; height: calc(2.25rem + 2px);" onchange="limpiarClon(this)">
                    </select>
                </div>
            </div>
            <div class="col-md-3" style="padding-top: 2rem;">
                <button type="button" class="btn btn-danger" onclick="retirarItem(${contEquip},2)"> <i class="fas fa-times"></i></button>
            </div>
        </div>`);
        $.ajax({
            url: `${urls}sistemas/lbl_equipos_accesorios`,
            type: "post",
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (listResponse) {
                if (listResponse) {
                    $(`#otros_equipos_${contEquip}`).append(`<option value="">Equipos...</option>`);
                    listResponse.forEach(element => {
                        $(`#otros_equipos_${contEquip}`).append(`<option value="${element.id_equip}">${element.equipo}</option>`);
                    });
                } else {
                    $(`#otros_equipos_${contEquip}`).append(`<option value="">Sin Equipos Disponible</optino>`);
                }
            }
        });
        $("#otros_equipos_" + contEquip).select2();
        arrayItemsEquip.push(contEquip);

    }

}

function retirarItem(item, type) {
    if (type == 1) {
        const i = arrayItemsInv.indexOf(item);
        arrayItemsInv.splice(i, 1);
        // sessionStorage.setItem('arrayProduct', JSON.stringify(arrayProduct));
        $("#div_inv_clon_" + item).remove();
        contInv = 0;
    } else {
        const i = arrayItemsEquip.indexOf(item);
        arrayItemsEquip.splice(i, 1);
        // sessionStorage.setItem('arrayProduct', JSON.stringify(arrayProduct));
        $("#div_equip_clon_" + item).remove();
        contEquip = 0;
    }

}
