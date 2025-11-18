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

<style>

</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Reportes</h1>
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

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">Reportes Consulta Médica</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="form_reportes_consultas" method="post">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label>Fecha Inicio</label>
                                <input type="date" class="form-control rounded-0" id="fecha_inicial" onchange="validar()">
                                <div id="error_fecha_inicial" name="error_fecha_inicial" class="text-danger"></div>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Fecha Final</label>
                                <input type="date" class="form-control rounded-0" id="fecha_final" onchange="validar()">
                                <div id="error_fecha_final" name="error_fecha_final" class="text-danger"></div>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Tipo de Reporte:</label>
                                <select id="tipo" class="form-control">
                                    <option value="1">Todos</option>
                                    <option value="2">Nomina</option>
                                    <option value="3">Turno</option>
                                    <option value="4">Departamento</option>
                                    <option value="5">Tipo de Atencion</option>
                                    <option value="6">Clasificacion</option>
                                    <option value="7">Aparato y Sistema</option>
                                </select>
                                <div id="error_tipo" class="text-danger"></div>
                            </div>
                            <div id="div_2" class="form-group col-md-3" style="display: none;">
                                <label>Nomina:</label>
                                <input type="number" min="1" class="form-control" id="nomina">
                            </div>
                            <div id="div_3" class="form-group col-md-3" style="display: none;">
                                <label>Turno:</label>
                                <select id="turno" class="form-control">
                                    <option value="">Seleccionar Opción...</option>
                                    <optgroup label="SINDICALIZADO">
                                        <?php foreach ($turnoS as $key) { ?>
                                            <option value="<?php echo $key->id; ?>"><?php echo $key->name_turn; ?></option>
                                        <?php } ?>
                                    </optgroup>
                                    <optgroup label="ADMINISTRATIVO">
                                        <?php foreach ($turnoA as $key) { ?>
                                            <option value="<?php echo $key->id; ?>"><?php echo $key->name_turn; ?></option>
                                        <?php } ?>
                                    </optgroup>
                                </select>
                            </div>
                            <div id="div_4" class="form-group col-md-3" style="display: none;">
                                <label>Departamento:</label>
                                <select id="depto" id="depto" class="form-control select2bs4 select2-hidden-accessible" style="width: 100%; height: calc(2.25rem + 2px);">
                                    <option value="">Seleccionar Opción...</option>
                                    <?php foreach ($depto as $key => $usuario) {  ?>
                                        <option value="<?php echo $usuario->id_depto; ?>"><?php echo $usuario->departament; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div id="div_5" class="form-group col-md-3" style="display: none;">
                                <label for="tipo_atencion">Tipo de atención :</label>
                                <select id="tipo_atencion" class="form-control">
                                    <option value="">Opciones...</option>
                                    <option value="INICIAL">INICIAL</option>
                                    <option value="SUBSIGUIENTE">SUBSIGUIENTE</option>
                                    <option value="PROCEDIMIENTO">PROCEDIMIENTO</option>
                                </select>
                            </div>
                            <div id="div_6" class="form-group col-md-3" style="display: none;">
                                <label>Clasificacion:</label>
                                <select id="clasificacion" class="form-control">
                                    <option value="">Seleccionar Opción...</option>
                                    <?php foreach ($classification as $key => $usuario) {  ?>
                                        <option value="<?php echo $usuario->id_classification; ?>"><?php echo $usuario->classification; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div id="div_7" class="form-group col-md-3" style="display: none;">
                                <label>Aparato y Sistema:</label>
                                <select id="system" class="form-control">
                                    <option value="">Seleccionar Opción...</option>
                                    <?php foreach ($system as $key => $usuario) {  ?>
                                        <option value="<?php echo $usuario->id_system; ?>"><?php echo $usuario->system; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <!-- <div id="asignacion"></div> -->

                        <div class="row">
                            <button id="generar_reporte" style="margin-top:26px;" type="submit" class="btn btn-guardar btn-lg">Generar Reporte</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <a href="#">Servicio Médico</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">Reportes Incapacidad Médica</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="formReportes" method="post">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Fecha Inicio</label>
                                <input type="date" class="form-control rounded-0" id="fecha_inicial_2" onchange="validar_2()">
                                <div id="error_fecha_inicial_2" name="error_fecha_inicial" class="text-danger"></div>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Fecha Final</label>
                                <input type="date" class="form-control rounded-0" id="fecha_final_2" onchange="validar_2()">
                                <div id="error_fecha_final_2" name="error_fecha_final" class="text-danger"></div>
                            </div>
                            <div class="form-group col-md-4" style="text-align: right;">
                                <button id="generar_reporte_2" style="margin-top:26px;" type="submit" class="btn btn-guardar btn-lg">Generar Reporte</button>
                            </div>
                        </div>
                    </form>
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
<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/plugins/select2/js/select2.min.js"></script>
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/js/medical/reports_v1.js"></script>

<?= $this->endSection() ?>