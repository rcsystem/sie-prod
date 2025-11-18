/**
 * ARCHIVO MODULO REQUISICIONES
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
$(document).ready(function () {
  tbl_requisitions = $("#tabla_todas_requisiciones")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: urls + "requisiciones/todas_requisiciones",
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
          title: "Requisiciones",
          exportOptions: {
            columns: [0, 1, 2, 3, 4],
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
          data: "id_folio",
          title: "FOLIO",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            var objFechaCreacion = new Date(data["fecha_creacion"]);
            var dia = objFechaCreacion.getDate().toString().padStart(2, "0");
            var mes = (objFechaCreacion.getMonth() + 1)
              .toString()
              .padStart(2, "0");
            var anio = objFechaCreacion.getFullYear();
            var hora = objFechaCreacion.getHours();
            var minutos = objFechaCreacion.getMinutes();
            // Devuelve: '1/2/2011':
            let fecha_creacion = dia + "-" + mes + "-" + anio;
            let hora_creacion = hora + ":" + minutos;
            return $.trim(data["fecha_creacion"]) === "0000-00-00"
              ? "---"
              : ` <div class="mr-auto"> ${fecha_creacion} ${hora_creacion} </div> `;
          },
          title: "FECHA CREACIÓN",
          className: "text-center",
        },
        
        {
          data: null,
          render: function (data, type, full, meta) {
            return data["name"] + " " + data["surname"];
          },
          title: "USUARIO",
          className: "text-center",
        },
        {
          data: "depto",
          title: "DEPARTAMENTO",
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
          title: "ACCIONES",
          className: "text-center",
        },
      ],
      destroy: "true",
      columnDefs: [
        {
          targets: 5,
          render: function (data, type, full, meta) {
            return ` <div class=" mr-auto">
                       <button type="button" class="btn btn-primary btn-sm" title="Editar Requisiciones"  onClick=handleEdit(${
                         data["id_folio"]
                       })>
                             <i class="far fa-edit"></i>
                       </button>
                       <a href="${urls}requisiciones/ver-requisicion/${$.md5(
              key + data["id_folio"]
            )}" title="Ver Requisición" target="_blank" class="btn btn-info btn-sm">
                             <i class="fas fa-eye"></i>
                       </a>
                     </div> `;
          },
        },
        /*           {
           targets: [3],
           className:"text-center"
         },  */
      ],

      order: [[0, "DESC"]],

      createdRow: (row, data) => {
        $(row).attr("id", "request_" + data.id_folio);
      },
    })
    .DataTable();
  $("#tabla_todas_requisiciones thead").addClass("thead-dark text-center");
});

$("#actualizar_requisicion").submit(function (event) {
  event.preventDefault();
  $("#actualiza_requisicion").prop("disabled", true);

  let data = new FormData();

  data.append("tipo_personal", $("#tipo_personal").val());
  data.append("puesto_solicitado", $("#puesto_solicitado").val());
  data.append("motivo", $("#motivo").val());
  data.append("remplazo", $("#remplazo").val());
  data.append("cotizacion", $("#cotizacion").val());
  data.append("periodo", $("#periodo").val());
  data.append("genero", $("#genero").val());
  data.append("estado_civil", $("#estado_civil").val());
  data.append("edad_minima", $("#edad_minima").val());
  data.append("edad_maxima", $("#edad_maxima").val());
  data.append("rolar", $("#rolar").val());
  data.append("licencia", $("#licencia").val());
  data.append("experiencia", $("#experiencia").val());
  data.append("trato", $("#trato").val());
  data.append("manejo", $("#manejo").val());
  data.append("jornada", $("#jornada").val());
  data.append("empresa", $("#empresa").val());
  data.append("centro_costo", $("#centro_costo").val());
  data.append("area_operativa", $("#area_operativa").val());
  data.append("id_folio", $("#id_folio").val());
  data.append("tipo_personal", $("#tipo_personal").val());
  data.append("puesto_solicitado", $("#puesto_solicitado").val());
  data.append("personas_requeridas", $("#personas_requeridas").val());
  data.append("estudios", $("#estudios").val());
  data.append("salario_inicial", $("#salario_inicial").val());
  data.append("salario_final", $("#salario_final").val());
  data.append("horario_inicial", $("#horario_inicial").val());
  data.append("horario_final", $("#horario_final").val());
  data.append("jefe_inmediato", $("#jefe_inmediato").val());
  data.append("status", $("#status_req").val());
  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: urls + "requisiciones/actualizar_requisicion", //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    success: function (response) {
      //una vez que el archivo recibe el request lo procesa y lo devuelve
      //console.log(response);
      /*codigo que borra todos los campos del form newProvider*/
      if (response != "error") {
        setTimeout(function () {
          tbl_requisitions.ajax.reload(null, false);
        }, 100);
        $("#actualiza_requisicion").prop("disabled", false);
        $("#editarModal").modal("toggle");
        Swal.fire("!Los datos se han Actualizado!", "", "success");
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
      console.log("Mal Revisa entro en el error");
    },
  });
});

function handleEdit(id_folio) {
  //console.log("Hola Mundo Edit" + id_supplies);
  let data = new FormData();

  data.append("id_folio", id_folio);

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: urls + "requisiciones/editar_requisicion", //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    async: true,
    dataType: "json",
    success: function (resp) {
      //console.log(resp);
      //console.log(resp);
      if (resp != "error") {
        resp.forEach(function (data, index) {
          $("#empresa").val(data.empresa_solicitante);
          $("#centro_costo").val(data.centro_costos);
          $("#area_operativa").val(data.area_operativas);
          $("#tipo_personal").val(data.tipo_de_personal);
          $("#puesto_solicitado").val(data.puesto_solicitado);
          $("#motivo").val(data.motivo_requisicion);
          $("#jefe").val(data.jefe_inmediato);
          $("#remplazo").val(data.colaborador_reemplazo);
          $("#cotizacion").val(data.cotizacion);
          $("#periodo").val(data.periodo);
          $("#genero").val(data.genero_requerido);
          $("#estado_civil").val(data.estado_civil);
          $("#edad_minima").val(data.edad_minima);
          $("#edad_maxima").val(data.edad_maxima);
          $("#rolar").val(data.rolar_turno);
          $("#licencia").val(data.licencia_conducir);
          $("#experiencia").val(data.anios_experiencia);
          $("#trato").val(data.trato_cli_prov);
          $("#manejo").val(data.manejo_personal);
          $("#jornada").val(data.jornada);
          $("#id_folio").val(id_folio);
          $("#tipo_personal").val(data.tipo_de_personal);
          $("#puesto_solicitado").val(data.puesto_solicitado);
          $("#personas_requeridas").val(data.personas_requeridas);
          $("#horario_inicial").val(data.horario_inicial);
          $("#horario_final").val(data.horario_final);
          $("#salario_inicial").val(data.salario_inicial);
          $("#salario_final").val(data.salario_final);
          $("#jefe_inmediato").val(data.jefe_inmediato);
          $("#estudios").val(data.grado_estudios);
        });

        $("#editarModal").modal("show");
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
        console.log("Mal Revisa");
      }
    },
    error: function (jqXHR, status, error) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Algo salió Mal! Contactar con el Administrador",
      });
      console.log("Mal Revisa entro en el error: "+ error);
    },
  });
}

/**
 * DAR FORMATO A LA CANTIDAD DEL INPUT
 */
function MASK(form, n, mask, format) {
  if (format == "undefined") format = false;

  if (format || NUM(n)) {
    (dec = 0), (point = 0);
    x = mask.indexOf(".") + 1;
    if (x) {
      dec = mask.length - x;
    }
    if (dec) {
      n = NUM(n, dec) + "";
      x = n.indexOf(".") + 1;
      if (x) {
        point = n.length - x;
      } else {
        n += ".";
      }
    } else {
      n = NUM(n, 0) + "";
    }
    for (var x = point; x < dec; x++) {
      n += "0";
    }
    (x = n.length), (y = mask.length), (XMASK = "");
    while (x || y) {
      if (x) {
        while (y && "#0.".indexOf(mask.charAt(y - 1)) == -1) {
          if (n.charAt(x - 1) != "-") XMASK = mask.charAt(y - 1) + XMASK;
          y--;
        }
        (XMASK = n.charAt(x - 1) + XMASK), x--;
      } else if (y && "$0".indexOf(mask.charAt(y - 1)) + 1) {
        XMASK = mask.charAt(y - 1) + XMASK;
      }
      if (y) {
        y--;
      }
    }
  } else {
    XMASK = "";
  }
  if (form) {
    form.value = XMASK;
    if (NUM(n) < 0) {
      form.style.color = "#FF0000";
    } else {
      form.style.color = "#000000";
    }
  }
  return XMASK;
}

/* Convierte una cadena alfanumérica a numérica (incluyendo formulas aritméticas)
    s   = cadena a ser convertida a numérica
    dec = numero de decimales a redondear
    La función devuelve el numero redondeado */

function NUM(s, dec) {
  for (var s = s + "", num = "", x = 0; x < s.length; x++) {
    c = s.charAt(x);
    if (".-+/*".indexOf(c) + 1 || (c != " " && !isNaN(c))) {
      num += c;
    }
  }
  if (isNaN(num)) {
    num = eval(num);
  }
  if (num == "") {
    num = 0;
  } else {
    num = parseFloat(num);
  }
  if (dec != undefined) {
    r = 0.5;
    if (num < 0) r = -r;
    e = Math.pow(10, dec > 0 ? dec : 0);
    return parseInt(num * e + r) / e;
  } else {
    return num;
  }
}

function validaNumericos(event) {
  return event.charCode >= 48 && event.charCode <= 57 ? true : false;
}
