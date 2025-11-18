/**
 * ARCHIVO MODULO VIAJES
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EDITOR:HORUS RIVAS PEDRAZA
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */

$(document).ready(function () {
  tbl_viaticos = $("#tabla_autorizar_viaticos").dataTable({
    processing: true,
    ajax: {
      method: "post",
      url: `${urls}viajes/datos_autoriza_viaticos`,
      dataSrc: "",
    },
    lengthChange: true,
    ordering: true,
    responsive: true,
    autoWidth: false,
    rowId: "staffId",
    dom: "lfrtip",
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
        data: "creacion",
        title: "FECHA CREACIÓN",
        className: "text-center"
      },
      {
        data: "grado",
        title: "GRADO",
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
        data: "fechas",
        title: "INICIO -- FINAL",
        className: "text-center"
      },
      {
        data: "tipo_viaje",
        title: "TIPO",
        className: "text-center"
      },
      {
        data: "avion",
        title: "AVION",
        className: "text-center"
      },
      {
        data: "destino",
        title: "DESTINO",
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
        render: function (data, type, full, meta) {
          const color = (data["request_status"] == 1) ? 'outline-primary' : 'secondary';
          const onclick = (data["request_status"] == 1) ? `onClick="handleAutorizeTravel(${data["folio"]})"` : '';
          // Director-> 252
          btnAuthorize = (data["type_travel"] == 1 && data["access_direct"] != 'true')
            ? ``
            : ``;
          const btnAuthorizeVictor = (data["access_direct"] == 'true')
            ? `<button type="button" class="btn btn-${color} btn-sm" title="Autorizar Viaticos" ${onclick}>
                <i class="fas fa-user-check"></i>
              </button>`
            : ``;
          return ` <div class="mr-auto">
            <button type="button" class="btn btn-${color} btn-sm" title="Autorizar Viaticos" ${onclick}>
              <i class="fas fa-user-check"></i>
            </button>
            <!-- ${btnAuthorizeVictor} -->
          </div> `;
        },
        title: "ACCIONES",
        className: "text-center"
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
  $('#tabla_autorizar_viaticos thead').addClass('thead-dark text-center');

  tbl_gastos = $("#tabla_autorizar_gastos").dataTable({
    processing: true,
    ajax: {
      method: "post",
      url: `${urls}viajes/datos_autoriza_gastos`,
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
        render: function (data, type, full, meta) {
          const color = (data["request_status"] == 1) ? 'outline-primary' : 'secondary';
          const onclick = (data["request_status"] == 1) ? `onClick="onClick=handleAutorizeExpenses(${data["folio"]})"` : '';

          return ` <div class="mr-auto">
            <button type="button" class="btn btn-${color} btn-sm" title="Autorizar Gastos" ${onclick}>
              <i class="fas fa-user-check"></i>
            </button>
          </div> `;
        },
        title: "ACCIONES",
        className: "text-center"
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
  $('#tabla_autorizar_gastos thead').addClass('thead-dark text-center');
});

function messageZip() {
  Swal.fire({
    icon: "Warning",
    title: "Oops...",
    text: "No se ha realizado Comprobacion de Gastos",
  });
}

function zipDownload(folio, tipo_gasto) {
  //e.preventDefault();
  console.log("folio", folio);
  console.log("tipo_gasto", tipo);
  let timerInterval = Swal.fire({ //se le asigna un nombre al swal
    title: 'Descargando Zip!',
    html: 'Espere unos Segundos.',
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });
  var tipo = (tipo_gasto == 1) ? "Viatico" : "Gasto";
  var nomArchivo = `${tipo}_Folio_${folio}.zip`;
  var param = JSON.stringify({
    folio: folio,
    tipo_gasto: tipo_gasto
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

/* function handleDeleteExpenses(folio) {
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
        url: `${urls}viajes/eliminar-gastos`,//archivo que recibe la peticion
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
} */

/* function handleDeleteTravel(folio) {
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
        url: `${urls}viajes/eliminar-viaticos`,//archivo que recibe la peticion
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
} */

function handleAutorizeExpenses(id_folio) {
  Swal.fire({
    title: '<i class="fas fa-file-signature" style="margin-right: 10px;"></i>¿Autorizar Solicitud de Gasto?',
    showDenyButton: true,
    showCancelButton: true,
    confirmButtonText: '<i class="fas fa-check" style="margin-right: 10px;"></i>Confirmar',
    confirmButtonColor: "#28A745",
    denyButtonText: `<i class="fas fa-times" style="margin-right: 10px;"></i>Rechazar`,
    cancelButtonText: `Atras`,
  }).then((result) => {
    if (result.isConfirmed) {
      actualizarRequest(2, id_folio, 2)
    } else if (result.isDenied) {
      actualizarRequest(3, id_folio, 2)
    }
  })
}

function handleAutorizeTravel(id_folio) {
  Swal.fire({
    title: '<i class="fas fa-file-signature" style="margin-right: 10px;"></i>¿Autorizar Solicitud de Viatico?',
    showDenyButton: true,
    showCancelButton: true,
    confirmButtonText: '<i class="fas fa-check" style="margin-right: 10px;"></i>Confirmar',
    confirmButtonColor: "#28A745",
    denyButtonText: `<i class="fas fa-times" style="margin-right: 10px;"></i>Rechazar`,
    cancelButtonText: `Atras`,
  }).then((result) => {
    if (result.isConfirmed) {
      actualizarRequest(2, id_folio, 1)
    } else if (result.isDenied) {
      actualizarRequest(3, id_folio, 1)
    }
  })
}

function actualizarRequest(status, id, type) {
  let timerInterval = Swal.fire({ //se le asigna un nombre al swal
    title: '<i class="fas fa-envelope-open-text" style="margin-right: 10px;"></i>¡Notificando a Recursos Humanos!',
    html: 'Espere unos Segundos.',
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });
  const route = (type == 1) ? 'autorizar_viaticos' : 'autorizar_gasto';
  const dataToUpdate = new FormData();
  dataToUpdate.append('status', status);
  dataToUpdate.append('id_folio', id);

  $.ajax({
    type: "post",
    url: `${urls}viajes/${route}`,
    data: dataToUpdate,
    cache: false,
    dataType: "json",
    contentType: false,
    processData: false,
    success: function (save) {
      Swal.close(timerInterval);
      if (save === true) {
        if (type == 1) {
          tbl_viaticos.ajax.reload(null, false);
        } else {
          tbl_gastos.ajax.reload(null, false);
        }
        Swal.fire({
          icon: 'success',
          title: "¡Actualizacion Exitosa!",
          text: 'Se registró el cambio en la Solicitud',
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

function testAnim(e) {
  $('.modal .gastos').attr('class', `modal-dialog modal-xl ${e} animated`);
};

$('#autorizarGastoModal').on('show.bs.modal', function (e) {
  $("html, body").css("overflow", "hidden");
  var anim = "bounceInRight";
  testAnim(anim);
})
$('#autorizarGastoModal').on('hide.bs.modal', function (e) {
  $("html, body").css("overflow", "scroll");
  var anim = "slideOutLeft";
  testAnim(anim);
})



function testAnimPermisos(e) {
  $('.modal .viaticos').attr('class', `modal-dialog modal-xl ${e} animated`);
};

$('#autorizarViaticoModal').on('show.bs.modal', function (e) {
  var anim = "bounceInRight";
  testAnimPermisos(anim);
})
$('#autorizarViaticoModal').on('hide.bs.modal', function (e) {
  var anim = "slideOutLeft";
  testAnimPermisos(anim);
})