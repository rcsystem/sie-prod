/**
 * ARCHIVO MODULO VIAJES
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */

$(document).ready(function () {
  tbl_viaticos = $("#tabla_usuario_viaticos").dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}viajes/mis-viaticos`,
        dataSrc: "",
      },
      lengthChange: true,
      ordering: true,
      responsive: true,
      autoWidth: false,
      rowId: "staffId",
      dom: "lBfrtip",
      buttons: [
        /* {
          extend: "excelHtml5",
          title: "Requisiciones",
          exportOptions: {
            columns: [0, 1, 2, 3, 4],
          },
        }, */
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
          data: "folio",
          title: "FOLIO",
          className: "text-center",
        },
        {
          data: "creacion",
          title: "FECHA CREACIÓN",
          className: "text-center",
        }, {
          data: "tipo_viaje",
          title: "VIAJE",
          className: "text-center",
        },
        {
          data: "destino",
          title: "DESTINO",
          className: "text-center",
        },
        {
          data: "avion",
          title: "AVION",
          className: "text-center",
        },
        {
          data: null,
          render: function (data) {
            var status = '';
            if (data["txt"] == null) {
              status = '<span class="badge badge-warning">Error</span>';
            } else {
              status = `<span class="badge" style="background-color: ${data["color"]}; color:#FFF;">${data["txt"]}</span>`;
            }
            return status;
          },
          title: "ESTADO",
          className: "text-center"
        },
        {
          data: null,
          render: function (data) {
            var status = '';
            if (data["txt"] == null) {
              status = '<span class="badge badge-warning">Error</span>';
            } else {
              status = `<span class="badge badge-${data["verification_color"]}" >${data["verification_txt"]}</span>`;
            }
            return status;
          },
          title: "COMPROBACION",
          className: "text-center"
        },

        {
          data: null,
          render: function (data, type, full, meta) {
            const color = (data["request_status"] == 2 && data["verification_status"] != 3) ? "outline-info" : "secondary";
            const href = (data["request_status"] == 2 && data["verification_status"] != 3) ? `href="${urls}/viajes/ver_datos_folio/1/${$.md5(key + data["folio"])}" target="_blank"` : "";

            return ` <div class="mr-auto">
              <a ${href} class="btn btn-${color} btn-sm">
                COMPROBAR
              </a>
            </div> `;
          },
          title: "ACCIONES",
          className: "text-center",
        },
      ],
      destroy: "true",
      /*  columnDefs: [
         {
           targets: 6,
          
         },
 
       ], */

      order: [[0, "DESC"]],

      createdRow: (row, data) => {
        $(row).attr("id", "travel_" + data.folio);
      },
    }).DataTable();
  $("#tabla_usuario_viaticos thead").addClass("thead-dark text-center");

  tbl_gastos = $("#tabla_usuario_gastos").dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}viajes/mis-gastos`, //requestMyExpenses
        dataSrc: "",
      },
      lengthChange: true,
      ordering: true,
      responsive: true,
      autoWidth: false,
      rowId: "staffId",
      dom: "lBfrtip",
      buttons: [
        /* {
          extend: "excelHtml5",
          title: "Requisiciones",
          exportOptions: {
            columns: [0, 1, 2, 3, 4],
          },
        }, */
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
          data: "folio",
          title: "FOLIO",
          className: "text-center"
        },
        {
          data: "creado",
          title: "FECHA CREACION",
          className: "text-center"
        },
        {
          data: "fechas",
          title: "INICIO -- FINAL",
          className: "text-center"
        },
        {
          data: "total",
          title: "MONTO SOLICITADO",
          className: "text-center"
        },

        {
          data: null,
          render: function (data) {
            var status = '';
            if (data["txt"] == null) {
              status = '<span class="badge badge-warning">Error</span>';
            } else {
              status = `<span class="badge" style="background-color: ${data["color"]}; color:#FFF;">${data["txt"]}</span>`;
            }
            return status;
          },
          title: "ESTADO",
          className: "text-center"
        },
        {
          data: null,
          render: function (data) {
            var status = '';
            if (data["txt"] == null) {
              status = '<span class="badge badge-warning">Error</span>';
            } else {
              status = `<span class="badge badge-${data["verification_color"]}" >${data["verification_txt"]}</span>`;
            }
            return status;
          },
          title: "COMPROBACION",
          className: "text-center"
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            const color = (data["request_status"] == 2 && data["verification_status"] != 3) ? "outline-warning" : "secondary";
            const href = (data["request_status"] == 2 && data["verification_status"] != 3) ? `href="${urls}/viajes/ver_datos_folio/2/${$.md5(key + data["folio"])}" target="_blank"` : "";

            return ` <div class="mr-auto">
              <a ${href} class="btn btn-${color} btn-sm">
                COMPROBAR
              </a>
            </div> `;
          },
          title: "ACCIONES",
          className: "text-center",
        },
      ],
      destroy: "true",
      /*  columnDefs: [
         {
           targets: 6,
          54202
         },
 
       ], */

      order: [[0, "DESC"]],

      createdRow: (row, data) => {
        $(row).attr("id", "expenses_" + data.folio);
      },
    }).DataTable();
  $("#tabla_usuario_gastos thead").addClass("thead-dark text-center");
});

function messageZip() {
  Swal.fire({
    icon: "error",
    title: "Oops...",
    text: "No se ha realizado Comprobacion de Gastos",
  });
}

function zipDownload(folio, tipo_gasto) {
  //e.preventDefault();
  console.log("folio", folio);
  console.log("tipo_gasto", tipo);
  let timerInterval = Swal.fire({
    //se le asigna un nombre al swal
    title: "Descargando Zip!",
    html: "Espere unos Segundos.",
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading(); //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });
  var tipo = tipo_gasto == 1 ? "Viatico" : "Gasto";
  var nomArchivo = `${tipo}_Folio_${folio}.zip`;
  var param = JSON.stringify({
    folio: folio,
    tipo_gasto: tipo_gasto,
  });
  var pathservicehost = `${urls}viajes/descargar_gasto_zip`;
  var xhr = new XMLHttpRequest();
  xhr.open("POST", pathservicehost, true);
  xhr.responseType = "blob";
  //Send the proper header information along with the request
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onload = function (e) {
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
      //$("#generarReporte").prop("disabled", false);
      Swal.close(timerInterval); // cierra el swal en ejecucion (nombre del swal)
      //link.click();
    } else {
      //$("#generarReporte").prop("disabled", false);

      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No es posible acceder al archivo, probablemente no existe. Comunicarse con el Administrador",
      });
    }
  };
  xhr.send(`data=${param}`);
}

function handleDeleteExpenses(folio) {
  Swal.fire({
    title: `Eliminar Solicitud de Gastos : ${folio}`,
    text: "Eliminar Solicitud de Gastos?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Eliminar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.value) {
      let dataForm = new FormData();
      dataForm.append("folio", folio);

      $.ajax({
        data: dataForm, //datos que se envian a traves de ajax
        url: `${urls}viajes/eliminar-gastos`, //archivo que recibe la peticion
        type: "post", //método de envio
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentType
        success: function (resp) {
          console.log(resp);
          if (resp) {
            //console.log("Hola Mundo Delete" + row);
            document.getElementById("expenses_" + folio).style.display = "none";
            $("tr.child").remove();
            Swal.fire("!Eliminado correctamente!", "", "success");
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
  });
}

function handleDeleteTravel(folio) {
  Swal.fire({
    title: `Eliminar Solicitud de Viaticos: ${folio}`,
    text: "Deseas Eliminar esta Solicitud de Viaticos!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Eliminar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.value) {
      let dataForm = new FormData();
      dataForm.append("folio", folio);

      $.ajax({
        data: dataForm, //datos que se envian a traves de ajax
        url: `${urls}viajes/eliminar-viaticos`, //archivo que recibe la peticion
        type: "post", //método de envio
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentType
        success: function (resp) {
          console.log(resp);
          if (resp) {
            //console.log("Hola Mundo Delete" + row);
            document.getElementById("travel_" + folio).style.display = "none";
            $("tr.child").remove();
            Swal.fire("!Eliminado correctamente!", "", "success");
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
  });
}

function handleAutorizeExpenses(id_folio) {
  $("#usuario").val("");
  $("#motivo").val("");
  $("#inicio").val("");
  $("#termino").val("");
  $("#presupuesto").val("");
  $("#listado_gastos").empty();
  $("#id_folio").val("");

  let data = new FormData();
  data.append("id_folio", id_folio);

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}viajes/editar_gasto`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    // async: true,
    dataType: "json",
    success: function (resp) {
      //console.log(resp);
      if (resp != false) {
        console.log(resp);
        const total_amount = new Intl.NumberFormat("es-MX").format(
          resp.info.total_amount
        );

        $("#autorizarGastoModal").modal("show");
        $("#usuario").val(resp.info.user);
        $("#motivo").val(resp.info.reasons);
        $("#inicio").val(resp.info.start_date);
        $("#termino").val(resp.info.end_date);
        $("#presupuesto").val(`$${total_amount}`);

        resp.items.forEach(function (valor, indice, array) {
          const total = new Intl.NumberFormat("es-MX").format(valor.amount);
          $("#listado_gastos").append(`
          <tr style="background:#fbfbfb">
                                          <td style="background:#e9ecef;font-weight:bold;font-size:16px;text-align:center;">${valor.definition}</td>
                                          <td style="background:#fbfbfb;font-weight:bold;text-align:center;font-size:16px;text-align:center;">$${total}</td>
                                      </tr>`);
        });
        $("#id_folio").val(id_folio);
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

function handleAutorizeTravel(id_folio) {
  $("#v_usuario").val("");
  $("#v_motivo").val("");
  $("#v_inicio").val("");
  $("#v_termino").val("");
  $("#v_presupuesto").val("");
  $("#v_listado_gastos").empty();
  $("#id_folio_v").val("");

  let data = new FormData();
  data.append("id_folio", id_folio);

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}viajes/editar_viaticos`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    // async: true,
    dataType: "json",
    success: function (resp) {
      //console.log(resp);
      if (resp != false) {
        console.log(resp);

        const total_amount = new Intl.NumberFormat("es-MX").format(
          resp.total_travel
        );

        $("#autorizarViaticoModal").modal("show");
        $("#v_usuario").val(resp.user_name);
        $("#v_motivo").val(resp.trip_details);
        $("#v_inicio").val(resp.start_of_trip);
        $("#v_termino").val(resp.return_trip);
        $("#v_presupuesto").val(`$${total_amount}`);
        $("#id_folio_v").val(id_folio);
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

$("#autorizar_gasto").submit(function (event) {
  event.preventDefault();

  const autorizacion = document.getElementById("autorizacion");
  const error_autorizacion =
    autorizacion.value.length == 0 ? "El campo es requerido" : "";
  console.log("tipo", error_autorizacion);
  document.getElementById("error_autorizacion").innerHTML = error_autorizacion;
  error_autorizacion.length > 0
    ? autorizacion.classList.add("has-error")
    : autorizacion.classList.remove("has-error");

  if (error_autorizacion != "") return false;

  $("#btn_autorizar_gasto").prop("disabled", true);
  let data = new FormData();

  data.append("id_folio", $("#id_folio").val());
  data.append("status", autorizacion.value);

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}viajes/autorizar_gasto`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    success: function (response) {
      //una vez que el archivo recibe el request lo procesa y lo devuelve
      console.log(response);
      /*codigo que borra todos los campos del form newProvider*/

      if (response) {
        setTimeout(function () {
          tbl_gastos.ajax.reload(null, false);
        }, 100);
        $("#btn_autorizar_gasto").prop("disabled", false);
        $("#autorizarGastoModal").modal("toggle");
        Swal.fire("!Los datos se han Actualizado!", "", "success");
      } else {
        $("#btn_autorizar_gasto").prop("disabled", false);
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
  }).fail(function (jqXHR, textStatus, errorThrown) {
    if (jqXHR.status === 0) {
      $("#btn_autorizar_gasto").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Fallo de conexión: ​​Verifique la red.",
      });
    } else if (jqXHR.status == 404) {
      $("#btn_autorizar_gasto").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No se encontró la página solicitada [404]",
      });
    } else if (jqXHR.status == 500) {
      $("#btn_autorizar_gasto").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Internal Server Error [500]",
      });
    } else if (textStatus === "parsererror") {
      $("#btn_autorizar_gasto").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Error de análisis JSON solicitado.",
      });
    } else if (textStatus === "timeout") {
      $("#btn_autorizar_gasto").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Time out error.",
      });
    } else if (textStatus === "abort") {
      $("#btn_autorizar_gasto").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ajax request aborted.",
      });
    } else {
      alert("Uncaught Error: " + jqXHR.responseText);
      $("#btn_autorizar_gasto").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: `Uncaught Error: ${jqXHR.responseText}`,
      });
    }
  });
});

$("#autorizar_viaticos").submit(function (event) {
  event.preventDefault();

  const autorizacion = document.getElementById("v_autorizacion");
  const error_autorizacion =
    autorizacion.value.length == 0 ? "El campo es requerido" : "";
  console.log("tipo", error_autorizacion);
  document.getElementById("error_autorizacion_v").innerHTML =
    error_autorizacion;
  error_autorizacion.length > 0
    ? autorizacion.classList.add("has-error")
    : autorizacion.classList.remove("has-error");

  if (error_autorizacion != "") return false;

  $("#btn_autorizar_viaticos").prop("disabled", true);
  let data = new FormData();

  data.append("id_folio", $("#id_folio_v").val());
  data.append("status", autorizacion.value);

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}viajes/autorizar_viaticos`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    success: function (response) {
      //una vez que el archivo recibe el request lo procesa y lo devuelve
      console.log(response);
      /*codigo que borra todos los campos del form newProvider*/

      if (response) {
        setTimeout(function () {
          tbl_viaticos.ajax.reload(null, false);
        }, 100);
        $("#btn_autorizar_viaticos").prop("disabled", false);
        $("#autorizarViaticoModal").modal("toggle");
        Swal.fire("!Los datos se han Actualizado!", "", "success");
      } else {
        $("#btn_autorizar_viaticos").prop("disabled", false);
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
  }).fail(function (jqXHR, textStatus, errorThrown) {
    if (jqXHR.status === 0) {
      $("#btn_autorizar_viaticos").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Fallo de conexión: ​​Verifique la red.",
      });
    } else if (jqXHR.status == 404) {
      $("#btn_autorizar_viaticos").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No se encontró la página solicitada [404]",
      });
    } else if (jqXHR.status == 500) {
      $("#btn_autorizar_viaticos").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Internal Server Error [500]",
      });
    } else if (textStatus === "parsererror") {
      $("#btn_autorizar_viaticos").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Error de análisis JSON solicitado.",
      });
    } else if (textStatus === "timeout") {
      $("#btn_autorizar_viaticos").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Time out error.",
      });
    } else if (textStatus === "abort") {
      $("#btn_autorizar_viaticos").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ajax request aborted.",
      });
    } else {
      alert("Uncaught Error: " + jqXHR.responseText);
      $("#btn_autorizar_viaticos").prop("disabled", false);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: `Uncaught Error: ${jqXHR.responseText}`,
      });
    }
  });
});

function testAnim(e) {
  $(".modal .gastos").attr("class", `modal-dialog modal-xl ${e} animated`);
}

$("#autorizarGastoModal").on("show.bs.modal", function (e) {
  $("html, body").css("overflow", "hidden");
  var anim = "bounceInRight";
  testAnim(anim);
});
$("#autorizarGastoModal").on("hide.bs.modal", function (e) {
  $("html, body").css("overflow", "scroll");
  var anim = "slideOutLeft";
  testAnim(anim);
});

function testAnimPermisos(e) {
  $(".modal .viaticos").attr("class", `modal-dialog modal-xl ${e} animated`);
}

$("#autorizarViaticoModal").on("show.bs.modal", function (e) {
  var anim = "bounceInRight";
  testAnimPermisos(anim);
});
$("#autorizarViaticoModal").on("hide.bs.modal", function (e) {
  var anim = "slideOutLeft";
  testAnimPermisos(anim);
});
