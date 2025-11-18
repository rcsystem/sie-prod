$(document).ready(function () {
  tabla_deptos = $("#tabla_deptos")
    .dataTable({
      responsive: true,
      lengthChange: false,
      autoWidth: false,
      buttons: [
        {
          extend: "excelHtml5",
          title: "Listado de Deptos y CC",
          exportOptions: {
            columns: [1, 2, 3],
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
        url: `${urls}usuarios/deptos`,
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
          data: "id_depto",
          title: "id",
        },
        {
          data: "departament",
          title: "Departamento",
        },
        {
          data: "area",
          title: "Area",
          className: "text-center",
        },
        {
          data: "cost_center",
          title: "Centro de Costo",
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
          targets: 4,
          render: function (data, type, full, meta) {
            return ` <div class="pull-right mr-auto">
                      <button type="button" class="btn btn-primary btn-sm "  onClick=handleEdit(${data["id_depto"]})>
                          <i class="far fa-edit"></i>
                      </button>
                      <button class="btn btn-danger btn-sm" onClick=handleDelete(${data["id_depto"]}) >
                          <i class="fas fa-trash"></i>
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
        $(row).attr("id", "depto_" + data.id_depto);
      },
    }).DataTable();
    $("#tabla_deptos thead").addClass("thead-dark text-center");
});

function handleDelete(row) {
  Swal.fire({
    title: "Eliminar Centro de Costo ?",
    text: "Deseas Eliminar el Centro de Costo!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Eliminar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.value) {
      let dataForm = new FormData();
      dataForm.append("id_departament", row);
      const requestInfo = {
        body: dataForm,
        method: "POST",
      };

      $.ajax({
        type: "GET",
        url: urls + "sistemas/eliminar-depto/" + row,
        async: true,
        success: function (resp) {
          //console.log(resp);
          if (resp != "error") {
            //console.log("Hola Mundo Delete" + row);
            document.getElementById("depto_" + row).style.display = "none";
            $("tr.child").remove();
            Swal.fire("!Se ha Eliminado correctamente!", "", "success");
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
  });
}

function handleEdit(id_depto) {
  console.log("Hola Mundo Edit" + id_depto);
  $.ajax({
    type: "GET",
    url: urls + "usuarios/editar_depto/" + id_depto,
    async: true,
    dataType: "json",
    success: function (resp) {
      console.log(resp);
      if (resp != "error") {
        $("#editar_departamento").val(resp.departament);
        $("#editar_centro_costo").val(resp.cost_center);
        $("#editar_area").val(resp.area);
        
        $("#exampleModal").modal("show");
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

$("#editProvider").submit(function (e) {
  e.preventDefault();
  let data = new FormData();

  data.append("edit_idprovider", $("#edit_idprovider").val());
  data.append("edit_provider", $("#edit_provider").val());
  data.append("edit_telprovider", $("#edit_telprovider").val());
  data.append("edit_contactoprovider", $("#edit_contactoprovider").val());
  data.append("edit_celprovider", $("#edit_celprovider").val());
  data.append("edit_emailprovider", $("#edit_emailprovider").val());
  data.append("edit_mailprovider", $("#edit_mailprovider").val());
  data.append("edit_dirprovider", $("#edit_dirprovider").val());
  data.append("edit_calleprovider", $("#edit_calleprovider").val());
  data.append("edit_municipioprovider", $("#edit_municipioprovider").val());
  data.append("edit_estadoprovider", $("#edit_estadoprovider").val());
  data.append("edit_bancoprovider", $("#edit_bancoprovider").val());
  data.append("edit_cuentaprovider", $("#edit_cuentaprovider").val());
  data.append("edit_cpprovider", $("#edit_cpprovider").val());
  data.append("edit_giroprovider", $("#edit_giroprovider").val());
  data.append("edit_rfcprovider", $("#edit_rfcprovider").val());
  data.append("edit_monedaprovider", $("#edit_monedaprovider").val());
  data.append("edit_creditoprovider", $("#edit_creditoprovider").val());

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: urls + "compras/editProvider", //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    success: function (response) {
      //console.log(response);
      $("#exampleModal").modal("toggle");
      //una vez que el archivo recibe el request lo procesa y lo devuelve
      //console.log(response);
      /*codigo que borra todos los campos del form newProvider*/
      if (response != "error") {
        Swal.fire("!Los datos se han Actualizado!", "", "success");
        tableProviders.ajax.reload(null, false);
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

$("#form_depto").submit(function (e) {
  e.preventDefault();
  let data = new FormData();
  
  data.append("departamento", $("#departamento").val());
  data.append("centro_costo", $("#centro_costo").val());
  data.append("area", $("#area").val());

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: urls + "usuarios/insertar_depto", //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // tell jQuery not to process the data
    contentType: false, // tell jQuery not to set contentType
    beforeSend: function () {
      $("#resultado")
        .html(`<div class="alert alert-warning alert-dismissible" role="alert">
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                              <strong>Procesando,</strong>  espere por favor...
                            </div>
                              <span></span>`);
    },
    success: function (response) {
      //una vez que el archivo recibe el request lo procesa y lo devuelve
      //console.log(response);
      /*codigo que borra todos los campos del form newProvider*/

      $("#departamento").val("");
      $("#centro_costo").val("");
      $("#area").val("");
    
      $("#resultado")
        .html(`<div class="alert alert-success alert-dismissible" role="alert">
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                              <strong>Se ha Registrado con exito!</strong> un nuevo Centro de Costo
                            </div>
                              <span></span>`);

      setTimeout(function () {
        $(".alert")
          .fadeTo(1000, 0)
          .slideUp(800, function () {
            $(this).remove();
            tabla_deptos.ajax.reload(null, false);
          });
      }, 2000);
    },
  });
});

function validaNumericos(event) {
  return event.charCode >= 48 && event.charCode <= 57 ? true : false;
}

