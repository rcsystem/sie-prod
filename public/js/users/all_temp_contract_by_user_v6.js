/* 
* MODULO: CONTRATOS TEMPORALES
* AUTOR: Horus Samael Rivas Pedraza
* CONTACTO: horus.riv.ped@gmail.com, 56 2439 2632
*/
$(document).ready(function () {

    tbl_contracto = $("#tbl_contratos_de_usuario")
        .dataTable({
            processing: true,
            ajax: {
                data: { 'id_user': $("#id_md5").val() },
                method: "post",
                url: `${urls}usuarios/todos_contratos_temp`,
                dataSrc: "",
            },
            lengthChange: true,
            ordering: true,
            responsive: true,
            autoWidth: true,
            rowId: "staffId",
            dom: "lBfrtip",
            buttons: [
                /* {
                  extend: "excelHtml5",
                  title: "Permisos",
                  exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6],
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
                    data: null,
                    render: function (data, type, full, meta) {                        
                        return (data["type_of_contract"] == "PLANTA") 
                        ? `<span style="font-size:15px;" class="badge badge-${data["color"]}">${data["type_of_contract"]}</span>` 
                        : data["type_of_contract"];
                    },
                    title: "TIPO",
                    className: "text-center",
                },
                {
                    data: "date_of_new_entry",
                    title: "INICIO DE CONTRATO",
                    className: "text-center",
                },
                {
                    data: "date_expiration",
                    title: "VENCIMIENTO DE CONTRATO",
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
                    targets: 3,
                    render: function (data, type, full, meta) {
                        const color = (data["option"] == 1) ? 'secondary' : `outline-primary`;
                        const change = (data["option"] == 1) ? '' : `onClick="Edit(${data["id_contract"]},${data["type_of_employee"]})"`;
                        return ` <div class="pull-right mr-auto">
                            <button type="button" class="btn btn-${color} btn-sm " title="Editar" ${change}>
                                <i class="far fa-edit"></i>
                            </button> 
                            <button type="button" class="btn btn-outline-danger btn-sm " title="Eliminar Contato"  onClick="deleteCT(${data["id_contract"]})">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                            <a class="btn btn-outline-info btn-sm" href="${urls}/usuarios/ver-contrato/${$.md5(key + data["id_contract"])}" title="Ver Contrato" target="_blank">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div> `;
                    },
                },
                /* {
                    targets: [0],
                    visible: false,
                    searchable: false,
                }, */
            ],

            order: [[1, "ASC"]],

            createdRow: (row, data) => {
                $(row).attr("id", "info_" + data.id_contract);
            },
        })
        .DataTable();

    $("#tbl_contratos_de_usuario thead").addClass("thead-dark text-center");

});

function Edit(id_, type_emple) {
    console.log('ID: ', id_);
    const tipo = $(`#info_${id_} td`)[0].innerHTML;
    const data = new FormData();
    data.append('id_contract', id_);
    $("#fecha_recontrato").val();
    $("#fecha_recontrato").attr('readonly', false);
    $("#error_fecha_recontrato").val("");
    $("#fecha_recontrato").removeClass('has-error');
    $.ajax({
        data: data,
        type: "post",
        url: `${urls}usuarios/datos_editar_contratos_temp`,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (resp) {
            if (resp != null && resp != false) {
                $("#folio").val(id_);
                $("#tipo").val(tipo);
                $("#fecha_recontrato").val(resp.date_reing);
                $("#fecha_creacion").val(resp.date_of_new_entry);
                $("#fecha_expiracion").val(resp.date_expiration);
                if (type_emple == 1) {
                    $("#fecha_recontrato").attr('readonly', true);
                }
                $("#contrato_temporal_Modal").modal("toggle");
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
            }

        }
    })
    $("#contrato_temporal_Modal").modal("show");
}

function deleteCT(id_folio) {
    const tipo = $(`#info_${id_folio} td`)[0].innerHTML;
    Swal.fire({
        title: `¿Deseas Eliminar el Contrato Temporal de ${tipo}.<br>Con Folio: ${id_folio} ?`,
        text: `Una vez Eliminado no podras Recuperarlo!`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Eliminar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.value) {
            let dataForm = new FormData();
            dataForm.append("id_folio", id_folio);
            $.ajax({
                data: dataForm,
                url: `${urls}usuarios/eliminar_contrato_temporal`,
                type: "post",
                processData: false,
                contentType: false,
                dataType: "json",
                success: function (response) {
                    if (response === true) {
                        setTimeout(function () {
                            tbl_contracto.ajax.reload(null, false);
                        }, 200);
                        Swal.fire("!El Contrato se elimino exitosamente!", "", "success");
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

$("#form_contrato_temp").submit(function (event) {
    event.preventDefault();
    $("#fecha_recontrato").removeClass('has-error');
    $("#error_fecha_recontrato").text("");
    if ($("#fecha_recontrato").val().lenght == 0) {
        $("#fecha_recontrato").addClass('has-error');
        $("#error_fecha_recontrato").text("Campo Requerido");
        return false;
    }
    $("#btn_contrato_temp").prop("disabled", true);
    let data = new FormData($("#form_contrato_temp")[0]);
    $.ajax({
        data: data,
        type: "post",
        url: `${urls}usuarios/editar_contratos_temp`,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (resp) {
            $("#btn_contrato_temp").prop("disabled", false);
            if (resp == true) {
                setTimeout(function () {
                    tbl_contracto.ajax.reload(null, false);
                }, 200);
                $("#contrato_temporal_Modal").modal("toggle");
                Swal.fire("!Los datos se han Actualizado!", "", "success");
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
            }

        }
    })
})