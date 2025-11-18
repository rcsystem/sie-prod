/*
 * ARCHIVO MODULO SERVICIO MEDICO
 * AUTOR:Horus Samael Rivas Pedraza
 * EMAIL:horus.riv.ped@gmail.com
 * CEL:56 2439 2632
*/
const text = ['No Definido', 'Más de 12 meses', 'De 6 a 12 meses', 'Menos de 6 meses', 'Menos de 3 meses', 'Caduco'];
const color_css = ['#5C636A', '#28a745', '#ffc107', '#F65E0A', '#dc3545', '#212529'];
const presentacion = ['ERROR', 'TABLETAS', 'AMPULAS', 'FRASCO', 'CAPSULAS', 'AMPOLLETAS', 'AMPOLLETA', 'GOTERO', 'TUBO', 'COMPRIMIDOS'];
const color = ['secondary', 'success', 'warning', 'acceptable', 'danger', 'dark'];

$(document).ready(function () {
    tabla_supplies = $("#tabla_suministros")
        .dataTable({
            responsive: true,
            lengthChange: false,
            autoWidth: false,
            buttons: [
            ],
            processing: true,
            ajax: {
                data: { "type": 1 },
                method: "post",
                url: `${urls}medico/inventario_medicamentos`,
                dataSrc: "",
            },
            lengthChange: true,
            ordering: true,
            autoWidth: false,
            rowId: "staffId",
            dom: "lBfrtip",
            language: {
                url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
            },
            columns: [
                {
                    data: "id_medicine",
                    title: "FOLIO",
                    className: "text-center",
                },
                {
                    data: "active_substance",
                    title: "SUSTANCIA ACTIVA",
                    className: "text-center",
                },
                {
                    data: "expiration_date",
                    title: "FECHA CADUCIDAD",
                    className: "text-center",
                },
                {
                    data: null,
                    render: function (data, type, full, meta) {
                        let i = parseInt(data["traffic_light"]);
                        return `<span class="badge bg-${color[i]}">${text[i]}</span>`;
                    },
                    title: "SEMAFORO",
                    className: "text-center",
                },
                {
                    data: null,
                    title: "ALMACEN - PASTILLEO",
                    className: "text-center",
                },
                {
                    data: null,
                    title: "ACCIONES",
                    className: "text-center",
                },
                {
                    data: "traffic_light",
                    title: "SEMAFORO",
                    className: "text-center",
                },
            ],
            destroy: "true",
            columnDefs: [
                {
                    targets: 4,
                    render: function (data, type, full, meta) {
                        lbl_status = ['ERROR', 'ALMACEN', 'PASTILLEO'];
                        setTimeout(function () {
                            if (data["inventory_tablet"] == 2) {
                                $("#permiso_extra_" + data["id_medicine"]).attr('checked', true);
                            } else {
                                $("#permiso_extra_" + data["id_medicine"]).attr('checked', false);
                            }
                        }, 10);
                        return `<div class="pull-right mr-auto">
                        <div class="row">
                            <div class="col-md-6" style="text-align:end">
                                <label id="lbl_extra_${data["id_medicine"]}">${lbl_status[data["inventory_tablet"]]}</label>
                            </div>
                            <div class="col-md-6" style="text-align:initial;padding-top: 1px;">
                                <div class="toggle">
                                    <input type="checkbox" id="permiso_extra_${data["id_medicine"]}" onchange="OnOffExtra(${data["id_medicine"]})">
                                    <label></label>
                                </div>
                            </div>
                        </div> `;
                    },
                },
                {
                    targets: 5,
                    render: function (data, type, full, meta) {
                        return ` <div class="pull-right mr-auto">
                      <button type="button" class="btn btn-info btn-sm" title="Editar Producto"  onClick=handleEdit(${data["id_medicine"]})>
                        <i class="fas fa-info-circle"></i>
                      </button>
                      <button type="button" class="btn btn-danger btn-sm" title="Eliminar Producto"  onClick="handleDelete(${data["id_medicine"]})">
                      <i class="fas fa-trash-alt"></i>
                  </button>
                    </div> `;
                    },
                },
                {
                  targets: [6],
                  visible: false,
                  searchable: false,
                },
            ],
            order: [[6, "ASC"]],
            createdRow: (row, data) => {
                $(row).attr("id", "medicine_" + data.id_medicine);
            },
        })
        .DataTable();
    $('#tabla_suministros thead').addClass('thead-dark text-center');

    tabla_tablet = $("#tabla_suministros_pastilleo")
        .dataTable({
            responsive: true,
            lengthChange: false,
            autoWidth: false,
            buttons: [
            ],
            processing: true,
            ajax: {
                data: { "type": 2 },
                method: "post",
                url: `${urls}medico/inventario_medicamentos`,
                dataSrc: "",
            },
            lengthChange: true,
            ordering: true,
            autoWidth: false,
            rowId: "staffId",
            dom: "lBfrtip",
            language: {
                url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
            },
            columns: [
                {
                    data: "id_medicine",
                    title: "FOLIO",
                    className: "text-center",
                },
                {
                    data: "active_substance",
                    title: "SUSTANCIA ACTIVA",
                    className: "text-center",
                },
                {
                    data: "trademark",
                    title: "NOMBRE COMERCIAL",
                    className: "text-center",
                },
                {
                    data: null,
                    render: function (data, type, full, meta) {
                        let i = parseInt(data["traffic_light"]);
                        return `<span class="badge bg-${color[i]}">${text[i]}</span>`;
                    },
                    title: "SEMAFORO",
                    className: "text-center",
                },
                {
                    data: null,
                    render: function (data, type, full, meta) {
                        let i_pro = data["id_presentation"];
                        if (i_pro == 7 || i_pro == 8 || i_pro == 3) {
                            return `${presentacion[i_pro]}`;
                        } else {
                            return `${data["piezas"]}  ${presentacion[i_pro]}`;
                        }
                    },
                    title: "PIEZAS",
                    className: "text-center",
                },
                {
                    data: null,
                    title: "ACCIONES",
                    className: "text-center",
                },
                {
                    data: "traffic_light",
                    title: "SEMAFORO",
                    className: "text-center",
                },
            ],
            destroy: "true",
            columnDefs: [
                {
                    targets: 5,
                    render: function (data, type, full, meta) {
                        return ` <div class="pull-right mr-auto">
                  <!--- <button type="button" class="btn btn-info btn-sm" title="Editar Producto"  onClick=handleEdit(${data["id_medicine"]})>
                      <i class="far fa-edit"></i>
                  </button> --->
                  <button type="button" class="btn btn-danger btn-sm" title="Eliminar Producto"  onClick="handleDelete(${data["id_medicine"]})">
                  <i class="fas fa-trash-alt"></i>
              </button>
                </div> `;
                    },
                },
                {
                    targets: [6],
                    visible: false,
                    searchable: false,
                  },
              ],
              order: [[6, "ASC"]],

            createdRow: (row, data) => {
                $(row).attr("id", "medicine_" + data.id_medicine);
            },
        })
        .DataTable();
    $('#tabla_suministros_pastilleo thead').addClass('thead-dark text-center');
});


function handleDelete(id_folio) {
    const producto = $(`#medicine_${id_folio} td`)[1].innerHTML;
    Swal.fire({
        title: `Deseas Eliminar: ${producto}`,
        text: `Una vez Eliminado no podras usar el Producto!`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#5A6268",
        confirmButtonText: "Eliminar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.value) {
            var id_medicament = new FormData();
            id_medicament.append("product_", id_folio)
            $.ajax({
                data: id_medicament, //datos que se envian a traves de ajax
                url: `${urls}medico/eliminar_medicamento`, //archivo que recibe la peticion
                type: "post", //método de envio
                processData: false, // dile a jQuery que no procese los datos
                contentType: false, // dile a jQuery que no establezca contentType
                dataType: "json",
                success: function (response) {
                    if (response == true) {
                        tabla_supplies.ajax.reload(null, false);
                        Swal.fire("!Medicamento Eliminado Correctamente!", "", "success");
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

function handleEdit(id_) {
    var id = new FormData();
    id.append('id_medicamento', id_);
    $.ajax({
        data: id,
        url: `${urls}medico/datos_medicamento`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (medic) {
            if (medic == false) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
            } else {
                $("#id_medicament").val(medic.id_medicine);
                $("#modal_sustancia_activa").val(medic.active_substance);
                $("#modal_nombre_comercial").val(medic.trademark);
                $("#modal_presentacion").val(medic.id_presentation);
                $("#modal_pz_caja").val(medic.pieza_caja);
                $("#modal_pz_exist").val(medic.piezas);
                $("#modal_fecha_caducidad").val(medic.expiration_date);
                $("#color_semaforo").prop('style', `background-color:${color_css[medic.traffic_light]}`);
                $("#semaforo").val(text[medic.traffic_light]);
                $("#actualizaModal").modal("show");
            }
        }
    });

}

function OnOffExtra(id_item) {
    const medicamento = $(`#medicine_${id_item} td`)[1].innerHTML;
    $("#permiso_extra_" + id_item).prop("disabled", true);
    $("#lbl_extra_" + id_item).empty();
    if ($("#permiso_extra_" + id_item).is(':checked')) {
        texto = "PATILLEO"; estado = 2;
    } else {
        texto = "ALMACEN"; estado = 1;
    }
    $("#lbl_extra_" + id_item).append(texto);
    let data = new FormData();
    data.append("id", id_item);
    data.append("status", estado);
    $.ajax({
        data: data,
        url: `${urls}medico/mover_medicamento`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (response) {
            $("#permiso_extra_" + id_item).prop("disabled", false);
            if (response) {
                Swal.fire(`!SÉ HA TRANSFERIDO A ${texto}!`,
                    `El medicamento: ${medicamento} ha sido trasferido correctamente.`,
                    "success");
                tabla_tablet.ajax.reload(null, false);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
            }
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        $("#permiso_extra_" + id_item).prop("disabled", false);
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

$("#presentacion").on('change', function () {
    var valor = $("#presentacion").val();
    $("#pz_caja").attr('readonly', false);
    $("#pz_caja").attr('required', true);
    if (valor == 3 || valor == 7 || valor == 8) {
        $("#pz_caja").attr('required', false);
        $("#pz_caja").attr('readonly', true);
    }
});

$("#form_medicament").submit(function (event) {
    event.preventDefault();
    $("#alta_medicament").prop("disabled", true);
    let data = new FormData($("#form_medicament")[0]);
    $.ajax({
        data: data, //datos que se envian a traves de ajax
        url: `${urls}medico/alta_medicamento`, //archivo que recibe la peticion
        type: "post", //método de envio
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentType
        dataType: "json",
        success: function (response) {
            $("#alta_medicament").prop("disabled", false);
            if (response == true) {
                $('#form_medicament')[0].reset();
                setTimeout(function () {
                    tabla_supplies.ajax.reload(null, false);
                }, 100);
                $("#pz_caja").attr('readonly', false);
                $("#pz_caja").attr('required', true);
                Swal.fire("!El Producto ah sido dado de Alta!", "", "success");
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
});

$("#edit_article").submit(function (event) {
    event.preventDefault();
    let data = new FormData($("#edit_article")[0]);
    $.ajax({
        data: data,
        url: urls + "sistemas/editar_producto",
        type: "post",
        processData: false,
        contentType: false,
        success: function (response) {
            if (response != false) {
                setTimeout(function () {
                    tabla_supplies.ajax.reload(null, false);
                }, 100);
                $("#actualizaModal").modal("toggle");
                Swal.fire("!Los datos se han Actualizado!", "", "success");
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
            }
        },
    });
});