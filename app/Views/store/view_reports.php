<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Almacen Materia Prima
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<style>
    .btn-primary:not(:disabled):not(.disabled).active,
    .btn-primary:not(:disabled):not(.disabled):active,
    .show>.btn-primary.dropdown-toggle {
        color: #fff;
        background-color: #022d5c;
        border-color: #022d5c;
    }

    .active {
        border-color: #28a745 !important;
        background-color: #dee2e6;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.18), 0 3px 6px rgba(0, 0, 0, 0.2);
    }

    .card {
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15), 0 2px 5px rgba(0, 0, 0, 0.2);
        -webkit-transition: all 0.5s ease;
        -moz-transition: all 0.5s ease;
        -o-transition: all 0.5s ease;
        transition: all 0.5s ease;
    }

    .btn-check {
        position: absolute;
        clip: rect(0, 0, 0, 0);
        pointer-events: none;
    }

    .btn-primary:not(:disabled):not(.disabled).active,
    .btn-primary:not(:disabled):not(.disabled):active,
    .show>.btn-primary {
        color: #fff;
        background-color: #062a50;
        border-color: #062a50 !important;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Reportes de Almacen y Materia Prima</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item">Almacen Materia Prima</li>
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
                    <h3 class="card-title">Generar Reporte</h3>

                </div>
                <div class="card-body col-md-12">
                    <div class="container-fluid">
                        <div class="pb-5">
                            <h3 class="card-title">Reporte Vale PDF</h3>
                        </div>
                        <form id="reporte_almacen_pdf" method="post">
                            <div class="form-row">
                                <div id="reporte_pdf_1" class="form-group col-md-3">
                                    <label for="fecha_inicio_pdf">Fecha de Inicio</label>
                                    <input type="date" class="form-control rounded-0" id="fecha_inicio_pdf" name="fecha_inicio_pdf" value="" onchange="validar()">
                                    <div id="error_fecha_inicio_pdf" class="text-danger"></div>
                                </div>
                                <div id="reporte_pdf_2" class="form-group col-md-3">
                                    <label for="fecha_fin_pdf">Fecha Final</label>
                                    <input type="date" class="form-control rounded-0" id="fecha_fin_pdf" name="fecha_fin_pdf" value="" onchange="validar()">
                                    <div id="error_fecha_fin_pdf" class="text-danger"></div>
                                </div>
                                <div id="reporte_pdf_3" class="form-group col-md-3">
                                    <button id="btn_reporte_pdf" type="submit" class="btn btn-guardar btn-lg" style=" margin-top: 27px;">Generar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <hr>
                    <div class="container-fluid">
                        <div class="pb-5">
                            <h3 class="card-title">Generar Reporte Excel</h3>
                        </div>
                        <form id="reporte_almacen" method="post">
                            <div class="form-row">
                                <div id="opciones" class="form-group col-md-3">
                                    <label for="fecha_inicio">Fecha de Inicio</label>
                                    <input type="date" class="form-control rounded-0" id="fecha_inicio" name="fecha_inicio" value="" onchange="validar()">
                                    <div id="error_fecha_inicio" class="text-danger"></div>
                                </div>
                                <div id="opciones2" class="form-group col-md-3">
                                    <label for="fecha_fin">Fecha Final</label>
                                    <input type="date" class="form-control rounded-0" id="fecha_fin" name="fecha_fin" value="" onchange="validar()">
                                    <div id="error_fecha_fin" class="text-danger"></div>
                                </div>
                                <div id="opciones2" class="form-group col-md-3">
                                    <button id="btn_reporte_almacen" type="submit" class="btn btn-guardar btn-lg" style=" margin-top: 27px;">Generar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="#">Almacen Materia Prima</a>
                </div>
            </div>
        </div>
    </section>

    <input type="hidden" name="tipo" id="tipo" value="<?= session()->type_of_employee ?>">
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<!-- AdminLTE for demo purposes  -->
<script src="<?= base_url() ?>/public/js/store/reports_v1.js"></script>


<?= $this->endSection() ?>