/**
 * ARCHIVO MODULO PERMISSIONS
 * AUTOR:HORUS SAMAEL RIVAS PEDRAZA
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:56 2439 2632
 */
 $(document).ready(function () {
    tbl_permissions = $("#tabla_autorizar_permisos")
      .dataTable({
        processing: true,
        ajax: {
          method: "post",
          url: `${urls}permisos/autorizacion-permisos`,
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
              columns: [ 1, 2, 3, 4, 5, 6, 0, 7],
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
          /* {
            data: null,
            render: function (data, type, full, meta) {
              var objFechaCreacion = new Date(data["fecha_creacion"]);
              var dia = (objFechaCreacion.getDate()+1).toString().padStart(2, "0");
              var mes = (objFechaCreacion.getMonth() + 1)
                .toString()
                .padStart(2, "0");
                if(dia == 32){dia="01"; var mes = (objFechaCreacion.getMonth() + 2)
                .toString()
                .padStart(2, "0"); }
              var anio = objFechaCreacion.getFullYear();
              // Devuelve: '1/2/2011':
              let fecha_creacion = dia + "-" + mes + "-" + anio;
              return $.trim(data["fecha_creacion"]) === "0000-00-00"
                ? "---"
                : ` <div class="mr-auto">${fecha_creacion} </div> `;
            },
            title: "CREACIÓN",
            className: "text-center",
          }, */
          {
            data: "nombre_solicitante",
            title: "USUARIO",
          },
          {
            data: "observaciones",
            title: "OBSERVACIONES",
            className: "text-center",
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
              var anio = objFechaSalida.getFullYear();
              // Devuelve: '1/2/2011':
              let fecha_salida = dia + "-" + mes + "-" + anio;
  
              const hrSalida = data["hora_salida"];
              return $.trim(data["fecha_salida"]) === "0000-00-00"
                ? "---"
                : ` <div class="mr-auto">${data["fecha_salida"]} - ${hrSalida} </div> `;
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
              var anio = objFechaEntrada.getFullYear();
              // Devuelve: '1/2/2011':
              let fecha_entrada = dia + "-" + mes + "-" + anio;
              const hrEntrada = data["hora_entrada"];
              return $.trim(data["fecha_entrada"]) === "0000-00-00"
                ? "---"
                : ` <div class="mr-auto"> ${data["fecha_entrada"]}- ${hrEntrada} </div> `;
            },
            title: "ENTRADA",
            className: "text-center",
          },
          {
            data: null,
            render: function (data, type, full, meta) {
              var objFechaInicio = new Date(data["inasistencia_del"]);
              var dia = objFechaInicio.getDate()+1;
              var mes = objFechaInicio.getMonth() +1;
              var anio = objFechaInicio.getFullYear();
              var objFechaFin = new Date(data["inasistencia_al"]);
              var dia_fin = (objFechaFin.getDate()+1).toString().padStart(2, "0");
              var mes_fin = (objFechaFin.getMonth() + 1)
                .toString()
                .padStart(2, "0");
              var anio_fin = objFechaFin.getFullYear();
              // Devuelve: '1/2/2011':
              let inasistencia_del = dia + "-" + mes + "-" + anio;
              let inasistencia_al = dia_fin + "-" + mes_fin + "-" + anio_fin;
              return $.trim(data["inasistencia_del"]) == "0000-00-00"
                ? "---"
                : ` <div class="mr-auto">Del: ${data["inasistencia_del"]} </br> Al: ${data["inasistencia_al"]} </div> `;
            },
            title: "INASISTENCIA",
            className: "text-center",
          },
          {
            data: null,
            render: function (data, type, full, meta) {
              switch (data["tipo_permiso"]) {
                case "PERSONAL":
                  return `<span class="badge" style="color:#fff;background-color: #7DCD67;">${data["tipo_permiso"]}</span>`;
                  break;
                case "LABORAL":
                  return `<span class="badge" style="color:#fff;background-color:#2B43C6;">${data["tipo_permiso"]}</span>`;
                  break;
                  case "SERVICIO MEDICO":
                    return `<span class="badge" style="color:#fff;background-color:#3FC3EE;">${data["tipo_permiso"]}</span>`;
                    break;
                default:
                  return `<span class="badge badge-secondary">NO DEFINIDO</span>`;
                  break;
              }
          },
          title: "TIPO PERMISO",
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
            title: "Acciones",
            className: "text-center",
          },
        ],
        destroy: "true",
        columnDefs: [
          {
            targets: 8,
            render: function (data, type, full, meta) {
                return `<div class=" mr-auto">
                <button type="button" class="btn btn-outline-primary btn-sm" title="Autorizar Permiso" onClick=handleChange(${
                  data["id_es"]
                })>
                <i class="fas fa-user-check"></i>
          </button>
          <a href="${urls}permisos/ver-permisos/${$.md5(
                  key + data["id_es"]
                )}" target="_blank" class="btn btn-outline-info btn-sm">
                <i class="fas fa-eye"></i>
          </a>
             </div> `;
              
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
          $(row).attr("id", "permissions_" + data.id_es);
        },
      })
      .DataTable();
    $("#tabla_autorizar_permisos thead").addClass("thead-dark text-center");
   
    /*tabla para permisos de vacaciones */
    tbl_vacations = $("#tabla_autorizar_vacaciones")
      .dataTable({
        processing: true,
        ajax: {
          method: "post",
          url: `${urls}permisos/autorizacion-vacaciones`,
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
            title: "Vacaciones",
            exportOptions: {
              columns: [0, 1, 2, 3, 4, 5],
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
            data: "id_vcns",
            title: "FOLIO",
            className: "text-center"
          },
          {
            data: null,
            render: function (data, type, full, meta) {
              var objFechaCreacion = new Date(data["fecha_registro"]);
              var dia = (objFechaCreacion.getDate()).toString().padStart(2, "0");
              var mes = (objFechaCreacion.getMonth() + 1)
                .toString()
                .padStart(2, "0");
              var anio = objFechaCreacion.getFullYear();
              // Devuelve: '1/2/2011':
              let fecha_creacion = dia + "-" + mes + "-" + anio;
              return $.trim(data["fecha_creacion"]) === "0000-00-00"
                ? "---"
                : ` <div class="mr-auto">${fecha_creacion} </div> `;
            },
            title: "CREACIÓN",
            className: "text-center",
          },
          {
            data: "nombre_solicitante",
            title: "USUARIO",
          },
          {
            data: null,
            render: function (data, type, full, meta) {
              var objFechaSalida = new Date(data["dias_a_disfrutar_del"]);
              var dia = (objFechaSalida.getDate()+1).toString().padStart(2, "0");
              var mes = (objFechaSalida.getMonth() + 1)
              .toString()
              .padStart(2, "0");
              if(dia == 32){dia="01"; var mes = (objFechaSalida.getMonth() + 2)
              .toString()
              .padStart(2, "0"); }
              var anio = objFechaSalida.getFullYear();
              // Devuelve: '1/2/2011':
              let fecha_salida = dia + "-" + mes + "-" + anio;
              return $.trim(data["dias_a_disfrutar_del"]) === "0000-00-00"
                ? "---"
                : ` <div class="mr-auto"> ${fecha_salida} </div> `;
            },
            title: "DEL",
            className: "text-center",
          },
          {
            data: null,
            render: function (data, type, full, meta) {
              var objFechaEntrada = new Date(data["dias_a_disfrutar_al"]);
              var dia = (objFechaEntrada.getDate()+1).toString().padStart(2, "0");
              var mes = (objFechaEntrada.getMonth() + 1)
                .toString()
                .padStart(2, "0");
                if(dia == 32){dia="01"; var mes = (objFechaEntrada.getMonth() + 2).toString().padStart(2, "0"); }
              var anio = objFechaEntrada.getFullYear();
              // Devuelve: '1/2/2011':
              let fecha_entrada = dia + "-" + mes + "-" + anio;
              return $.trim(data["dias_a_disfrutar_al"]) === "0000-00-00"
                ? "---"
                : ` <div class="mr-auto"> ${fecha_entrada} </div> `;
            },
            title: "AL",
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
            title: "Acciones",
            className: "text-center",
          },
        ],
        destroy: "true",
        columnDefs: [
          {
            targets: 6,
            render: function (data, type, full, meta) {
                return `<div class=" mr-auto">
                <button type="button" class="btn btn-primary btn-sm " title="Autorizar Vacaciones"  onClick=handleChangeVacation(${
                  data["id_vcns"]
                })>
                <i class="fas fa-user-check"></i>
                </button>
          <a href="${urls}permisos/vacaciones/${$.md5(
            key + data["id_vcns"]
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
          $(row).attr("id", "vacation_" + data.id_vcns);
        },
      })
      .DataTable();
  
    $("#tabla_autorizar_vacaciones thead").addClass("thead-dark text-center");
  });
  
  /**
   * 
   * tabla de permisos
   */
  function handleChange(id_folio) {
    let data = new FormData();
    data.append("id_folio", id_folio);
  
    $.ajax({
      data: data, //datos que se envian a traves de ajax
      url: `${urls}permisos/editar_permiso`, //archivo que recibe la peticion
      type: "post", //método de envio
      processData: false, // dile a jQuery que no procese los datos
      contentType: false, // dile a jQuery que no establezca contentType
      async: true,
      dataType: "json",
      success: function (resp) {
        //console.log(resp);
        if (resp != "error") {
          resp.forEach(function (persona, index) {
            $("#autorizarModal").modal("show");
            $("#usuario").val(persona.user);
            $("#puesto_solicitado").val(persona.puesto_solicitado);
            $("#motivo").val(persona.observaciones);
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
  
  $("#autorizar_permiso").submit(function (e) {
    e.preventDefault();
    let data = new FormData();
    let estatus = $("#estatus").val();
    data.append("id_folio", $("#id_folio").val());
    data.append("estatus", estatus);
  
    $.ajax({
      data: data, //datos que se envian a traves de ajax
      url: `${urls}permisos/autorizar-permiso`, //archivo que recibe la peticion
      type: "post", //método de envio
      processData: false, // dile a jQuery que no procese los datos
      contentType: false, // dile a jQuery que no establezca contentType
      success: function (response) {
        //una vez que el archivo recibe el request lo procesa y lo devuelve
        //console.log(response);
        $("#autorizarModal").modal("toggle");
        /*codigo que borra todos los campos del form newProvider*/
        if (response != false) {
          Swal.fire("!El Permiso ha sido Actualizado !", "", "success");
          tbl_permissions.ajax.reload(null, false);
        } else {
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
  });
  /**
   * 
   * @param Tabla de Vacaciones
   */
  
  
  function handleChangeVacation(id_folio) {
    let data = new FormData();
  
    data.append("id_folio", id_folio);
  
    $.ajax({
      data: data, //datos que se envian a traves de ajax
      url: `${urls}permisos/editar_vacaciones`, //archivo que recibe la peticion
      type: "post", //método de envio
      processData: false, // dile a jQuery que no procese los datos
      contentType: false, // dile a jQuery que no establezca contentType
      async: true,
      dataType: "json",
      success: function (resp) {
        //console.log(resp);
        if (resp != "error") {
          $("#id_folio_vacaciones").val(id_folio);
          resp.forEach(function (persona, index) {
            $("#autorizarVacacionesModal").modal("show");
            $("#usuario_vacaciones").val(persona.nombre_solicitante);
            $("#vacaciones_del").val(persona.dias_a_disfrutar_del);
            $("#vacaciones_al").val(persona.dias_a_disfrutar_al);
            $("#regresando").val(persona.regreso);
            $("#dias").val(persona.num_dias_a_disfrutar);
            $("#num_nomina").val(persona.num_nomina);
          });
          
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
  
  $("#autorizar_vacaciones").submit(function (e) {
    e.preventDefault();
    let data = new FormData();
    let estatus = $("#estatus_vacaciones").val();
    let dias = $("#dias").val();
    let num_nomina = $("#num_nomina").val();
    let id_folio = $("#id_folio_vacaciones").val();
    data.append("id_folio", id_folio);
    data.append("estatus", estatus);
    data.append("dias", dias);
    data.append("num_nomina", num_nomina);
  
    $.ajax({
      data: data, //datos que se envian a traves de ajax
      url: `${urls}permisos/autoriza-vacaciones`, //archivo que recibe la peticion
      type: "post", //método de envio
      processData: false, // dile a jQuery que no procese los datos
      contentType: false, // dile a jQuery que no establezca contentType
      success: function (response) {
        //una vez que el archivo recibe el request lo procesa y lo devuelve
        //console.log(response);
        $("#autorizarVacacionesModal").modal("toggle");
        /*codigo que borra todos los campos del form newProvider*/
        if (response != false) {
          Swal.fire("!El Permiso de Vacaciones ha sido Actualizado !", "", "success");
          tbl_vacations.ajax.reload(null, false);
        } else {
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
        
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: `Uncaught Error: ${jqXHR.responseText}`,
        });
      }
    });
  });
  
  