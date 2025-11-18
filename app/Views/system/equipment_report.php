<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Reportes de Equipos
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Reportes de Sistemas</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item active">Sistemas</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- CARDS -->
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Reportes</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button> -->
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form id="reportes" method="post">
                        <div class="col-md-12 form-row">
                            <h4 style="margin-right: 15px;">REPORTE DE EQUIPOS ASIGNADOS</h4><button type="submit" id="btn_reportes" name="btn_reportes" class="btn btn-guardar btn-lg">DESCARGAR</button>
                        </div>

                            <!-- <div class="form-group col-md-3">
                                <label for="fecha_inicio">Fecha Inicial:</label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" onchange="validar()">
                                <div id="error_fecha_inicio" class="text-danger"></div>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="fecha_fin">Fecha Final:</label>
                                <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" onchange="validar()">
                                <div id="error_fecha_fin" class="text-danger"></div>
                            </div>
                            <input type="hidden" id="opcion" value="9"> -->
                            <!-- <div class="form-group col-md-3">
                                <label for="opcion">Reporte por:</label>
                                <select name="opcion" id="opcion" class="form-control" onchange="tipoReporte()">
                                    <option value="">Seleccionar Opción...</option>
                                    <option value="0">Personas</option>
                                    <option value="1">Equipos</option>
                                </select>
                                <div id="error_opcion" class="text-danger"></div>
                            </div> -->
                            <div id="equipos" class="form-group col-md-3"></div>
                        <div class="footer">
                        </div>
                    </form>
                </div>
                <!--  /.card-body -->
                <div class="card-footer">
                    <a href="#">Recoleccion</a>
                </div>
            </div>
    </section>

</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/js/system/equipos_report_v1.js"></script>
<?= $this->endSection() ?>