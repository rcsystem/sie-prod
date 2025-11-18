<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Servicio Médico
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2/css/select2.min.css">
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Consulta Medica</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item active">Servicio Médico</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Formulario de Consulta Medica</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body col-md-12">
                    <div class="container-fluid">
                        <form id="form_consulta_medica" method="post">
                            <div class="row">
                                <div class="form-group col-md-8">
                                    <div class="btn-group1 btn-group-toggle" data-toggle="buttons">
                                        <label id="tipo_datos_" class="btn btn-outline-primary">
                                            <input type="radio" id="datos_interno"> EMPLEADO INTERNO
                                        </label>
                                        <label id="tipo_datos" class="btn btn-outline-primary">
                                            <input type="radio" id="datos_externo"> CONTRATISTA / VISITA
                                        </label>
                                        <div id="error_datos" class="text-danger"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <input type="hidden" id="id_user">
                                <div class="form-group col-md-2">
                                    <label for="nomina">Numero de Empleado:</label>
                                    <input type="number" min="1" name="nomina" id="nomina" class="form-control" readonly>
                                    <div class="text-danger" id="error_nomina"></div>
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="depto">Departamento:</label>
                                    <input type="text" name="depto" id="depto" class="form-control" readonly>
                                    <input type="hidden" name="id_depto" id="id_depto">
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="puesto">Puesto:</label>
                                    <input type="text" name="puesto" id="puesto" class="form-control" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="nombre">Nombre:</label>
                                    <input type="text" name="nombre" id="nombre" class="form-control" readonly onchange="validar()">
                                    <div class="text-danger" id="error_nombre"></div>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="genero">Genero:</label>
                                    <select name="genero" id="genero" class="form-control" readonly onchange="validar()">
                                        <option value="">Opciones...</option>
                                        <option value="FEMENINO">FEMENINO</option>
                                        <option value="MASCULINO">MASCULINO</option>
                                        <option value="INDEFINIDO">INDEFINIDO</option>
                                    </select>
                                    <div class="text-danger" id="error_genero"></div>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="edad">Edad:</label>
                                    <input type="number" name="edad" id="edad" min="1" class="form-control" readonly onchange="validar()">
                                    <div class="text-danger" id="error_edad"></div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="escolaridad">Escolaridad:</label>
                                    <select class="form-control" id="escolaridad" name="escolaridad" readonly onchange="validar()">
                                        <option value="">Opciones...</option>
                                        <option value="PRIMARIA">PRIMARIA</option>
                                        <option value="SECUNDARIA">SECUNDARIA</option>
                                        <option value="BACHILLERATO GENERAL">BACHILLERATO GENERAL</option>
                                        <option value="BACHILLERATO TÉCNICO">BACHILLERATO TÉCNICO</option>
                                        <option value="LICENCIATURA">LICENCIATURA</option>
                                        <option value="INGENIERIA">INGENIERIA</option>
                                        <option value="ESPECIALIDAD">ESPECIALIDAD</option>
                                        <option value="MAESTRÍA">MAESTRÍA</option>
                                        <option value="DOCTORADO">DOCTORADO</option>
                                    </select>
                                    <div class="text-danger" id="error_escolaridad"></div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="supervisor">Supervisor:</label>
                                    <input type="hidden" name="id_supervisor" id="id_supervisor">
                                    <input type="text" name="supervisor" id="supervisor" class="form-control" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="antiguedad">Antigüedad Específica:</label>
                                    <input type="text" name="antiguedad" id="antiguedad" class="form-control" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="antiguedad_general">Antigüedad General:</label>
                                    <input type="text" name="antiguedad_general" id="antiguedad_general" class="form-control" readonly>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="turno">Turno:</label>
                                    <select name="turno" id="turno" class="form-control" readonly onchange="validar()">
                                    </select>
                                    <div class="text-danger" id="error_turno"></div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="planta">Planta:</label>
                                    <select name="planta" id="planta" class="form-control" onchange="validar()" disabled>
                                        <option value="">Opciones...</option>
                                        <option value="AXONE">AXONE</option>
                                        <option value="BIAS">BIAS</option>
                                        <option value="CONTRATISTA">CONTRATISTA</option>
                                        <option value="GPT">GPT</option>
                                        <option value="WALWORTH">WALWORTH</option>
                                    </select>
                                    <div class="text-danger" id="error_planta"></div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="tipo_atencion">Tipo de atención :</label>
                                    <select name="tipo_atencion" id="tipo_atencion" class="form-control" onchange="validar()">
                                        <option value="">Opciones...</option>
                                        <option value="INICIAL">INICIAL</option>
                                        <option value="SUBSIGUIENTE">SUBSIGUIENTE</option>
                                        <option value="PROCEDIMIENTO">PROCEDIMIENTO</option>
                                    </select>
                                    <div class="text-danger" id="error_tipo_atencion"></div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="procedimientos">Procedimientos:</label>
                                    <select name="procedimientos" id="procedimientos" class="form-control" onchange="validar()">
                                        <option value="">Opciones...</option>
                                        <?php foreach ($procedures as $key) { ?>
                                            <option value="<?php echo $key->id_procedures; ?>"><?php echo $key->procedures; ?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="text-danger" id="error_procedimientos"></div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="sistema">Aparatos y Sistemas:</label>
                                    <select name="sistema" id="sistema" class="form-control" onchange="validar()">
                                        <option value="">Opciones...</option>
                                        <?php foreach ($system as $key) { ?>
                                            <option value="<?php echo $key->id_system; ?>"><?php echo $key->system; ?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="text-danger" id="error_sistema"></div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="clasificacion">Clasificación:</label>
                                    <select name="clasificacion" id="clasificacion" class="form-control">
                                        <option value="">Opciones...</option>
                                        <?php foreach ($classification as $key) { ?>
                                            <option value="<?php echo $key->id_classification; ?>"><?php echo $key->classification; ?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="text-danger" id="error_clasificacion"></div>
                                </div>
                                <div class="form-group col-md-4" style="display: none;" id="div_tipo_lesion">
                                    <label for="tipo_lesion">Tipo de Lesión:</label>
                                    <select name="tipo_lesion" id="tipo_lesion" class="form-control" onchange="validar()">
                                        <option value="">Opciones...</option>
                                        <?php foreach ($type_of_injury as $key) { ?>
                                            <option value="<?php echo $key->id_injury; ?>"><?php echo $key->injury; ?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="text-danger" id="error_tipo_lesion"></div>
                                </div>
                                <div class="form-group col-md-4" style="display: none;" id="div_anatomical_area">
                                    <label for="anatomical_area">Area Anatomica:</label>
                                    <select name="anatomical_area" id="anatomical_area" class="form-control" onchange="validar()">
                                        <option value="">Opciones...</option>
                                        <?php foreach ($anatomical_area as $key) { ?>
                                            <option value="<?php echo $key->id_anatomical_area; ?>"><?php echo $key->anatomical_area; ?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="text-danger" id="error_anatomical_area"></div>
                                </div>
                                <div class="form-group col-md-10">
                                    <label for="alergias">Alergias</label>
                                    <textarea name="alergias" id="alergias" cols="30" rows="3" class="form-control"></textarea>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="diagnostico">Diagnostico</label>
                                    <textarea name="diagnostico" id="diagnostico" cols="30" rows="3" class="form-control"></textarea>
                                    <div id="error_diagnostico" class="text-danger"></div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="form-group col-md-2">
                                    <h4>TRATAMIENTO</h4>
                                </div>
                                <div id="error_item" class="form-group col-md-6"></div>
                                <div class="form-group col-md-4" style="text-align: right;">
                                    <button id="btn_agregar_item" class="btn btn-guardar btn-style" style="background-color:#0056B3!important;"><i class="fas fa-prescription-bottle-alt"></i>&nbsp;&nbsp;&nbsp;Agregar Medicamento</button>
                                </div>
                            </div>
                            <div id="items_duplica">
                            </div>
                            <div id="asignacion"></div>
                            <hr>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="cita">Proxima Cita:</label>
                                    <input type="date" name="cita" id="cita" class="form-control">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="telefono">Telefono:</label>
                                    <input type="text" name="telefono" id="telefono" class="form-control">
                                </div>
                                <div class="col-md-5">
                                <label for="telefono">Motivo Relacionado:</label>
                                <input type="hidden" name="motivo_comun" id="motivo_comun">
                                    <div class="btn-group1 btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-outline-primary btn-opcion">
                                            <input type="radio" id="estres_laboral"> ESTRÉS LABORAL
                                        </label>
                                        <label class="btn btn-outline-primary btn-opcion">
                                            <input type="radio" id="estres_personal"> ESTRÉS PERSONAL
                                        </label>
                                        <label class="btn btn-outline-primary btn-opcion">
                                            <input type="radio" id="egronomia">  EGRONOMÍA
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="observaciones">Observaciones</label>
                                    <textarea name="observaciones" id="observaciones" cols="30" rows="4" class="form-control" onchange="validar()"></textarea>
                                    <div id="error_observaciones" class="text-danger"></div>
                                </div>
                            </div>
                            <div class="row">
                                <button type="submit" id="btn_consulta_medica" class="btn btn-guardar btn-lg btn-block">Generar</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="#">Servicio Médico</a>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/plugins/select2/js/select2.min.js"></script>
<script src="<?= base_url() ?>/public/js/medical/medical_consultation_v1.js"></script>
<?= $this->endSection() ?>