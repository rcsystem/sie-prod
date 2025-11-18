/**
 * ARCHIVO MODULO DASHBOARD VIGILANCIA
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
$(document).ready(function () {
  tbl_permissions = $("#tabla_permisos")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}permisos/permisos_autorizados`,
        dataSrc: "",
      },
      lengthChange: true,
      ordering: true,
      responsive: true,
      autoWidth: true,
      rowId: "staffId",
      dom: "lBfrtip",
      buttons: [
        {
          extend: "excelHtml5",
          title: "Permisos",
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6],
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
          data: "id_es",
          title: "FOLIO",
          className: "text-center",
        },

        {
          width: "15%",
          data: "nombre_solicitante",
          title: "USUARIO",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            var objFechaSalida = new Date(data["fecha_salida"]);
            var dia = (objFechaSalida.getDate() + 1)
              .toString()
              .padStart(2, "0");
            var mes = (objFechaSalida.getMonth() + 1)
              .toString()
              .padStart(2, "0");
              if(dia == 32){dia="01"; var mes = (objFechaSalida.getMonth() + 2)
              .toString()
              .padStart(2, "0"); }
            var anio = objFechaSalida.getFullYear();
            // Devuelve: '1/2/2011':
            let fecha_salida = dia + "-" + mes + "-" + anio;

            const hrSalida = data["hora_salida"];
            return $.trim(data["fecha_salida"]) === "0000-00-00"
              ? "---"
              : ` <div class="mr-auto"> ${fecha_salida} <br> ${hrSalida} </div> `;
          },
          title: "SALIDA",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            var objFechaEntrada = new Date(data["fecha_entrada"]);
            var dia = (objFechaEntrada.getDate() + 1)
              .toString()
              .padStart(2, "0");
            var mes = (objFechaEntrada.getMonth() + 1)
              .toString()
              .padStart(2, "0");
              if(dia == 32){dia="01"; var mes = (objFechaEntrada.getMonth() + 2)
              .toString()
              .padStart(2, "0"); }
            var anio = objFechaEntrada.getFullYear();
            // Devuelve: '1/2/2011':
            let fecha_entrada = dia + "-" + mes + "-" + anio;
            const hrEntrada = data["hora_entrada"];
            return $.trim(data["fecha_entrada"]) === "0000-00-00"
              ? "---"
              : ` <div class="mr-auto"> ${fecha_entrada}<br> ${hrEntrada} </div> `;
          },
          title: "ENTRADA",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            var objFechaInicio = new Date(data["inasistencia_del"]);
            var dia = objFechaInicio.getDate().toString().padStart(2, "0");
            var mes = (objFechaInicio.getMonth() + 1)
              .toString()
              .padStart(2, "0");
              
            var anio = objFechaInicio.getFullYear();
            var objFechaFin = new Date(data["inasistencia_al"]);
            var dia_fin = objFechaFin.getDate().toString().padStart(2, "0");
            var mes_fin = (objFechaFin.getMonth() + 1)
              .toString()
              .padStart(2, "0");
              if(dia == 32){dia="01"; var mes = (objFechaFin.getMonth() + 2)
              .toString()
              .padStart(2, "0"); }
            var anio_fin = objFechaFin.getFullYear();
            // Devuelve: '1/2/2011':
            let inasistencia_del = dia + "-" + mes + "-" + anio;
            let inasistencia_al = dia_fin + "-" + mes_fin + "-" + anio_fin;
            return $.trim(data["inasistencia_del"]) == "0000-00-00"
              ? "---"
              : ` <div class="mr-auto">Del: ${inasistencia_del} </br> Al: ${inasistencia_al} </div> `;
          },
          title: "AUSENCIA",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            switch (data["estatus"]) {
              case "Rechazada":
                return `<span class="badge badge-danger">${data["estatus"]}</span>`;
                break;
              case "Autorizada":
                return `<span class="badge badge-success">${data["estatus"]}</span>`;
                break;

              default:
                return `<span class="badge badge-warning">${data["estatus"]}</span>`;
                break;
            }
          },
          title: "ESTATUS",
          className: "text-center",
        },
        {
          data: null,
          title: "VER",
          className: "text-center",
        },
      ],
      destroy: "true",
      columnDefs: [
        {
          targets: 6,
          render: function (data, type, full, meta) {
            var hora_entrada ="";
              if (data["fecha_entrada"] != "0000-00-00") {
                 hora_entrada =(data["confirm_hora_entrada"] == null) 
                 ? `<button type="button" class="btn btn-outline-success btn-sm " title="Hora de Entrada"  onClick=handleEnter(${data["id_es"]})>
                      <i class="fas fa-user-clock"></i>
                    </button>
                    </div> ` 
                :`<button type="button" class="btn btn-outline-secondary btn-sm " title="Hora de Entrada">
                    <i class="fas fa-user-clock"></i>
                  </button>` ;

              }
           

            return ` <div class="pull-right mr-auto">
                        <a href="${urls}permisos/ver-permisos/${$.md5(
              key + data["id_es"]
            )}" target="_blank" class="btn btn-outline-info btn-sm">
                              <i class="fas fa-eye"></i>
                        </a>
                        ${hora_entrada}
                       `;
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
        $(row).attr("id", "permissions_" + data.id_es);
      },
    })
    .DataTable();

  $("#tabla_permisos thead").addClass("thead-dark text-center");

  tbl_provee = $("#tabla_proveedores_visitas")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}qhse/proveedores_visitas`,
        dataSrc: "",
      },
      lengthChange: true,
      ordering: true,
      responsive: true,
      autoWidth: true,
      lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
      rowId: "staffId",
    /*   dom: "lBfrtip",
      buttons: [
        {
          extend: "excelHtml5",
          title: "Visitas Proveedores",
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6],
          },
        },
         {
              extend:'pdfHtml5',
              title:'Listado de Proveedores',
              exportOptions:{
                columns:[1,2,3,4,5,6,7]
              }
            }      
           ], */
      language: {
        url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
      },
      columns: [
        {
          data: "id",
          title: "FOLIO",
          className: "text-center",
        },

        {
          data: "person_you_visit",
          title: "USUARIO",
        },

        {
          data: "suppliers",
          title: "PROVEEDOR",
        },

        {
          data: null,
          render: function (data, type, full, meta) {
            var objFechaSalida = new Date(data["day_you_visit"]);
            var dia = (objFechaSalida.getDate() + 1)
              .toString()
              .padStart(2, "0");
            var mes = (objFechaSalida.getMonth() + 1)
              .toString()
              .padStart(2, "0");
            var anio = objFechaSalida.getFullYear();
            // Devuelve: '1/2/2011':
            let fecha_salida = dia + "-" + mes + "-" + anio;

            const hrSalida = data["time_of_entry"];
            return $.trim(data["fecha_salida"]) === "0000-00-00"
              ? "---"
              : ` <div class="mr-auto"> ${fecha_salida} <br> ${hrSalida} </div> `;
          },
          title: "ENTRADA",
          className: "text-center",
        },

        {
          data: null,
          render: function (data, type, full, meta) {
            switch (data["authorize"]) {
              case "3":
                return `<span class="badge badge-danger">Rechazada</span>`;
                break;
              case "2":
                return `<span class="badge badge-success">Autorizada</span>`;
                break;
              case "1":
                return `<span class="badge badge-warning">Pendiente</span>`;
                break;

              default:
                return `<span class="badge badge-default">Error</span>`;
                break;
            }
          },
          title: "ESTATUS",
          className: "text-center",
        },
        
        {
          data: "departament_you_visit",
          title: "DEPTO",
        },
        {
          data: null,
          title: "VER",
          className: "text-center",
        },
      ],
      destroy: "true",
      columnDefs: [
        {
          targets: 6,
          render: function (data, type, full, meta) {
            return ` <div class="pull-right mr-auto">
                        <a href="${urls}qhse/ver-permiso/${$.md5(
              key + data["id"]
            )}" target="_blank" class="btn btn-info btn-sm">
                              <i class="fas fa-eye"></i>
                        </a>
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
        $(row).attr("id", "proveedor_" + data.id);
      },
    })
    .DataTable();

  $("#tabla_proveedores_visitas thead").addClass("thead-dark text-center");

  tbl_over_time = $("#tabla_tiempo_extra")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}qhse/tiempos_extra`,
        dataSrc: "",
      },
      lengthChange: true,
      ordering: true,
      responsive: true,
      autoWidth: true,
      lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
      rowId: "staffId",
      /*dom: "lBfrtip",
       buttons: [
        {
          extend: "excelHtml5",
          title: "Tiempo Extra",
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6],
          },
        },
        {
            extend:'pdfHtml5',
            title:'Listado de Proveedores',
            exportOptions:{
              columns:[1,2,3,4,5,6,7]
            }
          } 
      ], */
      language: {
        url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
      },
      columns: [
        {
          data: "id",
          title: "FOLIO",
          className: "text-center",
        },

        {
          data: "name",
          title: "USUARIO",
        },

        {
          data: null,
          render: function (data, type, full, meta) {
            var objFechaSalida = new Date(data["day_you_visit"]);
            var dia = (objFechaSalida.getDate() + 1)
              .toString()
              .padStart(2, "0");
            var mes = (objFechaSalida.getMonth() + 1)
              .toString()
              .padStart(2, "0");
            var anio = objFechaSalida.getFullYear();
            // Devuelve: '1/2/2011':
            let fecha_salida = dia + "-" + mes + "-" + anio;

            const hrSalida = data["time_of_entry"];
            return $.trim(data["day_you_visit"]) === "0000-00-00"
              ? "---"
              : ` <div class="mr-auto"> ${fecha_salida} <br> ${hrSalida} </div> `;
          },
          title: "ENTRADA",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            var objFechaSalida = new Date(data["departure_time"]);

            const hrSalida = data["departure_time"];
            return $.trim(hrSalida) === "00:00:00"
              ? "---"
              : ` <div class="mr-auto">${hrSalida} </div> `;
          },
          title: "SALIDA",
          className: "text-center",
        },
       
        {
          data: null,
          render: function (data, type, full, meta) {
            switch (data["authorize"]) {
              case "3":
                return `<span class="badge badge-danger">Rechazada</span>`;
                break;
              case "2":
                return `<span class="badge badge-success">Autorizada</span>`;
                break;
              case "1":
                return `<span class="badge badge-warning">Pendiente</span>`;
                break;

              default:
                return `<span class="badge badge-default">Error</span>`;
                break;
            }
          },
          title: "ESTATUS",
          className: "text-center",
        },
        {
          data: "departament",
          title: "DEPTO",
          className: "text-center",
        },
        {
          data: null,
          title: "VER",
          className: "text-center",
        },
      ],
      destroy: "true",
      columnDefs: [
        {
          targets: 6,
          render: function (data, type, full, meta) {
            return ` <div class="pull-right mr-auto">
                      <a href="${urls}qhse/ver-tiempo-extra/${$.md5(
              key + data["id"]
            )}" target="_blank" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                      </a>
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
        $(row).attr("id", "overtime_" + data.id);
      },
    })
    .DataTable();

  $("#tabla_tiempo_extra thead").addClass("thead-dark text-center");
});

function validar() {
  if ($("#fecha_inicial").val().length > 0) {
    $("#error_fecha_inicial").text("");
    $("#fecha_inicial").removeClass('has-error');
  }
  if ($("#fecha_final").val().length > 0) {
    $("#error_fecha_final").text("");
    $("#fecha_final").removeClass('has-error');
  }

  if ($("#fecha_inicial_V").val().length > 0) {
    $("#error_fecha_inicial_V").text("");
    $("#fecha_inicial_V").removeClass('has-error');
  }
  if ($("#fecha_final_V").val().length > 0) {
    $("#error_fecha_final_V").text("");
    $("#fecha_final_V").removeClass('has-error');
  }
}

$("#formReportes_tiempo").on("submit", function (e) {
  e.preventDefault();
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

  if (error_fecha_inicial != "" || error_fecha_final != "" ) { return false; }
  $("#btn_Reportes_tiempo").prop("disabled", true);

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
  var nomArchivo = `Reporte_Horario_Obscuro_${fecha_inicio}_${fecha_fin}.xlsx`;
  var param = JSON.stringify({
      fecha_inicio: fecha_inicio,
      fecha_fin: fecha_fin,
  });
  var pathservicehost = `${urls}/qhse/genera_reportes_horaio`;

  var xhr = new XMLHttpRequest();
  xhr.open("POST", pathservicehost, true);
  xhr.responseType = "blob";

  //Send the proper header information along with the request
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onload = function (e) {
      Swal.close(timerInterval);
      $("#btn_Reportes_tiempo").prop("disabled", false);
      if (xhr.readyState === 4 && xhr.status === 200) {
          $("#fecha_inicial").val("");
          $("#fecha_final").val("");
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

$("#formReportes_visitas").on("submit", function (e) {
  e.preventDefault();
  if ($("#fecha_inicial_V").val().length == 0) {
      error_fecha_inicial = "Fecha Inicial Requerida";
      $("#error_fecha_inicial_V").text(error_fecha_inicial);
      $("#fecha_inicial_V").addClass('has-error');
  } else {
      error_fecha_inicial = "";
      $("#error_fecha_inicial_V").text(error_fecha_inicial);
      $("#fecha_inicial_V").removeClass('has-error');
  }

  if ($("#fecha_final_V").val().length == 0) {
      error_fecha_final = "Fecha Final Requerida";
      $("#error_fecha_final_V").text(error_fecha_final);
      $("#fecha_final_V").addClass('has-error');
  } else if ($("#fecha_final_V").val() < $("#fecha_inicial_V").val()) {
      error_fecha_final = "Fecha Final Incorrecta";
      $("#error_fecha_final_V").text(error_fecha_final);
      $("#fecha_final_V").addClass('has-error');
  } else {
      error_fecha_final = "";
      $("#error_fecha_final_V").text(error_fecha_final);
      $("#fecha_final_V").removeClass('has-error');
  }

  if (error_fecha_inicial != "" || error_fecha_final != "" ) { return false; }
  $("#btn_Reportes_visitas").prop("disabled", true);

  let timerInterval = Swal.fire({ //se le asigna un nombre al swal
      allowOutsideClick: false,
      title: '¡Generando Reporte!',
      timerProgressBar: true,
      didOpen: () => {
          Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
      },
  });

  let fecha_inicio = $("#fecha_inicial_V").val();
  let fecha_fin = $("#fecha_final_V").val();
  var nomArchivo = `Reporte_Visitas_${fecha_inicio}_${fecha_fin}.xlsx`;
  var param = JSON.stringify({
      fecha_inicio: fecha_inicio,
      fecha_fin: fecha_fin,
  });
  var pathservicehost = `${urls}/qhse/genera_reportes_visitas`;

  var xhr = new XMLHttpRequest();
  xhr.open("POST", pathservicehost, true);
  xhr.responseType = "blob";

  //Send the proper header information along with the request
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onload = function (e) {
      Swal.close(timerInterval);
      $("#btn_Reportes_visitas").prop("disabled", false);
      if (xhr.readyState === 4 && xhr.status === 200) {
          $("#fecha_inicial_V").val("");
          $("#fecha_final_V").val("");
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



function handleEnter(id_folio) {
  showTime();
  let data = new FormData();

  data.append("folio", id_folio);
  Swal.fire({
    title: `Agregar la Hora de Entrada del Permiso: ${id_folio} ?`,
    html: `<h2 id="HoraActual"></h2>`,
    icon: "info",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Confirmar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.value) {
     
      $.ajax({
        data: data, //datos que se envian a traves de ajax
        url: `${urls}permisos/hora_entrada`, //archivo que recibe la peticion
        type: "post", //método de envio
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentType
        success: function (response) {
          //una vez que el archivo recibe el request lo procesa y lo devuelve
          console.log(response);

          /*codigo que borra todos los campos del form newProvider*/
          if (response) {
            tbl_permissions.ajax.reload(null, false);
            Swal.fire('Se ha guardado correctamente la Hora de Entrada!', '', 'success')

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

function showTime(){
  myDate = new Date();
  hours = myDate.getHours();
  minutes = myDate.getMinutes();
  seconds = myDate.getSeconds();
  if (hours < 10) hours = 0 + hours;
  if (minutes < 10) minutes = "0" + minutes;
  if (seconds < 10) seconds = "0" + seconds;
  $("#HoraActual").text(hours+ ":" +minutes+ ":" +seconds);
  setTimeout("showTime()", 1000);
  
  }