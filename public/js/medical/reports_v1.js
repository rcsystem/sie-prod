/*
 * ARCHIVO MODULO SERVICIO MEDICO
 * AUTOR:Horus Samael Rivas Pedraza
 * EMAIL:horus.riv.ped@gmail.com
 * CEL: 56 2439 2632
 */
// const asignation = document.getElementById("asignacion");

$(document).ready(function () {
    // asignation.style.display = "none";
    $('#depto').select2({
        placeholder: "Selecciona una Opción",
    });
});

$("#form_reportes_consultas").on("submit", function (e) {
    e.preventDefault();
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

    if (error_fecha_inicial != "" || error_fecha_final != "") { return false; }
    $("#generar_reporte").prop("disabled", true);

    let timerInterval = Swal.fire({ //se le asigna un nombre al swal
        allowOutsideClick: false,
        title: '¡Generando Reporte!',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });

    switch (parseInt($("#tipo").val())) {
        case 1: opcion = ""; break;
        case 2: opcion = $("#nomina").val(); break;
        case 3: opcion = $("#turno").val(); break;
        case 4: opcion = $("#depto").val(); break;
        case 5: opcion = $("#tipo_atencion").val(); break;
        case 6: opcion = $("#clasificacion").val(); break;
        case 7: opcion = $("#system").val(); break;
        default: 
        Swal.close(timerInterval);
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "No es posible acceder al archivo, probablemente no existe.",
        });
        opcion = 'error';
        break;
    }
    let fecha_inicio = $("#fecha_inicial").val();
    let fecha_fin = $("#fecha_final").val();
    var nomArchivo = `Reporte_Consultas_Servicio_Medico_${fecha_inicio}_${fecha_fin}.xlsx`;
    var param = JSON.stringify({
        date_star: fecha_inicio,
        date_end: fecha_fin,
        option: opcion,
        type: $("#tipo").val(),
    });
    var pathservicehost = `${urls}/medico/excel_consultas`;
    // var pathservicehost = `${urls}/medico/excel_incapacidad`;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", pathservicehost, true);
    xhr.responseType = "blob";

    //Send the proper header information along with the request
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function (e) {
        Swal.close(timerInterval);
        $("#generar_reporte").prop("disabled", false);
        if (xhr.readyState === 4 && xhr.status === 200) {
            $("#fecha_inicial").val("");
            $("#fecha_final").val("");
            $("#tipo").val(1)
            $("#div_1").hide();
            $("#div_2").hide();
            $("#div_3").hide();
            $("#div_4").hide();
            $("#div_5").hide();
            $("#div_6").hide();
            $("#div_7").hide();
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
            // alert(" No es posible acceder al archivo, probablemente no existe.");
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No es posible acceder al archivo, probablemente no existe.",
            });
        }
    };
    xhr.send("data=" + param);
});

$("#tipo").on('change', function () {
    $("#div_2").hide();
    $("#div_3").hide();
    $("#div_4").hide();
    $("#div_5").hide();
    $("#div_6").hide();
    $("#div_7").hide();
    $("#nomina").val("");
    $("#turno").val("");
    $("#depto").val("");
    $("#tipo_atencion").val("");
    $("#clasificacion").val("");
    $("#system").val("");
    $("#nomina").attr("required", false);
    $("#turno").attr("required", false);
    $("#depto").attr("required", false);
    $("#tipo_atencion").attr("required", false);
    $("#clasificacion").attr("required", false);
    $("#system").attr("required", false);
    switch (parseInt($("#tipo").val())) {
        case 2:
            $("#div_2").show();
            $("#nomina").attr("required", true); break;
        case 3:
            $("#div_3").show();
            $("#turno").attr("required", true); break;
        case 4:
            $("#div_4").show();
            $("#depto").attr("required", true); break;
        case 5:
            $("#div_5").show();
            $("#tipo_atencion").attr("required", true); break;
        case 6:
            $("#div_6").show();
            $("#clasificacion").attr("required", true); break;
        case 7:
            $("#div_7").show();
            $("#system").attr("required", true); break;

        default:
            break;
    }
});

function validar() {
    if ($("#fecha_inicial").val().length > 0) {
        $("#error_fecha_inicial").text("");
        $("#fecha_inicial").removeClass('has-error');
    }
    if ($("#fecha_final").val().length > 0) {
        $("#error_fecha_final").text("");
        $("#fecha_final").removeClass('has-error');
    }
}

function validar_2() {
    if ($("#fecha_inicial_2").val().length > 0) {
        $("#error_fecha_inicial_2").text("");
        $("#fecha_inicial_2").removeClass('has-error');
    }
    if ($("#fecha_final_2").val().length > 0) {
        $("#error_fecha_final_2").text("");
        $("#fecha_final_2").removeClass('has-error');
    }
}

$("#formReportes").on("submit", function (e) {
    e.preventDefault();
    if ($("#fecha_inicial_2").val().length == 0) {
        error_fecha_inicial_2 = "Fecha Inicial Requerida";
        $("#error_fecha_inicial_2").text(error_fecha_inicial_2);
        $("#fecha_inicial_2").addClass('has-error');
    } else {
        error_fecha_inicial_2 = "";
        $("#error_fecha_inicial_2").text(error_fecha_inicial_2);
        $("#fecha_inicial_2").removeClass('has-error');
    }

    if ($("#fecha_final_2").val().length == 0) {
        error_fecha_final_2 = "Fecha Final Requerida";
        $("#error_fecha_final_2").text(error_fecha_final_2);
        $("#fecha_final_2").addClass('has-error');
    } else if ($("#fecha_final_2").val() < $("#fecha_inicial_2").val()) {
        error_fecha_final_2 = "Fecha Final Incorrecta";
        $("#error_fecha_final_2").text(error_fecha_final_2);
        $("#fecha_final_2").addClass('has-error');
    } else {
        error_fecha_final_2 = "";
        $("#error_fecha_final_2").text(error_fecha_final_2);
        $("#fecha_final_2").removeClass('has-error');
    }

    if (error_fecha_inicial_2 != "" || error_fecha_final_2 != "") { return false; }
    $("#generar_reporte_2").prop("disabled", true);

    let timerInterval = Swal.fire({ //se le asigna un nombre al swal
        allowOutsideClick: false,
        title: '¡Generando Reporte!',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });
    let fecha_inicio = $("#fecha_inicial_2").val();
    let fecha_fin = $("#fecha_final_2").val();
    var nomArchivo = `Reporte_Incapacidades_Medicas_${fecha_inicio}_${fecha_fin}.xlsx`;
    var param = JSON.stringify({
        fecha_inicio: fecha_inicio,
        fecha_fin: fecha_fin
    });
    var pathservicehost = `${urls}/medico/excel_incapacidad`;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", pathservicehost, true);
    xhr.responseType = "blob";

    //Send the proper header information along with the request
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function (e) {
        Swal.close(timerInterval);
        $("#generar_reporte_2").prop("disabled", false);
        if (xhr.readyState === 4 && xhr.status === 200) {
            $("#fecha_inicial_2").val("");
            $("#fecha_final_2").val("");
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
