/**
 * ARCHIVO MODULO PAPELERIA
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */

$(document).ready(function () {
  tbl_epp = $("#tabla_solicitudes_epp")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}qhse/listado_solicitudes_epp`,
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
          title: "Inventerio de EPP",
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
          data: "id_request",
          title: "FOLIO",
          className: "text-center",
        },
        {
          data: "name",
          title: "USUARIO",
          className: "text-center",
        },
        {
          data: "job_position",
          title: "PUESTO",
          className: "text-center",
        },
        {
          data: "departament",
          title: "DEPARTAMENTO",
          className: "text-center",
        },
        {
          data: "pw_security",
          title: "CLAVE",
          className: "text-center",
        },
        {
          data: null,
          render: function (data) {
            switch (data["request_status"]) {
              case "1":
                result = `<span class="badge badge-warning">Pendiente</span>`;
                break;
              case "2":
                result = `<span class="badge badge-success">Entregado</span>`;
                break;
              case "3":
                result = `<span class="badge" style="color:#fff;background-color:#f76a77;">Cancelado</span>`;
                break;

              default:
                result = `<span class="badge badge-warning">Error</span>`;
                break;
            }
            return result;
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
                    <a href="https://sie.grupowalworth.com/qhse/ver-epp/${$.md5(
                      key + data["id_request"]
                    )}" target="_blank" class="btn btn-outline-info btn-sm">
                      <i class="fas fa-eye"></i>
                    </a>
                    <button type="button" class="btn btn-outline-danger btn-sm" title="Eliminar Suministro"  onClick=handleDelete(${
                      data["id_request"]
                    })>
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
        $(row).attr("id", "request_" + data.id_request);
      },
    })
    .DataTable();
  $("#tabla_solicitudes_epp thead").addClass("thead-dark text-center");
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
    title: `Eliminar Solicitud EPP: ${folio}`,
    text: "Eliminar Solicitud ?",
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
          '<i class="fas fas fa-trash-alt" style="margin-right: 10px;"></i>¡Eliminando Registro!',
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
        url: `${urls}qhse/eliminar_epp`, //archivo que recibe la peticion
        type: "post", //método de envio
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentType
        dataType: "json",
        success: function (resp) {
          console.log(resp);
          Swal.close(timerInterval);
          if (resp) {
            tbl_epp.ajax.reload(null, false);
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

$("#epp_reporte").submit(function (e) {
  e.preventDefault();
  const btn = document.getElementById("btn_servicio_reporte");
  const finFecha = document.getElementById("servicio_fecha_fin");
  const iniFecha = document.getElementById("servicio_fecha_ini");

  var error_fecha = "";
  var error_fecha_ini = "";
  var error_tipo = "";

  if (iniFecha.value.length == 0) {
    error_fecha_ini = "Campo Requerido";
    iniFecha.classList.add("has-error");
    document.getElementById("error_" + iniFecha.id).textContent =
      error_fecha_ini;
  } else {
    error_fecha_ini = "";
    iniFecha.classList.remove("has-error");
    document.getElementById("error_" + iniFecha.id).textContent =
      error_fecha_ini;
  }

  if (finFecha.value.length == 0) {
    error_fecha = "Campo Requerido";
    finFecha.classList.add("has-error");
    document.getElementById("error_" + finFecha.id).textContent = error_fecha;
  } else if (finFecha.value < iniFecha.value) {
    error_fecha = "Fecha Final debe ser mayor a Fecha Inicial";
    finFecha.classList.add("has-error");
    document.getElementById("error_" + finFecha.id).textContent = error_fecha;
  } else {
    error_fecha = "";
    finFecha.classList.remove("has-error");
    document.getElementById("error_" + finFecha.id).textContent = error_fecha;
  }

  if (error_fecha != "" || error_fecha_ini != "") {
    return false;
  }

  btn.disabled = true;

  var fecha_inicio = iniFecha.value;
  var fecha_fin = finFecha.value;
  var nomArchivo = `Reporte_entrega_epp_${fecha_inicio}_${fecha_fin}.xlsx`;

  
    let timerInterval = Swal.fire({
      //se le asigna un nombre al swal
      icon: "success",
      iconHtml:
        '<i class="far fa-file-excel nav-icon" style="font-size: 50px;margin: inherit;"></i>',
      title: "Generando Reporte !",
      html: "Espere unos Segundos.",
      timerProgressBar: true,
      didOpen: () => {
        Swal.showLoading(); //remplaza boton "OK" por animacion de "circulo Cargando"
      },
    });

    var end_point = "generar_reportes_epp";

    var param = JSON.stringify({
      fechaInicio: fecha_inicio,
      fechaFin: fecha_fin,
    });
    var pathservicehost = `${urls}qhse/reporte-epp`;
    var xhr = new XMLHttpRequest();
    xhr.open("POST", pathservicehost, true);
    xhr.responseType = "blob";
    //Send the proper header information along with the request
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function (e) {
      Swal.close(timerInterval); // cierra el swal en ejecucion (nombre del swal)
      btn.disabled = false;
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
 // }
});
