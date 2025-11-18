/**
 * ARCHIVO MODULO PAPELERIA
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
function validar() {
    if ($("#categoria").val().length > 0) {
        $("#error_categoria").text("");
        $("#categoria").removeClass('has-error');
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

$("#formReportes").on("submit", function (e) {
    e.preventDefault();
    if ($("#categoria").val().length == 0) {
        error_categoria = "Categoria Requerida";
        $("#error_categoria").text(error_categoria);
        $("#categoria").addClass('has-error');
    } else {
        error_categoria = "";
        $("#error_categoria").text(error_categoria);
        $("#categoria").removeClass('has-error');
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

    if (
        error_categoria != "" ||
        error_fecha_inicial != "" ||
        error_fecha_final != ""
    ) {
        return false;
    }
    $("#generar_reporte").prop("disabled", true);

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
    let categoria = $("#categoria").val();
    let categorias = ['error', 'Administrativos', 'Sindicalizados', 'Grupo Walworth', 'Todos los Contratos'];
    var nomArchivo = `Contratos_${categorias[categoria]}_${fecha_inicio}_${fecha_fin}.xlsx`;
    var param = JSON.stringify({
        fecha_inicio: fecha_inicio,
        fecha_fin: fecha_fin,
        categoria: categoria,
    });
    var pathservicehost = `${urls}/usuarios/genera_reportes_contratos`;

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
            $("#categoria").val("");
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

$("#categoria").on("change", () => {
    let categoria = $("#categoria").val();
    if (categoria == 2) {
        $("#parametro").empty();
        $("#parametro").addClass("col-md-3");
        campo = ` <label for="descripcion">Numero de Nomina</label>
                 <input type="number" class="form-control rounded-0" id="num_nomina" name="num_nomina" value="" onchange="validar()" >
                 <div id="error_opcion" name="error_opcion" class="text-danger"></div>`;
        $("#parametro").append(campo);
    } else if (categoria == 1) {
        $("#parametro").empty();

        $("#parametro").addClass("col-md-3");
        campo = `<label for="ingenieria">Departamento:</label>
         <select name="depto" id="depto" class="form-control rounded-0" onchange="validar()"></select>
         <div id="error_opcion" name="error_opcion" class="text-danger"></div>   `;
        $("#parametro").append(campo);
        $.ajax({
            // data: data, //datos que se envian a traves de ajax
            url: `${urls}permisos/departamentos`, //archivo que recibe la peticion
            type: "post", //método de envio
            processData: false, // dile a jQuery que no procese los datos
            contentType: false, // dile a jQuery que no establezca contentType
            dataType: "json",
            success: function (resp) {
                // console.log(resp);
                // Limpiamos el select
                //puestos.find("option").remove();
                $("#depto").append('<option value="">Seleccionar...</option>');
                $.each(resp, function (id, value) {
                    $("#depto").append(
                        '<option value="' +
                        value.cost_center +
                        '">' +
                        value.departament +
                        "</option>"
                    );
                });
            },
            error: function () {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Ocurrio un error en el servidor! Contactar con el Administrador",
                });
            },
        });
    } else if (categoria == 3) {

        $("#parametro").empty();
        $("#parametro").removeClass("col-md-3");
    }
});
