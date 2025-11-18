/**
 * ARCHIVO MODULO HSE
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */

$(document).ready(function () {
  tbl_menus = $("#tabla_lista_menus")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}qhse/listado_menus`,
        dataSrc: "",
      },
      lengthChange: true,
      //ordering: true,
      responsive: true,
      autoWidth: false,
      rowId: "staffId",
      dom: "lBfrtip",
      buttons: [
        {
          extend: "excelHtml5",
          title: "Listado de Menus",
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6],
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
          data: "id_social",
          title: "FOLIO",
          className: "text-center",
        },
        {
          data: "menus",
          title: "MENUS",
          className: "text-center",
        },
        {
          data: "event_date",
          title: "FECHA",
          className: "text-center",
        },

        {
          data: null,

          render: function (data, type, full, meta) {
            switch (data["type_menu"]) {
              case "1":
                tipo = "Voluntariado";
                break;
              case "2":
                tipo = "Acción Verde";
                break;
              case "3":
                tipo = "Actividad Deportiva";
                break;
                case "4":
                  tipo = "Reforestación";
                  break;

              default:
                tipo = "Sin Tipo";
                break;
            }

            return `<span class="badge" style="color:#fff;background-color:#1A709C;">${tipo}</span>`;
          },
          title: "TIPO",
          className: "text-center",
        },

        {
          data: null,

          render: function (data, type, full, meta) {
            setTimeout(function () {
              if (data["active_menu"] == 2) {
                $("#permiso_extra_" + data["id_social"]).attr("checked", false);
              } else {
                $("#permiso_extra_" + data["id_social"]).attr("checked", true);
              }
            }, 10);

            return `<div class="checkbox-center">
                <div class="toggle">
                <input type="checkbox" id="permiso_extra_${data["id_social"]}" onchange="OnOffExtra(${data["id_social"]})">
                <label></label>
                </div>
            </div>`;
          },
          title: "ESTATUS",
          className: "text-center",
        },

        {
          data: null,
          render: function (data, type, full, meta) {
            /* <button type="button" class="btn btn-outline-primary btn-sm" title="Autorizar Suministro"  onClick=handleAuthorized(${data["id_request"]})>
                        <i class="fas fa-user-check"></i>
                    </button> */
            return ` <div class="pull-right mr-auto">
                      <button type="button" class="btn btn-outline-danger btn-sm" title="Eliminar Suministro"  onClick=handleDelete(${data["id_social"]})>
                    <i class="fas fa-trash-alt"></i>
                    </button>
                    </div> `;
          },
          title: "ACCIONES",
          className: "text-center",
        },
      ],
      destroy: "true",
      columnDefs: [
        {
          /* targets: [0],
          visible: false */
        },
      ],

      order: [[0, "ASC"]],

      createdRow: (row, data) => {
        $(row).attr("id", "menus_" + data.id_social);
      },
    })
    .DataTable();
  $("#tabla_lista_menus thead").addClass("thead-dark text-center");
});

function handleAuthorized(id_product) {
  let data = new FormData();
  data.append("id_product", id_product);

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    type: "post", //método de envio
    url: `${urls}qhse/autorizar_epp`, //archivo que recibe la peticion
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    dataType: "json",
    success: function (resp) {
      if (resp) {
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
}

function handleDelete(folio) {
  Swal.fire({
    title: `Eliminar Menú: ${folio}`,
    text: "Eliminar Menú ?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Eliminar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.value) {
      const timerInterval = Swal.fire({
        //se le asigna un nombre al swal
        title:
          '<i class="fas fas fa-trash-alt" style="margin-right: 10px;"></i>¡Menú Eliminando!',
        html: "Espere unos Segundos.",
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading(); //remplaza boton "OK" por animacion de "circulo Cargando"
        },
      });
      let dataForm = new FormData();
      dataForm.append("folio", folio);
      $.ajax({
        data: dataForm, //datos que se envian a traves de ajax
        url: `${urls}qhse/eliminar_menu`, //archivo que recibe la peticion
        type: "post", //método de envio
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentType
        dataType: "json",
        success: function (resp) {
          console.log(resp);
          Swal.close(timerInterval);
          if (resp) {
            tbl_menus.ajax.reload(null, false);
            Swal.fire("!Eliminado correctamente!", "", "success");
          } else {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Algo salió Mal! Contactar con el Administrador",
            });
            // console.log("Mal Revisa");
          }
        },
      });
    }
  });
}

$("#agregar_menus").submit(function (e) {
  e.preventDefault();
  const btn = document.getElementById("btn_social");
  const nombreMenu = document.getElementById("nombre_menu");
  const tipoMenu = document.getElementById("tipo_menu");
  const fechaEvento = document.getElementById("fecha_evento");

  var error_menu = "";
  var error_tipo_menu = "";

  if (nombreMenu.value.length == 0) {
    error_menu = "Campo Requerido";
    nombreMenu.classList.add("has-error");
    document.getElementById("error_menu").textContent = error_menu;
  } else {
    error_menu = "";
    nombreMenu.classList.remove("has-error");
    document.getElementById("error_menu").textContent = error_menu;
  }

  if (tipoMenu.value.length == 0) {
    error_tipo_menu = "Campo Requerido";
    tipoMenu.classList.add("has-error");
    document.getElementById("error_tipo_menu").textContent = error_tipo_menu;
  } else {
    error_tipo_menu = "";
    tipoMenu.classList.remove("has-error");
    document.getElementById("error_tipo_menu").textContent = error_tipo_menu;
  }

  if (fechaEvento.value.length == 0) {
    error_evento = "Campo Requerido";
    fechaEvento.classList.add("has-error");
    document.getElementById("error_fecha").textContent = error_evento;
  } else {
    error_evento = "";
    fechaEvento.classList.remove("has-error");
    document.getElementById("error_fecha").textContent = error_evento;
  }



  if (error_menu != "" || error_tipo_menu != "" || error_evento != "") {
    return false;
  }

  btn.disabled = true;

  // Mostrar SweetAlert de carga
  Swal.fire({
    title: "Procesando...",
    text: "Por favor, espera mientras procesamos tu solicitud.",
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });

  const data = new FormData($("#agregar_menus")[0]);

  $.ajax({
    url: `${urls}qhse/agregar_menu`,
    type: "POST", // Tipo de solicitud (puede ser GET o POST)
    data: data,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (response) {
      // Cerrar el Swal de carga y mostrar resultado
      Swal.close();
      Swal.fire(
        "Éxito",
        "La solicitud fue procesada correctamente.",
        "success"
      );
      $("#resultado").html(response);
      tbl_menus.ajax.reload(null, false);
      btn.disabled = false;
      nombreMenu.value ="";
        tipoMenu.value ="";
     fechaEvento.value ="";

    },
    error: function (xhr, status, error) {
      // Cerrar el Swal de carga y mostrar mensaje de error
      Swal.close();
      Swal.fire(
        "Error",
        "Hubo un problema con la solicitud: " + error,
        "error"
      );
    },
  });
});

function OnOffExtra(id_item) {
  const menu = $(`#menus_${id_item} td`)[1].innerHTML;
  $("#permiso_extra_" + id_item).prop("disabled", true);
  $("#lbl_extra_" + id_item).empty();
  if ($("#permiso_extra_" + id_item).is(":checked")) {
    texto = "ACTIVO";
    estado = 1;
  } else {
    texto = "INACTIVO";
    estado = 2;
  }
  $("#lbl_extra_" + id_item).append(texto);
  let data = new FormData();
  data.append("id", id_item);
  data.append("status", estado);
  $.ajax({
    data: data,
    url: `${urls}qhse/activar_menu`,
    type: "post",
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (response) {
      $("#permiso_extra_" + id_item).prop("disabled", false);
      if (response) {
        Swal.fire(
          `!MENÚ ${texto}!`,
          `El Menú esta ${texto} correctamente.`,
          "success"
        );
        // Actualiza los datos de la fila afectada sin recargar toda la tabla
        var row = tbl_menus.row(`#menus_${id_item}`);
        var rowData = row.data();
        rowData.active_menu = estado;
        row.data(rowData).draw(false);
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
      }
    },
  }).fail(function (jqXHR, textStatus, errorThrown) {
    $("#permiso_extra_" + id_item).prop("disabled", false);
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
