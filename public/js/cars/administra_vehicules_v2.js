/**
 * ARCHIVO MODULO CARS
 * AUTOR: HORUS SAMAEL RIVAS PEDRAZA
 * horus.riv.ped@gmail.com
 * CEL:5624392632
 */

var error_modelo = "0";
var error_placas = "0";
var error_imagen = "0";

// data table
$(document).ready(function () {
    tbl_carros = $("#tabla_inventario_vehiculos")
        .dataTable({
            processing: true,
            ajax: {
                method: "post",
                url: urls + "autos/todos_vehiculos",
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
                    data: "id_car",
                    title: "FOLIO",
                    className: "text-center",
                },
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        let model = data["model"].toUpperCase();
                        return `<h5>${model}</h5><hr><img src="${row.imagen}" style="height:90px;width:200px;" />`;
                    },
                    title: "MODELO",
                    className: "text-center",
                },
                {
                    data: null,
                    render: function (data, type, full, meta) {
                        let name = data["placa"].toUpperCase();
                        return name;
                    },
                    title: "PLACAS",
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
                        var placa = data["placa"].toUpperCase();
                        return ` <div class="pull-right mr-auto">
                        <button class="btn btn-danger btn-sm" onClick=BorrarVehiculo(${data["id_car"]},"${placa}") >
                            <i class="fas fa-trash-alt"></i>
                        </button>
                         </div> `;
                    },
                },
            ],
            order: [[0, "DESC"]],

        })
        .DataTable();

});

function validar() {
    if ($("#modelo").val().length > 0) {
        error_modelo = "";
        $("#error_modelo").text(error_modelo);
        $("#modelo").removeClass('has-error');
    }
    if ($("#placas").val().length > 0) {
        error_placas = "";
        $("#error_placas").text(error_placas);
        $("#placas").removeClass('has-error');
    }
    if ($("#imagen").val().length > 0) {
        error_imagen = "";
        $("#error_imagen").text(error_imagen);
        $("#imagen").removeClass('has-error');
    }
}

$("#guardar_auto").on("submit", function (e) {
    e.preventDefault();
    var error_modelo = '';
    var error_placas = '';
    var error_imagen = '';
    if ($("#modelo").val().length == 0
        && $("#placas").val().length == 0
        && $("#imagen").val().length == 0) {
        Swal.fire({
            icon: "error",
            title: "!ERROR¡",
            text: "Llena el formulario",
        });
        return false;

    } else {
        if ($("#modelo").val().length == 0) {
            error_modelo = "Campor Requerido";
            $("#error_modelo").text(error_modelo);
            $("#modelo").addClass('has-error');
        }
        if ($("#placas").val().length == 0) {
            error_placas = "Campor Requerido";
            $("#error_placas").text(error_placas);
            $("#placas").addClass('has-error');
        }
        if ($("#imagen").val().length == 0) {
            error_imagen = "Campor Requerida";
            $("#error_imagen").text(error_imagen);
            $("#imagen").addClass('has-error');
        }
    }
    if (
        error_modelo != "" ||
        error_placas != "" ||
        error_imagen != ""
    ) {
        console.log('error_modelo ', error_modelo);
        console.log('error_placas ', error_placas);
        console.log('error_imagen ', error_imagen);
        return false;
    }
    var fileSize = $('#imagen')[0].files[0].size;
    var siezekiloByte = parseInt(fileSize / 1024);
    if (siezekiloByte > 1024) {
        $("#imagen").val("");
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "El Tamaño de la Imagen sobre pasa el permitido...",
        });
        return false;
    }
    $("#btn_guardar_auto").prop("disabled", true);

    var formData1 = new FormData($('#guardar_auto')[0]);

    $.ajax({
        type: "post",
        url: `${urls}autos/nuevo_vehiculo`,
        cache: false,
        data: formData1,
        dataType: "json",
        contentType: false,
        processData: false,

        success: function (response) {
            if (response != "error") {

                tbl_carros.ajax.reload(null, false);

                $("#modelo").val("");
                $("#placas").val("");
                $("#imagen").val("");

                Swal.fire("!El Vehiculo se a creado correctamente!", "", "success");
                $("#btn_guardar_auto").prop("disabled", false);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
                $("#btn_guardar_auto").prop("disabled", false);
            }
        },
    }).fail(function (jqXHR, textStatus, errorThrown) {
        if (jqXHR.status === 0) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Fallo de conexión: ​​Verifique la red.",
            });
            $("#btn_guardar_auto").prop("disabled", false);
        } else if (jqXHR.status == 404) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No se encontró la página solicitada [404]",
            });
            $("#btn_guardar_auto").prop("disabled", false);
        } else if (jqXHR.status == 500) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Internal Server Error [500]",
            });
            $("#btn_guardar_auto").prop("disabled", false);
        } else if (textStatus === "parsererror") {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Error de análisis JSON solicitado.",
            });
            $("#btn_guardar_auto").prop("disabled", false);
        } else if (textStatus === "timeout") {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Time out error.",
            });
            $("#btn_guardar_auto").prop("disabled", false);
        } else if (textStatus === "abort") {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ajax request aborted.",
            });

            $("#btn_guardar_auto").prop("disabled", false);
        } else {
            alert("Uncaught Error: " + jqXHR.responseText);
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: `Uncaught Error: ${jqXHR.responseText}`,
            });
            $("#btn_guardar_auto").prop("disabled", false);
        }
    });
});



function BorrarVehiculo(id, placa) {
    console.log(id, " -> ", placa);
    Swal.fire({
        title: `Deseas Eliminar el Vehiculo con placas: \n \n ${placa}`,
        text: `\n Una vez Eliminado ¡no podras Recuperarlo!`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Eliminar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.value) {
            let dataForm = new FormData();
            dataForm.append("id", id);

            $.ajax({
                data: dataForm,
                url: `${urls}autos/borrar_vehiculo`,
                type: "post",
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response) {

                        tbl_carros.ajax.reload(null, false);

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
