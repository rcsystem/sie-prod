/**
 * ARCHIVO MODULO PERMISSIONS
 * AUTOR:HORUS SAMAEL RIVAS PEDRAZA
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:56 2439 2632
 */
$(document).ready(function () {
    tbl_permissions = $("#tabla_usuarios_permisos")
        .dataTable({
            processing: true,
            ajax: {
                method: "post",
                url: `${urls}permisos/permisos_usuarios`,
                dataSrc: "",
            },
            lengthChange: true,
            ordering: true,
            responsive: true,
            autoWidth: true,
            rowId: "staffId",
            dom: "lBfrtip",
            buttons: [
                {
                    extend: "excelHtml5",
                    title: "Permisos_Usuarios",
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 0, 7],
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
                    data: "payroll_number",
                    title: "NOMINA",
                    className: "text-center",
                },
                {
                    data: "user_name",
                    title: "USUARIO",
                },
                {
                    data: "job",
                    title: "PUESTO",
                    className: "text-center",
                },
                {
                    data: "departament",
                    title: "DEPARTAMENTO",
                    className: "text-center",
                },
                {
                    data: "amount_permissions",
                    title: "PERMISOS PERSONALES",
                    className: "text-center",
                },
                {
                    data: null,
                    title: "PERMISO EXTRA",
                    className: "text-center",
                },
            ],
            destroy: "true",
            columnDefs: [
                {
                    targets: 5,
                    render: function (data, type, full, meta) {
                        lbl_status = ['ERROR', 'DESACTIVADO', 'ACTIVADO'];
                        setTimeout(function () {
                            if (data["director_permission"] == 2) {
                                $("#permiso_extra_" + data["id"]).attr('checked', true);
                            } else {
                                $("#permiso_extra_" + data["id"]).attr('checked', false);
                            }
                        }, 10);
                        return `<div class="pull-right mr-auto">
                        <div class="row">
                        <div class="col-md-6" style="text-align:end">
                        <label id="lbl_extra_${data["id"]}">${lbl_status[data["director_permission"]]}</label>
                        </div>
                        <div class="col-md-6" style="text-align:initial;padding-top: 1px;">
                        <div class="toggle">
                            <input type="checkbox" id="permiso_extra_${data["id"]}" onchange="OnOffExtra(${data["id"]})">
                            <label></label>
                            </div>
                        </div>
                    </div> `;
                    },
                },
                /* {
                 targets: [0],
                 visible: false,
                 searchable: false,
               },   */
            ],

            order: [[0, "DESC"]],
            createdRow: (row, data) => {
                $(row).attr("id", "permissions_" + data.id);
            },
        })
        .DataTable();
    $("#tabla_usuarios_permisos thead").addClass("thead-dark text-center");
});

function OnOffExtra(id_item) {
    var usuario = $(`#permissions_${id_item} td`)[1].innerHTML;
    $("#permiso_extra_" + id_item).prop("disabled", true);
    $("#lbl_extra_" + id_item).empty();
    if ($("#permiso_extra_" + id_item).is(':checked')) {
        texto = "ACTIVADO"; estado = 2;
    } else {
        texto = "DESACTIVADO"; estado = 1;
    }
    $("#lbl_extra_" + id_item).append(texto);
    let data = new FormData();
    data.append("id", id_item);
    data.append("status", estado);
    $.ajax({
        data: data,
        url: urls + "permisos/permiso_extra",
        type: "post",
        processData: false,
        contentType: false,
        success: function (response) {
            $("#permiso_extra_" + id_item).prop("disabled", false);
            if (response) {
                Swal.fire(`!SÉ HA ${texto}!`,
                    `PERMISOS EXTRA PARA EL USUARIO: ${usuario}`,
                    "success");
                // tbl_permissions.ajax.reload(null, false);
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

$("#off_all").on('click', function (e) {
    e.preventDefault();
    $("#off_all").prop("disabled", false);
    $.ajax({
        url: urls + "permisos/permisos_desactivar",
        type: "post",
        processData: false,
        contentType: false,
        success: function (response) {
            $("#off_all").prop("disabled", false);
            if (response) {
                Swal.fire(`!SÉ HAN DESACTIVADO!`,
                    `TODOS LOS PERMISOS EXTRA`,
                    "success");
                tbl_permissions.ajax.reload(null, false);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
            }
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        $("#off_all").prop("disabled", false);
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
})