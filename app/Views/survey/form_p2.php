<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Datos Generales
<?= $this->endSection() ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/datos_generales/style.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2/css/select2.min.css">
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
                    <h1 class="m-0">Datos Generales.</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item active">Datos Generales</li>
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
                    <h3 class="card-title">Información General</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body col-md-12">
                    <div class="container-fluid">
                        <form id="form_document" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-7">
                                    <h2 class="fs-title">Comprobantes:</h2>
                                    <b style="color: red;">*NOTA: Peso Maximo de Archivos 2 MB. </b>
                                </div>
                                <div class="col-5">
                                    <h2 class="steps">Paso 7 - 7</h2>
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: 1rem; text-align:left;">
                                <div class="col-md-6 form-group" style="padding-top:1rem;">
                                    <label for="ingles">Certificado Ingles: &nbsp;&nbsp;&nbsp;</label>
                                    <button type="button" id="ingles_si" class="btn btn-hijo text-center">SI</button>
                                    <button type="button" id="ingles_no" class="btn btn-hijo text-center">NO</button>
                                    <input type="hidden" id="ingles" name="ingles">
                                    <div id="error_ingles" class="text-danger"></div>
                                </div>
                                <div class="col-md-6" id="ingles_div"></div>
                            </div>
                            <div class="row" style="margin-bottom: 1rem; text-align:left;">
                                <div class="col-md-6 form-group" style="padding-top:1rem;">
                                    <label for="cv">Anexar Curriculum: &nbsp;&nbsp;&nbsp;</label>
                                    <button type="button" id="cv_si" class="btn btn-hijo text-center">SI</button>
                                    <button type="button" id="cv_no" class="btn btn-hijo text-center">NO</button>
                                    <input type="hidden" id="cv" name="cv">
                                    <div id="error_cv" class="text-danger"></div>
                                </div>
                                <div class="col-md-6" id="cv_div"></div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="diplomados">Diplomados &nbsp;&nbsp;&nbsp;
                                        <button class="btn btn-hijo" id="btn_diploma"><i class="fas fa-plus"></i>&nbsp;&nbsp;&nbsp; Añadir Diploma</button>
                                    </label>
                                    <div id="diplimas_div_error"></div>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="cursos_externos">Cursos Externos &nbsp;&nbsp;&nbsp;
                                        <button class="btn btn-hijo" id="btn_curso"><i class="fas fa-plus"></i>&nbsp;&nbsp;&nbsp; Añadir Curso Externo</button>
                                    </label>
                                    <div id="cusos_div_error"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div id="diplimas_div" class="col-md-6"></div>
                                <div id="cusos_div" class="col-md-6 "></div>
                            </div>
                            <div class="row" style="margin-top:1rem;text-align:left;">
                                <div class="input-group col-md-4">
                                    <label for="doc_acta">Acta de Nacimiento:</label>
                                </div>
                                <div class="input-group col-md-7">
                                    <div class="custom-file">
                                        <input type="file" accept="application/pdf" class="custom-file-input" id="doc_acta" name="doc_acta" aria-describedby="inputGroupFileAddon01" onchange="validar()">
                                        <label id="lbl_acta" class="custom-file-label" for="doc_acta">Acta de Nacimiento</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top:10px;text-align:left;">
                                <div class="input-group col-md-4">
                                    <label for="doc_curp">CURP:</label>
                                </div>
                                <div class="input-group col-md-7">
                                    <div class="custom-file">
                                        <input type="file" accept="application/pdf" class="custom-file-input" id="doc_curp" name="doc_curp" aria-describedby="inputGroupFileAddon01" onchange="validar()">
                                        <label id="lbl_curp" class="custom-file-label" for="doc_curp">CURP</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top:10px;text-align:left;">
                                <div class="input-group col-md-4">
                                    <label for="doc_rfc">RFC (Constancia de Situación Fiscal):</label>
                                </div>
                                <div class="input-group col-md-7">
                                    <div class="custom-file">
                                        <input type="file" accept="application/pdf" class="custom-file-input" id="doc_rfc" name="doc_rfc" aria-describedby="inputGroupFileAddon01" onchange="validar()">
                                        <label id="lbl_rfc" class="custom-file-label" for="doc_rfc">RFC</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top:10px;text-align:left;">
                                <div class="input-group col-md-4">
                                    <label for="doc_domicilio">Comprobante de Domicilio:</label>
                                </div>
                                <div class="input-group col-md-7">
                                    <div class="custom-file">
                                        <input type="file" accept="application/pdf" class="custom-file-input" id="doc_domicilio" name="doc_domicilio" aria-describedby="inputGroupFileAddon01" onchange="validar()">
                                        <label id="lbl_domicilio" class="custom-file-label">Domicilio</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top:10px;text-align:left;">
                                <div class="input-group col-md-4">
                                    <label for="doc_estudios">Comprobante de Ultimo Grado de Estudios:</label>
                                </div>
                                <div class="input-group col-md-7">
                                    <div class="custom-file">
                                        <input type="file" accept="application/pdf" class="custom-file-input" id="doc_estudios" name="doc_estudios" aria-describedby="inputGroupFileAddon01" onchange="validar()">
                                        <label id="lbl_estudios" class="custom-file-label" for="doc_estudios">Ultimo Grado de Estudios</label>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-guardar btn-style" type="submit" id="btn_document">Guardar</button>
                        </form>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="#">Datos Generales</a>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/js/survey/generate_p2_v3.js"></script>
<script src="<?= base_url() ?>/public/plugins/select2/js/select2.min.js"></script>

<?= $this->endSection() ?>