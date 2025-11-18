<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Reportes Contratos Temporales
<?= $this->endSection() ?>

<?= $this->section('content') ?>
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
                        <li class="breadcrumb-item"><a>Usuarios</a></li>
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
                    <h3 class="card-title">Reporte de Contratos Temporales</h3>
                    <div class="card-tools">
                        <!--<button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button> -->
                    </div>
                </div>
                <div class="card-body">
                    <form id="formReportes" method="post">

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="categoria">Categoria</label>
                                <select name="categoria" id="categoria" class="form-control rounded-0"  onchange="validar()">
                                    <option value="">Seleccionar</option>
                                    <option value="1">Administrativos</option>
                                    <option value="2">Sindicalizados</option>
                                    <option value="3">Grupo Walworth</option>
                                    <option value="4">Todos los Contratos</option>
                                </select>
                                <div id="error_categoria" name="error_categoria" class="text-danger"></div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="cantidad">Fecha Inicio</label>
                                <input type="date" class="form-control rounded-0" id="fecha_inicial" name="fecha_inicial" value=""  onchange="validar()">
                                <div id="error_fecha_inicial" name="error_fecha_inicial" class="text-danger"></div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="minimo">Fecha Final</label>
                                <input type="date" class="form-control rounded-0" id="fecha_final" name="fecha_final" value=""  onchange="validar()">
                                <div id="error_fecha_final" name="error_fecha_final" class="text-danger"></div>
                            </div>
                        </div>
                        <button id="generar_reporte" type="submit" class="btn btn-guardar btn-lg">Generar</button>
                    </form>
                </div>

                <div class="card-footer">
                    <a href="#">Reporte de Contratos Temporales</a>
                </div>
            </div>
        </div>
    </section>

</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/js/users/reports_contract_v1.js"></script>

<?= $this->endSection() ?>