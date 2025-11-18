/*
 * ARCHIVO MODULO ESTACIONAMIENTO
 * AUTOR:Horus Samael Rivas Pedraza
 * EMAIL:horus.riv.ped@gmail.com
 * CEL: 56 2439 2632
 */

// const asignation = document.getElementById("asignacion");

$("#form_reporte").on("submit", function (e) {
    e.preventDefault();
    var fechaInicial = document.getElementById("fecha_inicial");
    var fechaFinal = document.getElementById("fecha_final");
    var opcion = document.getElementById("opcion");

    var error_fecha_inicial = "";
    var error_fecha_final = "";
    var error_opcion = "";

    if (fechaInicial.value.length == 0) {
        error_fecha_inicial = "Fecha Inicial Requerida";
        document.getElementById("error_fecha_inicial").textContent = error_fecha_inicial;
        fechaInicial.classList.add("has-error");
    } else {
        document.getElementById("error_fecha_inicial").textContent = error_fecha_inicial;
        fechaInicial.classList.remove("has-error");
    }

    if (fechaFinal.value.length == 0) {
        error_fecha_final = "Fecha Final Requerida";
        document.getElementById("error_fecha_final").textContent = error_fecha_final;
        fechaFinal.classList.add("has-error");
    } else if (fechaFinal.value < fechaInicial.value) {
        error_fecha_final = "Fecha Final Incorrecta";
        document.getElementById("error_fecha_final").textContent = error_fecha_final;
        fechaFinal.classList.add("has-error");
    } else {
        document.getElementById("error_fecha_final").textContent = error_fecha_final;
        fechaFinal.classList.remove("has-error");
    }

    if (opcion.value.length == 0) {
        error_opcion = "Fecha Inicial Requerida";
        document.getElementById("error_opcion").textContent = error_opcion;
        opcion.classList.add("has-error");
    } else {
        document.getElementById("error_opcion").textContent = error_opcion;
        opcion.classList.remove("has-error");
    }

    if (error_fecha_inicial != "" || error_fecha_final != "" || error_opcion != "") { return false; }
    document.getElementById("generar_reporte").disabled = true;

    let timerInterval = Swal.fire({ //se le asigna un nombre al swal
        allowOutsideClick: false,
        title: '¡Generando Reporte!',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
        },
    });


    var nomArchivo = `Reporte_Estacionamiento_${fechaInicial.value}_${fechaFinal.value}.xlsx`;
    var param = JSON.stringify({
        date_star: fechaInicial.value,
        date_end: fechaFinal.value,
        option : opcion.value,
    });
    var pathservicehost = `${urls}/estacionamiento/descargar_reportes`;
    console.log(pathservicehost);
    // var pathservicehost = `${urls}/medico/excel_incapacidad`;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", pathservicehost, true);
    xhr.responseType = "blob";

    //Send the proper header information along with the request
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function (e) {
        Swal.close(timerInterval);
        document.getElementById("generar_reporte").disabled = false;
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById("form_reporte").reset();
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

function limpiarError(id_field) {
    var fecha = document.getElementById(id_field);
    if (fecha.value.length > 0) {
        document.getElementById("error_" + id_field).textContent = "";
        fecha.classList.remove("has-error");
    }
}