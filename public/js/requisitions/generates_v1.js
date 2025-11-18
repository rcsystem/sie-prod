/**
 * ARCHIVO MODULO REQUISICIONES
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
$(document).ready(function () {
  $(".select2bs4").select2({
    placeholder: "Selecciona una Opción",
  });
});

//Me creo una funcion para al cambiar el select me llene un campo de texto con ese valor en este caso centro de costo dependiendo el area operativa
$("#area_operativa").on("change", function () {
  $id_depto = $("#area_operativa").val();

  let data = new FormData();

  data.append("id_depto", $id_depto);

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: `${urls}requisiciones/centro-costo`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    dataType: "json",
    success: function (resp) {
      //console.log(resp)
      $("#centro_costo").val(resp.cost_center);
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

/* CODIGO PARA VALIDAR Y PASAR A LA SIGUIENTE FIELDSETS */

var current_fs, next_fs, previous_fs; //fieldsets
var opacity;
var current = 0;
var steps = $("fieldset").length;

setProgressBar(current);

$(".next").click(function () {
  if ($.trim($("#empresa_solicitante").val()).length == 0) {
    var error_solicitante = "El campo es requerido";
    $("#error_empresa_solicitante").text(error_solicitante);
    $("#empresa_solicitante").addClass("has-error");
  } else {
    error_solicitante = "";
    $("#error_empresa_solicitante").text(error_solicitante);
    $("#empresa_solicitante").removeClass("has-error");
  }

  if ($.trim($("#centro_costo").val()).length == 0) {
    var error_costo = "El campo es requerido";
    $("#error_centro_costo").text(error_costo);
    $("#centro_costo").addClass("has-error");
  } else {
    error_costo = "";
    $("#error_centro_costo").text(error_costo);
    $("#centro_costo").removeClass("has-error");
  }

  if ($.trim($("#area_operativa :selected").text()).length == 0) {
    var error_area = "El campo es requerido";
    $("#error_area_operativa").text(error_area);
    $("#area_operativa").addClass("has-error");
    $(".select2-selection--single").css({
      border: "1px solid #cc0000",
    });
  } else {
    error_area = "";
    $("#error_area_operativa").text(error_area);
    $("#area_operativa").removeClass("has-error");
  }
  if($("#tipo_estudio").length){
  if ($.trim($("#tipo_estudio").val()).length == 0) {
    var error_areas = "El campo es requerido";
    $("#error_tipo_estudio").text(error_areas);
    $("#tipo_estudio").addClass("has-error");
  } else {
    error_areas = "";
    $("#error_tipo_estudio").text(error_areas);
    $("#tipo_estudio").removeClass("has-error");
  }
  }else{
    var error_areas="";
  }
  if ($.trim($("#tipo_personal").val()).length == 0) {
    var error_personal = "El campo es requerido";
    $("#error_tipo_personal").text(error_personal);
    $("#tipo_personal").addClass("has-error");
  } else {
    error_personal = "";
    $("#error_tipo_personal").text(error_personal);
    $("#tipo_personal").removeClass("has-error");
  }

  if ($.trim($("#puesto_solicitado").val()).length == 0) {
    var error_puesto = "El campo es requerido";
    $("#error_puesto_solicitado").text(error_puesto);
    $("#puesto_solicitado").addClass("has-error");
  } else {
    error_puesto = "";
    $("#error_puesto_solicitado").text(error_puesto);
    $("#puesto_solicitado").removeClass("has-error");
  }

  if ($.trim($("#personas_requeridas").val()).length == 0) {
    var error_personas = "El campo es requerido";
    $("#error_personas_requeridas").text(error_personas);
    $("#personas_requeridas").addClass("has-error");
  } else if ($("#personas_requeridas").val() < 1) {
    var error_personas =
      "El número de personas requeridas no puede ser menor a 1";
    $("#error_personas_requeridas").text(error_personas);
    $("#personas_requeridas").addClass("has-error");
  } else if ($("#personas_requeridas").val() > 50) {
    var error_personas ="El número de personas requeridas no puede ser mayor a 50";
    $("#error_personas_requeridas").text(error_personas);
    $("#personas_requeridas").addClass("has-error");
  } else {
    var error_personas = "";
    $("#error_personas_requeridas").text(error_personas);
    $("#personas_requeridas").removeClass("has-error");
  }

  if ($.trim($("#grado_estudios").val()).length == 0) {
    var error_estudios = "El campo es requerido";
    $("#error_grado_estudios").text(error_estudios);
    $("#grado_estudios").addClass("has-error");
  } else {
    error_estudios = "";
    $("#error_grado_estudios").text(error_estudios);
    $("#grado_estudios").removeClass("has-error");
  }

  if ($.trim($("#motivo").val()).length == 0) {
    var error_motivo = "El campo es requerido";
    $("#error_motivo").text(error_motivo);
    $("#motivo").addClass("has-error");
  } else {
    error_motivo = "";
    $("#error_motivo").text(error_motivo);
    $("#motivo").removeClass("has-error");
  }

  if ($.trim($("#jefe_inmediato").val()).length == 0) {
    var error_jefe = "El campo es requerido";
    $("#error_jefe_inmediato").text(error_jefe);
    $("#jefe_inmediato").addClass("has-error");
  } else {
    error_jefe = "";
    $("#error_jefe_inmediato").text(error_jefe);
    $("#jefe_inmediato").removeClass("has-error");
  }

  if (
    error_solicitante != "" ||
    error_costo != "" ||
    error_area != "" ||
    error_areas != "" ||
    error_personal != "" ||
    error_puesto != "" ||
    error_personas != "" ||
    error_estudios != "" ||
    error_motivo != "" ||
    error_jefe != ""
  ) {
 
   /*  console.log(error_solicitante );
    console.log(error_costo );
    console.log(error_area );
    console.log(error_areas );
    console.log(error_personal );
    console.log(error_puesto );
    console.log(error_personas );
    console.log(error_estudios );
    console.log(error_motivo );
    console.log(error_jefe ); */
    return false;
  }

  current_fs = $(this).parent();
  next_fs = $(this).parent().next();

  //Add Class Active
  $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

  //show the next fieldset
  next_fs.show();
  //hide the current fieldset with style
  current_fs.animate(
    {
      opacity: 0,
    },
    {
      step: function (now) {
        // for making fielset appear animation
        opacity = 1 - now;

        current_fs.css({
          display: "none",
          position: "relative",
        });
        next_fs.css({
          opacity: opacity,
        });
      },
      duration: 500,
    }
  );
  setProgressBar(1);
});

/* SEGUNDA SECCION DE CREAR REQUISICION */

$(".next2").click(function () {

  if ($.trim($("#salario_inicial").val()).length == 0) {
    error_inicial = "El campo es requerido";
    $("#error_salario_inicial").text(error_inicial);
    $("#salario_inicial").addClass("has-error");
  } else if ($("#salario_inicial").val() == "$0.00") {
    error_inicial = "El salario no puede ser menor a $1";
    $("#error_salario_inicial").text(error_inicial);
    $("#salario_inicial").addClass("has-error");
  } else if ($("#salario_inicial").val() > 999999) {
    error_inicial = "El salario no puede ser mayor a $999999";
    $("#error_salario_inicial").text(error_inicial);
    $("#salario_inicial").removeClass("has-error");
  } else {
    error_inicial = "";
    $("#error_salario_inicial").text(error_inicial);
    $("#salario_inicial").removeClass("has-error");
  }

  if ($.trim($("#salario_final").val()).length == 0) {
    error_final = "El campo es requerido";
    $("#error_salario_final").text(error_final);
    $("#salario_final").addClass("has-error");
  } else if ($("#salario_final").val() == "$0.00") {
    error_final = "El salario no puede ser menor a $1";
    $("#error_salario_final").text(error_final);
    $("#salario_final").addClass("has-error");
  } else if ($("#salario_final").val() > 999999) {
    error_final = "El salario no puede ser mayor a $999999";
    $("#error_salario_final").text(error_final);
    $("#salario_final").removeClass("has-error");
  } else {
    error_final = "";
    $("#error_salario_final").text(error_final);
    $("#salario_final").removeClass("has-error");
  }

  if ($.trim($("#cotizacion").val()).length == 0) {
    var error_cotizacion = "El campo es requerido";
    $("#error_cotizacion").text(error_cotizacion);
    $("#cotizacion").addClass("has-error");
  } else {
    error_cotizacion = "";
    $("#error_cotizacion").text(error_cotizacion);
    $("#cotizacion").removeClass("has-error");
  }

  if ($.trim($("#periodo").val()).length == 0) {
    var error_periodo = "El campo es requerido";
    $("#error_periodo").text(error_periodo);
    $("#periodo").addClass("has-error");
  } else {
    error_periodo = "";
    $("#error_periodo").text(error_periodo);
    $("#periodo").removeClass("has-error");
  }

  if (
    error_inicial != "" ||
    error_final != "" ||
    error_cotizacion != "" ||
    error_periodo != ""
  ) {
    return false;
  }

  current_fs = $(this).parent();
  next_fs = $(this).parent().next();

  //Add Class Active
  $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

  //show the next fieldset
  next_fs.show();
  //hide the current fieldset with style
  current_fs.animate(
    {
      opacity: 0,
    },
    {
      step: function (now) {
        // for making fielset appear animation
        opacity = 1 - now;

        current_fs.css({
          display: "none",
          position: "relative",
        });
        next_fs.css({
          opacity: opacity,
        });
      },
      duration: 500,
    }
  );
  setProgressBar(2);
});

/* TERCERA SECCION DE CREAR REQUISICION */

$(".next3").click(function () {

/*   if ($.trim($("#genero_requerido").val()).length == 0) {
    var error_genero = "El campo es requerido";
    $("#error_genero_requerido").text(error_genero);
    $("#genero_requerido").addClass("has-error");
  } else {
    error_genero = "";
    $("#error_genero_requerido").text(error_genero);
    $("#genero_requerido").removeClass("has-error");
  } */

  if ($.trim($("#anios_experiencia").val()).length == 0) {
    var error_experiencia = "El campo es requerido";
    $("#error_anios_experiencia").text(error_experiencia);
    $("#anios_experiencia").addClass("has-error");
  } else {
    error_experiencia = "";
    $("#error_anios_experiencia").text(error_experiencia);
    $("#anios_experiencia").removeClass("has-error");
  }

/*   if ($.trim($("#estado_civil").val()).length == 0) {
    var error_civil = "El campo es requerido";
    $("#error_estado_civil").text(error_civil);
    $("#estado_civil").addClass("has-error");
  } else {
    error_civil = "";
    $("#error_estado_civil").text(error_civil);
    $("#estado_civil").removeClass("has-error");
  } */
  if ($.trim($("#rolar_turnos").val()).length == 0) {
    var error_turnos = "El campo es requerido";
    $("#error_rolar_turnos").text(error_turnos);
    $("#rolar_turnos").addClass("has-error");
  } else {
    error_turnos = "";
    $("#error_rolar_turnos").text(error_turnos);
    $("#rolar_turnos").removeClass("has-error");
  }

/*   if ($.trim($("#edad_minima").val()).length == 0) {
    var error_edad_minima = "El campo es requerido";
    $("#error_edad_minima").text(error_edad_minima);
    $("#edad_minima").addClass("has-error");
  } else {
    error_edad_minima = "";
    $("#error_edad_minima").text(error_edad_minima);
    $("#edad_minima").removeClass("has-error");
  } */

/*   if ($.trim($("#edad_maxima").val()).length == 0) {
    var error_edad_maxima = "El campo es requerido";
    $("#error_edad_maxima").text(error_edad_maxima);
    $("#edad_maxima").addClass("has-error");
  } else {
    error_edad_maxima = "";
    $("#error_edad_maxima").text(error_edad_maxima);
    $("#edad_maxima").removeClass("has-error");
  } */

  if ($.trim($("#trato_clientes").val()).length == 0) {
    var error_clientes = "El campo es requerido";
    $("#error_trato_clientes").text(error_clientes);
    $("#trato_clientes").addClass("has-error");
  } else {
    error_clientes = "";
    $("#error_trato_clientes").text(error_clientes);
    $("#trato_clientes").removeClass("has-error");
  }

  if ($.trim($("#manejo_personal").val()).length == 0) {
    var error_personal = "El campo es requerido";
    $("#error_manejo_personal").text(error_personal);
    $("#manejo_personal").addClass("has-error");
  } else {
    error_personal = "";
    $("#error_manejo_personal").text(error_personal);
    $("#manejo_personal").removeClass("has-error");
  }

  if ($.trim($("#licencia").val()).length == 0) {
    var error_licencia = "El campo es requerido";
    $("#error_licencia").text(error_licencia);
    $("#licencia").addClass("has-error");
  } else {
    error_licencia = "";
    $("#error_licencia").text(error_licencia);
    $("#licencia").removeClass("has-error");
  }

  if (
   // error_genero != "" ||
    error_experiencia != "" ||
    error_civil != "" ||
    error_turnos != "" ||
   // error_edad_minima != "" ||
   // error_edad_maxima != "" ||
    error_clientes != "" ||
    error_personal != "" ||
    error_licencia != ""
  ) {
    return false;
  }

  current_fs = $(this).parent();
  next_fs = $(this).parent().next();

  //Add Class Active
  $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

  //show the next fieldset
  next_fs.show();
  //hide the current fieldset with style
  current_fs.animate(
    {
      opacity: 0,
    },
    {
      step: function (now) {
        // for making fielset appear animation
        opacity = 1 - now;

        current_fs.css({
          display: "none",
          position: "relative",
        });
        next_fs.css({
          opacity: opacity,
        });
      },
      duration: 500,
    }
  );
  setProgressBar(3);
});

/* CUARTA SECCION DE CREAR REQUISICION */

$(".next4").click(function () {
  if ($.trim($("#jornada").val()).length == 0) {
    error_jornada = "El campo es requerido";
    $("#error_jornada").text(error_jornada);
    $("#jornada").addClass("has-error");
  } else {
    error_jornada = "";
    $("#error_jornada").text(error_jornada);
    $("#jornada").removeClass("has-error");
  }

  if ($.trim($("#horario_inicial").val()).length == 0) {
    error_horario_inicial = "El campo es requerido";
    $("#error_horario_inicial").text(error_horario_inicial);
    $("#horario_inicial").addClass("has-error");
  } else {
    error_horario_inicial = "";
    $("#error_horario_inicial").text(error_horario_inicial);
    $("#horario_inicial").removeClass("has-error");
  }

  if ($.trim($("#horario_final").val()).length == 0) {
    error_horario_final = "El campo es requerido";
    $("#error_horario_final").text(error_horario_final);
    $("#horario_final").addClass("has-error");
  } else {
    error_horario_final = "";
    $("#error_horario_final").text(error_horario_final);
    $("#horario_final").removeClass("has-error");
  }

  if (
    error_jornada != "" ||
    error_horario_inicial != "" ||
    error_horario_final != ""
  ) {
    return false;
  }

  current_fs = $(this).parent();
  next_fs = $(this).parent().next();

  //Add Class Active
  $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

  //show the next fieldset
  next_fs.show();
  //hide the current fieldset with style
  current_fs.animate(
    {
      opacity: 0,
    },
    {
      step: function (now) {
        // for making fielset appear animation
        opacity = 1 - now;

        current_fs.css({
          display: "none",
          position: "relative",
        });
        next_fs.css({
          opacity: opacity,
        });
      },
      duration: 500,
    }
  );
  setProgressBar(4);
});

/* QUINTA SECCION DE CREAR REQUISICION */

$(".next5").click(function () {
  if ($.trim($("#primer_conocimiento").val()).length == 0) {
    error_conoce1 = "El campo es requerido";
    $("#error_primer_conocimiento").text(error_conoce1);
    $("#primer_conocimiento").addClass("has-error");
  } else {
    error_conoce1 = "";
    $("#error_primer_conocimiento").text(error_conoce1);
    $("#primer_conocimiento").removeClass("has-error");
  }

  if ($.trim($("#segundo_conocimiento").val()).length == 0) {
    error_conoce2 = "El campo es requerido";
    $("#error_segundo_conocimiento").text(error_conoce2);
    $("#segundo_conocimiento").addClass("has-error");
  } else {
    error_conoce2 = "";
    $("#error_segundo_conocimiento").text(error_conoce2);
    $("#segundo_conocimiento").removeClass("has-error");
  }

  if ($.trim($("#tercer_conocimiento").val()).length == 0) {
    error_conoce3 = "El campo es requerido";
    $("#error_tercer_conocimiento").text(error_conoce3);
    $("#tercer_conocimiento").addClass("has-error");
  } else {
    error_conoce3 = "";
    $("#error_tercer_conocimiento").text(error_conoce3);
    $("#tercer_conocimiento").removeClass("has-error");
  }

  if ($.trim($("#cuarto_conocimiento").val()).length == 0) {
    error_conoce4 = "El campo es requerido";
    $("#error_cuarto_conocimiento").text(error_conoce4);
    $("#cuarto_conocimiento").addClass("has-error");
  } else {
    error_conoce4 = "";
    $("#error_cuarto_conocimiento").text(error_conoce4);
    $("#cuarto_conocimiento").removeClass("has-error");
  }

  if ($.trim($("#quinto_conocimiento").val()).length == 0) {
    error_conoce5 = "El campo es requerido";
    $("#error_quinto_conocimiento").text(error_conoce5);
    $("#quinto_conocimiento").addClass("has-error");
  } else {
    error_conoce5 = "";
    $("#error_quinto_conocimiento").text(error_conoce5);
    $("#quinto_conocimiento").removeClass("has-error");
  }

  if (
    error_conoce1 != "" ||
    error_conoce2 != "" ||
    error_conoce3 != "" ||
    error_conoce4 != "" ||
    error_conoce5 != ""
  ) {
    return false;
  }

  current_fs = $(this).parent();
  next_fs = $(this).parent().next();

  //Add Class Active
  $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

  //show the next fieldset
  next_fs.show();
  //hide the current fieldset with style
  current_fs.animate(
    {
      opacity: 0,
    },
    {
      step: function (now) {
        // for making fielset appear animation
        opacity = 1 - now;

        current_fs.css({
          display: "none",
          position: "relative",
        });
        next_fs.css({
          opacity: opacity,
        });
      },
      duration: 500,
    }
  );
  setProgressBar(5);
});

/* SEXTA SECCION DE CREAR REQUISICION */

$(".next6").click(function () {
  if ($.trim($("#primer_competencia").val()).length == 0) {
    error_competencia1 = "El campo es requerido";
    $("#error_primer_competencia").text(error_competencia1);
    $("#primer_competencia").addClass("has-error");
  } else {
    error_competencia1 = "";
    $("#error_primer_competencia").text(error_competencia1);
    $("#primer_competencia").removeClass("has-error");
  }

  if ($.trim($("#segunda_competencia").val()).length == 0) {
    error_competencia2 = "El campo es requerido";
    $("#error_segunda_competencia").text(error_competencia2);
    $("#segunda_competencia").addClass("has-error");
  } else {
    error_competencia2 = "";
    $("#error_segunda_competencia").text(error_competencia2);
    $("#segunda_competencia").removeClass("has-error");
  }

  if ($.trim($("#tercer_competencia").val()).length == 0) {
    error_competencia3 = "El campo es requerido";
    $("#error_tercer_competencia").text(error_competencia3);
    $("#tercer_competencia").addClass("has-error");
  } else {
    error_competencia3 = "";
    $("#error_tercer_competencia").text(error_competencia3);
    $("#tercer_competencia").removeClass("has-error");
  }
  if ($.trim($("#cuarta_competencia").val()).length == 0) {
    error_competencia4 = "El campo es requerido";
    $("#error_cuarta_competencia").text(error_competencia4);
    $("#cuarta_competencia").addClass("has-error");
  } else {
    error_competencia4 = "";
    $("#error_cuarta_competencia").text(error_competencia4);
    $("#cuarta_competencia").removeClass("has-error");
  }
  if ($.trim($("#quinta_competencia").val()).length == 0) {
    error_competencia5 = "El campo es requerido";
    $("#error_quinta_competencia").text(error_competencia5);
    $("#quinta_competencia").addClass("has-error");
  } else {
    error_competencia5 = "";
    $("#error_quinta_competencia").text(error_competencia5);
    $("#quinta_competencia").removeClass("has-error");
  }

  if (
    error_competencia1 != "" ||
    error_competencia2 != "" ||
    error_competencia3 != "" ||
    error_competencia4 != "" ||
    error_competencia5 != ""
  ) {
    return false;
  }

  current_fs = $(this).parent();
  next_fs = $(this).parent().next();

  //Add Class Active
  $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

  //show the next fieldset
  next_fs.show();
  //hide the current fieldset with style
  current_fs.animate(
    {
      opacity: 0,
    },
    {
      step: function (now) {
        // for making fielset appear animation
        opacity = 1 - now;

        current_fs.css({
          display: "none",
          position: "relative",
        });
        next_fs.css({
          opacity: opacity,
        });
      },
      duration: 500,
    }
  );
  setProgressBar(6);
});
/* SEPTIMA SECCION DE CREAR REQUISICION */

$("#msform").submit(function (e) {
  e.preventDefault();

  
  $("#genera_request").prop("disabled", true);

  if ($.trim($("#primer_actividad").val()).length == 0) {
    error_actividad1 = "El campo es requerido";
    $("#error_primer_actividad").text(error_actividad1);
    $("#primer_actividad").addClass("has-error");
  } else {
    error_actividad1 = "";
    $("#error_primer_actividad").text(error_actividad1);
    $("#primer_actividad").removeClass("has-error");
  }

  if ($.trim($("#segunda_actividad").val()).length == 0) {
    error_actividad2 = "El campo es requerido";
    $("#error_segunda_actividad").text(error_actividad2);
    $("#segunda_actividad").addClass("has-error");
  } else {
    error_actividad2 = "";
    $("#error_segunda_actividad").text(error_actividad2);
    $("#segunda_actividad").removeClass("has-error");
  }

  if ($.trim($("#tercer_actividad").val()).length == 0) {
    error_actividad3 = "El campo es requerido";
    $("#error_tercer_actividad").text(error_actividad3);
    $("#tercer_actividad").addClass("has-error");
  } else {
    error_actividad3 = "";
    $("#error_tercer_actividad").text(error_actividad3);
    $("#tercer_actividad").removeClass("has-error");
  }

  if ($.trim($("#cuarta_actividad").val()).length == 0) {
    error_actividad4 = "El campo es requerido";
    $("#error_cuarta_actividad").text(error_actividad4);
    $("#cuarta_actividad").addClass("has-error");
  } else {
    error_actividad4 = "";
    $("#error_cuarta_actividad").text(error_actividad4);
    $("#cuarta_actividad").removeClass("has-error");
  }

  if ($.trim($("#quinta_actividad").val()).length == 0) {
    error_actividad5 = "El campo es requerido";
    $("#error_quinta_actividad").text(error_actividad5);
    $("#quinta_actividad").addClass("has-error");
  } else {
    error_actividad5 = "";
    $("#error_quinta_actividad").text(error_actividad5);
    $("#quinta_actividad").removeClass("has-error");
  }

  if (
    error_actividad1 != "" ||
    error_actividad2 != "" ||
    error_actividad3 != "" ||
    error_actividad4 != "" ||
    error_actividad5 != ""
  ) {
    return false;
  }

  setProgressBar(7);

  let data = new FormData();

  data.append("empresa_solicitante", $("#empresa_solicitante").val());
  data.append("puesto_solicitado", $("#puesto_solicitado").val());
  data.append("centro_costo", $("#centro_costo").val());
  data.append("personas_requeridas", $("#personas_requeridas").val());
  data.append("area_operativa", $("#area_operativa").val());
  let area = $("#select2-area_operativa-container").attr("title");
  data.append("area_operativas", area);
  data.append("grado_estudios", $("#grado_estudios").val());
  data.append("tipo_personal", $("#tipo_personal").val());
  data.append("motivo", $("#motivo").val());
  data.append("jefe_inmediato", $("#jefe_inmediato").val());
  data.append("remplazo", $("#remplazo").val());
  data.append("salario_inicial", $("#salario_inicial").val());
  data.append("salario_final", $("#salario_final").val());
  data.append("cotizacion", $("#cotizacion").val());
  data.append("periodo", $("#periodo").val());
  //data.append("genero_requerido", $("#genero_requerido").val());
  data.append("anios_experiencia", $("#anios_experiencia").val());
  //data.append("estado_civil", $("#estado_civil").val());
  data.append("rolar_turnos", $("#rolar_turnos").val());
  //data.append("edad_minima", $("#edad_minima").val());
  //data.append("edad_maxima", $("#edad_maxima").val());
  data.append("trato_clientes", $("#trato_clientes").val());
  data.append("manejo_personal", $("#manejo_personal").val());
  data.append("licencia", $("#licencia").val());
  data.append("horario_inicial", $("#horario_inicial").val());
  data.append("horario_final", $("#horario_final").val());
  data.append("jornada", $("#jornada").val());
  data.append("primer_conocimiento", $("#primer_conocimiento").val());
  data.append("segundo_conocimiento", $("#segundo_conocimiento").val());
  data.append("tercer_conocimiento", $("#tercer_conocimiento").val());
  data.append("cuarto_conocimiento", $("#cuarto_conocimiento").val());
  data.append("quinto_conocimiento", $("#quinto_conocimiento").val());
  data.append("primer_competencia", $("#primer_competencia").val());
  data.append("segunda_competencia", $("#segunda_competencia").val());
  data.append("tercer_competencia", $("#tercer_competencia").val());
  data.append("cuarta_competencia", $("#cuarta_competencia").val());
  data.append("quinta_competencia", $("#quinta_competencia").val());
  data.append("primer_actividad", $("#primer_actividad").val());
  data.append("segunda_actividad", $("#segunda_actividad").val());
  data.append("tercer_actividad", $("#tercer_actividad").val());
  data.append("cuarta_actividad", $("#cuarta_actividad").val());
  data.append("quinta_actividad", $("#quinta_actividad").val());

  let grado_estudios = $("#grado_estudios").val();

  if (grado_estudios === "Licenciatura") {
    data.append("tipo_estudio", $("#tipo_estudio").val());
  } else if (grado_estudios === "Ingenieria") {
    data.append("tipo_estudio", $("#tipo_estudio").val());
  } else {
    
  }

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: urls + "requisiciones/insertar", //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    success: function (response) {
      console.log(response);
      //una vez que el archivo recibe el request lo procesa y lo devuelve
      //console.log(response);
      /*codigo que borra todos los campos del form newProvider*/
      if (response != "error") {
        $("#empresa_solicitante").val("");
        $("#puesto_solicitado").val("");
        $("#centro_costo").val("");
        $("#personas_requeridas").val("");
        $("#area_operativa").val("");
        $("#grado_estudios").val("");
        $("#tipo_personal").val("");
        $("#motivo").val("");
        $("#jefe_inmediato").val("");
        $("#remplazo").val("");
        $("#salario_inicial").val("");
        $("#salario_final").val("");
        $("#cotizacion").val("");
        $("#periodo").val("");
        //$("#genero_requerido").val("");
        $("#anios_experiencia").val("");
       // $("#estado_civil").val("");
        $("#rolar_turnos").val("");
        //$("#edad_minima").val("");
        //$("#edad_maxima").val("");
        $("#trato_clientes").val("");
        $("#manejo_personal").val("");
        $("#licencia").val("");
        $("#horario_inicial").val("");
        $("#horario_final").val("");
        $("#jornada").val("");
        $("#primer_conocimiento").val("");
        $("#segundo_conocimiento").val("");
        $("#tercer_conocimiento").val("");
        $("#cuarto_conocimiento").val("");
        $("#quinto_conocimiento").val("");
        $("#primer_competencia").val("");
        $("#segunda_competencia").val("");
        $("#tercer_competencia").val("");
        $("#cuarta_competencia").val("");
        $("#quinta_competencia").val("");
        $("#primer_actividad").val("");
        $("#segunda_actividad").val("");
        $("#tercer_actividad").val("");
        $("#cuarta_actividad").val("");
        $("#quinta_actividad").val("");
        $(".select2-selection__rendered").empty();
        $("#personal").removeClass("active");
        $("#payment").removeClass("active");
        $("#laboral").removeClass("active");
        $("#conocimiento").removeClass("active");
        $("#competencia").removeClass("active");
        $("#confirm").removeClass("active");
        $("#tipo_grado_estudios").empty();
        $("#tipo_grado_estudios").removeClass("col-md-4")
        $("#fieldset_final").css({
          display: "none",
          position: "relative",
        });
        $("#fieldset_inicial").css({
          display: "block",
          position: "relative",
          opacity: 1,
        });
        setProgressBar(0);
        Swal.fire(
          "!La Requisición se ha generado correctamente!",
          "",
          "success"
        );
        $("#genera_request").prop("disabled", false);
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Algo salió Mal! Contactar con el Administrador del Sistema",
        });
      }
    },
  });
});

$(".previous").click(function () {
  current_fs = $(this).parent();
  previous_fs = $(this).parent().prev();

  //Remove class active
  $("#progressbar li")
    .eq($("fieldset").index(current_fs))
    .removeClass("active");

  //show the previous fieldset
  previous_fs.show();

  //hide the current fieldset with style
  current_fs.animate(
    {
      opacity: 0,
    },
    {
      step: function (now) {
        // for making fielset appear animation
        opacity = 1 - now;

        current_fs.css({
          display: "none",
          position: "relative",
        });
        previous_fs.css({
          opacity: opacity,
        });
      },
      duration: 500,
    }
  );
  setProgressBar(--current);
});

function setProgressBar(curStep) {
  var percent = parseFloat(100 / steps) * curStep;
  percent = percent.toFixed();
  $(".progress-bar").css("width", percent + "%");
}

$(".submit").click(function () {
  return false;
});

/**
 * MODULO DE GENERACION DE REQUISICION SECCION SALARIO
 *
 * VALIDAR SI EL CAMPO TIENE DECIMALES
 */

function ValidateDecimalInputs(e) {
  var beforeDecimal = 6;
  var afterDecimal = 2;
  $(".salario").on("input", function () {
    this.value = this.value
      .replace(/[^\d.]/g, "")
      .replace(new RegExp("(^[\\d]{" + beforeDecimal + "})[\\d]", "g"), "$1")
      .replace(/(\..*)\./g, "$1")
      .replace(new RegExp("(\\.[\\d]{" + afterDecimal + "}).", "g"), "$1");
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

//Me creo una funcion para al cambiar el select me llene un campo de texto con ese valor en este caso centro de costo dependiendo el area operativa
$("#tipo_personal").on("change", function () {
  
  let tipo_usuario = $("#tipo_personal").val();
  let tipo_personal = tipo_usuario === "Administrativo" ? 1 : 2;

  if(tipo_personal == 1){
    $("#puesto_solicitado").remove();
    $("#puestos").append(`
    <input type="text" class="form-control" name="puesto_solicitado" id="puesto_solicitado" required>
    `);
    return false;
  }else{
    $("#puesto_solicitado").remove();
    $("#puestos").append(`
    <select name="puesto_solicitado" id="puesto_solicitado" class="form-control">
    <option value="">Seleccionar...</option>
</select>
    `);
   
  }
  // Guardamos el select de cursos
  var puestos = $("#puesto_solicitado");
  let data = new FormData();

  data.append("tipo_usuario", tipo_personal);

  $.ajax({
    data: data, //datos que se envian a traves de ajax
    url: urls + "usuarios/tipo_usuario", //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    dataType: "json",
    success: function (resp) {
      //console.log(resp);
      // Limpiamos el select
      puestos.find("option").remove();
      $("#puesto_solicitado").append(
        '<option value="">Seleccionar...</option>'
      );
      $.each(resp, function (id, value) {
        $("#puesto_solicitado").append(
          '<option value="' + value.job + '">' + value.job + "</option>"
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

//Me creo una funcion para al cambiar el select me agregue un input para poder agregar la carrera que estan buscando
$("#grado_estudios").on("change", function () {
  let grado_estudios = $("#grado_estudios").val();

  if (grado_estudios === "Licenciatura") {
    $("#tipo_grado_estudios").empty();
    $("#tipo_grado_estudios").addClass("col-md-4");
    campo = `    <div class="form-group">
                      <label for="licenciatura">Licenciatura en:</label>
                      <input type="text" class="form-control" id="tipo_estudio" name="tipo_estudio" placeholder="Ejemplo: Lic.Administración" required>
                      <div id="error_tipo_estudio" class="request-error text-danger"></div>
                  </div>`;
    $("#tipo_grado_estudios").append(campo);
  } else if (grado_estudios === "Ingenieria") {
    $("#tipo_grado_estudios").empty();
    $("#tipo_grado_estudios").addClass("col-md-4");
    campo = `<div class="form-group">
                <label for="ingenieria">Ingenieria en:</label>
                <input type="text" class="form-control" id="tipo_estudio" name="tipo_estudio" placeholder="Ejemplo: Ing.Industrial" required>
                <div id="error_tipo_estudio" class="request-error text-danger"></div>
            </div>`;
    $("#tipo_grado_estudios").append(campo);
  } else {
    $("#tipo_grado_estudios").empty();
    $("#tipo_grado_estudios").removeClass("col-md-4");
  }
  // Guardamos el select de cursos
});
