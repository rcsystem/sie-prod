/**
 * ARCHIVO MODULO PERMISSIONS
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
$(document).ready(function () {
  tbl_ordenes = $("#tabla_ordenes_sunimistros")
    .dataTable({
      
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}suministros/listar-ordenes`,
        dataSrc: "",
      },
      lengthChange: true,
      ordering: true,
      responsive: true,
      autoWidth: true,
      rowId: "staffId",
      dom: "lBfrtip",
      buttons: [
         /* {
        {
          extend: "excelHtml5",
          title: "Permisos",
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 0, 7],
          },
        },
       
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
          data: null,
          title: "",
          className: "text-center",
        },
        {
          data: "id_request",
          title: "FOLIO",
          className: "text-center",
        },

        {
          data: "usuario",
          title: "USUARIO",
          className: "text-center",
        },
        {
          data: "orden_compra",
          title: "ORDEN COMPRA",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            switch (data["orden_status"]) {
              case "1":
                return `<span class="badge badge-success">ABIERTA</span>`;
                break;
              case "2":
                return `<span class="badge badge-primary">CERRADA</span>`;
                break;

              default:
                return `<span class="badge badge-warning">ERROR</span>`;
                break;
            }
          },
          title: "ESTATUS",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {

            return `<div class=" mr-auto">
               <button type="button" class="btn btn-outline-success btn-sm btn-flat" title="Orden" onClick=listItems(${data["id_request"]})>
               <i class="fas fa-tasks"></i>
              </button>
                <button type="button" class="btn btn-outline-primary btn-sm btn-flat" title="Editar Orden" onClick=editOrden(${data["id_request"]})>
                <i class="fas fa-edit"></i>
                </button>
              <a href="${urls}suministros/ver-orden/${$.md5(key + data["id_request"])}" target="_blank" title="Ver Orden" class="btn btn-outline-info btn-sm btn-flat">
                <i class="fas fa-eye"></i>
              </a>
              <button type="button" class="btn btn-outline-danger btn-sm btn-flat"  onClick=handleDeleteOrder(${data["id_request"]})>
              <i class="fas fa-trash-alt"></i>
             </button>
                  </div> `;

          },
          title: "Acciones",
          className: "text-center",
        },
      ],
      destroy: "true",
      columnDefs: [{
          targets: 0,
          'render': function(data, type, row, meta){
            if(type === 'display'){
               data = '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>';
            }

            return data;
         },
        checkboxes: {
            'selectRow': true,
            selectAllRender: '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>'
        },
                   
        },
        /* {
         targets: [0],
         visible: false,
         searchable: false,
       },   */
      ],
      
    select: {
        'style': 'multi'
    },
    fnCreatedRow: function(nRow, aData, iDataIndex) {
      $(nRow).attr('data-id', aData.id_request); // or whatever you choose to set as the id
      $(nRow).attr('id', 'id_' + aData.id_request); // or whatever you choose to set as the id
  },

      order: [[1, "DESC"]],

     /*  createdRow: (row, data) => {
        $(row).attr("id", "orden_" + data.id_request);
      }, */
    })
    .DataTable();
  $("#tabla_ordenes_sunimistros thead").addClass("thead-dark text-center");

  

});


function handleDeleteOrder(id_request) {
  Swal.fire({
    title: `Deseas Eliminar la Orden de Compra con Folio: ${id_request} ?`,
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
      dataForm.append("id_request", id_request);

      $.ajax({
        data: dataForm, //datos que se envian a traves de ajax
        url: `${urls}suministros/eliminar_orden_compra`, //archivo que recibe la peticion
        type: "post", //método de envio
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentType
        success: function (response) {
          //una vez que el archivo recibe el request lo procesa y lo devuelve
          console.log(response);

          /*codigo que borra todos los campos del form newProvider*/
          if (response) {
            Swal.fire(`!Se ha Eliminado la Orden ${id_request}!`, "", "success");
            tbl_ordenes.ajax.reload(null, false);

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




function editOrden(id_request) {
  let data = new FormData();

  data.append("id_request", id_request);
  $("#id_requisicion").val(`${id_request}`);
  $("#editRequestModal").modal("show");

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}suministros/editar-orden-compra`, //archivo que recibe la peticion
    type: "post", //método de envio
    contentType: false, //importante enviar este parametro en false
    processData: false, //importante enviar este parametro en false
    async: true,
    dataType: "json",
    success: function (response) {

      //una vez que el archivo recibe el request lo procesa y lo devuelve
      /*codigo que borra todos los campos del form Tickets*/
      response.forEach(function (resp, index) {
        console.log(resp.orden_compra);
        $("#orden_compra").val(`${resp.orden_compra}`);
        $("#fecha_formalizacion").val(`${resp.fecha_formalizacion}`);
        $("#fecha_estatus").val(`${resp.fecha_estatus_trabajo}`);
      });
       
        //Swal.fire("!Se ha Registrado el permiso!", "", "success");
      
    },
    error: function (jqXHR, status, error) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Algo salió Mal! Contactar con el Administrador",
      });
      console.log("Mal Revisa entro en el error: " + error);
    },
  });

}
function listItems(id_request) {

  let data = new FormData();

  data.append("id_request", id_request);
  $("#listarItemsModal").modal("show");
  $("#btn_todos").hide();
  let orden = $(`#id_${id_request} td`)[1].innerHTML;
  let orden_compra = $(`#id_${id_request} td`)[2].innerHTML;
  $("#ordenes_compras").text(`${orden_compra}`);
  $("#resultado").empty();
  $("#resultado").append(`
                    <table id="tabla_partida_${orden}" class="table table-bordered table-striped " role="grid" aria-describedby="partida_info" style="width:100%" ref="">
                    </table>
                `);
  tbl_partidas = $(`#tabla_partida_${id_request}`)
    .dataTable({
      processing: true,
      ajax: {
        data: { 'id_request': id_request },
        method: "post",
        url: `${urls}suministros/listar-items`,
        dataSrc: "",
      },
      lengthChange: true,
      ordering: true,
      responsive: false,
      autoWidth: false,
      rowId: false,
      dom: "rt",
      buttons: [
        /* {
          extend: "excelHtml5",
          title: "Permisos",
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6],
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
          data: "codigo",
          title: "CODIGO",
          className: "text-center",
        },
        {
          data: "desc_breve",
          title: "DESCRIPCIÓN",
          className: "text-center",
        },
        {
          data: "tipo",
          title: "TIPO",
          className: "text-center",
        },
        {
          data: "diametro",
          title: "DIAMETRO",
          className: "text-center",
        },
        {
          data: "clase",
          title: "CLASE",
          className: "text-center",
        },
        {
          data: "num_piezas",
          title: "PIEZAS",
          className: "text-center",
        },
        {
          data: "tiempo",
          title: "TIEMPO",
          className: "text-center",
        },
        {
          data: "fecha_entrega",
          title: "ENTREGA",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            let fecha_real = data["fecha_real_entrega"];

            return (fecha_real == null) ? "----" : data["fecha_real_entrega"];
          },

          title: "FECHA REAL",
          className: "text-center",
        },
        {
          data: null,
          title: "ACCION",
          className: "text-center",
        },

      ],
      destroy: "true",
      columnDefs: [
        {
          targets: 9,
          render: function (data, type, full, meta) {
            $("#btn_todos").show();
            if (data["active_status"] == 1) {
              return ` <div class="pull-right mr-auto">
              <button type="button" class="btn btn-info btn-sm btn-flat" title="Descargar Documentos" Onclick="prueba(${data["id_items"]})">
              <i class="fas fa-check"></i>
              </button>
            </div> `;
            } else {
              return ` <div class="pull-right mr-auto">
              <button type="button" class="btn btn-secondary btn-sm btn-flat" title="Descargar Documentos" >
              <i class="fas fa-check"></i>
              </button>
            </div> `;
            }

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
        $(row).attr("id", "doc_" + data.id_items);
      },
    })
    .DataTable();
  $(`#tabla_partida_${id_request} thead`).addClass("thead-dark text-center");


}




function prueba(id_items) {
  $('#form_cierre').trigger("reset");
  $("#fecha_cierre").removeClass("has-error");
  $("#error_fecha_cierre").text("");
  $("#items").val(id_items);
  $("#listarModal").modal("show");


}


/* $("table").dataTable({
  
  rowCallback:function(row,data)
  {


    
    if(data[2] == "Excelente")
    {
      $($(row).find("td")[6]).css("background-color","green");
    }
    else if(data[2] == "Bueno"){
        $($(row).find("td")[6]).css("background-color","blue");
    }
    else{
        $($(row).find("td")[6]).css("background-color","red");
    }
    
  }
  
}); */



$("#editar_orden").submit(function (event) {
  event.preventDefault();
  $("#guardar_orden").prop("disabled", true);
  console.log("aqui estoy 1");
  let data = new FormData();
  let orden_compra = $("#orden_compra").val();
  let fecha_formalizacion = $("#fecha_formalizacion").val();
  let fecha_estatus = $("#fecha_estatus").val();
  data.append("id_request", $("#id_requisicion").val());
  data.append("orden_compra", orden_compra);
  data.append("fecha_formalizacion", fecha_formalizacion);
  data.append("fecha_estatus", fecha_estatus);

 
  if ($.trim(orden_compra).length == 0) {
    var error_orden = "El campo es requerido";
    $("#error_orden_compra").text(error_orden);
    $("#orden_compra").addClass("has-error");
  } else {
    error_orden = "";
    $("#error_orden_compra").text(error_orden);
    $("#orden_compra").removeClass("has-error");
  }



  if (error_orden != "") {
    $("#guardar_orden").prop("disabled", false);
    console.log("aqui estoy 2");
    return false;
  }


  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}suministros/actualizar_orden`, //archivo que recibe la peticion
    type: "post", //método de envio
    contentType: false, //importante enviar este parametro en false
    processData: false, //importante enviar este parametro en false
    async: true,
    dataType: "json",
    success: function (response) {

      //una vez que el archivo recibe el request lo procesa y lo devuelve
      console.log(response);
      /*codigo que borra todos los campos del form Tickets*/
      if (response) {
        $('#editar_orden').trigger("reset");
        tbl_ordenes.ajax.reload(null, false);
        $("#guardar_orden").prop("disabled", false);
        $('#editRequestModal').modal('toggle');
        Swal.fire("!Se ha Actualizado la Orden !", "", "success");
      } 
    },
    error: function (jqXHR, status, error) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Algo salió Mal! Contactar con el Administrador",
      });
      console.log("Mal Revisa entro en el error: " + error);
    },
  });
});


$("#form_cierre").submit(function (event) {
  event.preventDefault();
  $("#cerrar_partida").prop("disabled", true);

  let data = new FormData();
  let fecha_cierre = $("#fecha_cierre").val();
  let id_item = $("#items").val();
  let obs = $("#obs_partida").val();


  data.append("fecha_cierre", fecha_cierre);
  data.append("id_item", id_item);
  data.append("observacion", obs);



  if ($.trim(fecha_cierre).length == 0) {
    var error_fecha = "El campo es requerido";
    $("#error_fecha_cierre").text(error_fecha);
    $("#fecha_cierre").addClass("has-error");
  } else {
    error_fecha = "";
    $("#error_fecha_cierre").text(error_fecha);
    $("#fecha_cierre").removeClass("has-error");
  }



  if (error_fecha != "") {
    $("#cerrar_partida").prop("disabled", false);
    return false;
  }


  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}suministros/cerrar-partida`, //archivo que recibe la peticion
    type: "post", //método de envio
    contentType: false, //importante enviar este parametro en false
    processData: false, //importante enviar este parametro en false
    success: function (response) {
      //una vez que el archivo recibe el request lo procesa y lo devuelve
    // console.log(response);
      /*codigo que borra todos los campos del form Tickets*/
      if (response) {
        $('#form_cierre').trigger("reset");
        tbl_partidas.ajax.reload(null, false);
        $("#cerrar_partida").prop("disabled", false);
        $('#listarModal').modal('toggle');
        //Swal.fire("!Se ha Registrado el permiso!", "", "success");
      } else {
        $(".btn-primary").removeClass("active");
        $("#cerrar_partida").prop("disabled", false);

        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
    error: function (jqXHR, status, error) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Algo salió Mal! Contactar con el Administrador",
      });
      console.log("Mal Revisa entro en el error: " + error);
    },
  });
});

 // Manejar evento de envío de formulario
 $('#frm-sum').on('submit', function(e) {
  var form = this;
  // Impedir el envío de formularios reales
  e.preventDefault();

  var rows = $(tbl_ordenes.rows({
      selected: true
  }).$('input[type="checkbox"]').map(function() {
      return $(this).prop("checked") ? $(this).closest('tr').attr('data-id') : null;
  }));
  //console.log(table.column(0).checkboxes.selected())
  //Iterar sobre todas las casillas de verificación checkboxes
  rows_selected = [];
  $.each(rows, function(index, rowId) {
     // console.log(rowId)
      // Create a hidden element 
      rows_selected.push(rowId);
      /* $(form).append(
          $('<input>')
          .attr('type', 'hidden')
          .attr('name', 'id[]')
          .val(rowId)
      ); */
  });

  let ordenes= rows_selected.join(",");
  
  let timerInterval = Swal.fire({ //se le asigna un nombre al swal
    title: 'Generando Reporte en Excel!',
    html: 'Espere unos Segundos.',
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });

  var nomArchivo = `Reporte_Suministros.xlsx`;
/*   var param = JSON.stringify({
    ordenes: ordenes
  }); */
  var pathservicehost = `${urls}suministros/reporte-excel`;
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
  xhr.send("data=" + ordenes);

  // Eliminar elementos agregados
  $('input[type="checkbox"]',form).prop("checked", false);  
  
});

