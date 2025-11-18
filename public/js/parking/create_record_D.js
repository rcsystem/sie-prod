/*
 * ARCHIVO MODULO ESTACIONAMIENTO
 * AUTOR:Horus Samael Rivas Pedraza
 * EMAIL:horus.riv.ped@gmail.com
 * CEL: 56 2439 2632
 */
var type = 0;
var contador = 1;
var arrayItems = [1];

var contAppend = 1;
var contAppendRegist = 0;
var arrayItemsModal = [];

const tipo_vehiculo = { 1: "AUTOMOVIL", 2: "MOTOCICLETA", 3: "BICICLETA", 4: "AUTOMOVIL", 5: "AUTOMOVIL", 6: "AUTOMOVIL" };
const btnAgregarItem = document.getElementById("btn_agregar_item");

$(document).ready(function () {
    tbl_registo_autos = $("#tbl_todos_automoviles").dataTable({
        processing: true,
        ajax: {
            method: "post",
            url: `${urls}estacionamiento/todos_registros/1`,
            dataSrc: "",
        },
        lengthChange: true,
        ordering: true,
        responsive: true,
        autoWidth: true,
        rowId: "staffId",
        // dom: "lBfrtip",
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
                data: "num_tag",
                title: "MARBETE",
                className: "text-center",
            },
            {
                data: "payroll_number",
                title: "NOMINA",
                className: "text-center",
            },
            {
                data: "nombre",
                title: "NOMBRE",
                className: "text-center",
            },
            {
                data: "departament",
                title: "DEPARTAMENTO",
                className: "text-center",
            },
            {
                data: "ext",
                title: "CONTACTO",
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
                targets: 5,
                render: function (data, type, full, meta) {
                    ;
                    return ` <div class="pull-right mr-auto">
                        <button type="button" class="btn btn-primary btn-sm" title="Editar Vehiculos" onclick="showData(${data["id_record"]},1)">
                            <i class="fas fa-edit"></i>
                        </button>

                        <button id="#btn_dowload_${data["id_record"]}" class="btn btn-info btn-sm" onclick="DownloadQR(${data["id_record"]}, '${data["qr_location"]}' ,'${data["nombre"]}', 1)">
                            <i class="fas fa-qrcode"></i>
                        </button>

                        <button type="button" class="btn btn-danger btn-sm "  onClick=handleDeleteTags(${data["id_record"]},1)>
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div> `;
                },
            },
            /* {
              targets: [0],
              visible: false,
              searchable: false,
            }, */
        ],
        order: [[0, "DESC"]],
        createdRow: (row, data) => {
            $(row).attr("id", "info_" + data.id_record);
        },
    }).DataTable();
    $("#tbl_todos_automoviles thead").addClass("thead-dark text-center");

    tbl_registo_motos = $("#tbl_todos_motos").dataTable({
        processing: true,
        ajax: {
            method: "post",
            url: `${urls}estacionamiento/todos_registros/2`,
            dataSrc: "",
        },
        lengthChange: true,
        ordering: true,
        responsive: true,
        autoWidth: true,
        rowId: "staffId",
        // dom: "lBfrtip",
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
                data: "num_tag",
                title: "MARBETE",
                className: "text-center",
            },
            {
                data: "payroll_number",
                title: "NOMINA",
                className: "text-center",
            },
            {
                data: "nombre",
                title: "NOMBRE",
                className: "text-center",
            },
            {
                data: "departament",
                title: "DEPARTAMENTO",
                className: "text-center",
            },
            {
                data: "ext",
                title: "CONTACTO",
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
                targets: 5,
                render: function (data, type, full, meta) {
                    ;
                    return ` <div class="pull-right mr-auto">
                        <button type="button" class="btn btn-primary btn-sm" title="Editar Vehiculos" onclick="showData(${data["id_record"]},2)">
                            <i class="fas fa-edit"></i>
                        </button>
             
                        <button id="#btn_dowload_${data["id_record"]}" class="btn btn-info btn-sm" onclick="DownloadQR(${data["id_record"]}, '${data["qr_location"]}' ,'${data["nombre"]}', 1)">
                            <i class="fas fa-qrcode"></i>
                        </button>

                        <button type="button" class="btn btn-danger btn-sm "  onClick=handleDeleteTags(${data["id_record"]},2)>
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        </div> `;
                },
            },
            /* {
              targets: [0],
              visible: false,
              searchable: false,
            }, */
        ],
        order: [[0, "DESC"]],
        createdRow: (row, data) => {
            $(row).attr("id", "info_" + data.id_record);
        },
    }).DataTable();
    $("#tbl_todos_motos thead").addClass("thead-dark text-center");

    tbl_registo_bicis = $("#tbl_todos_bicis").dataTable({
        processing: true,
        ajax: {
            method: "post",
            url: `${urls}estacionamiento/todos_registros/3`,
            dataSrc: "",
        },
        lengthChange: true,
        ordering: true,
        responsive: true,
        autoWidth: true,
        rowId: "staffId",
        // dom: "lBfrtip",
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
                data: "num_tag",
                title: "MARBETE",
                className: "text-center",
            },
            {
                data: "payroll_number",
                title: "NOMINA",
                className: "text-center",
            },
            {
                data: "nombre",
                title: "NOMBRE",
                className: "text-center",
            },
            {
                data: "departament",
                title: "DEPARTAMENTO",
                className: "text-center",
            },
            {
                data: "ext",
                title: "CONTACTO",
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
                targets: 5,
                render: function (data, type, full, meta) {
                    ;
                    return ` <div class="pull-right mr-auto">
                        <button type="button" class="btn btn-primary btn-sm" title="Editar Vehiculos" onclick="showData(${data["id_record"]},3)">
                            <i class="fas fa-edit"></i>
                        </button>
             
                        <button id="#btn_dowload_${data["id_record"]}" class="btn btn-info btn-sm" onclick="DownloadQR(${data["id_record"]}, '${data["qr_location"]}' ,'${data["nombre"]}', 1)">
                            <i class="fas fa-qrcode"></i>
                        </button>

                        <button type="button" class="btn btn-danger btn-sm "  onClick=handleDeleteTags(${data["id_record"]},3)>
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        </div> `;
                },
            },
            /* {
              targets: [0],
              visible: false,
              searchable: false,
            }, */
        ],
        order: [[0, "DESC"]],
        createdRow: (row, data) => {
            $(row).attr("id", "info_" + data.id_record);
        },
    }).DataTable();
    $("#tbl_todos_bicis thead").addClass("thead-dark text-center");

    tbl_registo_n3 = $("#tbl_todos_n3").dataTable({
        processing: true,
        ajax: {
            method: "post",
            url: `${urls}estacionamiento/todos_registros/4`,
            dataSrc: "",
        },
        lengthChange: true,
        ordering: true,
        responsive: true,
        autoWidth: true,
        rowId: "staffId",
        // dom: "lBfrtip",
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
                data: "num_tag",
                title: "MARBETE",
                className: "text-center",
            },
            {
                data: "payroll_number",
                title: "NOMINA",
                className: "text-center",
            },
            {
                data: "nombre",
                title: "NOMBRE",
                className: "text-center",
            },
            {
                data: "departament",
                title: "DEPARTAMENTO",
                className: "text-center",
            },
            {
                data: "ext",
                title: "CONTACTO",
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
                targets: 5,
                render: function (data, type, full, meta) {
                    ;
                    return ` <div class="pull-right mr-auto">
                        <button type="button" class="btn btn-primary btn-sm" title="Editar Vehiculos" onclick="showData(${data["id_record"]},4)">
                            <i class="fas fa-edit"></i>
                        </button>
             
                        <button id="#btn_dowload_${data["id_record"]}" class="btn btn-info btn-sm" onclick="DownloadQR(${data["id_record"]}, '${data["qr_location"]}' ,'${data["nombre"]}', 1)">
                            <i class="fas fa-qrcode"></i>
                        </button>

                        <button type="button" class="btn btn-danger btn-sm "  onClick=handleDeleteTags(${data["id_record"]},4)>
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        </div> `;
                },
            },
            /* {
              targets: [0],
              visible: false,
              searchable: false,
            }, */
        ],
        order: [[0, "DESC"]],
        createdRow: (row, data) => {
            $(row).attr("id", "info_" + data.id_record);
        },
    }).DataTable();
    $("#tbl_todos_n3 thead").addClass("thead-dark text-center");

    tbl_registo_jardin = $("#tbl_todos_jardin").dataTable({
        processing: true,
        ajax: {
            method: "post",
            url: `${urls}estacionamiento/todos_registros/5`,
            dataSrc: "",
        },
        lengthChange: true,
        ordering: true,
        responsive: true,
        autoWidth: true,
        rowId: "staffId",
        // dom: "lBfrtip",
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
                data: "num_tag",
                title: "MARBETE",
                className: "text-center",
            },
            {
                data: "payroll_number",
                title: "NOMINA",
                className: "text-center",
            },
            {
                data: "nombre",
                title: "NOMBRE",
                className: "text-center",
            },
            {
                data: "departament",
                title: "DEPARTAMENTO",
                className: "text-center",
            },
            {
                data: "ext",
                title: "CONTACTO",
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
                targets: 5,
                render: function (data, type, full, meta) {
                    ;
                    return ` <div class="pull-right mr-auto">
                        <button type="button" class="btn btn-primary btn-sm" title="Editar Vehiculos" onclick="showData(${data["id_record"]},5)">
                            <i class="fas fa-edit"></i>
                        </button>
             
                        <button id="#btn_dowload_${data["id_record"]}" class="btn btn-info btn-sm" onclick="DownloadQR(${data["id_record"]}, '${data["qr_location"]}' ,'${data["nombre"]}', 1)">
                            <i class="fas fa-qrcode"></i>
                        </button>

                        <button type="button" class="btn btn-danger btn-sm "  onClick=handleDeleteTags(${data["id_record"]},5)>
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        </div> `;
                },
            },
            /* {
              targets: [0],
              visible: false,
              searchable: false,
            }, */
        ],
        order: [[0, "DESC"]],
        createdRow: (row, data) => {
            $(row).attr("id", "info_" + data.id_record);
        },
    }).DataTable();
    $("#tbl_todos_jardin thead").addClass("thead-dark text-center");

    tbl_registo_n1 = $("#tbl_todos_n1").dataTable({
        processing: true,
        ajax: {
            method: "post",
            url: `${urls}estacionamiento/todos_registros/6`,
            dataSrc: "",
        },
        lengthChange: true,
        ordering: true,
        responsive: true,
        autoWidth: true,
        rowId: "staffId",
        // dom: "lBfrtip",
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
                data: "num_tag",
                title: "MARBETE",
                className: "text-center",
            },
            {
                data: "payroll_number",
                title: "NOMINA",
                className: "text-center",
            },
            {
                data: "nombre",
                title: "NOMBRE",
                className: "text-center",
            },
            {
                data: "departament",
                title: "DEPARTAMENTO",
                className: "text-center",
            },
            {
                data: "ext",
                title: "CONTACTO",
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
                targets: 5,
                render: function (data, type, full, meta) {
                    ;
                    return ` <div class="pull-right mr-auto">
                        <button type="button" class="btn btn-primary btn-sm" title="Editar Vehiculos" onclick="showData(${data["id_record"]},6)">
                            <i class="fas fa-edit"></i>
                        </button>
             
                        <button id="#btn_dowload_${data["id_record"]}" class="btn btn-info btn-sm" onclick="DownloadQR(${data["id_record"]}, '${data["qr_location"]}' ,'${data["nombre"]}', 1)">
                            <i class="fas fa-qrcode"></i>
                        </button>

                        <button type="button" class="btn btn-danger btn-sm "  onClick=handleDeleteTags(${data["id_record"]},6)>
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        </div> `;
                },
            },
            /* {
              targets: [0],
              visible: false,
              searchable: false,
            }, */
        ],
        order: [[0, "DESC"]],
        createdRow: (row, data) => {
            $(row).attr("id", "info_" + data.id_record);
        },
    }).DataTable();
    $("#tbl_todos_n1 thead").addClass("thead-dark text-center");
});

$("#form_registro").submit(function (e) {
    e.preventDefault();
    const btn = document.getElementById('btn_registro');
    if ($("#id_user").val().length == 0) {
        error_nomina = "Dato Valido Requerido";
        $("#nomina").addClass('has-error');
        $("#error_nomina").text(error_nomina);
    } else {
        error_nomina = "";
        $("#nomina").removeClass('has-error');
        $("#error_nomina").text(error_nomina);
    }

    if ($("#ext").val().length == 0) {
        error_ext = "Dato Requerido";
        $("#ext").addClass('has-error');
        $("#error_ext").text(error_ext);
    } else {
        error_ext = "";
        $("#ext").removeClass('has-error');
        $("#error_ext").text(error_ext);
    }
    if ($("#tipo_vehiculo").val().length == 0) {
        error_tipo = 'Campo Requerido';
        $("#tipo_vehiculo").addClass('has-error');
        $("#error_tipo_vehiculo").text(error_tipo);
    } else {
        error_tipo = '';
        $("#tipo_vehiculo").removeClass('has-error');
        $("#error_tipo_vehiculo").text(error_tipo);
    }

    contErrors = 0;
    if ($("#tipo_marbete").val().length == 0) {
        contErrors++;
        $("#error_tipo_marbete").text('Selecciona Una Opcion');
    } else {
        $("#error_tipo_marbete").text('');
    }
 
    if ($("#tipo_marbete").val() == 2) {
        if ($("#no_marbete").val().length == 0) {
            contErrors++;
            $("#no_marbete").addClass('has-error');
            $("#error_no_marbete").text('Campo Requerido');
        } else {
            $("#no_marbete").removeClass('has-error');
            $("#error_no_marbete").text('');
        }
    }

    if ($("#tipo_vehiculo").val().length > 0) {
        if (arrayItems.length > 0) {
            arrayItems.forEach(item => {
                if ($("#tipo_vehiculo").val() == 3) {
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
            });
        }
    }

    if (error_nomina != "" || error_ext != '' || error_tipo != '' || contErrors != 0) { return false }

    let timerInterval = Swal.fire({ //se le asigna un nombre al swal
        allowOutsideClick: false,
        title: '¡Guardando Registro!',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });
    var nombre = $("#nombre").val();
    btn.disabled = true;
    const datas = new FormData($("#form_registro")[0]);
    datas.append('items', arrayItems);
    $.ajax({
        data: datas,
        url: `${urls}estacionamiento/generate_code`,
        type: "post",
        dataType: "json",
        processData: false,
        contentType: false,
        cache: false,
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
            } else if (save == "existente") {
                Swal.fire({
                    icon: "info",
                    title: "Registro Exitente",
                    text: "Este Usuario ya tiene Marbete de este tipo.",
                });
            } else if (save === false) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
            } else {
                btnAgregarItem.disabled = true;
                $(".btn-opcion").removeClass("active focus");
                $("#form_registro")[0].reset();
                $("#items_clon").empty();
                $("#div_datos_1").empty();
                $("#div_tags").hide();
                type = 0;
                contador = 1;
                arrayItems = [1];
                Swal.fire({
                    icon: "success",
                    title: "!Registro de Datos Exitoso!",
                    html: '¿Quieres Descargar el Marbete?',
                    showCloseButton: true,
                    showCancelButton: true,
                    focusConfirm: false,
                    // confirmButtonText: `<a href="${urls}medico/ver-incapacidad-medica/${$.md5(key + save)}" arget="_blank"><i class="fas fa-qrcode"></i> Descargar</a>`,
                    confirmButtonText: '<i class="fas fa-qrcode"></i> Descargar',
                    confirmButtonAriaLabel: 'Thumbs up, great!',
                    cancelButtonText: 'En otro momento',
                    cancelButtonAriaLabel: 'Thumbs down'
                }).then((result) => {
                    if (result.isConfirmed) {
                        DownloadQR(save.id, save.location, nombre, 0);
                    }
                })
                tbl_registo_autos.ajax.reload(null, false);
                tbl_registo_motos.ajax.reload(null, false);
                tbl_registo_bicis.ajax.reload(null, false);
                tbl_registo_n3.ajax.reload(null, false);
                tbl_registo_jardin.ajax.reload(null, false);
                tbl_registo_n1.ajax.reload(null, false);
                nombre = "";
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
})

$("#form_editar_vehiculos").submit(function (e) {
    e.preventDefault();
    const btn = document.getElementById('btn_editar_vehiculos');
    var arrayItemsModalAjax = [];
    contErrors = 0;
    console.log(arrayItemsModal);
    if (arrayItemsModal.length > contAppendRegist) {
        arrayItemsModal.forEach(item => {
            if (item > contAppendRegist) {
                arrayItemsModalAjax.push(item);
                if ($("#tipo_tbl").val() == 3) {
                    if ($.trim($("#modelo_modal_" + item).val()).length == 0) {
                        contErrors = contErrors + 1;
                        $("#modelo_modal_" + item).addClass('has-error');
                        $("#error_modelo_modal_" + item).text('Campo Requerido');
                    } else {
                        $("#modelo_modal_" + item).removeClass('has-error');
                        $("#error_modelo_modal_" + item).text('');
                    }

                    if ($.trim($("#color_modal_" + item).val()).length == 0) {
                        contErrors = contErrors + 1;
                        $("#color_modal_" + item).addClass('has-error');
                        $("#error_color_modal_" + item).text('Campo Requerido');
                    } else {
                        $("#color_modal_" + item).removeClass('has-error');
                        $("#error_color_modal_" + item).text('');
                    }
                } else {
                    console.log('tipo otros opciones');
                    if ($.trim($("#modelo_modal_" + item).val()).length == 0) {
                        contErrors = contErrors + 1;
                        $("#modelo_modal_" + item).addClass('has-error');
                        $("#error_modelo_modal_" + item).text('Campo Requerido');
                    } else {
                        $("#modelo_modal_" + item).removeClass('has-error');
                        $("#error_modelo_modal_" + item).text('');
                    }

                    if ($.trim($("#color_modal_" + item).val()).length == 0) {
                        contErrors = contErrors + 1;
                        $("#color_modal_" + item).addClass('has-error');
                        $("#error_color_modal_" + item).text('Campo Requerido');
                    } else {
                        $("#color_modal_" + item).removeClass('has-error');
                        $("#error_color_modal_" + item).text('');
                    }

                    if ($.trim($("#placas_modal_" + item).val()).length == 0) {
                        contErrors = contErrors + 1;
                        $("#placas_modal_" + item).addClass('has-error');
                        $("#error_placas_modal_" + item).text('Campo Requerido');
                    } else {
                        $("#placas_modal_" + item).removeClass('has-error');
                        $("#error_placas_modal_" + item).text('');
                    }

                    if ($.trim($("#vencimiento_modal_" + item).val()).length == 0) {
                        contErrors = contErrors + 1;
                        $("#vencimiento_modal_" + item).addClass('has-error');
                        $("#error_vencimiento_modal_" + item).text('Campo Requerido');
                    } else {
                        $("#vencimiento_modal_" + item).removeClass('has-error');
                        $("#error_vencimiento_modal_" + item).text('');
                    }

                    if ($("#archivo_modal_" + item).val().length == 0) {
                        contErrors = contErrors + 1;
                        $("#lbl_archivo_modal_" + item).addClass('has-error');
                        $("#error_archivo_modal_" + item).text('Campo Requerido');
                    } else if ($("#archivo_modal_" + item)[0].files[0].type !== 'application/pdf') {
                        contErrors = contErrors + 1;
                        $("#lbl_archivo_modal_" + item).addClass('has-error');
                        $("#error_archivo_modal_" + item).text('Archivo no Valido');
                    } else {
                        $("#lbl_archivo_modal_" + item).removeClass('has-error');
                        $("#error_archivo_modal_" + item).text('');
                    }
                }
            }
        });
    } else {
        contErrors = 10;
    }
    console.log('errores: ', contErrors);
    if (contErrors != 0) { return false; }
    let timerInterval = Swal.fire({ //se le asigna un nombre al swal
        allowOutsideClick: false,
        title: '¡Guardando Vehiculos!',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });
    var nombre = $("#nombre").val();
    btn.disabled = true;
    const datas = new FormData($("#form_editar_vehiculos")[0]);
    datas.append('items_modal', arrayItemsModalAjax);
    $.ajax({
        data: datas,
        url: `${urls}estacionamiento/generar_nuevo_vehiculo`,
        type: "post",
        dataType: "json",
        processData: false,
        contentType: false,
        cache: false,
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
            } else if (save != false) {
                btnAgregarItem.disabled = true;
                $("#form_editar_vehiculos")[0].reset();
                $("#items_clon_modal").empty();
                Swal.fire({
                    icon: "success",
                    title: "Exito!",
                    text: "Se generó el nuevo vehículo exitosamente.",
                });
                $("#ver_modal").modal("hide");
                arrayItemsModal = [];
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
            alert('Uncaught Error: ' + jqXHR.saveText);
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: `Uncaught Error: ${jqXHR.responseText}`,
            });
        }
    });
})

$("#nomina").on('change', function () {
    $("#nomina").removeClass('has-error');
    $("#error_nomina").text('');
    $("#id_user").val("");
    $("#id_depto").val("");
    $("#nombre").val("");
    $("#depto").val("");
    if ($("#nomina").val().length > 0) {
        var nomina = new FormData();
        nomina.append('nomina', $("#nomina").val());
        $.ajax({
            url: `${urls}estacionamiento/datos_usuario`,
            data: nomina,
            type: "post",
            processData: false,
            contentType: false,
            cache: false,
            dataType: "json",
            success: function (data) {
                if (data != null && data != false) {
                    $("#id_user").val(data.id_user);
                    $("#id_depto").val(data.id_departament);
                    $("#nombre").val(data.nombre);
                    $("#depto").val(data.departamento);
                } else {
                    $("#nomina").addClass('has-error');
                    $("#error_nomina").text('Nomina no encontrada');
                }
            }
        });
    }
});

document.getElementById("tipo_vehiculo").addEventListener("change", function () {
    btnAgregarItem.disabled = true;
    $("#tipo_vehiculo").removeClass('has-error');
    $("#error_tipo_vehiculo").text('');
    arrayItems = [1];
    $("#items_clon").empty();
    $("#div_datos_1").empty();
    $("#div_tags").hide();
    $("#div_no_marbete").hide();
    $(".btn-opcion").removeClass("active focus");
    $("#tipo_marbete").val('');

    if (this.value.length > 0) {
        btnAgregarItem.disabled = false;
        datosShow(1);
        $("#div_tags").show();
    }
});

function datosShow(item) {
    const tipoVehiculo = document.getElementById("tipo_vehiculo");
    if (tipoVehiculo.value == 3) {
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
    } else {
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

function datosShowModal(item) {
    const tipoVehiculo = document.getElementById("tipo_tbl");
    if (tipoVehiculo.value == 3) {
        $("#div_datos_modal_" + item).append(`<div class="col-md-3">
        <label>Marca:</label>
        <input type="text" class="form-control" name="modelo_modal_[]" id="modelo_modal_${item}" onchange="validarItem(this)">
        <div id="error_modelo_modal_${item}" class="text-danger"></div>
    </div>
    <div class="col-md-3">
        <label>Color:</label>
        <input type="text" class="form-control" name="color_modal_[]" id="color_modal_${item}" onchange="validarItem(this)">
        <div id="error_color_modal_${item}" class="text-danger"></div>
    </div>
    <input type="hidden" name="placas_modal_[]">
    <input type="hidden" name="vencimiento_modal_[]">
    <input type="hidden" name="archivo_modal_${item}">`);
    } else {
        $("#div_datos_modal_" + item).append(`<div class="col-md-2">
            <label>Modelo:</label>
            <input type="text" class="form-control" name="modelo_modal_[]" id="modelo_modal_${item}" onchange="validarItem(this)">
            <div id="error_modelo_modal_${item}" class="text-danger"></div>
        </div>
        <div class="col-md-2">
            <label>Color:</label>
            <input type="text" class="form-control" name="color_modal_[]" id="color_modal_${item}" onchange="validarItem(this)">
            <div id="error_color_modal_${item}" class="text-danger"></div>
        </div>
        <div class="col-md-2">
            <label>Placas:</label>
            <input type="text" class="form-control" name="placas_modal_[]" id="placas_modal_${item}" onchange="validarItem(this)">
            <div id="error_placas_modal_${item}" class="text-danger"></div>
        </div>
        <div class="col-md-3">
            <label>Vencimiento:</label>
            <input type="date" class="form-control" name="vencimiento_modal_[]" id="vencimiento_modal_${item}" onchange="validarItem(this)">
            <div id="error_vencimiento_modal_${item}" class="text-danger"></div>
        </div>
        <div class="col-md-3">
            <label>Póliza:</label>
            <div class="custom-file">
                <input type="file" class="custom-file-input" accept=".pdf" name="archivo_modal_${item}" id="archivo_modal_${item}" onchange="validarFile(this)">
                <label class="custom-file-label" id="lbl_archivo_modal_${item}" for="archivo_modal_${item}">Selecionar</label>
            </div>
            <div id="error_archivo_modal_${item}" class="text-danger"></div>
        </div>`);
    }
}

$("#btn_agregar_item").on('click', function () {
    if (arrayItems.length < 5) {
        contador++;
        arrayItems.forEach(item => {
            if (item === contador) {
                contador++;
            }
        });
        arrayItems.push(contador);
        $("#items_clon").append(`<div class="row" id="items_clon_${contador}">
        <div class="col-md-11 row" id="div_datos_${contador}"></div>
        <div class="form-group col-md-1" style="text-align:center;">
            <button type="button" class="btn btn-danger btn-retirar-item" style="margin-top:25px;" onclick="retirarItem(${contador}) ">
                <i class="fas fa-times"></i>
            </button>
        </div>
        </div>`);
        if (arrayItems.length % 2 === 0) {
            document.getElementById("items_clon_" + contador).style.backgroundColor = "#F3F3F3";
        }
        datosShow(contador)
    } else {
        $("#error_item").html(
            `<div class="alert alert-warning alert-dismissible" role="alert">
                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
                   </button>
                   <strong>El Sistema solo permite 5 vehículos por usuario...</strong>
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
    $("#items_clon_" + item).remove();
    contador = 1;
}

function retirarItemModal(item) {
    var i = arrayItemsModal.indexOf(item);
    arrayItemsModal.splice(i, 1);
    $("#items_clon_Modal_" + item).remove();
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

function DownloadQR(id, urlUbiv, nombre, tipo) {
    let timerInterval = Swal.fire({ //se le asigna un nombre al swal
        allowOutsideClick: false,
        title: '¡Descargando!',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });
    if (tipo == 1) { $("#btn_dowload_" + id).prop("disabled", true); }
    const downloadOneDocument = document.createElement('a');
    downloadOneDocument.href = `${urls}${urlUbiv}`;
    downloadOneDocument.download = `Marbete_#${id}_${nombre}.png`;
    // downloadOneDocument.target = "";
    var clicEvent = new MouseEvent("click", {
        view: window,
        bubbles: true,
        cancelable: true,
    });
    // downloadOneDocument.dispatchEvent(clicEvent);
    downloadOneDocument.click();
    if (tipo == 1) { $("#btn_dowload_" + id).prop("disabled", false); }
    Swal.close(timerInterval);
}

function showData(id, tbl) {
    arrayItemsModal = [];
    $("#items_clon_modal").empty();
    $("#items_existentes").empty();
    const data = new FormData();
    data.append('id_record', id);
    data.append('tipo', tbl);
    $.ajax({
        data: data,
        url: `${urls}estacionamiento/datos_registros`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (data) {
            if (data != false && data != null) {
                contAppend = 1;
                contAppendRegist = 0
                $("#id_record").val(id);
                $("#id_user_modal").val(data.personal.id_user)
                $("#nomina_modal").val(data.personal.payroll_number);
                $("#nombre_modal").val(data.personal.nombre);
                $("#depto_modal").val(data.personal.departament);
                $("#ext_modal").val(data.personal.ext);
                $("#tipo_vehiculo_modal").val(tipo_vehiculo[tbl]);
                $("#tipo_tbl").val(tbl);
                console.log(data.vehicule);
                console.log(data.vehicule != null);
                console.log(data.vehicule.length);
                if (data.vehicule.length != 0) {
                    data.vehicule.forEach(datos => {
                        arrayItemsModal.push(contAppend);
                        if (datos.type_vehicle == 3) {
                            $("#items_existentes").append(`<div class="row" id="row_${contAppend}" style="padding-top:7px;">
                                <div class="col-md-1 text-center" style="padding-top: 2rem;">
                                    <button type="button" class="btn btn-danger" onClick="deleteItem(${datos.id_item}, '${datos.model}', ${id}, ${tbl})">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <label>Modelo:</label>
                                    <input type="text" class="form-control" value="${datos.model}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label>Color:</label>
                                    <input type="text" class="form-control" value="${datos.color}" readonly>
                                </div>
                            </div>`);
                        } else {
                            $("#items_existentes").append(`<div class="row" id="row_${contAppend}" style="padding-top:7px;">
                            <div class="col-md-1 text-center" style="padding-top: 2rem;">
                                <button type="button" class="btn btn-danger" onClick="deleteItem(${datos.id_item}, '${datos.model}', ${id}, ${tbl})">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                            <div class="col-md-2">
                                <label>Modelo:</label>
                                <input type="text" class="form-control" value="${datos.model}" readonly>
                            </div>
                            <div class="col-md-2">
                                <label>Color:</label>
                                <input type="text" class="form-control" value="${datos.color}" readonly>
                            </div>
                            <div class="col-md-2">
                                <label>Placas:</label>
                                <input type="text" class="form-control" value="${datos.placas}" readonly>
                            </div>
                            <div class="col-md-2">
                                <label>Vencimiento de Poliza:</label>
                                <input type="date" class="form-control" value="${datos.date_expiration}" readonly>
                            </div>
                            <div class="form-group col-md-3" style="text-align:center;">
                                <button type="button" class="btn btn-info" title="Descargar Poliza" style="margin-top:31px;" onclick="verPoliza('${datos.location_archive}') ">
                                    <i class="fas fa-file-download" style = "margin-right:10px;"></i>Descargar Poliza
                                </button>
                            </div>
                        </div>`);
                        }
                        if (contAppend % 2 === 0) {
                            document.getElementById("row_" + contAppend).style.backgroundColor = "#F3F3F3";
                        }
                        contAppend++;
                        contAppendRegist++;
                    });
                } else {
                    $("#items_existentes").append(`<div class="row" id="row_0" style="background-color: rgb(243, 243, 243);">
                        <div class="col-md-12 text-center" style="padding-top: 1rem;">
                            <h3 style="color:#CDCDCD;">Sin Vehículos Registrados</h3>
                        </div>
                    </div>`);
                }
                $("#ver_modal").modal("show");
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
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
        } else if (textStatus === "parsererror") {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Error de análisis JSON solicitado.",
            });
        } else if (textStatus === "timeout") {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Time out error.",
            });
        } else if (textStatus === "abort") {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ajax request aborted.",
            });
        } else {
            alert("Uncaught Error: " + jqXHR.responseText);
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: `Uncaught Error: ${jqXHR.responseText}`,
            });
        }
    });
}

function verPoliza(url) {
    window.open(url);
}

$("#btn_agregar_item_modal").on('click', function () {
    console.log('longitus del array modal', arrayItemsModal.length);
    if (arrayItemsModal.length < 5) {
        contAppend++;
        arrayItemsModal.forEach(item => {
            if (item === contAppend) {
                contAppend++;
            }
        });
        arrayItemsModal.push(contAppend);
        $("#items_clon_modal").append(`<div class="row" id="items_clon_Modal_${contAppend}">
        <div class="col-md-11 row" id="div_datos_modal_${contAppend}"></div>
        <div class="form-group col-md-1" style="text-align:center;">
            <button type="button" class="btn btn-danger btn-retirar-item" style="margin-top:25px;" onclick="retirarItemModal(${contAppend}) ">
                <i class="fas fa-times"></i>
            </button>
        </div>
        </div>`);
        if (arrayItemsModal.length % 2 === 0) {
            document.getElementById("items_clon_Modal_" + contAppend).style.backgroundColor = "#F3F3F3";
        }
        datosShowModal(contAppend);
    } else {
        $("#error_item_modal").html(
            `<div class="alert alert-warning alert-dismissible" role="alert">
                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
                   </button>
                   <strong>El Sistema solo permite 5 vehículos por usuario...</strong>
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

function handleDeleteTags(id_folio, tipo) {
    Swal.fire({
        title: `Deseas Eliminar el Marbete <b>${id_folio}</b> de ${tipo_vehiculo[tipo]} ?`,
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
                url: `${urls}estacionamiento/eliminar_marberte/${tipo}`,
                type: "post",
                processData: false,
                contentType: false,
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    if (response) {
                        Swal.fire({
                            icon: "success",
                            title: "¡Exito!",
                            text: "Marbete eliminado con exito",
                        });
                        tbl_registo_autos.ajax.reload(null, false);
                        tbl_registo_motos.ajax.reload(null, false);
                        tbl_registo_bicis.ajax.reload(null, false);
                        tbl_registo_n3.ajax.reload(null, false);
                        tbl_registo_jardin.ajax.reload(null, false);
                        tbl_registo_n1.ajax.reload(null, false);
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

function deleteItem(id_item, modelo, id_, tbl_) {
    Swal.fire({
        title: `Deseas Eliminar el vehiculo <b>${modelo}</b>?`,
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
            dataForm.append("id_item", id_item);
            $.ajax({
                data: dataForm,
                url: `${urls}estacionamiento/eliminar_vehiculo`,
                type: "post",
                processData: false,
                contentType: false,
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    if (response) {
                        Swal.fire({
                            icon: "success",
                            title: "¡Exito!",
                            text: "Vehoculo eliminado con exito",
                        });
                        $("#ver_modal").modal("hide");
                        showData(id_, tbl_);
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

function tipoMarbete(tipo) {
    $("#tipo_marbete").text('');
    $("#tipo_marbete").val(tipo);
    $("#no_marbete").val();
    if (tipo == 1) {
        $("#div_no_marbete").hide();
    }
    if (tipo == 2) {
        $("#div_no_marbete").show();
        $("#no_marbete").empty();
        const tbl = new FormData();
        tbl.append("tbl", $("#tipo_vehiculo").val());
        $.ajax({
            data: tbl,
            url: `${urls}estacionamiento/marbetes_disponibles`,
            type: "post",
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (response) {
                console.log(response);
                console.log(response != false);
                console.log(response != null);
                if (response != false) {
                    response.forEach(key => {
                        $("#no_marbete").append(`<option value="">Opciones...</option>`);
                        $("#no_marbete").append(`<option value="${key.num_tag}">${key.num_tag}</option>`);
                    });
                } else {
                    $("#no_marbete").append(`<option value="">Sin Datos</option>`);
                    Swal.fire({
                        icon: "info",
                        text: "No hay Marbetes Disponibles",
                    });
                    // document.getElementById("opc_1").checked = true;
                    // document.getElementById("opc_2").checked = false;
                }
            },
        })
    }
}