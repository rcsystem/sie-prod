/*
 * ARCHIVO MODULO RECORRIDOS HSE
 * AUTOR:Horus Samael Rivas Pedraza
 * EMAIL:horus.riv.ped@gmail.com
 */

$(document).ready(function () {
    tbl_condiciones_inseguras = $("#tbl_incidencias").dataTable({
        processing: true,
        ajax: {
            method: "post",
            url: `${urls}recorridos-HSE/todas_incidencias_actividades`,
            dataSrc: "",
        },
        lengthChange: true,
        ordering: true,
        responsive: true,
        autoWidth: false,
        rowId: "staffId",
        dom: "lfrtip",
        language: {
            url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
        },
        columns: [
            {
                data: "id_incidents",
                title: "ID",
                className: "text-center",
            },
            {
                data: "name_user",
                title: "USUARIO",
                className: "text-center",
            },
            {
                data: null,
                render: function (data, type, full, meta) {
                    if (data["response_opc"] == null) {
                        return `<button type="button" class="btn btn-primary btn-sm" onclick="Edit(${data["id_incidents"]})">
                            EVALUAR
                        </button>`;
                    } else {
                        return `<span class="badge badge-success">EVALUADO</span>`;
                    }
                },
                title: "ESTADO",
                className: "text-center",
            },
            {
                data: "created",
                title: "FECHA | HORA",
                className: "text-center",
            },
            {
                data: "txt_category",
                title: "TIPO",
                className: "text-center",
            },
            {
                data: "description",
                title: "DESCRIPCION",
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
            $(row).attr("id", "equip_" + data.id_equip);
        },
    }).DataTable();
    $("#tbl_incidencias thead").addClass("thead-dark text-center");
});

$("#form_confirm_solucion").submit(function (e) {
    e.preventDefault();
    const btn = document.getElementById('btn_confirm_solucion');
    var errors = 0;
    const campo = document.getElementById('respuesta_opc');
    console.log(campo.value);
    if (campo.value.length == 0) {
        errors++;
        $("#error_" + campo.id).text('Campo Requerido');
    } else {
        $("#error_" + campo.id).text('');
    }

    const foto_incidencia = document.getElementById('foto_incidencia');
    if (foto_incidencia.value.length == 0) {
        errors++;
        // $("#error_" + foto_incidencia.id).text('Campo Requerido');
        Swal.fire({
            title: `Falta Evidencia <i class="far fa-image" style="margin-left: 5px;"></i>`,
        });
    }

    if (errors > 0) {
        return;
    }
    btn.disabled = true;
    const timerInterval = Swal.fire({
        title: '¡Guardando Respuesta!',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading()
        },
    });

    const data_form = new FormData($("#form_confirm_solucion")[0]);
    $.ajax({
        type: "post",
        url: `${urls}recorridos-HSE/registrar_respuesta_incidencia`,
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
                    text: "Algo salió Mal en registrar_respuesta_incidencia! Contactar con el Administrador",
                });
                console.log(save.xdebug_message);
            } else if (save === true) {
                tbl_condiciones_inseguras.ajax.reload(null, false);
                $("#form_confirm_solucion")[0].reset();
                $("#actualizaModal").modal("hide");
                Swal.fire({
                    icon: 'success',
                    title: "¡Actualizacion Exitoso!",
                    text: 'Se registró la respuesta.',
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

function Edit(id_incident) {
    $("#form_confirm_solucion")[0].reset();
    $(".btn_tomar_foto").empty();
    $("#error_respuesta_opc").text('');
    $(".btn-opcion").removeClass('active');
    $(".imagePreview").attr("style", "display:none");
    $(".btn_tomar_foto").append(`<i class="fas fa-camera"  style="margin-right: 10px;"></i>TOMAR FOTO`);

    $("#id_incidencia").val(id_incident);
    $("#actualizaModal").modal("show");
}

function mostrarImagenPrevia(campo) {
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

function cambioValorRadioBtn(opc) {
    $("#error_respuesta_opc").text('');
    $("#respuesta_opc").val(opc)
}



