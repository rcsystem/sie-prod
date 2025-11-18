/**
 * ARCHIVO MODULO VIAJES
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EDITOR:HORUS RIVAS PEDRAZA
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */

$(document).ready(function () {
    pintarDatosRequest();
    if ($("#type").val() == 2) {
        $(".my-col-it").attr('class', 'col-md-3');
        $("#div_monto_grado").hide();
    }
    /* const datos = new FormData();
    datos.append('id_request', $("#folio").val());
    datos.append('type', $("#type").val()); */
    tbl_amount_state = $("#tabla_estado_cuenta").dataTable({
        processing: true,
        ajax: {
            data: { 'id_request': $("#folio").val(), 'type': $("#type").val() },
            method: "post",
            url: `${urls}viajes/lista_estado_cuenta`,
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
                data: "id_item",
                title: "FOLIO",
                className: "text-center",
            },
            {
                data: "rule",
                title: "REGLA",
                className: "text-center",
            },
            {
                data: "lugar",
                title: "LUGAR | MOTIVO",
                className: "text-center",
            },
            {
                data: "fecha",
                title: "FECHA",
                className: "text-center",
            },
            {
                data: null,
                render: function (data, type, full, meta) {
                    return `${data["amount"]} ${data["divisa"]}`;
                },
                title: "MONTO",
                className: "text-center",
            },
            {
                data: null,
                render: function (data, type, full, meta) {
                    return `<h5><span class="badge badge-pill badge-${data["comprobacion_color"]}">${data["comprobacion_txt"]}</span></h5>`;
                },
                title: "COMPROBACION",
                className: "text-center",
            },
            {
                data: null,
                render: function (data, type, full, meta) {
                    return `<h5><span class="badge badge-pill badge-${data["estado_color"]}">${data["estado_txt"]}</span></h5>`;
                },
                title: "ESTATUS",
                className: "text-center",
            },
            /* {
                data: null,
                render: function (data, type, full, meta) {
                    return `<h5><span class="badge badge-pill badge-${data["conta_color"]}">${data["conta_txt"]}</span></h5>`;
                },
                title: "CONTABILIDAD",
                className: "text-center",
            }, */
            {
                data: null,
                title: "Acciones",
                className: "text-center",
            },
        ],
        destroy: "true",
        columnDefs: [
            {
                targets: 7,
                render: function (data, type, full, meta) {
                    const deletVerification = (data["transaction_status"] == 2) ? `<button type="button" class="btn btn-outline-guardar btn-sm" tittle="Rechazar Comprobacion" onclick="removeVerification('${data["id_item"]}')">
                        <i class="fas fa-user-slash"></i>
                    </button>` : '';
                    const pdfArchive = (data["transaction_status"] == 2) ? `<button type="button" class="btn btn-outline-info btn-sm" tittle="Descargar Archivo PDF" onclick="dowloadPdf('${data["pdf_travel_routes"]}')">
                        <i class="far fa-file-pdf"></i>
                    </button>` : '';
                    const btnChekRule = (data["rule"] == "EF") ? `<button type="button" class="btn btn-outline-warning btn-sm" tittle="Proceso por falta de Comprobacion" onclick="fataComprobacion(${data["id_item"]},${$("#type").val()},${data["amount"]}, '${data["divisa"]}')">
                        <i class="far fa-file-archive"></i>
                    </button>` : '';
                    return ` <div class="pull-right mr-auto">
                        ${deletVerification}
                        ${btnChekRule}
                        ${pdfArchive}
                        <button type="button" class="btn btn-outline-danger btn-sm"  onClick=handleDeletePermissions(${data["id_item"]})>
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
            $(row).attr("id", "item_" + data.id_item);
        },
    }).DataTable();
    $("#tabla_estado_cuenta thead").addClass("thead-dark text-center");
});

$("#form_estado_cuenta").submit(function (e) {
    e.preventDefault();
    var error = 0;
    const btn = document.getElementById('btn_estado_cuenta');
    const archivo = document.getElementById('archivo');
    if (archivo.value.length == 0) {
        error++;
        $("#lbl_" + archivo.id).addClass('has-error');
        $("#error_" + archivo.id).text('Archivo requerido');
    } else if (archivo.value.split(".").pop() != "xlsx") {
        error++;
        $("#lbl_" + archivo.id).addClass('has-error');
        $("#error_" + archivo.id).text('Archivo .xlsx necesario');
    } else {
        $("#lbl_" + archivo.id).removeClass('has-error');
        $("#error_" + archivo.id).text('');
    }

    if (error != 0) {
        return false
    }
    const timerInterval = Swal.fire({
        iconHtml: '<i class="fas fa-file-upload"></i>',
        title: 'Subiendo Datos!',
        html: 'Espere unos Segundos.',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });
    btn.disabled = true;
    const data = new FormData($("#form_estado_cuenta")[0]);
    data.append('id_request', $("#folio").val());
    data.append('type', $("#type").val());
    $.ajax({
        data: data,
        url: `${urls}viajes/subir_estado_cuenta`,
        type: "POST",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (save) {
            btn.disabled = false;
            Swal.close(timerInterval);
            if (save.hasOwnProperty('xdebug_message')) {
                Swal.fire({
                    icon: "error",
                    title: "Oops, Exception...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
                console.log('Mensaje de xdebug:', response.xdebug_message);
            } else if (save == true) {
                $("#lbl_archivo").empty();
                $("#lbl_archivo").append('Seleccionar Excel');
                document.getElementById('form_estado_cuenta').reset();
                pintarDatosRequest()
                Swal.fire(`!Sea Registrado los estados de cuenta!`, "¡Exito!", "success");
                tbl_amount_state.ajax.reload(null, false);
            } else if (!isNaN(save)) {
                $("#lbl_archivo").empty();
                $("#lbl_archivo").append('Seleccionar Excel');
                document.getElementById('form_estado_cuenta').reset();
                pintarDatosRequest()
                Swal.fire({
                    icon: "info",
                    title: `Datos interrumpidos en Fila ${save}`,
                    text: `Se guardaron los datos hasta la fila ${save - 1}. Revisa y Edita el Archivo`,
                });
                tbl_amount_state.ajax.reload(null, false);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Ocurrio un error en el servidor! Contactar con el Administrador",
                });
            }
        }, error: function () {
            btn.disabled = false;
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ocurrio un error en el servidor! Contactar con el Administrador",
            });
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
    })
})

function handleDeletePermissions(id_item) {
    Swal.fire({
        title: `Deseas Eliminar este Estado de cuenta?`,
        text: `Una vez Eliminado no podras Recuperarlo!`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        // cancelButtonColor: "",
        confirmButtonText: "Eliminar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.value) {
            let dataForm = new FormData();
            dataForm.append("id_item", id_item);
            $.ajax({
                data: dataForm,
                url: `${urls}viajes/eliminar_estados_de_cuenta`,
                type: "post",
                processData: false,
                contentType: false,
                dataType: "json",
                success: function (response) {
                    if (response) {
                        pintarDatosRequest();
                        tbl_amount_state.ajax.reload(null, false);
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
    });
}

function removeVerification(id_item) {
    Swal.fire({
        title: `¿Deseas Eliminar la comprobacion de este Estado de cuenta?`,
        text: `¡Una vez Eliminado no podras Recuperarlo!`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#1F2D3D",
        // cancelButtonColor: "",
        confirmButtonText: "Eliminar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.value) {
            let dataForm = new FormData();
            dataForm.append("id_item", id_item);
            $.ajax({
                data: dataForm,
                url: `${urls}viajes/eliminar_comprobacion_estados_de_cuenta`,
                type: "post",
                processData: false,
                contentType: false,
                dataType: "json",
                success: function (response) {
                    if (response == true) {
                        pintarDatosRequest();
                        tbl_amount_state.ajax.reload(null, false);
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
    });
}

$("#form_estado_cuenta_individual").submit(function (e) {
    e.preventDefault();
    var error = 0;
    const error_txt = 'Campo requerido';
    const btn = document.getElementById('btn_estado_cuenta_individual');
    const lugar = document.getElementById('lugar');
    const fecha = document.getElementById('fecha');
    const monoto_original = document.getElementById('monoto_original');
    const monto_mxn = document.getElementById('monto_mxn');

    if (lugar.value.length == 0) {
        error++;
        lugar.classList.add('has-error');
        $("#error_" + lugar.id).text(error_txt);
    } else {
        lugar.classList.remove('has-error');
        $("#error_" + lugar.id).text('');
    }

    if (fecha.value.length == 0) {
        error++;
        fecha.classList.add('has-error');
        $("#error_" + fecha.id).text(error_txt);
    } else {
        fecha.classList.remove('has-error');
        $("#error_" + fecha.id).text('');
    }

    if (monoto_original.value.length == 0) {
        error++;
        monoto_original.classList.add('has-error');
        $("#error_" + monoto_original.id).text(error_txt);
    } else {
        monoto_original.classList.remove('has-error');
        $("#error_" + monoto_original.id).text('');
    }

    if (monto_mxn.value.length == 0) {
        error++;
        monto_mxn.classList.add('has-error');
        $("#error_" + monto_mxn.id).text(error_txt);
    } else {
        monto_mxn.classList.remove('has-error');
        $("#error_" + monto_mxn.id).text('');
    }

    if (error != 0) {
        return false
    }
    const timerInterval = Swal.fire({
        iconHtml: '<i class="fas fa-file-upload"></i>',
        title: 'Subiendo Datos!',
        html: 'Espere unos Segundos.',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });
    btn.disabled = true;
    const data = new FormData($("#form_estado_cuenta_individual")[0]);
    data.append('id_request', $("#folio").val());
    data.append('type', $("#type").val());
    console.log(data);
    $.ajax({
        data: data,
        url: `${urls}viajes/subir_estado_cuenta_individual`,
        type: "POST",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (save) {
            btn.disabled = false;
            Swal.close(timerInterval);
            if (save.hasOwnProperty('xdebug_message')) {
                Swal.fire({
                    icon: "error",
                    title: "Oops, Exception...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
                console.log('Mensaje de xdebug:', response.xdebug_message);
            } else if (save == true) {
                $("#lbl_archivo").empty();
                $("#lbl_archivo").append('Seleccionar Excel');
                document.getElementById('form_estado_cuenta_individual').reset();
                pintarDatosRequest()
                Swal.fire(`¡Registro Exitoso!`, "¡Exito!", "success");
                tbl_amount_state.ajax.reload(null, false);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Ocurrio un error en el servidor! Contactar con el Administrador",
                });
            }
        },
        error: function () {
            btn.disabled = false;
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ocurrio un error en el servidor! Contactar con el Administrador",
            });
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
    })
})

function pintarDatosRequest() {
    let dataForm = new FormData();
    dataForm.append("folio", $("#folio").val());
    dataForm.append("type", $("#type").val());
    $("#h_folio").text();
    $("#h2_solicitado").text();
    $("#h2_estado_cuenta").text();
    $("#h2_comprobado").text();
    $("#h2_descuento").text();
    $("#h2_grado").text();
    $("#icon_grade").text();
    $.ajax({
        data: dataForm,
        url: `${urls}viajes/datos_solicitud_cartas_cabeza`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (resp) {
            if (resp) {
                $("#h_folio").text(`Folio: ${resp.folio}`);
                $("#h2_solicitado").text(resp.solicitado);
                $("#h2_estado_cuenta").text(resp.cuenta);
                $("#h2_comprobado").text(resp.comprobado);
                $("#h2_descuento").text(resp.descuento);
                $("#h2_grado").text(resp.monto_diario);
                $("#icon_grade").text(resp.icon_grado);
                $("#h3_usuario").text(resp.user_name);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
                console.log("Mal Revisa");
            }
        },
    });
}

function fataComprobacion(id_item, type, amount, divisa) {
    console.log('\nitem: ', id_item, '\ntype: ', type, '\ncantidad: ', amount, '\ndivisa: ', divisa);
    $("#faltaComprobacionModal").modal("show");
}

function validarFile(campo) {
    const input = campo;
    if (input.value.length > 0) {
        $("#lbl_" + input.id).empty();
        $("#lbl_" + input.id).append(`${document.getElementById(input.id).files[0].name}`);
        $("#lbl_" + input.id).attr('style', 'color:#343a40!important;');
        $("#lbl_" + input.id).removeClass('has-error');
        $("#error_" + input.id).text('');
    }
}

function validarInput(campo) {
    const input = campo;
    if (input.value.length > 0) {
        input.classList.remove('has-error');
        $("#error_" + input.id).text('');
    }
}

function dowloadPdf(ubicacion) {

    console.log(`${urls}${ubicacion}`);
    const btn = document.getElementById('btn_dowload_format');
    btn.disabled = true;
    const cargando = Swal.fire({ //se le asigna un nombre al swal
        allowOutsideClick: false,
        title: `DESCARGANDO <i class="fas fa-qrcode"></i>`,
        html: 'Espere unos Segundos.',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });
    const downloadOneDocument = document.createElement('a');
    downloadOneDocument.href = `${urls}${ubicacion}`;
    downloadOneDocument.download = 'FormatoSubir_EstadoCuenta';
    // downloadOneDocument.target = "";
    /*  var clicEvent = new MouseEvent("click", {
         view: window,
         bubbles: true,
         cancelable: true,
     }); */
    // downloadOneDocument.dispatchEvent(clicEvent);
    downloadOneDocument.click();
    btn.disabled = false;
    Swal.close(cargando);
}


function download() {
    const btn = document.getElementById('btn_dowload_format');
    btn.disabled = true;
    const cargando = Swal.fire({ //se le asigna un nombre al swal
        allowOutsideClick: false,
        title: `DESCARGANDO <i class="fas fa-qrcode"></i>`,
        html: 'Espere unos Segundos.',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });
    const downloadOneDocument = document.createElement('a');
    downloadOneDocument.href = `${urls}/public/doc/politicas/FormatoEstadoCuenta.xlsx`;
    downloadOneDocument.download = 'FormatoSubir_EstadoCuenta';
    // downloadOneDocument.target = "";
    /*  var clicEvent = new MouseEvent("click", {
         view: window,
         bubbles: true,
         cancelable: true,
     }); */
    // downloadOneDocument.dispatchEvent(clicEvent);
    downloadOneDocument.click();
    btn.disabled = false;
    Swal.close(cargando);
}