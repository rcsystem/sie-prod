/**
 * ARCHIVO MODULO SYSTEMA / EQUIPOS
 * AUTOR:HORUS SAMAEL RIVAS PEDRAZA
 * horus.riv.ped@gmail.com
 * CEL:56 2439 2632
 */

function validar() {
    if ($("#fecha_inicio").val().length >= 0) {
        $("#fecha_inicio").removeClass('has-error');
        $("#error_fecha_inicio").text("");
    }
    if ($("#fecha_fin").val().length >= 0) {
        $("#fecha_fin").removeClass('has-error');
        $("#error_fecha_fin").text("");
    }
}

function tipoReporte() {
    $("#opcion").removeClass('has-error');
    $("#error_opcion").text("");
    $("#equipos").empty();
    if ($("#opcion").val().length == 0) {
        return false;
    }
    if ($("#opcion").val() == 1) {
        $("#equipos").append(`
    <label for="tipo_equipo">Estado de Equipo:</label>
    <select name="tipo_equipo" id="tipo_equipo" class="form-control" >
    <option value="0">Almacenados</option>
    <option value="1">Asignados</option>
    <option value="2">Refacciones</option>
    <option value="3">Obsoletos</option>
    <option value="4">Todos</option>
    </select>
    `);
    }
}

$("#reportes").submit(function (e) {
    e.preventDefault();

    /* if ($("#opcion").val().length == 0) {
        error_opcion = "Campo Requerido";
        $("#opcion").addClass('has-error');
        $("#error_opcion").text(error_opcion);
    }

    if ($("#fecha_inicio").val().length == 0) {
        error_fecha_inicio = "Campo Requerido";
        $("#fecha_inicio").addClass('has-error');
        $("#error_fecha_inicio").text(error_fecha_inicio);
    }
    if ($("#fecha_fin").val().length == 0) {
        error_fecha_fin = "Campo Requerido";
        $("#fecha_fin").addClass('has-error');
        $("#error_fecha_fin").text(error_fecha_fin);
    } else if ($("#fecha_inicio").val() >= $("#fecha_fin").val()) {
        error_fecha_fin = "La Fecha Final debe ser mayo a la Fecha de Inico.";
        $("#fecha_fin").addClass('has-error');
        $("#error_fecha_fin").text(error_fecha_fin);
    }

    if ( error_fecha_inicio != ""
        || error_fecha_fin != ""
    ) {
        return false;
    } */
    $("#btn_reportes").prop("disabled", true);
    var nomArchivo = `Reporte_Equipos.xlsx`;
    var param = JSON.stringify({
        fechaInicio: ''
    }); 
    var pathservicehost = `${urls}sistemas/excel_equipos_asignados`;
    var xhr = new XMLHttpRequest();
    xhr.open("POST", pathservicehost, true);
    xhr.responseType = "blob";
    //Send the proper header information along with the request
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function (e) {
        $("#btn_reportes").prop("disabled", false);
        if (xhr.readyState === 4 && xhr.status === 200) {
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
            $("#equipos").empty();
            //link.click();
        } else {

            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No es posible acceder al archivo, probablemente no existe. Comunicarse con el Administrador",
            });
        }
    };
    xhr.send("data=" + param);
});