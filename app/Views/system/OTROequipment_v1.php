<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Administracion de Equipos
<?= $this->endSection() ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2/css/select2.min.css">
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<style>
    .form-row {
        padding: 10px 0 10px 0;
    }
</style>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Administracion de Equipos</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item active">Sistemas</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Asignacion de Equipos</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="form_asignar_equipo" method="post">
                        <div class="form-row">
                            <div class="col-md-3">
                                <label for="num_nomina">Nomina:</label>
                                <input type="number" name="num_nomina" id="num_nomina" class="form-control">
                                <div class="text-danger" id="error_num_nomina"></div>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Nombre Usuario</label>
                                <select id="id_user" name="id_user" class="form-control select2-hidden-accessible" style="width: 100%; height: calc(2.25rem + 2px);">
                                    <option value="">Seleccionar Opción...</option>
                                    <?php foreach ($data as $key => $usuario) {  ?>
                                        <option value="<?php echo $usuario->id_user; ?>"><?php echo $usuario->nombre; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="hidden" name="id_depto" id="id_depto">
                                <label for="depto">Departamento:</label>
                                <input type="text" name="depto" id="depto" class="form-control" readonly>
                            </div>
                            <div class="col-md-3" style="text-align: end;padding-top:2rem;">
                                <button type="button" class="btn btn-outline-info" id="btn_agregar_item">
                                    <i class="fas fa-plus-circle" style="margin-right: 10px;"></i>Agregar Equipo
                                </button>
                            </div>
                        </div>
                        <div id="error_item"></div>
                        <hr>
                        <div class="form-row">
                            <div class="col-md-3" style="margin-bottom: 5px;">
                                <label for="tipo_equipo_1">Tipo de Equipo:</label>
                                <select name="tipo_equipo_[]" id="tipo_equipo_1" class="form-control" onchange="seleccionarTipo(1)" required>
                                    <option value="">Opciones...</option>
                                    <option value="1">Laptop</option>
                                    <option value="2">Desktop</option>
                                    <option value="3">Tablet</option>
                                </select>
                                <div class="text-danger" id="error_tipo_equipo_1"></div>
                            </div>
                            <div id="div_campos_equipos_1" class="row"></div>
                        </div>
                        <div id="div_clones"></div>
                        <div class="footer" style="margin-top: 1rem;">
                            <button type="submit" id="btn_asignar_equipo" class="btn btn-guardar btn-lg">Asignar</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <a href="#">Asignacion</a>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="<?= base_url() ?>/public/plugins/select2/js/select2.min.js"></script>
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/js/system/equipament_v1.js"></script>
<?= $this->endSection() ?>