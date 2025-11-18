<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Prestamo de Equipo
<?= $this->endSection() ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2/css/select2.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<style>
    .extra {
        padding-top: 5px;
        padding-bottom: 5px;
    }
</style>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Administrar Prestamos</h1>
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
            <div class="card card-default collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">Alta de Prestamo</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="form_prestamos" method="post" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="col-md-2">
                                <label for="nomina">Nomina:</label>
                                <input class="form-control" type="number" min="1" name="nomina" id="nomina" onchange="limpiarError(this)">
                                <div id="error_nomina" class="text-danger"></div>
                            </div>
                            <div class="col-md-4">
                                <label for="usuario">Usuario:</label>
                                <select class="form-control" name="usuario" id="usuario" data-toggle="validation" data-required="true" data-message="Area." style="width: 100%;" onchange="limpiarError(this)">
                                    <option value=""></option>
                                    <?php foreach ($usuarios as $value) { ?>
                                        <option value="<?= $value->id_user ?>"><?= $value->user_name ?></option>
                                    <?php } ?>
                                </select>
                                <div id="error_usuario" class="text-danger"></div>
                            </div>
                            <div class="col-md-4" id="error_items">
                            </div>
                            <div class="col-md-1">
                                <button onclick="addItem()" type="button" class="btn btn-info" style="margin-top: 31px;"><i class="fas fa-plus-circle" style="margin-right: 10px;"></i> Añadir</button>
                            </div>
                            <div class="col-md-1">
                                <button id="btn_prestamos" type="submit" class="btn btn-guardar" style="margin-top: 31px;"><i class="fas fa-save" style="margin-right: 10px;"></i> Guardar</button>
                            </div>
                        </div>
                        <div id="div_items">
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <a href="#">Formulario Alta de Prestamos de Equipo</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Historial de Prestamos
                    </h3>
                </div>
                <div class="card-body">
                    <table id="tabla_prestamos" class="table table-bordered table-striped " role="grid" aria-describedby="vacaciones_info" style="width:100%" ref="">
                    </table>
                </div>

                <div class="card-footer">
                    <a href="#">Inventario </a>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="https://sie.grupowalworth.com/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/plugins/select2/js/select2.min.js"></script>
<script src="<?= base_url() ?>/public/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url() ?>/public/js/system/loan_system_v1.js"></script>
<?= $this->endSection() ?>