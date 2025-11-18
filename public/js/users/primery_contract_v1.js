/**
 * ARCHIVO MODULO ADMINISTRATOR
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
 const firma = $("#firma_user").val();
 $(document).ready(function () {
 
   //Firma();
 
 
 });
 
 function Firma() {
   if (firma.length == 0) {
     console.log("aqui");
     swalFirma();
     return false;
   }
 }
 

 
 
 function handleEdit(id_user) {
 
   url = "https://sie.grupowalworth.com/usuarios/";
   window.open(url, '_blank');
   return false;
 
 }
 
 $("#contrato_temp").submit(function (event) {
   event.preventDefault();
    
   if (!$('input[name="opcion"]').is(':checked')) {
     /* Mostrar Error */
     $("#error_opcion").html(
       `<div class="alert alert-warning alert-dismissible" role="alert">
           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
             <span aria-hidden="true">&times;</span>
             </button>
             <strong>SE DEBE DE SELECCIONAR UNA OPCIÓN...</strong>
             </div>`
     );
     setTimeout(function () {
       $(".alert")
         .fadeTo(1000, 0)
         .slideUp(800, function () {
           $(this).remove();
         });
     }, 3000);
     return false;
   }
 
 
   if ($('input[name="opcion"]:checked').val() == 2) {
     if ($("#contrato").val().length == 0) {
       error_opcion2 = "El campo es requerido";
       $("#error_opcion2").text(error_opcion2);
       $("#contrato").addClass("has-error");
       return false;
     } else {
       error_opcion2 = "";
       $("#error_opcion2").text(error_opcion2);
       $("#contrato").removeClass("has-error");
     }
   }
 
   if ($('input[name="opcion"]:checked').val() == 3) {
     if ($("#causa_baja").val().length == 0) {
       error_baja = "El campo es requerido";
       $("#error_baja").text(error_baja);
       $("#causa_baja").addClass("has-error");
       return false;
     } else {
       error_baja = "";
       $("#error_baja").text(error_baja);
       $("#causa_baja").removeClass("has-error");
     }
   }
 
   $("#guardar_contrato").prop("disabled", true);
 
   var formData = $("#contrato_temp").serialize();
 
   $.ajax({
     type: "post", //método de envio
     data: formData, //datos que se envian a traves de ajax
     url: `${urls}usuarios/registrar_primer_contrato`, //archivo que recibe la peticion
     // processData: false, // dile a jQuery que no procese los datos
     // contentType: false, // dile a jQuery que no establezca contentType
     dataType: "html",
     success: function (response) {
       //una vez que el archivo recibe el request lo procesa y lo devuelve
       console.log(response);
       /*codigo que borra todos los campos del form newProvider*/
 
       if (response) {
         $("#guardar_contrato").prop("disabled", false);
         Swal.fire('Generar Contrato', "!El Contrato se ha generado Correctamente!", 'success').then(() => {
           location.reload();
         });
 
       } else {
         $("#guardar_contrato").prop("disabled", false);
         Swal.fire({
           icon: "error",
           title: "Oops...",
           text: "Algo salió Mal! Contactar con el Administrador",
         });
       }
     },
     error: function (jqXHR, status, error) {
       $("#guardar_contrato").prop("disabled", false);
       Swal.fire({
         icon: "error",
         title: "Oops...",
         text: "Algo salió Mal! Contactar con el Administrador",
       });
       console.log(
         "Mal Revisa entro en el estatus: " +
         status +
         " error" +
         error +
         " jqXHR" +
         jqXHR
       );
     },
   });
 
 });
 
 
