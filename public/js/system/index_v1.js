/* INDEX DE SISTEMAS */
/**
 * ARCHIVO MODULO SISTEMAS
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
$(document).ready(function () {
  tabla_supplies = $("#tabla_suministros")
    .dataTable({
      responsive: true,
      lengthChange: false,
      autoWidth: false,
      buttons: [
        {
          extend: "excelHtml5",
          title: "Inventario de Suministros",
          exportOptions: {
            columns: [1, 2, 3, 4],
          },
        },
        /* {
              extend:'pdfHtml5',
              title:'Listado de Urs',
              exportOptions:{
                columns:[1,2,3,4]
              }
            } */
      ],
      processing: true,
      ajax: {
        method: "post",
        url: urls + "sistemas/todos_suministros",
        dataSrc: "",
      },
      lengthChange: true,
      ordering: true,
      autoWidth: false,
      rowId: "staffId",
      dom: "lBfrtip",
      language: {
        url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
      },
      columns: [
        {
          data: "id_supplies",
          title: "id",
        },
        {
          data: "description_supplies",
          title: "SUMINISTROS",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            let media_max = parseInt(data["stock_max"]) / 2;
            let media_min = parseInt(data["stock_min"]) + 1;
            let min = parseInt(data["stock_min"]);
            if (
              parseInt(data["stock_supplies"]) >= parseInt(data["stock_max"]) ||
              parseInt(data["stock_supplies"]) >= media_max
            ) {
              return `<span class="badge bg-success">${data["stock_supplies"]} piezas</span>`;
            } else if (
              parseInt(data["stock_supplies"]) <= media_min 
            ) {
              return ( data["stock_supplies"] == 1) ? `<span class="badge bg-danger">${data["stock_supplies"]} pieza</span>`: `<span class="badge bg-danger">${data["stock_supplies"]} piezas</span>`;
            } else {
              return `<span class="badge bg-warning">${data["stock_supplies"]} piezas</span>`;
            }
          },
          title: "STOCK",
          className: "text-center",
        },
        {
          data: "stock_min",
          title: "MINIMO",
          className: "text-center",
        },
        {
          data: "stock_max",
          title: "MAXIMO",
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
          targets: 5,
          render: function (data, type, full, meta) {
            let desc = data['description_supplies'].toString().trim();
            console.log(desc);
            return ` <div class="pull-right mr-auto">
                      <button type="button" class="btn btn-info btn-sm" title="Salida de Suministro"  onClick=Outlet(${data["id_supplies"]})>
                      <i class="fas fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-info btn-sm" title="Ingresar Suministro"  onClick=Input(${data["id_supplies"]})>
                      <i class="fas fa-plus"></i>
                      </button>
                      <button type="button" class="btn btn-info btn-sm" title="Editar Suministro"  onClick=handleEdit(${data["id_supplies"]})>
                          <i class="far fa-edit"></i>
                      </button>
                      <button type="button" class="btn btn-danger btn-sm" title="Editar Suministro"  onClick="handleDelete(${data["id_supplies"]},'${desc}')">
                      <i class="fas fa-trash-alt"></i>
                  </button>
                      
                    </div> `;
          },
        },
        {
          targets: [0],
          visible: false,
          searchable: false,
        },
      ],

      order: [[0, "DESC"]],

      createdRow: (row, data) => {
        $(row).attr("id", "supplies_" + data.id_supplies);
      },
    })
    .DataTable();
    $('#tabla_suministros thead').addClass('thead-dark text-center');
});

function handleEdit(id_supplies) {
  //console.log("Hola Mundo Edit" + id_supplies);
  $.ajax({
    type: "GET",
    url: urls + "sistemas/editar_suministro/" + id_supplies,
    async: true,
    dataType: "json",
    success: function (resp) {
      //console.log(resp);
      if (resp != "error") {
        $("#id_article").val(id_supplies);
        $("#stock_min").val(resp.stock_min);
        $("#stock_max").val(resp.stock_max);
        $("#description_supplies").val(resp.description_supplies);

        $("#actualizaModal").modal("show");
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

$("#actualizar_suministro").click(function (event) {
  event.preventDefault();

  let data = new FormData();

  data.append("id_articulo", $("#id_article").val());
  data.append("nombre_articulo", $("#description_supplies").val());
  data.append("stock_max", $("#stock_max").val());
  data.append("stock_min", $("#stock_min").val());

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: urls + "sistemas/actualizar_suministros", //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    success: function (response) {
      //console.log(response);

      //una vez que el archivo recibe el request lo procesa y lo devuelve
      //console.log(response);
      /*codigo que borra todos los campos del form newProvider*/
      if (response != "error") {
        setTimeout(function () {
          tabla_supplies.ajax.reload(null, false);
        }, 100);
        $("#actualizaModal").modal("toggle");
        Swal.fire("!Los datos se han Actualizado!", "", "success");
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
  });
});

function Outlet(id_supplies) {
  //console.log("Hola Mundo Salida" + id_supplies);
  let data = new FormData();
  data.append("id_articulo", id_supplies);
  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: urls + "sistemas/cantidad_suministros", //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    success: function (response) {
      //console.log(response);
      //una vez que el archivo recibe el request lo procesa y lo devuelve
      /*codigo que borra todos los campos del form newProvider*/
      if (response == "alcanza") {
        let article = $(`#supplies_${id_supplies} td`)[0].innerHTML;
        $("#id_articulo").val(id_supplies);
        $("#nombre_articulo").val(article);
        $("#articulo").val(article);
        $("#salidaModal").modal("show");
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "No exite Stock! para este producto",
        });
      }
    },
  });
}

$("#registrar_articulos").submit(function (event) {
  event.preventDefault();
  $("#salida_suministros").prop("disabled", true);
  let data = new FormData();

  data.append("id_articulo", $("#id_articulo").val());
  data.append("nombre_articulo", $("#nombre_articulo").val());
  data.append("cantidad_salida", $("#cantidad_salida").val());
  data.append("persona_recibe", $("#entrega").val());
  data.append("observacion_salida", $("#observacion_salida").val());

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: urls + "sistemas/salida_suministros", //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    success: function (response) {
      //una vez que el archivo recibe el request lo procesa y lo devuelve
      //console.log(response);
      /*codigo que borra todos los campos del form newProvider*/
      if (response == "Exede") {
        $("#resultado")
          .html(`<div class="alert alert-warning alert-dismissible" role="alert">
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                              La cantidad ingresada en mayor a la existente en Stock...
                            </div>
                              <span></span>`);
        setTimeout(function () {
          $(".alert")
            .fadeTo(1000, 0)
            .slideUp(800, function () {
              $(this).remove();
            });
        }, 3000);
        $("#salida_suministros").prop("disabled", false);
      } else {
        if (response != "error") {
          setTimeout(function () {
            tabla_supplies.ajax.reload(null, false);
          }, 100);
          $("#salida_suministros").prop("disabled", false);
          $("#salidaModal").modal("toggle");
          Swal.fire("!Los datos se han Actualizado!", "", "success");
          $("#cantidad_salida").val("");
          $("#entrega").val("");
          $("#descripcion_salida").val("");
        } else {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Algo salió Mal! Contactar con el Administrador",
          });
        }
      }
    },
  });
});

function Input(id_supplies) {
  //console.log("Hola Mundo Entrada" + id_supplies);
  let article = $(`#supplies_${id_supplies} td`)[0].innerHTML;
  $("#id_articulos").val(id_supplies);
  $("#nombre_entrada").val(article);
  $("#articulo_entrada").val(article);
  $("#entradaModal").modal("show");
}

$("#registrar_articulo").submit(function (event) {
  event.preventDefault();
  $("#guardar_entrada").prop("disabled", true);
  let data = new FormData();

  data.append("id_articulo", $("#id_articulos").val());
  data.append("nombre_entrada", $("#nombre_entrada").val());
  data.append("cantidad_entrada", $("#cantidad_entrada").val());
  data.append("observacion_entrada", $("#observacion_entrada").val());

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: urls + "sistemas/entrada_suministros", //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    success: function (response) {
      console.log(response);

      //una vez que el archivo recibe el request lo procesa y lo devuelve
      //console.log(response);
      /*codigo que borra todos los campos del form newProvider*/

      if (response != "error") {
        setTimeout(function () {
          tabla_supplies.ajax.reload(null, false);
        }, 100);
        $("#guardar_entrada").prop("disabled", false);
        $("#entradaModal").modal("toggle");
        Swal.fire("!Los datos se han Actualizado!", "", "success");
        $("#cantidad_entrada").val("");
        $("#observacion_entrada").val("");
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
  });
});

function validaNumericos(event) {
  return event.charCode >= 48 && event.charCode <= 57 ? true : false;
}

$("#alta_articulo").submit(function (event) {
  event.preventDefault();
  $("#alta_suministro").prop("disabled", true);
  let data = new FormData();

  data.append("stock_max", $("#alta_stock_max").val());
  data.append("stock_min", $("#alta_stock_min").val());
  data.append("nombre_suministro", $("#nombre_suministro").val());

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: urls + "sistemas/alta_suministro", //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    success: function (response) {
      console.log(response);

      //una vez que el archivo recibe el request lo procesa y lo devuelve
      //console.log(response);
      /*codigo que borra todos los campos del form newProvider*/

      if (response != "error") {
        setTimeout(function () {
          tabla_supplies.ajax.reload(null, false);
        }, 100);
        $("#alta_suministro").prop("disabled", false);
        Swal.fire("!El suministro a sido dado de Alta!", "", "success");
        $("#nombre_suministro").val("");
        $("#alta_stock_max").val("");
        $("#alta_stock_min").val("");
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
  });
});

function handleDelete(id_folio, description_supplies) {
  Swal.fire({
    title: `Deseas Eliminar el Suministro: ${description_supplies} ?`,
    text: `Una vez Eliminado no podras usar el Suministro!`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Eliminar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.value) {
      let dataForm = new FormData();
      dataForm.append("id_folio", id_folio);

      $.ajax({
        data: dataForm, //datos que se envian a traves de ajax
        url: `${urls}sistemas/eliminar_suministro`, //archivo que recibe la peticion
        type: "post", //método de envio
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentType
        success: function (response) {
          //una vez que el archivo recibe el request lo procesa y lo devuelve
          console.log(response);

          /*codigo que borra todos los campos del form newProvider*/
          if (response) {
            tabla_supplies.ajax.reload(null, false);
            Swal.fire("!Suministro Eliminado Correctamente!", "", "success");
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
