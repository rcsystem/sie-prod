/**
 * ARCHIVO MODULO ALMACEN 
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
 var users = $("#user_").val();
 $(document).ready(function () {
   tbl_list_material = $("#tabla_listado")
     .dataTable({
       processing: true,
       ajax: {
         method: "post",
         url: `${urls}almacen/listado_material`,
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
           title: "Listado de Material",
           exportOptions: {
             columns: [0, 1, 2, 3],
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
           data: "id_mp",
           title: "Id",
           className: "text-center",
         },
 
         {
           data: "code",
           title: "CODIGO",
           className: "text-center",
         },
         {
            data: "description",
            title: "DESCRIPCION",
            className: "text-center",
          },
          {
            data: "unit_of_measure",
            title: "UNIDAD",
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
           targets: 4,
           render: function (data, type, full, meta) {
             if(users == 650 || users == 328 || users == 1){
              var btnDelte = ` <div class="pull-right mr-auto">
              <button type="button" class="btn btn-danger btn-sm" title="Salida de Suministro"  onClick=handleDelete(${data["id_mp"]},'${data["code"]}')>
              <i class="fas fa-trash-alt"></i>
              </button>                       
            </div> `;

             }else{
              var btnDelte= ` <div class="pull-right mr-auto">
              <button type="button" class="btn btn-secondary btn-sm" title="Salida de Suministro">
              <i class="fas fa-trash-alt"></i>
              </button>                       
            </div> `;
             }
             return btnDelte;
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
         $(row).attr("id", "material_" + data.id_mp);
       },
     })
     .DataTable();
   $("#tabla_listado thead").addClass("thead-dark text-center");
 });

 $("#lista_material").submit(function (event) {
    event.preventDefault();
    $("#guardar_material").prop("disabled", true);
    let data = new FormData();
  
 
    let codigo = $("#codigo").val();


  
    data.append("codigo", codigo);

    $.ajax({
      data: data, //datos que se envian a traves de ajax
      url: `${urls}almacen/existe-codigo`, //archivo que recibe la peticion
      type: "POST", //método de envio
      processData: false, // dile a jQuery que no procese los datos
      contentType: false, // dile a jQuery que no establezca contentType
      dataType: "json",
       async: true,
        success: function (resp) {
        console.log(`respuesta: ${resp}`);
        // Limpiamos el select
        if (resp) {
          //descripcion
          //unidad_medida
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "El Codigo ya se encuentra dado de Alta",
          });
          $("#guardar_material").prop("disabled", true);
        } else {
          let codigo = $("#codigo").val();
          let descripcion = $("#descripcion").val();
          let unidad_medida = $("#unidad_medida").val();
          InsertCode(codigo,descripcion,unidad_medida);
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


  function InsertCode(codigo,descripcion,unidad_medida){

    let data = new FormData();

    data.append("codigo", codigo);
    data.append("descripcion", descripcion);
    data.append("unidad_medida", unidad_medida);

    
   
  
    $.ajax({
      data: data, //datos que se envian a traves de ajax
      url: `${urls}almacen/nuevo_codigo`, //archivo que recibe la peticion
      type: "post", //método de envio
      processData: false, // dile a jQuery que no procese los datos
      contentType: false, // dile a jQuery que no establezca contentType
      success: function (response) {
        //una vez que el archivo recibe el request lo procesa y lo devuelve
        //console.log(response);
        /*codigo que borra todos los campos del form newProvider*/
  
        if (response != "error") {
          setTimeout(function () {
            tbl_list_material.ajax.reload(null, false);
          }, 100);
          $("#guardar_material").prop("disabled", false);
          
          Swal.fire("!Los datos se han Actualizado!", "", "success");
        } else {
          $("#guardar_material").prop("disabled", false);
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
        $("#guardar_material").prop("disabled", false);
      } else if (jqXHR.status == 404) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "No se encontró la página solicitada [404]",
        });
        $("#guardar_material").prop("disabled", false);
      } else if (jqXHR.status == 500) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Internal Server Error [500]",
        });
        $("#guardar_material").prop("disabled", false);
      } else if (textStatus === "parsererror") {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Error de análisis JSON solicitado.",
        });
        $("#guardar_material").prop("disabled", false);
      } else if (textStatus === "timeout") {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Time out error.",
        });
        $("#guardar_material").prop("disabled", false);
      } else if (textStatus === "abort") {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Ajax request aborted.",
        });
  
        $("#guardar_material").prop("disabled", false);
      } else {
        alert("Uncaught Error: " + jqXHR.responseText);
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: `Uncaught Error: ${jqXHR.responseText}`,
        });
        $("#guardar_material").prop("disabled", false);
      }
    });

  }

  function handleDelete(item,code) {
    Swal.fire({
      title: `Deseas Eliminar el Codigo ${code} ?`,
      text: "Eliminar Codigo!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Eliminar",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.value) {
        let dataForm = new FormData();
        const newLocal = "id_code";
        dataForm.append(newLocal, item);
      
  
        $.ajax({
          data:dataForm,
          type: "post",
          url: `${urls}almacen/eliminar-codigo`,
          async: true,
          processData: false, // dile a jQuery que no procese los datos
          contentType: false, // dile a jQuery que no establezca contentType
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
        }).fail(function (jqXHR, textStatus, errorThrown) {
          if (jqXHR.status === 0) {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Fallo de conexión: ​​Verifique la red.",
            });
            $("#guardar_material").prop("disabled", false);
          } else if (jqXHR.status == 404) {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "No se encontró la página solicitada [404]",
            });
            $("#guardar_material").prop("disabled", false);
          } else if (jqXHR.status == 500) {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Internal Server Error [500]",
            });
            $("#guardar_material").prop("disabled", false);
          } else if (textStatus === "parsererror") {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Error de análisis JSON solicitado.",
            });
            $("#guardar_material").prop("disabled", false);
          } else if (textStatus === "timeout") {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Time out error.",
            });
            $("#guardar_material").prop("disabled", false);
          } else if (textStatus === "abort") {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Ajax request aborted.",
            });
      
            $("#guardar_material").prop("disabled", false);
          } else {
            alert("Uncaught Error: " + jqXHR.responseText);
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: `Uncaught Error: ${jqXHR.responseText}`,
            });
            $("#guardar_material").prop("disabled", false);
          }
        });
      }
    });
  }


    /* Ponemos evento blur a la escucha sobre id nombre en id cliente. */
    $("#lista_material").on("blur", `#codigo`, function () {
      /* Obtenemos el valor del campo */
      var valor = this.value;
      console.log(`mi_valor: ${valor}`);
      /*   let a= $(this).val();
       console.log(a); */
  
      let codigo = $("#codigo").val();
      //console.log(`codigo: ${codigo}`);
 
      let data = new FormData();
  
      data.append("codigo", codigo);
  
      $.ajax({
        data: data, //datos que se envian a traves de ajax
        url: `${urls}almacen/existe-codigo`, //archivo que recibe la peticion
        type: "POST", //método de envio
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentType
        dataType: "json",
         async: true,
          success: function (resp) {
          console.log(`respuesta: ${resp}`);
          // Limpiamos el select
          if (resp) {
            //descripcion
            //unidad_medida
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "El Codigo ya se encuentra dado de Alta",
            });
            $("#guardar_material").prop("disabled", true);
          } else {
            $("#guardar_material").prop("disabled", false);
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
  
 