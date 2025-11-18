/**
 * ARCHIVO MODULO INFO USUARIOS
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:55-65-42-96-49
 */
$(document).ready(function () {
  $("#depto").select2();
  $("#puesto").select2();
  $("#clace_cost").select2();
  $("#area_operative").select2();
  $("#id_manager_PyV").select2();
  $("#id_manager_contrato").select2();
  $("#id_manager_papeleria").select2();
  $("#id_manager_requicicion").select2();

  tbl_users = $("#tabla_usuarios").dataTable({
    responsive: true, "lengthChange": false, "autoWidth": false,
    buttons: [
      {
        extend: 'excelHtml5',
        title: 'Listado de Usuarios',
        exportOptions: {
          columns: [1, 2, 3, 4]
        }
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
      url: `${urls}usuarios/todos`,
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
        data: "id_user",
        title: "id",
      },
      {
        data: "payroll_number",
        title: "Num Nomina",
        className: "text-center"
      },
      {
        data: null,
        render: function (data, type, row) {
          return data["name"] + ' ' + data["surname"];
        },
        title: "Nombre",
        className: "text-center"
      },
      {
        data: "job",
        title: "Puesto",
        className: "text-center"
      },
      {
        data: "departament",
        title: "Depto",
        className: "text-center"
      },
      {
        data: "date_admission",
        title: "Ingreso",
        className: "text-center"
      },
      {
        data: "email",
        title: "Correo",
      },
      {
        data: null,
        title: "Acciones",
      },
    ],
    destroy: "true",
    columnDefs: [
      {
        targets: 7,
        render: function (data, type, full, meta) {
          return ` <div class="pull-right mr-auto">
            <button type="button" class="btn btn-info btn-sm "  onClick=handleEdit(${data["id_user"]})>
              <i class="far fa-edit"></i>
            </button>

            <button class="btn btn-danger btn-sm" onClick=handleDelete(${data["id_user"]}) >
              <i class="fas fa-trash"></i>
            </button>

            <button class="btn btn-success btn-sm" onClick=credencia(${data["id_user"]}) >
              <i class="far fa-address-card"></i>
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
      $(row).attr("id", "usuario_" + data.id_user);
    },
  }).DataTable();
  $("#tabla_usuarios thead").addClass("thead-dark text-center");
});

function handleDelete(row) {
  Swal.fire({
    title: "Eliminar Usuario ?",
    text: "Deseas Eliminar este Usuario!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Eliminar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.value) {
      let dataForm = new FormData();
      dataForm.append("id_user", row);

      $.ajax({
        data: dataForm, //datos que se envian a traves de ajax
        url: `${urls}sistemas/eliminar_usuario`,//archivo que recibe la peticion
        type: "post", //método de envio
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentType
        success: function (resp) {
          console.log(resp);
          if (resp) {
            //console.log("Hola Mundo Delete" + row);
            document.getElementById("usuario_" + row).style.display = "none";
            $("tr.child").remove();
            Swal.fire("!Eliminado correctamente!", "", "success");
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

function handleEdit(row) {
  const data = new FormData();
  data.append("id_user", row);
  $.ajax({
    data: data,
    type: "post",
    url: `${urls}sistemas/usuario-editar`,
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    async: true,
    dataType: "json",
    success: function (resp) {
      //console.log(resp);
      if (resp.hasOwnProperty('xdebug_message')) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
        console.log(resp.xdebug_message);
      } else if (resp != false) {
        //console.log(key.name);
        $("#id_usuario").val(resp.info.id_user);
        $("#nomina_modal").val(resp.info.payroll_number);
        $("#nombre").val(resp.info.name);
        $("#apellido_p").val(resp.info.surname);
        $("#apellido_m").val(resp.info.second_surname);
        $("#email").val(resp.info.email);
        $("#fecha_admision").val(resp.info.date_admission);
        $("#tipo_empleado").val(resp.info.type_of_employee);
        $("#dias_vacaciones").val(resp.info.vacation_days_total);
        $("#anios_laborados").val(resp.info.years_worked);
        $("#password").val(resp.info.password);
        $("#curp").val(resp.info.curp);
        $("#nss").val(resp.info.nss);
        $("#grado").val(resp.info.grado);

        $("#depto").val(resp.info.id_departament).trigger('change');
        $("#puesto").val(resp.info.id_job_position).trigger('change');
        $("#clace_cost").val(resp.info.id_cost_center).trigger('change');
        $("#area_operative").val(resp.info.id_area_operativa).trigger('change');
        $("#id_manager_PyV").val(resp.PyV).trigger('change');
        $("#id_manager_papeleria").val(resp.Papeleria).trigger('change');
        $("#id_manager_contrato").val(resp.Contrato).trigger('change');
        $("#id_manager_requicicion").val(resp.Requicicion).trigger('change');

        dasActivoP = (resp.Papeleria == '') ? true : false;
        $("#id_manager_papeleria").prop('disabled', dasActivoP);

        dasActivoPyV = (resp.PyV == '') ? true : false;
        $("#id_manager_PyV").prop('disabled', dasActivoPyV);

        dasActivoR = (resp.Requicicion == '') ? true : false;
        $("#id_manager_requicicion").prop('disabled', dasActivoR);

        console.log(resp.info.contracts);
        if (resp.info.contracts == 2) {
          $("#id_manager_contrato").prop('disabled', false);
          $("#div_add_id_manager_contrato").empty();
        } else {
          $("#id_manager_contrato").prop('disabled', true);
          $("#div_add_id_manager_contrato").append(`<input type="hidden" name="id_manager_contrato" value="${resp.Contrato}">`);
        }
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
  $("#editarModal").modal("show");
}

function credencia(row) {
  const data = new FormData();
  data.append("id_user", row);
  $.ajax({
    data: data,
    type: "post",
    url: `${urls}sistemas/usuario_credencial`,
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    async: true,
    dataType: "json",
    success: function (resp) {
      //console.log(resp);
      if (resp.hasOwnProperty('xdebug_message')) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
        console.log(resp.xdebug_message);
      } else if (resp != false) {
        $("#nombre_card").val(resp.name);
        $("#nomina_card").val(resp.payroll_number);
        $("#apellidos_card").val(resp.apellidos);
        $("#depto_card").val(resp.departament);
        $("#curp_card").val(resp.curp);
        $("#nss_card").val(resp.nss);
        $("#job_card").val(resp.job);
        $("#cardDataModal").modal("show");
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

$("#tipo_empleado").on("change", function () {
  let tipo_usuario = $("#tipo_empleado").val();
  // Guardamos el select de cursos
  var puestos = $("#puesto");
  let data = new FormData();

  data.append("tipo_usuario", tipo_usuario);

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: urls + "usuarios/tipo_usuario", //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentTypeasignacion
    dataType: "json",
    success: function (resp) {
      //console.log(resp);
      // Limpiamos el select
      puestos.find("option").remove();
      $("#puesto").append('<option value="">Seleccionar...</option>');
      $.each(resp, function (key, value) {
        $("#puesto").append(
          '<option value="' + value.id + '">' + value.job + "</option>"
        );
      });
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

$("#editUser").submit(function (e) {
  e.preventDefault();
  $("#actualiza_usuario").prop("disabled", true);
  const data = new FormData($("#editUser")[0]);
  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}sistemas/actualizar_usuario`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    dataType: "json",
    success: function (response) {
      $("#actualiza_usuario").prop("disabled", false);
      if (response.hasOwnProperty('xdebug_message')) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador",
        });
        console.log(response.xdebug_message);
      } else if (response === true) {
        $("#actualiza_usuario").prop("disabled", false);
        $('#editarModal').modal('hide');
        Swal.fire("!Los datos se han Actualizado!", "", "success");
        tbl_users.ajax.reload(null, false);

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

$("#newProvider").submit(function (e) {
  e.preventDefault();
  let data = new FormData();

  data.append("codeprovider", $("#codeprovider").val());
  data.append("provider", $("#provider").val());
  data.append("telprovider", $("#telprovider").val());
  data.append("contactoprovider", $("#contactoprovider").val());
  data.append("celprovider", $("#celprovider").val());
  data.append("emailprovider", $("#emailprovider").val());
  data.append("mailprovider", $("#mailprovider").val());
  data.append("dirprovider", $("#dirprovider").val());
  data.append("calleprovider", $("#calleprovider").val());
  data.append("municipioprovider", $("#municipioprovider").val());
  data.append("estadoprovider", $("#estadoprovider").val());
  data.append("bancoprovider", $("#bancoprovider").val());
  data.append("cuentaprovider", $("#cuentaprovider").val());
  data.append("cpprovider", $("#cpprovider").val());
  data.append("giroprovider", $("#giroprovider").val());
  data.append("rfcprovider", $("#rfcprovider").val());
  data.append("monedaprovider", $("#monedaprovider").val());
  data.append("creditoprovider", $("#creditoprovider").val());

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: urls + "compras/newProvider", //archivo que recibe la peticion
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

      $("#provider").val("");
      $("#telprovider").val("");
      $("#contactoprovider").val("");
      $("#celprovider").val("");
      $("#emailprovider").val("");
      $("#mailprovider").val("");
      $("#dirprovider").val("");
      $("#calleprovider").val("");
      $("#municipioprovider").val("");
      $("#estadoprovider").val("");
      $("#bancoprovider").val("");
      $("#cuentaprovider").val("");
      $("#rfcprovider").val("");
      $("#cpprovider").val("");
      $("#giroprovider").val("");
      $("#monedaprovider").val("");
      $("#creditoprovider").val("");

      $("#resultado")
        .html(`<div class="alert alert-success alert-dismissible" role="alert">
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                              <strong>Se ha Registrado con exito!</strong> un nuevo Proveedor
                            </div>
                              <span></span>`);

      setTimeout(function () {
        $(".alert")
          .fadeTo(1000, 0)
          .slideUp(800, function () {
            $(this).remove();
            tableProviders.ajax.reload(null, false);
          });
      }, 2000);
    },
  });
});