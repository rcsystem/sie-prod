<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Estacionamiento
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Reportes</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item active">Estacionamiento</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Reportes Registros de Estacionamiento</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="form_reporte" method="post">
                        <div class="form-row">
                            <div class="col-md-3">
                                <label>Fecha Inicio</label>
                                <input type="date" class="form-control" id="fecha_inicial" onchange="limpiarError('fecha_inicial')">
                                <div id="error_fecha_inicial" class="text-danger"></div>
                            </div>
                            <div class="col-md-3">
                                <label>Fecha Final</label>
                                <input type="date" class="form-control" id="fecha_final" onchange="limpiarError('fecha_final')">
                                <div id="error_fecha_final" class="text-danger"></div>
                            </div>
                            <div class="col-md-3">
                                <label>Vehiculo:</label>
                                <select class="form-control" id="opcion" onchange="limpiarError('opcion')">
                                    <option value="">Opciones...</option>
                                    <option value="1">Automovil</option>
                                    <option value="2">Motocicleta</option>
                                    <option value="3">Bicicleta</option>
                                </select>
                                <div id="error_opcion" class="text-danger"></div>
                            </div>
                            <div class="col-md-3" style="text-align: end;">
                                <button id="generar_reporte" style="margin-top:26px;" type="submit" class="btn btn-guardar btn-lg">Generar Reporte</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <a href="#">Estacionamiento</a>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->
<!-- <script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script> -->
<script src="<?= base_url() ?>/public/js/parking/reports.js"></script>
<?= $this->endSection() ?>