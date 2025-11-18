<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Mi Información
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
  .btn-style {
    margin-top: 12px;
    margin-bottom: 1rem;
    height: 45px;
    width: max-content;
  }

  .custom-file-label::after {
    content: "Seleccionar";
  }

  .file-error {
    background-color: red;
  }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Datos Generales</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">Información</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- SELECT2 EXAMPLE -->
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Mi Información</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body col-md-12">
          <div class="container-fluid">
            <form id="informacion_personal" method="post" enctype="multipart/form-data">
              <input type="hidden" id="id" name="id">
              <h5>DATOS PERSONALES</h5>
              <p id="nota_info" style="color:red;">*NOTA: Subir los Comprobantes en archivos PDF</p>
              <div class="row">
                <div class=" form-group col-md-3">
                  <label for="fecha_ingreso">Fecha de Ingreso:</label>
                  <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" disabled>
                </div>
                <div class=" form-group col-md-3">
                  <label for="nombre">Nombre(s):</label>
                  <input type="text" class="form-control" id="nombre" name="nombre" disabled>
                </div>
                <div class=" form-group col-md-3">
                  <label for="apep">Apellido Paterno:</label>
                  <input type="text" class="form-control" id="apep" name="apep" disabled>
                </div>
                <div class=" form-group col-md-3">
                  <label for="apem">Apellido Materno:</label>
                  <input type="text" class="form-control" id="apem" name="apem" disabled>
                </div>
              </div>
              <div class="row">
                <div class=" form-group col-md-3">
                  <label for="genero">Genero:</label>
                  <select class="form-control" id="genero" name="genero">
                    <option value="MASCULINO">MASCULINO</option>
                    <option value="FEMENINO">FEMENINO</option>
                    <option value="INDISTINTO">INDISTINTO</option>
                  </select>
                </div>
                <div class=" form-group col-md-3">
                  <label for="edo_civil">Estado Civil:</label>
                  <select class="form-control" id="edo_civil" name="edo_civil">
                    <option value="UNION LIBRE">UNION LIBRE</option>
                    <option value="SOLTERO">SOLTER@</option>
                    <option value="VIUDO">VIUD@</option>
                    <option value="CASADO">CASAD@</option>
                  </select>
                </div>
                <div class=" form-group col-md-3">
                  <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                  <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" onchange="valida()">
                </div>
                <div class=" form-group col-md-3">
                  <label for="edad">Edad:</label>
                  <input type="number" min="1" class="form-control" id="edad" name="edad" onchange="valida()">
                </div>
              </div>
              <div class="row">
                <div class=" form-group col-md-3">
                  <label for="escolaridad">Escolaridad:</label>
                  <select class="form-control" name="escolaridad" id="escolaridad" onchange="lvlEscolaridad()">
                    <option value="DOCTORADO">DOCTORADO</option>
                    <option value="MAESTRIA">MAESTRIA</option>
                    <option value="ESPECIALIDAD">ESPECIALIDAD</option>
                    <option value="LICENCIATURA">LICENCIATURA</option>
                    <option value="INGENIERIA">INGENIERIA</option>
                    <option value="BACHILLERATO TéCNICO">BACHILLERATO TÉCNICO</option>
                    <option value="BACHILLERATO GENERAL">BACHILLERATO GENERAL</option>
                    <option value="SECUNDARIA">SECUNDARIA</option>
                    <option value="PRIMARIA">PRIMARIA</option>
                  </select>
                </div>
                <div class="" id="titulo_div"></div>
                <div class="form-group col-md-4" id="doc_titulo_div"></div>
              </div>
              <hr>
              <h5>DATOS DE CÓNYUGE</h5>
              <div class="row">
                <div class=" form-group col-md-5">
                  <label for="nombre_cony">Nombre:</label>
                  <input type="text" class="form-control" id="nombre_cony" name="nombre_cony" aria-describedby="inputGroupFileAddon01" onchange="valida()">
                </div>
                <div class=" form-group col-md-2">
                  <label for="edad_cony">Edad:</label>
                  <input type="number" min="1" class="form-control" id="edad_cony" name="edad_cony" onchange="valida()">
                </div>
                <div class=" form-group col-md-3">
                  <label for="ocupacion_cony">Ocupacion:</label>
                  <input type="text" class="form-control" id="ocupacion_cony" name="ocupacion_cony" aria-describedby="inputGroupFileAddon01" onchange="valida()">
                </div>
                <div class=" form-group col-md-2">
                  <label for="cel_cony">Telefono:</label>
                  <input type="text" class="form-control" id="cel_cony" name="cel_cony" onchange="valida()">
                </div>
              </div>
              <hr>
              <div class="row" style="height:40px;">
                <h5 class="form-group col-md-4">DIRECCION</h5>
                <div class="form-group col-md-8" style="text-align:right;">
                  <button id="btn_direccion" class="btn btn-guardar btn-style" style="padding-top:3px;height:2rem;margin-top:0px;background-color:#0056B3!important;margin-left:1rem;"><i class="far fa-edit"></i>&nbsp;&nbsp;&nbsp;Nueva Direccion</button>
                </div>
              </div>
              <div class="row">
                <div class=" form-group col-md-4">
                  <label for="calle">Calle:</label>
                  <input type="text" class="form-control" id="calle" name="calle" aria-describedby="inputGroupFileAddon01" onchange="valida()">
                </div>
                <div class=" form-group col-md-3">
                  <label for="num_int">Numero Interior:</label>
                  <input type="text" class="form-control" id="num_int" name="num_int" onchange="valida()">
                </div>
                <div class=" form-group col-md-3">
                  <label for="num_ext">Numero Exterior:</label>
                  <input type="text" class="form-control" id="num_ext" name="num_ext" onchange="valida()">
                </div>
                <div class=" form-group col-md-2">
                  <label for="cp">Codigo Postal:</label>
                  <input type="number" min="1" class="form-control" id="cp" name="cp" onchange="valida()">
                </div>
              </div>
              <div class="row">
                <div id="col_1" class="form-group col-md-4">
                  <label for="colonia">Colonia:</label>
                  <input type="text" class="form-control" id="colonia" name="colonia" aria-describedby="inputGroupFileAddon01" onchange="valida()">
                </div>
                <div id="col_2" class="form-group col-md-4">
                  <label for="municipio">Municipio:</label>
                  <input type="text" class="form-control" id="municipio" name="municipio" aria-describedby="inputGroupFileAddon01" onchange="valida()">
                </div>
                <div id="col_3" class="form-group col-md-4">
                  <label for="estado">Estado:</label>
                  <input type="text" class="form-control" id="estado" name="estado" aria-describedby="inputGroupFileAddon01" onchange="valida()">
                </div>
                <div id="col_4" class="form-group col-md-0"></div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <button id="btn_edit_informacion_personal" class="btn btn-guardar btn-style"><i class="far fa-edit"></i> &nbsp;&nbsp;&nbsp; Editar</button>
                </div>
                <div class="col-md-6" style="text-align:right;">
                  <button id="btn_cancel_informacion_personal" class="btn btn-guardar btn-style" style="margin-right:20px;"><i class="far fa-window-close"></i> &nbsp;&nbsp;&nbsp; Cancelar</button>
                  <button id="btn_informacion_personal" type="submit" class="btn btn-guardar btn-style"><i class="fas fa-save"></i> &nbsp;&nbsp;&nbsp; Guardar Cambios</button>
                </div>
            </form>
          </div>
        </div>

        <div class="card-footer">
          <a href="#">Mi Informacion</a>
        </div>
      </div>
    </div>
  </section>
  <!-- Card 2 -->
  <section class="content">
    <div class="container-fluid">
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Contacto de Emergencia</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body col-md-12">
          <div class="container-fluid">
            <form id="contacto_emergencia" method="post" enctype="multipart/form-data">
              <h5>DATOS DE CONTACTOS</h5>
              <div class="row">
                <input type="hidden" id="id_1" name="id_[]">
                <div class=" form-group col-md-6">
                  <label for="contac_nombre_1">Nombre Completo:</label>
                  <input type="text" class="form-control" id="contac_nombre_1" name="contac_nombre_[]" aria-describedby="inputGroupFileAddon01" onchange="validaContac()">
                </div>
                <div class=" form-group col-md-3">
                  <label for="contac_pariente_1">Parentesco:</label>
                  <select class="form-control" id="contac_pariente_1" name="contac_pariente_[]" onchange="validaContac()">
                    <option value="MADRE">Madre</option>
                    <option value="PADRE">Padre</option>
                    <option value="HERMANO">Hermano</option>
                    <option value="HERMANA">Hermana</option>
                    <option value="EESPOSO">Esposo</option>
                    <option value="ESPOSA">Esposa</option>
                    <option value="HIJA">Hija</option>
                    <option value="HIJO">Hijo</option>
                    <option value="TIA">Tía</option>
                    <option value="TIO">Tío</option>
                    <option value="OTRO">Otro</option>
                  </select>
                </div>
                <div class=" form-group col-md-3">
                  <label for="contac_tel_1">Telefono:</label>
                  <input type="text" class="form-control" id="contac_tel_1" name="contac_tel_[]" onchange="validaContac()">
                </div>
              </div>
              <hr>
              <div class="row">
                <input type="hidden" id="id_2" name="id_[]">
                <div class=" form-group col-md-6">
                  <label for="contac_nombre_2">Nombre Completo:</label>
                  <input type="text" class="form-control" id="contac_nombre_2" name="contac_nombre_[]" aria-describedby="inputGroupFileAddon01" onchange="validaContac()">
                </div>
                <div class=" form-group col-md-3">
                  <label for="contac_pariente_2">Parentesco:</label>
                  <select class="form-control" id="contac_pariente_2" name="contac_pariente_[]" onchange="validaContac()">
                    <option value="MADRE">Madre</option>
                    <option value="PADRE">Padre</option>
                    <option value="HERMANO">Hermano</option>
                    <option value="HERMANA">Hermana</option>
                    <option value="EESPOSO">Esposo</option>
                    <option value="ESPOSA">Esposa</option>
                    <option value="HIJA">Hija</option>
                    <option value="HIJO">Hijo</option>
                    <option value="TIA">Tía</option>
                    <option value="TIO">Tío</option>
                    <option value="OTRO">Otro</option>
                  </select>
                </div>
                <div class=" form-group col-md-3">
                  <label for="contac_tel_2">Telefono:</label>
                  <input type="text" class="form-control" id="contac_tel_2" name="contac_tel_[]" onchange="validaContac()">
                </div>
              </div>
              <input type="hidden" id="cantidad" name="cantidad">
              <div class="row">
                <div class="col-md-6">
                  <button id="btn_edit_contacto_emergencia" class="btn btn-guardar btn-style"><i class="far fa-edit"></i> &nbsp;&nbsp;&nbsp; Editar</button>
                </div>
                <div class="col-md-6" style="text-align:right;">
                  <button id="btn_cancel_contacto_emergencia" class="btn btn-guardar btn-style" style="margin-right:20px;"><i class="far fa-window-close"></i> &nbsp;&nbsp;&nbsp; Cancelar</button>
                  <button id="btn_contacto_emergencia" type="submit" class="btn btn-guardar btn-style"><i class="fas fa-save"></i> &nbsp;&nbsp;&nbsp; Guardar Cambios</button>
                </div>
            </form>
          </div>
        </div>

        <div class="card-footer">
          <a href="#">Mi Informacion</a>
        </div>
      </div>
    </div>
  </section>
  <!-- Card3 -->
  <section class="content">
    <div class="container-fluid">
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Informacion de Familiares</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body col-md-12">
          <div class="container-fluid">
            <form id="familia" method="post" enctype="multipart/form-data">
              <h5>DATOS DE PADRES</h5>
              <div class="row">
                <input type="hidden" id="padres_id_1" name="padres_id_[]">
                <div class=" form-group col-md-5">
                  <label for="padres_nombre_1">Nombre Completo:</label>
                  <input type="text" class="form-control" id="padres_nombre_1" name="padres_nombre_[]" aria-describedby="inputGroupFileAddon01" onchange="validaPadres()">
                </div>
                <div class=" form-group col-md-2">
                  <label for="padres_fecha_1">Fecha de Nacimiento:</label>
                  <input type="date" class="form-control" id="padres_fecha_1" name="padres_fecha_[]" onchange="validaPadres()">
                </div>
                <div class=" form-group col-md-2">
                  <label for="padres_genero_1">Genero:</label>
                  <select class="form-control" id="padres_genero_1" name="padres_genero_[]" onchange="validaPadres()">
                    <option value="MASCULINO">MASCULINO</option>
                    <option value="FEMENINO">FEMENINO</option>
                    <option value="INDISTINTO">INDISTINTO</option>
                  </select>
                </div>
                <div class=" form-group col-md-2">
                  <label for="padres_finado_1">Estado:</label>
                  <select class="form-control" id="padres_finado_1" name="padres_finado_[]" onchange="validaPadres()">
                    <option value="VIVE">VIVE</option>
                    <option value="FINADO">FINADO</option>
                  </select>
                </div>
                <div class=" form-group col-md-1">
                  <label for="padres_edad_1">Edad:</label>
                  <input type="number" min="1" class="form-control" id="padres_edad_1" name="padres_edad_[]" onchange="validaPadres()">
                </div>
              </div>
              <hr>
              <div class="row">
                <input type="hidden" id="padres_id_2" name="padres_id_[]">
                <div class=" form-group col-md-5">
                  <label for="padres_nombre_2">Nombre Completo:</label>
                  <input type="text" class="form-control" id="padres_nombre_2" name="padres_nombre_[]" aria-describedby="inputGroupFileAddon01" onchange="validaPadres()">
                </div>
                <div class=" form-group col-md-2">
                  <label for="padres_fecha_2">Fecha de Nacimiento:</label>
                  <input type="date" class="form-control" id="padres_fecha_2" name="padres_fecha_[]" onchange="validaPadres()">
                </div>
                <div class=" form-group col-md-2">
                  <label for="padres_genero_2">Genero:</label>
                  <select class="form-control" id="padres_genero_2" name="padres_genero_[]" onchange="validaPadres()">
                    <option value="MASCULINO">MASCULINO</option>
                    <option value="FEMENINO">FEMENINO</option>
                    <option value="INDISTINTO">INDISTINTO</option>
                  </select>
                </div>
                <div class=" form-group col-md-2">
                  <label for="padres_finado_2">Estado:</label>
                  <select class="form-control" id="padres_finado_2" name="padres_finado_[]" onchange="validaPadres()">
                    <option value="VIVE">VIVE</option>
                    <option value="FINADO">FINADO</option>
                  </select>
                </div>
                <div class=" form-group col-md-1">
                  <label for="padres_edad_2">Edad:</label>
                  <input type="number" min="1" class="form-control" id="padres_edad_2" name="padres_edad_[]" onchange="validaPadres()">
                </div>
              </div>
              <hr>
              <hr>
              <div class="row">
                <div class="form-group col-md-3">
                  <h5>DATOS DE HIJOS</h5>
                  <input type="hidden" id="cantidad_hijos" name="cantidad_hijos">
                </div>
                <div id="error_hijos" class="form-group col-md-7"></div>
                <div class="form-group col-md-2">
                  <button id="btn_agregar" class="btn btn-guardar btn-style" style="background-color:#0056B3!important;"><i class="fas fa-user-plus"></i> &nbsp;&nbsp;&nbsp; Agregar</button>
                </div>
              </div>
              <div id="hijos"></div>
              <input type="hidden" id="id_datos" name="id_datos">
              <input type="hidden" id="id_nomina" name="id_nomina">
              <div class="row">
                <div class="col-md-6">
                  <button id="btn_edit_familia" class="btn btn-guardar btn-style"><i class="far fa-edit"></i> &nbsp;&nbsp;&nbsp; Editar</button>
                </div>
                <div class="col-md-6" style="text-align:right;">
                  <button id="btn_cancel_familia" class="btn btn-guardar btn-style" style="margin-right:20px;"><i class="far fa-window-close"></i> &nbsp;&nbsp;&nbsp; Cancelar</button>
                  <button id="btn_familia" type="submit" class="btn btn-guardar btn-style"><i class="fas fa-save"></i> &nbsp;&nbsp;&nbsp; Guardar Cambios</button>
                </div>
            </form>
          </div>
        </div>

        <div class="card-footer">
          <a href="#">Mi Informacion</a>
        </div>
      </div>
    </div>
  </section>
  <!-- Card4 -->
  <section class="content">
    <div class="container-fluid">
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Documentos</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body col-md-12">
          <div class="container-fluid">
            <form id="document" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id_datos_doc" id="id_datos_doc" value="">
              <h5>DOCUMENTOS</h5>
              <div class="row">
                <input type="hidden" id="id_doc_acta" name="id_doc_acta">
                <div class="form-group col-md-4" id="acta_div"></div>
                <input type="hidden" id="id_doc_ingles" name="id_doc_ingles">
                <div class="form-group col-md-4" id="ingles_div"></div>
                <input type="hidden" id="id_doc_cv" name="id_doc_cv">
                <div class="form-group col-md-4" id="cv_div"></div>
              </div>
              <div class="row">
                <input type="hidden" id="id_doc_estudios" name="id_doc_estudios">
                <div class="form-group col-md-4" id="estudios_div"></div>
                <input type="hidden" id="id_doc_curp" name="id_doc_curp">
                <div class="form-group col-md-4" id="curp_div"></div>
                <input type="hidden" id="id_doc_rfc" name="id_doc_rfc">
                <div class="form-group col-md-4" id="rfc_div"></div>
              </div>
              <hr>
              <div class="row" style="margin-bottom:-2rem;">
                <div class="col-md-6 form-group" id="diplomas_div_error"></div>
                <div class="col-md-6 form-group" id="cursos_div_error"></div>
              </div>
              <div class="form-group row" style="height:3rem;">
                <div class="col-md-6 form-group">
                  <label id="lbl_1" for="diplomados">Diplomados &nbsp;&nbsp;&nbsp;
                    <button class="btn btn-guardar btn-style" id="btn_diploma"><i class="fas fa-plus"></i>&nbsp;&nbsp;&nbsp; Añadir Diploma</button>
                  </label>
                </div>
                <div class="col-md-6 form-group">
                  <label id="lbl_2" for="cursos_externos">Cursos Externos &nbsp;&nbsp;&nbsp;
                    <button class="btn btn-guardar btn-style" id="btn_curso"><i class="fas fa-plus"></i>&nbsp;&nbsp;&nbsp; Añadir Curso Externo</button>
                  </label>
                </div>
              </div>
              <div class="row">
                <div id="diplimas_div" class="col-md-6"></div>
                <div id="cursos_div" class="col-md-6" style="text-align:left;"></div>
              </div>
              <div class="row" style="margin-top:1rem;">
                <div class="col-md-6">
                  <button id="btn_edit_document" class="btn btn-guardar btn-style"><i class="far fa-edit"></i> &nbsp;&nbsp;&nbsp; Editar</button>
                </div>
                <div class="col-md-6" style="text-align:right;">
                  <button id="btn_cancel_document" class="btn btn-guardar btn-style" style="margin-right:20px;"><i class="far fa-window-close"></i> &nbsp;&nbsp;&nbsp; Cancelar</button>
                  <button id="btn_document" type="submit" class="btn btn-guardar btn-style"><i class="fas fa-save"></i> &nbsp;&nbsp;&nbsp; Guardar Cambios</button>
                </div>
              </div>
            </form>
          </div>
        </div>

        <div class="card-footer">
          <a href="#">Mi Informacion</a>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="modal fade" id="archivosModal" tabindex="-1" aria-labelledby="emergencyContactModalLabel" aria-hidden="true" data-backdrop='static' data-keyboard="false">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header" style="margin-bottom: -1px;">
            <h5 class="modal-title"><i class="fas fa-file-upload" style="margin-right: 0.5rem;"></i>SUBIR ARCHIVOS </h5>
            <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button> -->
          </div>
          <form id="form_archivos" method="post" enctype="multipart/form-data">
            <div class="modal-body">
              <h5 class="modal-title">Registra tus Documentos </h5>
              <b style="color: red;">*NOTA: Peso Maximo de Archivos 2 MB. </b>
              <div class="row">
                <div class="col-md-4">
                  <label for="curp_m">CURP:</label>
                  <input type="text" style="text-transform:uppercase;" class="form-control" name="curp_m" id="curp_m" aria-describedby="inputGroupFileAddon01" onchange="validarFileModal()">
                </div>
                <div class="col-md-6">
                <label for="doc_curp_m">Comprobante:</label>
                  <div class="custom-file">
                    <input type="file" accept="application/pdf" class="custom-file-input" id="doc_curp_m" name="doc_curp_m" onchange="validarFileModal()">
                    <label style="color:#D9D9D9;" id="lbl_curp_m" class="custom-file-label" for="doc_curp_m">CURP</label>
                  </div>
                </div>
              </div>
              <div class="row" style="margin-top:10px;">
                <div class="col-md-4">
                  <label for="rfc_m">RFC (Constancia de Situación Fiscal):</label>
                  <input type="text" style="text-transform:uppercase;" class="form-control" name="rfc_m" id="rfc_m" aria-describedby="inputGroupFileAddon01" onchange="validarFileModal()">
                </div>
                <div class="col-md-6">
                  <label for="doc_rfc_m">Comprobante:</label>
                  <div class="custom-file">
                    <input type="file" accept="application/pdf" class="custom-file-input" id="doc_rfc_m" name="doc_rfc_m" onchange="validarFileModal()">
                    <label style="color:#D9D9D9;" id="lbl_rfc_m" class="custom-file-label" for="doc_rfc_m">RFC</label>
                  </div>
                </div>
              </div>
              <div class="row" style="margin-top:10px;">
                <div class="col-md-6">
                  <label for="doc_acta_m">Acta de Nacimiento:</label>
                  <div class="custom-file">
                    <input type="file" accept="application/pdf" class="custom-file-input" id="doc_acta_m" name="doc_acta_m" aria-describedby="inputGroupFileAddon01" onchange="validarFileModal()">
                    <label style="color:#D9D9D9;" id="lbl_acta_m" class="custom-file-label" for="doc_acta_m">Acta de Nacimiento</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <label for="doc_domicilio_m">Comprobante de Domicilio:</label>
                  <div class="custom-file">
                    <input type="file" accept="application/pdf" class="custom-file-input" id="doc_domicilio_m" name="doc_domicilio_m" aria-describedby="inputGroupFileAddon01" onchange="validarFileModal()">
                    <label style="color:#D9D9D9;" id="lbl_domicilio_m" class="custom-file-label">Domicilio</label>
                  </div>
                </div>
              </div>
              <div class="row" style="margin-top:10px;">
                <div class="col-md-8">
                  <label for="doc_estudios_m" id="lbl_doc_estudios_m"></label>
                  <div class="custom-file">
                    <input type="file" accept="application/pdf" class="custom-file-input" id="doc_estudios_m" name="doc_estudios_m" aria-describedby="inputGroupFileAddon01" onchange="validarFileModal()">
                    <label style="color:#D9D9D9;" id="lbl_estudios_m" class="custom-file-label" for="doc_estudios_m">Ultimo Grado de Estudios</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">CERRAR</button> -->
              <button type="submit" id="btn_archivos" class="btn btn-guardar">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>

</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/js/users/my_info_v3.js"></script>
<?= $this->endSection() ?>