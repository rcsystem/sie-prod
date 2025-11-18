/**
 * ARCHIVO MODULO INFO USUARIOS
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
$(document).ready(function () {
  tbl_info_users = $("#tabla_info_usuarios")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}usuarios/todo-info`,
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
          data: "id_datos",
          title: "IDS",
          className: "text-center",
        },
        {
          data: "num_nomina",
          title: "NUM NOMINA",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            $users = `${data["nombre"]} ${data["ape_paterno"]} ${data["ape_materno"]}`;
            return $users;
          },
          title: "USUARIO",
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
          targets: 3,
          render: function (data, type, full, meta) {
            ;
            return ` <div class="pull-right mr-auto">
             <button type="button" class="btn btn-danger btn-sm " title="Informacion del Usuario" onClick=infoUsers(${data["id_datos"]})>
             <i class="fas fa-user"></i>
             </button>

             <button type="button" class="btn btn-warning btn-sm " title="Familia del Usuario" onClick=infoFamily(${data["id_datos"]})>
             <i class="fas fa-users"></i>
             </button>

             <button type="button" class="btn btn-primary btn-sm " title="Contactos de Emergencia"  onClick=emergencyContact(${data["id_datos"]})>
              <i class="fas fa-phone"></i>
             </button>
            
              <button type="button" class="btn btn-success btn-sm "  onClick=dataExcelUsers(${data["id_datos"]})>
              <i class="fas fa-file-excel"></i>
              </button>

              <button type="button" class="btn btn-info btn-sm " title="Descargar Documentos" onClick=downloadDocumen(${data["id_datos"]})>
              <i class="fas fa-cloud-download-alt"></i>
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
        $(row).attr("id", "info_" + data.id_datos);
      },
    })
    .DataTable();

  $("#tabla_info_usuarios thead").addClass("thead-dark text-center");

});

function emergencyContact(id_datos) {
  let data = new FormData();

  data.append("id_datos", id_datos);
  let contador = 1;
  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}usuarios/contacto-emergencia`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    async: true,
    dataType: "json",
    success: function (resp) {
      if (resp != "error") {
        resp.forEach(function (contacto, index) {
          $(`#contacto_${contador}`).val(contacto.contacto_emergencia);
          $(`#parentesco_${contador}`).val(contacto.parentesco_emergencia);
          $(`#tel_${contador}`).val(contacto.tel_emergencia);
          let usuario_info = $(`#info_${id_datos} td`)[1].innerHTML;
          $(`#usuario_info`).text(usuario_info);
          contador++;
        });

        $("#emergencyContactModal").modal("show");
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

function dataExcelUsers(id_datos) {

  let timerInterval = Swal.fire({ //se le asigna un nombre al swal
    title: 'Generando Reporte en Excel!',
    html: 'Espere unos Segundos.',
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });
  let usuario_info = $(`#info_${id_datos} td`)[1].innerHTML;
  let user = usuario_info.toLowerCase();
  var nomArchivo = `info_usuario_${user}.xlsx`;
  var param = JSON.stringify({ "id_datos": id_datos });
  var pathservicehost = `${urls}/usuarios/info_general`;
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
      $("#reporte_datos_general").prop("disabled", false);
      Swal.close(timerInterval); // cierra el swal en ejecucion (nombre del swal)
      //link.click();
    } else {
      $("#reporte_datos_general").prop("disabled", false);

      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No es posible acceder al archivo, probablemente no existe. Comunicarse con el Administrador",
      });
    }
  };
  //xhr.send();
  xhr.send("data=" + param);

}

$("#formDatosGeneral").on("submit", function (e) {
  e.preventDefault();

  $("#reporte_datos_general").prop("disabled", true);
  let timerInterval = Swal.fire({ //se le asigna un nombre al swal
    allowOutsideClick: false,
    title: 'Generando Reporte en Excel!',
    html: 'Espere unos Segundos.',
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });
  var nomArchivo = `reporte_datos_generales.xlsx`;

  var pathservicehost = `${urls}/permisos/reporte_datos_generales`;
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
      $("#reporte_datos_general").prop("disabled", false);
      Swal.close(timerInterval); // cierra el swal en ejecucion (nombre del swal)
      //link.click();
    } else {
      $("#reporte_datos_general").prop("disabled", false);

      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "No es posible acceder al archivo, probablemente no existe. Comunicarse con el Administrador",
      });
    }
  };
  xhr.send();

});

function downloadDocumen(id_user) {
  $("#btn_todos").hide();
  let nombre = $(`#info_${id_user} td`)[1].innerHTML;
  $("#btn_todos").val();
  $("#btn_todos").val(id_user);
  $("#usuario_doc").empty();
  $("#usuario_doc").append(nombre);
  $("#nombre_user").val();
  $("#nombre_user").val(nombre);
  $('#documentModal').modal("show");
  $("#tbl_doc").empty();
  $("#tbl_doc").append(`
         <table id="tabla_document_usuarios_${id_user}" class="table table-bordered table-striped " role="grid" aria-describedby="equipos_info" style="width:100%" ref="">
          </table>
     `);
  tbl_doc_users = $("#tabla_document_usuarios_" + id_user)
    .dataTable({
      processing: true,
      ajax: {
        data: { 'id_datos': id_user },
        method: "post",
        url: `${urls}usuarios/todos_documentos`,
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
          data: null,
          render: function (data, type, full, meta) {
            $tipos = ['ERROR', 'DOMICILIO', 'ESTUDIOS', 'ACTA', 'DIPLOMA', 'CURSO', 'CERTIFICADO INGLES', 'CURP', 'RFC', 'CURRICULUM'];
            if (parseInt(data["tipo_document"]) >= 1 && parseInt(data["tipo_document"]) <= 9) {
              $tipo = $tipos[parseInt(data["tipo_document"])];
            } else {
              $tipo = $tipos[0];
            }
            return $tipo;
          },
          title: "TIPO",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            $descripcion = data["descripcion"].toUpperCase();
            return $descripcion;
          },
          title: "DESCRIPCION",
          className: "text-center",
        },
        {
          data: null,
          render: function (data, type, full, meta) {
            fecha = new Date(data["created_at"]);
            if (fecha.getMonth() + 1 < 10) { mes = `0${fecha.getMonth() + 1}`; }
            else { mes = fecha.getMonth() + 1; }
            if (fecha.getDate() < 10) { dia = `0${fecha.getDate()}`; }
            else { dia = fecha.getDate(); }
            $descripcion = `${dia} / ${mes} / ${fecha.getFullYear()}`;
            return $descripcion;
          },
          title: "FECHA",
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
          targets: 3,
          render: function (data, type, full, meta) {
            $("#btn_todos").show();
            return ` <div class="pull-right mr-auto">
              <button type="button" class="btn btn-info btn-sm " title="Descargar Documentos" onClick="oneDocument(${data["id_doc"]},'${data["ubicacion"]}','${nombre}')">
              <i class="fas fa-file-download"></i>&nbsp;&nbsp;Descargar
              </button>
            </div> `;
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
        $(row).attr("id", "doc_" + data.id_doc);
      },
    })
    .DataTable();
  $(`#tabla_document_usuarios_${id_user} thead`).addClass("thead-dark text-center");
}

function oneDocument(id_, urlUbiv, nombre) {
  const tipo = $(`#doc_${id_} td`)[0].innerHTML;
  $("#btn_dowload").prop("disabled", true);
  const downloadOneDocument = document.createElement('a');
  downloadOneDocument.href = `${urls}${urlUbiv}`;
  downloadOneDocument.download = `${tipo}_${nombre}`;
  // downloadOneDocument.target = "";
  var clicEvent = new MouseEvent("click", {
    view: window,
    bubbles: true,
    cancelable: true,
  });
  // downloadOneDocument.dispatchEvent(clicEvent);
  downloadOneDocument.click();

  $("#btn_dowload").prop("disabled", false);

}

function infoUsers(id_user) {
  let nombre = $(`#info_${id_user} td`)[1].innerHTML;
  $("#modal_body").empty();
  $("#tittle_modal").empty();
  $("#tittle_modal").append(`<i class="fas fa-user" style="margin-right: 0.5rem;"></i> INFORMACION DEL USUARIO: ${nombre}`);
  let id = new FormData();
  id.append('id_user', id_user);
  $.ajax({
    data: id,
    url: `${urls}usuarios/toda_informacion_usuario`,
    type: "post",
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (data) {
      fecha = new Date(data.personal.fecha_ingreso);
      if (fecha.getMonth() + 1 < 10) { mes = `0${fecha.getMonth() + 1}`; }
      else { mes = fecha.getMonth() + 1; }
      if ((fecha.getDate() + 1) < 10) { dia = `0${(fecha.getDate() + 1)}`; }
      else { dia = (fecha.getDate() + 1); }
      fecha_ingreso = `${dia} / ${mes} / ${fecha.getFullYear()}`;

      fechaN = new Date(data.personal.fecha_nacimiento);
      if (fechaN.getMonth() + 1 < 10) { mes = `0${fechaN.getMonth() + 1}`; }
      else { mes = fechaN.getMonth() + 1; }
      if ((fechaN.getDate() + 1) < 10) { dia = `0${(fechaN.getDate() + 1)}`; }
      else { dia = (fechaN.getDate() + 1); }
      fecha_nacimiento = `${dia} / ${mes} / ${fechaN.getFullYear()}`;
      if (data.personal.edad_conyuge == 0) { edad_conyuge = ""; } else { edad_conyuge = data.personal.edad_conyuge; }
      $("#modal_body").append(`
    <div class="form-row" style="height:3rem;text-align:center;"><div class="col-md-12"><label>INFORMACION GENERAL</label></div></div>
    <div class="row">
    <div class="col-md-1">
        <label style="margin-bottom:-8px;">Nomina:</label>
        <input type="text" class="form-control" value="${data.personal.num_nomina}" readonly>
      </div>
      <div class="col-md-3">
        <label style="margin-bottom:-8px;">Fecha de Ingreso:</label>
        <input type="text" class="form-control" value="${fecha_ingreso}" readonly>
      </div>
      <div class="col-md-3">
        <label style="margin-bottom:-8px;">Fecha de Nacimiento:</label>
        <input type="text" class="form-control" value="${fecha_nacimiento}" readonly>
      </div>
      <div class="col-md-1">
        <label style="margin-bottom:-8px;">Edad:</label>
        <input type="text" class="form-control" value="${data.personal.edad_usuario}" readonly>
      </div>
      <div class="col-md-2">
        <label style="margin-bottom:-8px;">Genero:</label>
        <input type="text" class="form-control" value="${data.personal.genero}" readonly>
      </div>
      <div class="col-md-2">
        <label style="margin-bottom:-8px;">Estado Civil:</label>
        <input type="text" class="form-control" value="${data.personal.estado_civil}" readonly>
      </div>      
    <div class="col-md-1"></div>
    </div>
    <div class="row" >
    <div class="col-md-3">
        <label style="margin-bottom:-8px;">CURP:</label>
        <input type="text" class="form-control" value="${data.personal.curp}" readonly>
      </div>
      <div class="col-md-2">
        <label style="margin-bottom:-8px;">RFC:</label>
        <input type="text" class="form-control" value="${data.personal.rfc}" readonly>
      </div>
      <div class="col-md-3">
        <label style="margin-bottom:-8px;">Escolatidad:</label>
        <input type="text" class="form-control" value="${data.personal.escolaridad}" readonly>
      </div>
      <div class="col-md-4">
        <label style="margin-bottom:-8px;">Titulo:</label>
        <input type="text" class="form-control" value="${data.personal.lic_ing}" readonly>
      </div>
    </div>
    <div class="form-row" style="height:3rem;text-align:center;padding-top:13px;"><div class="col-md-12"><label>DIRECCION</label></div></div>
   <div class="row">
   <div class="col-md-5">
       <label style="margin-bottom:-8px;">Calle:</label>
       <input type="text" class="form-control" value="${data.personal.calle}" readonly>
     </div>
     <div class="col-md-2">
       <label style="margin-bottom:-8px;">Número Interior:</label>
       <input type="text" class="form-control" value="${data.personal.numero_interior}" readonly>
     </div>
     <div class="col-md-2">
       <label style="margin-bottom:-8px;">Número Exterior:</label>
       <input type="text" class="form-control" value="${data.personal.numero_exterior}" readonly>
     </div>
     <div class="col-md-2">
       <label style="margin-bottom:-8px;">Codigo Postal:</label>
       <input type="text" class="form-control" value="${data.personal.codigo_postal}" readonly>
     </div>
   </div>
   <div class="row" >
     <div class="col-md-4">
       <label style="margin-bottom:-8px;">Colonia:</label>
       <input type="text" class="form-control" value="${data.personal.colonia}" readonly>
     </div>
     <div class="col-md-4">
       <label style="margin-bottom:-8px;">Municipio:</label>
       <input type="text" class="form-control" value="${data.personal.municipio}" readonly>
     </div>
     <div class="col-md-4">
       <label style="margin-bottom:-8px;">Estado:</label>
       <input type="text" class="form-control" value="${data.personal.estado}" readonly>
     </div>
   </div>
    <div class="form-row" style="height:3rem;text-align:center;padding-top:13px;"><div class="col-md-12"><label>DATOS DE CÓNYUGE</label></div></div>
    `);
      if (data.personal.nombre_conyuge == "" || data.personal.nombre_conyuge == null) {
        $("#modal_body").append(`<div class="form-row" style="height:3rem;text-align:center;padding-top:13px;"><div class="col-md-12"><H5>SIN CONYUGE</H5></div></div>`);
      } else {
        $("#modal_body").append(`
    <div class="row">
      <div class="col-md-5">
        <label style="margin-bottom:-8px;">Nombre:</label>
        <input type="text" class="form-control" value="${data.personal.nombre_conyuge}" readonly>
      </div>
      <div class="col-md-3">
        <label style="margin-bottom:-8px;">Ocupacion:</label>
        <input type="text" class="form-control" value="${data.personal.ocupacion_conyuge}" readonly>
        </div>
        <div class="col-md-2">
        <label style="margin-bottom:-8px;">Edad:</label>
        <input type="text" class="form-control" value="${edad_conyuge}" readonly>
        </div>
        <div class="col-md-2">
        <label style="margin-bottom:-8px;">Télefono:</label>
        <input type="text" class="form-control" value="${data.personal.tel_conyuge}" readonly>
        </div>
        </div>
        `);
      }
    }
  });
  $("#informacionUserModal").modal("show");
}

function infoFamily(id_user) {
  $("#modal_family_body").empty();
  let nombre = $(`#info_${id_user} td`)[1].innerHTML;
  $("#tittle_modal_family").empty();
  $("#tittle_modal_family").append(`<i class="fas fa-users" style="margin-right: 0.5rem;"></i> FAMILIA DEL USUARIO: ${nombre}`);
  let id = new FormData();
  id.append('id_user', id_user);
  $.ajax({
    data: id,
    url: `${urls}usuarios/toda_informacion_usuario`,
    type: "post",
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (data) {
      $("#modal_family_body").append(`<div class="row" style="height:3rem;text-align:center;"><div class="col-md-12"><label>PADRES</label></div></div>`);
      data.padres.forEach(element => {
         var fecha_p = new Date(element.fecha_nacimiento_padres);
        if (fecha_p.getMonth() + 1 < 10) { mes = `0${fecha_p.getMonth() + 1}`; }
        else { mes = fecha_p.getMonth() + 1; }
        if ((fecha_p.getDate() + 1) < 10) { dia = `0${(fecha_p.getDate() + 1)}`; }
        else { dia = (fecha_p.getDate() + 1); }
        fecha_padres = `${dia} / ${mes} / ${fecha_p.getFullYear()}`;
        $("#modal_family_body").append(`
      <div class="row">
      <div class="col-md-4">
        <label style="margin-bottom:-8px;">Nombre:</label>
        <input type="text" class="form-control" value="${element.nombre_padres}" readonly>
        </div>
        <div class="col-md-2">
        <label style="margin-bottom:-8px;">Genero:</label>
        <input type="text" class="form-control" value="${element.genero_padres}" readonly>
        </div>
        <div class="col-md-3">
        <label style="margin-bottom:-8px;">Fecha de Nacimiento:</label>
        <input type="text" class="form-control" value="${fecha_padres}" readonly>
        </div>
        <div class="col-md-2">
        <label style="margin-bottom:-8px;">Estado:</label>
        <input type="text" class="form-control" value="${element.finado}" readonly>
        </div>
        <div class="col-md-1">
        <label style="margin-bottom:-8px;">Edad:</label>
        <input type="text" class="form-control" value="${element.edad}" readonly>
        </div>
        </div>`);
      });
      $("#modal_family_body").append(`<div class="form-row" style="height:3rem;text-align:center;padding-top:13px;"><div class="col-md-12"><label>HIJOS</label></div></div>`);
      if (data.hijos == null || data.hijos == "") {
        $("#modal_family_body").append(`<div class="form-row" style="height:3rem;text-align:center;padding-top:13px;"><div class="col-md-12"><H5>SIN HIJOS</H5></div></div>`);
      } else {
        data.hijos.forEach(hijo => {
          var fecha_h = new Date(hijo.fecha_nacimiento);
          if (fecha_h.getMonth() + 1 < 10) { mes = `0${fecha_h.getMonth() + 1}`; }
          else { mes = fecha_h.getMonth() + 1; }
          if ((fecha_h.getDate() + 1) < 10) { dia = `0${(fecha_h.getDate() + 1)}`; }
          else { dia = (fecha_h.getDate() + 1); }
          fecha_hijos = `${dia} / ${mes} / ${fecha_h.getFullYear()}`;
          $("#modal_family_body").append(`
          <div class="form-row">
        <div class="col-md-4">
        <label style="margin-bottom:-8px;">Nombre:</label>
        <input type="text" class="form-control" value="${hijo.nombre_hijo}" readonly>
        </div>
        <div class="col-md-2">
        <label style="margin-bottom:-8px;">Genero:</label>
        <input type="text" class="form-control" value="${hijo.genero}" readonly>
        </div>
        <div class="col-md-3">
        <label style="margin-bottom:-8px;">Fecha de Nacimiento:</label>
        <input type="text" class="form-control" value="${fecha_hijos}" readonly>
        </div>
        <div class="col-md-1">
        <label style="margin-bottom:-8px;">Edad:</label>
        <input type="text" class="form-control" value="${hijo.edad_hijo}" readonly>
        </div>
        </div>
          `);
        });
      }
    }
  });
  $("#informacionFamilyModal").modal("show");
}

/* $("#btn_todos").click(function (e) {
  e.preventDefault();
  let timerDowload = Swal.fire({ //se le asigna un nombre al swal
    allowOutsideClick: false,
    title: '¡Descargando!',
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });
  let nombre_user = $("#nombre_user").val();
  let id_user_doc = parseInt($("#btn_todos").val());
  // console.log(id_user_doc);
  $.ajax({
    data: { 'id_datos': id_user_doc },
    method: "post",
    url: `${urls}usuarios/todos_documentos`,
    cache: false,
    dataType: "json",
    success: function (documentos) {
      documentos.forEach(doc => {
        // console.log(doc);
        let type = ['ERROR', 'DOMICILIO', 'ESTUDIOS', 'ACTA', 'DIPLOMA', 'CURSO', 'CERTIFICADO INGLES'];
        if (parseInt(doc.tipo_document) >= 1 && parseInt(doc.tipo_document) <= 6) {
          tipo_name = type[parseInt(doc.tipo_document)];
        } else {
          tipo_name = type[0];
        }
        console.log(`${urls}${doc.ubicacion}`);
        const allDocumentDownload = document.createElement('a');
        allDocumentDownload.id = `${doc.id_doc}`;
        allDocumentDownload.href = `${urls}${doc.ubicacion}`;
        allDocumentDownload.download = `${tipo_name}_${nombre_user}`;
        allDocumentDownload.click();
        allDocumentDownload.remove();

      });
      Swal.close(timerDowload);
    }
  });
}); */