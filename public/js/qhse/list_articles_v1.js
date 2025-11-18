/**
 * ARCHIVO MODULO LISTA DE ARTICULOS EPP
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:rafael.cruz.aguilar1@gmail.com
 * CEL:55-65-42-96-49.
 */
var cont = "1";

var arrayMenuItems = [];
var arrayMenuItemsEdit = [];
var arrayMenuItemsDelet = [];

// data table
$(document).ready(function () {
    tbl_articles = $("#tabla_lista_articulos")
        .DataTable({
            processing: true,
            ajax: {
                method: "POST",
                url: urls + "qhse/lista_articulos_epp",
                dataSrc: "",
            },
            lengthChange: true,
            ordering: true,
            responsive: true,
            autoWidth: true,
            rowId: "staffId",
            dom: "frtip",

            language: {
                url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
            },
            columns: [
                {
                    data: "id_product",
                    title: "FOLIO",
                    className: "text-center",
                },
                {
                    data: null,
                    render: function (data, type, full, meta) {
                        let name = data["description_product"].toLowerCase();

                        return name;
                    },

                    title: "ARTICULO",
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
                    targets: 2,
                    render: function (data, type, full, meta) {
                        return ` <div class="pull-right mr-auto">
                        <button type="button" class="btn btn-outline-success btn-sm"
                          onClick=ActualizarMenu(${data["id_product"]})>
                          <i class="far fa-edit"></i>
                        </button>

                        <button class="btn btn-outline-danger btn-sm"
                         onClick="BorrarMenu(${data['id_product']},'${data['description_product']}')" >
                         <i class="fas fa-trash-alt"></i>
                        </button>
                      </div> `;
                    },
                },

            ],
            order: [[0, "DESC"]],
        });
    $("#tabla_lista_articulos thead").addClass("thead-dark text-center");

});

function validar() {
    if ($("#articulo").val().length > 0) {
        $("#error_articulo").text("");
        $("#articulo").removeClass('has-error');
    }
    
}




$("#nuevo_articulo").on("submit", function (e) {
    e.preventDefault();
    if ($("#articulo").val().length == 0) {

        Swal.fire({
            icon: "error",
            title: "!ERROR¡",
            text: "ARTICULO VACIO",
        });
    } else {
        if ($("#articulo").val().length == 0) {
            error_articulo = "Articulo Requerido";
            $("#error_articulo").text(error_articulo);
            $("#articulo").addClass('has-error');
        } else {
            error_articulo = "";
            $("#error_articulo").text(error_articulo);
            $("#articulo").removeClass('has-error');
        }
        
    }
    if (error_articulo) {
        return false;
    }


    $("#btn_guardar_articulo").prop("disabled", true);

    var formData1 = new FormData($('#nuevo_articulo')[0]);
    $.ajax({
        type: "post",
        url: `${urls}qhse/nuevo_articulo`,
        cache: false,
        data: formData1,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (response) {
            if (response != "error") {
                setTimeout(function () {
                    tbl_articles.ajax.reload(null, false);
                }, 100);
               
             
                $("#titulo").val("");
                

                Swal.fire("!El Articulo se a creado correctamente!", "", "success");
                $("#btn_guardar_articulo").prop("disabled", false);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
                $("#btn_guardar_articulo").prop("disabled", false);
            }
        },
    }).fail(function (jqXHR, textStatus, errorThrown) {
        if (jqXHR.status === 0) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Fallo de conexión: ​​Verifique la red.",
            });
            $("#btn_guardar_articulo").prop("disabled", false);
        } else if (jqXHR.status == 404) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No se encontró la página solicitada [404]",
            });
            $("#btn_guardar_articulo").prop("disabled", false);
        } else if (jqXHR.status == 500) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Internal Server Error [500]",
            });
            $("#btn_guardar_articulo").prop("disabled", false);
        } else if (textStatus === "parsererror") {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Error de análisis JSON solicitado.",
            });
            $("#btn_guardar_articulo").prop("disabled", false);
        } else if (textStatus === "timeout") {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Time out error.",
            });
            $("#btn_guardar_articulo").prop("disabled", false);
        } else if (textStatus === "abort") {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ajax request aborted.",
            });

            $("#btn_guardar_articulo").prop("disabled", false);
        } else {
            alert("Uncaught Error: " + jqXHR.responseText);
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: `Uncaught Error: ${jqXHR.responseText}`,
            });
            $("#btn_guardar_articulo").prop("disabled", false);
        }
    });
});



function BorrarMenu(id,articulo) {
    Swal.fire({
        title: `Deseas Eliminar el Articulo: ${articulo} ?`,
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
            dataForm.append("folio", id);

            $.ajax({
                data: dataForm,
                url: `${urls}qhse/eliminar_articulo`,
                type: "post",
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response) {
                        tbl_articles.ajax.reload(null, false);

                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Algo salió Mal! Contactar con el Administrador",
                        });
                    }
                },
            });
        }
    });
}

var inputUserEdit = $("#input_duplica_edit").clone(true, true).html();
var cont1 = "1";
var items_DataBase = "";
var error_comida_edit = 0;
function ActualizarMenu(id) {
    arrayMenuItemsEdit = [];
    sessionStorage.setItem('arrayMenuItemsEdit', JSON.stringify(arrayMenuItemsEdit));
    $(".extras-edit").remove();
    cont1 = 1;
    error_comida_edit = 0;
    items_DataBase = "";
    $("#titulo_edit").val("");
    $("#folio_edit").val("");
    $("#comida_edit_1").val("");

    let data = new FormData();
    data.append("id", id);

    $.ajax({
        data: data,
        type: "post",
        url: `${urls}cafeteria/datos_editar_menu`,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (resp) {
            if (resp != "error") {
                resp.forEach((key, value) => {
                    $("#imagen1").attr("src", key.imagen_menu);
                    $("#imagen1").attr("width", "300px");
                    $("#imagen1").attr("height", "250px");
                    $("#folio_edit").val(key.special_menu);
                    $("#titulo_edit").html(`<h3>${key.tittle_menu}</h3>`);
                    $("#food_edit_" + cont1).val(key.id_food);
                    $("#comida_edit_" + cont1).val(key.description);
                    items_DataBase = resp.length;
                    if (arrayMenuItemsEdit.length < (resp.length) - 1) {
                        cont1++;                        
                        // Agregamos el input
                        $("#item-duplica-edit").prepend(inputUserEdit).show("slow");
                        $("#extra_edit_1").attr("id", "extra_edit_" + cont1);
                        $("#food_edit_1").attr("id", `food_edit_${cont1}`);
                        $("#comida_edit_1").attr("onChange", `validarPlatilloModal(${cont1})`);
                        $("#comida_edit_1").attr("id", `comida_edit_${cont1}`);
                        $("#error_comida_edit_1").attr("id", `error_comida_edit_${cont1}`);
                        $("#extra_edit_1").attr("id", `extra_edit_${cont1}`);
                        $("#btn_eliminar_edit_1").attr("id", `btn_eliminar_edit_${cont1}`);
                        arrayMenuItemsEdit.push(cont1);
                        // Se guarda en localStorage despues de JSON stringificarlo 
                        sessionStorage.setItem('arrayMenuItemsEdit', JSON.stringify(arrayMenuItemsEdit));
                        $("#extra_edit_" + cont1).addClass("extras-edit");
                        $("#comida_edit_" + cont1).focus();
                        console.log(items_DataBase);

                    }
                });
                $("#actualizar_menu_modal").modal("show");
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
            }
        },
    });
}

function BorrarComida(id) {
    arrayMenuItemsDelet = [];
    sessionStorage.setItem('arrayMenuItemsDelet', JSON.stringify(arrayMenuItemsDelet));
    cont1 = 0;
    items_DataBase = "";
    $("#input_duplica_borrar").empty();

    let data = new FormData();
    data.append("id", id);

    $.ajax({
        data: data,
        type: "post",
        url: `${urls}cafeteria/datos_editar_menu`,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (resp) {
            items_DataBase = resp.length;
            if (resp != "error") {
                resp.forEach((key, value) => {
                    $("#imagen2").attr("src", key.imagen_menu);
                    $("#imagen2").attr("width", "300px");
                    $("#imagen2").attr("height", "300px");
                    $("#folio_borrar").val(key.special_menu);
                    $("#titulo_borrar").html(`<h2>${key.tittle_menu}</h2>`);


                    if (cont1 < (resp.length)) {
                        cont1++;
                        // Agregamos el input
                        $("#input_duplica_borrar").append(
                            `
                        <div class="row">
                            <div class="col-md-1">
                                <input type="checkbox" id="cbx_comida_${cont1}" name="cbx_comida_[]" class="check-camp"  value="${key.id_food}" onclick="SelecionarItemModal(${cont1},1)">
                            </div>
                            <div class="form-group col-md-9">
                                <input type="text" class="form-control rounded-0" id="comida_borrar_${cont1}" name="comida_borrar[]" value="${key.description}" readonly>
                            </div>
                        </div>
                        `);
                    }
                });
                $("#eliminar_comida_modal").modal("show");
            } else {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
            }
        }
    });
}

function validarModal() {
    if ($("#titulo_edit").val().length > 0) {
        error_titulo_edit = "";
        $("#error_titulo_edit").text(error_titulo_edit);
        $("#titulo_edit").removeClass('has-error');
    }
}

function validarPlatilloModal(cont_e) {
    if ($("#comida_edit_" + cont_e).val().length == 0) {
        $("#error_comida_edit_" + cont_e).text("");
        $("#comida_edit_" + cont_e).removeClass('has-error');
    }
    else if ($("#comida_edit_" + cont_e).val().length <= 3) {
        error_comida_edit = "Ingresa más informacion";
        $("#error_comida_edit_" + cont_e).text(error_comida_edit);
        $("#comida_edit_" + cont_e).addClass('has-error');
    } else if ($("#comida_edit_" + cont_e).val().length >= 4) {
        if ($.trim($("#comida_edit_" + cont_e).val()) == "abcd" || $.trim($("#comida_edit_" + cont_e).val()) == "ABCD" ||
            $.trim($("#comida_edit_" + cont_e).val()) == "asdf" || $.trim($("#comida_edit_" + cont_e).val()) == "ASDF" ||
            $.trim($("#comida_edit_" + cont_e).val()) == "xxxx" || $.trim($("#comida_edit_" + cont_e).val()) == "XXXX" ||
            $.trim($("#comida_edit_" + cont_e).val()) == "aaaa" || $.trim($("#comida_edit_" + cont_e).val()) == "AAAA" ||
            $.trim($("#comida_edit_" + cont_e).val()) == "...." || $.trim($("#comida_edit_" + cont_e).val()) == ",,,," ||
            $.trim($("#comida_edit_" + cont_e).val()) == "____" || $.trim($("#comida_edit_" + cont_e).val()) == "----") {
            error_comida_edit = "Escribe correctamente el platillo";
            $('#error_comida_edit_' + cont_e).text(error_comida_edit);
            $('#comida_edit_' + cont_e).addClass('has-error');
        } else if (isNaN($.trim($("#comida_edit_" + cont_e).val())) == false) {
            error_comida_edit = "No se permiten solo números";
            $('#error_comida_edit_' + cont_e).text(error_comida_edit);
            $('#comida_edit_' + cont_e).addClass('has-error');
        } else {
            error_comida_edit = "";
            $("#error_comida_edit_" + cont_e).text(error_comida_edit);
            $("#comida_edit_" + cont_e).removeClass('has-error');
        }
    }
}

$("#btn_agregar_platillo_edit").click(function (e) {
    e.preventDefault();

    if (items_DataBase == cont1 && error_comida_edit == 0) {
        error_comida_edit = "";
    }

    if ($("#comida_edit_" + cont1).val().length == 0) {
        error_comida_edit = "Plaillo Requerida";
        $("#error_comida_edit_" + cont1).text(error_comida_edit);
        $("#comida_edit_" + cont1).addClass('has-error');
    }
    if (error_comida_edit != "") {
    }

    if (arrayMenuItemsEdit.length < 5) {
        if (arrayMenuItemsEdit.length == 0) {
            cont1++;
        } else {
            cont1++;
            arrayMenuItemsEdit.forEach(food => {
                if (food === cont1) {
                    cont1++;
                }
            });
        }
        // Agregamos el input
        $("#item-duplica-edit").prepend(inputUserEdit).show("slow");
        $("#extra_edit_1").attr("id", "extra_edit_" + cont1);

        $("#food_edit_1").attr("id", `food_edit_${cont1}`);
        $("#comida_edit_1").attr("onChange", `validarPlatilloModal(${cont1})`);
        $("#comida_edit_1").attr("id", `comida_edit_${cont1}`);
        $("#error_comida_edit_1").attr("id", `error_comida_edit_${cont1}`);

        $("#extra_edit_1").attr("id", `extra_edit_${cont1}`);
        $("#btn_eliminar_edit_1").attr("id", `btn_eliminar_edit_${cont1}`);

        $("#btn_eliminar_edit_" + cont1).append(
            `<div class="item-duplica card-tools" >
            <button type="button" class="btn btn-danger btn-retirar-item" 
            onclick="retirarItemModal(${cont1})">
            <i class="fas fa-times"></i>
            </button>
            </div>`
        );
        arrayMenuItemsEdit.push(cont1);
        // Se guarda en localStorage despues de JSON stringificarlo 
        sessionStorage.setItem('arrayMenuItemsEdit', JSON.stringify(arrayMenuItemsEdit));
        $("#extra_edit_" + cont1).addClass("extras-edit");
        $("#comida_edit_" + cont1).focus();
    } else {
        /* Mostrar error */
        $("#resultado_edit").html(
            `<div class="alert alert-warning alert-dismissible" role="alert">
               <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
                 </button>
                 <strong>NO SE PERMITEN MAS DE 6 ITEMS EN EL MENU...</strong>
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

function SelecionarItemModal(itemD, cbx) {
    if (cbx == 1) {
        arrayMenuItemsDelet.push(itemD);
        sessionStorage.setItem('arrayMenuItemsDelet', JSON.stringify(arrayMenuItemsDelet));
        $("#comida_borrar_" + itemD).addClass('has-error');
        $("#cbx_comida_" + itemD).attr("onclick", `SelecionarItemModal(${itemD},${2})`);
    }

    if (cbx == 2) {
        var i_d = arrayMenuItemsDelet.indexOf(itemD);
        arrayMenuItemsDelet.splice(i_d, 1);
        sessionStorage.setItem('arrayMenuItemsDelet', JSON.stringify(arrayMenuItemsDelet));

        $("#comida_borrar_" + itemD).removeClass('has-error');
        $("#cbx_comida_" + itemD).attr("onclick", `SelecionarItemModal(${itemD},${1})`);
    }

}

function retirarItemModal(itemE) {
    var i_m = arrayMenuItemsEdit.indexOf(itemE);
    arrayMenuItemsEdit.splice(i_m, 1);
    sessionStorage.setItem('arrayMenuItemsEdit', JSON.stringify(arrayMenuItemsEdit));
    $("#extra_edit_" + itemE).remove();
    if (cont1 > 0) {
        --cont1;
    }
}

$("#editar_menu").on("submit", function (e) {
    e.preventDefault();
    $("#btn_editar_menu").prop("disabled", true);

    /* if (cont1 == 1
        && $("#titulo_edit").val().length == 0 
        && $("#comida_edit_" + cont1).val().length == 0) {

        Swal.fire({
            icon: "error",
            title: "!ERROR¡",
            text: "Llena el formulario",
        });
    } else {
         if ($("#titulo_edit").val().length == 0) {
            error_titulo_edit = "Titulo del Menu Requerido";
            $("#error_titulo_edit").text(error_titulo_edit);
            $("#titulo_edit").addClass('has-error');
        } else {
            error_titulo_edit = "";
            $("#error_titulo_edit").text(error_titulo_edit);
            $("#titulo_edit").removeClass('has-error');
        } */
    if ($("#comida_edit_1").val().length == 0) {
        error_comida_edit = "Platillo del Menu Requerido";
        $("#error_comida_edit_1").text(error_comida_edit);
        $("#comida_edit_1").addClass('has-error');
    }

    arrayMenuItemsEdit.forEach(food => {
        if ($("#comida_edit_" + food).val() == "") {
            error_comida_edit = "Platillo del Menu Requerido";
            $("#error_comida_edit_" + food).text(error_comida_edit);
            $("#comida_edit_" + food).addClass('has-error');
        }
    });
    /* } */
    if (
        /* error_titulo_edit != "" || */
        error_comida_edit != ""

    ) {
        $("#btn_editar_menu").prop("disabled", false);
        return false;
    }


    var formData1 = new FormData($('#editar_menu')[0]);

    $.ajax({
        type: "post",
        url: `${urls}cafeteria/editar_menu`,
        cache: false,
        data: formData1,
        dataType: "json",
        contentType: false,
        processData: false,

        success: function (response) {
            if (response != "error") {
                setTimeout(function () {
                    tbl_menu.ajax.reload(null, false);
                }, 100);
                $("#item-duplica-edit").slideUp("slow", function () {
                    $(".extras-edit").remove();
                });
                arrayMenuItemsEdit = [];
                sessionStorage.setItem('arrayMenuItemsEdit', JSON.stringify(arrayMenuItemsEdit));
                cont1 = 1;
                $('#actualizar_menu_modal').modal('toggle');
                Swal.fire("!El Menu se a Actualizado correctamente!", "", "success");
                $("#btn_editar_menu").prop("disabled", false);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
                $("#btn_editar_menu").prop("disabled", false);
            }
        },
    }).fail(function (jqXHR, textStatus, errorThrown) {
        if (jqXHR.status === 0) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Fallo de conexión: ​​Verifique la red.",
            });
            $("#btn_editar_menu").prop("disabled", false);
        } else if (jqXHR.status == 404) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No se encontró la página solicitada [404]",
            });
            $("#btn_editar_menu").prop("disabled", false);
        } else if (jqXHR.status == 500) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Internal Server Error [500]",
            });
            $("#btn_editar_menu").prop("disabled", false);
        } else if (textStatus === "parsererror") {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Error de análisis JSON solicitado.",
            });
            $("#btn_editar_menu").prop("disabled", false);
        } else if (textStatus === "timeout") {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Time out error.",
            });
            $("#btn_editar_menu").prop("disabled", false);
        } else if (textStatus === "abort") {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ajax request aborted.",
            });

            $("#btn_editar_menu").prop("disabled", false);
        } else {
            alert("Uncaught Error: " + jqXHR.responseText);
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: `Uncaught Error: ${jqXHR.responseText}`,
            });
            $("#btn_editar_menu").prop("disabled", false);
        }
    });
});

$("#borrar_comida").on("submit", function (e) {
    e.preventDefault();
    $("#btn_borrar_comida").prop("disabled", true);

    if (arrayMenuItemsDelet.length == items_DataBase) {

        Swal.fire({
            icon: "error",
            title: "!ERROR¡",
            text: "No puedes borrar todos los campos",
        });
        $("#btn_borrar_comida").prop("disabled", false);
        return false;
    }

    var data = new FormData();
    $("input[name='cbx_comida_[]']:checked").each(function () {
        data.append("id_comida[]", this.value);
    });
    $.ajax({
        url: `${urls}cafeteria/editar_comida`,
        type: "post", //método de envio
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentType
        data: data,
        success: function (response) {
            if (response != "error") {
                setTimeout(function () {
                    tbl_menu.ajax.reload(null, false);
                }, 100);
                arrayMenuItemsDelet = [];
                sessionStorage.setItem('arrayMenuItemsDelet', JSON.stringify(arrayMenuItemsDelet));
                cont1 = 1;
                $('#eliminar_comida_modal').modal('toggle');
                Swal.fire("!El Menu se a Actualizado correctamente!", "", "success");
                $("#btn_borrar_comida").prop("disabled", false);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
                $("#btn_borrar_comida").prop("disabled", false);
            }
        },
    }).fail(function (jqXHR, textStatus, errorThrown) {
        if (jqXHR.status === 0) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Fallo de conexión: ​​Verifique la red.",
            });
            $("#btn_borrar_comida").prop("disabled", false);
        } else if (jqXHR.status == 404) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No se encontró la página solicitada [404]",
            });
            $("#btn_borrar_comida").prop("disabled", false);
        } else if (jqXHR.status == 500) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Internal Server Error [500]",
            });
            $("#btn_borrar_comida").prop("disabled", false);
        } else if (textStatus === "parsererror") {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Error de análisis JSON solicitado.",
            });
            $("#btn_borrar_comida").prop("disabled", false);
        } else if (textStatus === "timeout") {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Time out error.",
            });
            $("#btn_borrar_comida").prop("disabled", false);
        } else if (textStatus === "abort") {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ajax request aborted.",
            });

            $("#btn_borrar_comida").prop("disabled", false);
        } else {
            alert("Uncaught Error: " + jqXHR.responseText);
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: `Uncaught Error: ${jqXHR.responseText}`,
            });
            $("#btn_borrar_comida").prop("disabled", false);
        }
    });
});