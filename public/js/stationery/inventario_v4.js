/**
 * ARCHIVO MODULO PAPELERIA
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
var error_categoria = "0";
var error_descripcion = "0";
var error_cantidad = "0";
var error_minimo = "0";
var error_maximo = "0";
var error_imagen = "0";

var error_maximo1 = "0";
var error_minimo1 = "0";
var error_cantidad_salida = "0";
var error_observacion_salida = "0";
var error_cantidad_entrada = "0";
var error_observacion_entrada = "0";

$(document).ready(function () {
  tbl_inventary = $("#tabla_inventario_papeleria")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}papeleria/inventario_total`,
        dataSrc: "",
      },
      lengthChange: true,
      ordering: true,
      responsive: true,
      autoWidth: false,
      rowId: "staffId",
      dom: "lBfrtip",
      buttons: [
        {
          extend: "excelHtml5",
          title: "Inventerio de papeleria",
          exportOptions: {
            columns: [ 1, 2, 3, 4, 5, 6],
          },
        },
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
          data: "stock_product",
          title: "stock",
          className: "text-center",
        },
        {
          data: "id_product",
          title: "ITEM",
          className: "text-center",
        },
        {
          data: "code_product",
          title: "EPICOR",
          className: "text-center",
        },

        {
          data: "description_product",
          title: "PRODUCTO",
          className: "text-center",
        },
        {
          data: "unit_of_measurement",
          title: "UNIDAD MEDIDA",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            let media_max = parseInt(data["stock_max"]) / 2;
            let media_min = parseInt(data["stock_min"]) + 1;
            let min = parseInt(data["stock_min"]);
            if (
              parseInt(data["stock_product"]) >= parseInt(data["stock_max"]) ||
              parseInt(data["stock_product"]) >= media_max
            ) {
              return `<span class="badge bg-success">${data["stock_product"]}</span>`;
            } else if (parseInt(data["stock_product"]) <= media_min) {
              return data["stock_product"] == 1
                ? `<span class="badge bg-danger">${data["stock_product"]} </span>`
                : `<span class="badge bg-danger">${data["stock_product"]} </span>`;
            } else {
              return `<span class="badge bg-warning">${data["stock_product"]}</span>`;
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
          targets: 8,
          render: function (data, type, full, meta) {
            return ` <div class="pull-right mr-auto">
            <button type="button" class="btn btn-outline-info btn-sm" title="Salida de Suministro"  onClick=Outlet(${data["id_product"]})>
            <i class="fas fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-outline-info btn-sm" title="Ingresar Suministro"  onClick=Input(${data["id_product"]})>
                      <i class="fas fa-plus"></i>
                      </button>
                      <button type="button" class="btn btn-outline-primary btn-sm" title="Editar Suministro"  onClick=handleEdit(${data["id_product"]})>
                          <i class="far fa-edit"></i>
                      </button>
                      <button type="button" class="btn btn-outline-danger btn-sm" title="Eliminar Suministro"  onClick=handleDelete(${data["id_product"]})>
                      <i class="fas fa-trash-alt"></i>
                      </button>
                    </div> `;
          },
        },
        {
          targets: [0],
          visible: false
          
        },
      ],

      order: [[0, "ASC"]],

      createdRow: (row, data) => {
        $(row).attr("id", "product_" + data.id_product);
      },
    })
    .DataTable();
  $("#tabla_inventario_papeleria thead").addClass("thead-dark text-center");
});

function validaModal() {

  if ($("#minimo1").val().length > 0) {
    error_minimo1 = "";
    $("#error_minimo1").text(error_minimo1);
    $("#minimo1").removeClass('has-error');
  }
  if ($("#maximo1").val().length > 0) {
    error_maximo1 = "";
    $("#error_maximo1").text(error_maximo1);
    $("#maximo1").removeClass('has-error');
  }

  $("#salida_articulos").on
    ("change", `#cantidad_salida`, function () {
      let cantidad = $(this).val();
      let nom_producto = $("#nombre_articulo").val();
      let data = new FormData();
      data.append("description_product", nom_producto);

      $.ajax({
        data: data, //datos que se envian a traves de ajax
        type: "post", //método de envio
        url: `${urls}papeleria/inventario_solicitud`, //archivo que recibe la peticion
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentType
        dataType: "json",
        success: function (resp) {
          Math.abs(cantidad);
          Math.abs(resp.stock_product);
          var a = cantidad / resp.stock_product;
          if (a <= 1) {
            error_funcion = "";
            $(`#error_cantidad_salida`).text("");
            $(`#cantidad_salida`).removeClass("has-error");
          }
          else {
            error_funcion = "Existencia = " + resp.stock_product;
            $(`#error_cantidad_salida`).text(error_funcion);
            $(`#cantidad_salida`).addClass("has-error");

          }
        },
        error: function () {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Ocurrio un error en el servidor! Contactar con el Administrador",
          });
        },
      });
    });
  if ($("#cantidad_salida").val().length > 0) {
    error_cantidad_salida = "";
    $("#error_cantidad_salida").text(error_cantidad_salida);
    $("#cantidad_salida").removeClass('has-error');
  }
  if ($.trim($("#observacion_salida").val()).length > 3) {
    error_observacion_salida = "";
    $('#error_observacion_salida').text(error_observacion_salida);
    $('#observacion_salida').removeClass('has-error');
  } if ($.trim($("#observacion_salida").val()).length >= 4) {
    if (isNaN($.trim($("#observacion_salida").val())) == false) {
      error_observacion_salida = "No se permiten solo números";
      $('#error_observacion_salida').text(error_observacion_salida);
      $('#observacion_salida').addClass('has-error');
    }
    if ($.trim($("#observacion_salida").val()) == "abcd" || $.trim($("#observacion_salida").val()) == "ABCD" ||
      $.trim($("#observacion_salida").val()) == "asdf" || $.trim($("#observacion_salida").val()) == "ASDF" ||
      $.trim($("#observacion_salida").val()) == "xxxx" || $.trim($("#observacion_salida").val()) == "XXXX" ||
      $.trim($("#observacion_salida").val()) == "aaaa" || $.trim($("#observacion_salida").val()) == "AAAA" ||
      $.trim($("#observacion_salida").val()) == "...." || $.trim($("#observacion_salida").val()) == ",,,," ||
      $.trim($("#observacion_salida").val()) == "____" || $.trim($("#observacion_salida").val()) == "----") {
      error_observacion_salida = "Escribe una observacion aceptable";
      $('#error_observacion_salida').text(error_observacion_salida);
      $('#observacion_salida').addClass('has-error');
    }
  }

  if ($("#cantidad_entrada").val().length > 0) {
    error_cantidad_entrada = "";
    $("#error_cantidad_entrada").text(error_cantidad_entrada);
    $("#cantidad_entrada").removeClass('has-error');
  }
  if ($.trim($("#observacion_entrada").val()).length > 3) {
    error_observacion_entrada = "";
    $('#error_observacion_entrada').text(error_observacion_entrada);
    $('#observacion_entrada').removeClass('has-error');
  } if ($.trim($("#observacion_entrada").val()).length >= 4) {
    if (isNaN($.trim($("#observacion_entrada").val())) == false) {
      error_observacion_entrada = "No se permiten solo números";
      $('#error_observacion_entrada').text(error_observacion_entrada);
      $('#observacion_entrada').addClass('has-error');
    }
    if ($.trim($("#observacion_entrada").val()) == "abcd" || $.trim($("#observacion_entrada").val()) == "ABCD" ||
      $.trim($("#observacion_entrada").val()) == "asdf" || $.trim($("#observacion_entrada").val()) == "ASDF" ||
      $.trim($("#observacion_entrada").val()) == "xxxx" || $.trim($("#observacion_entrada").val()) == "XXXX" ||
      $.trim($("#observacion_entrada").val()) == "aaaa" || $.trim($("#observacion_entrada").val()) == "AAAA" ||
      $.trim($("#observacion_entrada").val()) == "...." || $.trim($("#observacion_entrada").val()) == ",,,," ||
      $.trim($("#observacion_entrada").val()) == "____" || $.trim($("#observacion_entrada").val()) == "----") {
      error_observacion_entrada = "Escribe una observacion aceptable";
      $('#error_observacion_entrada').text(error_observacion_entrada);
      $('#observacion_entrada').addClass('has-error');
    }
  }
}
function validaNumericos(event) {
  return event.charCode >= 48 && event.charCode <= 57 ? true : false;
}

function handleEdit(id_product) {
  //console.log("Hola Mundo Edit" + id_product);
  error_minimo1 = "";
  $("#error_minimo1").text(error_minimo1);
  $("#minimo1").removeClass('has-error');
  $("#minimo1").val("");
  error_maximo1 = "";
  $("#error_maximo1").text(error_maximo1);
  $("#maximo1").removeClass('has-error');
  $("#maximo1").val("");
  $.ajax({
    type: "GET",
    url: `${urls}papeleria/editar_producto/${id_product}`,
    async: true,
    dataType: "json",
    success: function (resp) {
      //console.log(resp);
      if (resp != "error") {
        $("#folio").val(id_product);
        $("#producto").val(resp.description_product);
        $("#maximo1").val(resp.stock_max);
        $("#minimo1").val(resp.stock_min);
        $("#unidad_medida").val(resp.unit_of_measurement);
        $("#inventarioModal").modal("show");
        error_maximo1 = "0";
        error_minimo1 = "0";

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



function handleDelete(id_product) {

  Swal.fire({
    title: `Deseas Eliminar el Producto ?`,
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
      dataForm.append("id_producto", id_product);
      
      $.ajax({
        data: dataForm, //datos que se envian a traves de ajax
        url: `${urls}papeleria/eliminar_producto`, //archivo que recibe la peticion
        type: "post", //método de envio
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentType
        success: function (response) {
          //una vez que el archivo recibe el request lo procesa y lo devuelve
          console.log(response);
          
          /*codigo que borra los prodctos de la tabla*/
          if (response) {
            tbl_inventary.ajax.reload(null, false);
              
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


function Input(id_product) {
  //console.log("Hola Mundo Entrada" + id_product);
  
  let article = $(`#product_${id_product} td`)[2].innerHTML;
  let code_epicor = $(`#product_${id_product} td`)[1].innerHTML;
  $("#code_epicor").val(code_epicor);
  $("#id_articulos").val(id_product);
  $("#nombre_entrada").val(article);
  $("#articulo_entrada").val(article);
  $("#cantidad_entrada").val("");
  $("#observacion_entrada").val("");
  
  $(`#cantidad_entrada`).removeClass("has-error");
  $(`#observacion_entrada`).removeClass("has-error");
  $(`#error_cantidad_entrada`).text("");
  $(`#error_observacion_entrada`).text("");
  
  $("#entradaModal").modal("show");
}

$("#parametros_papeleria").submit(function (event) {
  event.preventDefault();
  $("#parametros").prop("disabled", true);
 
  if ((error_maximo1 == "0" && error_minimo1 == "0")
    //|| ($("#maximo1").val()==resp.stock_max && $("#minimo1").val()==resp.stock_min )
  ) {
    error_maximo1 = "Edita el Stock Maximo";
    $("#error_maximo1").text(error_maximo1);
    $("#maximo1").addClass('has-error')
    error_minimo1 = "Edita el Stock Minimo";
    $("#error_minimo1").text(error_minimo1);
    $("#minimo1").addClass('has-error'); 

  } 
  
  if ($("#maximo1").val().length == 0) {
    error_maximo1 = "Maximo Requerida";
    $("#error_maximo1").text(error_maximo1);
    $("#maximo1").addClass('has-error');
  }
  if ($("#minimo1").val().length == 0) {
    error_minimo1 = "Minimo Requerida";
    $("#error_minimo1").text(error_minimo1);
    $("#minimo1").addClass('has-error');
  }

  if ($("#unidad_medida").val().length == 0) {
    error_unidad_medida = "Unidad Requerida";
    $("#error_unidad_medida").text(error_unidad_medida);
    $("#unidad_medida").addClass('has-error');
  }else{
    error_unidad_medida = "";
    $("#error_unidad_medida").text(error_unidad_medida);
    $("#unidad_medida").removeClass('has-error');
  }

   if (
    error_maximo1 != "" ||
    error_minimo1 != "" ||
    error_unidad_medida != ""
  ) {
    $("#parametros").prop("disabled", false);
    console.log("aqui estoy");
    return false;
  }
  let data = new FormData();
  data.append("id_folio", $("#folio").val());
  data.append("producto", $("#producto").val());
  data.append("maximo", $("#maximo1").val());
  data.append("minimo", $("#minimo1").val());
  data.append("unidad_medida", $("#unidad_medida").val());
  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}papeleria/parametros`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    success: function (response) {
      //una vez que el archivo recibe el request lo procesa y lo devuelve
      // console.log(response);
      /*codigo que borra todos los campos del form newProvider*/

      if (response) {
        setTimeout(function () {
          tbl_inventary.ajax.reload(null, false);
          $("#parametros").prop("disabled", false);
          $("#inventarioModal").modal("toggle");
          Swal.fire({
            icon: "success",
            title: "",
            text: "!Los datos se han Actualizado!",
          });
        }, 100);
      } else {
        $("#parametros").prop("disabled", false);
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
      $("#parametros").prop("disabled", false);
    } else if (jqXHR.status == 404) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No se encontró la página solicitada [404]",
      });
      $("#parametros").prop("disabled", false);
    } else if (jqXHR.status == 500) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Internal Server Error [500]",
      });
      $("#parametros").prop("disabled", false);
    } else if (textStatus === "parsererror") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Error de análisis JSON solicitado.",
      });
      $("#parametros").prop("disabled", false);
    } else if (textStatus === "timeout") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Time out error.",
      });
      $("#parametros").prop("disabled", false);
    } else if (textStatus === "abort") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ajax request aborted.",
      });

      $("#parametros").prop("disabled", false);
    } else {
      alert("Uncaught Error: " + jqXHR.responseText);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: `Uncaught Error: ${jqXHR.responseText}`,
      });
      $("#parametros").prop("disabled", false);
    }
  });
});

$("#registrar_articulo").submit(function (event) {
  event.preventDefault();
  $("#guardar_entrada").prop("disabled", true);
  if ($("#cantidad_entrada").val().length == 0) {
    error_cantidad_entrada = "Cantidad Requerida";
    $("#error_cantidad_entrada").text(error_cantidad_entrada);
    $("#cantidad_entrada").addClass('has-error');
  }
  if ($("#observacion_entrada").val().length < 4) {
    error_observacion_entrada = "Observacion Requerida";
    $("#error_observacion_entrada").text(error_observacion_entrada);
    $("#observacion_entrada").addClass('has-error');
  }
  if (
    error_observacion_entrada != "" ||
    error_cantidad_entrada != ""
  ) {
    $("#guardar_entrada").prop("disabled", false);
    return false;
  }
  let data = new FormData();
 
  data.append("code_epicor", $("#code_epicor").val());
  data.append("id_producto", $("#id_articulos").val());
  data.append("producto", $("#nombre_entrada").val());
  data.append("cantidad", $("#cantidad_entrada").val());
  data.append("observacion", $("#observacion_entrada").val());

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}papeleria/entrada`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    success: function (response) {
      //una vez que el archivo recibe el request lo procesa y lo devuelve
      //console.log(response);
      /*codigo que borra todos los campos del form newProvider*/

      if (response != "error") {
        setTimeout(function () {
          tbl_inventary.ajax.reload(null, false);
        }, 100);
        $("#guardar_entrada").prop("disabled", false);
        $("#entradaModal").modal("toggle");
        $("#id_articulos").val("");
        $("#nombre_entrada").val("");
        $("#cantidad_entrada").val("");
        $("#observacion_entrada").val("");
        Swal.fire("!Los datos se han Actualizado!", "", "success");
      } else {
        $("#guardar_entrada").prop("disabled", false);
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
      $("#guardar_entrada").prop("disabled", false);
    } else if (jqXHR.status == 404) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No se encontró la página solicitada [404]",
      });
      $("#guardar_entrada").prop("disabled", false);
    } else if (jqXHR.status == 500) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Internal Server Error [500]",
      });
      $("#guardar_entrada").prop("disabled", false);
    } else if (textStatus === "parsererror") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Error de análisis JSON solicitado.",
      });
      $("#guardar_entrada").prop("disabled", false);
    } else if (textStatus === "timeout") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Time out error.",
      });
      $("#guardar_entrada").prop("disabled", false);
    } else if (textStatus === "abort") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ajax request aborted.",
      });

      $("#guardar_entrada").prop("disabled", false);
    } else {
      alert("Uncaught Error: " + jqXHR.responseText);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: `Uncaught Error: ${jqXHR.responseText}`,
      });
      $("#guardar_entrada").prop("disabled", false);
    }
  });
});

function Outlet(id_product) {
  //console.log("Hola Mundo Entrada" + id_product);
  let article = $(`#product_${id_product} td`)[2].innerHTML;
  let code_epicor = $(`#product_${id_product} td`)[1].innerHTML;
  $("#code_epicor1").val(code_epicor);
  $("#id_articulo").val(id_product);
  $("#nombre_articulo").val(article);
  $("#cantidad_salida").val("");
  $("#observacion_salida").val("");
  $("#salidaModal").modal("show");
  $(`#cantidad_salida`).removeClass("has-error");
   $(`#observacion_salida`).removeClass("has-error");
   $(`#error_cantidad_salida`).text("");
   $(`#error_observacion_salida`).text("");
}


$("#salida_articulos").submit(function (event) {
  event.preventDefault();
  $("#salida_suministros").prop("disabled", true);


  if ($("#cantidad_salida").val().length == 0) {
    error_cantidad_salida = "Cantidad Requerida";
    $("#error_cantidad_salida").text(error_cantidad_salida);
    $("#cantidad_salida").addClass('has-error');
  }
  if ($("#observacion_salida").val().length < 4) {
    error_observacion_salida = "Observacion Requerida";
    $("#error_observacion_salida").text(error_observacion_salida);
    $("#observacion_salida").addClass('has-error');
  } 
  if (
    error_observacion_salida != "" ||
    error_cantidad_salida != ""
  ) {
    $("#salida_suministros").prop("disabled", false);
    return false;
  }

  let data = new FormData();

  data.append("code_epicor", $("#code_epicor1").val());
  data.append("id_producto", $("#id_articulo").val());
  data.append("producto", $("#nombre_articulo").val());
  data.append("cantidad", $("#cantidad_salida").val());
  data.append("observacion", $("#obs_salida").val());

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}papeleria/salidas`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    success: function (response) {
      //una vez que el archivo recibe el request lo procesa y lo devuelve
      //console.log(response);
      /*codigo que borra todos los campos del form newProvider*/

      if (response != "error") {
        setTimeout(function () {
          tbl_inventary.ajax.reload(null, false);
        }, 100);
        $("#salida_suministros").prop("disabled", false);
        $("#id_articulo").val("");
        $("#nombre_articulo").val("");
        $("#cantidad_salida").val("");
        $("#obs_salida").val("");
        $("#salidaModal").modal("toggle");
        Swal.fire("!Los datos se han Actualizado!", "", "success");
      } else {
        $("#salida_suministros").prop("disabled", false);
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
      $("#salida_suministros").prop("disabled", false);
    } else if (jqXHR.status == 404) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No se encontró la página solicitada [404]",
      });
      $("#salida_suministros").prop("disabled", false);
    } else if (jqXHR.status == 500) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Internal Server Error [500]",
      });
      $("#salida_suministros").prop("disabled", false);
    } else if (textStatus === "parsererror") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Error de análisis JSON solicitado.",
      });
      $("#salida_suministros").prop("disabled", false);
    } else if (textStatus === "timeout") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Time out error.",
      });
      $("#salida_suministros").prop("disabled", false);
    } else if (textStatus === "abort") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ajax request aborted.",
      });

      $("#salida_suministros").prop("disabled", false);
    } else {
      alert("Uncaught Error: " + jqXHR.responseText);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: `Uncaught Error: ${jqXHR.responseText}`,
      });
      $("#salida_suministros").prop("disabled", false);
    }
  });
});


function validar() {
  if ($("#categoria").val().length > 0) {
    error_categoria = "";
    $("#error_categoria").text(error_categoria);
    $("#categoria").removeClass('has-error');
  }
  if ($("#descripcion").val().length > 0) {
    error_descripcion = "";
    $("#error_descripcion").text(error_descripcion);
    $("#descripcion").removeClass('has-error');
  }
  if ($("#cantidad").val().length > 0) {
    error_cantidad = "";
    $("#error_cantidad").text(error_cantidad);
    $("#cantidad").removeClass('has-error');
  }
  if ($("#minimo").val().length > 0) {
    error_minimo = "";
    $("#error_minimo").text(error_minimo);
    $("#minimo").removeClass('has-error');
  }
  if ($("#maximo").val().length > 0) {
    error_maximo = "";
    $("#error_maximo").text(error_maximo);
    $("#maximo").removeClass('has-error');
  }
  if ($("#imagen").val().length > 0) {
    error_imagen = "";
    $("#error_imagen").text(error_imagen);
    $("#imagen").removeClass('has-error');
  }

}

$("#nuevo_articulo").submit(function (event) {
  event.preventDefault();

  $("#guardar_articulo").prop("disabled", true);

  if ($("#categoria").val().length == 0
    && $("#descripcion").val().length == 0
    && $("#unidad").val().length == 0
    && $("#cantidad").val().length == 0
    && $("#minimo").val().length == 0
    && $("#maximo").val().length == 0
    && $("#imagen").val().length == 0) {
    Swal.fire({
      icon: "error",
      title: "!ERROR¡",
      text: "Llena el formulario",
    });
  } else {
    if ($("#categoria").val().length == 0) {
      error_categoria = "Categoria Requerida";
      $("#error_categoria").text(error_categoria);
      $("#categoria").addClass('has-error');
    }
    if ($("#unidad").val().length == 0) {
      error_unidad = "Unidad Requerida";
      $("#error_unidad").text(error_unidad);
      $("#unidad").addClass('has-error');
    }else{
      error_unidad = "";
    }
    if ($("#descripcion").val().length == 0) {
      error_descripcion = "Descripcion Requerida";
      $("#error_descripcion").text(error_descripcion);
      $("#descripcion").addClass('has-error');
    }
    if ($("#cantidad").val().length == 0) {
      error_cantidad = "Cantidad Requerida";
      $("#error_cantidad").text(error_cantidad);
      $("#cantidad").addClass('has-error');
    }
    if ($("#minimo").val().length == 0) {
      error_minimo = "Minimo Requerida";
      $("#error_minimo").text(error_minimo);
      $("#minimo").addClass('has-error');
    }
    if ($("#maximo").val().length == 0) {
      error_maximo = "Maximo Requerida";
      $("#error_maximo").text(error_maximo);
      $("#maximo").addClass('has-error');
    }
    if ($("#imagen").val().length == 0) {
      error_imagen = "Imagen Requerida";
      $("#error_imagen").text(error_imagen);
      $("#imagen").addClass('has-error');
    }
  }

  if (
    error_categoria != "" ||
    error_unidad != "" ||
    error_descripcion != "" ||
    error_cantidad != "" ||
    error_minimo != "" ||
    error_maximo != "" ||
    error_imagen != ""

  ) {
    $("#guardar_articulo").prop("disabled", false);

    console.log("error 1"+error_categoria);
    console.log("error 2"+error_unidad);
    console.log("error 3"+error_descripcion);
    console.log("error 4"+error_cantidad);
    console.log("error 5"+error_minimo);
    console.log("error 6"+error_maximo);
    console.log("error 7"+error_imagen);

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
    $("#guardar_articulo").prop("disabled", false);
    return false;
  }

  let data = new FormData();

  data.append("categoria", $("#categoria").val());
  data.append("unidad", $("#unidad").val());
  data.append("descripcion", $("#descripcion").val());
  data.append("cantidad", $("#cantidad").val());
  data.append("minimo", $("#minimo").val());
  data.append("maximo", $("#maximo").val());
  var files = $('#imagen')[0].files[0];
  data.append('file', files);

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}papeleria/nuevo_articulo`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    success: function (response) {
      //una vez que el archivo recibe el request lo procesa y lo devuelve
      //console.log(response);
      /*codigo que borra todos los campos del form newProvider*/

      if (response != "error") {
        setTimeout(function () {
          tbl_inventary.ajax.reload(null, false);
        }, 100);
        $("#guardar_articulo").prop("disabled", false);

        $("#categoria").val("");
        $("#descripcion").val("");
        $("#cantidad").val("");
        $("#minimo").val("");
        $("#maximo").val("");
        $("#imagen").val("");
        Swal.fire("!Los datos se han Actualizado!", "", "success");
      } else {
        $("#guardar_entrada").prop("disabled", false);
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
      $("#guardar_entrada").prop("disabled", false);
    } else if (jqXHR.status == 404) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No se encontró la página solicitada [404]",
      });
      $("#guardar_entrada").prop("disabled", false);
    } else if (jqXHR.status == 500) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Internal Server Error [500]",
      });
      $("#guardar_entrada").prop("disabled", false);
    } else if (textStatus === "parsererror") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Error de análisis JSON solicitado.",
      });
      $("#guardar_entrada").prop("disabled", false);
    } else if (textStatus === "timeout") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Time out error.",
      });
      $("#guardar_entrada").prop("disabled", false);
    } else if (textStatus === "abort") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Ajax request aborted.",
      });

      $("#guardar_entrada").prop("disabled", false);
    } else {
      alert("Uncaught Error: " + jqXHR.responseText);
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: `Uncaught Error: ${jqXHR.responseText}`,
      });
      $("#guardar_entrada").prop("disabled", false);
    }
  });
});