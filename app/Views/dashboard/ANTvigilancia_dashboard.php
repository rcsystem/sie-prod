<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
DashBoard
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="row">
        <section class="content col-md-12">
            <div class="container-fluid">
                <!-- TABLE: LATEST ORDERS -->
                <div class="card collapsed-card">
                    <div class="card-header border-transparent">
                        <h3 class="card-title">Proveedores & Visitantes</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-plus"></i>
                            </button>
                            <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button> -->
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div style="padding: 1rem 1rem;">
                            <h4>Reporte</h4>
                            <form id="formReportes_visitas" method="post">
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label>Fecha Inicio</label>
                                        <input type="date" class="form-control rounded-0" id="fecha_inicial_V" name="fecha_inicial_V" value="" onchange="validar()">
                                        <div id="error_fecha_inicial_V" name="error_fecha_inicial_V" class="text-danger"></div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Fecha Final</label>
                                        <input type="date" class="form-control rounded-0" id="fecha_final_V" name="fecha_final_V" value="" onchange="validar()">
                                        <div id="error_fecha_final_V" name="error_fecha_final_V" class="text-danger"></div>
                                    </div>
                                    <div class="form-group col-md-4" style="text-align: right;">
                                        <button id="btn_Reportes_visitas" style="margin-top:26px;" type="submit" class="btn btn-guardar btn-lg">Generar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <hr>
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="tabla_proveedores_visitas" class=" m-0 table table-striped table-bordered nowrap" role="grid" aria-describedby="vacaciones_info" style="width:100%">

                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            <!-- <a href="javascript:void(0)" class="btn btn-sm btn-info float-left">Place New Order</a> -->
                            <a href="javascript:void(0)" class="btn btn-sm btn-secondary float-right">Ver las Ordenes</a>
                        </div>
                        <!-- /.card-footer -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
        </section>
        <section class="content col-md-12">
            <div class="container-fluid">
                <!-- TABLE: LATEST ORDERS -->
                <div class="card collapsed-card">
                    <div class="card-header border-transparent">
                        <h3 class="card-title">Horario Obscuro</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-plus"></i>
                            </button>
                            <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button> -->
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div style="padding: 1rem 1rem;">
                            <h4>Reporte</h4>
                            <form id="formReportes_tiempo" method="post">
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label>Fecha Inicio</label>
                                        <input type="date" class="form-control rounded-0" id="fecha_inicial" name="fecha_inicial" value="" onchange="validar()">
                                        <div id="error_fecha_inicial" name="error_fecha_inicial" class="text-danger"></div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Fecha Final</label>
                                        <input type="date" class="form-control rounded-0" id="fecha_final" name="fecha_final" value="" onchange="validar()">
                                        <div id="error_fecha_final" name="error_fecha_final" class="text-danger"></div>
                                    </div>
                                    <div class="form-group col-md-4" style="text-align: right;">
                                        <button id="btn_Reportes_tiempo" style="margin-top:26px;" type="submit" class="btn btn-guardar btn-lg">Generar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <hr>
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="tabla_tiempo_extra" class=" m-0 table table-striped table-bordered nowrap" role="grid" aria-describedby="vacaciones_info" style="width:100%">

                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            <!-- <a href="javascript:void(0)" class="btn btn-sm btn-info float-left">Place New Order</a> -->
                            <a href="javascript:void(0)" class="btn btn-sm btn-secondary float-right">Ver todas las Ordenes</a>
                        </div>
                        <!-- /.card-footer -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
        </section>
        <section class="content col-md-12">
            <div class="container-fluid">
                <!-- TABLE: LATEST ORDERS -->
                <div class="card">
                    <div class="card-header border-transparent">
                        <h3 class="card-title">Entradas y Salidas</h3>

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
                    <div class="card-body p-0">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="tabla_permisos" class=" m-0 table table-striped table-bordered nowrap" role="grid" aria-describedby="vacaciones_info" style="width:100%">

                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            <!-- <a href="javascript:void(0)" class="btn btn-sm btn-info float-left">Place New Order</a> -->
                            <a href="javascript:void(0)" class="btn btn-sm btn-secondary float-right">Ver todas las Ordenes</a>
                        </div>
                        <!-- /.card-footer -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->

            </div>
        </section>
    </div> <!-- /.row -->
    <!-- /.content -->
</div><!-- /.content-wrapper -->

<?= $this->endSection() ?>

<?= $this->section('js') ?>

<!-- ChartJS -->
<!-- <script src="<?= base_url() ?>/public/plugins/chart.js/Chart.min.js"></script> -->
<!-- Sparkline -->
<!-- <script src="<?= base_url() ?>/public/plugins/sparklines/sparkline.js"></script> -->
<!-- JQVMap -->
<!-- <script src="<?= base_url() ?>/public/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="<?= base_url() ?>/public/plugins/jqvmap/maps/jquery.vmap.usa.js"></script> -->
<!-- jQuery Knob Chart -->
<!-- <script src="<?= base_url() ?>/public/plugins/jquery-knob/jquery.knob.min.js"></script> -->
<!-- AdminLTE for demo purposes -->
<!-- <script src="<?= base_url() ?>/public/dist/js/demo.js"></script> -->
<!-- AdminLTE dashboard demo (This is only for demo purposes) 
<script src="<?= base_url() ?>/public/dist/js/pages/dashboard.js"></script>-->
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/js/dashboard/vigilancia_dashboard_v2.js"></script>
<?= $this->endSection() ?>