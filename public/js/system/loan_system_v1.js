/**
 * ARCHIVO MODULO VIAJES
 * AUTOR:HORUS RIVAS PEDRAZA
 * EMAIL:horus.riv.ped@gmail.com
 * CEL:56 2439 2632
 */
var arrayItem = [];
var contItem = 0;

$(document).ready(() => {
    $("#usuario").select2();
    tbl_prestamos = $("#tabla_prestamos").dataTable({
        processing: true,
        ajax: {
            method: "post",
            url: `${urls}sistemas/todos_prestamos`,
            dataSrc: "",
        },
        lengthChange: true,
        ordering: true,
        responsive: true,
        autoWidth: false,
        rowId: "staffId",
        dom: "lfrtip",
        buttons: [

        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
        },
        columns: [
            {
                data: "folio",
                title: "FOLIO",
                className: "text-center"
            },
            {
                data: null,
                render: function (data, type, full, meta) {
                    return `<span class="badge badge-${[data["color"]]}">${data["estado"]}</span>`;
                },
                title: "ESTADO",
                className: "text-center"
            },
            {
                data: "user_name",
                title: "USUARIO",
                className: "text-center"
            },
            {
                data: "nomina",
                title: "NOMINA",
                className: "text-center"
            },
            {
                data: "equip",
                title: "EQUIPO",
                className: "text-center"
            },
            {
                data: "incio",
                title: "FECHA PRESTAMO",
                className: "text-center"
            },
            {
                data: null,
                render: function (data, type, full, meta) {
                    const color = (data["fin"] == '') ? 'outline-primary' : 'secondary';
                    const change = (data["fin"] == '') ? `onclick="confirmReturn(${data["folio"]},'${data["equip"]}')"` : '';
                    if (data["fin"] == '') {
                        return ` <div class="mr-auto">
                            <button type="button" class="btn btn-${color} btn-sm" ${change}>
                                Confirmar Devolución
                            </button> 
                        </div> `;
                    } else {
                        return data["fin"];
                    }
                },
                title: "FECHA DEVOLUCIÓN",
                className: "text-center"
            },
            {
                data: null,
                render: function (data, type, full, meta) {
                    const color = (data["fin"] == '') ? 'outline-primary' : 'secondary';
                    const change = (data["fin"] == '') ? `onclick="handleDeleteTravel(${data["folio"]})"` : '';
                    return ` <div class="mr-auto">
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="handleDeleteLoan(${data["folio"]})">
                            <i class="fas fa-trash-alt"></i>
                        </button> 
                        <a href="${urls}sistemas/pdf-responsiva-prestamo/${$.md5(key + data['folio'])}" target="_blank" title="Responsiba equipo" class="btn btn-outline-info btn-sm">
                              <i class="fas fa-file-pdf"></i>
                          </a>
                  </div> `;
                },
                title: "ACCIONES",
                className: "text-center"
            },
        ],
        destroy: "true",
        //  columnDefs: [
        //    {
        //      targets: 6, 
        //    },     
        //  ],

        order: [[0, "DESC"]],

        createdRow: (row, data) => {
            $(row).attr("id", "travel_" + data.folio);
        },
    }).DataTable();
    $('#tabla_prestamos thead').addClass('thead-dark text-center');

});

$("#form_prestamos").submit(function (e) {
    e.preventDefault();
    var errores = 0;
    var mensaje = "Campo Requerido";
    const btn = document.getElementById('btn_prestamos');

    const nomina = document.getElementById('nomina');
    const usuario = document.getElementById('usuario');

    if (nomina.value.length == 0) {
        errores++;
        nomina.classList.add('has-error');
        document.getElementById("error_" + nomina.id).textContent = mensaje;
    } else {
        nomina.classList.remove('has-error');
        document.getElementById("error_" + nomina.id).textContent = '';
    }
    if (usuario.value.length == 0) {
        errores++;
        usuario.classList.add('has-error');
        document.getElementById("error_" + usuario.id).textContent = mensaje;
    } else {
        usuario.classList.remove('has-error');
        document.getElementById("error_" + usuario.id).textContent = '';
    }

    if (arrayItem.length == 0) {
        errores++;
    } else {
        arrayItem.forEach(item => {
            const cantidad_equipo = document.getElementById('cantidad_equipo_' + item);
            if (cantidad_equipo.value.length == 0) {
                errores++;
                cantidad_equipo.classList.add('has-error');
                document.getElementById("error_" + cantidad_equipo.id).textContent = mensaje;
            } else {
                cantidad_equipo.classList.remove('has-error');
                document.getElementById("error_" + cantidad_equipo.id).textContent = '';
            }

            const equipo = document.getElementById('equipo_' + item);
            if (equipo.value.length == 0) {
                errores++;
                equipo.classList.add('has-error');
                document.getElementById("error_" + equipo.id).textContent = mensaje;
            } else {
                equipo.classList.remove('has-error');
                document.getElementById("error_" + equipo.id).textContent = '';
            }

            const costo = document.getElementById('costo_' + item);
            if (costo.value.length == 0) {
                errores++;
                costo.classList.add('has-error');
                document.getElementById("error_" + costo.id).textContent = mensaje;
            } else {
                costo.classList.remove('has-error');
                document.getElementById("error_" + costo.id).textContent = '';
            }
        });

    }
    if (errores != 0) { return; }

    btn.disabled = true;
    const timerInterval = Swal.fire({ //se le asigna un nombre al swal
        title: '<i class="fas fa-user-plus" style="margin-right: 10px;"></i>Registrando Usuario!',
        html: 'Espere unos Segundos.',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });
    const data = new FormData($("#form_prestamos")[0]);
    $.ajax({
        data: data,
        url: `${urls}sistemas/registrar_prestamo`,
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
                    title: "Oops, Exception...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
                console.log('Mensaje de xdebug:', response.xdebug_message);
            } if (response == true) {
                tbl_prestamos.ajax.reload(null, false);
                Swal.fire({
                    icon: "success",
                    title: "¡Exito!",
                    text: "Sé género registro con Éxito.",
                });
                arrayItem = [];
                contItem = 0;
                document.getElementById('form_prestamos').reset();
                $("#cantidad_equipo").val(1);
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
    })
});

function limpiarError(campo) {
    if (campo.value.length > 0) {
        campo.classList.remove('has-error');
        document.getElementById("error_" + campo.id).textContent = '';
    }
}

function confirmReturn(folio, equipo) {
    console.log(folio);
    Swal.fire({
        title: `Confirmar Devolución`,
        html: `Confirmar devolución de <b>${equipo}</b>`,
        iconHtml: '<i class="fas fa-check"></i>',
        showCancelButton: true,
        confirmButtonColor: "#28A745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Confirmar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.value) {
            const dataForm = new FormData();
            dataForm.append("id_request", folio);
            $.ajax({
                data: dataForm,
                url: `${urls}sistemas/confirmar_devolucion`,
                type: "post",
                processData: false,
                contentType: false,
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    console.log(response == true);
                    if (response.hasOwnProperty('xdebug_message')) {
                        Swal.fire({
                            icon: "error",
                            title: "Oops, Exception...",
                            text: "Algo salió Mal! Contactar con el Administrador",
                        });
                        console.log('Mensaje de xdebug:', response.xdebug_message);
                    } if (response == true) {
                        tbl_prestamos.ajax.reload(null, false);
                        Swal.fire({
                            icon: "success",
                            title: "¡Exito!",
                            text: "Sé actualizó registro con Éxito.",
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

function handleDeleteLoan(id_folio) {
    Swal.fire({
        title: `Deseas Eliminar el Prestamo con Folio: ${id_folio} ?`,
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
                url: `${urls}sistemas/eliminar_registro`,
                type: "post",
                processData: false,
                contentType: false,
                success: function (response) {
                    console.log(response);
                    if (response) {
                        tbl_prestamos.ajax.reload(null, false);
                        Swal.fire({
                            icon: "success",
                            title: "¡Exito!",
                            text: "Sé elimino registro con Éxito.",
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

function addItem() {
    if (arrayItem.length < 5) {
        contItem++;
        arrayItem.forEach(item => {
            if (item === contItem) {
                contItem++;
            }
        });
        arrayItem.push(contItem);
        $("#div_items").append(`<div class="row extra" id="div_clon_${contItem}">
            <div class="col-md-1" style="text-align: center;">
                <button onclick="removeItem(1)" type="button" class="btn btn-danger" style="margin-top: 17px;"><i class="fas fa-trash-alt"></i></button>
            </div>
            <div class="col-md-2">
                <label for="cantidad_equipo_${contItem}">Cantidad:</label>
                <input class="form-control" type="number" min="1" value="1" name="cantidad_equipo_[]" id="cantidad_equipo_${contItem}" onchange="limpiarError(this)">
                <div id="error_cantidad_equipo_${contItem}" class="text-danger"></div>
            </div>
            <div class="col-md-4">
                <label for="equipo_${contItem}">Equipo:</label>
                <select class="form-control" type="text" name="equipo_[]" id="equipo_${contItem}" style="width: 100%;" onchange="buscarCosto(this,${contItem})">
                    <option value="">Selecciona una opción</option>
                </select>
                <div id="error_equipo_${contItem}" class="text-danger"></div>
            </div>
            <div class="col-md-2">
                <label for="costo_${contItem}">Costo:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">$</span>
                    </div>
                    <input class="form-control" type="number" min="1.00" name="costo_[]" id="costo_${contItem}" onchange="limpiarError(this)">
                </div>
                <div id="error_costo_${contItem}" class="text-danger"></div>
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
                    listResponse.forEach(element => {
                        $(`#equipo_${contItem}`).append(`<option value="${element.id_product}">${element.product}</option>`);
                    });
                } else {
                    $(`#equipo_${contItem}`).append(`<option value="">Sin Equipos Disponible</optino>`);
                }
            }
        });
        $(`#equipo_${contItem}`).select2({
            // allowClear: true,
            tags: true
        });
        if (contItem % 2 == 0) {
            $("#div_clon_" + contItem).attr("style", "background-color: #E5E5E5;");
        }
    } else {
        $("#error_items").html(
            `<div class="alert alert-warning alert-dismissible" role="alert">
                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
                   </button>
                   <strong>EL SISTEMA SOLO PERMITE SALIDA DE 5 PRODUCTOS ...</strong>
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
        return false;
    }
}

function removeItem(item) {
    var i = arrayItem.indexOf(item);
    arrayItem.splice(i, 1);
    $("#div_clon_" + item).remove();
    if (contItem > 0) {
        contItem = 0;
    }
}

function buscarCosto(campo, item) {
    document.getElementById("error_" + campo.id).textContent = '';
    const costo = document.getElementById('costo_' + item);
    costo.value = '';
    costo.readOnly = false;
    if (!isNaN(campo.value)) {
        const data = new FormData();
        data.append('id_product', campo.value);
        $.ajax({
            data: data,
            url: `${urls}sistemas/datos_productos`,
            type: "post",
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (resp) {
                if (resp.cost_unit != null) {
                    costo.readOnly = true;
                    costo.value = resp.cost_unit;
                    costo.classList.remove('has-error');
                    document.getElementById("error_" + costo.id).textContent = '';

                }
            }
        });
    }
}

$("#nomina").on('change', function () {
    if ($("#nomina").val().length == 0) {
        $("#nomina").addClass('has-error');
        $("#error_nomina").text('Campo Requerido');
        return false;
    }
    const dataUser = new FormData();
    dataUser.append('ID', $("#nomina").val())
    $.ajax({
        data: dataUser,
        url: `${urls}sistemas/datos_usuario`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (resp) {
            if (resp) {
                if ($("#usuario").val() != resp.id_user) {
                    $("#usuario").val(resp.id_user).change();
                }
            } else {
                $("#nomina").addClass('has-error');
                $("#error_nomina").text('No Existente');
            }
        },
    });
});

$("#usuario").on('change', function () {
    if ($("#usuario").val().length == 0) {
        $("#usuario").addClass('has-error');
        $("#error_usuario").text('Campo Requerido');
        return false;
    }
    const dataUser = new FormData();
    dataUser.append('ID_U', $("#usuario").val())
    $.ajax({
        data: dataUser,
        url: `${urls}sistemas/datos_usuario`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (resp) {
            if (resp) {
                if ($("#nomina").val() != resp.nomina) {
                    $("#nomina").val(resp.nomina).change();
                }
            } else {
                $("#usuario").addClass('has-error');
                $("#error_usuario").text('No Existente');
            }
        },
    });
});