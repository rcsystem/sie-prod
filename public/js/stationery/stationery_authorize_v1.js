/**
 * ARCHIVO MODULO PAPELERIA
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
$(document).ready(function () {
    tbl_stationery = $("#tabla_autorizar_pape")
        .dataTable({
            processing: true,
            ajax: {
                method: "post",
                url: urls + "papeleria/autorizar-papeleria",
                dataSrc: "",
            },
            lengthChange: true,
            ordering: true,
            responsive: true,
            autoWidth: true,
            rowId: "staffId",
            dom: "lftrip",
        
            language: {
                url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
            },
            columns: [
                {
                    data: "id_request",
                    title: "Folio",
                    className: "text-center",
                },
                {
                    data: null,
                    render: function (data, type, full, meta) {
                        var objFechaCreacion = new Date(data["created_at"]);
                        var dia = (objFechaCreacion.getDate() ).toString().padStart(2, "0");
                        var mes = (objFechaCreacion.getMonth() + 1)
                            .toString()
                            .padStart(2, "0");
                        if (dia == 32) {
                            dia = "01"; var mes = (objFechaCreacion.getMonth() + 2)
                                .toString()
                                .padStart(2, "0");
                        }
                        var anio = objFechaCreacion.getFullYear();
                        // Devuelve: '1/2/2011':
                        let fecha_creacion = dia + "-" + mes + "-" + anio;
                        return $.trim(data["fecha_creacion"]) === "0000-00-00"
                            ? "---"
                            : ` <div class="mr-auto">${fecha_creacion} </div> `;
                    },
                    title: "Creación",
                    className: "text-center",
                },
                {
                    data: "name",
                    title: "Usuario",
                },
                {
                    data: null,
                    render: function (data, type, full, meta) {
                        const estatus = ["Error", "Pendiente", "Autorizado", "Completado", "Rechazada"];
                        const badge = ["warning", "warning", "info", "success", "danger"];

                        if (data["request_status"] < 1 && data["request_status"] > 4) {
                            return `<span class="badge badge-warning">Error</span>`;
                        }

                        return `<span class="badge badge-${badge[data["request_status"]]}">${estatus[data["request_status"]]}</span>`;

                    },
                    title: "Estatus",
                    className: "text-center",
                },
                {
                    data: null,
                    title: "Acciones",
                    className: "text-center",
                },
            ],
            destroy: "true",
            columnDefs: [
                {
                    targets: 4,
                    render: function (data, type, full, meta) {
                        if (data["estatus"] != "Pendiente") {
                            return `<div class=" mr-auto">
               <div class="btn-group" role="group">
               <button id="btnGroupDropPermisos" type="button" class="btn btn-primary btn-sm"  onClick=handleAuthorize(${data["id_request"]
                                })>
               <i class="fas fa-user-check"></i>
               </button>
               
             </div>
             <a href="${urls}papeleria/ver-requisicion/${$.md5(key + data["id_request"])}" title="Ver Requisición" target="_blank" class="btn btn-info btn-sm">
              <i class="fas fa-eye"></i>
        </a>
               </div> `;
                        } else {
                            return `<div class=" mr-auto">
               <button type="button" class="btn btn-primary btn-sm" title="Autorizar Permiso" onClick=handleChange(${data["id_request"]
                                })>
               <i class="fas fa-user-check"></i>
         </button>
         <a href="${urls}papeleria/ver-requisicion/${$.md5(key + data["id_request"])}" title="Ver Requisición" target="_blank" class="btn btn-info btn-sm">
          <i class="fas fa-eye"></i>
        </a>
            </div> `;
                        }
                    },
                },
                /* {
                 targets: [0],
                 visible: false,
                 searchable: false,
               },   */
            ],

            order: [[0, "DESC"]],

            createdRow: (row, data) => {
                $(row).attr("id", "pape_" + data.id_request);
            },
        })
        .DataTable();
    $("#tabla_autorizar_pape thead").addClass("thead-dark text-center");
});

function handleAuthorize(id_request) {
    let data = new FormData();
    $("#table").empty();
    data.append("id_request", id_request);

    $.ajax({
        data: data, //datos que se envian a traves de ajax
        url: `${urls}papeleria/autorizar_pape`, //archivo que recibe la peticion
        type: "post", //método de envio
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentType
        async: true,
        dataType: "json",
        beforeSend: function () {
            /*
           * Se ejecuta al inicio de la petición
           * */
            $('#loaderGif').show();
        },

        success: function (resp) {
            console.log(resp);
            if (resp != false) {


                let folio = $(`#pape_${id_request} td`)[0].innerHTML;
                let nombre = $(`#pape_${id_request} td`)[2].innerHTML;

                $("#id_folio").val(folio);
                $("#usuario").val(nombre);

                for (var arreglo in resp) {
                    //alert(" arreglo2 = " + arreglo);
                    $("#table").append(`<tr style="background:#e9ecef;">
                                         <td colspan="2" style="font-weight:bold;font-size:16px;">${arreglo}</td>
                                        </tr>`);
                    for (var elemento in resp[arreglo]) {

                        element = resp[arreglo][elemento];
                        if (element <= 9 && element >= 0) { e = "0" + element } else { e = element };
                        $("#table").append(`<tr style="background:#fbfbfb">
                                            <td style="font-weight:bold;font-size:16px;">${elemento}</td>
                                            <td style="font-weight:bold;text-align:center;font-size:16px;">${e}</td>
                                        </tr>`);
                    }
                }


                $("#autorizarModal").modal("show");
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
                console.log("Mal Revisa");
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


$("#autorizar_pape").submit(function (event) {
    event.preventDefault();
    $("#autoriza_pape").prop("disabled", true);

    let data = new FormData();

    data.append("id_folio", $("#id_folio").val());
    data.append("autorizacion", $("#estatus").val());

    $.ajax({
        data: data, //datos que se envian a traves de ajax
        type: "post", //método de envio
        url: `${urls}papeleria/autorizacion`, //archivo que recibe la peticion
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentType
        success: function (response) {
            //una vez que el archivo recibe el request lo procesa y lo devuelve
            //console.log(response);
            /*codigo que borra todos los campos del form newProvider*/
            if (response) {
                setTimeout(function () {
                    tbl_stationery.ajax.reload(null, false);
                }, 100);
                $("#autoriza_pape").prop("disabled", false);
                $("#estatus").val("");
                $("#autorizarModal").modal("toggle");
                Swal.fire("!Los datos se han Actualizado!", "", "success");
            } else {
                $("#autoriza_pape").prop("disabled", false);
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador",
                });
            }
        },
        error: function (jqXHR, status, error) {
            $("#autoriza_pape").prop("disabled", false);
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Algo salió Mal! Contactar con el Administrador",
            });
            console.log("Mal Revisa entro en el error: " + error);
        },
    });
});