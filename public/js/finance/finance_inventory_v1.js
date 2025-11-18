var tbl_activos, tbl_inactivos;
$(document).ready(function () {
  tbl_activos = $("#tbl_finanzas_inventario")
    .dataTable({
      processing: true,
      ajax: {
        method: "post",
        url: `${urls}finanzas/todo_inventario`,
        dataSrc: "data",
      },
      lengthChange: true,
      //ordering: true,
      //responsive: false,
      // fixedHeader: false, // Mantiene los encabezados fijos
      scrollY: "600px",
      scrollX: true, // Activa el desplazamiento horizontal
      scrollCollapse: true,
      paging: false,
      fixedColumns: {
        left: 3,
      }, 
      autoWidth: true,
      rowId: "staffId",
      dom: "lBfrtip", // Esto coloca los botones encima de la tabla
      bInfo: false,
      language: {
        url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
      },
      columns: [
        {
          data: "id_activo",
          title: "Id",
          className: "text-center",
          type: "num", // Forzar tipo numérico
          render: function (data, type, row) {
            return parseInt(data); // Convertir a número
          },
        },
        { data: "codigo", className: "text-center" },
        { data: "descripcion", className: "text-center" },
        { data: "marca", className: "text-center" },
        { data: "capacidad", className: "text-center" },
        { data: "modelo", className: "text-center" },
        { data: "serie", className: "text-center" },
        { data: "ubicacion", className: "text-center" },
        { data: "area", className: "text-center" },
        { data: "fecha", className: "text-center" },
        { data: "proveedor", className: "text-center" },
        {
          data: null,
          render: function (data, type, full, meta) {
            let facturas = data["factura"];
            if (facturas != "NA") {
              let nombreArchivo = `factura_${data["factura"]}.pdf`;
              return `<a href="${data["ruta_factura"]}" download="${nombreArchivo}" title="Descargar factura">
                       ${facturas}
                    </a>`;
            } else {
              return "no";
            }
          },

          className: "text-center",
        },
        { data: "revisado", className: "text-center" },
        {
          data: null,
          render: function (data) {
            return `<div class="mr-auto">
          <button type="button" class="btn btn-outline-black btn-sm btn-download" title="Descargar Qr" data-qr="${data["codigo"]}" data-imagen="${data["imagen_qr"]}">
              <i class="fas fa-qrcode"></i>
            </button>
            <button type="button" class="btn btn-outline-info btn-sm" title="Editar Activo" onclick="editarActivo(${data["id_activo"]})">
              <i class="fas fa-edit"></i>
            </button> 
            <button type="button" class="btn btn-outline-warning btn-sm" title="Descargar Archivos" onclick="downloadData(${data["id_activo"]})">
             <i class="fas fa-file-archive"></i>
            </button> 
            
            <button type="button" class="btn btn-outline-danger btn-sm" title="Desactivar Activo" onclick="deleteChange(${data["id_activo"]},'${data["codigo"]}')">
              <i class="fas fa-power-off"></i>
            </button>
            
          </div>`;
          },

          className: "text-center",
        },
      ],
      destroy: "true",
      columnDefs: [
        {
          targets: [1],
          //visible: false,
          searchable: true,
        },
      ],
      order: [[0, "desc"]], // Ordenar por la columna oculta `id_activo`
      buttons: [
        {
          extend: "excelHtml5",
          text: '<i class="far fa-file-excel"></i> Exportar a Excel',
          title: "Inventario", // Título del archivo Excel
          className: "btn btn-success", // Clase para el botón (puedes personalizarlo)
        },
      ],
     
      createdRow: (row, data) => {
        $(row).attr("id", "activos_" + data.id_activo);
      },
    })
    .DataTable();

  // Evento para el botón de descarga
  $("#tbl_finanzas_inventario").on("click", ".btn-download", function () {
    var imgUrl = $(this).attr("data-imagen");
    var nombreFactura = $(this).attr("data-qr");

    var link = document.createElement("a");
    link.href = imgUrl;
    link.download = `activo_${nombreFactura}.jpg`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  });
  /*************************************
   * inventario inactivo               *
   * ***********************************/

  /*  // Si DataTable ya está inicializado, lo destruimos
    if ($.fn.DataTable.isDataTable("#tbl_inventario_inactivo")) {
      $("#tbl_inventario_inactivo").DataTable().destroy();
      $("#tbl_inventario_inactivo").empty();
    } */

  tbl_inactivos = $("#tbl_inventario_inactivo").DataTable({
    processing: true,
    ajax: {
      method: "POST",
      url: `${urls}finanzas/inventario_inactivo`,
      dataSrc: function (json) {
        console.log("Datos recibidos:", json); // Verifica si llegan datos
        if (json.status === "error") {
          console.warn(json.message); // Muestra el mensaje de error en la consola
          return []; // Devuelve un array vacío para que DataTables no falle
        }
        return json.data || [];
      },
      error: function (xhr, error, thrown) {
        $("#tbl_inventario_inactivo").DataTable().clear().draw();
        console.error("Error en la solicitud AJAX:", error, xhr.responseText);
      },
    },
    language: {
      url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
      emptyTable: "No hay datos disponibles.",
    },
    lengthChange: true,
    ordering: true,
    responsive: false,
    autoWidth: true,
    fixedHeader: true, // Mantiene los encabezados fijos
    scrollX: true, // Activa el desplazamiento horizontal
    rowId: "staffId",
    columns: [
      { data: "id_activo", title: "Id" },
      { data: "codigo", title: "Código" },
      { data: "descripcion", title: "Descripción" },
      { data: "marca", title: "Marca" },
      { data: "capacidad", title: "Capacidad" },
      { data: "modelo", title: "Modelo" },
      { data: "serie", title: "Serie" },
      { data: "ubicacion", title: "Ubicación" },
      { data: "area", title: "Área" },
      { data: "fecha", title: "Fecha" },
      { data: "proveedor", title: "Proveedor" },
      {
        data: "factura",
        title: "Factura",
        render: function (data, type, row) {
          if (!data || !row.ruta_factura)
            return '<span class="text-muted">No disponible</span>';
          let facturaLimpia = data.trim();
          return `<a href="${row.ruta_factura}" download="${facturaLimpia}" title="Descargar factura">${facturaLimpia}</a>`;
        },
      },
      { data: "revisado", title: "Revisado" },
      {
        data: null,
        title: "ACCIONES",
        render: function (data) {
          return `
                  <button class="btn btn-sm btn-outline-black btn-download" title="Descargar QR" 
                      data-qr="${data.factura}" data-imagen="${data.imagen_qr}">
                      <i class="fas fa-qrcode"></i>
                  </button>
                  <button class="btn btn-sm btn-outline-success" title="Activar Item" 
                      onclick="activeChange(${data.id_activo}, '${data.codigo}')">
                      <i class="fas fa-power-off"></i>
                  </button>`;
        },
      },
    ],
    order: [[0, "desc"]], // Ordenar por la columna oculta `id_activo`
    createdRow: (row, data) => {
      $(row).attr("id", "inactivos_" + data.id_activo);
    },
  });

  /*Dar de Alta Activos */

  // Evento click para el botón "Guardar activo"
  $("#alta_activos").submit(function (event) {
    event.preventDefault();
    // Validar campos obligatorios
    if ($("#codigo").val() === "") {
      alert("El campo Código es obligatorio.");
      return;
    }
    if ($("#descripcion").val() === "") {
      alert("El campo Descripción es obligatorio.");
      return;
    }
    if ($("#marca").val() === "") {
      alert("El campo Marca es obligatorio.");
      return;
    }
    if ($("#fecha").val() === "") {
      alert("El campo Fecha es obligatorio.");
      return;
    }
    if ($("#revisado").val() === "") {
      alert("El campo Revisado es obligatorio.");
      return;
    }
    const timerInterval = Swal.fire({
      //se le asigna un nombre al swal
      title:
        '<i class="far fa-save" style="margin-right: 10px;"></i>¡Guardando Activo!',
      html: "Espere unos Segundos.",
      timerProgressBar: true,
      didOpen: () => {
        Swal.showLoading(); //remplaza boton "OK" por animacion de "circulo Cargando"
      },
    });

    // Crear un objeto FormData para enviar los datos del formulario
    const formData = new FormData();
    formData.append("codigo", $("#codigo").val());
    formData.append("descripcion", $("#descripcion").val());
    formData.append("marca", $("#marca").val());
    formData.append("capacidad", $("#capacidad").val());
    formData.append("modelo", $("#modelo").val());
    formData.append("serie", $("#serie").val());
    formData.append("ubicacion", $("#ubicacion").val());
    formData.append("area", $("#area").val());
    formData.append("fecha", $("#fecha").val());
    formData.append("proveedor", $("#proveedor").val());
    formData.append("revisado", $("#revisado").val());
    formData.append("datos", $("#datos").val());
    formData.append("factura", $("#factura")[0].files[0]); // Archivo adjunto

    // Enviar los datos por AJAX
    $.ajax({
      url: `${urls}finanzas/alta_activo`, // Reemplaza con la URL de tu servidor
      method: "POST",
      data: formData,
      processData: false, // Evitar que jQuery procese los datos
      contentType: false, // Evitar que jQuery establezca el tipo de contenido
      success: function (response) {
        Swal.close(timerInterval);
        console.log("Respuesta del servidor:", response);
        //alert("Activo guardado correctamente.");
        Swal.fire({
          icon: "success",
          title: "¡Activo guardado correctamente.!",
          text: "",
        });
        // Limpiar el formulario después de guardar
        $("#alta_activos")[0].reset();
      },
      error: function (xhr, status, error) {
        console.error("Error en la solicitud AJAX:", error);
        alert("Hubo un error al guardar el activo.");
      },
    });
  });
});

function downloadData(id_activo) {
  let timerInterval = Swal.fire({
    //se le asigna un nombre al swal
    title: `<i class="fas fa-file-download" style="margin-right: 10px;"></i>¡Descargando Archivos!`,
    html: "Espere unos Segundos.",
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading(); //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });

  // Hacer la solicitud AJAX
  $.ajax({
    url: `${urls}finanzas/descargar_carpeta/${id_activo}`,
    method: "GET",
    xhrFields: {
      responseType: "blob", // Indicar que esperamos un archivo binario
    },
    success: function (data) {
      // Crear un enlace temporal para descargar el archivo
      const link = document.createElement("a");
      link.href = window.URL.createObjectURL(data);
      link.download = "carpeta_" + id_activo + ".zip";
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      Swal.close(timerInterval);
    },
    error: function (xhr, status, error) {
      alert("Error al descargar la carpeta: " + xhr.responseText);
    },
  });
}

function activeChange(item, codigo) {
  Swal.fire({
    icon: "question",
    title: ` <i class="fas fa-power-off" style="margin-right: 10px;"></i>¿Activar el codigo ${codigo}?`,
    confirmButtonText:
      '<i class="fas fa-check" style="margin-right: 10px;"></i>Activar',
    confirmButtonColor: "#28A745",
    showCancelButton: true,
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      let timerInterval = Swal.fire({
        //se le asigna un nombre al swal
        title: `<i class="far fa-save" style="margin-right: 10px;"></i>¡Codigo ${codigo} Activado!`,
        html: "Espere unos Segundos.",
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading(); //remplaza boton "OK" por animacion de "circulo Cargando"
        },
      });
      var statusContract = new FormData();
      statusContract.append("id_activo", item);
      console.log(statusContract);
      $.ajax({
        type: "post",
        url: `${urls}finanzas/activar_activo`,
        data: statusContract,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (save) {
          console.log(save);
          resetTablas();
          Swal.close(timerInterval);
          if (save.hasOwnProperty("xdebug_message")) {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Algo salió Mal! Contactar con el Administrador",
            });
            console.log(save.xdebug_message);
          } else if (save.data === true) {
            Swal.fire({
              icon: "success",
              title: "¡Activación Exitosa!",
              text: `Se ha desactivado el codigo ${codigo} correctamente`,
            });
          } else {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Algo salió Mal! Contactar con el Administrador",
            });
          }
        },
      });
    }
  });
}

function deleteChange(item, codigo) {
  Swal.fire({
    icon: "question",
    title: ` <i class="fas fa-power-off" style="margin-right: 10px;"></i>¿Desactivar el codigo ${codigo}?`,
    confirmButtonText:
      '<i class="fas fa-check" style="margin-right: 10px;"></i>Desactivar',
    confirmButtonColor: "#28A745",
    showCancelButton: true,
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      let timerInterval = Swal.fire({
        //se le asigna un nombre al swal
        title: `<i class="far fa-save" style="margin-right: 10px;"></i>¡Codigo ${codigo} Desactivado!`,
        html: "Espere unos Segundos.",
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading(); //remplaza boton "OK" por animacion de "circulo Cargando"
        },
      });
      var statusContract = new FormData();
      statusContract.append("id_activo", item);
      console.log(statusContract);
      $.ajax({
        type: "post",
        url: `${urls}finanzas/desactivar_activo`,
        data: statusContract,
        cache: false,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (save) {
          console.log(save);
          resetTablas();
          Swal.close(timerInterval);
          if (save.hasOwnProperty("xdebug_message")) {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Algo salió Mal! Contactar con el Administrador",
            });
            console.log(save.xdebug_message);
          } else if (save.data === true) {
            Swal.fire({
              icon: "success",
              title: "¡Desactivación Exitosa!",
              text: `Se ha desactivado el codigo ${codigo} correctamente`,
            });
          } else {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Algo salió Mal! Contactar con el Administrador",
            });
          }
        },
      });
    }
  });
}

function abrirActivoModal() {
  var miModal = new bootstrap.Modal(document.getElementById("activoModal"));
  miModal.show();
}

function resetTablas() {
  tbl_activos.ajax.reload(null, false);
  tbl_inactivos.ajax.reload(null, false);
}

// Evento click para el botón "Editar"
function editarActivo(id_activo) {
  // Obtener el ID del activo
  const idActivo = id_activo;

  // Realizar una solicitud AJAX para obtener los datos del activo
  $.ajax({
    url: `${urls}finanzas/editar_activo`, // Ruta para obtener los datos
    method: "post",
    data: { id_activo: idActivo },
    success: function (response) {
      if (response.status === "success") {
        console.log("datos: ", response.data);

        response.data.forEach((element) => {
          // Cargar los datos en el formulario del modal
          $("#edit_id_activo").val(element.id_activo);
          $("#edit_codigo").val(element.codigo);
          $("#edit_descripcion").val(element.descripcion);
          $("#edit_marca").val(element.marca);
          $("#edit_capacidad").val(element.capacidad);
          $("#edit_modelo").val(element.modelo);
          $("#edit_serie").val(element.serie);
          $("#edit_ubicacion").val(element.ubicacion);
          $("#edit_area").val(element.area);
          $("#edit_fecha").val(element.fecha);
          $("#edit_proveedor").val(element.proveedor);
          $("#edit_revisado").val(element.revisado);
          $("#edit_datos").val(element.datos);
        });

        // Abrir el modal
        $("#editarActivoModal").modal("show");
      } else {
        alert("Error al cargar los datos del activo.");
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un error al cargar los datos.");
    },
  });
}

/*Guardar Cambios de Activos */

// Evento click para el botón "Guardar activo"
$("#form_editar_activo").submit(function (event) {
  console.log("entre al submit");

  event.preventDefault();
  // Validar campos obligatorios
  if ($("#edit_codigo").val() === "") {
    alert("El campo Código es obligatorio.");
    return;
  }
  if ($("#edit_descripcion").val() === "") {
    alert("El campo Descripción es obligatorio.");
    return;
  }
  if ($("#edit_marca").val() === "") {
    alert("El campo Marca es obligatorio.");
    return;
  }
  if ($("#edit_fecha").val() === "") {
    alert("El campo Fecha es obligatorio.");
    return;
  }
  if ($("#edit_revisado").val() === "") {
    alert("El campo Revisado es obligatorio.");
    return;
  }
  const timerInterval = Swal.fire({
    //se le asigna un nombre al swal
    title:
      '<i class="far fa-save" style="margin-right: 10px;"></i>¡Guardando Activo!',
    html: "Espere unos Segundos.",
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading(); //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });

  // Crear un objeto FormData para enviar los datos del formulario
  const formData = new FormData();

  formData.append("id_activo", $("#edit_id_activo").val());
  formData.append("codigo", $("#edit_codigo").val());
  formData.append("descripcion", $("#edit_descripcion").val());
  formData.append("marca", $("#edit_marca").val());
  formData.append("capacidad", $("#edit_capacidad").val());
  formData.append("modelo", $("#edit_modelo").val());
  formData.append("serie", $("#edit_serie").val());
  formData.append("ubicacion", $("#edit_ubicacion").val());
  formData.append("area", $("#edit_area").val());
  formData.append("fecha", $("#edit_fecha").val());
  formData.append("proveedor", $("#edit_proveedor").val());
  formData.append("revisado", $("#edit_revisado").val());
  //  formData.append("datos", $("#datos").val());
  // formData.append("factura", $("#factura")[0].files[0]); // Archivo adjunto

  // Enviar los datos por AJAX
  $.ajax({
    url: `${urls}finanzas/actualizar_activo`, // Reemplaza con la URL de tu servidor
    method: "POST",
    data: formData,
    processData: false, // Evitar que jQuery procese los datos
    contentType: false, // Evitar que jQuery establezca el tipo de contenido
    success: function (response) {
      Swal.close(timerInterval);
      console.log("Respuesta del servidor:", response);
      //alert("Activo guardado correctamente.");
      Swal.fire({
        icon: "success",
        title: "¡Activo guardado correctamente.!",
        text: "",
      });
      // Limpiar el formulario después de guardar
      //$("#form_editar_activos")[0].reset();
      resetTablas();
      $("#editarActivoModal").modal("toggle");
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un error al guardar el activo.");
    },
  });
});
