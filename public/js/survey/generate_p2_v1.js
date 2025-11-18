var arrayDiplomas = [];
var arrayCursos = [];
var contDiploma = 0;
var contCursos = 0;

$("#btn_diploma").on("click", function (e) {
  e.preventDefault();
  if (arrayDiplomas.length < 3) {
    if (arrayDiplomas.length == 0) {
      contDiploma++;
    } else {
      contDiploma++;
      arrayDiplomas.forEach(item => {
        if (item === contDiploma) {
          contDiploma++;
        }
      });
    }
    $("#diplimas_div").append(`
       <div id="diplimas_div_${contDiploma}" class="row">
       <input type="hidden" name="id_diploma_[]" value="${contDiploma}">
         <div class="col-md-5"><label for="diploma_${contDiploma}">Titulo</label>
             <input type="text" name="diploma_${contDiploma}" id="diploma_${contDiploma}" class="form-control" onchange="validaDiploma(${contDiploma})">
         </div>
         <div class="col-md-5">
             <label for="diploma_${contDiploma}">Comprobante</label>
             <div class="custom-file">
                 <input type="file" class="custom-file-input" accept="application/pdf" id="doc_diploma_${contDiploma}" name="doc_diploma_${contDiploma}" aria-describedby="inputGroupFileAddon01" onchange="validaDiploma(${contDiploma})">
                 <label id="lbl_diploma_${contDiploma}" class="custom-file-label" for="doc_diploma_${contDiploma}" style="color:#DBDBDB!important;">comprobante</label>
                 <div class="text-danger" id="error_doc_diploma_${contDiploma}"></div>
             </div>
         </div>
         <div class="col-md-1">
             <button type="button" class="btn btn-danger btn-retirar-item" style="margin-top:2rem;" onclick="retirarDiploma(${contDiploma})">
                 <i class="fas fa-times"></i>
             </button>
         </div>
       </div>
       `);
    arrayDiplomas.push(contDiploma);
    sessionStorage.setItem('arrayDiplomas', JSON.stringify(arrayDiplomas));
  } else {
    $("#diplimas_div_error").html(
      `<div class="alert alert-warning alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                <strong>NO SE PERMITEN MAS DE 3 DIPLOMAS ...</strong>
                </div>
                <span></span>`
    );
    setTimeout(function () {
      $(".alert")
        .fadeTo(1000, 0)
        .slideUp(800, function () {
          $(this).remove();
        });
    }, 1500);
    return false;
  }
});

function validaDiploma(cont_D) {
  if ($("#diploma_" + cont_D).val().length > 0) {
    $("#error_doc_diploma_" + cont_D).text('');
    $("#diploma_" + cont_D).removeClass('has-error');
  }
  if ($("#doc_diploma_" + cont_D).val().length > 0) {
    $("#lbl_diploma_" + cont_D).empty();
    $("#lbl_diploma_" + cont_D).append(`${document.getElementById(`doc_diploma_${cont_D}`).files[0].name}`);
    $("#lbl_diploma_" + cont_D).attr('style', 'color:#343a40!important;');
    $("#lbl_diploma_" + cont_D).removeClass('has-error');
  }
}

function retirarDiploma(item_D) {
  var i = arrayDiplomas.indexOf(item_D);
  arrayDiplomas.splice(i, 1);
  sessionStorage.setItem('arrayDiplomas', JSON.stringify(arrayDiplomas));

  $("#diplimas_div_" + item_D).remove();
  if (contDiploma > 0) {
    contDiploma = 0;
  }
}

$("#btn_curso").on("click", function (e) {
  e.preventDefault();
  if (arrayCursos.length < 3) {
    if (arrayCursos.length == 0) {
      contCursos++;
    } else {
      contCursos++;
      arrayCursos.forEach(item => {
        if (item === contCursos) {
          contCursos++;
        }
      });
    }
    $("#cusos_div").append(`
       <div id="cusos_div_${contCursos}" class="row">
         <div class="col-md-5">
         <input type="hidden" name="id_cusos_[]" value="${contCursos}">
             <label for="curso_${contCursos}">Titulo</label>
             <input type="text" name="curso_${contCursos}" id="curso_${contCursos}" class="form-control" onchange="validaCurso(${contCursos})">
         </div>
         <div class="col-md-5">
             <label for="diploma_${contCursos}">Comprobante</label>
             <div class="custom-file">
                 <input type="file" class="custom-file-input" accept="application/pdf" id="doc_curso_${contCursos}" name="doc_curso_${contCursos}" aria-describedby="inputGroupFileAddon01" onchange="validaCurso(${contCursos})">
                 <label id="lbl_curso_${contCursos}" class="custom-file-label" for="doc_curso_${contCursos}" style="color:#DBDBDB!important;">comprobante</label>
                 <div class="text-danger" id="error_doc_curso_${contCursos}"></div>
             </div>
         </div>
         <div class="col-md-1">
             <button type="button" class="btn btn-danger btn-retirar-item" style="margin-top:2rem;" onclick="retirarCurso(${contCursos})">
                 <i class="fas fa-times"></i>
             </button>
         </div>
       </div>
       `);
    arrayCursos.push(contCursos);
    // Se guarda en localStorage despues de JSON stringificarlo 
    sessionStorage.setItem('arrayCursos', JSON.stringify(arrayCursos));
  } else {
    $("#cusos_div_error").html(
      `<div class="alert alert-warning alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                <strong>NO SE PERMITEN MAS DE 3 CURSOS ...</strong>
                </div>
                <span></span>`
    );
    setTimeout(function () {
      $(".alert")
        .fadeTo(1000, 0)
        .slideUp(800, function () {
          $(this).remove();
        });
    }, 1500);
    return false;
  }
});

function validaCurso(cont_C) {
  if ($("#curso_" + cont_C).val().length > 0) {
    $("#curso_" + cont_C).removeClass('has-error');
    $("#error_doc_curso_" + cont_C).text('');
  }
  if ($("#doc_curso_" + cont_C).val().length > 0) {
    $("#lbl_curso_" + cont_C).empty();
    $("#lbl_curso_" + cont_C).append(`${document.getElementById(`doc_curso_${cont_C}`).files[0].name}`);
    $("#lbl_curso_" + cont_C).attr('style', 'color:#343a40!important;');
    $("#lbl_curso_" + cont_C).removeClass('has-error');
  }
}

function retirarCurso(item_C) {
  var i = arrayCursos.indexOf(item_C);
  arrayCursos.splice(i, 1);
  sessionStorage.setItem('arrayCursos', JSON.stringify(arrayCursos));
  $("#cusos_div_" + item_C).remove();
  if (contCursos > 0) {
    contCursos = 0;
  }
}

$("#ingles_si").on("click", function () {
  $("#ingles_div").empty();
  $("#error_ingles").text("");
  $("#ingles").val("1");
  $("#ingles_no").attr('style', '');
  $("#ingles_si").attr('style', 'background-color:#014421!important;border-color:#014421!important');
  $("#ingles_div").append(`
         <label for="doc_ingles">Certificado de Ingles:</label>
             <div class="custom-file">
                 <input type="file" class="custom-file-input" accept="application/pdf" id="doc_ingles" name="doc_ingles" aria-describedby="inputGroupFileAddon01" onchange="validar()">
                 <label id="lbl_ingles" class="custom-file-label" for="doc_ingles">Certificado de Ingles</label>
             </div>`);
});
$("#ingles_no").on("click", function () {
  $("#ingles_div").empty();
  $("#error_ingles").text("");
  $("#ingles").val("2");
  $("#ingles_si").attr('style', '');
  $("#ingles_no").attr('style', 'background-color:#014421!important;border-color:#014421!important');
});

$("#cv_si").on("click", function () {
  $("#cv_div").empty();
  $("#error_cv").text("");
  $("#cv").val("1");
  $("#cv_no").attr('style', '');
  $("#cv_si").attr('style', 'background-color:#014421!important;border-color:#014421!important');
  $("#cv_div").append(`
         <label for="doc_cv">Curriculum:</label>
             <div class="custom-file">
                 <input type="file" class="custom-file-input" accept="application/pdf" id="doc_cv" name="doc_cv" aria-describedby="inputGroupFileAddon01" onchange="validar()">
                 <label id="lbl_cv" class="custom-file-label" for="doc_cv">Curriculum</label>
             </div>`);
});
$("#cv_no").on("click", function () {
  $("#cv_div").empty();
  $("#error_cv").text("");
  $("#cv").val("2");
  $("#cv_si").attr('style', '');
  $("#cv_no").attr('style', 'background-color:#014421!important;border-color:#014421!important');
});

function validar() {
  if ($("#doc_domicilio").val().length > 0) {
    $("#lbl_domicilio").empty();
    $("#lbl_domicilio").append(`${document.getElementById('doc_domicilio').files[0].name}`);
    $("#lbl_domicilio").removeClass('has-error');
  }
  if ($("#doc_estudios").val().length > 0) {
    $("#lbl_estudios").empty();
    $("#lbl_estudios").append(`${document.getElementById('doc_estudios').files[0].name}`);
    $("#lbl_estudios").removeClass('has-error');
  }
  if ($("#ingles").val() == 1) {
    if ($("#doc_ingles").val().length > 0) {
      $("#lbl_ingles").empty();
      $("#lbl_ingles").append(`${document.getElementById('doc_ingles').files[0].name}`);
      $("#lbl_ingles").removeClass('has-error');
    }
  }
  if ($("#cv").val() == 1) {
    if ($("#doc_cv").val().length > 0) {
      $("#lbl_cv").empty();
      $("#lbl_cv").append(`${document.getElementById('doc_cv').files[0].name}`);
      $("#lbl_cv").removeClass('has-error');
    }
  }
  if ($("#doc_acta").val().length > 0) {
    $("#lbl_acta").empty();
    $("#lbl_acta").append(`${document.getElementById('doc_acta').files[0].name}`);
    $("#lbl_acta").removeClass('has-error');
  }
  if ($("#doc_curp").val().length > 0) {
    $("#lbl_curp").empty();
    $("#lbl_curp").append(`${document.getElementById('doc_curp').files[0].name}`);
    $("#lbl_curp").removeClass('has-error');
  }
  if ($("#doc_rfc").val().length > 0) {
    $("#lbl_rfc").empty();
    $("#lbl_rfc").append(`${document.getElementById('doc_rfc').files[0].name}`);
    $("#lbl_rfc").removeClass('has-error');
  }
}

$("#form_document").submit(function (e) {
  e.preventDefault();
  mensaje = "<p>";
  if ($("#ingles").val().length == 0) {
    error_ingles = "Campo Requerido"
    $("#error_ingles").text(error_ingles);
  } else {
    error_ingles = ""
    $("#error_ingles").text(error_ingles);
  }
  if ($("#cv").val().length == 0) {
    error_cv = "Campo Requerido"
    $("#error_cv").text(error_cv);
  } else {
    error_cv = ""
    $("#error_cv").text(error_cv);
  }

  var peso_doc_domicilio = "";
  if ($("#doc_domicilio").val().length == 0) {
    error_doc_domicilio = "Archivo Requerido";
    $("#lbl_domicilio").addClass('has-error');
  } else if (document.getElementById("doc_domicilio").files[0].size > 2000000) {
    error_doc_domicilio = "2MB";
    peso_doc_domicilio=document.getElementById("doc_domicilio").files[0].size /1000000;
    mensaje = mensaje +`Peso Doc. domicilio = ${peso_doc_domicilio} MB <br> `;
    $("#lbl_domicilio").addClass('has-error');
  } else {
    error_doc_domicilio = "";
    $("#lbl_domicilio").removeClass('has-error');
  }
  var peso_doc_acta = "";
  if ($("#doc_acta").val().length == 0) {
    error_doc_acta = "Archivo Requerido";
    $("#lbl_acta").addClass('has-error');
  } else if (document.getElementById("doc_acta").files[0].size > 2000000) {
    error_doc_acta = "2MB";
    peso_doc_acta=document.getElementById("doc_acta").files[0].size /1000000;
    mensaje = mensaje +`Peso Doc. acta = ${peso_doc_acta} MB <br> `;
    $("#lbl_acta").addClass('has-error');
  } else {
    error_doc_acta = "";
    $("#lbl_acta").removeClass('has-error');
  }
  var peso_doc_estudios = "";
  if ($("#doc_estudios").val().length == 0) {
    error_doc_estudios = "Archivo Requerido";
    $("#lbl_estudios").addClass('has-error');
  } else if (document.getElementById("doc_estudios").files[0].size > 2000000) {
    error_doc_estudios = "2MB";
    peso_doc_estudios=document.getElementById("doc_estudios").files[0].size /1000000;
    mensaje = mensaje +`Peso Doc. estudios = ${peso_doc_estudios} MB <br> `;
    $("#lbl_estudios").addClass('has-error');
  } else {
    error_doc_estudios = "";
    $("#lbl_estudios").removeClass('has-error');
  }

  var peso_doc_curp = "";
  if ($("#doc_curp").val().length == 0) {
    error_doc_curp = "Archivo Requerido";
    $("#lbl_curp").addClass('has-error');
  } else if (document.getElementById("doc_curp").files[0].size > 2000000) {
    error_doc_curp = "2MB";
    peso_doc_curp=document.getElementById("doc_curp").files[0].size /1000000;
    mensaje = mensaje +`Peso Doc. curp = ${peso_doc_curp} MB <br> `;
    $("#lbl_curp").addClass('has-error');
  } else {
    error_doc_curp = "";
    $("#lbl_curp").removeClass('has-error');
  }

  var peso_doc_rfc = "";
  if ($("#doc_rfc").val().length == 0) {
    error_doc_rfc = "Archivo Requerido";
    $("#lbl_rfc").addClass('has-error');
  } else if (document.getElementById("doc_rfc").files[0].size > 2000000) {
    error_doc_rfc = "2MB";
    peso_doc_rfc=document.getElementById("doc_rfc").files[0].size /1000000;
    mensaje = mensaje +`Peso Doc. rfc = ${peso_doc_rfc} MB <br> `;
    $("#lbl_rfc").addClass('has-error');
  } else {
    error_doc_rfc = "";
    $("#lbl_rfc").removeClass('has-error');
  }

  error_doc_ingles = "";
  if ($("#ingles").val() == 1) {
    var peso_doc_ingles = "";
    if ($("#doc_ingles").val().length == 0) {
      error_doc_ingles = "Archivo Requerido";
      $("#lbl_ingles").addClass('has-error');
    } else if (document.getElementById("doc_ingles").files[0].size > 2000000) {
      error_doc_ingles = "2MB";
      peso_doc_ingles=document.getElementById("doc_ingles").files[0].size /1000000;
      mensaje = mensaje +`Peso Doc. ingles = ${peso_doc_ingles} MB <br> `;
      $("#lbl_ingles").addClass('has-error');
    } else {
      error_doc_ingles = "";
      $("#lbl_ingles").removeClass('has-error');
    }
  }

  error_doc_cv = "";
  if ($("#cv").val() == 1) {
    var peso_doc_cv = "";
    if ($("#doc_cv").val().length == 0) {
      error_doc_cv = "Archivo Requerido";
      $("#lbl_cv").addClass('has-error');
    } else if (document.getElementById("doc_cv").files[0].size > 2000000) {
      error_doc_cv = "2MB";
      peso_doc_cv=document.getElementById("doc_cv").files[0].size /1000000;
      mensaje = mensaje +`Peso Doc. cv = ${peso_doc_cv} MB <br> `;
      $("#lbl_cv").addClass('has-error');
    } else {
      error_doc_cv = "";
      $("#lbl_cv").removeClass('has-error');
    }
  }

  error_diplo = "";
  if (arrayDiplomas.length > 0) {
    arrayDiplomas.forEach(item => {
      $("#error_doc_diploma_" + item).text('');
      if ($("#diploma_" + item).val().length == 0) {
        $("#diploma_" + item).addClass('has-error');
        error_diplo = "error";
      } else {
        error_diplo = "";
        $("#diploma_" + item).removeClass('has-error');
      }
      if ($("#doc_diploma_" + item).val().length == 0) {
        $("#lbl_diploma_" + item).addClass('has-error');
        error_diplo = "error";
      } else if (document.getElementById(`doc_diploma_${item}`).files[0].size > 2000000) {
        error_diplo = "2MB";
        $("#error_doc_diploma_" + item).text('Archivo con Peso Mayor a 2MB');
        $("#lbl_diploma_" + item).addClass('has-error');
      } else {
        error_diplo = "";
        $("#lbl_diploma_" + item).removeClass('has-error');
      }
    });
  }
  error_curso = "";
  if (arrayCursos.length > 0) {
    arrayCursos.forEach(item => {
      $("#error_doc_curso_" + item).text('');
      if ($("#curso_" + item).val().length == 0) {
        $("#curso_" + item).addClass('has-error');
        error_curso = "error";
      } else {
        error_curso = "";
        $("#curso_" + item).removeClass('has-error');
      }
      if ($("#doc_curso_" + item).val().length == 0) {
        $("#lbl_curso_" + item).addClass('has-error');
        error_curso = "error";
      } else if (document.getElementById(`doc_curso_${item}`).files[0].size > 2000000) {
        error_curso = "2MB";
        $("#error_doc_curso_" + item).text('Archivo con Peso Mayor a 2MB');
        $("#lbl_curso_" + item).addClass('has-error');
      } else {
        error_curso = "";
        $("#lbl_curso_" + item).removeClass('has-error');
      }
    });
  }
 if (error_doc_domicilio != "" ||
    error_doc_acta != "" ||
    error_doc_estudios != "" ||
    error_doc_curp != "" ||
    error_doc_rfc != "" ||
    error_doc_ingles != "" ||
    error_doc_cv != "" ||
    error_diplo != "" ||
    error_curso != ""
  ) {
    if (error_doc_domicilio == "2MB" ||
    error_doc_acta == "2MB" ||
    error_doc_estudios == "2MB" ||
    error_doc_curp == "2MB" ||
    error_doc_rfc == "2MB" ||
    error_doc_ingles == "2MB" ||
    error_doc_cv == "2MB") { 
      mensaje = mensaje + "</p>"; 
      console.log(mensaje);
    Swal.fire({
      icon: "error",
      title: "PESO DE ARCHIVOS",
      html: mensaje,
    });
    return false;
  }
    return false;
  }
  let timerInterval = Swal.fire({ //se le asigna un nombre al swal
    title: 'Guardando!',
    html: 'Espere unos Segundos.',
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading() //remplaza boton "OK" por animacion de "circulo Cargando"
    },
  });
  $("#btn_document").prop("disbled", true);
  let dataString = new FormData($("#form_document")[0]);
  $.ajax({
    url: `${urls}permisos/info_personal_doc`, //archivo que recibe la peticion
    type: "POST",
    data: dataString, //datos que se envian a traves de ajax
    processData: false,
    contentType: false,
    cache: false,
    dataType: "json",
    success: function (response) {
      $("#btn_document").prop("disabled", false);
      Swal.close(timerInterval); // cierra el swal en ejecucion (nombre del swal)
      if (response == true) {
        $('#form_document').trigger("reset");
        $("#item-duplica").slideUp("slow", function () {
          $(".extras").remove();
        });
        Swal.fire({
          icon: 'success',
          title: '',
          text: "!TUS COMPROBANTES SE HAN REGISTRADO CORRECTAMENTE...!",
          allowOutsideClick: false,
        }).then((result) => {
          setTimeout(function () {
            location.href = `${urls}usuarios/info`;
          }, 100);
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador del Sistema",
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
  });
});