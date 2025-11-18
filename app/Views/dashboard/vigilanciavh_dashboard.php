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
                <div class="card">
                    <div class="card-header border-transparent">
                        <h3 class="card-title">Entradas y Salidas</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
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

<!-- <script src="<?= base_url() ?>/public/dist/js/pages/dashboard.js"></script> -->
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/js/dashboard/vigilancia_dashboard_villahermosa_v1.js"></script>
<?= $this->endSection() ?>
