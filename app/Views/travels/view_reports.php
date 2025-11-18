<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Reportes Viáticos & Gastos
<?= $this->endSection() ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2/css/select2.min.css">
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<style>
    .icon-nav {
        margin-right: 5px;
    }

    .btn-locate-center {
        margin-top: 2rem;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="far fa-file-excel "></i>Reporte de Viáticos & Gatos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item"><a href="#">Viajes</a></li>
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
                    <h3 class="card-title">Tipos de Reporte</h3>
                    <div class="card-tools">
                    </div>
                </div>
                <div class="card-body">
                    <form id="form_report_by_user" method="post">
                        <div class="row">
                            <H5><i class="fas fa-user-tie icon-nav"></i> POR USUARIO</H5>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="name_by_user">Nombre de Empleado:</label>
                                <select name="name_by_user" id="name_by_user" class="form-control select2bs4 select2-hidden-accessible" style="width: 100%; height: calc(2.25rem + 2px);" onchange="validarInput(this)">
                                    <option value="">Seleccionar...</option>
                                    <?php foreach ($usuarios as $usuario) {  ?>
                                        <option value="<?php echo $usuario->id_user; ?>"><?php echo $usuario->user_name; ?></option>
                                    <?php } ?>
                                </select>
                                <div id="error_name_by_user" class="text-danger"></div>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" id="btn_report_by_user" class="btn btn-locate-center btn-outline-guardar"><i class="far fa-file-excel icon-nav"></i>GENERAR</button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <form id="form_report_by_date" method="post">
                        <div class="row">
                            <H5><i class="fas fa-calendar-alt icon-nav"></i> POR TIPO Y FECHA</H5>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="tipo_by_date">Tipo de reporte</label>
                                <select name="tipo_by_date" id="tipo_by_date" class="form-control" onchange="validarInput(this)">
                                    <option value="">Seleccionar</option>
                                    <option value="1">VIATICOS</option>
                                    <option value="2">GASTOS</option>
                                    <option value="3">TODOS</option>
                                </select>
                                <div id="error_tipo_by_date" class="text-danger"></div>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="fecha_inicial_by_date">Fecha Inicio</label>
                                <input type="date" class="form-control" id="fecha_inicial_by_date" name="fecha_inicial_by_date" onchange="validarInput(this)">
                                <div id="error_fecha_inicial_by_date" class="text-danger"></div>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="fecha_final_by_date">Fecha Final</label>
                                <input type="date" class="form-control" id="fecha_final_by_date" name="fecha_final_by_date" onchange="validarInput(this)">
                                <div id="error_fecha_final_by_date" class="text-danger"></div>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" id="btn_report_by_date" class="btn btn-locate-center btn-outline-guardar"><i class="far fa-file-excel icon-nav"></i>GENERAR</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-footer">
                    <a href="#">Reporte de Viáticos & Gastos </a>
                </div>
            </div>
            <!-- NUEVO REPORTE -->
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Reporte Comparativo</h3>
                    <div class="card-tools">
                    </div>
                </div>
                <div class="card-body">
              
                    <form id="form_report_date" method="post">
                        <div class="row">
                            <H5><i class="fas fa-calendar-alt icon-nav"></i> POR TIPO Y FECHA</H5>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="tipo_date">Tipo de reporte</label>
                                <select name="type_report" id="type_report" class="form-control" onchange="validarInput(this)">
                                    <option value="">Seleccionar</option>
                                    <option value="1">VIATICOS</option>
                                    <option value="2">GASTOS</option>
                                    <option value="3">TODOS</option>
                                </select>
                                <div id="error_report" class="text-danger"></div>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="date_initial_report">Fecha Inicio</label>
                                <input type="date" class="form-control" id="date_initial_report" name="date_initial_report" onchange="validarInput(this)">
                                <div id="error_date_initial_report" class="text-danger"></div>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="date_term_report">Fecha Final</label>
                                <input type="date" class="form-control" id="date_term_report" name="date_term_report" onchange="validarInput(this)">
                                <div id="error_date_term_report" class="text-danger"></div>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" id="btn_report" class="btn btn-locate-center btn-outline-guardar"><i class="far fa-file-excel icon-nav"></i>GENERAR</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-footer">
                    <a href="#">Reporte de Viáticos & Gastos </a>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/plugins/select2/js/select2.min.js"></script>
<script src="<?= base_url() ?>/public/js/travels/reports_v1.js"></script>
<?= $this->endSection() ?>