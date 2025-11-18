/**
 * ARCHIVO MODULO SYSTEMA / EQUIPOS
 * AUTOR:HORUS SAMAEL RIVAS PEDRAZA
 * horus.riv.ped@gmail.com
 * CEL:56 2439 2632
 */
$(document).ready(function () {
    tbl_equipos = $("#tbl_equipos").dataTable({
        processing: true,
        ajax: {
            method: "post",
            url: `${urls}sistemas/lista_equipos`,
            dataSrc: "",
        },
        lengthChange: true,
        ordering: true,
        responsive: true,
        autoWidth: false,
        rowId: "staffId",
        dom: 'Bfrtip',  // 'B' es para los botones

        language: {
            url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
        },
        buttons: [
            {
                extend: 'excelHtml5',  // Aquí se habilita el botón de Excel
                text: 'Excel',
                className: 'btn btn-success' // Personaliza el estilo si lo necesitas
            }
        ],
        columns: [
            {
                data: "id_equip",
                title: "ID",
                className: "text-center",
            },
            {
                data: "label_equip",
                title: "ETIQUETA",
                className: "text-center",
            },
            {
                data: "no_serial",
                title: "IMEI / No. SERIE",
                className: "text-center",
            },
            {
                data: "type_equip",
                title: "TIPO",
                className: "text-center",
            },
            {
                data: "marca",
                title: "MARCA",
                className: "text-center",
            },
            {
                data: "modelo",
                title: "MODELO",
                className: "text-center",
            },
            {
                data: null,
                render: function (data, type, full, meta) {
                    return `<span class="badge badge-${data["color"]}">${data["txt"]}</span>`;
                },
                title: "ESTADO",
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
                targets: 7,
                render: function (data, type, full, meta) {
                    fecha = "'" + data["created_at"] + "'";
                    return ` <div class="mr-auto">
                        <button type="button" class="btn btn-primary btn-sm" title="Editar equipo" onclick="Edit(${data["id_equip"]})">
                            <i class="far fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-info btn-sm" title="Historial del equipo" onclick="History(${data["id_equip"]})">
                            <i class="fas fa-history"></i>
                        </button>
                    </div> `;
                },
            },
            {
                targets: [0],
                visible: false,
                searchable: false,
            },
        ],
        order: [[0, "DESC"]],
        createdRow: (row, data) => {
            $(row).attr("id", "equip_" + data.id_equip);
        },
    }).DataTable();
    $("#tbl_equipos thead").addClass("thead-dark text-center");
});

function tipoEquipo() {
    if ($("#tipo").val().length > 0) {
        $("#error_tipo").text('');
        $("#tipo").removeClass('has-error');
    }
    $("#campos").empty();
    if ($("#tipo").val() == 2 || $("#tipo").val() == 3) {
        $("#campos").append(`<div class="form-group col-md-3">
                <label for="marca">Marca</label>
                <input type="text" class="form-control" id="marca" name="marca" onchange="validar(this)" list="lista_marcas">
                <datalist id="lista_marcas"></datalist>
                <div id="error_marca" class="text-danger"></div>
            </div>
            <div class="form-group col-md-3">
                <label for="imei">No. Serie</label>
                <input type="text" class="form-control" id="no_serie" name="no_serie" oninput="this.value = this.value.toUpperCase()" onchange="validar(this)">
                <div id="error_no_serie" class="text-danger"></div>
            </div>
            <div class="form-group col-md-3">
                <label for="modelo">Modelo</label>
                <input type="text" class="form-control" id="modelo" name="modelo" onchange="validar(this)" list="lista_modelos">
                <datalist id="lista_modelos"></datalist>
                <div id="error_modelo" class="text-danger"></div>
            </div>
            <div class="form-group col-md-3">
                <label for="procesador">Procesador</label>
                <input type="text" name="procesador" id="procesador" class="form-control" onchange="validar(this)" list="lista_procesadores">
                <datalist id="lista_procesadores"></datalist>
                <div class="text-danger" id="error_procesador"></div>
            </div>
            <div class="form-group col-md-3">
                <label for="memoria">Memoria</label>
                <select name="memoria" id="memoria" class="form-control" onchange="validar(this)">
                    <option value="">Seleccionar...</option>
                    <option value="2GB">2GB</option>
                    <option value="4GB">4GB</option>
                    <option value="8GB">8GB</option>
                    <option value="12GB">12GB</option>
                    <option value="16GB">16GB</option>
                    <option value="32GB">32GB</option>
                </select>
                <div class="text-danger" id="error_memoria"></div>
            </div>
            <div class="form-group col-md-4">
                <label for="disco_duro">Disco duro</label>
                <input type="hidden" name="disco_duro" id="disco_duro">
                <div class="input-group">
                    <input type="number" min="1" id="disco_duro_txt" class="form-control" onchange="hardDisc(1)">
                    <div class="input-group-prepend"> 
                    <select id="disco_duro_extent" class="form-control" onchange="hardDisc(1)">
                    <option value="GB">GB</option>
                    <option value="TB">TB</option>
                    </select>
                    </div>
                    <div class="input-group-prepend"> 
                    <select id="disco_duro_type" class="form-control" onchange="hardDisc(1)">
                    <option value="SSD">SSD</option>
                    <option value="HDD">HDD</option>
                    </select>
                    </div>
                </div>
                <div class="text-danger" id="error_disco_duro"></div>
            </div>
            <div class="form-group col-md-2">
                <label for="costo_equipo">Costo aproximado</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                    </div>
                    <input type="number" step="0.01" min="1.00" name="costo_equipo" id="costo_equipo" class="form-control" placeholder="500.00">
                </div>
                <div class="text-danger" id="error_costo_equipo"></div>
            </div>
            <div class="form-group col-md-2">
                <label for="dmf">Fecha Manofactura(año)</label>
                <input type="text" class="form-control" id="dmf" name="dmf" onchange="validar(this)" placeholder="2017">
                <div id="error_dmf" class="text-danger"></div>
            </div>
            <div class="form-group col-md-8">
                <label for="caracteristicas">Observación Extra</label>
                <textarea type="text" class="form-control" id="caracteristicas" name="caracteristicas" onchange="validar(this)"></textarea>
                <div id="error_caracteristicas" class="text-danger"></div>
        </div>`);
        const tipo_equipo = new FormData();
        tipo_equipo.append('type_equip', $("#tipo").val());
        $.ajax({
            data: tipo_equipo,
            url: `${urls}sistemas/equipos_lista_pz_existentes`,
            type: "post",
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (respEquip) {
                if (respEquip) {
                    respEquip.processors.forEach(element => {
                        $("#lista_procesadores").append(`<option value="${element.processor_data}">`);
                    });
                    respEquip.marcas.forEach(element => {
                        $("#lista_marcas").append(`<option value="${element.marca}">`);
                    });
                    respEquip.model.forEach(element => {
                        $("#lista_modelos").append(`<option value="${element.model}">`);
                    });
                }
            }
        });
    } else {
        $("#campos").append(`
            <div class="form-group col-md-2">
                <input type="hidden" name="procesador">
                <input type="hidden" name="memoria">
                <input type="hidden" name="disco_duro">
                <label for="marca">Marca</label>
                <input type="text" class="form-control" id="marca" name="marca" onchange="validar(this)">
                <div id="error_marca" class="text-danger"></div>
              </div>
              <div class="form-group col-md-3">
                <label for="no_serie">No. Serie / IEMI</label>
                <input type="text" class="form-control" id="no_serie" name="no_serie" onchange="validar(this)">
                <div id="error_no_serie" class="text-danger"></div>
              </div>
              <div class="form-group col-md-3">
                <label for="modelo">Modelo</label>
                <input type="text" class="form-control" id="modelo" name="modelo" onchange="validar(this)">
                <div id="error_modelo" class="text-danger"></div>
              </div>
              <div class="form-group col-md-2">
                <label for="costo_equipo">Costo aproximado de Equipo</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                    </div>
                    <input type="number" step="0.01" min="1.00" name="costo_equipo" id="costo_equipo" class="form-control" placeholder="500.00">
                </div>
                <div class="text-danger" id="error_costo_equipo"></div>
              </div>
              <div class="form-group col-md-2">
                <label for="dmf">Fecha Manofactura(año)</label>
                <input type="text" class="form-control" id="dmf" name="dmf" onchange="validar(this)" placeholder="2017">
                <div id="error_dmf" class="text-danger"></div>
              </div>
              <div class="form-group col-md-8">
                <label for="caracteristicas">Observación Extra</label>
                <textarea type="text" class="form-control" id="caracteristicas" name="caracteristicas" onchange="validar(this)"></textarea>
                <div id="error_caracteristicas" class="text-danger"></div>
             </div>
            </div>
        `);
    }
}

function validar(campo) {
    const input = campo;
    if (input.value.length > 0) {
        input.classList.remove('has-error');
        document.getElementById("error_" + input.id).textContent = '';
    }
}

function hardDisc(type) {
    if (type = 1) {
        const input = document.getElementById('disco_duro_txt');
        input.classList.remove('has-error');
        document.getElementById("error_disco_duro").textContent = '';
        if (input.value.length == 0) { return }
        $("#disco_duro").val(`${$("#disco_duro_txt").val()}${$("#disco_duro_extent").val()} ${$("#disco_duro_type").val()}`)
    }
    if (type = 2) {
        const input = document.getElementById('disco_duro_txt_');
        input.classList.remove('has-error');
        document.getElementById("error_disco_duro_").textContent = '';
        if (input.value.length == 0) { return }
        $("#disco_duro_").val(`${$("#disco_duro_txt_").val()}${$("#disco_duro_extent_").val()} ${$("#disco_duro_type_").val()}`)
    }
}

$("#alta_articulo").submit(function (e) {
    e.preventDefault();
    error = 0;
    $("#error_tipo").text("");
    $("#tipo").removeClass('has-error');
    if ($("#tipo").val().length == 0) {
        error++;
        $("#error_tipo").text("Campo requerido");
        $("#tipo").addClass('has-error');
    } else if ($("#tipo").val() == 2 || $("#tipo").val() == 3) {
        if ($("#marca").val().length == 0) {
            error++;
            $("#error_marca").text("Campo requerido");
            $("#marca").addClass('has-error');
        } else {
            $("#error_marca").text("");
            $("#marca").removeClass('has-error');
        }

        if ($("#no_serie").val().length == 0) {
            error++;
            $("#error_no_serie").text("Campo requerido");
            $("#no_serie").addClass('has-error');
        } else {
            $("#error_no_serie").text("");
            $("#no_serie").removeClass('has-error');
        }
                
        if ($("#modelo").val().length == 0) {
            error++;
            $("#error_modelo").text("Campo requerido");
            $("#modelo").addClass('has-error');
        } else {
            $("#error_modelo").text("");
            $("#modelo").removeClass('has-error');
        }

        if ($("#procesador").val().length == 0) {
            error++;
            $("#error_procesador").text("Campo requerido");
            $("#procesador").addClass('has-error');
        } else {
            $("#error_procesador").text("");
            $("#procesador").removeClass('has-error');
        }

        if ($("#memoria").val().length == 0) {
            error++;
            $("#error_memoria").text("Campo requerido");
            $("#memoria").addClass('has-error');
        } else {
            $("#error_memoria").text("");
            $("#memoria").removeClass('has-error');
        }

        if ($("#disco_duro_txt").val().length == 0) {
            error++;
            $("#error_disco_duro").text("Campo requerido");
            $("#disco_duro_txt").addClass('has-error');
        } else {
            $("#error_disco_duro").text("");
            $("#disco_duro_txt").removeClass('has-error');
        }

        if ($("#costo_equipo").val().length == 0) {
            error++;
            $("#error_costo_equipo").text("Campo requerido");
            $("#costo_equipo").addClass('has-error');
        } else {
            $("#error_costo_equipo").text("");
            $("#costo_equipo").removeClass('has-error');
        }
        
        if ($("#dmf").val().length == 0) {
            error++;
            $("#error_dmf").text("Campo requerido");
            $("#dmf").addClass('has-error');
        } else {
            $("#error_dmf").text("");
            $("#dmf").removeClass('has-error');
        }
    } else {
        if ($("#marca").val().length == 0) {
            error++;
            $("#error_marca").text("Campo requerido");
            $("#marca").addClass('has-error');
        } else {
            $("#error_marca").text("");
            $("#marca").removeClass('has-error');
        }

        if ($("#no_serie").val().length == 0) {
            error++;
            $("#error_no_serie").text("Campo requerido");
            $("#no_serie").addClass('has-error');
        } else {
            $("#error_no_serie").text("");
            $("#no_serie").removeClass('has-error');
        }

        if ($("#modelo").val().length == 0) {
            error++;
            $("#error_modelo").text("Campo requerido");
            $("#modelo").addClass('has-error');
        } else {
            $("#error_modelo").text("");
            $("#modelo").removeClass('has-error');
        }

        if ($("#costo_equipo").val().length == 0) {
            error++;
            $("#error_costo_equipo").text("Campo requerido");
            $("#costo_equipo").addClass('has-error');
        } else {
            $("#error_costo_equipo").text("");
            $("#costo_equipo").removeClass('has-error');
        }

        if ($("#dmf").val().length == 0) {
            error++;
            $("#error_dmf").text("Campo requerido");
            $("#dmf").addClass('has-error');
        } else {
            $("#error_dmf").text("");
            $("#dmf").removeClass('has-error');
        }
    }
    if (error != 0) {
        return false;
    }

    $("#btn_alta_articulo").prop("disabled", true);
    var data = new FormData($('#alta_articulo')[0]);
    $.ajax({
        data: data,
        url: `${urls}sistemas/registrar_equipo`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (save) {
            $("#btn_alta_articulo").prop("disabled", false);
            console.log(save);
            if (save.hasOwnProperty('xdebug_message')) {
                Swal.fire({
                    icon: "error",
                    title: "Oops, Exception...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
                console.log('Mensaje de xdebug:', save.xdebug_message);
            } else if (save == 'Duplicado') {
                Swal.fire(
                    "¡Número de serie ya existente!",// mensaje
                    "", //titulo
                    "info" // figura
                );
            } else if (save == true) {
                setTimeout(function () {
                    tbl_equipos.ajax.reload(null, false);
                }, 100);
                document.getElementById('alta_articulo').reset();
                $("#campos").empty();
                $("#tipo").val("");
                Swal.fire(
                    "¡Articulo agregado Exitosamente!",// mensaje
                    "", //titulo
                    "success" // figura
                );
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador del Sistema",
                });
            }

        }
    });
});

/* ---------------------- */

function Edit(id) {
    document.getElementById('form_edit_article').reset();
    $(".has-error").removeClass('has-error');
    const data = new FormData();
    data.append('id', id);
    $.ajax({
        data: data,
        url: `${urls}sistemas/datos_equipo`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (respEdit) {
            if (respEdit) {
                $("#folio_").val(id);
                $("#etiqueta_").val(respEdit.label_equip);
                $("#no_serie_").val(respEdit.no_serial);
                $("#marca_").val(respEdit.marca);
                $("#modelo_").val(respEdit.model);
                $("#tipo_").val(respEdit.type_equip);
                $("#caracteristicas_").val(respEdit.features);
                $("#estado_").val(respEdit.status_equip);
                $("#procesador_").val(respEdit.processor_data);
                $("#memoria_").val(respEdit.memory_data);
                $("#disco_duro_").val(respEdit.hard_drive_data);
                $("#disco_duro_txt_").val(respEdit.dato_numerico);
                $("#disco_duro_extent_").val(respEdit.unidad);
                $("#disco_duro_type_").val(respEdit.tipo_de_disco);
                $("#costo_equipo_").val(respEdit.approximate_cost);
                $("#fecha_").val(respEdit.created_at);
                $("#dmf_").val(respEdit.date_manofacture);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador del Sistema",
                });
            }
        }
    });
    $("#actualizaModal").modal("show");
}

function validarEdit(campo) {
    $("#error_" + campo.id).text('');
    $("#" + campo.id).removeClass('has-error');
}

$("#form_edit_article").submit(function (e) {
    e.preventDefault();
    errores = 0;
    /* if ($("#estado_").val().length == 0) {
        errores++;
        $("#error_estado_").text("Campo requerido");
        $("#estado_").addClass('has-error');
    } else {
        $("#error_estado_").text("");
        $("#estado_").removeClass('has-error');
    } */

    if ($("#no_serie_").val().length == 0) {
        errores++;
        $("#error_no_serie_").text("Campo requerido");
        $("#no_serie_").addClass('has-error');
    } else {
        $("#error_no_serie_").text("");
        $("#no_serie_").removeClass('has-error');
    }

    if ($("#tipo_").val().length == 0) {
        errores++;
        $("#error_tipo_").text("Campo requerido");
        $("#tipo_").addClass('has-error');
    } else {
        $("#error_tipo_").text("");
        $("#tipo_").removeClass('has-error');
    }

    if ($("#marca_").val().length == 0) {
        errores++;
        $("#error_marca_").text("Campo requerido");
        $("#marca_").addClass('has-error');
    } else {
        $("#error_marca_").text("");
        $("#marca_").removeClass('has-error');
    }

    if ($("#modelo_").val().length == 0) {
        errores++;
        $("#error_modelo_").text("Campo requerido");
        $("#modelo_").addClass('has-error');
    } else {
        $("#error_modelo_").text("");
        $("#modelo_").removeClass('has-error');
    }

    if ($("#dmf_").val().length == 0) {
        errores++;
        $("#error_dmf_").text("Campo requerido");
        $("#dmf_").addClass('has-error');
    } else {
        $("#error_dmf_").text("");
        $("#dmf_").removeClass('has-error');
    }


    if (errores > 0) { return false; }
    const timerInterval = Swal.fire({
        allowOutsideClick: false,
        title: '¡ACTUALIZANDO!',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });
    $("#btn_edit_article").prop("disabled", true);
    var data = new FormData($('#form_edit_article')[0]);
    $.ajax({
        data: data,
        url: `${urls}sistemas/editar_datos_equipo`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (response) {
            Swal.close(timerInterval);
            $("#btn_edit_article").prop("disabled", false);
            if (response.hasOwnProperty('xdebug_message')) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal en editar_datos_equipo! Contactar con el Administrador",
                });
                console.log(save.xdebug_message);
            } else if (response === true) {
                setTimeout(function () {
                    tbl_equipos.ajax.reload(null, false);
                }, 100);
                Swal.fire(
                    "¡Articulo actualizado Exitosamente!",// mensaje
                    "", //titulo
                    "success" // figura
                );
                $("#actualizaModal").modal("toggle");
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador del Sistema",
                });
            }
        }
    });
});

function History(id) {
    $("#tbl_h").empty();
    var dataID = new FormData();
    dataID.append('id', id);
    $.ajax({
        data: dataID,
        url: `${urls}sistemas/datos_equipo`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (respHistoryTbl) {
            if (respHistoryTbl) {
                $("#no_serie_h").val(respHistoryTbl.no_serial);
                $("#etiqueta_h").val(respHistoryTbl.label_equip);
                $("#estado_h").val(respHistoryTbl.txt);
                $("#estado_h").attr('class', `form-control btn-${respHistoryTbl.color}`);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador del Sistema",
                });
            }
        }
    });
    $("#tbl_h").append(`<table id="tbl_historial_equipos_${id}" class="table table-bordered table-striped " role="grid" aria-describedby="equipos_info" style="width:100%" ref="">
    </table>`);

    tbl_historial_equipos = $("#tbl_historial_equipos_" + id).dataTable({
        processing: true,
        ajax: {
            data: { 'id': id },
            method: "post",
            url: `${urls}sistemas/historial_equipo_no_serial`,
            dataSrc: "",
        },
        lengthChange: true,
        ordering: true,
        responsive: true,
        autoWidth: false,
        rowId: "staffId",
        dom: "frtip",
        buttons: [
            /* {
                extend: "excelHtml5",
                title: "Solicitud de Vehiculo",
                exportOptions: {
                    columns: [0, 1, 2, 3],
                },
            }, */
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
                data: "fechaInicio",
                title: "DESDE:",
                className: "text-center",
            },
            {
                data: null,
                render: function (data, type, full, meta) {
                    if (data["fechaFinal"] == null) {
                        return `En Posecion`;
                    } else {
                        return data["fechaFinal"];
                    }
                },
                title: "HASTA:",
                className: "text-center",
            },
            {
                data: "usuario",
                title: "USUARIO",
                className: "text-center",
            },
            {
                data: "departamento",
                title: "DEPARTAMENTO",
                className: "text-center",
            },
            {
                data: "puesto",
                title: "PUESTO",
                className: "text-center",
            },
            {
                data: null,
                render: function (data, type, full, meta) {
                    return ` <div class="mr-auto">
                          <a href="${urls}sistemas/pdf-responsiva-asignacion/${$.md5(key + data['folio'])}" target="_blank" title="Responsiba equipo" class="btn btn-danger btn-sm">
                              <i class="fas fa-file-pdf"></i>
                          </a>
                      </div> `;
                },
                title: "RESP.",
                className: "text-center",
            },
        ],
        order: [[1, "DESC"]],
        createdRow: (row, data) => {
            $(row).attr("id", "equip_" + data.id_equip);
        },
    }).DataTable();
    $(`#tbl_historial_equipos_${id} thead`).addClass("thead-dark text-center");
    $("#historialModal").modal("show");
}

/* ---------------------- */

var error_tipo_buscar = "";
var error_ID_buscar = "";
function tipoBuscar() {
    error_tipo_buscar = "";
    $("#error_tipo_buscar").text(error_tipo_buscar);
    $("#tipo_buscar").removeClass('has-error');
    $("#campo_buscar").empty();

    if ($("#tipo_buscar").val() == 1) {
        $("#campo_buscar").addClass('$col-md-4');
        $("#campo_buscar").append(`
        <label for="ID_buscar">Nomina</label>
            <input type="text" name="ID_buscar" id="ID_buscar" class="form-control" onchange="buscarUsuario()">
        <div id="error_ID_buscar" class="text-danger"></div>
        `);
    } else if ($("#tipo_buscar").val() == 2) {
        $("#campo_buscar").addClass('$col-md-4');
        $("#campo_buscar").append(`
        <label for="ID_buscar">*En Obra Negra*</label>
        <select name="ID_buscar" id="ID_buscar" class="form-control" >
            <option value="">Departamentos</option>
            <?php foreach ($departamentos as $key => $value) { ?>
                <option value="<?= $value->id_depto; ?>"><?= $value->departament; ?></option>
            <?php } ?>
        </select>
        <div id="error_ID_buscar" class="text-danger"></div>
        `);
    } else if ($("#tipo_buscar").val() == 3) {
        $("#campo_buscar").addClass('$col-md-4');
        $("#campo_buscar").append(`
        <label for="ID_buscar">IMEI / No. Serie</label>
            <input type="text" name="ID_buscar" id="ID_buscar" class="form-control" onchange="buscarEquipo()">
        <div id="error_ID_buscar" class="text-danger"></div>
        `);
    } else {
        $("#campo_buscar").removeClass('$col-md-4');
        $("#campo_buscar").empty();
    }
}

function buscarUsuario() {
    error_ID_buscar = "";
    $("#error_ID_buscar").text(error_ID_buscar);
    $("#ID_buscar").removeClass('has-error');
    if ($("#ID_buscar").val().length == 0) {
        return false;
    }
    if ($("#ID_buscar").val().length == 0) {
        return false;
    }
    $("#dato_Asig_usuario").empty();
    var data = new FormData();
    data.append('ID', $("#ID_buscar").val());
    $.ajax({
        data: data,
        url: `${urls}sistemas/datos_usuario`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (respUserBuscar) {
            if (respUserBuscar) {
            } else {
                error_ID_buscar = "Nomina no Encontrada";
                $("#error_ID_buscar").text(error_ID_buscar);
                $("#ID_buscar").addClass('has-error');
            }
        }
    });
}

function buscarEquipo() {
    error_ID_buscar = "";
    $("#error_ID_buscar").text(error_ID_buscar);
    $("#ID_buscar").removeClass('has-error');
    if ($("#ID_buscar").val().length == 0) {
        return false;
    }
    $("#dato_Asig_equipo").empty();
    var data = new FormData();
    data.append('id', $("#ID_buscar").val());
    $.ajax({
        data: data,
        url: `${urls}sistemas/datos_equipo`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (respEquipBuscar) {
            if (respEquipBuscar) {
            } else {
                error_ID_buscar = "Dato no encontrado";
                $("#error_ID_buscar").text(error_ID_buscar);
                $("#ID_buscar").addClass('has-error');
            }
        }
    });
}

$("#buscar_equipo").submit(function (e) {
    e.preventDefault();
    $("#tbl_buscar").empty();

    var tipo_buscar = $("#tipo_buscar").val();
    var id_buscar = $("#ID_buscar").val();

    if (tipo_buscar.length == 0) {
        error_tipo_buscar = "Campo Requerido";
        $("#error_tipo_buscar").text(error_tipo_buscar);
        $("#tipo_buscar").addClass('has-error');
    }
    if (id_buscar.length == 0) {
        error_ID_buscar = "Campo Requerido";
        $("#error_ID_buscar").text(error_ID_buscar);
        $("#ID_buscar").addClass('has-error');
    } else {
        error_ID_buscar = "";
        $("#error_ID_buscar").text(error_ID_buscar);
        $("#ID_buscar").removeClass('has-error');
    }

    if (error_ID_buscar != "" || error_tipo_buscar != "") {
        return false
    }
    $("#btn_buscar_equipo").prop("disabled", true);
    $("#tbl_buscar").append(`
        <hr>
        <table id="tbl_historial_${id_buscar}" class="table table-bordered table-striped " role="grid" aria-describedby="equipos_info" style="width:100%" ref="">
        </table>
    `);
    if (tipo_buscar == 1) {
        tbl_historial = $(`#tbl_historial_${id_buscar}`).dataTable({
            processing: true,
            ajax: {
                data: { 'ID_buscar': id_buscar },
                method: "post",
                url: `${urls}sistemas/historial_equipo_nomina`,
                dataSrc: "",
            },
            lengthChange: true,
            ordering: true,
            responsive: true,
            autoWidth: false,
            rowId: "staffId",
            dom: "lBfrtip",
            buttons: [
                {
                    extend: "excelHtml5",
                    title: "Historial de Equipos por Usuario",
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
                    data: "folio",
                    title: "ID",
                    className: "text-center",
                },
                {
                    data: "fechaInicio",
                    title: "DESDE:",
                    className: "text-center",
                },
                {
                    data: null,
                    render: function (data, type, full, meta) {
                        if (data["fechaFinal"] == null) {
                            return `En Posecion`;
                        } else {
                            return data["fechaFinal"];
                        }
                    },
                    title: "HASTA:",
                    className: "text-center",
                },
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        let marca = data["marca"].toUpperCase();
                        return marca;
                    },
                    title: "MARCA",
                    className: "text-center",
                },
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        let model = data["modelo"].toUpperCase();
                        return model;
                    },
                    title: "MODELO",
                    className: "text-center",
                },
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        let tipo = data["tipo"].toUpperCase();
                        return tipo;
                    },
                    title: "TIPO",
                    className: "text-center",
                },
                {
                    data: "no_serie",
                    title: "IMEI / No. SERIE",
                    className: "text-center",
                },
            ],
            columnDefs: [
                {
                    targets: [0],
                    visible: false,
                    searchable: false,
                },
            ],
            order: [[1, "DESC"]],
            /* createdRow: (row, data) => {
                $(row).attr("id", "equip_" + data.id_equip);
            }, */
        })
            .DataTable();
        $(`#tbl_historial_${id_buscar} thead`).addClass("thead-dark text-center");
    }
    else if (tipo_buscar == 3) {
        tbl_historial = $(`#tbl_historial_${id_buscar}`).dataTable({
            processing: true,
            ajax: {
                data: { 'ID_buscar': id_buscar },
                method: "post",
                url: `${urls}sistemas/historial_equipo_no_serial`,
                dataSrc: "",
            },
            lengthChange: true,
            ordering: true,
            responsive: true,
            autoWidth: false,
            rowId: "staffId",
            dom: "lBfrtip",
            buttons: [
                {
                    extend: "excelHtml5",
                    title: "Historial de Equipo",
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7],
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
                    title: "ID",
                    className: "text-center",
                },
                {
                    data: "fechaInicio",
                    title: "DESDE:",
                    className: "text-center",
                },
                {
                    data: null,
                    render: function (data, type, full, meta) {
                        if (data["fechaFinal"] == null) {
                            return `En Posecion`;
                        } else {
                            return data["fechaFinal"];
                        }
                    },
                    title: "HASTA:",
                    className: "text-center",
                },
                {
                    data: "nomina",
                    title: "NOMINA",
                    className: "text-center",
                },
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        let nombre = data["nombre"].toUpperCase() + " " + data["apep"].toUpperCase() + " " + data["apem"].toUpperCase();
                        return nombre;
                    },
                    title: "USUARIO",
                    className: "text-center",
                },
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        let departamento = data["departamento"].toUpperCase();
                        return departamento;
                    },
                    title: "DEPARTAMENTO",
                    className: "text-center",
                },
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        let puesto = data["puesto"].toUpperCase();
                        return puesto;
                    },
                    title: "PUESTO",
                    className: "text-center",
                },
                {
                    data: "comentario",
                    title: "COMENTARIO",
                    className: "text-center",
                },

            ],
            columnDefs: [
                {
                    targets: [0],
                    visible: false,
                    searchable: false,
                },
            ],
            order: [[1, "DESC"]],
            /* createdRow: (row, data) => {
                $(row).attr("id", "equip_" + data.id_equip);
            }, */
        })
            .DataTable();
        $(`#tbl_historial_${id_buscar} thead`).addClass("thead-dark text-center");
    }
    $("#tipo_buscar").val("");
    $("#campo_buscar").empty();
    $("#btn_buscar_equipo").prop("disabled", false);
});