/*
* MODULO: Inventario
* AUTOR: Horus Samael Riva Pedraza
* 
*
*/
const asignation = document.getElementById("asignacion");
const users_select = document.getElementById("usuarios_select");
var arrayProduct = [];
var contProduct = 1;

$(document).ready(function () {
    console.log("user: ", users_select);
    
  //  users_select.style.display = "none";

    $("#id_user").select2({
        placeholder: "Selecciona una Opción",
    });
    tabla_supplies = $("#tabla_suministros").dataTable({
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        buttons: [
            {
                extend: "excelHtml5",
                title: "Inventario de Suministros",
                exportOptions: {
                    columns: [1, 2],

                },
            }
            /*{
                          extend:'pdfHtml5',
                          title:'Listado de Urs',
                          exportOptions:{
                            columns:[1,2,3,4]
                        }
            }*/
        ],
        processing: true,
        ajax: {
            method: "post",
            url: urls + "sistemas/datos_productos_inventario",
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
                data: "id_product",
                title: "FOLIO",
            },
            {
                data: "product",
                title: "PRODUCTO",
            },
            {
                data: null,
                render: function (data, type, full, meta) {
                    let media_min = parseInt(data["min"]);
                    let cantidad = parseInt(data["amount"]);
                    if (cantidad == 0) {
                        return `<span class="badge bg-danger">${cantidad} piezas</span>`;
                    } else if (cantidad <= media_min) {
                        return `<span class="badge bg-warning">${cantidad} piezas</span>`;
                    }
                    else {
                        return `<span class="badge bg-success">${cantidad} piezas</span>`;
                    }
                },
                title: "STOCK",
                className: "text-center",
            },
            {
                data: "min",
                title: "MINIMO",
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
                targets: 4,
                render: function (data, type, full, meta) {
                    return ` <div class="pull-right mr-auto">
                      <button type="button" class="btn btn-info btn-sm" title="Ingresar Producto"  onClick=Input(${data["id_product"]},${data["amount"]})>
                      <i class="fas fa-plus"></i>
                      </button>
                      <button type="button" class="btn btn-info btn-sm" title="Editar Producto"  onClick=handleEdit(${data["id_product"]},${data["amount"]})>
                          <i class="far fa-edit"></i>
                      </button>
                      <button type="button" class="btn btn-danger btn-sm" title="Eliminar Producto"  onClick="handleDelete(${data["id_product"]})">
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
            $(row).attr("id", "product_" + data.id_product);
        },
    }).DataTable();
    $('#tabla_suministros thead').addClass('thead-dark text-center');

    tabla_responsability = $("#tabla_asignaciones").dataTable({
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        buttons: [
            /* {
                extend: "excelHtml5",
                title: "Inventario de Suministros",
                exportOptions: {
                    columns: [1, 2],

                },
            } */
            /*{
                          extend:'pdfHtml5',
                          title:'Listado de Urs',
                          exportOptions:{
                            columns:[1,2,3,4]
                        }
            }*/
        ],
        processing: true,
        ajax: {
            method: "post",
            url: urls + "sistemas/datos_productos_asignados",
            dataSrc: "",
        },
        lengthChange: true,
        ordering: true,
        autoWidth: false,
        rowId: "staffId",
        dom: "lfrtip",
        language: {
            url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
        },
        columns: [
            {
                data: "id_request",
                title: "FOLIO",
                className: "text-center",
            },
            {
                data: "product",
                title: "PRODUCTO",
                className: "text-center",
            },
            {
                data: "name",
                title: "USUARIO",
                className: "text-center",
            },
            {
                data: "created",
                title: "FECHA",
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
                targets: 4,
                render: function (data, type, full, meta) {
                    return ` <div class="pull-right mr-auto">
                    <a href="${urls}sistemas/ver-responsiva-inventario/${$.md5(key + data["id_request"])}" target="_blank" class="btn btn-outline-info btn-sm">
                    <i class="fas fa-eye"></i>
                  </a>
                      <button type="button" class="btn btn-danger btn-sm" title="Eliminar Producto"  onClick="handleDelete(${data["id_request"]})">
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
            $(row).attr("id", "product_" + data.id_request);
        },
    }).DataTable();
    $('#tabla_asignaciones thead').addClass('thead-dark text-center');
});

$("#ID_").on("change", function (e) {
    e.preventDefault();
    _nomina_ = "";
    $("#error_ID_").text("");
    $("#ID_").removeClass('has-error');
    if ($("#ID_").val().length == 0) {
        return false;
    }
    var data = new FormData();
    data.append('ID', $("#ID_").val());
    $.ajax({
        data: data,
        url: `${urls}sistemas/datos_usuario`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (respUser) {
            // console.log(respUser);
            if (respUser) {
                var nombre = `${respUser.nombre} ${respUser.apep} ${respUser.apem}`
                $("#user").val(nombre);
                $("#select2-id_user-container").attr('title', nombre);
                $("#select2-id_user-container").empty();
                $("#select2-id_user-container").append(`${nombre}`);
                $("#id_user").val(respUser.id_user);
                $("#depto").val(respUser.departamento);
            } else {
                _nomina_ = "Nomina no Encontrada";
                $("#error_ID_").text(_nomina_);
                $("#ID_").addClass('has-error');
            }
        }
    });
});

$("#id_user").on("change", function (e) {
    e.preventDefault();
    var data = new FormData();
    data.append('ID_U', $("#id_user").val());
    $.ajax({
        data: data,
        url: `${urls}sistemas/datos_usuario`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (respUser) {
            // console.log(respUser);
            if (respUser) {
                var nombre = `${respUser.nombre} ${respUser.apep} ${respUser.apem}`
                $("#user").val(nombre);
                $("#ID_").val(respUser.nomina);
                $("#depto").val(respUser.departamento);
            } else {
            }
        }
    });
});

var formUser = $("#inputs_duplica").clone(true, true).html();
$("#btn_agregar").on("click", function (e) {
    e.preventDefault();
    if (arrayProduct.length < 5) {
        contProduct++;
        arrayProduct.forEach(item => {
            if (item === contProduct) {
                contProduct++;
            }
        });
        arrayProduct.push(contProduct);
        $("#inputs_duplica").append(`<div class="row" id="inputs_clon_${contProduct}">
            <div class="col-md-4">
                <input type="hidden" id="amount_${contProduct}" name="amount_[]">
                <label for="product_${contProduct}">Inventario:</label>
                <select name="product_[]" id="product_${contProduct}" class="form-control select2-hidden-accessible" style="width: 100%; height: calc(2.25rem + 2px);" onchange="validarClon(this)">
                </select>
                <div id="error_product_${contProduct}" class="text-danger"></div>
            </div>
            <div class="form-group col-md-4">
                    <label for="cantidad_${contProduct}">Cantidad</label>
                    <input type="number" name="cantidad_[]" id="cantidad_${contProduct}" min="1" class="form-control" onchange="validarClon(this)">
                    <div id="error_cantidad_${contProduct}" class="text-danger"></div>
            </div>            
            <div class="col-md-2">
            <input type="hidden" name="responsiba_[]" id="opc_responsiba_${contProduct}">
                <label for="responsiba_${contProduct}">Responsiva</label>
                <div class="form-check">
                    <input class="form-check-input" style="width: 30px;height: 30px;" type="checkbox" id="responsiba_${contProduct}" class="form-control" onclick="actualizarValor(this)">
                    <label class="form-check-label" for="miCheckbox" id="lbl_responsiba_${contProduct}" style="margin-left: 20px;font-size: 24px;">No</label>
                </div>
            </div>
            <div class="col-md-2" style="padding-top: 2rem;">
                <button type="button" class="btn btn-danger" onclick="retirarItem(${contProduct},1)"> <i class="fas fa-times"></i></button>
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
                    $(`#product_${contProduct}`).append(`<option value="">Inventario...</optino>`);
                    listResponse.forEach(element => {
                        $(`#product_${contProduct}`).append(`<option value="${element.id_product}">${element.product}</option>`);
                    });
                } else {
                    $(`#product_${contProduct}`).append(`<option value="">Sin Equipos Disponible</optino>`);
                }
            }
        });
        $("#product_" + contProduct).select2();
        $("#inputs_clon_" + contProduct).addClass("extras");
        if (contProduct % 2 == 0) {
            $("#inputs_clon_" + contProduct).attr("style", "background-color: #E5E5E5;");
        }
    } else {
        $("#error_hijos").html(
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
});

function validarClon(input) {
    if (input.value.length > 0) {
        document.getElementById(input.id).classList.remove('has-error');
        document.getElementById("error_" + input.id).textContent = '';
    }
}

function retirarItem(item) {
    var i = arrayProduct.indexOf(item);
    arrayProduct.splice(i, 1);
    sessionStorage.setItem('arrayProduct', JSON.stringify(arrayProduct));
    $("#inputs_clon_" + item).remove();
    if (contProduct > 0) {
        contProduct = 0;
    }
}

function actualizarValor(checkbox) {
    const valorInput = document.getElementById("opc_" + checkbox.id);
    if (checkbox.checked) {
        valorInput.value = 1;
        $("#lbl_" + checkbox.id).text("SI");
    } else {
        valorInput.value = 0;
        $("#lbl_" + checkbox.id).text("NO");
    }
}

function validarCant(item) {
    var respuesta = "";
    $("#amount_" + item).val("");
    let id = new FormData();
    id.append("id_product", $("#product_" + item).val());
    $.ajax({
        data: id,
        async: false,
        url: `${urls}sistemas/datos_productos`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (dataProduct) {
            if (dataProduct != false) {
                if (parseInt($("#cantidad_" + item).val()) > parseInt(dataProduct.amount)) {
                    respuesta = `${dataProduct.amount} Productos disponibles`;
                    $("#cantidad_" + item).addClass('has-error');
                    $("#error_cantidad_" + item).text(respuesta);
                } else {
                    $("#amount_" + item).val(dataProduct.amount);
                    respuesta = "";
                    $("#cantidad_" + item).removeClass('has-error');
                    $("#error_cantidad_" + item).text(respuesta);
                }
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador del Sistema",
                });
            }
        }
    });
    return respuesta;
}

function handleDelete(id_folio) {
    let producto = $(`#product_${id_folio} td`)[1].innerHTML;
    Swal.fire({
        title: `Deseas Eliminar el Producto: ${producto}`,
        text: `Una vez Eliminado no podras usar el Producto!`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#5A6268",
        confirmButtonText: "Eliminar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.value) {
            let dataForm = new FormData();
            dataForm.append("product_", id_folio);
            $.ajax({
                data: dataForm, //datos que se envian a traves de ajax
                url: `${urls}sistemas/eliminar_producto`, //archivo que recibe la peticion
                type: "post", //método de envio
                processData: false, // dile a jQuery que no procese los datos
                contentType: false, // dile a jQuery que no establezca contentType
                dataType: "json",
                success: function (response) {
                    if (response == true) {
                        tabla_supplies.ajax.reload(null, false);
                        Swal.fire("!Producto Eliminado Correctamente!", "", "success");
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

function handleEdit(id, cantidad) {
    $("#id_article").val("");
    $("#id_article").val(id);
    $("#description").val($(`#product_${id} td`)[1].innerHTML);
    /* $("#stock").val(cantidad); */
    $("#stock_min").val($(`#product_${id} td`)[3].innerHTML);
    $("#actualizaModal").modal("show");
}

function Input(id, cantidad) {
    let producto = $(`#product_${id} td`)[1].innerHTML;
    $("#articulo_title").empty();
    $("#articulo_title").append(`${producto.toUpperCase()}`);
    $("#id_articulos").val(id);
    $("#cantidad").val(cantidad);

    $("#codigo_entrada").val("");
    $("#cantidad_entrada").val("");
    $("#requisicion_entrada").val("");
    
    $("#entradaModal").modal("show");
}

function validarReport() {
    if ($("#tipo").val().length > 0) {
        $("#error_tipo").text("");
        $("#tipo").removeClass('has-error');
    }
    if ($("#fecha_inicial").val().length > 0) {
        $("#error_fecha_inicial").text("");
        $("#fecha_inicial").removeClass('has-error');
    }
    if ($("#fecha_final").val().length > 0) {
        $("#error_fecha_final").text("");
        $("#fecha_final").removeClass('has-error');
    }
}

$("#asignar_equipo").submit(function (e) {
    e.preventDefault();
    const btn = document.getElementById("btn_asignar_equipo");
    var error = 0;

    if (arrayProduct.length > 0) {
        arrayProduct.forEach(item => {
            if ($("#product_" + item).val().length == 0) {
                error++;
                $("#product_" + item).addClass('has-error');
                $("#error_product_" + item).text("Campo Requerido");
            } else {
                $("#product_" + item).removeClass('has-error');
                $("#error_product_" + item).text("");
            }

            if ($("#cantidad_" + item).val().length == 0) {
                error++;
                $("#cantidad_" + item).addClass('has-error');
                $("#error_cantidad_" + item).text("Campo Requerido");
            } else {
                cantidad = validarCant(item);
                console.log("cantidad:  ", cantidad);
                if (cantidad != "") {
                    error++;
                }
            }
        });
    } else { error++; }
    console.log("errores:  ", error);
    if (error != 0) {
        return false;
    }
    btn.disabled = true;

    const outProduct = new FormData($("#asignar_equipo")[0]);
    $.ajax({
        data: outProduct,
        url: `${urls}sistemas/salida_productos`,
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (saveOut) {
            btn.disabled = false;
            if (saveOut == true) {
                $('#asignar_equipo')[0].reset();
                $("#dato_Asig_usuario").empty();
                arrayProduct = [];
                $(".extras").remove();
                $("#select2-depto-container").empty();
                $("#select2-id_user-container").empty();
                setTimeout(function () {
                    tabla_supplies.ajax.reload(null, false);
                    tabla_responsability.ajax.reload(null, false);
                }, 100);
                Swal.fire("!Los datos se han Actualizado!", "", "success");
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador del Sistema",
                });
            }
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: `Error: ​​[ ${jqXHR.status} ]`,
        });
        $("#btn_asignar_equipo").prop("disabled", false);
    });
});

$("#alta_articulo").submit(function (event) {
    event.preventDefault();
    $("#alta_suministro").prop("disabled", true);
    let data = new FormData($("#alta_articulo")[0]);
    $.ajax({
        data: data, //datos que se envian a traves de ajax
        url: urls + "sistemas/alta_producto", //archivo que recibe la peticion
        type: "post", //método de envio
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentType
        success: function (response) {
            $("#alta_suministro").prop("disabled", false);
            if (response != "error") {
                $('#alta_articulo')[0].reset();
                setTimeout(function () {
                    tabla_supplies.ajax.reload(null, false);
                }, 100);
                Swal.fire("!El Producto ah sido dado de Alta!", "", "success");
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

$("#edit_article").submit(function (event) {
    event.preventDefault();
    let data = new FormData($("#edit_article")[0]);
    $.ajax({
        data: data,
        url: urls + "sistemas/editar_producto",
        type: "post",
        processData: false,
        contentType: false,
        dataType: "json",
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

$("#entrada_articulo").submit(function (e) {
    e.preventDefault();
    $("#btn_entrada_articulo").prop("disabled", true);
    let amount_in = new FormData($("#entrada_articulo")[0]);
    amount_in.append('tipo', 2);
    $.ajax({
        data: amount_in,
        url: urls + "sistemas/alta_producto",
        type: "post",
        processData: false,
        contentType: false,
        success: function (response) {
            $("#btn_entrada_articulo").prop("disabled", false);
            if (response != false) {
                setTimeout(function () {
                    tabla_supplies.ajax.reload(null, false);
                }, 100);
                $("#entradaModal").modal("toggle");
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
})

$("#form_reportes").on("submit", function (e) {
    e.preventDefault();

    if ($("#tipo").val().length == 0) {
        error_tipo = "Fecha Inicial Requerida";
        $("#error_tipo").text(error_tipo);
        $("#tipo").addClass('has-error');
    } else {
        error_tipo = "";
        $("#error_tipo").text(error_tipo);
        $("#tipo").removeClass('has-error');
    }

    if ($("#fecha_inicial").val().length == 0) {
        error_fecha_inicial = "Fecha Inicial Requerida";
        $("#error_fecha_inicial").text(error_fecha_inicial);
        $("#fecha_inicial").addClass('has-error');
    } else {
        error_fecha_inicial = "";
        $("#error_fecha_inicial").text(error_fecha_inicial);
        $("#fecha_inicial").removeClass('has-error');
    }

    if ($("#fecha_final").val().length == 0) {
        error_fecha_final = "Fecha Final Requerida";
        $("#error_fecha_final").text(error_fecha_final);
        $("#fecha_final").addClass('has-error');
    } else if ($("#fecha_final").val() < $("#fecha_inicial").val()) {
        error_fecha_final = "Fecha Final Incorrecta";
        $("#error_fecha_final").text(error_fecha_final);
        $("#fecha_final").addClass('has-error');
    } else {
        error_fecha_final = "";
        $("#error_fecha_final").text(error_fecha_final);
        $("#fecha_final").removeClass('has-error');
    }
    if (error_fecha_inicial != "" || error_fecha_final != "" || error_tipo != "") { return false; }
    $("#btn_reportes").prop("disabled", true);

    let timerInterval = Swal.fire({ //se le asigna un nombre al swal
        allowOutsideClick: false,
        title: '¡Generando Reporte!',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });

    let fecha_inicio = $("#fecha_inicial").val();
    let fecha_fin = $("#fecha_final").val();
    let tipo = $("#tipo").val();
    if (tipo == 1) { tipo_text = "Entradas_Productos"; } else { tipo_text = "Salidas_Productos"; }
    var nomArchivo = `Reporte_${tipo_text}_${fecha_inicio}_${fecha_fin}.xlsx`;
    var param = JSON.stringify({
        fecha_inicio: fecha_inicio,
        fecha_fin: fecha_fin,
        type: tipo,
    });
    var pathservicehost = `${urls}/sistemas/reporte_producto`;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", pathservicehost, true);
    xhr.responseType = "blob";

    //Send the proper header information along with the request
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function (e) {
        Swal.close(timerInterval);
        $("#btn_reportes").prop("disabled", false);
        if (xhr.readyState === 4 && xhr.status === 200) {
            $("#tipo").val("");
            $("#fecha_inicial").val("");
            $("#fecha_final").val("");
            var contenidoEnBlob = xhr.response;
            var link = document.createElement("a");
            link.href = (window.URL || window.webkitURL).createObjectURL(
                contenidoEnBlob
            );
            link.download = nomArchivo;
            var clicEvent = new MouseEvent("click", {
                view: window,
                bubbles: true,
                cancelable: true,
            });
            //Simulamos un clic del usuario
            //no es necesario agregar el link al DOM.
            link.dispatchEvent(clicEvent);
            //link.click();
        } else {
            alert(" No es posible acceder al archivo, probablemente no existe.");
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No es posible acceder al archivo, probablemente no existe.",
            });
        }
    };
    xhr.send("data=" + param);
});